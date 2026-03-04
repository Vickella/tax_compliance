<?php

namespace App\Models\Tax;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VatReturn extends Model
{
    use HasFactory;

    protected $table = 'vat_returns';
    
    protected $fillable = [
        'company_id',
        'period_start',
        'period_end',
        'return_no',
        'filing_date',
        'status',
        'output_vat',
        'input_vat',
        'vat_payable',
        'vat_rate',
        'output_accounts',
        'input_accounts',
        'metadata',
        'submitted_by',
        'submitted_at',
        'approved_by',
        'approved_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'filing_date' => 'date',
        'output_vat' => 'decimal:2',
        'input_vat' => 'decimal:2',
        'vat_payable' => 'decimal:2',
        'vat_rate' => 'decimal:4',
        'output_accounts' => 'json',
        'input_accounts' => 'json',
        'metadata' => 'json',
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
     * Get the period name (e.g., "January 2026")
     */
    public function getPeriodNameAttribute(): string
    {
        return $this->period_start->format('F Y');
    }

    /**
     * Check if return is for a specific month
     */
    public function isForMonth(int $year, int $month): bool
    {
        return $this->period_start->year == $year && $this->period_start->month == $month;
    }

    /**
     * Calculate tax fraction
     */
    public function getTaxFractionAttribute(): float
    {
        return $this->vat_rate / (100 + $this->vat_rate);
    }

    /**
     * Scope by period
     */
    public function scopeForPeriod($query, $start, $end)
    {
        return $query->where('period_start', $start)->where('period_end', $end);
    }

    /**
     * Scope by status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Generate return number
     */
    public static function generateReturnNo(int $companyId, string $periodStart): string
    {
        $prefix = 'VAT';
        $year = date('Y', strtotime($periodStart));
        $month = date('m', strtotime($periodStart));
        
        $lastReturn = self::where('company_id', $companyId)
            ->whereYear('period_start', $year)
            ->whereMonth('period_start', $month)
            ->orderBy('id', 'desc')
            ->first();
        
        $sequence = $lastReturn ? intval(substr($lastReturn->return_no, -2)) + 1 : 1;
        
        return sprintf('%s-%s%s-%02d', $prefix, $year, $month, $sequence);
    }
}