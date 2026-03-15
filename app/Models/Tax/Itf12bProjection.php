<?php

namespace App\Models\Tax;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Company;
use App\Models\User;
use App\Models\ChartOfAccount;

class Itf12bProjection extends Model
{
    use HasFactory;

    protected $table = 'itf12b_projections';
    
    protected $fillable = [
        'company_id',
        'account_id',
        'tax_year',
        'forecast_method',
        'fixed_amount',
        'growth_rate',
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
    ];

    protected $casts = [
        'account_id' => 'integer',
        'tax_year' => 'integer',
        'fixed_amount' => 'decimal:2',
        'growth_rate' => 'decimal:2',
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
        'projection_date' => 'date',
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
     * Get the account that this projection is for
     */
    public function account()
    {
        return $this->belongsTo(ChartOfAccount::class, 'account_id');
    }

    /**
     * Get the user who created this projection
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Calculate total projected profit for all quarters
     */
    public function getTotalProjectedProfitAttribute(): float
    {
        return (float)($this->q1_profit + $this->q2_profit + $this->q3_profit + $this->q4_profit);
    }

    /**
     * Calculate total projected tax for all quarters
     */
    public function getTotalProjectedTaxAttribute(): float
    {
        return (float)($this->q1_tax + $this->q2_tax + $this->q3_tax + $this->q4_tax);
    }

    /**
     * Check if this is a company-level projection (no specific account)
     */
    public function getIsCompanyLevelAttribute(): bool
    {
        return is_null($this->account_id);
    }

    /**
     * Get forecast method display name
     */
    public function getMethodDisplayAttribute(): string
    {
        $methods = [
            'linear' => 'Linear Growth',
            'fixed' => 'Fixed Amount',
            'average' => 'Monthly Average',
        ];
        
        return $methods[$this->forecast_method] ?? ucfirst($this->forecast_method);
    }

    /**
     * Scope by tax year
     */
    public function scopeForYear($query, $year)
    {
        return $query->where('tax_year', $year);
    }

    /**
     * Scope by account
     */
    public function scopeForAccount($query, $accountId)
    {
        return $query->where('account_id', $accountId);
    }

    /**
     * Scope for company-level projections (no account)
     */
    public function scopeCompanyLevel($query)
    {
        return $query->whereNull('account_id');
    }

    /**
     * Scope for account-specific projections
     */
    public function scopeAccountLevel($query)
    {
        return $query->whereNotNull('account_id');
    }

    /**
     * Get the latest projection for a specific account and year
     */
    public static function latestForAccount(int $companyId, int $accountId, int $year): ?self
    {
        return self::where('company_id', $companyId)
            ->where('account_id', $accountId)
            ->where('tax_year', $year)
            ->orderBy('projection_date', 'desc')
            ->first();
    }

    /**
     * Get the latest company-level projection for a year
     */
    public static function latestForCompany(int $companyId, int $year): ?self
    {
        return self::where('company_id', $companyId)
            ->whereNull('account_id')
            ->where('tax_year', $year)
            ->orderBy('projection_date', 'desc')
            ->first();
    }

    /**
     * Get all active forecast profiles for a year
     */
    public static function getActiveProfiles(int $companyId, int $year): \Illuminate\Support\Collection
    {
        return self::where('company_id', $companyId)
            ->where('tax_year', $year)
            ->with('account')
            ->orderBy('account_id')
            ->get();
    }
}