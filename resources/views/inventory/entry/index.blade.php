<x-app-layout>
    <x-erp.page title="Stock Entry" subtitle="Record receipts, issues, and adjustments across warehouses.">
        <x-slot name="actions">
            <x-erp.action-button variant="primary">Create Entry</x-erp.action-button>
            <x-erp.action-button>Export</x-erp.action-button>
            <x-erp.action-button variant="danger" x-on:click="$dispatch('open-modal', 'cancel-stock-entry')">Cancel</x-erp.action-button>
        </x-slot>

        <x-erp.section>
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                <div>
                    <label class="text-xs text-white/70">Entry type</label>
                    <select class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white">
                        <option>Receipt</option>
                        <option>Issue</option>
                        <option>Adjustment</option>
                        <option>Transfer</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs text-white/70">Posting date</label>
                    <input type="date" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                </div>
                <div>
                    <label class="text-xs text-white/70">Reference</label>
                    <input type="text" placeholder="SR-2026-001" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                </div>
                <div class="flex items-end">
                    <x-erp.action-button variant="muted" class="w-full justify-center">Apply Filters</x-erp.action-button>
                </div>
            </div>
        </x-erp.section>

        <x-erp.section>
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-white">Stock movements</h3>
                <x-erp.action-button variant="ghost">Add Line</x-erp.action-button>
            </div>
            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full text-sm text-white/80">
                    <thead class="text-xs uppercase text-white/50 border-b border-white/10">
                        <tr>
                            <th class="py-3 text-left">Item</th>
                            <th class="py-3 text-left">Warehouse</th>
                            <th class="py-3 text-right">Qty</th>
                            <th class="py-3 text-right">Unit Cost</th>
                            <th class="py-3 text-right">Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="py-4 text-white/50" colspan="5">No stock movements recorded.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-erp.section>

        <x-modal name="cancel-stock-entry" maxWidth="lg">
            <div class="bg-slate-950 text-white p-6">
                <h3 class="text-lg font-semibold">Cancel stock entry</h3>
                <p class="text-sm text-white/70 mt-2">Cancelled entries will reverse stock ledger postings.</p>
                <textarea class="mt-4 w-full rounded-xl bg-white/10 border border-white/10 text-white" rows="4" placeholder="Reason for cancellation"></textarea>
                <div class="mt-6 flex justify-end gap-3">
                    <x-erp.action-button variant="muted" x-on:click="$dispatch('close-modal', 'cancel-stock-entry')">Keep Entry</x-erp.action-button>
                    <x-erp.action-button variant="danger">Confirm Cancel</x-erp.action-button>
                </div>
            </div>
        </x-modal>
    </x-erp.page>
</x-app-layout>
