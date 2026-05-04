<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        $modules = self::modules();

        $shortcuts = [
            ['label' => 'Sales Invoice',     'icon' => '🧾', 'route' => route('modules.sales.invoices.index')],
            ['label' => 'Purchase Invoice',  'icon' => '📥', 'route' => route('modules.purchases.invoices.index')],
            ['label' => 'Items',             'icon' => '📦', 'route' => route('modules.inventory.items.index')],
            ['label' => 'Customers',         'icon' => '👤', 'route' => route('modules.sales.customers.index')],
            ['label' => 'Suppliers',         'icon' => '🏭', 'route' => route('modules.purchases.suppliers.index')],
            ['label' => 'Journal Entry',     'icon' => '📚', 'route' => route('modules.accounting.journals.index')],
            ['label' => 'Payroll Run',       'icon' => '👥', 'route' => route('modules.payroll.runs.index')],
        ];

        $cards = [
            ['title' => 'Today', 'items' => ['Sales Invoices', 'Purchase Invoices', 'Payments', 'Stock Moves']],
            ['title' => 'This Period', 'items' => ['VAT (Draft)', 'Payroll (Draft)', 'QPD (Due)', 'Compliance Flags']],
            ['title' => 'Quick Setup', 'items' => ['Company Profile', 'Tax Rates', 'PAYE Bands', 'Fiscal Periods']],
        ];

        return view('dashboard.home', compact('modules', 'shortcuts', 'cards'));
    }

    public static function modules(): array
    {
        return [
            ['key' => 'company-settings',     'name' => 'Company Settings',     'icon' => '⚙️'],
            ['key' => 'sales',                'name' => 'Sales',                'icon' => '🧾'],
            ['key' => 'purchases',            'name' => 'Purchases',            'icon' => '📥'],
            ['key' => 'accounting',           'name' => 'Accounting',           'icon' => '📚'],
            ['key' => 'payroll',              'name' => 'Payroll',              'icon' => '👥'],
            ['key' => 'tax',                  'name' => 'Tax',                  'icon' => '📈'],
            ['key' => 'stock-management',     'name' => 'Stock Management',     'icon' => '📦'],
        ];
    }
}
