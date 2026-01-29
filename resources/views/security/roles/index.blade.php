<x-app-layout>
    <x-erp.page title="Roles" subtitle="Configure module roles and permission bundles.">
        <x-slot name="actions">
            <x-erp.action-button variant="primary">Create Role</x-erp.action-button>
            <x-erp.action-button>Export</x-erp.action-button>
            <x-erp.action-button variant="danger" x-on:click="$dispatch('open-modal', 'delete-role')">Delete</x-erp.action-button>
        </x-slot>

        <x-erp.section>
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                <div class="lg:col-span-6">
                    <label class="text-xs text-white/70">Search</label>
                    <input type="text" placeholder="Role name" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                </div>
                <div class="lg:col-span-3">
                    <label class="text-xs text-white/70">Company</label>
                    <input type="text" placeholder="Company" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                </div>
                <div class="lg:col-span-3 flex items-end">
                    <x-erp.action-button variant="muted" class="w-full justify-center">Filter</x-erp.action-button>
                </div>
            </div>
        </x-erp.section>

        <x-erp.section>
            <div class="mt-1 overflow-x-auto">
                <table class="min-w-full text-sm text-white/80">
                    <thead class="text-xs uppercase text-white/50 border-b border-white/10">
                        <tr>
                            <th class="py-3 text-left">Role</th>
                            <th class="py-3 text-left">Company</th>
                            <th class="py-3 text-right">Users</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-white/5">
                            <td class="py-4">Accountant</td>
                            <td class="py-4">Tax Compliance</td>
                            <td class="py-4 text-right">4</td>
                        </tr>
                        <tr>
                            <td class="py-4 text-white/50" colspan="3">No more roles.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-erp.section>

        <x-modal name="delete-role" maxWidth="lg">
            <div class="bg-slate-950 text-white p-6">
                <h3 class="text-lg font-semibold">Delete role</h3>
                <p class="text-sm text-white/70 mt-2">Ensure the role is not assigned to any users.</p>
                <div class="mt-6 flex justify-end gap-3">
                    <x-erp.action-button variant="muted" x-on:click="$dispatch('close-modal', 'delete-role')">Close</x-erp.action-button>
                    <x-erp.action-button variant="danger">Confirm Delete</x-erp.action-button>
                </div>
            </div>
        </x-modal>
    </x-erp.page>
</x-app-layout>
