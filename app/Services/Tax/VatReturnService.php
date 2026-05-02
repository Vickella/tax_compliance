<?php

namespace App\Services\Tax;

use App\Models\Tax\VatReturn;
use App\Models\Tax\TaxMapping;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class VatReturnService extends BaseTaxService
{
    protected array $outputAccounts = [];
    protected array $inputAccounts = [];

    public function __construct(int $companyId)
    {
        parent::__construct($companyId);

        $this->outputAccounts = TaxMapping::getVatOutputAccounts($companyId);
        $this->inputAccounts = TaxMapping::getVatInputAccounts($companyId);
    }

    /**
     * Calculate VAT return for a period.
     *
     * This intentionally keeps the net VAT signed:
     *   positive = payable
     *   negative = refundable
     *   zero     = nil return
     */
    public function calculate(string $periodStart, string $periodEnd): array
    {
        $this->startDate = $periodStart;
        $this->endDate = $periodEnd;

        $outputBalances = $this->getAccountBalances($this->outputAccounts, $periodStart, $periodEnd);
        $inputBalances = $this->getAccountBalances($this->inputAccounts, $periodStart, $periodEnd);

        $outputVat = 0.0;
        $taxableSales = 0.0;
        $details = [
            'output' => [],
            'input' => [],
        ];

        foreach ($outputBalances as $code => $balance) {
            $vatAmount = (float) $balance['credit'] - (float) $balance['debit'];

            if (abs($vatAmount) > 0.004) {
                $outputVat += $vatAmount;
                $taxableSales += max(0, (float) $balance['credit']);

                $details['output'][] = [
                    'code' => $code,
                    'name' => $balance['name'],
                    'amount' => round($vatAmount, 2),
                    'vat' => round($vatAmount, 2),
                ];
            }
        }

        $inputVat = 0.0;
        $taxablePurchases = 0.0;

        foreach ($inputBalances as $code => $balance) {
            $vatAmount = (float) $balance['debit'] - (float) $balance['credit'];

            if (abs($vatAmount) > 0.004) {
                $inputVat += $vatAmount;
                $taxablePurchases += max(0, (float) $balance['debit']);

                $details['input'][] = [
                    'code' => $code,
                    'name' => $balance['name'],
                    'amount' => round($vatAmount, 2),
                    'vat' => round($vatAmount, 2),
                ];
            }
        }

        $vatRate = (float) $this->taxSettings->getVatRate();
        $netVat = round($outputVat - $inputVat, 2);
        $vatPayable = max(0, $netVat);
        $vatRefundable = max(0, -$netVat);
        $position = $netVat > 0 ? 'PAYABLE' : ($netVat < 0 ? 'REFUNDABLE' : 'NIL');

        return [
            'period' => [
                'start' => $periodStart,
                'end' => $periodEnd,
                'name' => date('F Y', strtotime($periodStart)),
            ],
            'vat_rate' => $vatRate,
            'taxable_sales' => round($taxableSales, 2),
            'output_vat' => round($outputVat, 2),
            'taxable_purchases' => round($taxablePurchases, 2),
            'input_vat' => round($inputVat, 2),
            'net_vat' => $netVat,
            'net_vat_payable' => $netVat,
            'vat_payable' => $vatPayable,
            'vat_refundable' => $vatRefundable,
            'vat_position' => $position,
            'output_tax' => round($outputVat, 2),
            'input_tax' => round($inputVat, 2),
            'tax_fraction' => $vatRate > 0 ? round($vatRate / (100 + $vatRate), 6) : 0,
            'details' => $details,
            'calculated_at' => now()->toDateTimeString(),
        ];
    }

    public function saveReturn(array $data, int $userId): VatReturn
    {
        Log::info('VatReturnService::saveReturn called', [
            'companyId' => $this->companyId,
            'userId' => $userId,
            'period_start' => $data['period_start'],
            'period_end' => $data['period_end'],
        ]);

        $netVat = round((float) ($data['net_vat'] ?? $data['net_vat_payable'] ?? 0), 2);

        $saveData = [
            'company_id' => $this->companyId,
            'period_start' => $data['period_start'],
            'period_end' => $data['period_end'],
            'vat_rate' => $data['vat_rate'],
            'taxable_sales' => $data['taxable_sales'] ?? 0,
            'output_vat' => $data['output_vat'] ?? 0,
            'taxable_purchases' => $data['taxable_purchases'] ?? 0,
            'input_vat' => $data['input_vat'] ?? 0,
            'net_vat_payable' => $netVat,
            'notes' => $data['notes'] ?? null,
            'source_snapshot' => json_encode([
                'details' => $data['details'] ?? [],
                'output_accounts' => $this->outputAccounts,
                'input_accounts' => $this->inputAccounts,
                'vat_position' => $netVat > 0 ? 'PAYABLE' : ($netVat < 0 ? 'REFUNDABLE' : 'NIL'),
                'vat_payable' => max(0, $netVat),
                'vat_refundable' => max(0, -$netVat),
                'calculation_date' => now()->toDateTimeString(),
            ]),
            'return_type' => 'VAT7',
            'status' => 'DRAFT',
            'output_tax' => $data['output_vat'] ?? 0,
            'input_tax' => $data['input_vat'] ?? 0,
            'net_vat' => $netVat,
            'generated_at' => now(),
        ];

        if (isset($data['period_id'])) {
            $saveData['period_id'] = $data['period_id'];
        }

        return VatReturn::create($saveData);
    }

    public function generateVat7Pdf(VatReturn $vatReturn)
    {
        $snapshot = json_decode($vatReturn->source_snapshot, true) ?? [];

        $data = [
            'return' => $vatReturn,
            'company' => $vatReturn->company,
            'period' => $vatReturn->period_start->format('F Y'),
            'output_details' => $snapshot['details']['output'] ?? [],
            'input_details' => $snapshot['details']['input'] ?? [],
            'vat_position' => $snapshot['vat_position'] ?? ($vatReturn->net_vat_payable > 0 ? 'PAYABLE' : ($vatReturn->net_vat_payable < 0 ? 'REFUNDABLE' : 'NIL')),
            'vat_payable' => $snapshot['vat_payable'] ?? max(0, $vatReturn->net_vat_payable),
            'vat_refundable' => $snapshot['vat_refundable'] ?? max(0, -$vatReturn->net_vat_payable),
            'generated_at' => now(),
        ];

        $pdf = Pdf::loadView('modules.tax.print.vat7', $data);
        return $pdf->download("VAT7_{$vatReturn->id}.pdf");
    }

    public function exportReturnToCsv(VatReturn $vatReturn): string
    {
        $filename = "VAT7_{$vatReturn->id}_" . date('YmdHis') . ".csv";
        $snapshot = json_decode($vatReturn->source_snapshot, true) ?? [];
        $position = $snapshot['vat_position'] ?? ($vatReturn->net_vat_payable > 0 ? 'PAYABLE' : ($vatReturn->net_vat_payable < 0 ? 'REFUNDABLE' : 'NIL'));

        $data = [
            'headers' => ['Code', 'Account Name', 'Amount', 'VAT Component'],
            'rows' => [],
            'summary' => [
                'Period' => $vatReturn->period_start->format('d/m/Y') . ' - ' . $vatReturn->period_end->format('d/m/Y'),
                'Return ID' => $vatReturn->id,
                'Output VAT' => $this->formatCurrency($vatReturn->output_vat),
                'Input VAT' => $this->formatCurrency($vatReturn->input_vat),
                'Net VAT Position' => $position,
                'VAT Payable' => $this->formatCurrency(max(0, $vatReturn->net_vat_payable)),
                'VAT Refundable' => $this->formatCurrency(max(0, -$vatReturn->net_vat_payable)),
                'VAT Rate' => $vatReturn->vat_rate . '%',
            ],
        ];

        if (!empty($snapshot['details']['output'])) {
            $data['rows'][] = ['OUTPUT VAT'];
            $data['rows'][] = ['Code', 'Account Name', 'Amount', 'VAT'];
            foreach ($snapshot['details']['output'] as $item) {
                $data['rows'][] = [$item['code'], $item['name'], $this->formatCurrency($item['amount']), $this->formatCurrency($item['vat'])];
            }
            $data['rows'][] = [];
        }

        if (!empty($snapshot['details']['input'])) {
            $data['rows'][] = ['INPUT VAT'];
            $data['rows'][] = ['Code', 'Account Name', 'Amount', 'VAT'];
            foreach ($snapshot['details']['input'] as $item) {
                $data['rows'][] = [$item['code'], $item['name'], $this->formatCurrency($item['amount']), $this->formatCurrency($item['vat'])];
            }
            $data['rows'][] = [];
        }

        return $this->exportToCsv($data, $filename);
    }
}
