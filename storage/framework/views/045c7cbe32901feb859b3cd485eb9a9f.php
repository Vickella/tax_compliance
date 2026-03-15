

<?php $__env->startSection('content'); ?>
<div class="max-w-6xl mx-auto px-4 py-6">

    <div class="flex items-start justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-white">New Journal Entry</h1>
            <p class="text-sm text-white/70">Debits must equal credits. Posting will generate GL entries.</p>
        </div>

        <a href="<?php echo e(route('modules.accounting.journals.index')); ?>"
           class="px-4 py-2 rounded-lg bg-white/10 hover:bg-white/15 text-white text-sm">
            Back
        </a>
    </div>

    <?php if($errors->any()): ?>
        <div class="mb-4 rounded-xl border border-red-500/40 bg-red-500/10 p-4 text-white">
            <div class="font-semibold mb-2">Fix the following:</div>
            <ul class="list-disc pl-6 space-y-1 text-white/90">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($e); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('modules.accounting.journals.store')); ?>" id="jeForm">
        <?php echo csrf_field(); ?>

        
        <div class="rounded-2xl border border-white/10 bg-white/5 p-5 pb-28">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm text-white/80 mb-1">Posting Date</label>
                    <input type="date"
                           name="posting_date"
                           value="<?php echo e(old('posting_date', now()->toDateString())); ?>"
                           class="w-full rounded-xl bg-black/20 border border-white/10 text-white px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                </div>

                <div>
                    <label class="block text-sm text-white/80 mb-1">Memo</label>
                    <input type="text"
                           name="memo"
                           value="<?php echo e(old('memo')); ?>"
                           placeholder="e.g. January payroll accrual"
                           class="w-full rounded-xl bg-black/20 border border-white/10 text-white px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                </div>

                <div>
                    <label class="block text-sm text-white/80 mb-1">Currency <span class="text-red-400">*</span></label>
                    <select name="currency" id="currency"
                            class="w-full rounded-xl bg-black/20 border border-white/10 text-white px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <?php $__currentLoopData = ($currencies ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($c->code); ?>" <?php if(old('currency', $baseCurrency ?? 'USD') === $c->code): echo 'selected'; endif; ?>>
                                <?php echo e($c->code); ?> — <?php echo e($c->name ?? $c->code); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <?php if(empty($currencies)): ?>
                            <option value="<?php echo e(old('currency', $baseCurrency ?? 'USD')); ?>" selected>
                                <?php echo e(old('currency', $baseCurrency ?? 'USD')); ?>

                            </option>
                        <?php endif; ?>
                    </select>
                    <div class="text-xs text-white/60 mt-1">
                        Base: <?php echo e($baseCurrency ?? 'USD'); ?> (auto 1.0000 when same currency)
                    </div>
                </div>

                <div>
                    <label class="block text-sm text-white/80 mb-1">Exchange Rate <span class="text-red-400">*</span></label>
                    <input type="number"
                           step="0.00000001"
                           min="0.00000001"
                           name="exchange_rate"
                           id="exchange_rate"
                           value="<?php echo e(old('exchange_rate', 1)); ?>"
                           class="w-full rounded-xl bg-black/20 border border-white/10 text-white px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                </div>
            </div>

            <div class="rounded-2xl border border-white/10 bg-black/10 overflow-hidden">
                <div class="px-4 py-3 border-b border-white/10 flex items-center justify-between gap-3">
                    <div class="text-white font-semibold">Lines</div>

                    <div class="flex items-center gap-3 text-sm text-white/80">
                        <div>Debit: <span id="debitTotal" class="font-semibold text-white">0.00</span></div>
                        <div>Credit: <span id="creditTotal" class="font-semibold text-white">0.00</span></div>
                        <div class="px-2 py-1 rounded-lg bg-white/10">
                            Diff: <span id="diffTotal" class="font-semibold text-white">0.00</span>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-white/5">
                        <tr class="text-left text-xs text-white/70">
                            <th class="px-4 py-3 w-[320px]">Account</th>
                            <th class="px-4 py-3 w-[260px]">Description</th>
                            <th class="px-4 py-3 w-[160px]">Debit</th>
                            <th class="px-4 py-3 w-[160px]">Credit</th>
                            <th class="px-4 py-3 w-[220px]">Party</th>
                            <th class="px-4 py-3 w-[120px] text-right">Remove</th>
                        </tr>
                        </thead>

                        <tbody id="linesBody" class="divide-y divide-white/10">
                        
                        <?php
                            $oldLines = old('lines', []);
                            if (empty($oldLines)) {
                                $oldLines = [
                                    ['account_id'=>'','description'=>'','debit'=>0,'credit'=>0,'party_type'=>'NONE','party_id'=>''],
                                    ['account_id'=>'','description'=>'','debit'=>0,'credit'=>0,'party_type'=>'NONE','party_id'=>''],
                                ];
                            }
                        ?>

                        <?php $__currentLoopData = $oldLines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="align-top">
                                <td class="px-4 py-3">
                                    <select name="lines[<?php echo e($i); ?>][account_id]"
                                            class="w-full rounded-xl bg-black/20 border border-white/10 text-white px-3 py-2">
                                        <option value="">Select account…</option>
                                        <?php $__currentLoopData = ($accounts ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($acc->id); ?>" <?php if(($l['account_id'] ?? '') == $acc->id): echo 'selected'; endif; ?>>
                                                <?php echo e($acc->code); ?> — <?php echo e($acc->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </td>

                                <td class="px-4 py-3">
                                    <input type="text"
                                           name="lines[<?php echo e($i); ?>][description]"
                                           value="<?php echo e($l['description'] ?? ''); ?>"
                                           class="w-full rounded-xl bg-black/20 border border-white/10 text-white px-3 py-2"
                                           placeholder="Narration" />
                                </td>

                                <td class="px-4 py-3">
                                    <input type="number" step="0.01" min="0"
                                           name="lines[<?php echo e($i); ?>][debit]"
                                           value="<?php echo e($l['debit'] ?? 0); ?>"
                                           class="w-full rounded-xl bg-black/20 border border-white/10 text-white px-3 py-2 debitInput" />
                                </td>

                                <td class="px-4 py-3">
                                    <input type="number" step="0.01" min="0"
                                           name="lines[<?php echo e($i); ?>][credit]"
                                           value="<?php echo e($l['credit'] ?? 0); ?>"
                                           class="w-full rounded-xl bg-black/20 border border-white/10 text-white px-3 py-2 creditInput" />
                                </td>

                                <td class="px-4 py-3">
                                    <select name="lines[<?php echo e($i); ?>][party_type]"
                                            class="w-full rounded-xl bg-black/20 border border-white/10 text-white px-3 py-2 partyType">
                                        <?php $pt = $l['party_type'] ?? 'NONE'; ?>
                                        <option value="NONE" <?php if($pt==='NONE'): echo 'selected'; endif; ?>>None</option>
                                        <option value="CUSTOMER" <?php if($pt==='CUSTOMER'): echo 'selected'; endif; ?>>Customer</option>
                                        <option value="SUPPLIER" <?php if($pt==='SUPPLIER'): echo 'selected'; endif; ?>>Supplier</option>
                                        <option value="EMPLOYEE" <?php if($pt==='EMPLOYEE'): echo 'selected'; endif; ?>>Employee</option>
                                    </select>

                                    <input type="text"
                                           name="lines[<?php echo e($i); ?>][party_id]"
                                           value="<?php echo e($l['party_id'] ?? ''); ?>"
                                           class="mt-2 w-full rounded-xl bg-black/20 border border-white/10 text-white px-3 py-2 partyId"
                                           placeholder="Party ID (optional)" />
                                </td>

                                <td class="px-4 py-3 text-right">
                                    <button type="button"
                                            class="px-3 py-2 rounded-xl bg-red-500/15 hover:bg-red-500/25 text-red-200 text-sm removeRowBtn">
                                        Remove
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <div class="px-4 py-3 border-t border-white/10 flex items-center justify-between">
                    <button type="button"
                            id="addLineBtn"
                            class="px-4 py-2 rounded-xl bg-white/10 hover:bg-white/15 text-white text-sm">
                        + Add Line
                    </button>

                    <div class="text-xs text-white/60">
                        Tip: put values in either Debit OR Credit (not both) per line.
                    </div>
                </div>
            </div>
        </div>

        
        <div class="fixed bottom-0 left-0 right-0 z-50">
            <div class="max-w-6xl mx-auto px-4 pb-4">
                <div class="rounded-2xl border border-white/10 bg-black/50 backdrop-blur px-4 py-3 flex items-center justify-between">
                    <div class="text-sm text-white/70">
                        Ensure totals balance before posting.
                    </div>

                    <div class="flex items-center gap-3">
                        <a href="<?php echo e(route('modules.accounting.journals.index')); ?>"
                           class="px-4 py-2 rounded-xl bg-white/10 hover:bg-white/15 text-white text-sm">
                            Cancel
                        </a>

                        <button type="submit"
                                class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold"
                                id="saveBtn">
                            Save Journal (Draft)
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>

<script>
(function () {
    const linesBody = document.getElementById('linesBody');
    const addLineBtn = document.getElementById('addLineBtn');

    function nextIndex() {
        const rows = linesBody.querySelectorAll('tr');
        return rows.length;
    }

    function recalcTotals() {
        let d = 0, c = 0;

        document.querySelectorAll('.debitInput').forEach(i => d += parseFloat(i.value || 0));
        document.querySelectorAll('.creditInput').forEach(i => c += parseFloat(i.value || 0));

        const diff = (d - c);

        document.getElementById('debitTotal').textContent = d.toFixed(2);
        document.getElementById('creditTotal').textContent = c.toFixed(2);
        document.getElementById('diffTotal').textContent = diff.toFixed(2);

        // Optional: disable save if not balanced
        const saveBtn = document.getElementById('saveBtn');
        if (Math.abs(diff) > 0.009) {
            saveBtn.disabled = true;
            saveBtn.classList.add('opacity-60','cursor-not-allowed');
        } else {
            saveBtn.disabled = false;
            saveBtn.classList.remove('opacity-60','cursor-not-allowed');
        }
    }

    function bindRow(row) {
        const removeBtn = row.querySelector('.removeRowBtn');
        removeBtn.addEventListener('click', () => {
            // keep at least 2 lines
            const rows = linesBody.querySelectorAll('tr');
            if (rows.length <= 2) return;

            row.remove();
            normalizeIndexes();
            recalcTotals();
        });

        row.querySelectorAll('input').forEach(inp => {
            inp.addEventListener('input', recalcTotals);
        });
    }

    function normalizeIndexes() {
        const rows = linesBody.querySelectorAll('tr');
        rows.forEach((row, idx) => {
            row.querySelectorAll('select, input').forEach(el => {
                if (!el.name) return;
                el.name = el.name.replace(/lines\[\d+\]/, `lines[${idx}]`);
            });
        });
    }

    addLineBtn.addEventListener('click', () => {
        const idx = nextIndex();

        const tr = document.createElement('tr');
        tr.className = 'align-top';
        tr.innerHTML = `
            <td class="px-4 py-3">
                <select name="lines[${idx}][account_id]" class="w-full rounded-xl bg-black/20 border border-white/10 text-white px-3 py-2">
                    <option value="">Select account…</option>
                    <?php $__currentLoopData = ($accounts ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($acc->id); ?>"><?php echo e($acc->code); ?> — <?php echo e($acc->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </td>
            <td class="px-4 py-3">
                <input type="text" name="lines[${idx}][description]" value="" class="w-full rounded-xl bg-black/20 border border-white/10 text-white px-3 py-2" placeholder="Narration" />
            </td>
            <td class="px-4 py-3">
                <input type="number" step="0.01" min="0" name="lines[${idx}][debit]" value="0" class="w-full rounded-xl bg-black/20 border border-white/10 text-white px-3 py-2 debitInput" />
            </td>
            <td class="px-4 py-3">
                <input type="number" step="0.01" min="0" name="lines[${idx}][credit]" value="0" class="w-full rounded-xl bg-black/20 border border-white/10 text-white px-3 py-2 creditInput" />
            </td>
            <td class="px-4 py-3">
                <select name="lines[${idx}][party_type]" class="w-full rounded-xl bg-black/20 border border-white/10 text-white px-3 py-2 partyType">
                    <option value="NONE" selected>None</option>
                    <option value="CUSTOMER">Customer</option>
                    <option value="SUPPLIER">Supplier</option>
                    <option value="EMPLOYEE">Employee</option>
                </select>
                <input type="text" name="lines[${idx}][party_id]" value="" class="mt-2 w-full rounded-xl bg-black/20 border border-white/10 text-white px-3 py-2 partyId" placeholder="Party ID (optional)" />
            </td>
            <td class="px-4 py-3 text-right">
                <button type="button" class="px-3 py-2 rounded-xl bg-red-500/15 hover:bg-red-500/25 text-red-200 text-sm removeRowBtn">Remove</button>
            </td>
        `;

        linesBody.appendChild(tr);
        bindRow(tr);
        recalcTotals();

        // smooth scroll into view so user sees the new row
        tr.scrollIntoView({ behavior: 'smooth', block: 'center' });
    });

    // bind existing rows
    linesBody.querySelectorAll('tr').forEach(bindRow);

    // initial totals
    recalcTotals();
})();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Desktop\Victor\tax_compliance\resources\views/modules/accounting/journals/create.blade.php ENDPATH**/ ?>