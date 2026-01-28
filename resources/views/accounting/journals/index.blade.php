<x-app-layout>
    <x-erp.page title="Journal Entries" subtitle="Review and post general ledger journals.">
        <x-slot name="actions">
            <x-erp.action-button variant="primary">Create Journal</x-erp.action-button>
            <x-erp.action-button>Export</x-erp.action-button>
            <x-erp.action-button>Print</x-erp.action-button>
            <x-erp.action-button variant="danger" x-on:click="$dispatch('open-modal', 'reverse-journal')">Reverse</x-erp.action-button>
        </x-slot>

        <x-erp.section>
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                <div class="lg:col-span-4">
                    <label class="text-xs text-white/70">Journal #</label>
                    <input type="text" placeholder="JE-0001" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                </div>
                <div class="lg:col-span-3">
                    <label class="text-xs text-white/70">Status</label>
                    <select class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white">
                        <option>All</option>
                        <option>Draft</option>
                        <option>Posted</option>
                        <option>Cancelled</option>
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
                            <th class="py-3 text-left">Journal #</th>
                            <th class="py-3 text-left">Posting Date</th>
                            <th class="py-3 text-left">Memo</th>
                            <th class="py-3 text-right">Amount</th>
                            <th class="py-3 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-white/5">
                            <td class="py-4">JE-0007</td>
                            <td class="py-4">2026-01-20</td>
                            <td class="py-4">Payroll posting</td>
                            <td class="py-4 text-right">ZIG 22,000.00</td>
                            <td class="py-4"><span class="rounded-full bg-emerald-500/20 text-emerald-200 px-2 py-1 text-xs">Posted</span></td>
                        </tr>
                        <tr>
                            <td class="py-4 text-white/50" colspan="5">No more journal entries.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-erp.section>

        <x-modal name="reverse-journal" maxWidth="lg">
            <div class="bg-slate-950 text-white p-6">
                <h3 class="text-lg font-semibold">Reverse journal entry</h3>
                <p class="text-sm text-white/70 mt-2">Reversal journals create equal and opposite postings.</p>
                <textarea class="mt-4 w-full rounded-xl bg-white/10 border border-white/10 text-white" rows="4" placeholder="Reason for reversal"></textarea>
                <div class="mt-6 flex justify-end gap-3">
                    <x-erp.action-button variant="muted" x-on:click="$dispatch('close-modal', 'reverse-journal')">Close</x-erp.action-button>
                    <x-erp.action-button variant="danger">Confirm Reverse</x-erp.action-button>
                </div>
            </div>
        </x-modal>
    </x-erp.page>
</x-app-layout>
