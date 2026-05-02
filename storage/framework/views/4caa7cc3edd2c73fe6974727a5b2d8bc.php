


<?php $__env->startSection('content'); ?>
<div class="p-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-semibold">Purchase Invoices</h1>
            <p class="text-sm text-gray-500">Draft and submitted supplier invoices.</p>
        </div>
        <a href="<?php echo e(route('modules.purchases.invoices.create')); ?>" class="px-4 py-2 border rounded">New Purchase Invoice</a>
    </div>

    <?php if(session('success')): ?>
        <div class="mb-4 p-3 border rounded bg-green-50 text-green-800"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <div class="border rounded overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left p-3">Invoice No</th>
                    <th class="text-left p-3">Supplier</th>
                    <th class="text-left p-3">Posting Date</th>
                    <th class="text-left p-3">Due Date</th>
                    <th class="text-left p-3">Status</th>
                    <th class="text-right p-3">Total</th>
                    <th class="text-right p-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="border-t">
                        <td class="p-3">
                            <a class="underline" href="<?php echo e(route('modules.purchases.invoices.show', $inv)); ?>"><?php echo e($inv->invoice_no); ?></a>
                        </td>
                        <td class="p-3"><?php echo e($inv->supplier->name ?? '-'); ?></td>
                        <td class="p-3"><?php echo e($inv->posting_date?->format('Y-m-d')); ?></td>
                        <td class="p-3"><?php echo e($inv->due_date?->format('Y-m-d') ?? '-'); ?></td>
                        <td class="p-3"><?php echo e($inv->status); ?></td>
                        <td class="p-3 text-right"><?php echo e(number_format((float)$inv->total, 2)); ?> <?php echo e($inv->currency); ?></td>
                        <td class="p-3 text-right">
                            <a href="<?php echo e(route('modules.purchases.invoices.show', $inv)); ?>" class="px-3 py-1 border rounded">View</a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td class="p-6 text-center text-gray-500" colspan="7">No invoices found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4"><?php echo e($invoices->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Desktop\Victor\tax_compliance\resources\views/modules/purchases/invoices/index.blade.php ENDPATH**/ ?>