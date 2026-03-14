
<?php $__env->startSection('page_title','Receipt'); ?>

<?php $__env->startSection('content'); ?>
<div class="h-full overflow-auto space-y-4">

    <?php if(session('ok')): ?>
        <div class="p-3 rounded-lg bg-emerald-500/10 ring-1 ring-emerald-500/20 text-emerald-200 text-sm">
            <?php echo e(session('ok')); ?>

        </div>
    <?php endif; ?>

    <div class="rounded-xl bg-white/5 ring-1 ring-white/10 p-4">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-lg font-semibold"><?php echo e($payment->payment_no); ?></div>
                <div class="text-sm text-slate-300">Status: <?php echo e($payment->status); ?></div>
            </div>
            <a href="<?php echo e(route('modules.sales.receipts.index')); ?>"
               class="px-3 py-2 rounded-lg bg-white/10 hover:bg-white/15 ring-1 ring-white/10 text-sm">
                Back
            </a>
        </div>

        <div class="mt-3 grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
            <div>Posting Date: <span class="text-slate-200"><?php echo e($payment->posting_date); ?></span></div>
            <div>Currency: <span class="text-slate-200"><?php echo e($payment->currency); ?></span></div>
            <div>Amount: <span class="text-slate-200"><?php echo e(number_format((float)$payment->amount,2)); ?></span></div>
            <div>Customer ID: <span class="text-slate-200"><?php echo e($payment->party_id); ?></span></div>
            <div>Bank Account ID: <span class="text-slate-200"><?php echo e($payment->bank_account_id); ?></span></div>
            <div>Reference: <span class="text-slate-200"><?php echo e($payment->reference ?? 'N/A'); ?></span></div>
        </div>
    </div>

    <div class="rounded-xl bg-white/5 ring-1 ring-white/10 p-4 space-y-3">
        <div class="text-sm font-semibold">Allocations</div>

        <div class="rounded-xl ring-1 ring-white/10 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-white/5">
                    <tr>
                        <th class="p-3 text-left">Type</th>
                        <th class="p-3 text-left">Invoice</th>
                        <th class="p-3 text-left">Allocated Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                    <?php $__empty_1 = true; $__currentLoopData = $payment->allocations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-white/5">
                            <td class="p-3"><?php echo e($a->reference_type); ?></td>
                            <td class="p-3">
                                <?php if($a->reference_type === 'SALES_INVOICE' && $a->reference_id): ?>
                                    <a class="text-indigo-200 hover:underline"
                                       href="<?php echo e(route('modules.sales.invoices.show', $a->reference_id)); ?>">
                                        Invoice #<?php echo e($a->reference_id); ?>

                                    </a>
                                <?php else: ?>
                                    <span class="text-slate-400"><?php echo e($a->reference_id ?? 'N/A'); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="p-3">$<?php echo e(number_format((float)$a->allocated_amount,2)); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td class="p-4 text-slate-300 text-center" colspan="3">No allocations recorded.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="text-xs text-slate-400">
            Receipt posts: <span class="text-slate-200">DR Bank/Cash</span> and <span class="text-slate-200">CR Accounts Receivable</span>.
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Desktop\Victor\tax_compliance\resources\views/modules/sales/receipts/show.blade.php ENDPATH**/ ?>