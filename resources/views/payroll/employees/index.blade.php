<x-app-layout>
    <x-erp.page title="Employees" subtitle="Employee master data with NSSA, TIN, and payroll settings.">
        <x-slot name="actions">
            <x-erp.action-button variant="primary">Add Employee</x-erp.action-button>
            <x-erp.action-button x-on:click="$dispatch('open-modal', 'import-employees')">Import</x-erp.action-button>
            <x-erp.action-button>Export</x-erp.action-button>
            <x-erp.action-button variant="danger" x-on:click="$dispatch('open-modal', 'deactivate-employee')">Deactivate</x-erp.action-button>
        </x-slot>

        <x-erp.section>
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                <div class="lg:col-span-4">
                    <label class="text-xs text-white/70">Search</label>
                    <input type="text" placeholder="Employee name or number" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
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
                            <th class="py-3 text-left">Employee #</th>
                            <th class="py-3 text-left">Name</th>
                            <th class="py-3 text-left">TIN</th>
                            <th class="py-3 text-left">NSSA</th>
                            <th class="py-3 text-left">Currency</th>
                            <th class="py-3 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-white/5">
                            <td class="py-4">EMP-0001</td>
                            <td class="py-4">Tafadzwa Moyo</td>
                            <td class="py-4">TIN-20391</td>
                            <td class="py-4">NSSA-7788</td>
                            <td class="py-4">ZIG</td>
                            <td class="py-4 text-right"><span class="rounded-full bg-emerald-500/20 text-emerald-200 px-2 py-1 text-xs">Active</span></td>
                        </tr>
                        <tr>
                            <td class="py-4 text-white/50" colspan="6">No more employees.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-erp.section>

        <x-modal name="deactivate-employee" maxWidth="lg">
            <div class="bg-slate-950 text-white p-6">
                <h3 class="text-lg font-semibold">Deactivate employee</h3>
                <p class="text-sm text-white/70 mt-2">Inactive employees are excluded from payroll runs.</p>
                <div class="mt-6 flex justify-end gap-3">
                    <x-erp.action-button variant="muted" x-on:click="$dispatch('close-modal', 'deactivate-employee')">Close</x-erp.action-button>
                    <x-erp.action-button variant="danger">Confirm Deactivate</x-erp.action-button>
                </div>
            </div>
        </x-modal>

        <x-modal name="import-employees" maxWidth="lg">
            <div class="bg-slate-950 text-white p-6">
                <h3 class="text-lg font-semibold">Import employees</h3>
                <p class="text-sm text-white/70 mt-2">Upload employee master data with TIN, NSSA, and bank info.</p>
                <div class="mt-4 border border-dashed border-white/20 rounded-xl p-6 text-center text-sm text-white/70">
                    Drag and drop file here, or click to browse.
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <x-erp.action-button variant="muted" x-on:click="$dispatch('close-modal', 'import-employees')">Close</x-erp.action-button>
                    <x-erp.action-button variant="primary">Upload</x-erp.action-button>
                </div>
            </div>
        </x-modal>
    </x-erp.page>
</x-app-layout>
