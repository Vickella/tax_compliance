<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        return view('security.permissions.index');
    }

}