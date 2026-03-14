@extends('layouts.app')

@section('page_title', 'QPD Payments')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-semibold text-white">Provisional Tax Payments (QPD)</h2>
            <p class="text-sm text-slate-400">ITF12B - Quarterly Payment Dates</p>
        </div>
        <div class="flex gap-3">
            <form method="GET" class="flex gap-2">
                <select name="tax_year" onchange="this.form.submit()" 
                        class="px-3 py-2 rounded-lg bg-black/30 text-white border border-white/10 focus:border-indigo-500 outline-none">
                    @for($y = now()->year; $y >= now()->year - 2; $y--)
                        <option value="{{ $y }}" {{ ($taxYear ?? now()->year) == $y ? 'selected' : '' }}>
                            Year {{ $y }}
                        </option>
                    @endfor
                </select>
            </form>
            <a href="{{ route('modules.tax.qpd.forecast') }}" 
               class="px-4 py-2 bg-white/10 hover:bg-white/15 ring-1 ring-white/10 rounded-lg transition-colors">
                View Forecast
            </a>
        </div>
    </div>

    {{-- QPD Forecast Cards --}}
    @if(isset($forecast) && isset($forecast['quarters']))
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        @foreach($forecast['quarters'] as $q => $details)
        <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-4">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <div class="text-xs text-slate-400">Quarter {{ $q }}</div>
                    <div class="text-sm font-semibold text-white">Due: {{ \Carbon\Carbon::parse($details['due_date'])->format('d M') }}</div>
                </div>
                <span class="px-2 py-1 rounded-full text-xs 
                    @if($details['is_overdue']) bg-rose-600/20 text-rose-300
                    @elseif(($details['paid_amount'] ?? 0) > 0) bg-emerald-600/20 text-emerald-300
                    @else bg-amber-600/20 text-amber-300
                    @endif">
                    {{ ($details['paid_amount'] ?? 0) > 0 ? 'PAID' : ($details['is_overdue'] ? 'OVERDUE' : 'PENDING') }}
                </span>
            </div>
            <div class="mt-2 space-y-1">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-400">Amount:</span>
                    <span class="text-white font-semibold">${{ number_format($details['qpd_amount'], 2) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-400">Paid:</span>
                    <span class="text-emerald-400">${{ number_format($details['paid_amount'] ?? 0, 2) }}</span>
                </div>
                @if(($details['balance_due'] ?? 0) > 0)
                <div class="flex justify-between text-sm">
                    <span class="text-slate-400">Balance:</span>
                    <span class="text-amber-400">${{ number_format($details['balance_due'], 2) }}</span>
                </div>
                @endif
            </div>
            @if(($details['balance_due'] ?? 0) > 0 && !($details['is_overdue'] ?? false))
            <a href="{{ route('modules.tax.qpd.create', ['tax_year' => $taxYear, 'quarter' => $q]) }}" 
               class="mt-3 block w-full text-center px-3 py-1.5 bg-indigo-600/20 hover:bg-indigo-600/30 text-indigo-300 text-sm rounded-lg transition-colors">
                Make Payment
            </a>
            @endif
        </div>
        @endforeach
    </div>
    @endif

    {{-- Payments Made --}}
    <div class="mt-8">
        <h3 class="text-lg font-semibold text-white mb-4">Payment History</h3>
        <div class="bg-black/20 rounded-xl ring-1 ring-white/10 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-white/5">
                    <tr>
                        <th class="px-4 py-3 text-left text-slate-300">Payment No</th>
                        <th class="px-4 py-3 text-left text-slate-300">Quarter</th>
                        <th class="px-4 py-3 text-left text-slate-300">Payment Date</th>
                        <th class="px-4 py-3 text-right text-slate-300">Amount</th>
                        <th class="px-4 py-3 text-left text-slate-300">Method</th>
                        <th class="px-4 py-3 text-center text-slate-300">Status</th>
                        <th class="px-4 py-3 text-right text-slate-300">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                    @forelse($payments ?? [] as $payment)
                    <tr class="hover:bg-white/5">
                        <td class="px-4 py-3 font-mono">{{ $payment->payment_no ?? 'N/A' }}</td>
                        <td class="px-4 py-3">Q{{ $payment->quarter }}</td>
                        <td class="px-4 py-3">{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') : 'N/A' }}</td>
                        <td class="px-4 py-3 text-right font-semibold">${{ number_format($payment->amount ?? 0, 2) }}</td>
                        <td class="px-4 py-3">{{ $payment->payment_method ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-2 py-1 rounded-full text-xs 
                                @if($payment->status === 'PAID') bg-emerald-600/20 text-emerald-300
                                @elseif($payment->status === 'SUBMITTED') bg-blue-600/20 text-blue-300
                                @else bg-yellow-600/20 text-yellow-300
                                @endif">
                                {{ $payment->status ?? 'DRAFT' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <a href="{{ route('modules.tax.qpd.show', $payment) }}" class="text-indigo-400 hover:text-indigo-300">View</a>
                            <a href="{{ route('modules.tax.qpd.pdf', $payment) }}" class="text-slate-400 hover:text-slate-300">PDF</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-slate-400">
                            No QPD payments found for {{ $taxYear ?? now()->year }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection