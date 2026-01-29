<x-app-layout>
    <x-erp.page title="Suppliers" subtitle="Manage suppliers, VAT registration, and withholding tax flags.">
        <x-slot name="actions">
            <x-erp.action-link href="{{ route('crm.suppliers.create') }}" variant="primary">Create Supplier</x-erp.action-link>
            <x-erp.action-button x-on:click="$dispatch('open-modal', 'import-suppliers')">Import</x-erp.action-button>
            <x-erp.action-button>Export</x-erp.action-button>
            <x-erp.action-button variant="danger" x-on:click="$dispatch('open-modal', 'delete-supplier')">Delete</x-erp.action-button>
        </x-slot>

        <x-erp.section>
            <form method="GET" class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                <div class="lg:col-span-4">
                    <label class="text-xs text-white/70">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Supplier name or code" class="mt-2 w-full rounded-2xl bg-white/10 border border-white/10 text-white px-4 py-2.5" />
                </div>
                <div class="lg:col-span-3">
                    <label class="text-xs text-white/70">Status</label>
                    <select class="mt-2 w-full rounded-2xl bg-white/10 border border-white/10 text-white px-4 py-2.5">
                        <option>All</option>
                        <option>Active</option>
                        <option>Inactive</option>
                    </select>
                </div>
                <div class="lg:col-span-3">
                    <label class="text-xs text-white/70">Withholding Tax</label>
                    <select class="mt-2 w-full rounded-2xl bg-white/10 border border-white/10 text-white px-4 py-2.5">
                        <option>All</option>
                        <option>Enabled</option>
                        <option>Disabled</option>
                    </select>
                </div>
                <div class="lg:col-span-2 flex items-end">
                    <x-erp.action-button variant="muted" class="w-full justify-center" type="submit">Filter</x-erp.action-button>
                </div>
            </form>
        </x-erp.section>

        <x-erp.section>
            <div class="mt-1 overflow-x-auto">
                <table class="min-w-full text-sm text-white/80">
                    <thead class="text-xs uppercase text-white/50 border-b border-white/10">
                        <tr>
                            <th class="py-3 text-left">Code</th>
                            <th class="py-3 text-left">Supplier</th>
                            <th class="py-3 text-left">TIN</th>
                            <th class="py-3 text-left">VAT</th>
                            <th class="py-3 text-right">WHT</th>
                            <th class="py-3 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suppliers as $supplier)
                            <tr class="border-b border-white/5">
                                <td class="py-4">{{ $supplier->code }}</td>
                                <td class="py-4">{{ $supplier->name }}</td>
                                <td class="py-4">{{ $supplier->tin }}</td>
                                <td class="py-4">{{ $supplier->vat_number }}</td>
                                <td class="py-4 text-right">{{ $supplier->withholding_tax_flag ? 'Yes' : 'No' }}</td>
                                <td class="py-4 text-right"><span class="rounded-full bg-emerald-500/20 text-emerald-200 px-2 py-1 text-xs">{{ $supplier->is_active ? 'Active' : 'Inactive' }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td class="py-4 text-white/50" colspan="6">No suppliers found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $suppliers->links() }}</div>
        </x-erp.section>

        <x-modal name="delete-supplier" maxWidth="lg">
            <div class="bg-slate-950 text-white p-6">
                <h3 class="text-lg font-semibold">Delete supplier</h3>
                <p class="text-sm text-white/70 mt-2">Suppliers with unpaid invoices cannot be deleted.</p>
                <div class="mt-6 flex justify-end gap-3">
                    <x-erp.action-button variant="muted" x-on:click="$dispatch('close-modal', 'delete-supplier')">Close</x-erp.action-button>
                    <x-erp.action-button variant="danger">Confirm Delete</x-erp.action-button>
                </div>
            </div>
        </x-modal>

        <x-modal name="import-suppliers" maxWidth="lg">
            <div class="bg-slate-950 text-white p-6">
                <h3 class="text-lg font-semibold">Import suppliers</h3>
                <p class="text-sm text-white/70 mt-2">Upload supplier data with VAT and withholding flags.</p>
                <div class="mt-4 border border-dashed border-white/20 rounded-xl p-6 text-center text-sm text-white/70">
                    Drag and drop file here, or click to browse.
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <x-erp.action-button variant="muted" x-on:click="$dispatch('close-modal', 'import-suppliers')">Close</x-erp.action-button>
                    <x-erp.action-button variant="primary">Upload</x-erp.action-button>
                </div>
            </div>
        </x-modal>
    </x-erp.page>
</x-app-layout>
