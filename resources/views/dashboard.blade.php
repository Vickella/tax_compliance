<x-app-layout>
    
    @php
    $href = function (string $routeName, array $params = []) {
        return \Illuminate\Support\Facades\Route::has($routeName)
            ? route($routeName, $params)
            : '#';
    };

    $modules = [
        [
            'no' => 1,
            'title' => 'Sales Invoices',
            'items' => [
                ['label' => 'Sales Invoices', 'route' => $href('sales.invoices.index')],
            ],
        ],
        [
            'no' => 2,
            'title' => 'Purchase Invoices',
            'items' => [
                ['label' => 'Purchase Invoices', 'route' => $href('purchases.invoices.index')],
            ],
        ],
        
          [
            'no' => 3,
            'title' => 'Stock Control',
            'items' => [
                ['label' => 'Items', 'route' => $href('inventory.items.index')],
                ['label' => 'Warehouses', 'route' => $href('inventory.warehouses.index')],
                ['label' => 'Stock Entry', 'route' => $href('inventory.entry.index')],
                ['label' => 'Stock Ledger', 'route' => $href('inventory.ledger')],
            ],
        ],
        [
            'no' => 4,
            'title' => 'Cash Book / Banking',
            'items' => [
                ['label' => 'Receipts & Payments', 'route' => $href('banking.receipts.index')],
                ['label' => 'Bank Reconciliation', 'route' => $href('banking.reconciliation')],
            ],
        ],
        [
            'no' => 5,
            'title' => 'Chart of Accounts & GL',
            'items' => [
                ['label' => 'Chart of Accounts', 'route' => $href('accounting.coa.index')],
                ['label' => 'Journal Entries', 'route' => $href('accounting.journals.index')],
                ['label' => 'Trial Balance', 'route' => $href('reports.trial-balance')],
            ],
        ],
        [
            'no' => 6,
            'title' => 'Reports',
            'items' => [
                ['label' => 'Profit & Loss', 'route' => $href('reports.pnl')],
                ['label' => 'Balance Sheet', 'route' => $href('reports.balance-sheet')],
                ['label' => 'VAT Return', 'route' => $href('tax.vat-return')],
                ['label' => 'QPD Schedules', 'route' => $href('tax.qpd')],
                ['label' => 'Income Tax Computation', 'route' => $href('tax.income-tax')],
            ],
        ],
        [
            'no' => 7,
            'title' => 'Customers',
            'items' => [
                ['label' => 'Customers', 'route' => $href('crm.customers.index')],
                ['label' => 'Customer Statements', 'route' => $href('crm.customer-statements')],
            ],
        ],
        [
            'no' => 8,
            'title' => 'Suppliers',
            'items' => [
                ['label' => 'Suppliers', 'route' => $href('crm.suppliers.index')],
                ['label' => 'Supplier Ledger', 'route' => $href('crm.supplier-ledger')],
            ],
        ],

         [
            'no' => 9,
            'title' => 'Employees & Payroll',
            'items' => [
                ['label' => 'Employees', 'route' => $href('payroll.employees.index')], 
                ['label' => 'Run Payroll', 'route' => $href('payroll.runs.create')],
            ],
        ],

        [
            'no' => 10,
            'title' => 'Tax Compliance Analysis',
            'items' => [
                ['label' => 'VAT Validation', 'route' => $href('compliance.vat')],
                ['label' => 'QPD Checks', 'route' => $href('compliance.qpd')],
                ['label' => 'Payroll Statutory Checks', 'route' => $href('compliance.payroll')],
                ['label' => 'OCR / QR Import', 'route' => $href('compliance.ocr')],
            ],
        ],

         [
            'no' => 11,
            'title' => 'Users, Roles & Permissions',
            'items' => [
                ['label' => 'Users', 'route' => $href('security.users.index')],
                ['label' => 'Roles', 'route' => $href('security.roles.index')],
                ['label' => 'Permissions', 'route' => $href('security.permissions.index')],
            ],
        ],

        [
            'no' => 12,
            'title' => 'System Settings',
            'items' => [
                ['label' => 'Company Settings', 'route' => $href('settings.company')],
                ['label' => 'Tax Settings', 'route' => $href('settings.tax')],
                ['label' => 'Currencies & Rates', 'route' => $href('settings.currencies')],
                ['label' => 'Accounting Periods', 'route' => $href('settings.periods')],
            ],
        ],
       
     ];

@endphp


    <div class="min-h-screen bg-gradient-to-br from-indigo-950 via-violet-900 to-slate-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                {{-- LEFT: MODULE NAVIGATION --}}
                <aside class="lg:col-span-4 xl:col-span-3">
                    <div class="sticky top-6">
                        <div class="rounded-2xl border border-white/10 bg-white/5 backdrop-blur-xl shadow-xl overflow-hidden">
                            <div class="p-4 border-b border-white/10">
                                <p class="text-sm font-semibold text-white">My Dashboard</p>
                            </div>

                            <div class="max-h-[80vh] overflow-auto p-2">
                                @foreach($modules as $m)
                                    <details class="group rounded-xl hover:bg-white/5 transition">
                                        <summary class="list-none cursor-pointer select-none p-3 flex items-start gap-3">
    <div class="shrink-0 h-9 w-9 rounded-xl bg-white/10 border border-white/10 flex items-center justify-center text-white font-semibold">
        {{ $m['no'] }}
    </div>

    <div class="flex-1 min-w-0">
        <div class="flex items-center justify-between gap-1">
            <p class="text-sm font-semibold text-white truncate">
                {{ $m['title'] }}
            </p>
            <span class="text-white/60 group-open:rotate-90 transition-transform">›</span>
        </div>
    </div>
</summary>

                                        <div class="px-3 pb-3 -mt-1">
                                            <div class="pl-12 space-y-1">
                                                @foreach($m['items'] as $it)
                                                    <a
                                                        href="{{ $it['route'] }}"
                                                        class="block rounded-lg px-3 py-2 text-sm text-white/80 hover:text-white hover:bg-white/10 border border-transparent hover:border-white/10 transition"
                                                    >
                                                        {{ $it['label'] }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    </details>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </aside>

                {{-- RIGHT: OPERATIONAL OVERVIEW --}}
                <main class="lg:col-span-8 xl:col-span-9">
                    <div class="rounded-2xl border border-white/10 bg-white/5 backdrop-blur-xl shadow-xl overflow-hidden">
                        <div class="p-6 border-b border-white/10">
                            <h3 class="text-lg font-semibold text-white">Operational Overview</h3>
                            <p class="text-sm text-white/70 mt-1">
                                This will show compliance, period controls and exceptions once transactions are flowing.
                            </p>
                        </div>

                        <div class="p-6 space-y-6">
                            {{-- KPI Row (logical placeholders) --}}
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="rounded-2xl bg-black/10 border border-white/10 p-5">
                                    <p class="text-xs text-white/70">Current tax period</p>
                                    <p class="text-lg font-semibold text-white mt-1">Not configured</p>
                                    <p class="text-xs text-white/60 mt-1">Set in Settings → Accounting Periods</p>
                                </div>
                                <div class="rounded-2xl bg-black/10 border border-white/10 p-5">
                                    <p class="text-xs text-white/70">Compliance exceptions</p>
                                    <p class="text-lg font-semibold text-white mt-1">0</p>
                                    <p class="text-xs text-white/60 mt-1">Missing TIN, VAT refs, fiscal refs, backdating</p>
                                </div>
                                <div class="rounded-2xl bg-black/10 border border-white/10 p-5">
                                    <p class="text-xs text-white/70">Unposted documents</p>
                                    <p class="text-lg font-semibold text-white mt-1">0</p>
                                    <p class="text-xs text-white/60 mt-1">Draft or pending approvals</p>
                                </div>
                            </div>

                            {{-- High-value actions --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="rounded-2xl bg-black/10 border border-white/10 p-5">
                                    <p class="text-sm font-semibold text-white">Quick actions</p>
                                    <div class="mt-3 space-y-2">
                                        <a href="{{ $href('sales.invoices.create') }}" class="block rounded-xl px-4 py-3 bg-white/10 hover:bg-white/15 border border-white/10 text-white text-sm transition">
                                            New Sales Invoice
                                        </a>
                                        <a href="{{ $href('purchases.invoices.create') }}" class="block rounded-xl px-4 py-3 bg-white/10 hover:bg-white/15 border border-white/10 text-white text-sm transition">
                                            New Purchase Invoice
                                        </a>
                                        <a href="{{ $href('tax.vat-return') }}" class="block rounded-xl px-4 py-3 bg-white/10 hover:bg-white/15 border border-white/10 text-white text-sm transition">
                                            Prepare VAT Return
                                        </a>
                                    </div>
                                </div>

                                <div class="rounded-2xl bg-black/10 border border-white/10 p-5">
                                    <p class="text-sm font-semibold text-white">What to configure first</p>
                                    <ul class="mt-3 space-y-2 text-sm text-white/75">
                                        <li class="rounded-xl bg-white/5 border border-white/10 px-4 py-3">Company profile, currency and tax year</li>
                                        <li class="rounded-xl bg-white/5 border border-white/10 px-4 py-3">Chart of accounts and opening balances</li>
                                        <li class="rounded-xl bg-white/5 border border-white/10 px-4 py-3">VAT settings and invoice numbering rules</li>
                                        <li class="rounded-xl bg-white/5 border border-white/10 px-4 py-3">Payroll statutory settings (PAYE, NSSA, ZIMDEF, AIDS levy)</li>
                                    </ul>
                                </div>
                            </div>

                            {{-- Activity placeholder --}}
                            <div class="rounded-2xl bg-black/10 border border-white/10 p-5">
                                <div class="flex items-center justify-between gap-3">
                                    <p class="text-sm font-semibold text-white">Recent activity</p>
                                    <span class="text-xs text-white/60">Audit trail will populate this</span>
                                </div>
                                <div class="mt-3 rounded-xl bg-white/5 border border-white/10 p-4 text-sm text-white/70">
                                    No activity yet. When users create and post documents, you will see events here with who did what and when.
                                </div>
                            </div>

                        </div>
                    </div>
                </main>

            </div>
        </div>
    </div>
</x-app-layout>
