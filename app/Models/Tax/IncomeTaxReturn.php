<?php

namespace App\Models\Tax;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Company;
use App\Models\User;

class IncomeTaxReturn extends Model
{
    use HasFactory;

    protected $table = 'income_tax_returns';
    
    protected $fillable = [
        'company_id',
        'tax_year',
        'return_no',
        'period_start',
        'period_end',
        'filing_date',
        'status',
        'total_income',
        'total_expenses',
        'add_back_amount',
        'income_tax_rate',        // Changed from 'tax_rate'
        'profit_before_tax',
        'add_backs',
        'deductions',
        'accounting_profit',
        'non_deductible_expenses',
        'capital_allowances',
        'assessed_losses_bf',
        'other_adjustments',
        'taxable_income',
        'income_tax_payable',      // Changed from 'income_tax'
        'aids_levy',
        'total_tax',
        'qpd_paid_total',          // Changed from 'qpd_paid'
        'balance_due',
        'assessed_loss_bf',
        'assessed_loss_cf',
        'notes',
        'source_snapshot',
        'metadata',
        'submitted_by',
        'submitted_at',
        'approved_by',
        'approved_at',
        'created_by',
    ];

    protected $casts = [
        'tax_year' => 'integer',
        'period_start' => 'date',
        'period_end' => 'date',
        'filing_date' => 'date',
        'total_income' => 'decimal:2',
        'total_expenses' => 'decimal:2',
        'add_back_amount' => 'decimal:2',
        'income_tax_rate' => 'decimal:4',
        'profit_before_tax' => 'decimal:2',
        'add_backs' => 'decimal:2',
        'deductions' => 'decimal:2',
        'accounting_profit' => 'decimal:2',
        'non_deductible_expenses' => 'decimal:2',
        'capital_allowances' => 'decimal:2',
        'assessed_losses_bf' => 'decimal:2',
        'other_adjustments' => 'decimal:2',
        'taxable_income' => 'decimal:2',
        'income_tax_payable' => 'decimal:2',
        'aids_levy' => 'decimal:2',
        'total_tax' => 'decimal:2',
        'qpd_paid_total' => 'decimal:2',
        'balance_due' => 'decimal:2',
        'assessed_loss_bf' => 'decimal:2',
        'assessed_loss_cf' => 'decimal:2',
        'metadata' => 'json',
        'source_snapshot' => 'json',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'DRAFT',
    ];

    /**
     * Get the company that owns this return
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the user who submitted this return
     */
    public function submittedBy()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    /**
     * Get the user who approved this return
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get QPD payments for this tax year
     */
    public function qpdPayments()
    {
        return $this->hasMany(QpdPayment::class, 'company_id', 'company_id')
            ->where('tax_year', $this->tax_year);
    }

    /**
     * Get formatted period name
     */
    public function getPeriodNameAttribute(): string
    {
        if ($this->period_start && $this->period_end) {
            return $this->period_start->format('M Y') . ' - ' . $this->period_end->format('M Y');
        }
        return 'Tax Year ' . $this->tax_year;
    }

    /**
     * Calculate balance due
     */
    public function calculateBalanceDue(): float
    {
        return round($this->total_tax - $this->qpd_paid_total, 2);
    }

    /**
     * Check if return is balanced
     */
    public function isBalanced(): bool
    {
        return abs($this->total_tax - $this->qpd_paid_total - $this->balance_due) < 0.01;
    }

    /**
     * Scope by status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope by tax year
     */
    public function scopeForYear($query, $year)
    {
        return $query->where('tax_year', $year);
    }

    /**
     * Generate return number
     */
    public static function generateReturnNo(int $companyId, int $year): string
    {
        $prefix = 'ITF';
        $lastReturn = self::where('company_id', $companyId)
            ->where('tax_year', $year)
            ->orderBy('id', 'desc')
            ->first();
        
        $sequence = $lastReturn ? intval(substr($lastReturn->return_no, -4)) + 1 : 1;
        
        return sprintf('%s-%d-%04d', $prefix, $year, $sequence);
    }
}