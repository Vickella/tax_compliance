<x-app-layout>
    <x-erp.page title="Trial Balance" subtitle="Validate ledger balances for the selected period.">
        <x-slot name="actions">
            <x-erp.action-button>Export</x-erp.action-button>
            <x-erp.action-button>Print</x-erp.action-button>
        </x-slot>

        <x-erp.section>
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                <div class="lg:col-span-4">
                    <label class="text-xs text-white/70">Period</label>
                    <input type="text" placeholder="2026-01" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                </div>
                <div class="lg:col-span-4">
                    <label class="text-xs text-white/70">From - To</label>
                    <div class="mt-2 flex gap-2">
                        <input type="date" class="w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                        <input type="date" class="w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                    </div>
                </div>
                <div class="lg:col-span-2">
                    <label class="text-xs text-white/70">Currency</label>
                    <select class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white">
                        <option>ZIG</option>
                        <option>USD</option>
                    </select>
                </div>
                <div class="lg:col-span-2 flex items-end">
                    <x-erp.action-button variant="muted" class="w-full justify-center">Run Report</x-erp.action-button>
                </div>
            </div>
        </x-erp.section>

        <x-erp.section>
            <div class="mt-1 overflow-x-auto">
                <table class="min-w-full text-sm text-white/80">
                    <thead class="text-xs uppercase text-white/50 border-b border-white/10">
                        <tr>
                            <th class="py-3 text-left">Account</th>
                            <th class="py-3 text-right">Debit</th>
                            <th class="py-3 text-right">Credit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-white/5">
                            <td class="py-4">Accounts Receivable</td>
                            <td class="py-4 text-right">ZIG 120,000.00</td>
                            <td class="py-4 text-right">ZIG 0.00</td>
                        </tr>
                        <tr>
                            <td class="py-4 text-white/50" colspan="3">No more accounts in the selected period.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-erp.section>
    </x-erp.page>
</x-app-layout>
