<x-app-layout>
    <x-erp.page title="Customers" subtitle="Manage customer master data, TIN, VAT, and credit limits.">
        <x-slot name="actions">
            <x-erp.action-button variant="primary">Create Customer</x-erp.action-button>
            <x-erp.action-button x-on:click="$dispatch('open-modal', 'import-customers')">Import</x-erp.action-button>
            <x-erp.action-button>Export</x-erp.action-button>
            <x-erp.action-button variant="danger" x-on:click="$dispatch('open-modal', 'delete-customer')">Delete</x-erp.action-button>
        </x-slot>

        <x-erp.section>
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                <div class="lg:col-span-4">
                    <label class="text-xs text-white/70">Search</label>
                    <input type="text" placeholder="Customer name or code" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                </div>
                <div class="lg:col-span-3">
                    <label class="text-xs text-white/70">Status</label>
                    <select class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white">
                        <option>All</option>
                        <option>Active</option>
                        <option>Inactive</option>
                    </select>
                </div>
                <div class="lg:col-span-3">
                    <label class="text-xs text-white/70">Currency</label>
                    <select class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white">
                        <option>All</option>
                        <option>ZIG</option>
                        <option>USD</option>
                    </select>
                </div>
                <div class="lg:col-span-2 flex items-end">
                    <x-erp.action-button variant="muted" class="w-full justify-center">Filter</x-erp.action-button>
                </div>
            </div>
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
                        <tr class="border-b border-white/5">
                            <td class="py-4">CUST-001</td>
                            <td class="py-4">Bluewave Retailers</td>
                            <td class="py-4">TIN-98344</td>
                            <td class="py-4">263-123456-3</td>
                            <td class="py-4 text-right">ZIG 10,000.00</td>
                            <td class="py-4 text-right"><span class="rounded-full bg-emerald-500/20 text-emerald-200 px-2 py-1 text-xs">Active</span></td>
                        </tr>
                        <tr>
                            <td class="py-4 text-white/50" colspan="6">No more customers.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
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
