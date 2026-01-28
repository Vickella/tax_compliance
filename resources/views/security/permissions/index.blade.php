<x-app-layout>
    <x-erp.page title="Permissions" subtitle="Define fine-grained access control by module and action.">
        <x-slot name="actions">
            <x-erp.action-button variant="primary">Create Permission</x-erp.action-button>
            <x-erp.action-button>Export</x-erp.action-button>
            <x-erp.action-button variant="danger" x-on:click="$dispatch('open-modal', 'delete-permission')">Delete</x-erp.action-button>
        </x-slot>

        <x-erp.section>
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                <div class="lg:col-span-4">
                    <label class="text-xs text-white/70">Module</label>
                    <select class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white">
                        <option>All</option>
                        <option>Sales</option>
                        <option>Purchases</option>
                        <option>Inventory</option>
                        <option>Payroll</option>
                        <option>Tax</option>
                    </select>
                </div>
                <div class="lg:col-span-4">
                    <label class="text-xs text-white/70">Action</label>
                    <select class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white">
                        <option>All</option>
                        <option>Create</option>
                        <option>Submit</option>
                        <option>Cancel</option>
                        <option>Delete</option>
                    </select>
                </div>
                <div class="lg:col-span-4 flex items-end">
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
                            <th class="py-3 text-left">Module</th>
                            <th class="py-3 text-left">Resource</th>
                            <th class="py-3 text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-white/5">
                            <td class="py-4">sales.invoice.submit</td>
                            <td class="py-4">Sales</td>
                            <td class="py-4">Invoice</td>
                            <td class="py-4">Submit</td>
                        </tr>
                        <tr>
                            <td class="py-4 text-white/50" colspan="4">No more permissions.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-erp.section>

        <x-modal name="delete-permission" maxWidth="lg">
            <div class="bg-slate-950 text-white p-6">
                <h3 class="text-lg font-semibold">Delete permission</h3>
                <p class="text-sm text-white/70 mt-2">Removing permissions affects assigned roles.</p>
                <div class="mt-6 flex justify-end gap-3">
                    <x-erp.action-button variant="muted" x-on:click="$dispatch('close-modal', 'delete-permission')">Close</x-erp.action-button>
                    <x-erp.action-button variant="danger">Confirm Delete</x-erp.action-button>
                </div>
            </div>
        </x-modal>
    </x-erp.page>
</x-app-layout>
