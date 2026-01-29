<x-app-layout>
    <x-erp.page title="Accounting Periods" subtitle="Configure fiscal periods and tax year alignment.">
        <x-slot name="actions">
            <x-erp.action-button variant="primary">Create Period</x-erp.action-button>
            <x-erp.action-button>Close Period</x-erp.action-button>
        </x-slot>

        <x-erp.section>
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                <div class="lg:col-span-4">
                    <label class="text-xs text-white/70">Period type</label>
                    <select class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white">
                        <option>Month</option>
                        <option>Quarter</option>
                        <option>Year</option>
                    </select>
                </div>
                <div class="lg:col-span-4">
                    <label class="text-xs text-white/70">Start date</label>
                    <input type="date" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
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
                            <th class="py-3 text-left">Period</th>
                            <th class="py-3 text-left">Type</th>
                            <th class="py-3 text-left">Start</th>
                            <th class="py-3 text-left">End</th>
                            <th class="py-3 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-white/5">
                            <td class="py-4">2026-01</td>
                            <td class="py-4">Month</td>
                            <td class="py-4">2026-01-01</td>
                            <td class="py-4">2026-01-31</td>
                            <td class="py-4 text-right"><span class="rounded-full bg-emerald-500/20 text-emerald-200 px-2 py-1 text-xs">Open</span></td>
                        </tr>
                        <tr>
                            <td class="py-4 text-white/50" colspan="5">No more periods.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-erp.section>
    </x-erp.page>
</x-app-layout>
