<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ComplianceController extends Controller
{
    public function vat(Request $request)
    {
        return view('compliance.vat');
    }

    public function qpd(Request $request)
    {
        return view('compliance.qpd');
    }

    public function payroll(Request $request)
    {
        return view('compliance.payroll');
    }

    public function ocr(Request $request)
    {
        return view('compliance.ocr');
    }

}