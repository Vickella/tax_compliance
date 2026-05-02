<?php

namespace App\Models\Tax;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaxSetting extends Model
{
    use HasFactory;

    protected $table = 'tax_settings';
    
    protected $fillable = [
        'company_id',
        'vat_rate',
        'income_tax_rate',
        'qpd_q1_percent',
        'qpd_q2_percent',
        'qpd_q3_percent',
        'qpd_q4_percent',
        'qpd_q1_due',
        'qpd_q2_due',
        'qpd_q3_due',
        'qpd_q4_due',
        'vat_rules',
        'income_tax_rules',
        'capital_allowance_rules',
        'penalty_rules',
        'effective_from',
        'effective_to',
        'metadata',
    ];

    protected $casts = [
        'vat_rate' => 'decimal:4',
        'income_tax_rate' => 'decimal:4',
        'qpd_q1_percent' => 'decimal:4',
        'qpd_q2_percent' => 'decimal:4',
        'qpd_q3_percent' => 'decimal:4',
        'qpd_q4_percent' => 'decimal:4',
        'qpd_q1_due' => 'date',
        'qpd_q2_due' => 'date',
        'qpd_q3_due' => 'date',
        'qpd_q4_due' => 'date',
        'vat_rules' => 'json',
        'income_tax_rules' => 'json',
        'capital_allowance_rules' => 'json',
        'penalty_rules' => 'json',
        'effective_from' => 'date',
        'effective_to' => 'date',
        'metadata' => 'json',
    ];

    /**
     * Get the company that owns these tax settings
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get VAT rate (from either column or JSON)
     */
    public function getVatRate(): float
    {
        if ($this->vat_rate) {
            return (float) $this->vat_rate;
        }
        return (float) ($this->vat_rules['standard_rate'] ?? 15);
    }

    /**
     * Get corporate tax rate
     */
    public function getCorporateTaxRate(): float
    {
        if ($this->income_tax_rate) {
            return (float) $this->income_tax_rate;
        }
        return (float) ($this->income_tax_rules['corporate_rate'] ?? 25.75);
    }

    /**
     * Get AIDS levy rate
     */
    public function getAidsLevyRate(): float
    {
        return (float) ($this->income_tax_rules['aids_levy_rate'] ?? 3);
    }

    /**
     * Get QPD percentage for a specific quarter
     */
    public function getQpdPercentage(int $quarter): float
    {
        $column = "qpd_q{$quarter}_percent";
        if ($this->$column) {
            return (float) $this->$column;
        }
        
        $defaults = [10, 25, 30, 35];
        return (float) ($this->income_tax_rules["qpd_q{$quarter}_percent"] ?? $defaults[$quarter - 1]);
    }

    /**
     * Get QPD due date for a specific quarter
     */
    public function getQpdDueDate(int $quarter): ?string
    {
        $column = "qpd_q{$quarter}_due";
        if ($this->$column) {
            return $this->$column;
        }
        
        $defaults = [
            1 => date('Y') . '-03-25',
            2 => date('Y') . '-06-25',
            3 => date('Y') . '-09-25',
            4 => date('Y') . '-12-20',
        ];
        
        return $this->income_tax_rules["qpd_q{$quarter}_due"] ?? $defaults[$quarter];
    }

    /**
     * Get non-deductible account codes
     */
    public function getNonDeductibleAccounts(): array
    {
        return $this->income_tax_rules['non_deductible_accounts'] ?? [];
    }

    /**
     * Get partly deductible accounts with percentages
     */
    public function getPartlyDeductibleAccounts(): array
    {
        return $this->income_tax_rules['partly_deductible'] ?? [];
    }

    /**
     * Get capital allowance rates
     */
    public function getCapitalAllowanceRates(): array
    {
        return $this->capital_allowance_rules ?? [
            'furniture' => ['rate' => 20, 'method' => 'straight_line'],
            'computers' => ['rate' => 33.33, 'method' => 'straight_line'],
            'motor_vehicles' => ['rate' => 25, 'method' => 'straight_line'],
            'plant' => ['rate' => 15, 'method' => 'straight_line'],
            'buildings' => ['rate' => 5, 'method' => 'straight_line'],
        ];
    }

    /**
     * Get penalty rules
     */
    public function getPenaltyRules(): array
    {
        return $this->penalty_rules ?? [
            'late_submission' => ['amount' => 1000, 'type' => 'fixed'],
            'late_payment' => ['rate' => 5, 'type' => 'percentage', 'max_months' => 12],
        ];
    }

    /**
     * Scope for active settings at a given date
     */
    public function scopeEffectiveAt($query, $date)
    {
        return $query->where('effective_from', '<=', $date)
            ->where(function ($q) use ($date) {
                $q->whereNull('effective_to')
                  ->orWhere('effective_to', '>=', $date);
            });
    }
}