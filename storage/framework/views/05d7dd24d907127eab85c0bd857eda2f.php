

<?php $__env->startSection('page_title', 'Sales'); ?>

<?php $__env->startSection('content'); ?>
<div class="h-full overflow-auto">

    <div class="flex items-center justify-between mb-5">
        <div>
            <div class="text-lg font-semibold">Sales</div>
            <div class="text-xs text-slate-300">Invoices, customers, receipts, and receivables</div>
        </div>

        <a href="<?php echo e(route('modules.sales.invoices.create')); ?>"
           class="rounded-lg px-3 py-2 text-xs bg-white/10 hover:bg-white/15 ring-1 ring-white/10">
            + New Sales Invoice
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">

        
        <div class="rounded-2xl bg-white/5 ring-1 ring-white/10 p-4">
            <div class="text-sm font-semibold mb-3">Transactions</div>

            <div class="space-y-2">
               <a href="<?php echo e(route('modules.sales.receipts.index')); ?>"
                   class="flex items-center justify-between rounded-lg px-3 py-2 text-sm hover:bg-white/5 ring-1 ring-white/10">
                    <span>Receipts</span>
                    <span class="text-slate-300">→</span>
                </a>
            </div>
        </div>

        
        <div class="rounded-2xl bg-white/5 ring-1 ring-white/10 p-4">
            <div class="text-sm font-semibold mb-3">Masters</div>

            <div class="space-y-2">
                <a href="<?php echo e(route('modules.sales.customers.index')); ?>"
                   class="flex items-center justify-between rounded-lg px-3 py-2 text-sm hover:bg-white/5 ring-1 ring-white/10">
                    <span>Customers</span>
                    <span class="text-slate-300">→</span>
                </a>
            </div>
        </div>

        
        <div class="rounded-2xl bg-white/5 ring-1 ring-white/10 p-4">
            <div class="text-sm font-semibold mb-3">Reports</div>

            <div class="space-y-2">
                <a href="<?php echo e(route('modules.sales.ar.aging')); ?>"
                   class="flex items-center justify-between rounded-lg px-3 py-2 text-sm hover:bg-white/5 ring-1 ring-white/10">
                    <span>Accounts Receivable Aging</span>
                    <span class="text-slate-300">→</span>
                </a>
            </div>
        </div>

    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Desktop\Victor\tax_compliance\resources\views/modules/sales/invoices/index.blade.php ENDPATH**/ ?>