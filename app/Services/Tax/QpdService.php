<?php

namespace App\Services\Tax;

use App\Models\Tax\QpdPayment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QpdService extends BaseTaxService
{
    /**
     * Calculate QPD for a quarter
     */
    public function calculate(int $taxYear, int $quarter): array
    {
        try {
            // Get estimated annual tax (using previous year or current projections)
            $estimatedAnnualTax = $this->getEstimatedAnnualTax($taxYear);
            
            // Get QPD percentage for the quarter
            $percentage = $this->taxSettings->getQpdPercentage($quarter) / 100;
            $dueDate = $this->taxSettings->getQpdDueDate($quarter);

            // Calculate QPD amount
            $amount = $estimatedAnnualTax * $percentage;

            // Get payments already made for this quarter
            $paidAmount = $this->getPaidAmount($taxYear, $quarter);

            return [
                'tax_year' => $taxYear,
                'quarter' => $quarter,
                'quarter_name' => "Q{$quarter} " . $taxYear,
                'estimated_annual_tax' => round($estimatedAnnualTax, 2),
                'qpd_percentage' => $percentage * 100,
                'qpd_amount' => round($amount, 2),
                'paid_amount' => round($paidAmount, 2),
                'balance_due' => round(max(0, $amount - $paidAmount), 2),
                'due_date' => $dueDate,
                'is_overdue' => $dueDate ? now()->gt($dueDate) && $paidAmount < $amount : false,
                'calculated_at' => now()->toDateTimeString(),
            ];
            
        } catch (\Exception $e) {
            Log::error('QPD calculation error', [
                'taxYear' => $taxYear,
                'quarter' => $quarter,
                'error' => $e->getMessage()
            ]);
            
            // Return default values instead of throwing
            return $this->getDefaultCalculation($taxYear, $quarter);
        }
    }

    /**
     * Get estimated annual tax safely
     */
    protected function getEstimatedAnnualTax(int $taxYear): float
    {
        try {
            $incomeTaxService = new IncomeTaxService($this->companyId);
            $previousYearCalc = $incomeTaxService->calculate($taxYear - 1);
            return $previousYearCalc['total_tax'] ?? 0;
        } catch (\Exception $e) {
            Log::warning('Could not calculate previous year tax', [
                'taxYear' => $taxYear,
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }

    /**
     * Get default calculation values
     */
    protected function getDefaultCalculation(int $taxYear, int $quarter): array
    {
        $dueDate = $this->taxSettings->getQpdDueDate($quarter);
        
        return [
            'tax_year' => $taxYear,
            'quarter' => $quarter,
            'quarter_name' => "Q{$quarter} " . $taxYear,
            'estimated_annual_tax' => 0,
            'qpd_percentage' => $this->getDefaultPercentage($quarter),
            'qpd_amount' => 0,
            'paid_amount' => 0,
            'balance_due' => 0,
            'due_date' => $dueDate,
            'is_overdue' => false,
            'calculated_at' => now()->toDateTimeString(),
        ];
    }

    /**
     * Get default percentage based on quarter
     */
    protected function getDefaultPercentage(int $quarter): float
    {
        $defaults = [10, 25, 30, 35];
        return $defaults[$quarter - 1] ?? 10;
    }

    /**
     * Calculate full year forecast
     */
    public function forecast(int $taxYear): array
    {
        $forecast = [];
        $totalQpd = 0;

        for ($q = 1; $q <= 4; $q++) {
            $forecast[$q] = $this->calculate($taxYear, $q);
            $totalQpd += $forecast[$q]['qpd_amount'];
        }

        return [
            'tax_year' => $taxYear,
            'quarters' => $forecast,
            'total_qpd' => round($totalQpd, 2),
            'estimated_annual_tax' => $forecast[1]['estimated_annual_tax'] ?? 0,
            'calculated_at' => now()->toDateTimeString(),
        ];
    }

    /**
     * Get amount already paid for quarter
     */
    protected function getPaidAmount(int $taxYear, int $quarter): float
    {
        try {
            return (float) DB::table('qpd_payments')
                ->where('company_id', $this->companyId)
                ->where('tax_year', $taxYear)
                ->where('quarter', $quarter)
                ->where('status', 'PAID')
                ->sum('amount');
        } catch (\Exception $e) {
            Log::error('Failed to get paid amount', [
                'taxYear' => $taxYear,
                'quarter' => $quarter,
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }

    /**
     * Save QPD payment
     */
    public function savePayment(array $data, int $userId): QpdPayment
    {
        $paymentNo = $this->generatePaymentNo($data['tax_year'], $data['quarter']);

        return QpdPayment::create([
            'company_id' => $this->companyId,
            'tax_year' => $data['tax_year'],
            'quarter' => $data['quarter'],
            'payment_no' => $paymentNo,
            'payment_date' => $data['payment_date'] ?? now(),
            'due_date' => $data['due_date'],
            'amount' => $data['amount'],
            'estimated_annual_tax' => $data['estimated_annual_tax'],
            'percentage_applied' => $data['percentage_applied'],
            'payment_method' => $data['payment_method'] ?? 'BANK',
            'reference' => $data['reference'] ?? null,
            'status' => 'DRAFT',
            'metadata' => json_encode(['notes' => $data['notes'] ?? null]),
            'created_by' => $userId,
        ]);
    }

    /**
     * Generate payment number
     */
    protected function generatePaymentNo(int $taxYear, int $quarter): string
    {
        $prefix = 'QPD';
        $lastPayment = QpdPayment::where('company_id', $this->companyId)
            ->where('tax_year', $taxYear)
            ->where('quarter', $quarter)
            ->orderBy('id', 'desc')
            ->first();
        
        $sequence = $lastPayment ? intval(substr($lastPayment->payment_no, -4)) + 1 : 1;
        
        return sprintf('%s-%d-Q%d-%04d', $prefix, $taxYear, $quarter, $sequence);
    }

    /**
     * Generate ITF12B PDF
     */
    public function generateItf12bPdf(QpdPayment $payment)
    {
        $data = [
            'payment' => $payment,
            'company' => $payment->company,
            'tax_year' => $payment->tax_year,
            'quarter' => $payment->quarter,
            'generated_at' => now(),
        ];

        $pdf = Pdf::loadView('modules.tax.print.itf12b', $data);
        return $pdf->download("ITF12B_{$payment->payment_no}.pdf");
    }

    /**
     * Export forecast to CSV
     */
    public function exportForecastToCsv(array $forecast): string
    {
        $filename = "QPD_Forecast_{$forecast['tax_year']}_" . date('YmdHis') . ".csv";

        $data = [
            'headers' => ['Quarter', 'Due Date', 'Percentage', 'Amount', 'Status'],
            'rows' => [],
            'summary' => [
                'Tax Year' => $forecast['tax_year'],
                'Estimated Annual Tax' => $this->formatCurrency($forecast['estimated_annual_tax']),
                'Total QPD' => $this->formatCurrency($forecast['total_qpd']),
            ],
        ];

        foreach ($forecast['quarters'] as $q => $details) {
            $status = $details['is_overdue'] ? 'OVERDUE' : 
                     (($details['paid_amount'] ?? 0) > 0 ? 'PAID' : 'PENDING');
            
            $data['rows'][] = [
                "Q{$q}",
                $details['due_date'] ? date('d/m/Y', strtotime($details['due_date'])) : 'N/A',
                $details['qpd_percentage'] . '%',
                $this->formatCurrency($details['qpd_amount']),
                $status,
            ];
        }

        return $this->exportToCsv($data, $filename);
    }
}