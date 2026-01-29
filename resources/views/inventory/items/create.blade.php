<x-app-layout>
    <x-erp.page title="Create Item" subtitle="Define stock or service items with cost and selling prices.">
        <x-slot name="actions">
            <x-erp.action-link href="{{ route('inventory.items.index') }}">Back to Items</x-erp.action-link>
        </x-slot>

        <form method="POST" action="{{ route('inventory.items.store') }}" class="space-y-6">
            @csrf
            <x-erp.section>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="text-xs text-white/70">SKU</label>
                        <input type="text" name="sku" class="mt-2 w-full rounded-2xl bg-white/10 border border-white/10 text-white px-4 py-2.5" required />
                    </div>
                    <div>
                        <label class="text-xs text-white/70">Item name</label>
                        <input type="text" name="name" class="mt-2 w-full rounded-2xl bg-white/10 border border-white/10 text-white px-4 py-2.5" required />
                    </div>
                    <div>
                        <label class="text-xs text-white/70">Type</label>
                        <select name="item_type" class="mt-2 w-full rounded-2xl bg-white/10 border border-white/10 text-white px-4 py-2.5">
                            <option value="STOCK">Stock</option>
                            <option value="SERVICE">Service</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs text-white/70">UOM</label>
                        <input type="text" name="uom" value="Units" class="mt-2 w-full rounded-2xl bg-white/10 border border-white/10 text-white px-4 py-2.5" />
                    </div>
                    <div>
                        <label class="text-xs text-white/70">Cost price</label>
                        <input type="number" step="0.01" name="cost_price" class="mt-2 w-full rounded-2xl bg-white/10 border border-white/10 text-white px-4 py-2.5" required />
                    </div>
                    <div>
                        <label class="text-xs text-white/70">Selling price</label>
                        <input type="number" step="0.01" name="selling_price" class="mt-2 w-full rounded-2xl bg-white/10 border border-white/10 text-white px-4 py-2.5" required />
                    </div>
                    <div>
                        <label class="text-xs text-white/70">VAT category</label>
                        <select name="vat_category" class="mt-2 w-full rounded-2xl bg-white/10 border border-white/10 text-white px-4 py-2.5">
                            <option value="">None</option>
                            <option value="VAT_STD">VAT_STD</option>
                            <option value="VAT_ZERO">VAT_ZERO</option>
                            <option value="VAT_EXEMPT">VAT_EXEMPT</option>
                        </select>
                    </div>
                </div>
            </x-erp.section>

            <div class="flex justify-end gap-3">
                <x-erp.action-button variant="muted" type="reset">Reset</x-erp.action-button>
                <x-erp.action-button variant="primary" type="submit">Save Item</x-erp.action-button>
            </div>
        </form>
    </x-erp.page>
</x-app-layout>
