<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TaxReturnController extends Controller
{
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
