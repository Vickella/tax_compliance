<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChartOfAccountController extends Controller
{
    public function index(Request $request)
    {
        return view('accounting.coa.index');
    }

}