<x-app-layout>
    <x-erp.page title="Receipts & Payments" subtitle="Manage cash receipts, supplier payments, and allocations.">
        <x-slot name="actions">
            <x-erp.action-button variant="primary">Create Receipt</x-erp.action-button>
            <x-erp.action-button>Export</x-erp.action-button>
            <x-erp.action-button>Print</x-erp.action-button>
            <x-erp.action-button variant="danger" x-on:click="$dispatch('open-modal', 'cancel-payment')">Cancel</x-erp.action-button>
        </x-slot>

        <x-erp.section>
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                <div class="lg:col-span-4">
                    <label class="text-xs text-white/70">Party</label>
                    <input type="text" placeholder="Customer / Supplier" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                </div>
                <div class="lg:col-span-3">
                    <label class="text-xs text-white/70">Payment type</label>
                    <select class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white">
                        <option>All</option>
                        <option>Receipt</option>
                        <option>Payment</option>
                    </select>
                </div>
                <div class="lg:col-span-3">
                    <label class="text-xs text-white/70">Posting date</label>
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
                            <th class="py-3 text-left">Payment #</th>
                            <th class="py-3 text-left">Party</th>
                            <th class="py-3 text-left">Type</th>
                            <th class="py-3 text-left">Bank</th>
                            <th class="py-3 text-right">Amount</th>
                            <th class="py-3 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-white/5">
                            <td class="py-4">RC-0005</td>
                            <td class="py-4">Bluewave Retailers</td>
                            <td class="py-4">Receipt</td>
                            <td class="py-4">ZB Bank - ZIG</td>
                            <td class="py-4 text-right">ZIG 6,000.00</td>
                            <td class="py-4"><span class="rounded-full bg-emerald-500/20 text-emerald-200 px-2 py-1 text-xs">Submitted</span></td>
                        </tr>
                        <tr>
                            <td class="py-4 text-white/50" colspan="6">No more receipts or payments.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-erp.section>

        <x-modal name="cancel-payment" maxWidth="lg">
            <div class="bg-slate-950 text-white p-6">
                <h3 class="text-lg font-semibold">Cancel payment</h3>
                <p class="text-sm text-white/70 mt-2">Cancellation will reverse GL postings and allocations.</p>
                <textarea class="mt-4 w-full rounded-xl bg-white/10 border border-white/10 text-white" rows="4" placeholder="Reason for cancellation"></textarea>
                <div class="mt-6 flex justify-end gap-3">
                    <x-erp.action-button variant="muted" x-on:click="$dispatch('close-modal', 'cancel-payment')">Keep Payment</x-erp.action-button>
                    <x-erp.action-button variant="danger">Confirm Cancel</x-erp.action-button>
                </div>
            </div>
        </x-modal>
    </x-erp.page>
</x-app-layout>
