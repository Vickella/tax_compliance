<x-app-layout>
    <x-erp.page title="Sales Invoices" subtitle="Fiscal tax invoices, credit notes, and debit notes with VAT compliance.">
        <x-slot name="actions">
            <x-erp.action-link href="{{ route('sales.invoices.create') }}" variant="primary">Create Invoice</x-erp.action-link>
            <x-erp.action-button x-on:click="$dispatch('open-modal', 'import-sales')">Import</x-erp.action-button>
            <x-erp.action-button>Export</x-erp.action-button>
            <x-erp.action-button>Print</x-erp.action-button>
            <x-erp.action-button variant="danger" x-on:click="$dispatch('open-modal', 'cancel-sales')">Cancel</x-erp.action-button>
        </x-slot>

        <x-erp.section>
            <form method="GET" class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                <div class="lg:col-span-4">
                    <label class="text-xs text-white/70">Customer</label>
                    <input type="text" name="customer" value="{{ request('customer') }}" placeholder="Search customer" class="mt-2 w-full rounded-2xl bg-white/10 border border-white/10 text-white placeholder-white/40 px-4 py-2.5" />
                </div>
                <div class="lg:col-span-3">
                    <label class="text-xs text-white/70">Invoice status</label>
                    <select class="mt-2 w-full rounded-2xl bg-white/10 border border-white/10 text-white px-4 py-2.5">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                <div class="lg:col-span-4">
                    <label class="text-xs text-white/70">Customer</label>
                    <input type="text" placeholder="Search customer" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white placeholder-white/40" />
                </div>
                <div class="lg:col-span-3">
                    <label class="text-xs text-white/70">Invoice status</label>
                    <select class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white">
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
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-white">Invoice register</p>
                    <p class="text-xs text-white/60">Tracks VAT categories, fiscal device references, and QR payloads.</p>
                </div>
                <span class="text-xs text-white/50">Total invoices: 0</span>
            </div>

            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full text-sm text-white/80">
                    <thead class="text-xs uppercase text-white/50 border-b border-white/10">
                        <tr>
                            <th class="py-3 text-left">Invoice #</th>
                            <th class="py-3 text-left">Customer</th>
                            <th class="py-3 text-left">Posting Date</th>
                            <th class="py-3 text-left">Status</th>
                            <th class="py-3 text-left">VAT Category</th>
                            <th class="py-3 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $invoice)
                            <tr class="border-b border-white/5">
                                <td class="py-4">{{ $invoice->invoice_no }}</td>
                                <td class="py-4">{{ $invoice->customer?->name }}</td>
                                <td class="py-4">{{ optional($invoice->posting_date)->format('Y-m-d') }}</td>
                                <td class="py-4"><span class="rounded-full bg-emerald-500/20 text-emerald-200 px-2 py-1 text-xs">{{ $invoice->status }}</span></td>
                                <td class="py-4">{{ $invoice->vat_category }}</td>
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
                        <tr class="border-b border-white/5">
                            <td class="py-4">SI-00001</td>
                            <td class="py-4">Bluewave Retailers</td>
                            <td class="py-4">2026-01-15</td>
                            <td class="py-4"><span class="rounded-full bg-emerald-500/20 text-emerald-200 px-2 py-1 text-xs">Submitted</span></td>
                            <td class="py-4">VAT_STD</td>
                            <td class="py-4 text-right">ZIG 12,450.00</td>
                        </tr>
                        <tr>
                            <td class="py-4 text-white/50" colspan="6">No more invoices. Create a new sales invoice to begin.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-erp.section>

        <x-modal name="cancel-sales" maxWidth="lg">
            <div class="bg-slate-950 text-white p-6">
                <h3 class="text-lg font-semibold">Cancel sales invoice</h3>
                <p class="text-sm text-white/70 mt-2">Provide a reason and confirm cancellation. This action creates a reversal entry.</p>
                <textarea class="mt-4 w-full rounded-xl bg-white/10 border border-white/10 text-white" rows="4" placeholder="Reason for cancellation"></textarea>
                <div class="mt-6 flex justify-end gap-3">
                    <x-erp.action-button variant="muted" x-on:click="$dispatch('close-modal', 'cancel-sales')">Keep Invoice</x-erp.action-button>
                    <x-erp.action-button variant="danger">Confirm Cancel</x-erp.action-button>
                </div>
            </div>
        </x-modal>

        <x-modal name="import-sales" maxWidth="lg">
            <div class="bg-slate-950 text-white p-6">
                <h3 class="text-lg font-semibold">Import sales invoices</h3>
                <p class="text-sm text-white/70 mt-2">Upload CSV or Excel file with VAT-compliant invoice data.</p>
                <div class="mt-4 border border-dashed border-white/20 rounded-xl p-6 text-center text-sm text-white/70">
                    Drag and drop file here, or click to browse.
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <x-erp.action-button variant="muted" x-on:click="$dispatch('close-modal', 'import-sales')">Close</x-erp.action-button>
                    <x-erp.action-button variant="primary">Upload</x-erp.action-button>
                </div>
            </div>
        </x-modal>
    </x-erp.page>
</x-app-layout>
