<?php

namespace App\Models\Tax;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Company; // Add this import
use App\Models\User;    // Add this if not already imported

class VatReturn extends Model
{
    use HasFactory;

    protected $table = 'vat_returns';
    
    protected $fillable = [
        'company_id',
        'period_start',
        'period_end',
        'vat_rate',
        'taxable_sales',
        'output_vat',
        'taxable_purchases',
        'input_vat',
        'net_vat_payable',
        'notes',
        'source_snapshot',
        'period_id',
        'return_type',
        'status',
        'submitted_by',
        'submitted_at',
        'output_tax',
        'input_tax',
        'net_vat',
        'generated_at',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'generated_at' => 'datetime',
        'submitted_at' => 'datetime',
        'vat_rate' => 'decimal:4',
        'taxable_sales' => 'decimal:2',
        'output_vat' => 'decimal:2',
        'taxable_purchases' => 'decimal:2',
        'input_vat' => 'decimal:2',
        'net_vat_payable' => 'decimal:2',
        'output_tax' => 'decimal:2',
        'input_tax' => 'decimal:2',
        'net_vat' => 'decimal:2',
    ];

    protected $attributes = [
        'status' => 'DRAFT',
        'return_type' => 'VAT7',
    ];

    /**
     * Get the company that owns this return
     */
    public function company()
    {
        return $this->belongsTo(Company::class); // Now it will use App\Models\Company
    }

    /**
     * Get the period that owns this return
     */
    public function period()
    {
        return $this->belongsTo(Period::class); // Make sure Period model exists or comment out
    }

    /**
     * Get the user who submitted this return
     */
    public function submittedBy()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    /**
     * Get the period name (e.g., "January 2026")
     */
    public function getPeriodNameAttribute(): string
    {
        return $this->period_start->format('F Y');
    }

    /**
     * Calculate tax fraction
     */
    public function getTaxFractionAttribute(): float
    {
        return $this->vat_rate / (100 + $this->vat_rate);
    }

    /**
     * Get net VAT (alias for net_vat_payable)
     */
    public function getVatPayableAttribute(): float
    {
        return $this->net_vat_payable ?? $this->net_vat ?? 0;
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
     * Scope by return type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('return_type', $type);
    }
}