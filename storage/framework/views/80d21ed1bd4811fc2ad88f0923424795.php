<?php $__env->startSection('page_title', 'Manage User Access'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto">
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-white">Manage Access: <?php echo e($user->name); ?></h2>
        <p class="text-sm text-slate-400"><?php echo e($user->email); ?></p>
    </div>

    <?php if(session('success')): ?>
        <div class="mb-4 p-3 rounded-lg bg-emerald-600/20 text-emerald-300 ring-1 ring-emerald-600/30"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('admin.users.update', $user)); ?>" class="bg-black/20 rounded-xl ring-1 ring-white/10 p-6">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <label class="flex items-center gap-3 mb-6 text-white">
            <input type="checkbox" name="is_active" value="1" <?php echo e($user->is_active ? 'checked' : ''); ?>>
            Active user
        </label>

        <h3 class="text-white font-semibold mb-3">Assign Roles</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 mb-6">
            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <label class="p-4 rounded-lg bg-black/30 ring-1 ring-white/10 text-white flex items-start gap-3">
                    <input type="checkbox" name="role_ids[]" value="<?php echo e($role->id); ?>" <?php echo e(in_array($role->id, $userRoleIds) ? 'checked' : ''); ?>>
                    <span>
                        <span class="font-semibold"><?php echo e($role->name); ?></span>
                        <span class="block text-xs text-slate-400"><?php echo e($role->permissions->count()); ?> permission(s)</span>
                    </span>
                </label>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="border-t border-white/10 pt-5 mt-5">
            <h3 class="text-white font-semibold mb-3">Permission Reference</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module => $modulePermissions): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="rounded-lg bg-black/30 ring-1 ring-white/10 p-4">
                        <h4 class="text-indigo-300 font-semibold mb-2"><?php echo e(ucfirst($module)); ?></h4>
                        <ul class="text-xs text-slate-300 space-y-1">
                            <?php $__currentLoopData = $modulePermissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><code><?php echo e($permission->code); ?></code> — <?php echo e($permission->description); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-6">
            <a href="<?php echo e(route('admin.users.index')); ?>" class="px-4 py-2 rounded-lg bg-white/5 hover:bg-white/10 text-white">Back</a>
            <button class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white">Save Access</button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Desktop\Instacare\tax_compliance\resources\views/admin/users/edit.blade.php ENDPATH**/ ?>