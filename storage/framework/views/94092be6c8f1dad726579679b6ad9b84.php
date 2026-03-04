

<?php $__env->startSection('page_title', 'Income Tax Returns'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-semibold text-white">Income Tax Returns</h2>
            <p class="text-sm text-slate-400">ITF12C - Annual Income Tax Returns</p>
        </div>
        <div class="flex gap-3">
            <form method="GET" class="flex gap-2">
                <select name="tax_year" onchange="this.form.submit()" 
                        class="px-3 py-2 rounded-lg bg-black/30 text-white border border-white/10 focus:border-indigo-500 outline-none">
                    <?php for($y = now()->year; $y >= now()->year - 5; $y--): ?>
                        <option value="<?php echo e($y); ?>" <?php echo e(request('tax_year', now()->year) == $y ? 'selected' : ''); ?>>
                            Year <?php echo e($y); ?>

                        </option>
                    <?php endfor; ?>
                </select>
            </form>
            <a href="<?php echo e(route('modules.tax.income.create')); ?>" 
               class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                New Return
            </a>
        </div>
    </div>

    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-4">
            <div class="text-xs text-slate-400">Total Returns</div>
            <div class="text-2xl font-bold text-white"><?php echo e($stats['total_returns'] ?? 0); ?></div>
        </div>
        <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-4">
            <div class="text-xs text-slate-400">Submitted</div>
            <div class="text-2xl font-bold text-emerald-400"><?php echo e($stats['submitted'] ?? 0); ?></div>
        </div>
        <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-4">
            <div class="text-xs text-slate-400">Draft</div>
            <div class="text-2xl font-bold text-amber-400"><?php echo e($stats['draft'] ?? 0); ?></div>
        </div>
        <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-4">
            <div class="text-xs text-slate-400">Total Tax</div>
            <div class="text-2xl font-bold text-white">$<?php echo e(number_format($stats['total_tax'] ?? 0, 2)); ?></div>
        </div>
    </div>

    
    <div class="bg-black/20 rounded-xl ring-1 ring-white/10 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-white/5">
                <tr>
                    <th class="px-4 py-3 text-left text-slate-300">Return No</th>
                    <th class="px-4 py-3 text-left text-slate-300">Tax Year</th>
                    <th class="px-4 py-3 text-right text-slate-300">Taxable Income</th>
                    <th class="px-4 py-3 text-right text-slate-300">Tax Payable</th>
                    <th class="px-4 py-3 text-right text-slate-300">QPD Paid</th>
                    <th class="px-4 py-3 text-right text-slate-300">Balance Due</th>
                    <th class="px-4 py-3 text-center text-slate-300">Status</th>
                    <th class="px-4 py-3 text-right text-slate-300">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/10">
                <?php $__empty_1 = true; $__currentLoopData = $returns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $return): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-white/5">
                    <td class="px-4 py-3 font-mono"><?php echo e($return->return_no); ?></td>
                    <td class="px-4 py-3"><?php echo e($return->tax_year); ?></td>
                    <td class="px-4 py-3 text-right">$<?php echo e(number_format($return->taxable_income, 2)); ?></td>
                    <td class="px-4 py-3 text-right">$<?php echo e(number_format($return->total_tax, 2)); ?></td>
                    <td class="px-4 py-3 text-right">$<?php echo e(number_format($return->qpd_paid, 2)); ?></td>
                    <td class="px-4 py-3 text-right font-semibold <?php echo e($return->balance_due > 0 ? 'text-amber-400' : 'text-emerald-400'); ?>">
                        $<?php echo e(number_format($return->balance_due, 2)); ?>

                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-2 py-1 rounded-full text-xs 
                            <?php if($return->status === 'SUBMITTED'): ?> bg-green-600/20 text-green-300
                            <?php elseif($return->status === 'APPROVED'): ?> bg-blue-600/20 text-blue-300
                            <?php elseif($return->status === 'DRAFT'): ?> bg-yellow-600/20 text-yellow-300
                            <?php else: ?> bg-slate-600/20 text-slate-300
                            <?php endif; ?>">
                            <?php echo e($return->status); ?>

                        </span>
                    </td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="<?php echo e(route('modules.tax.income.show', $return)); ?>" 
                           class="text-indigo-400 hover:text-indigo-300">View</a>
                        <a href="<?php echo e(route('modules.tax.income.pdf', $return)); ?>" 
                           class="text-slate-400 hover:text-slate-300">PDF</a>
                        <a href="<?php echo e(route('modules.tax.income.csv', $return)); ?>" 
                           class="text-slate-400 hover:text-slate-300">CSV</a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" class="px-4 py-8 text-center text-slate-400">
                        No income tax returns found.
                        <a href="<?php echo e(route('modules.tax.income.create')); ?>" class="text-indigo-400 hover:underline">Create your first return</a>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        <?php echo e($returns->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.erp', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Desktop\Victor\tax_compliance\resources\views/modules/tax/income/index.blade.php ENDPATH**/ ?>