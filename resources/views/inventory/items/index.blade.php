<x-app-layout>
    <x-erp.page title="Inventory Items" subtitle="Maintain stock and service items with VAT categories and UOM.">
        <x-slot name="actions">
            <x-erp.action-link href="{{ route('inventory.items.create') }}" variant="primary">Create Item</x-erp.action-link>
            <x-erp.action-button variant="primary">Create Item</x-erp.action-button>
            <x-erp.action-button x-on:click="$dispatch('open-modal', 'import-items')">Import</x-erp.action-button>
            <x-erp.action-button>Export</x-erp.action-button>
            <x-erp.action-button variant="danger" x-on:click="$dispatch('open-modal', 'delete-item')">Delete</x-erp.action-button>
        </x-slot>

        <x-erp.section>
            <form method="GET" class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                <div class="lg:col-span-4">
                    <label class="text-xs text-white/70">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Item name or SKU" class="mt-2 w-full rounded-2xl bg-white/10 border border-white/10 text-white px-4 py-2.5" />
                </div>
                <div class="lg:col-span-3">
                    <label class="text-xs text-white/70">Item type</label>
                    <select class="mt-2 w-full rounded-2xl bg-white/10 border border-white/10 text-white px-4 py-2.5">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                <div class="lg:col-span-4">
                    <label class="text-xs text-white/70">Search</label>
                    <input type="text" placeholder="Item name or SKU" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                </div>
                <div class="lg:col-span-3">
                    <label class="text-xs text-white/70">Item type</label>
                    <select class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white">
                        <option>All</option>
                        <option>Stock</option>
                        <option>Service</option>
                    </select>
                </div>
                <div class="lg:col-span-3">
                    <label class="text-xs text-white/70">VAT category</label>
                    <select class="mt-2 w-full rounded-2xl bg-white/10 border border-white/10 text-white px-4 py-2.5">
                    <select class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white">
                        <option>All</option>
                        <option>VAT_STD</option>
                        <option>VAT_ZERO</option>
                        <option>VAT_EXEMPT</option>
                    </select>
                </div>
                <div class="lg:col-span-2 flex items-end">
                    <x-erp.action-button variant="muted" class="w-full justify-center" type="submit">Filter</x-erp.action-button>
                </div>
            </form>
                    <x-erp.action-button variant="muted" class="w-full justify-center">Filter</x-erp.action-button>
                </div>
            </div>
        </x-erp.section>

        <x-erp.section>
            <div class="mt-1 overflow-x-auto">
                <table class="min-w-full text-sm text-white/80">
                    <thead class="text-xs uppercase text-white/50 border-b border-white/10">
                        <tr>
                            <th class="py-3 text-left">SKU</th>
                            <th class="py-3 text-left">Item Name</th>
                            <th class="py-3 text-left">Type</th>
                            <th class="py-3 text-left">UOM</th>
                            <th class="py-3 text-right">Cost</th>
                            <th class="py-3 text-right">Selling</th>
                            <th class="py-3 text-left">VAT Category</th>
                            <th class="py-3 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                            <tr class="border-b border-white/5">
                                <td class="py-4">{{ $item->sku }}</td>
                                <td class="py-4">{{ $item->name }}</td>
                                <td class="py-4">{{ $item->item_type }}</td>
                                <td class="py-4">{{ $item->uom }}</td>
                                <td class="py-4 text-right">ZIG {{ number_format($item->cost_price ?? 0, 2) }}</td>
                                <td class="py-4 text-right">ZIG {{ number_format($item->selling_price ?? 0, 2) }}</td>
                                <td class="py-4">{{ $item->vat_category }}</td>
                                <td class="py-4 text-right"><span class="rounded-full bg-emerald-500/20 text-emerald-200 px-2 py-1 text-xs">{{ $item->is_active ? 'Active' : 'Inactive' }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td class="py-4 text-white/50" colspan="8">No items found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $items->links() }}</div>
                        <tr class="border-b border-white/5">
                            <td class="py-4">ITM-1001</td>
                            <td class="py-4">Premium Flour 50kg</td>
                            <td class="py-4">Stock</td>
                            <td class="py-4">Bag</td>
                            <td class="py-4">VAT_STD</td>
                            <td class="py-4 text-right"><span class="rounded-full bg-emerald-500/20 text-emerald-200 px-2 py-1 text-xs">Active</span></td>
                        </tr>
                        <tr>
                            <td class="py-4 text-white/50" colspan="6">No more items. Create or import inventory items.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-erp.section>

        <x-modal name="delete-item" maxWidth="lg">
            <div class="bg-slate-950 text-white p-6">
                <h3 class="text-lg font-semibold">Delete item</h3>
                <p class="text-sm text-white/70 mt-2">Deleting an item will block future transactions using it.</p>
                <div class="mt-6 flex justify-end gap-3">
                    <x-erp.action-button variant="muted" x-on:click="$dispatch('close-modal', 'delete-item')">Cancel</x-erp.action-button>
                    <x-erp.action-button variant="danger">Confirm Delete</x-erp.action-button>
                </div>
            </div>
        </x-modal>

        <x-modal name="import-items" maxWidth="lg">
            <div class="bg-slate-950 text-white p-6">
                <h3 class="text-lg font-semibold">Import items</h3>
                <p class="text-sm text-white/70 mt-2">Use the item master template with SKU, VAT category, and UOM.</p>
                <div class="mt-4 border border-dashed border-white/20 rounded-xl p-6 text-center text-sm text-white/70">
                    Drag and drop file here, or click to browse.
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <x-erp.action-button variant="muted" x-on:click="$dispatch('close-modal', 'import-items')">Close</x-erp.action-button>
                    <x-erp.action-button variant="primary">Upload</x-erp.action-button>
                </div>
            </div>
        </x-modal>
    </x-erp.page>
</x-app-layout>
