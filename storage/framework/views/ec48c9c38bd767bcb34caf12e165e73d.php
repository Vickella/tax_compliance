

<?php $__env->startSection('page_title', 'Forecasted Profit & Loss'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-semibold text-white">Forecasted Profit & Loss - <?php echo e($year); ?></h2>
            <p class="text-sm text-slate-400">Projected year-end financial performance</p>
        </div>
        <a href="<?php echo e(route('modules.tax.qpd.forecast.dashboard', ['year' => $year])); ?>" 
           class="px-4 py-2 bg-white/10 hover:bg-white/15 rounded-lg">
            ← Back to Dashboard
        </a>
    </div>

    <div class="bg-black/20 rounded-xl ring-1 ring-white/10 overflow-hidden">
        <div class="p-6">
            
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-emerald-400 mb-3">Revenue</h3>
                <?php $__currentLoopData = $forecastedPL['revenue']['accounts']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex justify-between items-center py-2 border-b border-white/5">
                    <div>
                        <span class="text-sm text-slate-400"><?php echo e($code); ?></span>
                        <span class="text-sm text-white ml-2"><?php echo e($account['name']); ?></span>
                    </div>
                    <span class="text-lg font-semibold text-emerald-400">$<?php echo e(number_format($account['projected_dec'], 2)); ?></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <div class="flex justify-between items-center py-3 mt-2 border-t border-white/20">
                    <span class="text-base font-semibold text-white">Total Revenue</span>
                    <span class="text-xl font-bold text-emerald-400">$<?php echo e(number_format($forecastedPL['revenue']['total'], 2)); ?></span>
                </div>
            </div>

            
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-amber-400 mb-3">Cost of Sales</h3>
                <?php $__currentLoopData = $forecastedPL['cost_of_sales']['accounts']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex justify-between items-center py-2 border-b border-white/5">
                    <div>
                        <span class="text-sm text-slate-400"><?php echo e($code); ?></span>
                        <span class="text-sm text-white ml-2"><?php echo e($account['name']); ?></span>
                    </div>
                    <span class="text-lg font-semibold text-amber-400">$<?php echo e(number_format($account['projected_dec'], 2)); ?></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <div class="flex justify-between items-center py-3 mt-2 border-t border-white/20">
                    <span class="text-base font-semibold text-white">Total Cost of Sales</span>
                    <span class="text-xl font-bold text-amber-400">$<?php echo e(number_format($forecastedPL['cost_of_sales']['total'], 2)); ?></span>
                </div>
            </div>

            
            <div class="bg-indigo-600/20 rounded-lg p-4 mb-6">
                <div class="flex justify-between items-center">
                    <span class="text-base font-semibold text-white">Gross Profit</span>
                    <span class="text-2xl font-bold text-indigo-400">$<?php echo e(number_format($forecastedPL['gross_profit'], 2)); ?></span>
                </div>
            </div>

            
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-amber-400 mb-3">Operating Expenses</h3>
                <?php $__currentLoopData = $forecastedPL['operating_expenses']['accounts']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex justify-between items-center py-2 border-b border-white/5">
                    <div>
                        <span class="text-sm text-slate-400"><?php echo e($code); ?></span>
                        <span class="text-sm text-white ml-2"><?php echo e($account['name']); ?></span>
                    </div>
                    <span class="text-lg font-semibold text-amber-400">$<?php echo e(number_format($account['projected_dec'], 2)); ?></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <div class="flex justify-between items-center py-3 mt-2 border-t border-white/20">
                    <span class="text-base font-semibold text-white">Total Operating Expenses</span>
                    <span class="text-xl font-bold text-amber-400">$<?php echo e(number_format($forecastedPL['operating_expenses']['total'], 2)); ?></span>
                </div>
            </div>

            
            <div class="bg-indigo-600/20 rounded-lg p-4 mb-6">
                <div class="flex justify-between items-center">
                    <span class="text-base font-semibold text-white">Operating Profit</span>
                    <span class="text-2xl font-bold text-indigo-400">$<?php echo e(number_format($forecastedPL['operating_profit'], 2)); ?></span>
                </div>
            </div>

            
            <?php if($forecastedPL['other_income']['total'] > 0 || $forecastedPL['other_expenses']['total'] > 0): ?>
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-slate-400 mb-3">Other Items</h3>
                <?php $__currentLoopData = $forecastedPL['other_income']['accounts']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex justify-between items-center py-2 border-b border-white/5">
                    <div>
                        <span class="text-sm text-slate-400"><?php echo e($code); ?></span>
                        <span class="text-sm text-white ml-2"><?php echo e($account['name']); ?></span>
                    </div>
                    <span class="text-lg font-semibold text-emerald-400">$<?php echo e(number_format($account['projected_dec'], 2)); ?></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php $__currentLoopData = $forecastedPL['other_expenses']['accounts']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex justify-between items-center py-2 border-b border-white/5">
                    <div>
                        <span class="text-sm text-slate-400"><?php echo e($code); ?></span>
                        <span class="text-sm text-white ml-2"><?php echo e($account['name']); ?></span>
                    </div>
                    <span class="text-lg font-semibold text-amber-400">$<?php echo e(number_format($account['projected_dec'], 2)); ?></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php endif; ?>

            
            <div class="bg-rose-600/20 rounded-lg p-4">
                <div class="flex justify-between items-center">
                    <span class="text-base font-semibold text-white">Net Profit Before Tax</span>
                    <span class="text-2xl font-bold text-rose-400">$<?php echo e(number_format($forecastedPL['net_profit_before_tax'], 2)); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Desktop\Victor\tax_compliance\resources\views/modules/tax/qpd/forecast_pl.blade.php ENDPATH**/ ?>