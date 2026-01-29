<x-app-layout>
    <x-erp.page title="Stock Ledger" subtitle="View item movement history and running balances.">
        <x-slot name="actions">
            <x-erp.action-button>Export</x-erp.action-button>
            <x-erp.action-button>Print</x-erp.action-button>
        </x-slot>

        <x-erp.section>
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                <div class="lg:col-span-4">
                    <label class="text-xs text-white/70">Item</label>
                    <input type="text" placeholder="Select item" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                </div>
                <div class="lg:col-span-3">
                    <label class="text-xs text-white/70">Warehouse</label>
                    <input type="text" placeholder="Select warehouse" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                </div>
                <div class="lg:col-span-3">
                    <label class="text-xs text-white/70">Date range</label>
                    <div class="mt-2 flex gap-2">
                        <input type="date" class="w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                        <input type="date" class="w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                    </div>
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
                            <th class="py-3 text-left">Posting Date</th>
                            <th class="py-3 text-left">Voucher</th>
                            <th class="py-3 text-right">Qty</th>
                            <th class="py-3 text-right">Unit Cost</th>
                            <th class="py-3 text-right">Value</th>
                            <th class="py-3 text-right">Running Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-white/5">
                            <td class="py-4">2026-01-05</td>
                            <td class="py-4">PurchaseInvoice PI-00012</td>
                            <td class="py-4 text-right">+50</td>
                            <td class="py-4 text-right">ZIG 80.00</td>
                            <td class="py-4 text-right">ZIG 4,000.00</td>
                            <td class="py-4 text-right">50</td>
                        </tr>
                        <tr>
                            <td class="py-4 text-white/50" colspan="6">No more ledger entries for the selected filters.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-erp.section>
    </x-erp.page>
</x-app-layout>
