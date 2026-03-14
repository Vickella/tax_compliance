


<?php $__env->startSection('content'); ?>
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold">Purchases</h1>
            <p class="text-sm text-gray-500">Manage suppliers and purchase invoices.</p>
        </div>
        <div class="flex gap-2">
            <a href="<?php echo e(route('modules.purchases.suppliers.create')); ?>" class="px-4 py-2 border rounded">New Supplier</a>
            <a href="<?php echo e(route('modules.purchases.invoices.create')); ?>" class="px-4 py-2 border rounded">New Purchase Invoice</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="p-4 border rounded">
            <div class="text-sm text-gray-500">Active Suppliers</div>
            <div class="text-2xl font-bold"><?php echo e($stats['suppliers'] ?? 0); ?></div>
        </div>
        <div class="p-4 border rounded">
            <div class="text-sm text-gray-500">Purchase Invoices</div>
            <div class="text-2xl font-bold"><?php echo e($stats['purchase_invoices'] ?? 0); ?></div>
        </div>
        <div class="p-4 border rounded">
            <div class="text-sm text-gray-500">Draft Invoices</div>
            <div class="text-2xl font-bold"><?php echo e($stats['purchase_invoices_draft'] ?? 0); ?></div>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <a class="p-4 border rounded hover:bg-gray-50" href="<?php echo e(route('modules.purchases.suppliers.index')); ?>">
            <div class="text-lg font-medium">Suppliers</div>
            <div class="text-sm text-gray-500">Create and maintain suppliers.</div>
        </a>

        <a class="p-4 border rounded hover:bg-gray-50" href="<?php echo e(route('modules.purchases.invoices.index')); ?>">
            <div class="text-lg font-medium">Purchase Invoices</div>
            <div class="text-sm text-gray-500">Draft, submit and manage supplier invoices.</div>
        </a>

        <a class="p-4 border rounded hover:bg-gray-50" href="<?php echo e(route('modules.purchases.ap.aging')); ?>">
            <div class="text-lg font-medium">AP Aging</div>
            <div class="text-sm text-gray-500">Outstanding payables aging summary.</div>
        </a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Desktop\Victor\tax_compliance\resources\views/modules/purchases/index.blade.php ENDPATH**/ ?>