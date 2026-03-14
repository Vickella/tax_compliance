
<?php $__env->startSection('page_title','New Receipt'); ?>

<?php $__env->startSection('content'); ?>
<div class="h-full overflow-auto space-y-4">

    <?php if($errors->any()): ?>
        <div class="p-3 rounded-lg bg-red-500/10 ring-1 ring-red-500/20 text-red-200 text-sm">
            <?php echo e($errors->first()); ?>

        </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('modules.sales.receipts.store')); ?>" class="space-y-4">
        <?php echo csrf_field(); ?>

        <div class="rounded-xl bg-white/5 ring-1 ring-white/10 p-4 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="text-xs text-slate-300">Customer</label>
                    <select name="customer_id" class="w-full mt-1 px-3 py-2 rounded-lg bg-black/20 ring-1 ring-white/10" required>
                        <option value="">Select...</option>
                        <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($c->id); ?>" <?php if(old('customer_id') == $c->id): echo 'selected'; endif; ?>>
                                <?php echo e($c->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div>
                    <label class="text-xs text-slate-300">Bank / Cash Account</label>
                    <select name="bank_account_id" class="w-full mt-1 px-3 py-2 rounded-lg bg-black/20 ring-1 ring-white/10" required>
                        <option value="">Select...</option>
                        <?php $__currentLoopData = $banks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($b->id); ?>" <?php if(old('bank_account_id') == $b->id): echo 'selected'; endif; ?>>
                                <?php echo e($b->name); ?> (<?php echo e($b->currency); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <div class="text-[11px] text-slate-400 mt-1">This posts DR to the linked GL account.</div>
                </div>

                <div>
                    <label class="text-xs text-slate-300">Posting Date</label>
                    <input type="date" name="posting_date"
                           value="<?php echo e(old('posting_date', now()->toDateString())); ?>"
                           class="w-full mt-1 px-3 py-2 rounded-lg bg-black/20 ring-1 ring-white/10" required>
                </div>

                <div>
                    <label class="text-xs text-slate-300">Currency</label>
                    <input name="currency" value="<?php echo e(old('currency', company_currency())); ?>"
                           class="w-full mt-1 px-3 py-2 rounded-lg bg-black/20 ring-1 ring-white/10" required>
                </div>

                <div>
                    <label class="text-xs text-slate-300">Exchange Rate</label>
                    <input name="exchange_rate" value="<?php echo e(old('exchange_rate', 1)); ?>"
                           class="w-full mt-1 px-3 py-2 rounded-lg bg-black/20 ring-1 ring-white/10">
                </div>

                <div>
                    <label class="text-xs text-slate-300">Amount</label>
                    <input name="amount" value="<?php echo e(old('amount')); ?>"
                           class="w-full mt-1 px-3 py-2 rounded-lg bg-black/20 ring-1 ring-white/10" required>
                </div>

                <div class="md:col-span-3">
                    <label class="text-xs text-slate-300">Reference / Note</label>
                    <input name="reference" value="<?php echo e(old('reference')); ?>"
                           class="w-full mt-1 px-3 py-2 rounded-lg bg-black/20 ring-1 ring-white/10"
                           placeholder="e.g. EcoCash ref, bank ref, POS ref">
                </div>
            </div>
        </div>

        
        <div class="rounded-xl bg-white/5 ring-1 ring-white/10 p-4 space-y-3">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-semibold">Allocate to Sales Invoices (Optional)</div>
                    <div class="text-xs text-slate-400">If you allocate, AR aging will reduce per invoice.</div>
                </div>
            </div>

            <div class="rounded-xl ring-1 ring-white/10 overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-white/5">
                        <tr>
                            <th class="p-3 text-left">Invoice</th>
                            <th class="p-3 text-left">Date</th>
                            <th class="p-3 text-left">Total</th>
                            <th class="p-3 text-left">Allocate Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        <?php
                            // Build old allocations keyed by invoice_id for sticky form
                            $oldAllocs = collect(old('allocations', []))->keyBy('invoice_id');
                        ?>

                        <?php $__empty_1 = true; $__currentLoopData = $openInvoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php
                                    $oldAmt = $oldAllocs->get($inv->id)['amount'] ?? '';
                                ?>
                                <tr class="hover:bg-white/5">
                                    <td class="p-3">
                                        <div class="font-medium"><?php echo e($inv->invoice_no); ?></div>
                                        <div class="text-xs text-slate-400">Customer ID: <?php echo e($inv->customer_id); ?></div>
                                    </td>
                                    <td class="p-3"><?php echo e($inv->posting_date); ?></td>
                                    <td class="p-3"><?php echo e($inv->currency); ?> <?php echo e(number_format((float)$inv->total,2)); ?></td>
                                    <td class="p-3">
                                        <input type="hidden" name="allocations[<?php echo e($loop->index); ?>][invoice_id]" value="<?php echo e($inv->id); ?>">
                                        <input name="allocations[<?php echo e($loop->index); ?>][amount]"
                                            value="<?php echo e($oldAmt); ?>"
                                            class="w-full px-3 py-2 rounded-lg bg-black/20 ring-1 ring-white/10"
                                            placeholder="0.00">
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td class="p-4 text-slate-300" colspan="4">No submitted invoices found to allocate.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="text-[11px] text-slate-400">
                Only enter amounts for the invoices you want to allocate. Leave others blank.
            </div>
        </div>

        <button class="px-4 py-2 rounded-lg bg-indigo-500/20 hover:bg-indigo-500/30 ring-1 ring-indigo-400/30 text-sm">
            Save Receipt (Posts to GL)
        </button>
    </form>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Desktop\Victor\tax_compliance\resources\views/modules/sales/receipts/create.blade.php ENDPATH**/ ?>