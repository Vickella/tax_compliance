

<?php $__env->startSection('content'); ?>
<div class="max-w-6xl mx-auto p-4 md:p-6 space-y-6">

    <div class="flex items-start justify-between gap-3">
        <div>
            <h1 class="text-xl md:text-2xl font-semibold text-slate-100">Journal <?php echo e($journal->entry_no); ?></h1>
            <div class="text-sm text-slate-400">Posting date: <?php echo e(\Illuminate\Support\Carbon::parse($journal->posting_date)->format('Y-m-d')); ?></div>
            <div class="text-sm text-slate-300 mt-1"><?php echo e($journal->memo); ?></div>
        </div>

        <div class="flex items-center gap-2">
            <a href="<?php echo e(route('modules.accounting.journals.index')); ?>"
               class="px-4 py-2 rounded-lg bg-white/10 hover:bg-white/15 text-slate-200 text-sm">Back</a>

            <?php if(($journal->status ?? 'DRAFT') === 'DRAFT'): ?>
                <form method="POST" action="<?php echo e(route('modules.accounting.journals.post', $journal)); ?>">
                    <?php echo csrf_field(); ?>
                    <button class="px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-sm">
                        Post
                    </button>
                </form>

                <a href="<?php echo e(route('modules.accounting.journals.edit', $journal)); ?>"
                   class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white text-sm">
                    Edit
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="rounded-xl ring-1 ring-white/10 bg-black/20 p-4">
        <div class="flex items-center justify-between">
            <div class="text-sm text-slate-300">
                Status:
                <span class="text-slate-100 font-medium"><?php echo e(strtoupper($journal->status ?? 'DRAFT')); ?></span>
            </div>
        </div>
    </div>

    <div class="rounded-xl ring-1 ring-white/10 overflow-hidden bg-black/20">
        <table class="w-full text-sm">
            <thead class="bg-white/5">
                <tr>
                    <th class="p-3 text-left text-slate-300">Account</th>
                    <th class="p-3 text-left text-slate-300">Description</th>
                    <th class="p-3 text-right text-slate-300">Debit</th>
                    <th class="p-3 text-right text-slate-300">Credit</th>
                    <th class="p-3 text-left text-slate-300">Party</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/10">
                <?php ($td=0); ?> <?php ($tc=0); ?>
                <?php $__currentLoopData = $journal->lines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php ($td += (float)$l->debit); ?> <?php ($tc += (float)$l->credit); ?>
                    <tr class="hover:bg-white/5">
                        <td class="p-3 text-slate-200">
                            <?php echo e($l->account->code ?? ''); ?> — <?php echo e($l->account->name ?? 'Account'); ?>

                        </td>
                        <td class="p-3 text-slate-200"><?php echo e($l->description); ?></td>
                        <td class="p-3 text-right text-slate-100"><?php echo e(number_format((float)$l->debit, 2)); ?></td>
                        <td class="p-3 text-right text-slate-100"><?php echo e(number_format((float)$l->credit, 2)); ?></td>
                        <td class="p-3 text-slate-300 text-xs">
                            <?php echo e($l->party_type ?? 'NONE'); ?> <?php if($l->party_id): ?> #<?php echo e($l->party_id); ?> <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
            <tfoot class="bg-white/5">
                <tr>
                    <td class="p-3 text-slate-300 font-medium" colspan="2">Totals</td>
                    <td class="p-3 text-right text-slate-100 font-medium"><?php echo e(number_format((float)$td, 2)); ?></td>
                    <td class="p-3 text-right text-slate-100 font-medium"><?php echo e(number_format((float)$tc, 2)); ?></td>
                    <td class="p-3"></td>
                </tr>
            </tfoot>
        </table>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Desktop\Victor\tax_compliance\resources\views/modules/accounting/journals/show.blade.php ENDPATH**/ ?>