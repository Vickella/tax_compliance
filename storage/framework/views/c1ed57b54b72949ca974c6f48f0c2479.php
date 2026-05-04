
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium mb-1">Code <span class="text-red-600">*</span></label>
        <input name="code" value="<?php echo e(old('code', $supplier->code ?? '')); ?>" class="w-full border rounded px-3 py-2" required>
        <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-sm text-red-600 mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Name <span class="text-red-600">*</span></label>
        <input name="name" value="<?php echo e(old('name', $supplier->name ?? '')); ?>" class="w-full border rounded px-3 py-2" required>
        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-sm text-red-600 mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">TIN</label>
        <input name="tin" value="<?php echo e(old('tin', $supplier->tin ?? '')); ?>" class="w-full border rounded px-3 py-2">
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">VAT Number</label>
        <input name="vat_number" value="<?php echo e(old('vat_number', $supplier->vat_number ?? '')); ?>" class="w-full border rounded px-3 py-2">
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium mb-1">Bank Details</label>
        <textarea name="bank_details" class="w-full border rounded px-3 py-2" rows="3"><?php echo e(old('bank_details', $supplier->bank_details ?? '')); ?></textarea>
    </div>

    <div class="flex items-center gap-2 mt-2">
        <?php $wht = old('withholding_tax_flag', $supplier->withholding_tax_flag ?? false); ?>
        <input id="withholding_tax_flag" name="withholding_tax_flag" type="checkbox" value="1" class="h-4 w-4" <?php if((bool)$wht): echo 'checked'; endif; ?>>
        <label for="withholding_tax_flag" class="text-sm">Withholding Tax Applies</label>
    </div>

    <div class="flex items-center gap-2 mt-2">
        <?php $active = old('is_active', $supplier->is_active ?? true); ?>
        <input id="is_active" name="is_active" type="checkbox" value="1" class="h-4 w-4" <?php if((bool)$active): echo 'checked'; endif; ?>>
        <label for="is_active" class="text-sm">Active</label>
    </div>
</div>
<?php /**PATH C:\Users\USER\Desktop\Instacare\tax_compliance\resources\views/modules/purchases/suppliers/_form.blade.php ENDPATH**/ ?>