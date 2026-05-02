{{-- resources/views/modules/purchases/invoices/show.blade.php --}}
@extends('layouts.app')

@section('page_title', 'Purchase Invoice ' . $invoice->invoice_no)

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    {{-- Header with actions --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-white">Purchase Invoice {{ $invoice->invoice_no }}</h1>
            <div class="flex items-center gap-2 mt-1">
                <span class="text-sm text-slate-400">{{ $invoice->supplier->name ?? '-' }}</span>
                <span class="text-xs text-slate-600">•</span>
                <span class="px-2 py-0.5 rounded-full text-xs 
                    @if($invoice->status === 'SUBMITTED') bg-blue-600/20 text-blue-300
                    @elseif($invoice->status === 'DRAFT') bg-yellow-600/20 text-yellow-300
                    @elseif($invoice->status === 'CANCELLED') bg-rose-600/20 text-rose-300
                    @else bg-slate-600/20 text-slate-300
                    @endif">
                    {{ $invoice->status }}
                </span>
            </div>
        </div>
        <div class="flex gap-2">
            @if($invoice->status === 'DRAFT')
                <a href="{{ route('modules.purchases.invoices.edit', $invoice) }}" 
                   class="px-4 py-2 rounded-lg bg-white/5 hover:bg-white/10 ring-1 ring-white/10 text-sm transition-colors">
                    Edit
                </a>
                <form method="POST" action="{{ route('modules.purchases.invoices.submit', $invoice) }}" class="inline">
                    @csrf
                    <button type="submit" 
                            class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium transition-colors">
                        Submit
                    </button>
                </form>
            @elseif($invoice->status === 'SUBMITTED')
                <form method="POST" action="{{ route('modules.purchases.invoices.cancel', $invoice) }}" class="inline">
                    @csrf
                    <button type="submit" 
                            class="px-4 py-2 rounded-lg bg-rose-600/20 hover:bg-rose-600/30 text-rose-300 text-sm transition-colors ring-1 ring-rose-600/30">
                        Cancel
                    </button>
                </form>
            @endif
            <a href="{{ route('modules.purchases.invoices.index') }}" 
               class="px-4 py-2 rounded-lg bg-white/5 hover:bg-white/10 ring-1 ring-white/10 text-sm transition-colors">
                Back
            </a>
        </div>
    </div>

    {{-- Success message --}}
    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-emerald-600/20 ring-1 ring-emerald-600/30 text-emerald-300">
            {{ session('success') }}
        </div>
    @endif

    {{-- Error message --}}
    @if(session('error'))
        <div class="mb-6 p-4 rounded-lg bg-rose-600/20 ring-1 ring-rose-600/30 text-rose-300">
            {{ session('error') }}
        </div>
    @endif

    {{-- Main content grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        {{-- Header details card --}}
        <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-5 lg:col-span-2">
            <h3 class="text-sm font-semibold text-white mb-3">Invoice Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <div class="text-xs text-slate-400 mb-1">Posting Date</div>
                    <div class="text-sm text-white">{{ $invoice->posting_date?->format('Y-m-d') ?? '-' }}</div>
                </div>
                <div>
                    <div class="text-xs text-slate-400 mb-1">Due Date</div>
                    <div class="text-sm text-white">{{ $invoice->due_date?->format('Y-m-d') ?? '-' }}</div>
                </div>
                <div>
                    <div class="text-xs text-slate-400 mb-1">Currency</div>
                    <div class="text-sm text-white">{{ $invoice->currency }}</div>
                </div>
                <div>
                    <div class="text-xs text-slate-400 mb-1">Exchange Rate</div>
                    <div class="text-sm text-white">{{ number_format($invoice->exchange_rate, 6) }}</div>
                </div>
                <div>
                    <div class="text-xs text-slate-400 mb-1">Supplier Invoice No.</div>
                    <div class="text-sm text-white">{{ $invoice->supplier_invoice_no ?? '-' }}</div>
                </div>
                <div>
                    <div class="text-xs text-slate-400 mb-1">Input Tax Document</div>
                    <div class="text-sm text-white">{{ $invoice->input_tax_document_ref ?? '-' }}</div>
                </div>
                <div>
                    <div class="text-xs text-slate-400 mb-1">Bill of Entry</div>
                    <div class="text-sm text-white">{{ $invoice->bill_of_entry_ref ?? '-' }}</div>
                </div>
                <div class="md:col-span-2">
                    <div class="text-xs text-slate-400 mb-1">Remarks</div>
                    <div class="text-sm text-white">{{ $invoice->remarks ?? '-' }}</div>
                </div>
            </div>
        </div>

        {{-- Totals card --}}
        <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-5">
            <h3 class="text-sm font-semibold text-white mb-3">Invoice Totals</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center pb-2 border-b border-white/10">
                    <span class="text-sm text-slate-400">Subtotal</span>
                    <span class="text-lg font-semibold text-white">
                        {{ number_format((float)$invoice->subtotal, 2) }} {{ $invoice->currency }}
                    </span>
                </div>
                <div class="flex justify-between items-center pb-2 border-b border-white/10">
                    <span class="text-sm text-slate-400">VAT</span>
                    <span class="text-lg font-semibold text-amber-400">
                        {{ number_format((float)$invoice->vat_amount, 2) }} {{ $invoice->currency }}
                    </span>
                </div>
                <div class="flex justify-between items-center pt-2">
                    <span class="text-base font-semibold text-white">Total</span>
                    <span class="text-2xl font-bold text-indigo-400">
                        {{ number_format((float)$invoice->total, 2) }} {{ $invoice->currency }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Lines table --}}
    <div class="bg-black/20 rounded-xl ring-1 ring-white/10 overflow-hidden">
        <div class="px-5 py-4 border-b border-white/10">
            <h3 class="text-sm font-semibold text-white">Invoice Lines</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-white/5">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Item</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Warehouse</th>
                        <th class="px-5 py-3 text-right text-xs font-medium text-slate-300 uppercase tracking-wider">Quantity</th>
                        <th class="px-5 py-3 text-right text-xs font-medium text-slate-300 uppercase tracking-wider">Unit Price</th>
                        <th class="px-5 py-3 text-right text-xs font-medium text-slate-300 uppercase tracking-wider">Amount</th>
                        <th class="px-5 py-3 text-right text-xs font-medium text-slate-300 uppercase tracking-wider">VAT</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                    @forelse($invoice->lines as $line)
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="px-5 py-4">
                            <div class="font-medium text-white">{{ $line->item->name ?? 'N/A' }}</div>
                            @if($line->item && $line->item->sku)
                                <div class="text-xs text-slate-400 mt-0.5">SKU: {{ $line->item->sku }}</div>
                            @endif
                            @if($line->description)
                                <div class="text-xs text-slate-500 mt-1">{{ $line->description }}</div>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-slate-300">{{ $line->warehouse->name ?? '-' }}</td>
                        <td class="px-5 py-4 text-right text-slate-300">{{ number_format((float)$line->qty, 4) }}</td>
                        <td class="px-5 py-4 text-right text-slate-300">{{ number_format((float)$line->rate, 4) }}</td>
                        <td class="px-5 py-4 text-right text-slate-300">{{ number_format((float)$line->amount, 2) }}</td>
                        <td class="px-5 py-4 text-right text-slate-300">{{ number_format((float)$line->vat_amount, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-slate-400">
                            No lines found for this invoice.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-white/5 border-t border-white/10">
                    <tr>
                        <td colspan="4" class="px-5 py-3 text-right font-semibold text-slate-300">Totals:</td>
                        <td class="px-5 py-3 text-right font-semibold text-white">{{ number_format($invoice->lines->sum('amount'), 2) }}</td>
                        <td class="px-5 py-3 text-right font-semibold text-white">{{ number_format($invoice->lines->sum('vat_amount'), 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Footer with timestamps --}}
    <div class="mt-6 flex justify-between text-xs text-slate-500 border-t border-white/10 pt-4">
        <div>
            <span class="text-slate-400">Created:</span> 
            {{ $invoice->created_at?->format('d M Y H:i') }} 
            @if($invoice->creator)
                by {{ $invoice->creator->name }}
            @endif
        </div>
        @if($invoice->submitted_at)
        <div>
            <span class="text-slate-400">Submitted:</span> 
            {{ $invoice->submitted_at->format('d M Y H:i') }}
            @if($invoice->submitter)
                by {{ $invoice->submitter->name }}
            @endif
        </div>
        @endif
        @if($invoice->journal_entry_id)
        <div>
            <span class="text-slate-400">Journal:</span>
            <a href="{{ route('modules.accounting.journals.show', $invoice->journal_entry_id) }}" 
               class="text-indigo-400 hover:text-indigo-300">
                View Entry
            </a>
        </div>
        @endif
    </div>
</div>
@endsection