

<?php $__env->startSection('page_title', 'Financial Forecast Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-semibold text-white">Financial Forecast <?php echo e($year); ?></h2>
            <p class="text-sm text-slate-400">
                Based on actuals: Jan - <?php echo e($forecastedTB['period_actuals']); ?>

                (<?php echo e($forecastedTB['months_elapsed']); ?> months elapsed)
            </p>
        </div>
        <div class="flex gap-3">
            <a href="<?php echo e(route('modules.tax.qpd.forecast.profiles', ['year' => $year])); ?>" 
               class="px-4 py-2 bg-white/10 hover:bg-white/15 ring-1 ring-white/10 rounded-lg">
                Forecast Settings
            </a>
        </div>
    </div>

    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-4">
            <div class="text-xs text-slate-400">Est. Annual Income</div>
            <div class="text-2xl font-bold text-emerald-400">$<?php echo e(number_format($forecastedTB['totals']['INCOME'] ?? 0, 2)); ?></div>
        </div>
        <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-4">
            <div class="text-xs text-slate-400">Est. Annual Expenses</div>
            <div class="text-2xl font-bold text-amber-400">$<?php echo e(number_format($forecastedTB['totals']['EXPENSE'] ?? 0, 2)); ?></div>
        </div>
        <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-4">
            <div class="text-xs text-slate-400">Est. Net Profit</div>
            <div class="text-2xl font-bold text-indigo-400">$<?php echo e(number_format($forecastedTB['totals']['NET_PROFIT'] ?? 0, 2)); ?></div>
        </div>
        <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-4">
            <div class="text-xs text-slate-400">Est. Tax Liability</div>
            <div class="text-2xl font-bold text-rose-400">$<?php echo e(number_format($taxComputation['total_tax'] ?? 0, 2)); ?></div>
        </div>
    </div>

    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <a href="<?php echo e(route('modules.tax.qpd.forecast.tb', ['year' => $year])); ?>" 
           class="bg-indigo-600/20 hover:bg-indigo-600/30 rounded-xl ring-1 ring-indigo-500/30 p-5 transition-all">
            <div class="text-indigo-400 text-lg font-semibold mb-2">📊 Trial Balance</div>
            <p class="text-sm text-slate-400">View projected account balances at year-end</p>
        </a>
        
        <a href="<?php echo e(route('modules.tax.qpd.forecast.pl', ['year' => $year])); ?>" 
           class="bg-emerald-600/20 hover:bg-emerald-600/30 rounded-xl ring-1 ring-emerald-500/30 p-5 transition-all">
            <div class="text-emerald-400 text-lg font-semibold mb-2">📈 Profit & Loss</div>
            <p class="text-sm text-slate-400">Projected income statement</p>
        </a>
        
        <a href="<?php echo e(route('modules.tax.qpd.forecast.tax', ['year' => $year])); ?>" 
           class="bg-amber-600/20 hover:bg-amber-600/30 rounded-xl ring-1 ring-amber-500/30 p-5 transition-all">
            <div class="text-amber-400 text-lg font-semibold mb-2">💰 Tax Computation</div>
            <p class="text-sm text-slate-400">Estimated tax liability with add-backs</p>
        </a>
        
        <a href="<?php echo e(route('modules.tax.qpd.forecast.qpd', ['year' => $year])); ?>" 
           class="bg-rose-600/20 hover:bg-rose-600/30 rounded-xl ring-1 ring-rose-500/30 p-5 transition-all">
            <div class="text-rose-400 text-lg font-semibold mb-2">📅 QPD Estimates</div>
            <p class="text-sm text-slate-400">Quarterly payment projections</p>
        </a>
    </div>

    
    <?php if(isset($qpdEstimates)): ?>
    <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-5">
        <h3 class="text-lg font-semibold text-white mb-4">QPD Estimates Preview</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <?php $__currentLoopData = $qpdEstimates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q => $estimate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-black/40 rounded-lg p-4">
                <div class="text-sm text-slate-400">Quarter <?php echo e($q); ?></div>
                <div class="text-xl font-bold text-white">$<?php echo e(number_format($estimate['amount'], 2)); ?></div>
                <div class="text-xs text-slate-500">Due: <?php echo e($estimate['formatted_due']); ?></div>
                <div class="text-xs text-slate-500"><?php echo e($estimate['percentage']); ?>% of annual</div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Desktop\Victor\tax_compliance\resources\views/modules/tax/qpd/forecast_dashboard.blade.php ENDPATH**/ ?>