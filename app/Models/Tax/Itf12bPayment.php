<?php

namespace App\Models\Tax;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Itf12bPayment extends Model
{
    use HasFactory;

    protected $table = 'itf12b_payments';
    
    protected $fillable = [
        'company_id',
        'tax_year',
        'quarter',
        'payment_no',
        'payment_date',
        'due_date',
        'amount',
        'estimated_annual_profit',
        'estimated_tax',
        'payment_method',
        'reference',
        'status',
        'journal_entry_id',
        'metadata',
        'created_by',
        'submitted_by',
        'submitted_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'tax_year' => 'integer',
        'quarter' => 'integer',
        'payment_date' => 'date',
        'due_date' => 'date',
        'amount' => 'decimal:2',
        'estimated_annual_profit' => 'decimal:2',
        'estimated_tax' => 'decimal:2',
        'metadata' => 'json',
        'submitted_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'DRAFT',
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
     * Generate payment number
     */
    public static function generatePaymentNo(int $companyId, int $year, int $quarter): string
    {
        $prefix = 'ITF12B';
        $lastPayment = self::where('company_id', $companyId)
            ->where('tax_year', $year)
            ->where('quarter', $quarter)
            ->orderBy('id', 'desc')
            ->first();
        
        $sequence = $lastPayment ? intval(substr($lastPayment->payment_no, -4)) + 1 : 1;
        
        return sprintf('%s-%d-Q%d-%04d', $prefix, $year, $quarter, $sequence);
    }
}