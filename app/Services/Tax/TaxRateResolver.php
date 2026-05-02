<?php

namespace App\Services\Tax;

use App\Models\Tax\TaxSetting;
use App\Models\Tax\TaxMapping;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class TaxRateResolver
{
    protected $companyId;

    public function __construct($companyId = null)
    {
        $this->companyId = $companyId ?? company_id();
    }

    /**
     * Get VAT rate for a specific date (alias for getVatRate)
     */
    public function vatRate($date = null)
    {
        return $this->getVatRate($date);
    }

    /**
     * Get VAT rate for a specific date
     */
    public function getVatRate($date = null)
    {
        // Convert to Carbon instance
        $date = $this->parseDate($date);
        
        $cacheKey = "vat_rate_{$this->companyId}_{$date->format('Y-m-d')}";
        
        return Cache::remember($cacheKey, 3600, function() use ($date) {
            // Get from TaxSetting (VAT is 15.5% as per your info)
            $setting = TaxSetting::where('company_id', $this->companyId)
                ->effectiveAt($date)
                ->first();
                
            if ($setting) {
                return $setting->getVatRate();
            }
            
            // Default VAT rate from your info: 15.5%
            return 15.5;
        });
    }

    /**
     * Get NSSA rate
     */
    public function nssaRate($date = null)
    {
        $date = $this->parseDate($date);
        
        // NSSA is 4.5% as per your info
        return Cache::remember("nssa_rate_{$this->companyId}", 3600, function() {
            // You can store this in TaxSetting if needed
            return 4.5;
        });
    }

    /**
     * Get AIDS Levy rate
     */
    public function aidsLevyRate($date = null)
    {
        $date = $this->parseDate($date);
        
        return Cache::remember("aids_levy_rate_{$this->companyId}", 3600, function() use ($date) {
            $setting = TaxSetting::where('company_id', $this->companyId)
                ->effectiveAt($date)
                ->first();
                
            return $setting ? $setting->getAidsLevyRate() : 3.0;
        });
    }

    /**
     * Get corporate tax rate
     */
    public function corporateTaxRate($date = null)
    {
        $date = $this->parseDate($date);
        
        return Cache::remember("corporate_tax_rate_{$this->companyId}_{$date->format('Y-m-d')}", 3600, function() use ($date) {
            $setting = TaxSetting::where('company_id', $this->companyId)
                ->effectiveAt($date)
                ->first();
                
            return $setting ? $setting->getCorporateTaxRate() : 25.75;
        });
    }

    /**
     * Get VAT output accounts from TaxMapping
     */
    public function getVatOutputAccounts()
    {
        return Cache::remember("vat_output_accounts_{$this->companyId}", 3600, function() {
            return TaxMapping::getVatOutputAccounts($this->companyId);
        });
    }

    /**
     * Get VAT input accounts from TaxMapping
     */
    public function getVatInputAccounts()
    {
        return Cache::remember("vat_input_accounts_{$this->companyId}", 3600, function() {
            return TaxMapping::getVatInputAccounts($this->companyId);
        });
    }

    /**
     * Get income accounts from TaxMapping
     */
    public function getIncomeAccounts()
    {
        return Cache::remember("income_accounts_{$this->companyId}", 3600, function() {
            return TaxMapping::getIncomeAccounts($this->companyId);
        });
    }

    /**
     * Get expense accounts from TaxMapping
     */
    public function getExpenseAccounts()
    {
        return Cache::remember("expense_accounts_{$this->companyId}", 3600, function() {
            return TaxMapping::getExpenseAccounts($this->companyId);
        });
    }

    /**
     * Get non-deductible accounts from TaxMapping
     */
    public function getNonDeductibleAccounts()
    {
        return Cache::remember("non_deductible_accounts_{$this->companyId}", 3600, function() {
            return TaxMapping::getNonDeductibleAccounts($this->companyId);
        });
    }

    /**
     * Get partly deductible accounts from TaxMapping
     */
    public function getPartlyDeductibleAccounts()
    {
        return Cache::remember("partly_deductible_accounts_{$this->companyId}", 3600, function() {
            return TaxMapping::getPartlyDeductibleAccounts($this->companyId);
        });
    }

    /**
     * Get QPD percentage for a specific quarter
     */
    public function getQpdPercentage($quarter, $date = null)
    {
        $date = $this->parseDate($date);
        
        return Cache::remember("qpd_q{$quarter}_percent_{$this->companyId}_{$date->format('Y')}", 3600, function() use ($quarter, $date) {
            $setting = TaxSetting::where('company_id', $this->companyId)
                ->effectiveAt($date)
                ->first();
                
            return $setting ? $setting->getQpdPercentage($quarter) : [10, 25, 30, 35][$quarter - 1];
        });
    }

    /**
     * Parse date input to Carbon instance
     */
    protected function parseDate($date)
    {
        if ($date === null) {
            return Carbon::now();
        }
        
        if (is_numeric($date)) {
            return Carbon::createFromTimestamp($date);
        }
        
        if (is_string($date)) {
            return Carbon::parse($date);
        }
        
        if ($date instanceof Carbon) {
            return $date;
        }
        
        return Carbon::now();
    }
}