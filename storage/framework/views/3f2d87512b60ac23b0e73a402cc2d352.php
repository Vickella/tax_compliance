<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    
    <div>
        <h1 class="text-3xl font-bold text-white">Automated Tax Filing System</h1>
        <p class="text-sm text-slate-400 mt-1">Welcome, <?php echo e(auth()->user()->name); ?>! Manage your tax filings and transactions.</p>
    </div>

    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card border-l-4 border-green-500">
            <div class="text-sm text-slate-400">PAYE Status</div>
            <div class="text-2xl font-semibold mt-1 text-green-400">Current</div>
            <div class="text-xs text-slate-400 mt-2">All payments up to date</div>
        </div>
        <div class="card border-l-4 border-blue-500">
            <div class="text-sm text-slate-400">VAT Status</div>
            <div class="text-2xl font-semibold mt-1 text-blue-400">Due</div>
            <div class="text-xs text-slate-400 mt-2">Next filing: 25 May 2026</div>
        </div>
        <div class="card border-l-4 border-orange-500">
            <div class="text-sm text-slate-400">Income Tax Status</div>
            <div class="text-2xl font-semibold mt-1 text-orange-400">Review</div>
            <div class="text-xs text-slate-400 mt-2">Annual reconciliation pending</div>
        </div>
        <div class="card border-l-4 border-purple-500">
            <div class="text-sm text-slate-400">QPD Status</div>
            <div class="text-2xl font-semibold mt-1 text-purple-400">Draft</div>
            <div class="text-xs text-slate-400 mt-2">Q2 2026 in preparation</div>
        </div>
    </div>

    
    <div class="space-y-6">
        
        <div>
            <h2 class="text-xl font-semibold text-white mb-4">💼 Tax Filing Modules</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                
                
                <a href="<?php echo e(route('modules.index', ['module' => 'payroll'])); ?>" 
                   class="card hover:border-green-500/50 hover:bg-green-500/5 transition-all group cursor-pointer">
                    <div class="text-4xl mb-3 group-hover:scale-110 transition-transform">💰</div>
                    <h3 class="text-lg font-semibold text-white mb-2">PAYE</h3>
                    <p class="text-sm text-slate-400">Manage payroll and employee tax withholdings</p>
                    <div class="mt-4 text-xs text-slate-500">→ View PAYE</div>
                </a>

                
                <a href="<?php echo e(route('modules.tax.vat.index')); ?>" 
                   class="card hover:border-blue-500/50 hover:bg-blue-500/5 transition-all group cursor-pointer">
                    <div class="text-4xl mb-3 group-hover:scale-110 transition-transform">📊</div>
                    <h3 class="text-lg font-semibold text-white mb-2">VAT</h3>
                    <p class="text-sm text-slate-400">Track and file VAT returns</p>
                    <div class="mt-4 text-xs text-slate-500">→ View VAT</div>
                </a>

                
                <a href="<?php echo e(route('modules.tax.income.index')); ?>" 
                   class="card hover:border-orange-500/50 hover:bg-orange-500/5 transition-all group cursor-pointer">
                    <div class="text-4xl mb-3 group-hover:scale-110 transition-transform">🏢</div>
                    <h3 class="text-lg font-semibold text-white mb-2">Income Tax</h3>
                    <p class="text-sm text-slate-400">File income tax returns and reconciliations</p>
                    <div class="mt-4 text-xs text-slate-500">→ View Income Tax</div>
                </a>

                
                <a href="<?php echo e(route('modules.tax.qpd.index')); ?>" 
                   class="card hover:border-purple-500/50 hover:bg-purple-500/5 transition-all group cursor-pointer">
                    <div class="text-4xl mb-3 group-hover:scale-110 transition-transform">📋</div>
                    <h3 class="text-lg font-semibold text-white mb-2">Quarterly Payments</h3>
                    <p class="text-sm text-slate-400">Manage quarterly payment deductions</p>
                    <div class="mt-4 text-xs text-slate-500">→ View QPD</div>
                </a>
            </div>
        </div>

        
        <div>
            <h2 class="text-lg font-semibold text-white mb-4">⚡ Quick Actions</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <a href="<?php echo e(route('modules.sales.invoices.create')); ?>" 
                   class="bg-white/5 hover:bg-white/10 border border-white/10 rounded-lg p-4 text-center transition-all hover:border-indigo-500/50">
                    <div class="text-2xl mb-2">🧾</div>
                    <div class="text-sm font-medium text-white">Sales Invoice</div>
                </a>
                
                <a href="<?php echo e(route('modules.purchases.invoices.create')); ?>" 
                   class="bg-white/5 hover:bg-white/10 border border-white/10 rounded-lg p-4 text-center transition-all hover:border-indigo-500/50">
                    <div class="text-2xl mb-2">📥</div>
                    <div class="text-sm font-medium text-white">Purchase Invoice</div>
                </a>
                
                <a href="<?php echo e(route('modules.accounting.journals.create')); ?>" 
                   class="bg-white/5 hover:bg-white/10 border border-white/10 rounded-lg p-4 text-center transition-all hover:border-indigo-500/50">
                    <div class="text-2xl mb-2">📚</div>
                    <div class="text-sm font-medium text-white">Journal Entry</div>
                </a>
                
                <a href="<?php echo e(route('modules.payroll.runs.create')); ?>" 
                   class="bg-white/5 hover:bg-white/10 border border-white/10 rounded-lg p-4 text-center transition-all hover:border-indigo-500/50">
                    <div class="text-2xl mb-2">👥</div>
                    <div class="text-sm font-medium text-white">Payroll Run</div>
                </a>
            </div>
        </div>

        
        <div>
            <h2 class="text-lg font-semibold text-white mb-4">📑 Transaction Modules</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                
                
                <a href="<?php echo e(route('modules.index', ['module' => 'sales'])); ?>" 
                   class="card hover:border-indigo-500/50 hover:bg-indigo-500/5 transition-all group cursor-pointer">
                    <div class="text-3xl mb-2">🧾</div>
                    <h3 class="font-semibold text-white mb-1">Sales</h3>
                    <p class="text-xs text-slate-400">Invoices, customers, receipts</p>
                </a>

                
                <a href="<?php echo e(route('modules.index', ['module' => 'purchases'])); ?>" 
                   class="card hover:border-indigo-500/50 hover:bg-indigo-500/5 transition-all group cursor-pointer">
                    <div class="text-3xl mb-2">📥</div>
                    <h3 class="font-semibold text-white mb-1">Purchases</h3>
                    <p class="text-xs text-slate-400">Invoices, suppliers, AP</p>
                </a>

                
                <a href="<?php echo e(route('modules.index', ['module' => 'accounting'])); ?>" 
                   class="card hover:border-indigo-500/50 hover:bg-indigo-500/5 transition-all group cursor-pointer">
                    <div class="text-3xl mb-2">📚</div>
                    <h3 class="font-semibold text-white mb-1">Accounting</h3>
                    <p class="text-xs text-slate-400">Journals, payments, reports</p>
                </a>

                
                <a href="<?php echo e(route('modules.index', ['module' => 'inventory'])); ?>" 
                   class="card hover:border-indigo-500/50 hover:bg-indigo-500/5 transition-all group cursor-pointer">
                    <div class="text-3xl mb-2">📦</div>
                    <h3 class="font-semibold text-white mb-1">Inventory</h3>
                    <p class="text-xs text-slate-400">Items, warehouses, stock</p>
                </a>
            </div>
        </div>
    </div>

    
    <div class="card bg-gradient-to-r from-green-500/10 to-blue-500/10 border border-green-500/20">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="font-semibold text-white">Automated Tax Filing System</h3>
                <p class="text-sm text-slate-400 mt-1">Streamlined tax compliance and financial reporting. All transactions are automatically processed for tax calculations.</p>
            </div>
            <div class="text-right">
                <div class="text-sm text-slate-400">Version 1.0.0</div>
                <div class="text-xs text-slate-500 mt-1">© 2026 ATFS</div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Desktop\Instacare\tax_compliance\resources\views/dashboard/home.blade.php ENDPATH**/ ?>