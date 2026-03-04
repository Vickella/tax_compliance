@extends('layouts.erp')

@section('page_title', 'QPD Payment')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-semibold text-white">QPD Payment {{ $payment->payment_no }}</h2>
            <p class="text-sm text-slate-400">Q{{ $payment->quarter }} {{ $payment->tax_year }} • {{ $payment->payment_date->format('d M Y') }}</p>
        </div>
        <div class="flex gap-3">
            @if($payment->status === 'DRAFT')
            <form method="POST" action="{{ route('modules.tax.qpd.submit', $payment) }}" class="inline">
                @csrf
                <button type="submit" 
                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors">
                    Submit Payment
                </button>
            </form>
            @endif
            <a href="{{ route('tax.qpd.pdf', $payment) }}" 
               class="px-4 py-2 bg-white/5 hover:bg-white/10 ring-1 ring-white/10 rounded-lg transition-colors">
                Download ITF12B
            </a>
        </div>
    </div>

    {{-- Status Banner --}}
    <div class="mb-6 p-4 rounded-lg ring-1 
        @if($payment->status === 'PAID') bg-emerald-600/20 ring-emerald-600/30
        @elseif($payment->status === 'SUBMITTED') bg-blue-600/20 ring-blue-600/30
        @else bg-yellow-600/20 ring-yellow-600/30
        @endif">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="text-sm font-medium">
                    Status: <span class="font-bold">{{ $payment->status }}</span>
                </span>
            </div>
            @if($payment->status === 'PAID' && $payment->journalEntry)
            <span class="text-xs">
                Journal: <a href="{{ route('modules.accounting.journals.show', $payment->journalEntry) }}" class="text-indigo-400 hover:underline">
                    {{ $payment->journalEntry->entry_no }}
                </a>
            </span>
            @endif
        </div>
    </div>

    {{-- Payment Details --}}
    <div class="bg-black/20 rounded-xl ring-1 ring-white/10 overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Left Column --}}
                <div class="space-y-4">
                    <div>
                        <div class="text-xs text-slate-400">Payment Number</div>
                        <div class="text-lg font-semibold text-white font-mono">{{ $payment->payment_no }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-400">Tax Year</div>
                        <div class="text-white">{{ $payment->tax_year }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-400">Quarter</div>
                        <div class="text-white">Q{{ $payment->quarter }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-400">Payment Date</div>
                        <div class="text-white">{{ $payment->payment_date->format('d/m/Y') }}</div>
                    </div>
                </div>

                {{-- Right Column --}}
                <div class="space-y-4">
                    <div>
                        <div class="text-xs text-slate-400">Due Date</div>
                        <div class="text-white">{{ $payment->due_date->format('d/m/Y') }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-400">Payment Method</div>
                        <div class="text-white">{{ $payment->payment_method }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-400">Reference</div>
                        <div class="text-white">{{ $payment->reference ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-400">Created By</div>
                        <div class="text-white">{{ $payment->createdBy?->name ?? 'System' }}</div>
                    </div>
                </div>
            </div>

            <div class="border-t border-white/10 my-6"></div>

            {{-- Amount Summary --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-black/40 rounded-lg p-4">
                    <div class="text-xs text-slate-400">Estimated Annual Tax</div>
                    <div class="text-lg font-semibold text-white">${{ number_format($payment->estimated_annual_tax, 2) }}</div>
                </div>
                <div class="bg-black/40 rounded-lg p-4">
                    <div class="text-xs text-slate-400">Percentage Applied</div>
                    <div class="text-lg font-semibold text-white">{{ $payment->percentage_applied }}%</div>
                </div>
                <div class="bg-indigo-600/20 rounded-lg p-4">
                    <div class="text-xs text-indigo-300">Payment Amount</div>
                    <div class="text-xl font-bold text-indigo-400">${{ number_format($payment->amount, 2) }}</div>
                </div>
            </div>

            @if($payment->metadata['notes'] ?? false)
            <div class="mt-6 p-4 bg-black/40 rounded-lg">
                <div class="text-xs text-slate-400 mb-1">Notes</div>
                <p class="text-white">{{ $payment->metadata['notes'] }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection