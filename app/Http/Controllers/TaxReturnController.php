<?php

namespace App\Http\Controllers;

use App\Http\Requests\IncomeTaxRequest;
use App\Services\TaxCalculator;
use Illuminate\Http\Request;

class TaxReturnController extends Controller
{
    public function vatReturn(Request $request, TaxCalculator $calculator)
    {
        $vatRate = config('tax.vat_rate');

        return view('tax.vat-return', [
            'vatRate' => $vatRate,
        ]);
    }

    public function qpd(Request $request, TaxCalculator $calculator)
    {
        $estimatedAnnualTax = (float) $request->input('estimated_annual_tax', 0.0);
        $schedule = $calculator->calculateQpdSchedule($estimatedAnnualTax);

        return view('tax.qpd', [
            'estimatedAnnualTax' => $estimatedAnnualTax,
            'schedule' => $schedule,
        ]);
    }

    public function incomeTax(IncomeTaxRequest $request, TaxCalculator $calculator)
    {
        $taxableIncome = (float) $request->input('taxable_income', 0.0);
        $nonDeductible = (float) $request->input('non_deductible_expenses', 0.0);
        $result = $calculator->calculateIncomeTax($taxableIncome, $nonDeductible);

        return view('tax.income-tax', [
            'taxableIncome' => $taxableIncome,
            'nonDeductible' => $nonDeductible,
            'result' => $result,
            'incomeTaxRate' => config('tax.income_tax_rate'),
        ]);
    }
    public function vatReturn(Request $request)
    {
        return view('tax.vat-return');
    }

    public function qpd(Request $request)
    {
        return view('tax.qpd');
    }

    public function incomeTax(Request $request)
    {
        return view('tax.income-tax');
    }

}
