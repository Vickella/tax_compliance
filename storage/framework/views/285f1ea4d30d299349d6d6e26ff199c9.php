

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    
    <div>
        <h1 class="text-2xl font-semibold text-white">Dashboard</h1>
        <p class="text-sm text-slate-400 mt-1">Quick access to core actions</p>
    </div>

    
    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-white">Shortcuts</h2>
            <span class="text-xs text-slate-400">Common actions</span>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <?php $__currentLoopData = $shortcuts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shortcut): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e($shortcut['route']); ?>" 
                   class="bg-white/5 hover:bg-white/10 border border-white/10 rounded-lg p-4 text-center transition-all hover:border-indigo-500/50">
                    <div class="text-2xl mb-2"><?php echo e($shortcut['icon']); ?></div>
                    <div class="text-sm font-medium text-white"><?php echo e($shortcut['label']); ?></div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <?php $__currentLoopData = $cards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="card">
                <h2 class="text-lg font-semibold text-white mb-4"><?php echo e($card['title']); ?></h2>
                <div class="space-y-2">
                    <?php $__currentLoopData = $card['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="text-sm text-slate-300"><?php echo e($item); ?></div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Desktop\Victor\tax_compliance\resources\views/dashboard/home.blade.php ENDPATH**/ ?>