<x-app-layout>
    <x-erp.page title="Create Purchase Invoice" subtitle="Capture supplier VAT references and input tax documentation.">
        <x-slot name="actions">
            <x-erp.action-button variant="muted">Save Draft</x-erp.action-button>
            <x-erp.action-button variant="primary">Submit Invoice</x-erp.action-button>
            <x-erp.action-button variant="danger">Cancel</x-erp.action-button>
        </x-slot>

        <x-erp.section>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <div>
                    <label class="text-xs text-white/70">Invoice number</label>
                    <input type="text" placeholder="PI-00013" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                </div>
                <div>
                    <label class="text-xs text-white/70">Supplier</label>
                    <input type="text" placeholder="Select supplier" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                </div>
                <div>
                    <label class="text-xs text-white/70">Supplier invoice ref</label>
                    <input type="text" placeholder="INV-7789" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                </div>
                <div>
                    <label class="text-xs text-white/70">Posting date</label>
                    <input type="date" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                </div>
                <div>
                    <label class="text-xs text-white/70">Currency</label>
                    <select class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white">
                        <option>ZIG</option>
                        <option>USD</option>
                        <option>ZAR</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs text-white/70">Exchange rate</label>
                    <input type="number" step="0.0001" placeholder="1.0000" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                </div>
            </div>
        </x-erp.section>

        <x-erp.section>
            <h3 class="text-sm font-semibold text-white">Input tax documentation</h3>
            <div class="mt-4 grid grid-cols-1 lg:grid-cols-3 gap-4">
                <div>
                    <label class="text-xs text-white/70">Supplier VAT number</label>
                    <input type="text" placeholder="263-123456-3" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                </div>
                <div>
                    <label class="text-xs text-white/70">Supplier TIN</label>
                    <input type="text" placeholder="TIN-456-778" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                </div>
                <div>
                    <label class="text-xs text-white/70">Input tax document ref</label>
                    <input type="text" placeholder="ITD-2026-001" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                </div>
                <div>
                    <label class="text-xs text-white/70">Bill of entry ref</label>
                    <input type="text" placeholder="BOE-2026-118" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                </div>
            </div>
        </x-erp.section>

        <x-erp.section>
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-white">Invoice lines</h3>
                <x-erp.action-button variant="ghost">Add Item</x-erp.action-button>
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
                            <td class="py-4 text-white/50" colspan="6">No items added yet.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-erp.section>

        <x-erp.section>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <div class="lg:col-span-2">
                    <label class="text-xs text-white/70">Remarks</label>
                    <textarea rows="4" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" placeholder="Internal notes"></textarea>
                </div>
                <div class="rounded-2xl bg-black/10 border border-white/10 p-5">
                    <p class="text-xs text-white/70">Subtotal</p>
                    <p class="text-lg font-semibold text-white">ZIG 0.00</p>
                    <p class="text-xs text-white/70 mt-3">VAT</p>
                    <p class="text-lg font-semibold text-white">ZIG 0.00</p>
                    <p class="text-xs text-white/70 mt-3">Total</p>
                    <p class="text-lg font-semibold text-white">ZIG 0.00</p>
                </div>
            </div>
        </x-erp.section>
    </x-erp.page>
</x-app-layout>
