

<?php $__env->startSection('page_title', 'VAT Return'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto">
    
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-semibold text-white">VAT Return <?php echo e($return->return_no ?? 'N/A'); ?></h2>
            <p class="text-sm text-slate-400">
                <?php echo e($return->period_start ? $return->period_start->format('F Y') : 'N/A'); ?> 
                • Filed <?php echo e($return->filing_date ? $return->filing_date->format('d M Y') : 'Not filed'); ?>

            </p>
        </div>
        <div class="flex gap-3">
            <?php if($return->status === 'DRAFT'): ?>
            <form method="POST" action="<?php echo e(route('modules.tax.vat.submit', $return)); ?>" class="inline">
                <?php echo csrf_field(); ?>
                <button type="submit" 
                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors">
                    Submit Return
                </button>
            </form>
            <?php endif; ?>
            <a href="<?php echo e(route('modules.tax.vat.pdf', $return)); ?>" 
               class="px-4 py-2 bg-white/5 hover:bg-white/10 ring-1 ring-white/10 rounded-lg transition-colors">
                Download VAT7
            </a>
            <a href="<?php echo e(route('modules.tax.vat.csv', $return)); ?>" 
               class="px-4 py-2 bg-white/5 hover:bg-white/10 ring-1 ring-white/10 rounded-lg transition-colors">
                Export CSV
            </a>
        </div>
    </div>

    
    <div class="mb-6 p-4 rounded-lg ring-1 
        <?php if($return->status === 'PAID'): ?> bg-emerald-600/20 ring-emerald-600/30
        <?php elseif($return->status === 'SUBMITTED'): ?> bg-blue-600/20 ring-blue-600/30
        <?php else: ?> bg-yellow-600/20 ring-yellow-600/30
        <?php endif; ?>">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="text-sm font-medium">
                    Status: <span class="font-bold"><?php echo e($return->status ?? 'DRAFT'); ?></span>
                </span>
            </div>
            <span class="text-xs">
                Period: <?php echo e($return->period_start ? $return->period_start->format('d/m/Y') : 'N/A'); ?> - 
                <?php echo e($return->period_end ? $return->period_end->format('d/m/Y') : 'N/A'); ?>

            </span>
        </div>
    </div>

    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-4">
            <div class="text-xs text-slate-400">Output VAT</div>
            <div class="text-xl font-bold text-amber-400">$<?php echo e(number_format($return->output_vat ?? 0, 2)); ?></div>
        </div>
        <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-4">
            <div class="text-xs text-slate-400">Input VAT</div>
            <div class="text-xl font-bold text-emerald-400">$<?php echo e(number_format($return->input_vat ?? 0, 2)); ?></div>
        </div>
        <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-4">
            <div class="text-xs text-slate-400">VAT Rate</div>
            <div class="text-xl font-bold text-white"><?php echo e($return->vat_rate ?? 0); ?>%</div>
        </div>
        <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-4">
            <div class="text-xs text-slate-400">VAT <?php echo e(($return->vat_payable ?? 0) > 0 ? 'Payable' : 'Refundable'); ?></div>
            <div class="text-xl font-bold <?php echo e(($return->vat_payable ?? 0) > 0 ? 'text-amber-400' : 'text-emerald-400'); ?>">
                $<?php echo e(number_format(abs($return->vat_payable ?? 0), 2)); ?>

            </div>
        </div>
    </div>

    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-5">
            <h3 class="text-sm font-semibold text-white mb-3">Output VAT Details</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-white/5">
                        <tr>
                            <th class="px-3 py-2 text-left text-slate-300">Account</th>
                            <th class="px-3 py-2 text-left text-slate-300">Name</th>
                            <th class="px-3 py-2 text-right text-slate-300">Amount</th>
                            <th class="px-3 py-2 text-right text-slate-300">VAT</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        <?php $__empty_1 = true; $__currentLoopData = $return->metadata['details']['output'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="px-3 py-2 font-mono"><?php echo e($item['code']); ?></td>
                            <td class="px-3 py-2"><?php echo e($item['name']); ?></td>
                            <td class="px-3 py-2 text-right">$<?php echo e(number_format($item['amount'], 2)); ?></td>
                            <td class="px-3 py-2 text-right text-amber-400">$<?php echo e(number_format($item['vat'], 2)); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="px-3 py-4 text-center text-slate-400">
                                No output VAT transactions found
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        
        <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-5">
            <h3 class="text-sm font-semibold text-white mb-3">Input VAT Details</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-white/5">
                        <tr>
                            <th class="px-3 py-2 text-left text-slate-300">Account</th>
                            <th class="px-3 py-2 text-left text-slate-300">Name</th>
                            <th class="px-3 py-2 text-right text-slate-300">Amount</th>
                            <th class="px-3 py-2 text-right text-slate-300">VAT</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        <?php $__empty_1 = true; $__currentLoopData = $return->metadata['details']['input'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="px-3 py-2 font-mono"><?php echo e($item['code']); ?></td>
                            <td class="px-3 py-2"><?php echo e($item['name']); ?></td>
                            <td class="px-3 py-2 text-right">$<?php echo e(number_format($item['amount'], 2)); ?></td>
                            <td class="px-3 py-2 text-right text-emerald-400">$<?php echo e(number_format($item['vat'], 2)); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="px-3 py-4 text-center text-slate-400">
                                No input VAT transactions found
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    
    <?php if($return->metadata['notes'] ?? false): ?>
    <div class="mt-6 bg-black/20 rounded-xl ring-1 ring-white/10 p-5">
        <h3 class="text-sm font-semibold text-white mb-3">Notes</h3>
        <p class="text-slate-300"><?php echo e($return->metadata['notes']); ?></p>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.erp', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Desktop\Victor\tax_compliance\resources\views/modules/tax/vat/show.blade.php ENDPATH**/ ?>