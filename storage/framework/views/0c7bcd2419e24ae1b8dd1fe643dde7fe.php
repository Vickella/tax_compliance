

<?php $__env->startSection('page_title', 'QPD Payment'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-semibold text-white">QPD Payment <?php echo e($payment->payment_no); ?></h2>
            <p class="text-sm text-slate-400">Q<?php echo e($payment->quarter); ?> <?php echo e($payment->tax_year); ?> • <?php echo e($payment->payment_date->format('d M Y')); ?></p>
        </div>
        <div class="flex gap-3">
            <?php if($payment->status === 'DRAFT'): ?>
            <form method="POST" action="<?php echo e(route('modules.tax.qpd.submit', $payment)); ?>" class="inline">
                <?php echo csrf_field(); ?>
                <button type="submit" 
                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors">
                    Submit Payment
                </button>
            </form>
            <?php endif; ?>
            <a href="<?php echo e(route('modules.tax.qpd.pdf', $payment)); ?>" 
               class="px-4 py-2 bg-white/5 hover:bg-white/10 ring-1 ring-white/10 rounded-lg transition-colors">
                Download ITF12B
            </a>
        </div>
    </div>

    
    <div class="mb-6 p-4 rounded-lg ring-1 
        <?php if($payment->status === 'PAID'): ?> bg-emerald-600/20 ring-emerald-600/30
        <?php elseif($payment->status === 'SUBMITTED'): ?> bg-blue-600/20 ring-blue-600/30
        <?php else: ?> bg-yellow-600/20 ring-yellow-600/30
        <?php endif; ?>">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="text-sm font-medium">
                    Status: <span class="font-bold"><?php echo e($payment->status); ?></span>
                </span>
            </div>
            <?php if($payment->status === 'PAID' && $payment->journalEntry): ?>
            <span class="text-xs">
                Journal: <a href="<?php echo e(route('modules.accounting.journals.show', $payment->journalEntry)); ?>" class="text-indigo-400 hover:underline">
                    <?php echo e($payment->journalEntry->entry_no); ?>

                </a>
            </span>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="bg-black/20 rounded-xl ring-1 ring-white/10 overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div class="space-y-4">
                    <div>
                        <div class="text-xs text-slate-400">Payment Number</div>
                        <div class="text-lg font-semibold text-white font-mono"><?php echo e($payment->payment_no); ?></div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-400">Tax Year</div>
                        <div class="text-white"><?php echo e($payment->tax_year); ?></div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-400">Quarter</div>
                        <div class="text-white">Q<?php echo e($payment->quarter); ?></div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-400">Payment Date</div>
                        <div class="text-white"><?php echo e($payment->payment_date->format('d/m/Y')); ?></div>
                    </div>
                </div>

                
                <div class="space-y-4">
                    <div>
                        <div class="text-xs text-slate-400">Due Date</div>
                        <div class="text-white"><?php echo e($payment->due_date->format('d/m/Y')); ?></div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-400">Payment Method</div>
                        <div class="text-white"><?php echo e($payment->payment_method); ?></div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-400">Reference</div>
                        <div class="text-white"><?php echo e($payment->reference ?? 'N/A'); ?></div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-400">Created By</div>
                        <div class="text-white"><?php echo e($payment->createdBy?->name ?? 'System'); ?></div>
                    </div>
                </div>
            </div>

            <div class="border-t border-white/10 my-6"></div>

            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-black/40 rounded-lg p-4">
                    <div class="text-xs text-slate-400">Estimated Annual Tax</div>
                    <div class="text-lg font-semibold text-white">$<?php echo e(number_format($payment->estimated_annual_tax, 2)); ?></div>
                </div>
                <div class="bg-black/40 rounded-lg p-4">
                    <div class="text-xs text-slate-400">Percentage Applied</div>
                    <div class="text-lg font-semibold text-white"><?php echo e($payment->percentage_applied); ?>%</div>
                </div>
                <div class="bg-indigo-600/20 rounded-lg p-4">
                    <div class="text-xs text-indigo-300">Payment Amount</div>
                    <div class="text-xl font-bold text-indigo-400">$<?php echo e(number_format($payment->amount, 2)); ?></div>
                </div>
            </div>

            <?php if($payment->metadata['notes'] ?? false): ?>
            <div class="mt-6 p-4 bg-black/40 rounded-lg">
                <div class="text-xs text-slate-400 mb-1">Notes</div>
                <p class="text-white"><?php echo e($payment->metadata['notes']); ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Desktop\Victor\tax_compliance\resources\views/modules/tax/qpd/show.blade.php ENDPATH**/ ?>