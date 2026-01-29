<x-app-layout>
    <x-erp.page title="Purchase Invoices" subtitle="Capture supplier VAT invoices, bills of entry, and input tax documents.">
        <x-slot name="actions">
            <x-erp.action-link href="{{ route('purchases.invoices.create') }}" variant="primary">Create Invoice</x-erp.action-link>
            <x-erp.action-button x-on:click="$dispatch('open-modal', 'import-purchases')">Import</x-erp.action-button>
            <x-erp.action-button>Export</x-erp.action-button>
            <x-erp.action-button>Print</x-erp.action-button>
            <x-erp.action-button variant="danger" x-on:click="$dispatch('open-modal', 'cancel-purchase')">Cancel</x-erp.action-button>
        </x-slot>

        <x-erp.section>
            <form method="GET" class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                <div class="lg:col-span-4">
                    <label class="text-xs text-white/70">Supplier</label>
                    <input type="text" name="supplier" value="{{ request('supplier') }}" placeholder="Search supplier" class="mt-2 w-full rounded-2xl bg-white/10 border border-white/10 text-white px-4 py-2.5" />
                </div>
                <div class="lg:col-span-3">
                    <label class="text-xs text-white/70">Status</label>
                    <select class="mt-2 w-full rounded-2xl bg-white/10 border border-white/10 text-white px-4 py-2.5">
                        <option>All</option>
                        <option>Draft</option>
                        <option>Submitted</option>
                        <option>Cancelled</option>
                    </select>
                </div>
                <div class="lg:col-span-3">
                    <label class="text-xs text-white/70">Posting date range</label>
                    <div class="mt-2 flex gap-2">
                        <input type="date" class="w-full rounded-2xl bg-white/10 border border-white/10 text-white px-3 py-2.5" />
                        <input type="date" class="w-full rounded-2xl bg-white/10 border border-white/10 text-white px-3 py-2.5" />
                    </div>
                </div>
                <div class="lg:col-span-2 flex items-end">
                    <x-erp.action-button variant="muted" class="w-full justify-center" type="submit">Filter</x-erp.action-button>
                </div>
            </form>
        </x-erp.section>

        <x-erp.section>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-white">Supplier invoice register</p>
                    <p class="text-xs text-white/60">Tracks input VAT documentation and bills of entry.</p>
                </div>
                <span class="text-xs text-white/50">Total invoices: 0</span>
            </div>

            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full text-sm text-white/80">
                    <thead class="text-xs uppercase text-white/50 border-b border-white/10">
                        <tr>
                            <th class="py-3 text-left">Invoice #</th>
                            <th class="py-3 text-left">Supplier</th>
                            <th class="py-3 text-left">Supplier Ref</th>
                            <th class="py-3 text-left">Posting Date</th>
                            <th class="py-3 text-left">Status</th>
                            <th class="py-3 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $invoice)
                            <tr class="border-b border-white/5">
                                <td class="py-4">{{ $invoice->invoice_no }}</td>
                                <td class="py-4">{{ $invoice->supplier?->name }}</td>
                                <td class="py-4">{{ $invoice->supplier_invoice_no }}</td>
                                <td class="py-4">{{ optional($invoice->posting_date)->format('Y-m-d') }}</td>
                                <td class="py-4"><span class="rounded-full bg-amber-500/20 text-amber-200 px-2 py-1 text-xs">{{ $invoice->status }}</span></td>
                                <td class="py-4 text-right">{{ $invoice->currency }} {{ number_format($invoice->total, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="py-4 text-white/50" colspan="6">No invoices found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(isset($invoices))
                <div class="mt-4">{{ $invoices->links() }}</div>
            @endif
        </x-erp.section>

        <x-modal name="cancel-purchase" maxWidth="lg">
            <div class="bg-slate-950 text-white p-6">
                <h3 class="text-lg font-semibold">Cancel purchase invoice</h3>
                <p class="text-sm text-white/70 mt-2">Cancellation will reverse input VAT and payable entries.</p>
                <textarea class="mt-4 w-full rounded-xl bg-white/10 border border-white/10 text-white" rows="4" placeholder="Reason for cancellation"></textarea>
                <div class="mt-6 flex justify-end gap-3">
                    <x-erp.action-button variant="muted" x-on:click="$dispatch('close-modal', 'cancel-purchase')">Keep Invoice</x-erp.action-button>
                    <x-erp.action-button variant="danger">Confirm Cancel</x-erp.action-button>
                </div>
            </div>
        </x-modal>

        <x-modal name="import-purchases" maxWidth="lg">
            <div class="bg-slate-950 text-white p-6">
                <h3 class="text-lg font-semibold">Import purchase invoices</h3>
                <p class="text-sm text-white/70 mt-2">Upload supplier invoices with VAT fields and bill of entry references.</p>
                <div class="mt-4 border border-dashed border-white/20 rounded-xl p-6 text-center text-sm text-white/70">
                    Drag and drop file here, or click to browse.
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <x-erp.action-button variant="muted" x-on:click="$dispatch('close-modal', 'import-purchases')">Close</x-erp.action-button>
                    <x-erp.action-button variant="primary">Upload</x-erp.action-button>
                </div>
            </div>
        </x-modal>
    </x-erp.page>
</x-app-layout>
