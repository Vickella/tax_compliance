@extends('layouts.app')

@section('page_title', 'Forecasted Trial Balance')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-semibold text-white">Forecasted Trial Balance - {{ $year }}</h2>
            <p class="text-sm text-slate-400">
                Based on actuals: {{ $forecastedTB['period_actuals'] }}
                ({{ $forecastedTB['months_elapsed'] }} months elapsed, 
                {{ $forecastedTB['months_remaining'] }} months to forecast)
            </p>
        </div>
        <a href="{{ route('modules.tax.qpd.forecast.dashboard', ['year' => $year]) }}" 
           class="px-4 py-2 bg-white/10 hover:bg-white/15 rounded-lg">
            ← Back to Dashboard
        </a>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-emerald-600/20 rounded-xl ring-1 ring-emerald-500/30 p-4">
            <div class="text-xs text-slate-400">Total Projected Income</div>
            <div class="text-2xl font-bold text-emerald-400">${{ number_format($forecastedTB['totals']['INCOME'] ?? 0, 2) }}</div>
        </div>
        <div class="bg-amber-600/20 rounded-xl ring-1 ring-amber-500/30 p-4">
            <div class="text-xs text-slate-400">Total Projected Expenses</div>
            <div class="text-2xl font-bold text-amber-400">${{ number_format($forecastedTB['totals']['EXPENSE'] ?? 0, 2) }}</div>
        </div>
        <div class="bg-indigo-600/20 rounded-xl ring-1 ring-indigo-500/30 p-4">
            <div class="text-xs text-slate-400">Projected Net Profit</div>
            <div class="text-2xl font-bold text-indigo-400">${{ number_format($forecastedTB['totals']['NET_PROFIT'] ?? 0, 2) }}</div>
        </div>
    </div>

    {{-- Trial Balance Table --}}
    <div class="bg-black/20 rounded-xl ring-1 ring-white/10 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-white/5">
                    <tr>
                        <th class="px-4 py-3 text-left text-slate-300">Account Code</th>
                        <th class="px-4 py-3 text-left text-slate-300">Account Name</th>
                        <th class="px-4 py-3 text-right text-slate-300">Type</th>
                        <th class="px-4 py-3 text-right text-slate-300">YTD Actual</th>
                        <th class="px-4 py-3 text-right text-slate-300">Monthly Avg</th>
                        <th class="px-4 py-3 text-right text-slate-300">Forecast Method</th>
                        <th class="px-4 py-3 text-right text-slate-300">Projected Dec</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                    @foreach($forecastedTB['accounts'] as $account)
                    <tr class="hover:bg-white/5">
                        <td class="px-4 py-3 font-mono">{{ $account['code'] }}</td>
                        <td class="px-4 py-3">{{ $account['name'] }}</td>
                        <td class="px-4 py-3 text-right">
                            <span class="px-2 py-1 rounded-full text-xs 
                                @if($account['type'] == 'INCOME') bg-emerald-600/20 text-emerald-300
                                @elseif($account['type'] == 'EXPENSE') bg-amber-600/20 text-amber-300
                                @else bg-slate-600/20 text-slate-300
                                @endif">
                                {{ $account['type'] }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">${{ number_format($account['actual_ytd'], 2) }}</td>
                        <td class="px-4 py-3 text-right">${{ number_format($account['monthly_avg'], 2) }}</td>
                        <td class="px-4 py-3 text-right">
                            @if($account['growth_rate'] > 0)
                                {{ ucfirst($account['forecast_method']) }} +{{ $account['growth_rate'] }}%
                            @else
                                {{ ucfirst($account['forecast_method']) }}
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right font-semibold text-white">${{ number_format($account['projected_dec'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection