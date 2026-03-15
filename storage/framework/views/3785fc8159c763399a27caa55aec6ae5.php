

<?php $__env->startSection('page_title', 'QPD Estimates from Forecast'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-semibold text-white">QPD Estimates - <?php echo e($year); ?></h2>
            <p class="text-sm text-slate-400">Quarterly payment projections based on forecast</p>
        </div>
        <a href="<?php echo e(route('modules.tax.qpd.forecast.dashboard', ['year' => $year])); ?>" 
           class="px-4 py-2 bg-white/10 hover:bg-white/15 rounded-lg">
            ← Back to Dashboard
        </a>
    </div>

    
    <div class="bg-indigo-600/20 rounded-xl ring-1 ring-indigo-500/30 p-6 mb-6">
        <div class="text-center">
            <div class="text-sm text-slate-400 mb-1">Estimated Annual Tax Liability</div>
            <div class="text-4xl font-bold text-indigo-400">$<?php echo e(number_format($qpdEstimates[1]['amount'] * 10, 2)); ?></div>
            <div class="text-xs text-slate-500 mt-2">Based on forecasted financials</div>
        </div>
    </div>

    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <?php $__currentLoopData = $qpdEstimates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q => $estimate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-5">
            <div class="flex justify-between items-start mb-3">
                <div>
                    <div class="text-xs text-slate-400">Quarter <?php echo e($q); ?></div>
                    <div class="text-2xl font-bold text-white">$<?php echo e(number_format($estimate['amount'], 2)); ?></div>
                </div>
                <span class="px-2 py-1 rounded-full text-xs bg-blue-600/20 text-blue-300">
                    <?php echo e($estimate['percentage']); ?>%
                </span>
            </div>
            <div class="text-sm text-slate-400">Due Date</div>
            <div class="text-lg font-semibold text-amber-400"><?php echo e($estimate['formatted_due']); ?></div>
            <div class="mt-3 text-xs text-slate-500">
                <?php echo e($estimate['percentage']); ?>% of annual tax
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <div class="bg-black/20 rounded-xl ring-1 ring-white/10 overflow-hidden">
        <div class="p-4 border-b border-white/10">
            <h3 class="text-sm font-semibold text-white">QPD Payment Schedule</h3>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-white/5">
                <tr>
                    <th class="px-4 py-3 text-left text-slate-300">Quarter</th>
                    <th class="px-4 py-3 text-left text-slate-300">Due Date</th>
                    <th class="px-4 py-3 text-right text-slate-300">Percentage</th>
                    <th class="px-4 py-3 text-right text-slate-300">Amount</th>
                    <th class="px-4 py-3 text-center text-slate-300">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/10">
                <?php $__currentLoopData = $qpdEstimates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q => $estimate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="hover:bg-white/5">
                    <td class="px-4 py-3 font-medium">Quarter <?php echo e($q); ?></td>
                    <td class="px-4 py-3"><?php echo e($estimate['formatted_due']); ?></td>
                    <td class="px-4 py-3 text-right"><?php echo e($estimate['percentage']); ?>%</td>
                    <td class="px-4 py-3 text-right font-semibold text-white">$<?php echo e(number_format($estimate['amount'], 2)); ?></td>
                    <td class="px-4 py-3 text-center">
                        <?php
                            $isPast = now()->gt($estimate['due_date']);
                        ?>
                        <span class="px-2 py-1 rounded-full text-xs 
                            <?php if($isPast): ?> bg-amber-600/20 text-amber-300
                            <?php else: ?> bg-emerald-600/20 text-emerald-300
                            <?php endif; ?>">
                            <?php echo e($isPast ? 'PAST DUE' : 'UPCOMING'); ?>

                        </span>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>

    
    <div class="flex justify-end gap-3 mt-6">
        <a href="<?php echo e(route('modules.tax.qpd.create', ['tax_year' => $year])); ?>" 
           class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">
            Make QPD Payment
        </a>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Desktop\Victor\tax_compliance\resources\views/modules/tax/qpd/forecast_qpd.blade.php ENDPATH**/ ?>