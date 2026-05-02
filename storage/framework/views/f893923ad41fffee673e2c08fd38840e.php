<?php $__env->startSection('page_title','Trial Balance'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    
    <form class="flex flex-wrap items-end gap-3 bg-black/20 p-4 rounded-xl ring-1 ring-white/10">
        <div>
            <label class="text-xs text-slate-300 block mb-1">From Date</label>
            <input type="date" name="from" value="<?php echo e($from); ?>"
                   class="px-3 py-2 rounded-lg bg-black/30 text-slate-100 ring-1 ring-white/10 focus:ring-2 focus:ring-indigo-400/40 outline-none w-full min-w-[140px]">
        </div>
        <div>
            <label class="text-xs text-slate-300 block mb-1">To Date</label>
            <input type="date" name="to" value="<?php echo e($to); ?>"
                   class="px-3 py-2 rounded-lg bg-black/30 text-slate-100 ring-1 ring-white/10 focus:ring-2 focus:ring-indigo-400/40 outline-none w-full min-w-[140px]">
        </div>
        <button class="px-4 py-2 rounded-lg bg-indigo-600/80 hover:bg-indigo-600 text-white text-sm font-medium transition-colors">
            Run Report
        </button>

        <a href="<?php echo e(route('modules.accounting.reports.trial-balance.csv', ['from'=>$from,'to'=>$to])); ?>"
           class="ml-auto px-4 py-2 rounded-lg bg-white/10 hover:bg-white/15 ring-1 ring-white/10 text-sm transition-colors inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
            </svg>
            Download CSV
        </a>
    </form>

    
    <?php
        $totalDebit = collect($rows)->sum('debit');
        $totalCredit = collect($rows)->sum('credit');
        $totalNet = collect($rows)->sum('net');
    ?>
    
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-4">
            <div class="text-xs text-slate-400 uppercase tracking-wider">Total Debits</div>
            <div class="text-xl font-semibold text-white mt-1"><?php echo e(number_format($totalDebit, 2)); ?></div>
        </div>
        <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-4">
            <div class="text-xs text-slate-400 uppercase tracking-wider">Total Credits</div>
            <div class="text-xl font-semibold text-white mt-1"><?php echo e(number_format($totalCredit, 2)); ?></div>
        </div>
        <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-4">
            <div class="text-xs text-slate-400 uppercase tracking-wider">Net Balance</div>
            <div class="text-xl font-semibold <?php echo e($totalNet >= 0 ? 'text-green-400' : 'text-red-400'); ?> mt-1">
                <?php echo e(number_format($totalNet, 2)); ?>

            </div>
        </div>
    </div>

    
    <div class="rounded-xl ring-1 ring-white/10 overflow-hidden bg-black/20">
        <div class="overflow-x-auto scrollable-content" style="max-height: 600px;">
            <table class="w-full text-sm">
                <thead class="bg-white/5 sticky top-0 z-10">
                    <tr>
                        <th class="p-3 text-left font-semibold text-slate-300 w-24">Code</th>
                        <th class="p-3 text-left font-semibold text-slate-300 w-40">Account</th>
                        <th class="p-3 text-left font-semibold text-slate-300 w-28">Type</th>
                        <th class="p-3 text-right font-semibold text-slate-300 w-20">Debit</th>
                        <th class="p-3 text-right font-semibold text-slate-300 w-20">Credit</th>
                        <th class="p-3 text-right font-semibold text-slate-300 w-20">Net</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                    <?php $__empty_1 = true; $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="p-3 font-mono text-slate-300"><?php echo e($r['code']); ?></td>
                            <td class="p-3 text-slate-200">
                                <div class="truncate max-w-[300px]" title="<?php echo e($r['name']); ?>">
                                    <?php echo e($r['name']); ?>

                                </div>
                            </td>
                            <td class="p-3">
                                <span class="px-2 py-1 rounded-full text-xs 
                                    <?php if($r['type'] == 'ASSET'): ?> bg-blue-500/20 text-blue-300
                                    <?php elseif($r['type'] == 'LIABILITY'): ?> bg-yellow-500/20 text-yellow-300
                                    <?php elseif($r['type'] == 'EQUITY'): ?> bg-green-500/20 text-green-300
                                    <?php elseif($r['type'] == 'INCOME'): ?> bg-purple-500/20 text-purple-300
                                    <?php elseif($r['type'] == 'EXPENSE'): ?> bg-red-500/20 text-red-300
                                    <?php else: ?> bg-gray-500/20 text-gray-300
                                    <?php endif; ?>">
                                    <?php echo e($r['type']); ?>

                                </span>
                            </td>
                            <td class="p-3 text-right font-mono <?php echo e($r['debit'] > 0 ? 'text-slate-200' : 'text-slate-500'); ?>">
                                <?php echo e($r['debit'] > 0 ? number_format((float)$r['debit'],2) : '-'); ?>

                            </td>
                            <td class="p-3 text-right font-mono <?php echo e($r['credit'] > 0 ? 'text-slate-200' : 'text-slate-500'); ?>">
                                <?php echo e($r['credit'] > 0 ? number_format((float)$r['credit'],2) : '-'); ?>

                            </td>
                            <td class="p-3 text-right font-mono font-medium
                                <?php if($r['net'] > 0): ?> text-green-400
                                <?php elseif($r['net'] < 0): ?> text-red-400
                                <?php else: ?> text-slate-400
                                <?php endif; ?>">
                                <?php echo e(number_format((float)$r['net'],2)); ?>

                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400">
                                No transactions found for the selected period
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                
                <tfoot class="bg-white/5 sticky bottom-0 z-10 border-t border-white/10">
                    <tr>
                        <td colspan="3" class="p-3 text-right font-semibold text-slate-300">Totals:</td>
                        <td class="p-3 text-right font-mono font-semibold text-slate-200"><?php echo e(number_format($totalDebit, 2)); ?></td>
                        <td class="p-3 text-right font-mono font-semibold text-slate-200"><?php echo e(number_format($totalCredit, 2)); ?></td>
                        <td class="p-3 text-right font-mono font-semibold <?php echo e($totalNet >= 0 ? 'text-green-400' : 'text-red-400'); ?>">
                            <?php echo e(number_format($totalNet, 2)); ?>

                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    
    <div class="text-xs text-slate-400 flex justify-between items-center">
        <span>Generated on: <?php echo e(now()->format('F j, Y H:i:s')); ?></span>
        <span>Period: <?php echo e($from); ?> to <?php echo e($to); ?></span>
    </div>
</div>


<style>
    .scrollable-content {
        scrollbar-width: thin;
        scrollbar-color: rgba(255,255,255,0.2) rgba(255,255,255,0.05);
    }
    
    .scrollable-content::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    .scrollable-content::-webkit-scrollbar-track {
        background: rgba(255,255,255,0.05);
        border-radius: 4px;
    }
    
    .scrollable-content::-webkit-scrollbar-thumb {
        background: rgba(255,255,255,0.2);
        border-radius: 4px;
    }
    
    .scrollable-content::-webkit-scrollbar-thumb:hover {
        background: rgba(255,255,255,0.3);
    }
    
    /* Ensure table doesn't overflow container */
    .overflow-x-auto {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    /* Column width constraints */
    th, td {
        white-space: nowrap;
    }
    
    td:first-child, th:first-child {
        padding-left: 1rem;
    }
    
    td:last-child, th:last-child {
        padding-right: 1rem;
    }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Desktop\Victor\tax_compliance\resources\views/modules/accounting/reports/trial_balance.blade.php ENDPATH**/ ?>