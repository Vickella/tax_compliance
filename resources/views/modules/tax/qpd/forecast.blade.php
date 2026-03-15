@extends('layouts.app')

@section('page_title', 'QPD Forecast')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-semibold text-white">QPD Forecast - {{ $taxYear }}</h2>
            <p class="text-sm text-slate-400">ITF12B - Provisional Tax Projections</p>
        </div>
        <div class="flex gap-3">
            <form method="GET" class="flex gap-2">
                <select name="tax_year" onchange="this.form.submit()" 
                        class="px-3 py-2 rounded-lg bg-black/30 text-white border border-white/10 focus:border-indigo-500 outline-none">
                    @for($y = now()->year; $y >= now()->year - 2; $y--)
                        <option value="{{ $y }}" {{ $taxYear == $y ? 'selected' : '' }}>
                            Year {{ $y }}
                        </option>
                    @endfor
                </select>
            </form>
            <a href="{{ route('modules.tax.qpd.forecast.csv', ['tax_year' => $taxYear]) }}" 
               class="px-4 py-2 bg-white/10 hover:bg-white/15 ring-1 ring-white/10 rounded-lg transition-colors">
                Download CSV
            </a>
        </div>
    </div>

    {{-- Forecast Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        @foreach($forecast['quarters'] as $q => $details)
        <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-4">
            <div class="text-xs text-slate-400 mb-1">Quarter {{ $q }}</div>
            <div class="text-2xl font-bold text-white">${{ number_format($details['qpd_amount'], 2) }}</div>
            <div class="flex justify-between text-xs mt-2">
                <span class="text-slate-400">Due:</span>
                <span class="text-amber-400">{{ \Carbon\Carbon::parse($details['due_date'])->format('d M Y') }}</span>
            </div>
            <div class="flex justify-between text-xs">
                <span class="text-slate-400">% of Annual:</span>
                <span class="text-white">{{ $details['qpd_percentage'] }}%</span>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Detailed Forecast Table --}}
    <div class="bg-black/20 rounded-xl ring-1 ring-white/10 overflow-hidden">
        <div class="p-4 border-b border-white/10">
            <h3 class="text-sm font-semibold text-white">Quarterly Breakdown</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-white/5">
                    <tr>
                        <th class="px-4 py-3 text-left text-slate-300">Quarter</th>
                        <th class="px-4 py-3 text-left text-slate-300">Due Date</th>
                        <th class="px-4 py-3 text-right text-slate-300">Percentage</th>
                        <th class="px-4 py-3 text-right text-slate-300">Amount</th>
                        <th class="px-4 py-3 text-right text-slate-300">Paid</th>
                        <th class="px-4 py-3 text-right text-slate-300">Balance</th>
                        <th class="px-4 py-3 text-center text-slate-300">Status</th>
                        <th class="px-4 py-3 text-right text-slate-300">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                    @foreach($forecast['quarters'] as $q => $details)
                    <tr>
                        <td class="px-4 py-3 font-medium">Quarter {{ $q }}</td>
                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($details['due_date'])->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-right">{{ $details['qpd_percentage'] }}%</td>
                        <td class="px-4 py-3 text-right">${{ number_format($details['qpd_amount'], 2) }}</td>
                        <td class="px-4 py-3 text-right text-emerald-400">${{ number_format($details['paid_amount'] ?? 0, 2) }}</td>
                        <td class="px-4 py-3 text-right {{ ($details['balance_due'] ?? 0) > 0 ? 'text-amber-400' : 'text-emerald-400' }}">
                            ${{ number_format($details['balance_due'] ?? 0, 2) }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-2 py-1 rounded-full text-xs 
                                @if($details['is_overdue']) bg-rose-600/20 text-rose-300
                                @elseif(($details['paid_amount'] ?? 0) > 0) bg-emerald-600/20 text-emerald-300
                                @else bg-amber-600/20 text-amber-300
                                @endif">
                                {{ ($details['paid_amount'] ?? 0) > 0 ? 'PAID' : ($details['is_overdue'] ? 'OVERDUE' : 'PENDING') }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            @if(($details['balance_due'] ?? 0) > 0)
                            <a href="{{ route('modules.tax.qpd.create', ['tax_year' => $taxYear, 'quarter' => $q]) }}" 
                               class="text-indigo-400 hover:text-indigo-300 text-sm">
                                Make Payment →
                            </a>
                            @else
                            <span class="text-slate-500 text-sm">Paid</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-white/5">
                    <tr>
                        <td colspan="3" class="px-4 py-3 text-right font-semibold text-slate-300">Total:</td>
                        <td class="px-4 py-3 text-right font-semibold text-white">${{ number_format($forecast['total_qpd'], 2) }}</td>
                        <td colspan="4"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Summary Information --}}
    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-4">
            <div class="text-xs text-slate-400">Estimated Annual Tax</div>
            <div class="text-2xl font-bold text-white">${{ number_format($forecast['estimated_annual_tax'], 2) }}</div>
        </div>
        <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-4">
            <div class="text-xs text-slate-400">Total QPD Payments Due</div>
            <div class="text-2xl font-bold text-indigo-400">${{ number_format($forecast['total_qpd'], 2) }}</div>
        </div>
    </div>
</div>
@endsection