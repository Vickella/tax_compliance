
<?php $__env->startSection('page_title','New Sales Invoice'); ?>

<?php $__env->startSection('content'); ?>
<div class="h-full overflow-auto">
    <form method="POST" action="<?php echo e(route('modules.sales.invoices.store')); ?>" class="space-y-4">
        <?php echo csrf_field(); ?>

        <div class="rounded-xl bg-white/5 ring-1 ring-white/10 p-4">
            <?php echo $__env->make('modules.sales.invoices._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>

        <button class="px-4 py-2 rounded-lg bg-white/10 hover:bg-white/15 ring-1 ring-white/10 text-sm">Save Draft</button>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Desktop\Victor\tax_compliance\resources\views/modules/sales/invoices/create.blade.php ENDPATH**/ ?>