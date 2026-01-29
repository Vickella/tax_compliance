<x-app-layout>
    <x-erp.page title="Customers" subtitle="Manage customer master data, TIN, VAT, and credit limits.">
        <x-slot name="actions">
            <x-erp.action-link href="{{ route('crm.customers.create') }}" variant="primary">Create Customer</x-erp.action-link>
            <x-erp.action-button x-on:click="$dispatch('open-modal', 'import-customers')">Import</x-erp.action-button>
            <x-erp.action-button>Export</x-erp.action-button>
            <x-erp.action-button variant="danger" x-on:click="$dispatch('open-modal', 'delete-customer')">Delete</x-erp.action-button>
        </x-slot>

        <x-erp.section>
            <form method="GET" class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                <div class="lg:col-span-4">
                    <label class="text-xs text-white/70">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Customer name or code" class="mt-2 w-full rounded-2xl bg-white/10 border border-white/10 text-white px-4 py-2.5" />
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
                    <label class="text-xs text-white/70">Currency</label>
                    <select class="mt-2 w-full rounded-2xl bg-white/10 border border-white/10 text-white px-4 py-2.5">
                        <option>All</option>
                        <option>ZIG</option>
                        <option>USD</option>
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
                            <th class="py-3 text-left">Customer</th>
                            <th class="py-3 text-left">TIN</th>
                            <th class="py-3 text-left">VAT</th>
                            <th class="py-3 text-right">Credit Limit</th>
                            <th class="py-3 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                            <tr class="border-b border-white/5">
                                <td class="py-4">{{ $customer->code }}</td>
                                <td class="py-4">{{ $customer->name }}</td>
                                <td class="py-4">{{ $customer->tin }}</td>
                                <td class="py-4">{{ $customer->vat_number }}</td>
                                <td class="py-4 text-right">ZIG {{ number_format($customer->credit_limit ?? 0, 2) }}</td>
                                <td class="py-4 text-right"><span class="rounded-full bg-emerald-500/20 text-emerald-200 px-2 py-1 text-xs">{{ $customer->is_active ? 'Active' : 'Inactive' }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td class="py-4 text-white/50" colspan="6">No customers found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $customers->links() }}</div>
        </x-erp.section>

        <x-modal name="delete-customer" maxWidth="lg">
            <div class="bg-slate-950 text-white p-6">
                <h3 class="text-lg font-semibold">Delete customer</h3>
                <p class="text-sm text-white/70 mt-2">Customers with open invoices cannot be deleted.</p>
                <div class="mt-6 flex justify-end gap-3">
                    <x-erp.action-button variant="muted" x-on:click="$dispatch('close-modal', 'delete-customer')">Close</x-erp.action-button>
                    <x-erp.action-button variant="danger">Confirm Delete</x-erp.action-button>
                </div>
            </div>
        </x-modal>

        <x-modal name="import-customers" maxWidth="lg">
            <div class="bg-slate-950 text-white p-6">
                <h3 class="text-lg font-semibold">Import customers</h3>
                <p class="text-sm text-white/70 mt-2">Use the customer template with TIN, VAT, and credit limits.</p>
                <div class="mt-4 border border-dashed border-white/20 rounded-xl p-6 text-center text-sm text-white/70">
                    Drag and drop file here, or click to browse.
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <x-erp.action-button variant="muted" x-on:click="$dispatch('close-modal', 'import-customers')">Close</x-erp.action-button>
                    <x-erp.action-button variant="primary">Upload</x-erp.action-button>
                </div>
            </div>
        </x-modal>
    </x-erp.page>
</x-app-layout>
