<?php

namespace App\Services\Tax;

use App\Models\Tax\VatReturn;
use App\Models\Tax\TaxMapping;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        $taxableSales = 0;
        foreach ($outputBalances as $balance) {
            $outputVat += $balance['credit'] - $balance['debit'];
            // You might need to calculate taxable sales differently based on your data
            $taxableSales += $balance['credit']; // Adjust this logic as needed
        }

        $inputVat = 0;
        $taxablePurchases = 0;
        foreach ($inputBalances as $balance) {
            $inputVat += $balance['debit'] - $balance['credit'];
            $taxablePurchases += $balance['debit']; // Adjust this logic as needed
        }

        $vatRate = $this->taxSettings->getVatRate();
        $netVatPayable = max(0, $outputVat - $inputVat);

        // Build detailed breakdown
        $details = [];
        foreach ($outputBalances as $code => $balance) {
            $amount = $balance['credit'] - $balance['debit'];
            if ($amount != 0) {
                $details['output'][] = [
                    'code' => $code,
                    'name' => $balance['name'],
                    'amount' => $amount,
                    'vat' => $amount,
                ];
            }
        }

        foreach ($inputBalances as $code => $balance) {
            $amount = $balance['debit'] - $balance['credit'];
            if ($amount != 0) {
                $details['input'][] = [
                    'code' => $code,
                    'name' => $balance['name'],
                    'amount' => $amount,
                    'vat' => $amount,
                ];
            }
        }

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
            'net_vat_payable' => round($netVatPayable, 2),
            'output_tax' => round($outputVat, 2),
            'input_tax' => round($inputVat, 2),
            'net_vat' => round($netVatPayable, 2),
            'tax_fraction' => round($vatRate / (100 + $vatRate), 4),
            'details' => $details,
            'calculated_at' => now()->toDateTimeString(),
        ];
    }

    /**
     * Save VAT return
     */
    public function saveReturn(array $data, int $userId): VatReturn
    {
        Log::info('VatReturnService::saveReturn called', [
            'companyId' => $this->companyId,
            'userId' => $userId,
            'period_start' => $data['period_start'],
            'period_end' => $data['period_end']
        ]);

        try {
            // Prepare data for database
            $saveData = [
                'company_id' => $this->companyId,
                'period_start' => $data['period_start'],
                'period_end' => $data['period_end'],
                'vat_rate' => $data['vat_rate'],
                'taxable_sales' => $data['taxable_sales'] ?? 0,
                'output_vat' => $data['output_vat'],
                'taxable_purchases' => $data['taxable_purchases'] ?? 0,
                'input_vat' => $data['input_vat'],
                'net_vat_payable' => $data['net_vat_payable'] ?? $data['vat_payable'],
                'notes' => $data['notes'] ?? null,
                'source_snapshot' => json_encode([
                    'details' => $data['details'] ?? [],
                    'output_accounts' => $this->outputAccounts,
                    'input_accounts' => $this->inputAccounts,
                    'calculation_date' => now()->toDateTimeString(),
                ]),
                'return_type' => 'VAT7',
                'status' => 'DRAFT',
                'output_tax' => $data['output_vat'],
                'input_tax' => $data['input_vat'],
                'net_vat' => $data['net_vat_payable'] ?? $data['vat_payable'],
                'generated_at' => now(),
            ];

            // Add period_id if available
            if (isset($data['period_id'])) {
                $saveData['period_id'] = $data['period_id'];
            }

            $vatReturn = VatReturn::create($saveData);

            Log::info('VAT return created successfully', [
                'id' => $vatReturn->id,
                'company_id' => $vatReturn->company_id
            ]);

            return $vatReturn;

        } catch (\Exception $e) {
            Log::error('Failed to create VAT return in service', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Generate VAT7 PDF
     */
    /**
 * Generate VAT7 PDF
 */
public function generateVat7Pdf(VatReturn $vatReturn)
{
    $snapshot = json_decode($vatReturn->source_snapshot, true) ?? [];
    
    $data = [
        'return' => $vatReturn,
        'company' => $vatReturn->company, // This will now work with the fixed relationship
        'period' => $vatReturn->period_start->format('F Y'),
        'output_details' => $snapshot['details']['output'] ?? [],
        'input_details' => $snapshot['details']['input'] ?? [],
        'generated_at' => now(),
    ];

    $pdf = Pdf::loadView('modules.tax.print.vat7', $data);
    return $pdf->download("VAT7_{$vatReturn->id}.pdf");
}

    /**
     * Export VAT return to CSV
     */
    public function exportReturnToCsv(VatReturn $vatReturn): string
    {
        $filename = "VAT7_{$vatReturn->id}_" . date('YmdHis') . ".csv";
        $snapshot = json_decode($vatReturn->source_snapshot, true) ?? [];

        $data = [
            'headers' => ['Code', 'Account Name', 'Amount', 'VAT Component'],
            'rows' => [],
            'summary' => [
                'Period' => $vatReturn->period_start->format('d/m/Y') . ' - ' . $vatReturn->period_end->format('d/m/Y'),
                'Return ID' => $vatReturn->id,
                'Output VAT' => $this->formatCurrency($vatReturn->output_vat),
                'Input VAT' => $this->formatCurrency($vatReturn->input_vat),
                'VAT Payable' => $this->formatCurrency($vatReturn->net_vat_payable),
                'VAT Rate' => $vatReturn->vat_rate . '%',
            ],
        ];

        // Add output details
        if (!empty($snapshot['details']['output'])) {
            $data['rows'][] = ['OUTPUT VAT'];
            $data['rows'][] = ['Code', 'Account Name', 'Amount', 'VAT'];
            foreach ($snapshot['details']['output'] as $item) {
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
        if (!empty($snapshot['details']['input'])) {
            $data['rows'][] = ['INPUT VAT'];
            $data['rows'][] = ['Code', 'Account Name', 'Amount', 'VAT'];
            foreach ($snapshot['details']['input'] as $item) {
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