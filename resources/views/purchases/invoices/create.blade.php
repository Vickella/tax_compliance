<x-app-layout>
    <x-erp.page title="Create Purchase Invoice" subtitle="Capture supplier VAT references and input tax documentation.">
        <x-slot name="actions">
            <x-erp.action-link href="{{ route('purchases.invoices.index') }}" variant="muted">Back to Invoices</x-erp.action-link>
            <x-erp.action-button variant="primary" type="submit" form="purchase-invoice-form">Save Draft</x-erp.action-button>
        </x-slot>

        <form id="purchase-invoice-form" method="POST" action="{{ route('purchases.invoices.store') }}" class="space-y-6">
            @csrf
            <x-erp.section>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="text-xs text-white/70">Invoice number</label>
                        <input type="text" name="invoice_no" placeholder="PI-00013" class="mt-2 w-full rounded-2xl bg-white/10 border border-white/10 text-white px-4 py-2.5" required />
                    </div>
                    <div>
                        <label class="text-xs text-white/70">Supplier</label>
                        <select name="supplier_id" class="mt-2 w-full rounded-2xl bg-white/10 border border-white/10 text-white px-4 py-2.5" required>
                            <option value="">Select supplier</option>
                            @foreach($suppliers ?? [] as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-xs text-white/70">Supplier invoice ref</label>
                        <input type="text" name="supplier_invoice_no" placeholder="INV-7789" class="mt-2 w-full rounded-2xl bg-white/10 border border-white/10 text-white px-4 py-2.5" />
                    </div>
                    <div>
                        <label class="text-xs text-white/70">Posting date</label>
                        <input type="date" name="posting_date" class="mt-2 w-full rounded-2xl bg-white/10 border border-white/10 text-white px-3 py-2.5" required />
                    </div>
                    <div>
                        <label class="text-xs text-white/70">Currency</label>
                        <select name="currency" class="mt-2 w-full rounded-2xl bg-white/10 border border-white/10 text-white px-4 py-2.5">
                            @foreach($currencies ?? [] as $currency)
                                <option value="{{ $currency->code }}">{{ $currency->code }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-xs text-white/70">Exchange rate</label>
                        <input type="number" step="0.0001" name="exchange_rate" value="1.0000" class="mt-2 w-full rounded-2xl bg-white/10 border border-white/10 text-white px-4 py-2.5" />
                    </div>
                </div>
            </x-erp.section>

            <x-erp.section>
                <h3 class="text-sm font-semibold text-white">Input tax documentation</h3>
                <div class="mt-4 grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="text-xs text-white/70">Input tax document ref</label>
                        <input type="text" name="input_tax_document_ref" placeholder="ITD-2026-001" class="mt-2 w-full rounded-2xl bg-white/10 border border-white/10 text-white px-4 py-2.5" />
                    </div>
                    <div>
                        <label class="text-xs text-white/70">Bill of entry ref</label>
                        <input type="text" name="bill_of_entry_ref" placeholder="BOE-2026-118" class="mt-2 w-full rounded-2xl bg-white/10 border border-white/10 text-white px-4 py-2.5" />
                    </div>
                </div>
            </x-erp.section>

            <x-erp.section>
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-white">Invoice lines</h3>
                    <span class="text-xs text-white/60">VAT default {{ number_format(($vatRate ?? 0) * 100, 1) }}%</span>
                </div>
                <div class="mt-4 overflow-x-auto">
                    <table class="min-w-full text-sm text-white/80">
                        <thead class="text-xs uppercase text-white/50 border-b border-white/10">
                            <tr>
                                <th class="py-3 text-left">Item</th>
                                <th class="py-3 text-left">Warehouse</th>
                                <th class="py-3 text-right">Qty</th>
                                <th class="py-3 text-right">Rate</th>
                                <th class="py-3 text-right">VAT %</th>
                                <th class="py-3 text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="py-3">
                                    <select name="lines[0][item_id]" class="w-full rounded-2xl bg-white/10 border border-white/10 text-white px-3 py-2.5 js-item-select" required>
                                        <option value="">Select item</option>
                                        @foreach($items ?? [] as $item)
                                            <option value="{{ $item->id }}" data-cost="{{ $item->cost_price }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="py-3">
                                    <select name="lines[0][warehouse_id]" class="w-full rounded-2xl bg-white/10 border border-white/10 text-white px-3 py-2.5">
                                        <option value="">Select warehouse</option>
                                        @foreach($warehouses ?? [] as $warehouse)
                                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="py-3">
                                    <input type="number" name="lines[0][qty]" step="0.0001" value="1" class="w-full rounded-2xl bg-white/10 border border-white/10 text-white px-3 py-2.5 js-qty" />
                                </td>
                                <td class="py-3">
                                    <input type="number" name="lines[0][rate]" step="0.01" class="w-full rounded-2xl bg-white/10 border border-white/10 text-white px-3 py-2.5 js-rate" />
                                </td>
                                <td class="py-3">
                                    <input type="number" name="lines[0][vat_rate]" step="0.0001" value="{{ $vatRate ?? 0 }}" class="w-full rounded-2xl bg-white/10 border border-white/10 text-white px-3 py-2.5" />
                                </td>
                                <td class="py-3 text-right">
                                    <span class="text-white/70 js-line-total">0.00</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </x-erp.section>

            <x-erp.section>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <div class="lg:col-span-2">
                        <label class="text-xs text-white/70">Remarks</label>
                        <textarea rows="4" name="remarks" class="mt-2 w-full rounded-2xl bg-white/10 border border-white/10 text-white px-4 py-2.5" placeholder="Internal notes"></textarea>
                    </div>
                    <div class="rounded-2xl bg-black/10 border border-white/10 p-5">
                        <p class="text-xs text-white/70">Subtotal</p>
                        <p class="text-lg font-semibold text-white js-subtotal">ZIG 0.00</p>
                        <p class="text-xs text-white/70 mt-3">VAT</p>
                        <p class="text-lg font-semibold text-white js-vat">ZIG 0.00</p>
                        <p class="text-xs text-white/70 mt-3">Total</p>
                        <p class="text-lg font-semibold text-white js-total">ZIG 0.00</p>
                    </div>
                </div>
            </x-erp.section>

            <div class="flex justify-end gap-3">
                <x-erp.action-button variant="muted" type="reset">Reset</x-erp.action-button>
                <x-erp.action-button variant="primary" type="submit">Save Draft</x-erp.action-button>
            </div>
        </form>
    </x-erp.page>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const itemSelect = document.querySelector('.js-item-select');
            const rateInput = document.querySelector('.js-rate');
            const qtyInput = document.querySelector('.js-qty');
            const totalLabel = document.querySelector('.js-line-total');
            const subtotalLabel = document.querySelector('.js-subtotal');
            const vatLabel = document.querySelector('.js-vat');
            const totalSummary = document.querySelector('.js-total');
            const vatRateInput = document.querySelector('input[name=\"lines[0][vat_rate]\"]');

            const recalc = () => {
                const qty = parseFloat(qtyInput.value || 0);
                const rate = parseFloat(rateInput.value || 0);
                const vatRate = parseFloat(vatRateInput?.value || 0);
                const amount = qty * rate;
                const vat = amount * vatRate;
                const total = amount + vat;
                totalLabel.textContent = amount.toFixed(2);
                subtotalLabel.textContent = `ZIG ${amount.toFixed(2)}`;
                vatLabel.textContent = `ZIG ${vat.toFixed(2)}`;
                totalSummary.textContent = `ZIG ${total.toFixed(2)}`;
            };

            itemSelect?.addEventListener('change', () => {
                const option = itemSelect.selectedOptions[0];
                if (option && option.dataset.cost) {
                    rateInput.value = option.dataset.cost;
                    recalc();
                }
            });

            [rateInput, qtyInput, vatRateInput].forEach((input) => {
                input?.addEventListener('input', recalc);
            });
        });
    </script>
</x-app-layout>
