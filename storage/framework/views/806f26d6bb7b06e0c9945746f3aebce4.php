

<?php $__env->startSection('page_title', 'Forecasted Trial Balance'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-semibold text-white">Forecasted Trial Balance - <?php echo e($year); ?></h2>
            <p class="text-sm text-slate-400">
                Based on actuals: <?php echo e($forecastedTB['period_actuals']); ?>

                (<?php echo e($forecastedTB['months_elapsed']); ?> months elapsed, 
                <?php echo e($forecastedTB['months_remaining']); ?> months to forecast)
            </p>
        </div>
        <a href="<?php echo e(route('modules.tax.qpd.forecast.dashboard', ['year' => $year])); ?>" 
           class="px-4 py-2 bg-white/10 hover:bg-white/15 rounded-lg">
            ← Back to Dashboard
        </a>
    </div>

    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-emerald-600/20 rounded-xl ring-1 ring-emerald-500/30 p-4">
            <div class="text-xs text-slate-400">Total Projected Income</div>
            <div class="text-2xl font-bold text-emerald-400">$<?php echo e(number_format($forecastedTB['totals']['INCOME'] ?? 0, 2)); ?></div>
        </div>
        <div class="bg-amber-600/20 rounded-xl ring-1 ring-amber-500/30 p-4">
            <div class="text-xs text-slate-400">Total Projected Expenses</div>
            <div class="text-2xl font-bold text-amber-400">$<?php echo e(number_format($forecastedTB['totals']['EXPENSE'] ?? 0, 2)); ?></div>
        </div>
        <div class="bg-indigo-600/20 rounded-xl ring-1 ring-indigo-500/30 p-4">
            <div class="text-xs text-slate-400">Projected Net Profit</div>
            <div class="text-2xl font-bold text-indigo-400">$<?php echo e(number_format($forecastedTB['totals']['NET_PROFIT'] ?? 0, 2)); ?></div>
        </div>
    </div>

    
    <div class="bg-black/20 rounded-xl ring-1 ring-white/10 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-white/5">
                    <tr>
                        <th class="px-4 py-3 text-left text-slate-300">Account Code</th>
                        <th class="px-4 py-3 text-left text-slate-300">Account Name</th>
                        <th class="px-4 py-3 text-right text-slate-300">Type</th>
                        <th class="px-4 py-3 text-right text-slate-300">YTD Actual</th>
                        <th class="px-4 py-3 text-right text-slate-300">Monthly Avg</th>
                        <th class="px-4 py-3 text-right text-slate-300">Forecast Method</th>
                        <th class="px-4 py-3 text-right text-slate-300">Projected Dec</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                    <?php $__currentLoopData = $forecastedTB['accounts']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-white/5">
                        <td class="px-4 py-3 font-mono"><?php echo e($account['code']); ?></td>
                        <td class="px-4 py-3"><?php echo e($account['name']); ?></td>
                        <td class="px-4 py-3 text-right">
                            <span class="px-2 py-1 rounded-full text-xs 
                                <?php if($account['type'] == 'INCOME'): ?> bg-emerald-600/20 text-emerald-300
                                <?php elseif($account['type'] == 'EXPENSE'): ?> bg-amber-600/20 text-amber-300
                                <?php else: ?> bg-slate-600/20 text-slate-300
                                <?php endif; ?>">
                                <?php echo e($account['type']); ?>

                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">$<?php echo e(number_format($account['actual_ytd'], 2)); ?></td>
                        <td class="px-4 py-3 text-right">$<?php echo e(number_format($account['monthly_avg'], 2)); ?></td>
                        <td class="px-4 py-3 text-right">
                            <?php if($account['growth_rate'] > 0): ?>
                                <?php echo e(ucfirst($account['forecast_method'])); ?> +<?php echo e($account['growth_rate']); ?>%
                            <?php else: ?>
                                <?php echo e(ucfirst($account['forecast_method'])); ?>

                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3 text-right font-semibold text-white">$<?php echo e(number_format($account['projected_dec'], 2)); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Desktop\Victor\tax_compliance\resources\views/modules/tax/qpd/forecast_tb.blade.php ENDPATH**/ ?>