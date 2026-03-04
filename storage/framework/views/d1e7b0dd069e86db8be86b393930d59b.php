

<?php $__env->startSection('page_title', 'Create VAT Return'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto">
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-white">VAT Return - <?php echo e(\Carbon\Carbon::parse($periodStart)->format('F Y')); ?></h2>
        <p class="text-sm text-slate-400">Period: <?php echo e(\Carbon\Carbon::parse($periodStart)->format('d/m/Y')); ?> - <?php echo e(\Carbon\Carbon::parse($periodEnd)->format('d/m/Y')); ?></p>
    </div>

    <form method="POST" action="<?php echo e(route('modules.tax.vat.store')); ?>">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="period_start" value="<?php echo e($periodStart); ?>">
        <input type="hidden" name="period_end" value="<?php echo e($periodEnd); ?>">
        <input type="hidden" name="vat_rate" value="<?php echo e($calculation['vat_rate']); ?>">
        <input type="hidden" name="output_vat" value="<?php echo e($calculation['output_vat']); ?>">
        <input type="hidden" name="input_vat" value="<?php echo e($calculation['input_vat']); ?>">
        <input type="hidden" name="vat_payable" value="<?php echo e($calculation['vat_payable']); ?>">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <div class="lg:col-span-1">
                <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-5 h-full">
                    <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
                        <span class="w-2 h-2 bg-amber-400 rounded-full"></span>
                        Output VAT
                    </h3>
                    <div class="text-2xl font-bold text-amber-400 mb-4">$<?php echo e(number_format($calculation['output_vat'], 2)); ?></div>
                    
                    <div class="space-y-2 max-h-96 overflow-y-auto">
                        <?php $__currentLoopData = $calculation['details']['output'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex justify-between items-center p-2 bg-black/30 rounded-lg">
                            <div>
                                <div class="text-xs text-slate-400"><?php echo e($item['code']); ?></div>
                                <div class="text-sm text-white"><?php echo e($item['name']); ?></div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm text-white">$<?php echo e(number_format($item['amount'], 2)); ?></div>
                                <div class="text-xs text-amber-400">VAT: $<?php echo e(number_format($item['vat'], 2)); ?></div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>

            
            <div class="lg:col-span-1">
                <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-5 h-full">
                    <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
                        <span class="w-2 h-2 bg-emerald-400 rounded-full"></span>
                        Input VAT
                    </h3>
                    <div class="text-2xl font-bold text-emerald-400 mb-4">$<?php echo e(number_format($calculation['input_vat'], 2)); ?></div>
                    
                    <div class="space-y-2 max-h-96 overflow-y-auto">
                        <?php $__currentLoopData = $calculation['details']['input'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex justify-between items-center p-2 bg-black/30 rounded-lg">
                            <div>
                                <div class="text-xs text-slate-400"><?php echo e($item['code']); ?></div>
                                <div class="text-sm text-white"><?php echo e($item['name']); ?></div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm text-white">$<?php echo e(number_format($item['amount'], 2)); ?></div>
                                <div class="text-xs text-emerald-400">VAT: $<?php echo e(number_format($item['vat'], 2)); ?></div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>

            
            <div class="lg:col-span-1">
                <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-5">
                    <h3 class="text-sm font-semibold text-white mb-4">VAT Summary</h3>
                    
                    <div class="space-y-4">
                        <div class="p-4 bg-black/40 rounded-lg">
                            <div class="text-xs text-slate-400">VAT Rate</div>
                            <div class="text-xl font-semibold text-white"><?php echo e($calculation['vat_rate']); ?>%</div>
                        </div>

                        <div class="p-4 bg-black/40 rounded-lg">
                            <div class="text-xs text-slate-400">Tax Fraction</div>
                            <div class="text-xl font-semibold text-white"><?php echo e($calculation['tax_fraction']); ?></div>
                        </div>

                        <div class="border-t border-white/10 my-4"></div>

                        <div class="flex justify-between items-center">
                            <span class="text-slate-400">Output VAT</span>
                            <span class="text-white font-semibold">$<?php echo e(number_format($calculation['output_vat'], 2)); ?></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-400">Input VAT</span>
                            <span class="text-white font-semibold">$<?php echo e(number_format($calculation['input_vat'], 2)); ?></span>
                        </div>

                        <div class="border-t border-white/10 my-4"></div>

                        <div class="p-4 rounded-lg <?php echo e($calculation['vat_payable'] > 0 ? 'bg-amber-600/20' : 'bg-emerald-600/20'); ?>">
                            <div class="text-xs text-slate-400">VAT <?php echo e($calculation['vat_payable'] > 0 ? 'Payable' : 'Refundable'); ?></div>
                            <div class="text-2xl font-bold <?php echo e($calculation['vat_payable'] > 0 ? 'text-amber-400' : 'text-emerald-400'); ?>">
                                $<?php echo e(number_format(abs($calculation['vat_payable']), 2)); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="mt-6 bg-black/20 rounded-xl ring-1 ring-white/10 p-5">
            <label class="block text-xs text-slate-400 mb-1">Notes / Comments</label>
            <textarea name="notes" rows="2" 
                      class="w-full px-3 py-2 rounded-lg bg-black/30 text-white border border-white/10 focus:border-indigo-500 outline-none"
                      placeholder="Any additional notes about this return..."></textarea>
        </div>

        
        <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-white/10">
            <a href="<?php echo e(route('modules.tax.vat.index')); ?>" 
               class="px-4 py-2 rounded-lg bg-white/5 hover:bg-white/10 ring-1 ring-white/10 text-sm transition-colors">
                Cancel
            </a>
            <button type="submit" name="action" value="save" 
                    class="px-4 py-2 rounded-lg bg-white/10 hover:bg-white/15 ring-1 ring-white/10 text-sm transition-colors">
                💾 Save Draft
            </button>
            <button type="submit" name="action" value="submit" 
                    class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Submit Return
            </button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.erp', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Desktop\Victor\tax_compliance\resources\views/modules/tax/vat/create.blade.php ENDPATH**/ ?>