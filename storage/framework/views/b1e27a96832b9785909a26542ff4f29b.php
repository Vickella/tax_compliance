

<?php $__env->startSection('page_title','Journal Entries'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex items-center justify-between mb-4">
    <div class="text-sm text-slate-300">Drafts, posted entries, reversals</div>
    <a href="<?php echo e(route('modules.accounting.journals.create')); ?>"
       class="px-3 py-2 rounded-lg bg-white/10 hover:bg-white/15 ring-1 ring-white/10 text-sm">
        + New Journal
    </a>
</div>

<div class="rounded-xl ring-1 ring-white/10 overflow-hidden bg-black/10">
    <table class="w-full text-sm">
        <thead class="bg-white/5">
        <tr>
            <th class="p-3 text-left">Entry No</th>
            <th class="p-3 text-left">Posting Date</th>
            <th class="p-3 text-left">Memo</th>
            <th class="p-3 text-left">Status</th>
            <th class="p-3"></th>
        </tr>
        </thead>
        <tbody class="divide-y divide-white/10">
        <?php $__currentLoopData = $journals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td class="p-3 font-mono"><?php echo e($j->entry_no); ?></td>
                <td class="p-3"><?php echo e($j->posting_date?->format('Y-m-d')); ?></td>
                <td class="p-3"><?php echo e($j->memo); ?></td>
                <td class="p-3"><?php echo e($j->status); ?></td>
                <td class="p-3 text-right">
                    <a class="text-indigo-300 hover:underline" href="<?php echo e(route('modules.accounting.journals.show',$j)); ?>">Open</a>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>

<div class="mt-4"><?php echo e($journals->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Desktop\Victor\tax_compliance\resources\views/modules/accounting/journals/index.blade.php ENDPATH**/ ?>