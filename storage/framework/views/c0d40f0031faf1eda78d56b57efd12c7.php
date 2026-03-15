

<?php $__env->startSection('page_title','General Ledger'); ?>

<?php $__env->startSection('content'); ?>
<form class="flex flex-wrap items-end gap-3 mb-4">
    <div>
        <label class="text-xs text-slate-300">From</label>
        <input type="date" name="from" value="<?php echo e($from); ?>"
               class="mt-1 px-3 py-2 rounded-lg bg-black/30 text-slate-100 ring-1 ring-white/10 focus:ring-2 focus:ring-indigo-400/40 outline-none">
    </div>

    <div>
        <label class="text-xs text-slate-300">To</label>
        <input type="date" name="to" value="<?php echo e($to); ?>"
               class="mt-1 px-3 py-2 rounded-lg bg-black/30 text-slate-100 ring-1 ring-white/10 focus:ring-2 focus:ring-indigo-400/40 outline-none">
    </div>

    <div class="min-w-[260px]">
        <label class="text-xs text-slate-300">Account</label>
        <select name="account_id"
                class="mt-1 w-full px-3 py-2 rounded-lg bg-black/30 text-slate-100 ring-1 ring-white/10 focus:ring-2 focus:ring-indigo-400/40 outline-none">
            <option value="">All accounts</option>
            <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($a->id); ?>" <?php if((string)$accountId === (string)$a->id): echo 'selected'; endif; ?>>
                    <?php echo e($a->code); ?> - <?php echo e($a->name); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>

    <button class="px-3 py-2 rounded-lg bg-white/10 hover:bg-white/15 ring-1 ring-white/10 text-sm">
        Run
    </button>
</form>

<div class="rounded-xl ring-1 ring-white/10 overflow-hidden bg-black/10">
    <table class="w-full text-sm">
        <thead class="bg-white/5">
        <tr>
            <th class="p-3 text-left">Date</th>
            <th class="p-3 text-left">Account</th>
            <th class="p-3 text-right">Debit</th>
            <th class="p-3 text-right">Credit</th>
            <th class="p-3 text-left">Currency</th>
            <th class="p-3 text-left">Journal</th>
            <th class="p-3 text-left">Party</th>
        </tr>
        </thead>
        <tbody class="divide-y divide-white/10">
        <?php $running = 0; ?>

        <?php $__empty_1 = true; $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $running += ((float)$r['debit'] - (float)$r['credit']);
                $party = $r['party_type'] && $r['party_type'] !== 'NONE'
                    ? ($r['party_type'].' #'.$r['party_id'])
                    : '—';
            ?>
            <tr>
                <td class="p-3"><?php echo e(\Illuminate\Support\Carbon::parse($r['posting_date'])->format('Y-m-d')); ?></td>
                <td class="p-3">
                    <div class="font-mono text-xs text-slate-400"><?php echo e($r['code']); ?></div>
                    <div><?php echo e($r['name']); ?></div>
                </td>
                <td class="p-3 text-right"><?php echo e(number_format((float)$r['debit'],2)); ?></td>
                <td class="p-3 text-right"><?php echo e(number_format((float)$r['credit'],2)); ?></td>
                <td class="p-3"><?php echo e($r['currency']); ?></td>
                <td class="p-3">
                    <?php if($r['journal_entry_id']): ?>
                        <a class="text-indigo-300 hover:underline"
                           href="<?php echo e(route('modules.accounting.journals.show', $r['journal_entry_id'])); ?>">
                            JE #<?php echo e($r['journal_entry_id']); ?>

                        </a>
                    <?php else: ?>
                        —
                    <?php endif; ?>
                </td>
                <td class="p-3"><?php echo e($party); ?></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td class="p-3 text-slate-400" colspan="7">No ledger entries found for this range.</td>
            </tr>
        <?php endif; ?>
        </tbody>

        <?php if(count($rows)): ?>
        <tfoot class="bg-white/5">
        <tr>
            <th class="p-3 text-left" colspan="2">Running Net (Dr - Cr)</th>
            <th class="p-3 text-right" colspan="5"><?php echo e(number_format((float)$running,2)); ?></th>
        </tr>
        </tfoot>
        <?php endif; ?>
    </table>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Desktop\Victor\tax_compliance\resources\views/modules/accounting/reports/general_ledger.blade.php ENDPATH**/ ?>