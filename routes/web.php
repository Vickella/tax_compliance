<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('/sales/invoices', 'sales.invoices.index')->name('sales.invoices.index');
    Route::view('/sales/invoices/create', 'sales.invoices.create')->name('sales.invoices.create');
    Route::view('/purchases/invoices', 'purchases.invoices.index')->name('purchases.invoices.index');
    Route::view('/purchases/invoices/create', 'purchases.invoices.create')->name('purchases.invoices.create');

    Route::view('/inventory/items', 'inventory.items.index')->name('inventory.items.index');
    Route::view('/inventory/warehouses', 'inventory.warehouses.index')->name('inventory.warehouses.index');
    Route::view('/inventory/entry', 'inventory.entry.index')->name('inventory.entry.index');
    Route::view('/inventory/ledger', 'inventory.ledger')->name('inventory.ledger');

    Route::view('/banking/receipts', 'banking.receipts.index')->name('banking.receipts.index');
    Route::view('/banking/reconciliation', 'banking.reconciliation')->name('banking.reconciliation');

    Route::view('/accounting/coa', 'accounting.coa.index')->name('accounting.coa.index');
    Route::view('/accounting/journals', 'accounting.journals.index')->name('accounting.journals.index');

    Route::view('/reports/trial-balance', 'reports.trial-balance')->name('reports.trial-balance');
    Route::view('/reports/pnl', 'reports.pnl')->name('reports.pnl');
    Route::view('/reports/balance-sheet', 'reports.balance-sheet')->name('reports.balance-sheet');

    Route::view('/tax/vat-return', 'tax.vat-return')->name('tax.vat-return');
    Route::view('/tax/qpd', 'tax.qpd')->name('tax.qpd');
    Route::view('/tax/income-tax', 'tax.income-tax')->name('tax.income-tax');

    Route::view('/crm/customers', 'crm.customers.index')->name('crm.customers.index');
    Route::view('/crm/customer-statements', 'crm.customer-statements')->name('crm.customer-statements');
    Route::view('/crm/suppliers', 'crm.suppliers.index')->name('crm.suppliers.index');
    Route::view('/crm/supplier-ledger', 'crm.supplier-ledger')->name('crm.supplier-ledger');

    Route::view('/payroll/employees', 'payroll.employees.index')->name('payroll.employees.index');
    Route::view('/payroll/runs/create', 'payroll.runs.create')->name('payroll.runs.create');

    Route::view('/compliance/vat', 'compliance.vat')->name('compliance.vat');
    Route::view('/compliance/qpd', 'compliance.qpd')->name('compliance.qpd');
    Route::view('/compliance/payroll', 'compliance.payroll')->name('compliance.payroll');
    Route::view('/compliance/ocr', 'compliance.ocr')->name('compliance.ocr');

    Route::view('/security/users', 'security.users.index')->name('security.users.index');
    Route::view('/security/roles', 'security.roles.index')->name('security.roles.index');
    Route::view('/security/permissions', 'security.permissions.index')->name('security.permissions.index');

    Route::view('/settings/company', 'settings.company')->name('settings.company');
    Route::view('/settings/tax', 'settings.tax')->name('settings.tax');
    Route::view('/settings/currencies', 'settings.currencies')->name('settings.currencies');
    Route::view('/settings/periods', 'settings.periods')->name('settings.periods');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
