<?php

namespace App\Services\Tax;

use App\Models\Tax\QpdPayment;
use App\Models\Tax\Itf12bProjection;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QpdService extends BaseTaxService
{
    protected $forecastYear;
    protected $currentMonth;
    protected $monthsRemaining;
    protected $monthsElapsed;
    protected $startDate;
    protected $endDate;

    public function __construct(int $companyId, int $forecastYear = null)
    {
        parent::__construct($companyId);
        $this->forecastYear = $forecastYear ?? now()->year;
        $this->currentMonth = now()->month;
        $this->monthsElapsed = $this->currentMonth;
        $this->monthsRemaining = 12 - $this->currentMonth;
        $this->startDate = $this->forecastYear . '-01-01';
        $this->endDate = now()->endOfMonth()->toDateString();
    }

    /**
     * ORIGINAL: Calculate QPD for a quarter (keep this for actual payments)
     */
    public function calculate(int $taxYear, int $quarter): array
    {
        try {
            // Get estimated annual tax from forecast
            $estimatedAnnualTax = $this->getEstimatedAnnualTaxFromForecast($taxYear);
            
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
            
            return $this->getDefaultCalculation($taxYear, $quarter);
        }
    }

    /**
     * Generate forecasted trial balance
     */
    public function generateForecastedTB(): array
    {
        $actuals = $this->getActualBalances();
        $profiles = Itf12bProjection::where('company_id', $this->companyId)
            ->where('tax_year', $this->forecastYear)
            ->get()
            ->keyBy('account_id');
        
        $forecast = [];
        $totals = [
            'ASSET' => 0,
            'LIABILITY' => 0,
            'EQUITY' => 0,
            'INCOME' => 0,
            'EXPENSE' => 0,
        ];

        foreach ($actuals as $account) {
            $accountId = $account->account_id;
            $ytdActual = $account->ytd_balance;
            $monthlyAverage = $account->monthly_average;
            
            $profile = $profiles[$accountId] ?? null;
            
            $projectedBalance = $this->calculateProjectedBalance(
                $account, $ytdActual, $monthlyAverage, $profile
            );
            
            $forecast[$account->account_code] = [
                'account_id' => $accountId,
                'code' => $account->account_code,
                'name' => $account->account_name,
                'type' => $account->account_type,
                'actual_ytd' => round($ytdActual, 2),
                'monthly_avg' => round($monthlyAverage, 2),
                'forecast_method' => $profile->forecast_method ?? 'linear',
                'projected_dec' => round($projectedBalance, 2),
                'growth_rate' => $profile->growth_rate ?? 0,
            ];
            
            if (in_array($account->account_type, ['INCOME', 'EXPENSE'])) {
                $totals[$account->account_type] += $projectedBalance;
            }
        }
        
        $totals['NET_PROFIT'] = $totals['INCOME'] - $totals['EXPENSE'];
        
        return [
            'as_of' => $this->forecastYear . '-12-31',
            'current_month' => $this->currentMonth,
            'months_elapsed' => $this->monthsElapsed,
            'months_remaining' => $this->monthsRemaining,
            'period_actuals' => date('F', mktime(0, 0, 0, 1, 1)) . ' - ' . date('F', mktime(0, 0, 0, $this->currentMonth, 1)),
            'accounts' => $forecast,
            'totals' => $totals,
        ];
    }

    protected function calculateProjectedBalance($account, $ytdActual, $monthlyAverage, $profile)
    {
        if (!$profile) {
            // Default linear projection
            return $ytdActual + ($monthlyAverage * $this->monthsRemaining);
        }
        
        switch ($profile->forecast_method) {
            case 'fixed':
                $fixedAmount = $profile->fixed_amount ?? $monthlyAverage;
                return $ytdActual + ($fixedAmount * $this->monthsRemaining);
                
            case 'average':
                return $monthlyAverage * 12;
                
            case 'linear':
                $growthRate = ($profile->growth_rate ?? 0) / 100;
                $avgWithGrowth = $monthlyAverage * (1 + $growthRate);
                return $ytdActual + ($avgWithGrowth * $this->monthsRemaining);
                
            default:
                return $ytdActual + ($monthlyAverage * $this->monthsRemaining);
        }
    }

    /**
     * FIXED: Get actual balances with proper handling of income vs expenses
     */
    protected function getActualBalances()
    {
        return DB::table('gl_entries as gl')
            ->join('chart_of_accounts as coa', 'gl.account_id', '=', 'coa.id')
            ->where('gl.company_id', $this->companyId)
            ->whereBetween('gl.posting_date', [$this->startDate, $this->endDate])
            ->groupBy('coa.id', 'coa.code', 'coa.name', 'coa.type')
            ->select(
                'coa.id as account_id',
                'coa.code as account_code',
                'coa.name as account_name',
                'coa.type as account_type',
                // FIXED: Handle income vs expenses correctly
                DB::raw('
                    CASE 
                        WHEN coa.type = "INCOME" THEN SUM(gl.credit - gl.debit)
                        ELSE SUM(gl.debit - gl.credit)
                    END as ytd_balance
                '),
                DB::raw('
                    CASE 
                        WHEN coa.type = "INCOME" THEN SUM(gl.credit - gl.debit) / ' . $this->monthsElapsed . '
                        ELSE SUM(gl.debit - gl.credit) / ' . $this->monthsElapsed . '
                    END as monthly_average
                '),
                DB::raw('COUNT(DISTINCT MONTH(gl.posting_date)) as months_active')
            )
            ->having('months_active', '>', 0)
            ->orderBy('coa.code')
            ->get();
    }

    /**
     * Generate forecasted P&L
     */
    public function generateForecastedPL(array $forecastedTB): array
    {
        $pl = [
            'revenue' => ['accounts' => [], 'total' => 0],
            'cost_of_sales' => ['accounts' => [], 'total' => 0],
            'gross_profit' => 0,
            'operating_expenses' => ['accounts' => [], 'total' => 0],
            'operating_profit' => 0,
            'other_income' => ['accounts' => [], 'total' => 0],
            'other_expenses' => ['accounts' => [], 'total' => 0],
            'net_profit_before_tax' => 0,
        ];

        foreach ($forecastedTB['accounts'] as $code => $account) {
            $type = $account['type'];
            $amount = $account['projected_dec'];
            
            if (preg_match('/^4\d{3}/', $code)) {
                $pl['revenue']['accounts'][$code] = $account;
                $pl['revenue']['total'] += $amount;
            } elseif (preg_match('/^5\d{3}/', $code)) {
                $pl['cost_of_sales']['accounts'][$code] = $account;
                $pl['cost_of_sales']['total'] += $amount;
            } elseif (preg_match('/^6\d{3}/', $code)) {
                $pl['operating_expenses']['accounts'][$code] = $account;
                $pl['operating_expenses']['total'] += $amount;
            } elseif ($type == 'INCOME') {
                $pl['other_income']['accounts'][$code] = $account;
                $pl['other_income']['total'] += $amount;
            } elseif ($type == 'EXPENSE') {
                $pl['other_expenses']['accounts'][$code] = $account;
                $pl['other_expenses']['total'] += $amount;
            }
        }
        
        $pl['gross_profit'] = $pl['revenue']['total'] - $pl['cost_of_sales']['total'];
        $pl['operating_profit'] = $pl['gross_profit'] - $pl['operating_expenses']['total'];
        $pl['net_profit_before_tax'] = $pl['operating_profit'] + $pl['other_income']['total'] - $pl['other_expenses']['total'];
        
        return $pl;
    }

    /**
     * Generate tax computation
     */
    public function generateTaxComputation(array $forecastedPL, array $forecastedTB): array
    {
        $taxSettings = DB::table('tax_settings')->where('company_id', $this->companyId)->first();
        
        $nonDeductible = DB::table('tax_mapping')
            ->where('company_id', $this->companyId)
            ->where('tax_type', 'INCOME_TAX')
            ->where('mapping_type', 'NON_DEDUCTIBLE')
            ->pluck('account_code')
            ->toArray();
        
        $partlyDeductible = DB::table('tax_mapping')
            ->where('company_id', $this->companyId)
            ->where('tax_type', 'INCOME_TAX')
            ->where('mapping_type', 'PARTLY_DEDUCTIBLE')
            ->get()
            ->keyBy('account_code');
        
        $addBacks = [];
        $totalAddBacks = 0;
        
        foreach ($forecastedTB['accounts'] as $code => $account) {
            if (in_array($code, $nonDeductible)) {
                $addBacks[$code] = [
                    'name' => $account['name'],
                    'amount' => $account['projected_dec'],
                    'reason' => 'Non-deductible expense'
                ];
                $totalAddBacks += $account['projected_dec'];
            } elseif (isset($partlyDeductible[$code])) {
                $deductiblePercent = $partlyDeductible[$code]->deductible_percent;
                $nonDeductibleAmount = $account['projected_dec'] * (1 - $deductiblePercent/100);
                
                if ($nonDeductibleAmount > 0) {
                    $addBacks[$code] = [
                        'name' => $account['name'],
                        'amount' => $nonDeductibleAmount,
                        'reason' => $deductiblePercent . '% deductible only'
                    ];
                    $totalAddBacks += $nonDeductibleAmount;
                }
            }
        }
        
        $taxableIncome = max(0, $forecastedPL['net_profit_before_tax'] + $totalAddBacks);
        
        $taxRate = ($taxSettings->income_tax_rate ?? 25.75) / 100;
        $aidsLevyRate = ($taxSettings->aids_levy_rate ?? 3) / 100;
        
        $incomeTax = $taxableIncome * $taxRate;
        $aidsLevy = $incomeTax * $aidsLevyRate;
        
        return [
            'profit_before_tax' => round($forecastedPL['net_profit_before_tax'], 2),
            'add_backs' => $addBacks,
            'total_add_backs' => round($totalAddBacks, 2),
            'taxable_income' => round($taxableIncome, 2),
            'tax_rate' => $taxSettings->income_tax_rate ?? 25.75,
            'income_tax' => round($incomeTax, 2),
            'aids_levy' => round($aidsLevy, 2),
            'total_tax' => round($incomeTax + $aidsLevy, 2),
        ];
    }

    /**
     * Generate QPD estimates from forecast
     */
    public function generateQPDEstimates(array $taxComputation): array
    {
        $annualTax = $taxComputation['total_tax'];
        
        $qpdPercentages = [
            1 => 10,  // March
            2 => 25,  // June
            3 => 30,  // September
            4 => 35,  // December
        ];
        
        $qpdEstimates = [];
        foreach ($qpdPercentages as $q => $percent) {
            $dueDate = $this->getDueDate($q);
            
            $qpdEstimates[$q] = [
                'quarter' => $q,
                'percentage' => $percent,
                'amount' => round($annualTax * $percent / 100, 2),
                'due_date' => $dueDate,
                'formatted_due' => date('d M Y', strtotime($dueDate)),
            ];
        }
        
        return $qpdEstimates;
    }

    /**
     * Get estimated annual tax from forecast
     */
    protected function getEstimatedAnnualTaxFromForecast(int $taxYear): float
    {
        try {
            $forecastedTB = $this->generateForecastedTB();
            $forecastedPL = $this->generateForecastedPL($forecastedTB);
            $taxComputation = $this->generateTaxComputation($forecastedPL, $forecastedTB);
            
            return $taxComputation['total_tax'];
        } catch (\Exception $e) {
            Log::warning('Could not get forecasted tax', [
                'taxYear' => $taxYear,
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }

    protected function getDueDate(int $quarter): string
    {
        $dates = [
            1 => $this->forecastYear . '-03-25',
            2 => $this->forecastYear . '-06-25',
            3 => $this->forecastYear . '-09-25',
            4 => $this->forecastYear . '-12-20',
        ];
        return $dates[$quarter] ?? $this->forecastYear . '-12-31';
    }

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

    protected function getDefaultPercentage(int $quarter): float
    {
        $defaults = [10, 25, 30, 35];
        return $defaults[$quarter - 1] ?? 10;
    }

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

    protected function getPaidAmount(int $taxYear, int $quarter): float
{
    try {
        return (float) DB::table('qpd_payments')
            ->where('company_id', $this->companyId)
            ->where('tax_year', $taxYear)
            ->where('quarter_no', $quarter) // Changed from 'quarter' to 'quarter_no'
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

    public function savePayment(array $data, int $userId): QpdPayment
{
    Log::info('=== SAVEPAYMENT START ===', [
        'data' => $data,
        'user_id' => $userId
    ]);

    try {
        $paymentNo = QpdPayment::generatePaymentNo($this->companyId, $data['tax_year'], $data['quarter']);
        Log::info('Generated payment number', ['payment_no' => $paymentNo]);

        // Calculate cumulative percentages
        $quarterPercentages = [1 => 10, 2 => 25, 3 => 30, 4 => 35];
        $cumulativePercent = 0;
        for ($q = 1; $q <= $data['quarter']; $q++) {
            $cumulativePercent += $quarterPercentages[$q];
        }

        $paymentData = [
            'company_id' => $this->companyId,
            'tax_year' => $data['tax_year'],
            'quarter' => $data['quarter'],
            'quarter_no' => $data['quarter'], // ADDED: Set quarter_no to same as quarter
            'payment_no' => $paymentNo,
            'payment_date' => $data['payment_date'] ?? now(),
            'due_date' => $data['due_date'],
            'amount' => $data['amount'],
            'estimated_annual_tax' => $data['estimated_annual_tax'],
            'percentage_applied' => $data['percentage_applied'],
            'payment_method' => $data['payment_method'] ?? 'BANK',
            'reference' => $data['reference'] ?? null,
            'status' => 'DRAFT',
            'qpd_forecast_id' => null, // FIXED: Use null, not 0
            'quarter_percent' => $data['percentage_applied'], // ADDED
            'cumulative_percent' => $cumulativePercent, // ADDED
            'cumulative_due_amount' => $data['amount'], // ADDED
            'amount_already_paid' => 0, // ADDED
            'amount_now_due' => $data['amount'], // ADDED
            'metadata' => json_encode(['notes' => $data['notes'] ?? null]),
            'created_by' => $userId,
        ];

        Log::info('Payment data prepared', $paymentData);

        $payment = QpdPayment::create($paymentData);
        
        Log::info('Payment created successfully', [
            'id' => $payment->id,
            'payment_no' => $payment->payment_no
        ]);

        return $payment;

    } catch (\Exception $e) {
        Log::error('SavePayment exception', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        throw $e;
    }
}

    protected function generatePaymentNo(int $taxYear, int $quarter): string
{
    $prefix = 'QPD';
    $lastPayment = QpdPayment::where('company_id', $this->companyId)
        ->where('tax_year', $taxYear)
        ->where('quarter_no', $quarter) // Changed from 'quarter' to 'quarter_no'
        ->orderBy('id', 'desc')
        ->first();
    
    $sequence = $lastPayment ? intval(substr($lastPayment->payment_no, -4)) + 1 : 1;
    
    return sprintf('%s-%d-Q%d-%04d', $prefix, $taxYear, $quarter, $sequence);
}

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