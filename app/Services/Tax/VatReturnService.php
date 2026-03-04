<?php

namespace App\Services\Tax;

use App\Models\Tax\VatReturn;
use App\Models\Tax\TaxMapping;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class VatReturnService extends BaseTaxService
{
    protected $outputAccounts = [];
    protected $inputAccounts = [];
    protected $exemptAccounts = [];

    public function __construct(int $companyId)
    {
        parent::__construct($companyId);
        
        // Load account mappings
        $this->outputAccounts = TaxMapping::getVatOutputAccounts($companyId);
        $this->inputAccounts = TaxMapping::getVatInputAccounts($companyId);
    }

    /**
     * Calculate VAT return for a period
     */
    public function calculate(string $periodStart, string $periodEnd): array
    {
        $this->startDate = $periodStart;
        $this->endDate = $periodEnd;

        // Get account balances
        $outputBalances = $this->getAccountBalances($this->outputAccounts, $periodStart, $periodEnd);
        $inputBalances = $this->getAccountBalances($this->inputAccounts, $periodStart, $periodEnd);

        // Calculate totals
        $outputVat = 0;
        foreach ($outputBalances as $balance) {
            $outputVat += $balance['credit'] - $balance['debit']; // VAT Output is credit balance
        }

        $inputVat = 0;
        foreach ($inputBalances as $balance) {
            $inputVat += $balance['debit'] - $balance['credit']; // VAT Input is debit balance
        }

        $vatRate = $this->taxSettings->getVatRate();
        $vatPayable = max(0, $outputVat - $inputVat);
        $vatRefundable = $inputVat > $outputVat ? $inputVat - $outputVat : 0;

        // Build detailed breakdown
        $details = [];
        foreach ($outputBalances as $code => $balance) {
            $details['output'][] = [
                'code' => $code,
                'name' => $balance['name'],
                'amount' => $balance['credit'] - $balance['debit'],
                'vat' => ($balance['credit'] - $balance['debit']) * ($vatRate / (100 + $vatRate)),
            ];
        }

        foreach ($inputBalances as $code => $balance) {
            $details['input'][] = [
                'code' => $code,
                'name' => $balance['name'],
                'amount' => $balance['debit'] - $balance['credit'],
                'vat' => ($balance['debit'] - $balance['credit']) * ($vatRate / (100 + $vatRate)),
            ];
        }

        return [
            'period' => [
                'start' => $periodStart,
                'end' => $periodEnd,
                'name' => date('F Y', strtotime($periodStart)),
            ],
            'output_vat' => round($outputVat, 2),
            'input_vat' => round($inputVat, 2),
            'vat_payable' => round($vatPayable, 2),
            'vat_refundable' => round($vatRefundable, 2),
            'vat_rate' => $vatRate,
            'tax_fraction' => round($vatRate / (100 + $vatRate), 4),
            'details' => $details,
            'output_accounts' => $this->outputAccounts,
            'input_accounts' => $this->inputAccounts,
            'calculated_at' => now()->toDateTimeString(),
        ];
    }

    /**
     * Save VAT return
     */
    public function saveReturn(array $data, int $userId): VatReturn
    {
        $returnNo = VatReturn::generateReturnNo($this->companyId, $data['period_start']);

        return VatReturn::create([
            'company_id' => $this->companyId,
            'period_start' => $data['period_start'],
            'period_end' => $data['period_end'],
            'return_no' => $returnNo,
            'filing_date' => $data['filing_date'] ?? now(),
            'status' => 'DRAFT',
            'output_vat' => $data['output_vat'],
            'input_vat' => $data['input_vat'],
            'vat_payable' => $data['vat_payable'],
            'vat_rate' => $data['vat_rate'],
            'output_accounts' => $this->outputAccounts,
            'input_accounts' => $this->inputAccounts,
            'metadata' => [
                'details' => $data['details'] ?? [],
                'notes' => $data['notes'] ?? null,
            ],
            'created_by' => $userId,
        ]);
    }

    /**
     * Generate VAT7 PDF
     */
    public function generateVat7Pdf(VatReturn $vatReturn)
    {
        $data = [
            'return' => $vatReturn,
            'company' => $vatReturn->company,
            'period' => $vatReturn->period_start->format('F Y'),
            'output_details' => $vatReturn->metadata['details']['output'] ?? [],
            'input_details' => $vatReturn->metadata['details']['input'] ?? [],
            'generated_at' => now(),
        ];

        $pdf = Pdf::loadView('modules.tax.print.vat7', $data);
        return $pdf->download("VAT7_{$vatReturn->return_no}.pdf");
    }

    /**
     * Export VAT return to Excel (CSV format)
     */
    /**
 * Export VAT return to CSV (custom format)
 */
public function exportReturnToCsv(VatReturn $vatReturn): string
{
    $filename = "VAT7_{$vatReturn->return_no}_" . date('YmdHis') . ".csv";

    $data = [
        'headers' => ['Code', 'Account Name', 'Amount', 'VAT Component'],
        'rows' => [],
        'summary' => [
            'Period' => $vatReturn->period_start->format('d/m/Y') . ' - ' . $vatReturn->period_end->format('d/m/Y'),
            'Return No' => $vatReturn->return_no,
            'Output VAT' => $this->formatCurrency($vatReturn->output_vat),
            'Input VAT' => $this->formatCurrency($vatReturn->input_vat),
            'VAT Payable' => $this->formatCurrency($vatReturn->vat_payable),
            'VAT Rate' => $vatReturn->vat_rate . '%',
        ],
    ];

    // Add output details
    if (!empty($vatReturn->metadata['details']['output'])) {
        $data['rows'][] = ['OUTPUT VAT'];
        $data['rows'][] = ['Code', 'Account Name', 'Amount', 'VAT'];
        foreach ($vatReturn->metadata['details']['output'] as $item) {
            $data['rows'][] = [
                $item['code'],
                $item['name'],
                $this->formatCurrency($item['amount']),
                $this->formatCurrency($item['vat']),
            ];
        }
        $data['rows'][] = [];
    }

    // Add input details
    if (!empty($vatReturn->metadata['details']['input'])) {
        $data['rows'][] = ['INPUT VAT'];
        $data['rows'][] = ['Code', 'Account Name', 'Amount', 'VAT'];
        foreach ($vatReturn->metadata['details']['input'] as $item) {
            $data['rows'][] = [
                $item['code'],
                $item['name'],
                $this->formatCurrency($item['amount']),
                $this->formatCurrency($item['vat']),
            ];
        }
        $data['rows'][] = [];
    }

    return $this->exportToCsv($data, $filename);
}
}