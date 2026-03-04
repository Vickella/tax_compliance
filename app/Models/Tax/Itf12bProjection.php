<?php

namespace App\Models\Tax;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Itf12bProjection extends Model
{
    use HasFactory;

    protected $table = 'itf12b_projections';
    
    protected $fillable = [
        'company_id',
        'tax_year',
        'projection_date',
        'estimated_annual_profit',
        'estimated_tax',
        'q1_profit',
        'q2_profit',
        'q3_profit',
        'q4_profit',
        'q1_tax',
        'q2_tax',
        'q3_tax',
        'q4_tax',
        'notes',
        'metadata',
        'created_by',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'tax_year' => 'integer',
        'projection_date' => 'date',
        'estimated_annual_profit' => 'decimal:2',
        'estimated_tax' => 'decimal:2',
        'q1_profit' => 'decimal:2',
        'q2_profit' => 'decimal:2',
        'q3_profit' => 'decimal:2',
        'q4_profit' => 'decimal:2',
        'q1_tax' => 'decimal:2',
        'q2_tax' => 'decimal:2',
        'q3_tax' => 'decimal:2',
        'q4_tax' => 'decimal:2',
        'metadata' => 'json',
    ];

    /**
     * Get the company that owns this projection
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the user who created this projection
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Calculate total projected profit
     */
    public function getTotalProjectedProfitAttribute(): float
    {
        return $this->q1_profit + $this->q2_profit + $this->q3_profit + $this->q4_profit;
    }

    /**
     * Calculate total projected tax
     */
    public function getTotalProjectedTaxAttribute(): float
    {
        return $this->q1_tax + $this->q2_tax + $this->q3_tax + $this->q4_tax;
    }

    /**
     * Check if projection matches estimate
     */
    public function isMatchingEstimate(): bool
    {
        return abs($this->totalProjectedProfit - $this->estimated_annual_profit) < 0.01;
    }

    /**
     * Get variance from estimate
     */
    public function getVarianceAttribute(): float
    {
        return round($this->totalProjectedProfit - $this->estimated_annual_profit, 2);
    }

    /**
     * Get variance percentage
     */
    public function getVariancePercentageAttribute(): float
    {
        if ($this->estimated_annual_profit == 0) {
            return 0;
        }
        return round(($this->variance / $this->estimated_annual_profit) * 100, 2);
    }

    /**
     * Scope by tax year
     */
    public function scopeForYear($query, $year)
    {
        return $query->where('tax_year', $year);
    }

    /**
     * Get the latest projection for a year
     */
    public static function latestForYear(int $companyId, int $year): ?self
    {
        return self::where('company_id', $companyId)
            ->where('tax_year', $year)
            ->orderBy('projection_date', 'desc')
            ->first();
    }
}