<x-app-layout>
    <x-erp.page title="Customer Statements" subtitle="Review customer balances and aging summaries.">
        <x-slot name="actions">
            <x-erp.action-button>Export</x-erp.action-button>
            <x-erp.action-button>Print</x-erp.action-button>
        </x-slot>

        <x-erp.section>
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                <div class="lg:col-span-5">
                    <label class="text-xs text-white/70">Customer</label>
                    <input type="text" placeholder="Select customer" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                </div>
                <div class="lg:col-span-4">
                    <label class="text-xs text-white/70">Statement date</label>
                    <input type="date" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                </div>
                <div class="lg:col-span-3 flex items-end">
                    <x-erp.action-button variant="muted" class="w-full justify-center">Generate Statement</x-erp.action-button>
                </div>
            </div>
        </x-erp.section>

        <x-erp.section>
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                <div class="rounded-2xl bg-black/10 border border-white/10 p-5">
                    <p class="text-xs text-white/70">Current</p>
                    <p class="text-lg font-semibold text-white">ZIG 0.00</p>
                </div>
                <div class="rounded-2xl bg-black/10 border border-white/10 p-5">
                    <p class="text-xs text-white/70">30 Days</p>
                    <p class="text-lg font-semibold text-white">ZIG 0.00</p>
                </div>
                <div class="rounded-2xl bg-black/10 border border-white/10 p-5">
                    <p class="text-xs text-white/70">60 Days</p>
                    <p class="text-lg font-semibold text-white">ZIG 0.00</p>
                </div>
                <div class="rounded-2xl bg-black/10 border border-white/10 p-5">
                    <p class="text-xs text-white/70">90+ Days</p>
                    <p class="text-lg font-semibold text-white">ZIG 0.00</p>
                </div>
            </div>
            <div class="mt-6 overflow-x-auto">
                <table class="min-w-full text-sm text-white/80">
                    <thead class="text-xs uppercase text-white/50 border-b border-white/10">
                        <tr>
                            <th class="py-3 text-left">Date</th>
                            <th class="py-3 text-left">Document</th>
                            <th class="py-3 text-right">Debit</th>
                            <th class="py-3 text-right">Credit</th>
                            <th class="py-3 text-right">Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="py-4 text-white/50" colspan="5">No statement transactions available.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-erp.section>
    </x-erp.page>
</x-app-layout>
