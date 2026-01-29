<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupplierLedgerController extends Controller
{
    public function index(Request $request)
    {
        return view('crm.supplier-ledger');
    }

}