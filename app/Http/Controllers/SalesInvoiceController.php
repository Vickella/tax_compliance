<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SalesInvoiceController extends Controller
{
    public function index(Request $request)
    {
        return view('sales.invoices.index');
    }

    public function create(Request $request)
    {
        return view('sales.invoices.create');
    }

}