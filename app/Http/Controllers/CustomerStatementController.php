<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerStatementController extends Controller
{
    public function index(Request $request)
    {
        return view('crm.customer-statements');
    }

}