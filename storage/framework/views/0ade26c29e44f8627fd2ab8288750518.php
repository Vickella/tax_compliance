


<?php $__env->startSection('content'); ?>
<div class="p-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-semibold">New Purchase Invoice</h1>
            <p class="text-sm text-gray-500">Create a supplier invoice (Draft).</p>
        </div>
        <a href="<?php echo e(route('modules.purchases.invoices.index')); ?>" class="px-4 py-2 border rounded">Back</a>
    </div>

    <form method="POST" action="<?php echo e(route('modules.purchases.invoices.store')); ?>" class="border rounded p-4">
        <?php echo csrf_field(); ?>
        <?php echo $__env->make('modules.purchases.invoices._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <div class="mt-6 flex gap-2">
            <button class="px-4 py-2 border rounded bg-black text-white" type="submit">Save Draft</button>
            <a class="px-4 py-2 border rounded" href="<?php echo e(route('modules.purchases.invoices.index')); ?>">Cancel</a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Desktop\Victor\tax_compliance\resources\views/modules/purchases/invoices/create.blade.php ENDPATH**/ ?>