<x-app-layout>
    <x-erp.page title="Currencies & Rates" subtitle="Manage supported currencies and exchange rates.">
        <x-slot name="actions">
            <x-erp.action-button variant="primary">Add Currency</x-erp.action-button>
            <x-erp.action-button>Import Rates</x-erp.action-button>
            <x-erp.action-button>Export</x-erp.action-button>
        </x-slot>

        <x-erp.section>
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                <div class="lg:col-span-4">
                    <label class="text-xs text-white/70">Currency</label>
                    <input type="text" placeholder="ZIG" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                </div>
                <div class="lg:col-span-4">
                    <label class="text-xs text-white/70">Rate date</label>
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
                            <th class="py-3 text-left">Currency</th>
                            <th class="py-3 text-left">Name</th>
                            <th class="py-3 text-left">Symbol</th>
                            <th class="py-3 text-right">Rate</th>
                            <th class="py-3 text-left">Effective Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-white/5">
                            <td class="py-4">ZIG</td>
                            <td class="py-4">Zimbabwe Gold</td>
                            <td class="py-4">ZiG</td>
                            <td class="py-4 text-right">1.0000</td>
                            <td class="py-4">2026-01-01</td>
                        </tr>
                        <tr>
                            <td class="py-4 text-white/50" colspan="5">No more currency rates.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-erp.section>
    </x-erp.page>
</x-app-layout>
