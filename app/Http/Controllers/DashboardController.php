<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        $modules = self::modules();

        return view('dashboard.home', compact('modules'));
    }

    public static function modules(): array
    {
        return [
            // Tax Filing
            ['key' => 'paye',                 'name' => 'PAYE',                 'icon' => '💰', 'category' => 'Tax Filing'],
            ['key' => 'vat',                  'name' => 'VAT',                  'icon' => '📊', 'category' => 'Tax Filing'],
            ['key' => 'income-tax',           'name' => 'Income Tax',           'icon' => '🏢', 'category' => 'Tax Filing'],
            ['key' => 'quarterly-payments',   'name' => 'Quarterly Payments',   'icon' => '📋', 'category' => 'Tax Filing'],
            
            // Settings
            ['key' => 'company-settings',     'name' => 'Company Settings',     'icon' => '⚙️', 'category' => 'Settings'],
            
            // Transactions
            ['key' => 'sales',                'name' => 'Sales',                'icon' => '🧾', 'category' => 'Transactions'],
            ['key' => 'purchases',            'name' => 'Purchases',            'icon' => '📥', 'category' => 'Transactions'],
            ['key' => 'accounting',           'name' => 'Accounting',           'icon' => '📚', 'category' => 'Transactions'],
            ['key' => 'inventory',            'name' => 'Inventory',            'icon' => '📦', 'category' => 'Transactions'],
        ];
    }
}
