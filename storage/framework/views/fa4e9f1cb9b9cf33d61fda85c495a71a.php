<?php $__env->startSection('page_title', 'QPD Payments & Forecast'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-semibold text-white">Provisional Tax (QPD)</h2>
            <p class="text-sm text-slate-400">ITF12B - Quarterly Payments & Forecast</p>
        </div>
        <div class="flex gap-3">
            <form method="GET" class="flex gap-2">
                <select name="tax_year" onchange="this.form.submit()" 
                        class="px-3 py-2 rounded-lg bg-black/30 text-white border border-white/10 focus:border-indigo-500 outline-none">
                    <?php for($y = now()->year; $y >= now()->year - 2; $y--): ?>
                        <option value="<?php echo e($y); ?>" <?php echo e(($taxYear ?? now()->year) == $y ? 'selected' : ''); ?>>
                            Year <?php echo e($y); ?>

                        </option>
                    <?php endfor; ?>
                </select>
            </form>
            <a href="<?php echo e(route('modules.tax.qpd.forecast.dashboard', ['year' => $taxYear])); ?>" 
               class="px-4 py-2 bg-indigo-600/20 hover:bg-indigo-600/30 text-indigo-300 ring-1 ring-indigo-500/30 rounded-lg">
                📊 Full Forecast
            </a>
            <a href="<?php echo e(route('modules.tax.qpd.create', ['tax_year' => $taxYear])); ?>" 
               class="px-4 py-2 bg-white/10 hover:bg-white/15 ring-1 ring-white/10 rounded-lg">
                + New Payment
            </a>
        </div>
    </div>

    
    <?php if(isset($qpdEstimates) && count($qpdEstimates) > 0): ?>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <?php $__currentLoopData = $qpdEstimates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q => $details): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-4">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <div class="text-xs text-slate-400">Quarter <?php echo e($q); ?></div>
                    <div class="text-sm font-semibold text-white">Due: <?php echo e(\Carbon\Carbon::parse($details['due_date'])->format('d M')); ?></div>
                </div>
                <span class="px-2 py-1 rounded-full text-xs 
                    <?php if(isset($details['is_overdue']) && $details['is_overdue'] && ($details['paid_amount'] ?? 0) < $details['amount']): ?> bg-rose-600/20 text-rose-300
                    <?php elseif(($details['paid_amount'] ?? 0) >= $details['amount']): ?> bg-emerald-600/20 text-emerald-300
                    <?php else: ?> bg-amber-600/20 text-amber-300
                    <?php endif; ?>">
                    <?php echo e(($details['paid_amount'] ?? 0) >= $details['amount'] ? 'PAID' : (isset($details['is_overdue']) && $details['is_overdue'] ? 'OVERDUE' : 'PENDING')); ?>

                </span>
            </div>
            <div class="mt-2 space-y-1">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-400">Estimate:</span>
                    <span class="text-white font-semibold">$<?php echo e(number_format($details['amount'], 2)); ?></span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-400">Paid:</span>
                    <span class="text-emerald-400">$<?php echo e(number_format($details['paid_amount'] ?? 0, 2)); ?></span>
                </div>
                <?php if(($details['balance_due'] ?? 0) > 0): ?>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-400">Balance:</span>
                    <span class="text-amber-400">$<?php echo e(number_format($details['balance_due'], 2)); ?></span>
                </div>
                <?php endif; ?>
            </div>
            <?php if(($details['balance_due'] ?? 0) > 0): ?>
            <a href="<?php echo e(route('modules.tax.qpd.create', ['tax_year' => $taxYear, 'quarter' => $q])); ?>" 
               class="mt-3 block w-full text-center px-3 py-1.5 bg-indigo-600/20 hover:bg-indigo-600/30 text-indigo-300 text-sm rounded-lg transition-colors">
                Make Payment
            </a>
            <?php endif; ?>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <div class="bg-indigo-600/10 rounded-xl ring-1 ring-indigo-500/30 p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <div class="text-xs text-slate-400">Estimated Annual Tax</div>
                <div class="text-xl font-bold text-indigo-400">$<?php echo e(number_format($estimatedAnnualTax ?? 0, 2)); ?></div>
            </div>
            <div>
                <div class="text-xs text-slate-400">Total QPD Due</div>
                <div class="text-xl font-bold text-white">$<?php echo e(number_format($totalQpd ?? 0, 2)); ?></div>
            </div>
            <div>
                <div class="text-xs text-slate-400">Based on</div>
                <div class="text-sm text-white"><?php echo e(isset($calculatedAt) ? \Carbon\Carbon::parse($calculatedAt)->format('d M Y') : 'Current GL'); ?></div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-8 mb-6 text-center">
        <p class="text-slate-400">No forecast data available for <?php echo e($taxYear); ?></p>
    </div>
    <?php endif; ?>

    
    <div class="mt-8">
        <h3 class="text-lg font-semibold text-white mb-4">Payment History</h3>
        <div class="bg-black/20 rounded-xl ring-1 ring-white/10 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-white/5">
                    <tr>
                        <th class="px-4 py-3 text-left text-slate-300">Payment No</th>
                        <th class="px-4 py-3 text-left text-slate-300">Quarter</th>
                        <th class="px-4 py-3 text-left text-slate-300">Payment Date</th>
                        <th class="px-4 py-3 text-right text-slate-300">Amount</th>
                        <th class="px-4 py-3 text-left text-slate-300">Method</th>
                        <th class="px-4 py-3 text-center text-slate-300">Status</th>
                        <th class="px-4 py-3 text-right text-slate-300">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                    <?php $__empty_1 = true; $__currentLoopData = $payments ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-white/5">
                        <td class="px-4 py-3 font-mono"><?php echo e($payment->payment_no ?? 'N/A'); ?></td>
                        <td class="px-4 py-3">Q<?php echo e($payment->quarter); ?></td>
                        <td class="px-4 py-3"><?php echo e($payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') : 'N/A'); ?></td>
                        <td class="px-4 py-3 text-right font-semibold">$<?php echo e(number_format($payment->amount ?? 0, 2)); ?></td>
                        <td class="px-4 py-3"><?php echo e($payment->payment_method ?? 'N/A'); ?></td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-2 py-1 rounded-full text-xs 
                                <?php if($payment->status === 'PAID'): ?> bg-emerald-600/20 text-emerald-300
                                <?php elseif($payment->status === 'SUBMITTED'): ?> bg-blue-600/20 text-blue-300
                                <?php else: ?> bg-yellow-600/20 text-yellow-300
                                <?php endif; ?>">
                                <?php echo e($payment->status ?? 'DRAFT'); ?>

                            </span>
                        </td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <a href="<?php echo e(route('modules.tax.qpd.show', $payment)); ?>" class="text-indigo-400 hover:text-indigo-300">View</a>
                            <a href="<?php echo e(route('modules.tax.qpd.pdf', $payment)); ?>" class="text-slate-400 hover:text-slate-300">PDF</a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-slate-400">
                            No QPD payments found for <?php echo e($taxYear ?? now()->year); ?>

                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Desktop\Instacare\tax_compliance\resources\views/modules/tax/qpd/index.blade.php ENDPATH**/ ?>