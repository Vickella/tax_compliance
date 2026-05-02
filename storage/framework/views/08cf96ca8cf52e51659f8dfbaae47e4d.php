

<?php $__env->startSection('content'); ?>
<div class="h-[calc(100vh-4rem)] overflow-hidden px-6 py-5">
  <div class="text-white">
    <div class="text-lg font-semibold capitalize"><?php echo e(str_replace('-', ' ', $module)); ?></div>
    <div class="text-xs text-white/70">Coming next: <?php echo e($section ?? 'module'); ?> <?php echo e($page ? ' / '.$page : ''); ?></div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Desktop\Victor\tax_compliance\resources\views/modules/placeholder.blade.php ENDPATH**/ ?>