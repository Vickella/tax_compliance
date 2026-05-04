<?php $__env->startSection('page_title', 'User Access'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-semibold text-white">User Access</h2>
            <p class="text-sm text-slate-400">Administrator area for assigning roles and permissions.</p>
        </div>
    </div>

    <div class="bg-black/20 rounded-xl ring-1 ring-white/10 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-white/5 text-slate-300">
                <tr>
                    <th class="px-4 py-3 text-left">User</th>
                    <th class="px-4 py-3 text-left">Email</th>
                    <th class="px-4 py-3 text-left">Roles</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/10">
                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td class="px-4 py-3 text-white"><?php echo e($user->name); ?></td>
                    <td class="px-4 py-3 text-slate-300"><?php echo e($user->email); ?></td>
                    <td class="px-4 py-3 text-slate-300"><?php echo e($user->roles->pluck('name')->join(', ') ?: 'No role'); ?></td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded text-xs <?php echo e($user->is_active ? 'bg-emerald-600/20 text-emerald-300' : 'bg-red-600/20 text-red-300'); ?>">
                            <?php echo e($user->is_active ? 'Active' : 'Inactive'); ?>

                        </span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <a href="<?php echo e(route('admin.users.edit', $user)); ?>" class="px-3 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white">Manage</a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4"><?php echo e($users->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Desktop\Instacare\tax_compliance\resources\views/admin/users/index.blade.php ENDPATH**/ ?>