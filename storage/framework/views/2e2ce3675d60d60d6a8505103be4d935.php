

<?php $__env->startSection('page_title', 'Income Tax Return'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto">
    
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-semibold text-white">Income Tax Return <?php echo e($return->return_no); ?></h2>
            <p class="text-sm text-slate-400">
                Tax Year <?php echo e($return->tax_year); ?> • 
                Filed <?php echo e($return->filing_date ? $return->filing_date->format('d M Y') : 'Not filed'); ?>

            </p>
        </div>
        <div class="flex gap-3">
            <?php if($return->status === 'DRAFT'): ?>
            
            
            <form method="POST" action="<?php echo e(route('modules.tax.income.submit', $return)); ?>" class="inline">
                <?php echo csrf_field(); ?>
                <button type="submit" 
                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors">
                    Submit Return
                </button>
            </form>
            <?php endif; ?>
            <a href="<?php echo e(route('modules.tax.income.pdf', $return)); ?>" 
               class="px-4 py-2 bg-white/5 hover:bg-white/10 ring-1 ring-white/10 rounded-lg transition-colors">
                Download PDF
            </a>
            <a href="<?php echo e(route('modules.tax.income.csv', $return)); ?>" 
               class="px-4 py-2 bg-white/5 hover:bg-white/10 ring-1 ring-white/10 rounded-lg transition-colors">
                Export CSV
            </a>
        </div>
    </div>

    
    <div class="mb-6 p-4 rounded-lg ring-1 
        <?php if($return->status === 'APPROVED'): ?> bg-green-600/20 ring-green-600/30
        <?php elseif($return->status === 'SUBMITTED'): ?> bg-blue-600/20 ring-blue-600/30
        <?php else: ?> bg-yellow-600/20 ring-yellow-600/30
        <?php endif; ?>">
        <div class="flex items-center gap-3">
            <span class="text-sm font-medium">
                Status: <span class="font-bold"><?php echo e($return->status); ?></span>
            </span>
            <?php if($return->status === 'DRAFT'): ?>
            <span class="text-xs opacity-80">This return has not been submitted to ZIMRA yet</span>
            <?php elseif($return->status === 'SUBMITTED'): ?>
            <span class="text-xs opacity-80">
                Submitted on <?php echo e($return->submitted_at ? $return->submitted_at->format('d M Y H:i') : 'Unknown date'); ?>

            </span>
            <?php endif; ?>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-5">
                <h3 class="text-sm font-semibold text-white mb-4">Tax Summary</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Tax Year</span>
                        <span class="text-white font-semibold"><?php echo e($return->tax_year); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Return No</span>
                        <span class="text-white font-mono"><?php echo e($return->return_no); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Filing Date</span>
                        <span class="text-white"><?php echo e($return->filing_date ? $return->filing_date->format('d/m/Y') : 'Not set'); ?></span>
                    </div>
                    <div class="border-t border-white/10 my-3"></div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Taxable Income</span>
                        <span class="text-white font-bold">$<?php echo e(number_format($return->taxable_income, 2)); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Tax Rate</span>
                        <span class="text-white"><?php echo e($return->tax_rate); ?>%</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Income Tax</span>
                        <span class="text-white">$<?php echo e(number_format($return->income_tax, 2)); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">AIDS Levy</span>
                        <span class="text-white">$<?php echo e(number_format($return->aids_levy, 2)); ?></span>
                    </div>
                    <div class="border-t border-white/10 my-3"></div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Total Tax</span>
                        <span class="text-indigo-400 font-bold text-lg">$<?php echo e(number_format($return->total_tax, 2)); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">QPD Paid</span>
                        <span class="text-emerald-400">$<?php echo e(number_format($return->qpd_paid, 2)); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Balance Due</span>
                        <span class="<?php echo e($return->balance_due > 0 ? 'text-amber-400' : 'text-emerald-400'); ?> font-bold">
                            $<?php echo e(number_format(abs($return->balance_due), 2)); ?>

                            <span class="text-xs ml-1"><?php echo e($return->balance_due > 0 ? '(Payable)' : ($return->balance_due < 0 ? '(Refundable)' : '')); ?></span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-5">
                <h3 class="text-sm font-semibold text-white mb-4">Assessed Losses</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Brought Forward</span>
                        <span class="text-white">$<?php echo e(number_format($return->assessed_loss_bf, 2)); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Carried Forward</span>
                        <span class="text-white">$<?php echo e(number_format($return->assessed_loss_cf, 2)); ?></span>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-5">
                <h3 class="text-sm font-semibold text-white mb-3">Income Breakdown</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-white/5">
                            <tr>
                                <th class="px-3 py-2 text-left text-slate-300">Code</th>
                                <th class="px-3 py-2 text-left text-slate-300">Account Name</th>
                                <th class="px-3 py-2 text-right text-slate-300">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            <?php $__empty_1 = true; $__currentLoopData = $return->metadata['income_breakdown'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="px-3 py-2 font-mono"><?php echo e($item['code']); ?></td>
                                <td class="px-3 py-2"><?php echo e($item['name']); ?></td>
                                <td class="px-3 py-2 text-right">$<?php echo e(number_format($item['amount'], 2)); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="3" class="px-3 py-4 text-center text-slate-500">No income breakdown available</td>
                            </tr>
                            <?php endif; ?>
                            <tr class="bg-white/5 font-semibold">
                                <td colspan="2" class="px-3 py-2 text-right">Total Income</td>
                                <td class="px-3 py-2 text-right">$<?php echo e(number_format($return->total_income, 2)); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            
            <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-5">
                <h3 class="text-sm font-semibold text-white mb-3">Expense Breakdown</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-white/5">
                            <tr>
                                <th class="px-3 py-2 text-left text-slate-300">Code</th>
                                <th class="px-3 py-2 text-left text-slate-300">Account Name</th>
                                <th class="px-3 py-2 text-right text-slate-300">Total</th>
                                <th class="px-3 py-2 text-right text-slate-300">Deductible %</th>
                                <th class="px-3 py-2 text-right text-slate-300">Deductible</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            <?php $__empty_1 = true; $__currentLoopData = $return->metadata['expense_breakdown'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="px-3 py-2 font-mono"><?php echo e($item['code']); ?></td>
                                <td class="px-3 py-2"><?php echo e($item['name']); ?></td>
                                <td class="px-3 py-2 text-right">$<?php echo e(number_format($item['amount'], 2)); ?></td>
                                <td class="px-3 py-2 text-right"><?php echo e($item['deductible_percent'] ?? 100); ?>%</td>
                                <td class="px-3 py-2 text-right">$<?php echo e(number_format($item['deductible'] ?? $item['amount'], 2)); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="px-3 py-4 text-center text-slate-500">No expense breakdown available</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            
            <?php if(!empty($return->metadata['addback_breakdown'])): ?>
            <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-5">
                <h3 class="text-sm font-semibold text-white mb-3">Non-Deductible Add Backs</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-white/5">
                            <tr>
                                <th class="px-3 py-2 text-left text-slate-300">Code</th>
                                <th class="px-3 py-2 text-left text-slate-300">Account Name</th>
                                <th class="px-3 py-2 text-right text-slate-300">Amount</th>
                                <th class="px-3 py-2 text-left text-slate-300">Reason</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            <?php $__currentLoopData = $return->metadata['addback_breakdown']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="px-3 py-2 font-mono"><?php echo e($item['code']); ?></td>
                                <td class="px-3 py-2"><?php echo e($item['name']); ?></td>
                                <td class="px-3 py-2 text-right text-amber-400">$<?php echo e(number_format($item['amount'], 2)); ?></td>
                                <td class="px-3 py-2 text-slate-300"><?php echo e($item['reason'] ?? 'Non-deductible'); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>

            
            <?php if(!empty($return->metadata['notes'])): ?>
            <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-5">
                <h3 class="text-sm font-semibold text-white mb-3">Notes</h3>
                <p class="text-slate-300"><?php echo e($return->metadata['notes']); ?></p>
            </div>
            <?php endif; ?>

            
            <?php if(!empty($return->notes)): ?>
            <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-5">
                <h3 class="text-sm font-semibold text-white mb-3">Additional Notes</h3>
                <p class="text-slate-300"><?php echo e($return->notes); ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="mt-6 pt-4 border-t border-white/10">
        <a href="<?php echo e(route('modules.tax.income.index')); ?>" 
           class="inline-flex items-center text-slate-400 hover:text-white transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Income Tax Returns
        </a>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Desktop\Victor\tax_compliance\resources\views/modules/tax/income/show.blade.php ENDPATH**/ ?>