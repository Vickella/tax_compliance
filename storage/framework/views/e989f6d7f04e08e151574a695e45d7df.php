

<?php $__env->startSection('page_title', 'Make QPD Payment'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-white">QPD Payment - Q<?php echo e($quarter); ?> <?php echo e($taxYear); ?></h2>
        <p class="text-sm text-slate-400">ITF12B - Provisional Tax Payment</p>
    </div>

    <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-6">
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-black/40 rounded-lg p-4">
                <div class="text-xs text-slate-400">Estimated Annual Tax</div>
                <div class="text-xl font-bold text-white">$<?php echo e(number_format($calculation['estimated_annual_tax'], 2)); ?></div>
            </div>
            <div class="bg-black/40 rounded-lg p-4">
                <div class="text-xs text-slate-400">Quarter <?php echo e($quarter); ?> Percentage</div>
                <div class="text-xl font-bold text-white"><?php echo e($calculation['qpd_percentage']); ?>%</div>
            </div>
            <div class="bg-black/40 rounded-lg p-4">
                <div class="text-xs text-slate-400">Due Date</div>
                <div class="text-xl font-bold text-amber-400"><?php echo e(\Carbon\Carbon::parse($calculation['due_date'])->format('d M Y')); ?></div>
            </div>
        </div>

        <div class="bg-indigo-600/20 rounded-lg p-4 mb-6 border border-indigo-500/30">
            <div class="flex justify-between items-center">
                <div>
                    <div class="text-xs text-indigo-300">Recommended Payment</div>
                    <div class="text-2xl font-bold text-indigo-400">$<?php echo e(number_format($calculation['qpd_amount'], 2)); ?></div>
                </div>
                <?php if($calculation['paid_amount'] > 0): ?>
                <div class="text-right">
                    <div class="text-xs text-slate-400">Already Paid</div>
                    <div class="text-lg font-semibold text-emerald-400">$<?php echo e(number_format($calculation['paid_amount'], 2)); ?></div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <form method="POST" action="<?php echo e(route('modules.tax.qpd.store')); ?>">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="tax_year" value="<?php echo e($taxYear); ?>">
            <input type="hidden" name="quarter" value="<?php echo e($quarter); ?>">
            <input type="hidden" name="due_date" value="<?php echo e($calculation['due_date']); ?>">
            <input type="hidden" name="estimated_annual_tax" value="<?php echo e($calculation['estimated_annual_tax']); ?>">
            <input type="hidden" name="percentage_applied" value="<?php echo e($calculation['qpd_percentage']); ?>">

            <div class="space-y-4">
                
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Payment Amount</label>
                    <input type="number" name="amount" step="0.01" min="0.01" 
                           value="<?php echo e(old('amount', $calculation['balance_due'] ?? $calculation['qpd_amount'])); ?>"
                           class="w-full px-3 py-2 rounded-lg bg-black/30 text-white border border-white/10 focus:border-indigo-500 outline-none text-lg"
                           required>
                </div>

                
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Payment Method</label>
                    <select name="payment_method" required
                            class="w-full px-3 py-2 rounded-lg bg-black/30 text-white border border-white/10 focus:border-indigo-500 outline-none">
                        <option value="BANK">Bank Transfer</option>
                        <option value="CASH">Cash Deposit</option>
                        <option value="ECOCASH">EcoCash</option>
                        <option value="RTGS">RTGS</option>
                        <option value="SWIFT">SWIFT</option>
                    </select>
                </div>

                
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Reference / Transaction ID</label>
                    <input type="text" name="reference" value="<?php echo e(old('reference')); ?>"
                           class="w-full px-3 py-2 rounded-lg bg-black/30 text-white border border-white/10 focus:border-indigo-500 outline-none"
                           placeholder="e.g., Bank reference, receipt number">
                </div>

                
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Notes</label>
                    <textarea name="notes" rows="2" 
                              class="w-full px-3 py-2 rounded-lg bg-black/30 text-white border border-white/10 focus:border-indigo-500 outline-none"
                              placeholder="Any additional notes..."><?php echo e(old('notes')); ?></textarea>
                </div>

                
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-white/10">
                    <a href="<?php echo e(route('modules.tax.qpd.index', ['tax_year' => $taxYear])); ?>" 
                       class="px-4 py-2 rounded-lg bg-white/5 hover:bg-white/10 ring-1 ring-white/10 text-sm transition-colors">
                        Cancel
                    </a>
                    <button type="submit" name="action" value="save" 
                            class="px-4 py-2 rounded-lg bg-white/10 hover:bg-white/15 ring-1 ring-white/10 text-sm transition-colors">
                        Save Draft
                    </button>
                    <button type="submit" name="action" value="submit" 
                            class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium transition-colors">
                        Submit Payment
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Desktop\Victor\tax_compliance\resources\views/modules/tax/qpd/create.blade.php ENDPATH**/ ?>