<?php

namespace App\Models\Tax;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ChartOfAccount;

class TaxMapping extends Model
{
    use HasFactory;

    protected $table = 'tax_mapping';
    
    protected $fillable = [
        'company_id',
        'tax_type',
        'account_code',
        'mapping_type',
        'deductible_percent',
        'notes',
    ];

    protected $casts = [
        'deductible_percent' => 'decimal:2',
    ];

    /**
     * Get the account associated with this mapping
     */
    public function account()
    {
        return $this->belongsTo(ChartOfAccount::class, 'account_code', 'code');
    }

    /**
     * Get the company that owns this mapping
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Scope by tax type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('tax_type', $type);
    }

    /**
     * Scope by mapping type
     */
    public function scopeOfMapping($query, $mappingType)
    {
        return $query->where('mapping_type', $mappingType);
    }

    /**
     * Get income accounts
     */
    public static function getIncomeAccounts(int $companyId): array
    {
        return self::where('company_id', $companyId)
            ->where('tax_type', 'INCOME_TAX')
            ->where('mapping_type', 'INCOME')
            ->pluck('account_code')
            ->toArray();
    }

    /**
     * Get expense accounts
     */
    public static function getExpenseAccounts(int $companyId): array
    {
        return self::where('company_id', $companyId)
            ->where('tax_type', 'INCOME_TAX')
            ->where('mapping_type', 'EXPENSE')
            ->pluck('account_code')
            ->toArray();
    }

    /**
     * Get non-deductible accounts
     */
    public static function getNonDeductibleAccounts(int $companyId): array
    {
        return self::where('company_id', $companyId)
            ->where('tax_type', 'INCOME_TAX')
            ->where('mapping_type', 'NON_DEDUCTIBLE')
            ->pluck('account_code')
            ->toArray();
    }

    /**
     * Get partly deductible accounts with percentages
     */
    public static function getPartlyDeductibleAccounts(int $companyId): \Illuminate\Support\Collection
    {
        return self::where('company_id', $companyId)
            ->where('tax_type', 'INCOME_TAX')
            ->where('mapping_type', 'PARTLY_DEDUCTIBLE')
            ->get()
            ->keyBy('account_code');
    }

    /**
     * Get VAT output accounts
     */
    public static function getVatOutputAccounts(int $companyId): array
    {
        return self::where('company_id', $companyId)
            ->where('tax_type', 'VAT')
            ->where('mapping_type', 'VAT_OUTPUT')
            ->pluck('account_code')
            ->toArray();
    }

    /**
     * Get VAT input accounts
     */
    public static function getVatInputAccounts(int $companyId): array
    {
        return self::where('company_id', $companyId)
            ->where('tax_type', 'VAT')
            ->where('mapping_type', 'VAT_INPUT')
            ->pluck('account_code')
            ->toArray();
    }
}