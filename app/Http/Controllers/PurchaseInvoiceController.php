<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PurchaseInvoiceController extends Controller
{
    public function index(Request $request)
    {
        return view('purchases.invoices.index');
    }

    public function create(Request $request)
    {
        return view('purchases.invoices.create');
    }

}