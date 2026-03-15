<?php

namespace App\Services\Tax;

use App\Models\Tax\IncomeTaxReturn;
use App\Models\Tax\TaxMapping;
use App\Models\Tax\QpdPayment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class IncomeTaxService extends BaseTaxService
{
    protected $incomeAccounts = [];
    protected $expenseAccounts = [];
    protected $nonDeductibleAccounts = [];
    protected $partlyDeductibleAccounts = [];
    protected $capitalAllowanceAccounts = [];

    public function __construct(int $companyId)
    {
        parent::__construct($companyId);
        
        // Load account mappings
        $this->loadAccountMappings();
    }

    /**
     * Load account mappings from database
     */
    protected function loadAccountMappings(): void
    {
        // Get all income accounts
        $this->incomeAccounts = TaxMapping::where('company_id', $this->companyId)
            ->where('tax_type', 'INCOME_TAX')
            ->where('mapping_type', 'INCOME')
            ->pluck('account_code')
            ->toArray();

        // Get all expense accounts
        $this->expenseAccounts = TaxMapping::where('company_id', $this->companyId)
            ->where('tax_type', 'INCOME_TAX')
            ->where('mapping_type', 'EXPENSE')
            ->pluck('account_code')
            ->toArray();

        // Get non-deductible accounts
        $this->nonDeductibleAccounts = TaxMapping::where('company_id', $this->companyId)
            ->where('tax_type', 'INCOME_TAX')
            ->where('mapping_type', 'NON_DEDUCTIBLE')
            ->pluck('account_code')
            ->toArray();

        // Get partly deductible accounts with percentages
        $this->partlyDeductibleAccounts = TaxMapping::where('company_id', $this->companyId)
            ->where('tax_type', 'INCOME_TAX')
            ->where('mapping_type', 'PARTLY_DEDUCTIBLE')
            ->get()
            ->keyBy('account_code');

        // Get capital allowance accounts
        $this->capitalAllowanceAccounts = TaxMapping::where('company_id', $this->companyId)
            ->where('tax_type', 'INCOME_TAX')
            ->where('mapping_type', 'CAPITAL_ALLOWANCE')
            ->pluck('account_code')
            ->toArray();
    }

    /**
     * Calculate income tax for a year
     */
    public function calculate(int $taxYear): array
    {
        $startDate = "{$taxYear}-01-01";
        $endDate = "{$taxYear}-12-31";

        $this->startDate = $startDate;
        $this->endDate = $endDate;

        // Step 1: Calculate total income
        $incomeResult = $this->calculateIncome($startDate, $endDate);
        
        // Step 2: Calculate expenses and addbacks
        $expenseResult = $this->calculateExpenses($startDate, $endDate);
        
        // Step 3: Calculate capital allowances
        $capitalAllowances = $this->calculateCapitalAllowances($startDate, $endDate);
        
        // Step 4: Calculate taxable income
       $taxableIncome = $incomeResult['total'] - $expenseResult['total'] + $expenseResult['addbacks'] - $capitalAllowances;
        
        // Step 5: Apply assessed loss brought forward
        $assessedLossBf = $this->getAssessedLossBroughtForward($taxYear);
        $taxableIncomeAfterLoss = max(0, $taxableIncome - $assessedLossBf);
        $assessedLossCf = max(0, $assessedLossBf - $taxableIncome);
        
        // Step 6: Calculate tax
        $taxRate = $this->taxSettings->getCorporateTaxRate() / 100;
        $incomeTax = $taxableIncomeAfterLoss * $taxRate;
        $aidsLevy = $incomeTax * ($this->taxSettings->getAidsLevyRate() / 100);
        $totalTax = $incomeTax + $aidsLevy;
        
        // Step 7: Get QPD payments made
        $qpdPayments = $this->getQpdPayments($taxYear);
        $balanceDue = $totalTax - $qpdPayments;

        return [
            'tax_year' => $taxYear,
            'period' => [
                'start' => $startDate,
                'end' => $endDate,
            ],
            'income' => $incomeResult,
            'expenses' => $expenseResult,
            'capital_allowances' => $capitalAllowances,
            'taxable_income' => round($taxableIncome, 2),
            'assessed_loss_bf' => round($assessedLossBf, 2),
            'taxable_income_after_loss' => round($taxableIncomeAfterLoss, 2),
            'assessed_loss_cf' => round($assessedLossCf, 2),
            'tax_rate' => $this->taxSettings->getCorporateTaxRate() / 100,
            'income_tax' => round($incomeTax, 4),
            'aids_levy' => round($aidsLevy, 2),
            'total_tax' => round($totalTax, 2),
            'qpd_paid' => round($qpdPayments, 2),
            'balance_due' => round($balanceDue, 2),
            'calculated_at' => now()->toDateTimeString(),
        ];
    }

    /**
     * Calculate income
     */
    protected function calculateIncome(string $startDate, string $endDate): array
    {
        $incomeBalances = $this->getAccountBalances($this->incomeAccounts, $startDate, $endDate);
        $totalIncome = 0;
        $breakdown = [];

        foreach ($incomeBalances as $code => $balance) {
            $amount = $balance['credit'] - $balance['debit']; // Income is credit balance
            if ($amount != 0) {
                $totalIncome += $amount;
                $breakdown[] = [
                    'code' => $code,
                    'name' => $balance['name'],
                    'amount' => $amount,
                ];
            }
        }

        return [
            'total' => round($totalIncome, 2),
            'breakdown' => $breakdown,
        ];
    }

    /**
     * Calculate expenses and addbacks
     */
    protected function calculateExpenses(string $startDate, string $endDate): array
    {
        $expenseBalances = $this->getAccountBalances($this->expenseAccounts, $startDate, $endDate);
        $totalExpenses = 0;
        $totalAddbacks = 0;
        $expenseBreakdown = [];
        $addbackBreakdown = [];

        foreach ($expenseBalances as $code => $balance) {
            $amount = $balance['debit'] - $balance['credit']; // Expense is debit balance
            if ($amount == 0) continue;

            // Check if non-deductible
            if (in_array($code, $this->nonDeductibleAccounts)) {
                $totalAddbacks += $amount;
                $addbackBreakdown[] = [
                    'code' => $code,
                    'name' => $balance['name'],
                    'amount' => $amount,
                    'reason' => 'Fully non-deductible',
                ];
                continue;
            }

            // Check if partly deductible
            if (isset($this->partlyDeductibleAccounts[$code])) {
                $deductiblePercent = $this->partlyDeductibleAccounts[$code]->deductible_percent / 100;
                $deductible = $amount * $deductiblePercent;
                $nonDeductible = $amount - $deductible;
                
                $totalExpenses += $deductible;
                $totalAddbacks += $nonDeductible;
                
                $expenseBreakdown[] = [
                    'code' => $code,
                    'name' => $balance['name'],
                    'amount' => $amount,
                    'deductible' => $deductible,
                    'deductible_percent' => $deductiblePercent * 100,
                ];
                
                $addbackBreakdown[] = [
                    'code' => $code,
                    'name' => $balance['name'],
                    'amount' => $nonDeductible,
                    'reason' => ($deductiblePercent * 100) . '% deductible only',
                ];
            } else {
                // Fully deductible
                $totalExpenses += $amount;
                $expenseBreakdown[] = [
                    'code' => $code,
                    'name' => $balance['name'],
                    'amount' => $amount,
                    'deductible' => $amount,
                    'deductible_percent' => 100,
                ];
            }
        }

        return [
            'total' => round($totalExpenses, 2),
            'addbacks' => round($totalAddbacks, 2),
            'breakdown' => $expenseBreakdown,
            'addback_breakdown' => $addbackBreakdown,
        ];
    }

    /**
     * Calculate capital allowances (Wear and Tear)
     */
    protected function calculateCapitalAllowances(string $startDate, string $endDate): float
    {
        if (empty($this->capitalAllowanceAccounts)) {
            return 0;
        }

        $balances = $this->getAccountBalances($this->capitalAllowanceAccounts, $startDate, $endDate);
        $totalAllowances = 0;

        foreach ($balances as $code => $balance) {
            // Capital allowances are typically credit balances (reductions)
            $amount = $balance['credit'] - $balance['debit'];
            $totalAllowances += $amount;
        }

        return round($totalAllowances, 2);
    }

    /**
     * Get assessed loss brought forward
     */
    protected function getAssessedLossBroughtForward(int $taxYear): float
    {
        $previousYear = IncomeTaxReturn::where('company_id', $this->companyId)
            ->where('tax_year', $taxYear - 1)
            ->whereIn('status', ['APPROVED', 'SUBMITTED'])
            ->first();

        return $previousYear ? (float) $previousYear->assessed_loss_cf : 0;
    }

    /**
     * Get QPD payments made for the year
     */
    protected function getQpdPayments(int $taxYear): float
    {
        return (float) DB::table('qpd_payments')
            ->where('company_id', $this->companyId)
            ->where('tax_year', $taxYear)
            ->where('status', 'PAID')
            ->sum('amount');
    }

    /**
     * Calculate tax for multiple years comparison
     */
    public function calculateMultiYear(array $years): array
    {
        $results = [];
        foreach ($years as $year) {
            $results[$year] = $this->calculate($year);
        }
        return $results;
    }

    /**
     * Calculate estimated tax for current year based on YTD figures
     */
    public function calculateEstimatedTax(int $taxYear): array
    {
        $today = now();
        $startDate = "{$taxYear}-01-01";
        $endDate = $today->toDateString();

        // Get YTD figures
        $incomeYTD = $this->calculateIncome($startDate, $endDate)['total'];
        $expenseYTD = $this->calculateExpenses($startDate, $endDate)['total'];
        
        // Annualize (assuming linear)
        $daysInYear = 365;
        $daysPassed = $today->diffInDays($startDate) ?: 1;
        $annualizationFactor = $daysInYear / $daysPassed;

        $estimatedIncome = $incomeYTD * $annualizationFactor;
        $estimatedExpenses = $expenseYTD * $annualizationFactor;
        $estimatedProfit = $estimatedIncome - $estimatedExpenses;

        $taxRate = $this->taxSettings->getCorporateTaxRate() / 100;
        $estimatedTax = $estimatedProfit * $taxRate;
        $aidsLevy = $estimatedTax * ($this->taxSettings->getAidsLevyRate() / 100);

        return [
            'tax_year' => $taxYear,
            'as_of_date' => $today->toDateString(),
            'ytd_income' => round($incomeYTD, 2),
            'ytd_expenses' => round($expenseYTD, 2),
            'ytd_profit' => round($incomeYTD - $expenseYTD, 2),
            'estimated_annual_income' => round($estimatedIncome, 2),
            'estimated_annual_expenses' => round($estimatedExpenses, 2),
            'estimated_annual_profit' => round($estimatedProfit, 2),
            'estimated_tax' => round($estimatedTax, 2),
            'estimated_aids_levy' => round($aidsLevy, 2),
            'estimated_total_tax' => round($estimatedTax + $aidsLevy, 2),
            'annualization_factor' => round($annualizationFactor, 2),
        ];
    }

    /**
     * Save income tax return
     */
    public function saveReturn(array $data, int $userId): IncomeTaxReturn
{
    $returnNo = IncomeTaxReturn::generateReturnNo($this->companyId, $data['tax_year']);

    return IncomeTaxReturn::create([
        'company_id' => $this->companyId,
        'tax_year' => $data['tax_year'],
        'return_no' => $returnNo,
        'period_start' => $data['period_start'] ?? "{$data['tax_year']}-01-01",
        'period_end' => $data['period_end'] ?? "{$data['tax_year']}-12-31",
        'filing_date' => $data['filing_date'] ?? now(),
        'status' => $data['status'] ?? 'DRAFT',
        'total_income' => $data['total_income'],
        'total_expenses' => $data['total_expenses'],
        'add_back_amount' => $data['add_back_amount'] ?? 0,
        'taxable_income' => $data['taxable_income'],
        'income_tax_rate' => $data['tax_rate'], // Map tax_rate to income_tax_rate
        'income_tax_payable' => $data['income_tax'], // Map income_tax to income_tax_payable
        'aids_levy' => $data['aids_levy'] ?? 0,
        'total_tax' => $data['total_tax'],
        'qpd_paid_total' => $data['qpd_paid'] ?? 0, // Map qpd_paid to qpd_paid_total
        'balance_due' => $data['balance_due'] ?? $data['total_tax'],
        'assessed_loss_bf' => $data['assessed_loss_bf'] ?? 0,
        'assessed_loss_cf' => $data['assessed_loss_cf'] ?? 0,
        'metadata' => json_encode([
            'income_breakdown' => $data['income_breakdown'] ?? [],
            'expense_breakdown' => $data['expense_breakdown'] ?? [],
            'addback_breakdown' => $data['addback_breakdown'] ?? [],
            'capital_allowances' => $data['capital_allowances'] ?? 0,
            'notes' => $data['notes'] ?? null,
        ]),
        'created_by' => $userId,
    ]);
}

    /**
     * Generate ITF12C PDF
     */
    public function generateItf12cPdf(IncomeTaxReturn $return)
    {
        $data = [
            'return' => $return,
            'company' => $return->company,
            'tax_year' => $return->tax_year,
            'income_breakdown' => $return->metadata['income_breakdown'] ?? [],
            'expense_breakdown' => $return->metadata['expense_breakdown'] ?? [],
            'addback_breakdown' => $return->metadata['addback_breakdown'] ?? [],
            'capital_allowances' => $return->metadata['capital_allowances'] ?? 0,
            'generated_at' => now(),
        ];

        $pdf = Pdf::loadView('modules.tax.print.itf12c', $data);
        return $pdf->download("ITF12C_{$return->return_no}.pdf");
    }

    /**
     * Export to CSV
     */
    /**
 * Export income tax return to CSV (custom format)
 */
public function exportReturnToCsv(IncomeTaxReturn $return): string
{
    $filename = "ITF12C_{$return->return_no}_" . date('YmdHis') . ".csv";

    $data = [
        'headers' => ['Description', 'Amount (USD)'],
        'rows' => [],
        'summary' => [
            'Tax Year' => $return->tax_year,
            'Return No' => $return->return_no,
            'Filing Date' => $this->formatDate($return->filing_date),
            'Status' => $return->status,
            'Total Income' => $this->formatCurrency($return->total_income),
            'Total Deductible Expenses' => $this->formatCurrency($return->total_expenses),
            'Add Backs' => $this->formatCurrency($return->add_back_amount),
            'Taxable Income' => $this->formatCurrency($return->taxable_income),
            'Assessed Loss BF' => $this->formatCurrency($return->assessed_loss_bf),
            'Taxable Income After Loss' => $this->formatCurrency($return->taxable_income - $return->assessed_loss_bf),
            'Income Tax' => $this->formatCurrency($return->income_tax),
            'AIDS Levy' => $this->formatCurrency($return->aids_levy),
            'Total Tax' => $this->formatCurrency($return->total_tax),
            'QPD Paid' => $this->formatCurrency($return->qpd_paid),
            'Balance Due' => $this->formatCurrency($return->balance_due),
            'Assessed Loss CF' => $this->formatCurrency($return->assessed_loss_cf),
        ],
    ];

    // Add income breakdown
    if (!empty($return->metadata['income_breakdown'])) {
        $data['rows'][] = ['INCOME'];
        $data['rows'][] = ['Code', 'Account Name', 'Amount'];
        foreach ($return->metadata['income_breakdown'] as $item) {
            $data['rows'][] = [
                $item['code'],
                $item['name'],
                $this->formatCurrency($item['amount']),
            ];
        }
        $data['rows'][] = [];
    }

    // Add expense breakdown
    if (!empty($return->metadata['expense_breakdown'])) {
        $data['rows'][] = ['DEDUCTIBLE EXPENSES'];
        $data['rows'][] = ['Code', 'Account Name', 'Total Amount', 'Deductible %', 'Deductible Amount'];
        foreach ($return->metadata['expense_breakdown'] as $item) {
            $data['rows'][] = [
                $item['code'],
                $item['name'],
                $this->formatCurrency($item['amount']),
                $item['deductible_percent'] . '%',
                $this->formatCurrency($item['deductible']),
            ];
        }
        $data['rows'][] = [];
    }

    // Add addbacks
    if (!empty($return->metadata['addback_breakdown'])) {
        $data['rows'][] = ['ADD BACKS (NON-DEDUCTIBLE)'];
        $data['rows'][] = ['Code', 'Account Name', 'Amount', 'Reason'];
        foreach ($return->metadata['addback_breakdown'] as $item) {
            $data['rows'][] = [
                $item['code'],
                $item['name'],
                $this->formatCurrency($item['amount']),
                $item['reason'],
            ];
        }
        $data['rows'][] = [];
    }

    return $this->exportToCsv($data, $filename);
}

    /**
     * Reconcile income tax return with GL
     */
    public function reconcile(int $taxYear): array
    {
        $calculation = $this->calculate($taxYear);
        
        $savedReturn = IncomeTaxReturn::where('company_id', $this->companyId)
            ->where('tax_year', $taxYear)
            ->first();

        if (!$savedReturn) {
            return [
                'exists' => false,
                'calculation' => $calculation,
            ];
        }

        $differences = [];
        
        // Compare key figures
        $fields = [
            'total_income' => 'Total Income',
            'total_expenses' => 'Total Expenses',
            'add_back_amount' => 'Add Backs',
            'taxable_income' => 'Taxable Income',
            'income_tax' => 'Income Tax',
            'aids_levy' => 'AIDS Levy',
            'total_tax' => 'Total Tax',
        ];

        foreach ($fields as $field => $label) {
            $calculated = $calculation[$field] ?? 0;
            $saved = $savedReturn->$field ?? 0;
            
            if (abs($calculated - $saved) > 0.01) {
                $differences[] = [
                    'field' => $label,
                    'calculated' => $calculated,
                    'saved' => $saved,
                    'difference' => $calculated - $saved,
                ];
            }
        }

        return [
            'exists' => true,
            'return' => $savedReturn,
            'calculation' => $calculation,
            'differences' => $differences,
            'is_reconciled' => empty($differences),
        ];
    }
}