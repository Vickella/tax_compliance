<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StockEntryController extends Controller
{
    public function index(Request $request)
    {
        return view('inventory.entry.index');
    }

}