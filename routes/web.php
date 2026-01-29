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
    Route::get('/purchases/invoices', [App\Http\Controllers\PurchaseInvoiceController::class, 'index'])->name('purchases.invoices.index');
    Route::get('/purchases/invoices/create', [App\Http\Controllers\PurchaseInvoiceController::class, 'create'])->name('purchases.invoices.create');

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
    Route::get('/crm/suppliers', [App\Http\Controllers\SupplierController::class, 'index'])->name('crm.suppliers.index');
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

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
