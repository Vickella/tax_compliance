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
    Route::resource('sales/invoices', App\Http\Controllers\SalesInvoiceController::class)
        ->only(['index', 'create', 'store'])
        ->names('sales.invoices');
    Route::resource('purchases/invoices', App\Http\Controllers\PurchaseInvoiceController::class)
        ->only(['index', 'create', 'store'])
        ->names('purchases.invoices');

    Route::resource('inventory/items', App\Http\Controllers\InventoryItemController::class)
        ->only(['index', 'create', 'store', 'show'])
        ->names('inventory.items');
    Route::get('/inventory/warehouses', [App\Http\Controllers\WarehouseController::class, 'index'])->name('inventory.warehouses.index');
    Route::get('/inventory/entry', [App\Http\Controllers\StockEntryController::class, 'index'])->name('inventory.entry.index');
    Route::get('/inventory/ledger', [App\Http\Controllers\StockLedgerController::class, 'index'])->name('inventory.ledger');

    Route::get('/banking/receipts', [App\Http\Controllers\BankingReceiptController::class, 'index'])->name('banking.receipts.index');
    Route::get('/banking/reconciliation', [App\Http\Controllers\BankReconciliationController::class, 'index'])->name('banking.reconciliation');

    Route::get('/accounting/coa', [App\Http\Controllers\ChartOfAccountController::class, 'index'])->name('accounting.coa.index');
    Route::get('/accounting/journals', [App\Http\Controllers\JournalEntryController::class, 'index'])->name('accounting.journals.index');

    Route::get('/reports/trial-balance', [App\Http\Controllers\ReportController::class, 'trialBalance'])->name('reports.trial-balance');
    Route::get('/reports/pnl', [App\Http\Controllers\ReportController::class, 'profitAndLoss'])->name('reports.pnl');
    Route::get('/reports/balance-sheet', [App\Http\Controllers\ReportController::class, 'balanceSheet'])->name('reports.balance-sheet');

    Route::get('/tax/vat-return', [App\Http\Controllers\TaxReturnController::class, 'vatReturn'])->name('tax.vat-return');
    Route::get('/tax/qpd', [App\Http\Controllers\TaxReturnController::class, 'qpd'])->name('tax.qpd');
    Route::get('/tax/income-tax', [App\Http\Controllers\TaxReturnController::class, 'incomeTax'])->name('tax.income-tax');

    Route::resource('crm/customers', App\Http\Controllers\CustomerController::class)
        ->only(['index', 'create', 'store'])
        ->names('crm.customers');
    Route::get('/crm/customer-statements', [App\Http\Controllers\CustomerStatementController::class, 'index'])->name('crm.customer-statements');
    Route::resource('crm/suppliers', App\Http\Controllers\SupplierController::class)
        ->only(['index', 'create', 'store'])
        ->names('crm.suppliers');
    Route::get('/crm/supplier-ledger', [App\Http\Controllers\SupplierLedgerController::class, 'index'])->name('crm.supplier-ledger');

    Route::get('/payroll/employees', [App\Http\Controllers\EmployeeController::class, 'index'])->name('payroll.employees.index');
    Route::get('/payroll/runs/create', [App\Http\Controllers\PayrollRunController::class, 'create'])->name('payroll.runs.create');

    Route::get('/compliance/vat', [App\Http\Controllers\ComplianceController::class, 'vat'])->name('compliance.vat');
    Route::get('/compliance/qpd', [App\Http\Controllers\ComplianceController::class, 'qpd'])->name('compliance.qpd');
    Route::get('/compliance/payroll', [App\Http\Controllers\ComplianceController::class, 'payroll'])->name('compliance.payroll');
    Route::get('/compliance/ocr', [App\Http\Controllers\ComplianceController::class, 'ocr'])->name('compliance.ocr');

    Route::get('/security/users', [App\Http\Controllers\UserController::class, 'index'])->name('security.users.index');
    Route::get('/security/roles', [App\Http\Controllers\RoleController::class, 'index'])->name('security.roles.index');
    Route::get('/security/permissions', [App\Http\Controllers\PermissionController::class, 'index'])->name('security.permissions.index');

    Route::get('/settings/company', [App\Http\Controllers\SettingsController::class, 'company'])->name('settings.company');
    Route::get('/settings/tax', [App\Http\Controllers\SettingsController::class, 'tax'])->name('settings.tax');
    Route::get('/settings/currencies', [App\Http\Controllers\SettingsController::class, 'currencies'])->name('settings.currencies');
    Route::get('/settings/periods', [App\Http\Controllers\SettingsController::class, 'periods'])->name('settings.periods');
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
