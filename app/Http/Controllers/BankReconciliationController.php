<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BankReconciliationController extends Controller
{
    public function index(Request $request)
    {
        return view('banking.reconciliation');
    }

}