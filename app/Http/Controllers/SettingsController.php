<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function company(Request $request)
    {
        return view('settings.company');
    }

    public function tax(Request $request)
    {
        return view('settings.tax');
    }

    public function currencies(Request $request)
    {
        return view('settings.currencies');
    }

    public function periods(Request $request)
    {
        return view('settings.periods');
    }

}