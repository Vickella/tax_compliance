<?php

namespace App\Http\Controllers;

use App\Http\Requests\PayrollRunRequest;
use App\Services\PayrollCalculator;

class PayrollRunController extends Controller
{
    public function create(PayrollRunRequest $request, PayrollCalculator $calculator)
    {
        $earnings = $request->input('earnings', [
            'basic_salary' => 0.0,
            'housing_allowance' => 0.0,
            'transport_allowance' => 0.0,
            'other_income' => 0.0,
        ]);

        $calculated = $calculator->calculate($earnings);
        $summary = $calculator->summarize([
            array_merge($earnings, $calculated),
        ]);

        return view('payroll.runs.create', [
            'earnings' => $earnings,
            'calculated' => $calculated,
            'summary' => $summary,
        ]);
    }
}
