

<?php $__env->startSection('page_title', 'Forecasted Tax Computation'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-semibold text-white">Tax Computation - <?php echo e($year); ?></h2>
            <p class="text-sm text-slate-400">Based on forecasted financials</p>
        </div>
        <a href="<?php echo e(route('modules.tax.qpd.forecast.dashboard', ['year' => $year])); ?>" 
           class="px-4 py-2 bg-white/10 hover:bg-white/15 rounded-lg">
            ← Back to Dashboard
        </a>
    </div>

    <div class="bg-black/20 rounded-xl ring-1 ring-white/10 overflow-hidden">
        <div class="p-6 space-y-6">
            
            <div class="flex justify-between items-center py-3 border-b border-white/10">
                <span class="text-base text-white">Net Profit Before Tax</span>
                <span class="text-xl font-semibold text-white">$<?php echo e(number_format($taxComputation['profit_before_tax'], 2)); ?></span>
            </div>

            
            <?php if(!empty($taxComputation['add_backs'])): ?>
            <div>
                <h3 class="text-lg font-semibold text-amber-400 mb-3">Add Backs (Non-Deductible Expenses)</h3>
                <?php $__currentLoopData = $taxComputation['add_backs']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex justify-between items-center py-2 border-b border-white/5">
                    <div>
                        <span class="text-sm text-slate-400"><?php echo e($code); ?></span>
                        <span class="text-sm text-white ml-2"><?php echo e($item['name']); ?></span>
                        <p class="text-xs text-slate-500"><?php echo e($item['reason']); ?></p>
                    </div>
                    <span class="text-lg font-semibold text-amber-400">$<?php echo e(number_format($item['amount'], 2)); ?></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <div class="flex justify-between items-center py-3 mt-2 border-t border-white/20">
                    <span class="text-base font-semibold text-white">Total Add Backs</span>
                    <span class="text-xl font-bold text-amber-400">$<?php echo e(number_format($taxComputation['total_add_backs'], 2)); ?></span>
                </div>
            </div>
            <?php endif; ?>

            
            <div class="bg-indigo-600/20 rounded-lg p-4">
                <div class="flex justify-between items-center">
                    <span class="text-base font-semibold text-white">Taxable Income</span>
                    <span class="text-2xl font-bold text-indigo-400">$<?php echo e(number_format($taxComputation['taxable_income'], 2)); ?></span>
                </div>
            </div>

            
            <div class="space-y-3">
                <div class="flex justify-between items-center py-2">
                    <span class="text-sm text-slate-400">Income Tax Rate</span>
                    <span class="text-lg text-white"><?php echo e($taxComputation['tax_rate']); ?>%</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-white/10">
                    <span class="text-sm text-slate-400">Income Tax</span>
                    <span class="text-lg text-emerald-400">$<?php echo e(number_format($taxComputation['income_tax'], 2)); ?></span>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="text-sm text-slate-400">AIDS Levy (3%)</span>
                    <span class="text-lg text-emerald-400">$<?php echo e(number_format($taxComputation['aids_levy'], 2)); ?></span>
                </div>
                <div class="flex justify-between items-center py-3 border-t border-white/20">
                    <span class="text-base font-semibold text-white">Total Estimated Tax</span>
                    <span class="text-2xl font-bold text-rose-400">$<?php echo e(number_format($taxComputation['total_tax'], 2)); ?></span>
                </div>
            </div>

            
            <div class="mt-6 p-4 bg-black/40 rounded-lg">
                <h4 class="text-sm font-semibold text-white mb-3">QPD Payment Schedule</h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <?php
                        $percentages = [1 => 10, 2 => 25, 3 => 30, 4 => 35];
                        $dueDates = [
                            1 => $year . '-03-25',
                            2 => $year . '-06-25', 
                            3 => $year . '-09-25',
                            4 => $year . '-12-20'
                        ];
                    ?>
                    <?php $__currentLoopData = $percentages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q => $pct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="text-center">
                        <div class="text-xs text-slate-400">Q<?php echo e($q); ?></div>
                        <div class="text-sm font-bold text-white">$<?php echo e(number_format($taxComputation['total_tax'] * $pct / 100, 0)); ?></div>
                        <div class="text-xs text-slate-500"><?php echo e(date('d M', strtotime($dueDates[$q]))); ?></div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Desktop\Victor\tax_compliance\resources\views/modules/tax/qpd/forecast_tax.blade.php ENDPATH**/ ?>