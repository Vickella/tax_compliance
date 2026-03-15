<?php
    // Safe defaults: create page won't have $invoice
    $invoice ??= null;
    $editing = filled($invoice?->id);

    // Currency: if invoice not set, use company base currency helper
    $currency = old('currency', $invoice?->currency ?? company_currency());

    // Lines:
    // 1) prefer old input (validation error)
    // 2) else edit mode -> invoice lines
    // 3) else create -> 1 default row
    $oldLines = old('lines');

    $lines = $oldLines
        ? collect($oldLines)
        : ($invoice?->lines ?? collect([
            ['item_id'=>'','warehouse_id'=>'','qty'=>1,'rate'=>0,'description'=>'']
        ]));

    $nextIndex = $lines->count();
?>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    
    <div>
        <label class="text-xs text-slate-300">Supplier</label>
        <select name="supplier_id"
                class="w-full mt-1 px-3 py-2 rounded-lg bg-black/30 text-slate-100 ring-1 ring-white/10 focus:ring-2 focus:ring-indigo-400/40 outline-none"
                required>
            <option value="">Select...</option>
            <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($supplier->id); ?>"
                    <?php if((string)old('supplier_id', $invoice?->supplier_id) === (string)$supplier->id): echo 'selected'; endif; ?>>
                    <?php echo e($supplier->name); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>

    
    <div>
        <label class="text-xs text-slate-300">Posting Date</label>
        <input type="date"
               name="posting_date"
               value="<?php echo e(old('posting_date', optional($invoice?->posting_date)->format('Y-m-d') ?? now()->toDateString())); ?>"
               class="w-full mt-1 px-3 py-2 rounded-lg bg-black/30 text-slate-100 ring-1 ring-white/10 focus:ring-2 focus:ring-indigo-400/40 outline-none"
               required>
    </div>

    
    <div>
        <label class="text-xs text-slate-300">Currency</label>
        <input type="text"
               name="currency"
               value="<?php echo e($currency); ?>"
               class="w-full mt-1 px-3 py-2 rounded-lg bg-black/30 text-slate-100 ring-1 ring-white/10 focus:ring-2 focus:ring-indigo-400/40 outline-none"
               readonly>
        <div class="text-[11px] text-slate-400 mt-1">Company base currency (read-only)</div>
    </div>

    
    <div>
        <label class="text-xs text-slate-300">Exchange Rate</label>
        <input type="number" step="0.00000001" min="0"
               name="exchange_rate"
               value="<?php echo e(old('exchange_rate', $invoice?->exchange_rate ?? 1)); ?>"
               class="w-full mt-1 px-3 py-2 rounded-lg bg-black/30 text-slate-100 ring-1 ring-white/10 focus:ring-2 focus:ring-indigo-400/40 outline-none"
               required>
    </div>
</div>

<div class="mt-6 flex items-center justify-between">
    <div class="text-sm font-semibold text-slate-100">Invoice Lines</div>

    <button type="button" id="btnAddLine"
            class="rounded-lg px-3 py-2 text-xs bg-white/10 hover:bg-white/15 ring-1 ring-white/10">
        + Add Line
    </button>
</div>

<div class="mt-2 rounded-xl ring-1 ring-white/10 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-white/5">
        <tr>
            <th class="p-3 text-left">Item</th>
            <th class="p-3 text-left">Warehouse</th>
            <th class="p-3 text-right w-28">Qty</th>
            <th class="p-3 text-right w-36">Rate</th>
            <th class="p-3 text-left">Description</th>
            <th class="p-3 text-right w-24"></th>
        </tr>
        </thead>

        <tbody id="invoiceLinesTbody" class="divide-y divide-white/10">
        <?php $__currentLoopData = $lines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $itemId = is_array($l) ? ($l['item_id'] ?? '') : ($l->item_id ?? '');
                $whId   = is_array($l) ? ($l['warehouse_id'] ?? '') : ($l->warehouse_id ?? '');
                $qty    = is_array($l) ? ($l['qty'] ?? 1) : ($l->qty ?? 1);
                $rate   = is_array($l) ? ($l['rate'] ?? 0) : ($l->rate ?? 0);
                $desc   = is_array($l) ? ($l['description'] ?? '') : ($l->description ?? '');
            ?>

            <tr class="line-row" data-index="<?php echo e($i); ?>">
                <td class="p-2">
                    <select name="lines[<?php echo e($i); ?>][item_id]"
                            class="w-full px-2 py-2 rounded bg-black/30 text-slate-100 ring-1 ring-white/10 focus:ring-2 focus:ring-indigo-400/40 outline-none"
                            required>
                        <option value="">Select...</option>
                        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($it->id); ?>" <?php if((string)$itemId === (string)$it->id): echo 'selected'; endif; ?>>
                                <?php echo e($it->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </td>

                <td class="p-2">
                    <select name="lines[<?php echo e($i); ?>][warehouse_id]"
                            class="w-full px-2 py-2 rounded bg-black/30 text-slate-100 ring-1 ring-white/10 focus:ring-2 focus:ring-indigo-400/40 outline-none">
                        <option value="">—</option>
                        <?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($w->id); ?>" <?php if((string)$whId === (string)$w->id): echo 'selected'; endif; ?>>
                                <?php echo e($w->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </td>

                <td class="p-2">
                    <input type="number" step="0.0001" min="0"
                           name="lines[<?php echo e($i); ?>][qty]" value="<?php echo e($qty); ?>"
                           class="w-full text-right px-2 py-2 rounded bg-black/30 text-slate-100 ring-1 ring-white/10 focus:ring-2 focus:ring-indigo-400/40 outline-none"
                           required>
                </td>

                <td class="p-2">
                    <input type="number" step="0.000001" min="0"
                           name="lines[<?php echo e($i); ?>][rate]" value="<?php echo e($rate); ?>"
                           class="w-full text-right px-2 py-2 rounded bg-black/30 text-slate-100 ring-1 ring-white/10 focus:ring-2 focus:ring-indigo-400/40 outline-none"
                           required>
                </td>

                <td class="p-2">
                    <input name="lines[<?php echo e($i); ?>][description]" value="<?php echo e($desc); ?>"
                           class="w-full px-2 py-2 rounded bg-black/30 text-slate-100 ring-1 ring-white/10 focus:ring-2 focus:ring-indigo-400/40 outline-none">
                </td>

                <td class="p-2 text-right">
                    <button type="button"
                            class="btnRemoveLine rounded-lg px-2 py-2 text-xs bg-red-500/15 hover:bg-red-500/25 ring-1 ring-red-400/20">
                        Remove
                    </button>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>

<template id="purchaseLineTemplate">
    <tr class="line-row" data-index="__INDEX__">
        <td class="p-2">
            <select name="lines[__INDEX__][item_id]"
                    class="w-full px-2 py-2 rounded bg-black/30 text-slate-100 ring-1 ring-white/10 focus:ring-2 focus:ring-indigo-400/40 outline-none"
                    required>
                <option value="">Select...</option>
                <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($it->id); ?>"><?php echo e($it->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </td>

        <td class="p-2">
            <select name="lines[__INDEX__][warehouse_id]"
                    class="w-full px-2 py-2 rounded bg-black/30 text-slate-100 ring-1 ring-white/10 focus:ring-2 focus:ring-indigo-400/40 outline-none">
                <option value="">—</option>
                <?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($w->id); ?>"><?php echo e($w->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </td>

        <td class="p-2">
            <input type="number" step="0.0001" min="0"
                   name="lines[__INDEX__][qty]" value="1"
                   class="w-full text-right px-2 py-2 rounded bg-black/30 text-slate-100 ring-1 ring-white/10 focus:ring-2 focus:ring-indigo-400/40 outline-none"
                   required>
        </td>

        <td class="p-2">
            <input type="number" step="0.000001" min="0"
                   name="lines[__INDEX__][rate]" value="0"
                   class="w-full text-right px-2 py-2 rounded bg-black/30 text-slate-100 ring-1 ring-white/10 focus:ring-2 focus:ring-indigo-400/40 outline-none"
                   required>
        </td>

        <td class="p-2">
            <input name="lines[__INDEX__][description]" value=""
                   class="w-full px-2 py-2 rounded bg-black/30 text-slate-100 ring-1 ring-white/10 focus:ring-2 focus:ring-indigo-400/40 outline-none">
        </td>

        <td class="p-2 text-right">
            <button type="button"
                    class="btnRemoveLine rounded-lg px-2 py-2 text-xs bg-red-500/15 hover:bg-red-500/25 ring-1 ring-red-400/20">
                Remove
            </button>
        </td>
    </tr>
</template>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const tbody = document.getElementById('invoiceLinesTbody');
    const addBtn = document.getElementById('btnAddLine');
    const template = document.getElementById('purchaseLineTemplate');

    let nextIndex = <?php echo e((int) $nextIndex); ?>;

    function bindRemove(btn) {
        btn.addEventListener('click', () => {
            const row = btn.closest('tr');
            if (!row) return;
            row.remove();
        });
    }

    // bind existing remove buttons
    document.querySelectorAll('.btnRemoveLine').forEach(bindRemove);

    addBtn?.addEventListener('click', () => {
        const html = template.innerHTML.replaceAll('__INDEX__', String(nextIndex));
        const tmp = document.createElement('tbody');
        tmp.innerHTML = html.trim();

        const row = tmp.firstElementChild;
        tbody.appendChild(row);

        const rm = row.querySelector('.btnRemoveLine');
        if (rm) bindRemove(rm);

        nextIndex++;
    });
});
</script>
<?php /**PATH C:\Users\USER\Desktop\Victor\tax_compliance\resources\views/modules/purchases/invoices/_form.blade.php ENDPATH**/ ?>