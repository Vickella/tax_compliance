<?php

namespace App\Models\Tax;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Company;
use App\Models\User;
use App\Models\JournalEntry;

class QpdPayment extends Model
{
    use HasFactory;

    protected $table = 'qpd_payments';
    
    protected $fillable = [
        'company_id',
        'tax_year',
        'quarter',
        'quarter_no',
        'payment_no',
        'payment_date',
        'due_date',
        'amount',
        'estimated_annual_tax',
        'percentage_applied',
        'payment_method',
        'reference',
        'status',
        'qpd_forecast_id',
        'quarter_percent',
        'cumulative_percent',
        'cumulative_due_amount',
        'amount_already_paid',
        'amount_now_due',
        'journal_entry_id',
        'metadata',
        'notes',
        'created_by',
        'submitted_by',
        'submitted_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'tax_year' => 'integer',
        'quarter' => 'integer',
        'quarter_no' => 'integer',
        'payment_date' => 'date',
        'due_date' => 'date',
        'amount' => 'decimal:2',
        'estimated_annual_tax' => 'decimal:2',
        'percentage_applied' => 'decimal:4',
        'quarter_percent' => 'decimal:4',
        'cumulative_percent' => 'decimal:4',
        'cumulative_due_amount' => 'decimal:2',
        'amount_already_paid' => 'decimal:2',
        'amount_now_due' => 'decimal:2',
        'metadata' => 'json',
        'submitted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'DRAFT',
        'qpd_forecast_id' => null,
        'quarter_percent' => 0,
        'cumulative_percent' => 0,
        'cumulative_due_amount' => 0,
        'amount_already_paid' => 0,
        'amount_now_due' => 0,
        'notes' => null,
    ];

    /**
     * Get the company that owns this payment
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the user who created this payment
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who submitted this payment
     */
    public function submittedBy()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    /**
     * Get the journal entry for this payment
     */
    public function journalEntry()
    {
        return $this->belongsTo(JournalEntry::class, 'journal_entry_id');
    }

    /**
     * Get the income tax return for this tax year
     */
    public function incomeTaxReturn()
    {
        return $this->hasOne(IncomeTaxReturn::class, 'company_id', 'company_id')
            ->where('tax_year', $this->tax_year);
    }

    /**
     * Get the forecast associated with this payment
     */
    public function forecast()
    {
        return $this->belongsTo(Itf12bProjection::class, 'qpd_forecast_id');
    }

    /**
     * Get the quarter name (Q1, Q2, Q3, Q4)
     */
    public function getQuarterNameAttribute(): string
    {
        return 'Q' . $this->quarter;
    }

    /**
     * Get the quarter period description
     */
    public function getQuarterPeriodAttribute(): string
    {
        $periods = [
            1 => 'January - March',
            2 => 'April - June',
            3 => 'July - September',
            4 => 'October - December',
        ];
        return $periods[$this->quarter] ?? 'Unknown';
    }

    /**
     * Check if payment is overdue
     */
    public function isOverdue(): bool
    {
        return $this->status !== 'PAID' && now()->gt($this->due_date);
    }

    /**
     * Calculate days overdue
     */
    public function daysOverdue(): int
    {
        if (!$this->isOverdue()) {
            return 0;
        }
        return now()->diffInDays($this->due_date);
    }

    /**
     * Calculate penalty (using tax settings)
     */
    public function calculatePenalty(TaxSetting $settings): float
    {
        if (!$this->isOverdue()) {
            return 0;
        }

        $rules = $settings->getPenaltyRules();
        $penalty = 0;

        // Fixed penalty
        if (isset($rules['late_submission'])) {
            $penalty += $rules['late_submission']['amount'] ?? 0;
        }

        // Monthly penalty
        if (isset($rules['late_payment'])) {
            $months = min($this->daysOverdue() / 30, $rules['late_payment']['max_months'] ?? 12);
            $penalty += $this->amount * ($rules['late_payment']['rate'] / 100) * $months;
        }

        return round($penalty, 2);
    }

    /**
     * Scope by tax year
     */
    public function scopeForYear($query, $year)
    {
        return $query->where('tax_year', $year);
    }

    /**
     * Scope by quarter
     */
    public function scopeForQuarter($query, $quarter)
    {
        return $query->where('quarter', $quarter);
    }

    /**
     * Scope by status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for overdue payments
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', '!=', 'PAID')
            ->where('due_date', '<', now());
    }

    /**
     * Scope for payments that are not yet paid
     */
    public function scopeUnpaid($query)
    {
        return $query->where('status', '!=', 'PAID');
    }

    /**
     * Generate payment number
     */
    public static function generatePaymentNo(int $companyId, int $year, int $quarter): string
    {
        $prefix = 'QPD';
        $lastPayment = self::where('company_id', $companyId)
            ->where('tax_year', $year)
            ->where('quarter', $quarter)
            ->orderBy('id', 'desc')
            ->first();
        
        $sequence = $lastPayment ? intval(substr($lastPayment->payment_no, -4)) + 1 : 1;
        
        return sprintf('%s-%d-Q%d-%04d', $prefix, $year, $quarter, $sequence);
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            // Set quarter_no to match quarter if not set
            if (is_null($payment->quarter_no)) {
                $payment->quarter_no = $payment->quarter;
            }
        });
    }
}