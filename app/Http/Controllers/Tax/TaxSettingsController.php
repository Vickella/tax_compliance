<?php

namespace App\Http\Controllers\Tax;

use App\Http\Controllers\Controller;
use App\Models\Tax\TaxSetting;
use App\Models\Tax\TaxMapping;
use Illuminate\Http\Request;

class TaxSettingsController extends Controller
{
    /**
     * Edit tax settings
     */
    public function edit()
    {
        $settings = TaxSetting::where('company_id', company_id())->first();
        
        if (!$settings) {
            $settings = TaxSetting::create([
                'company_id' => company_id(),
                'vat_rate' => 15,
                'income_tax_rate' => 25.75,
                'qpd_q1_percent' => 10,
                'qpd_q2_percent' => 25,
                'qpd_q3_percent' => 30,
                'qpd_q4_percent' => 35,
                'qpd_q1_due' => date('Y') . '-03-25',
                'qpd_q2_due' => date('Y') . '-06-25',
                'qpd_q3_due' => date('Y') . '-09-25',
                'qpd_q4_due' => date('Y') . '-12-20',
            ]);
        }

        $mappings = TaxMapping::where('company_id', company_id())->get();

        return view('modules.tax.settings.edit', compact('settings', 'mappings'));
    }

    /**
     * Update tax settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'vat_rate' => 'required|numeric|min:0|max:100',
            'income_tax_rate' => 'required|numeric|min:0|max:100',
            'aids_levy_rate' => 'nullable|numeric|min:0|max:100',
            'qpd_q1_percent' => 'required|numeric|min:0|max:100',
            'qpd_q2_percent' => 'required|numeric|min:0|max:100',
            'qpd_q3_percent' => 'required|numeric|min:0|max:100',
            'qpd_q4_percent' => 'required|numeric|min:0|max:100',
            'qpd_q1_due' => 'required|date',
            'qpd_q2_due' => 'required|date',
            'qpd_q3_due' => 'required|date',
            'qpd_q4_due' => 'required|date',
        ]);

        $settings = TaxSetting::where('company_id', company_id())->first();
        $settings->update($validated);

        // Update income tax rules JSON
        $incomeTaxRules = $settings->income_tax_rules ?? [];
        $incomeTaxRules['aids_levy_rate'] = $request->aids_levy_rate ?? 3;
        $settings->income_tax_rules = $incomeTaxRules;
        $settings->save();

        return back()->with('success', 'Tax settings updated successfully');
    }

    /**
     * Update tax mapping
     */
    public function updateMapping(Request $request)
    {
        $validated = $request->validate([
            'mappings' => 'required|array',
            'mappings.*.id' => 'nullable|exists:tax_mapping,id',
            'mappings.*.tax_type' => 'required|in:VAT,INCOME_TAX,QPD',
            'mappings.*.account_code' => 'required|exists:chart_of_accounts,code',
            'mappings.*.mapping_type' => 'required|string',
            'mappings.*.deductible_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        foreach ($validated['mappings'] as $mappingData) {
            TaxMapping::updateOrCreate(
                [
                    'company_id' => company_id(),
                    'account_code' => $mappingData['account_code'],
                    'tax_type' => $mappingData['tax_type'],
                ],
                [
                    'mapping_type' => $mappingData['mapping_type'],
                    'deductible_percent' => $mappingData['deductible_percent'] ?? 100,
                ]
            );
        }

        return back()->with('success', 'Tax mappings updated successfully');
    }
}