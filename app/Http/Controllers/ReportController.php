<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function trialBalance(Request $request)
    {
        return view('reports.trial-balance');
    }

    public function profitAndLoss(Request $request)
    {
        return view('reports.pnl');
    }

    public function balanceSheet(Request $request)
    {
        return view('reports.balance-sheet');
    }

}