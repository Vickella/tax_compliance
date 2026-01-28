<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StockLedgerController extends Controller
{
    public function index(Request $request)
    {
        return view('inventory.ledger');
    }

}