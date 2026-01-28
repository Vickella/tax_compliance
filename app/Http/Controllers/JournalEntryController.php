<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JournalEntryController extends Controller
{
    public function index(Request $request)
    {
        return view('accounting.journals.index');
    }

}