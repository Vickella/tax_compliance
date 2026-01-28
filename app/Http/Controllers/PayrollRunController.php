<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PayrollRunController extends Controller
{
    public function create(Request $request)
    {
        return view('payroll.runs.create');
    }

}
