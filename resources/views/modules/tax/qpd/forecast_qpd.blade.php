@extends('layouts.app')

@section('page_title', 'QPD Estimates from Forecast')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-semibold text-white">QPD Estimates - {{ $year }}</h2>
            <p class="text-sm text-slate-400">Quarterly payment projections based on forecast</p>
        </div>
        <a href="{{ route('modules.tax.qpd.forecast.dashboard', ['year' => $year]) }}" 
           class="px-4 py-2 bg-white/10 hover:bg-white/15 rounded-lg">
            ← Back to Dashboard
        </a>
    </div>

    {{-- Summary Card --}}
    <div class="bg-indigo-600/20 rounded-xl ring-1 ring-indigo-500/30 p-6 mb-6">
        <div class="text-center">
            <div class="text-sm text-slate-400 mb-1">Estimated Annual Tax Liability</div>
            <div class="text-4xl font-bold text-indigo-400">${{ number_format($qpdEstimates[1]['amount'] * 10, 2) }}</div>
            <div class="text-xs text-slate-500 mt-2">Based on forecasted financials</div>
        </div>
    </div>

    {{-- QPD Payment Schedule --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        @foreach($qpdEstimates as $q => $estimate)
        <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-5">
            <div class="flex justify-between items-start mb-3">
                <div>
                    <div class="text-xs text-slate-400">Quarter {{ $q }}</div>
                    <div class="text-2xl font-bold text-white">${{ number_format($estimate['amount'], 2) }}</div>
                </div>
                <span class="px-2 py-1 rounded-full text-xs bg-blue-600/20 text-blue-300">
                    {{ $estimate['percentage'] }}%
                </span>
            </div>
            <div class="text-sm text-slate-400">Due Date</div>
            <div class="text-lg font-semibold text-amber-400">{{ $estimate['formatted_due'] }}</div>
            <div class="mt-3 text-xs text-slate-500">
                {{ $estimate['percentage'] }}% of annual tax
            </div>
        </div>
        @endforeach
    </div>

    {{-- Payment Schedule Table --}}
    <div class="bg-black/20 rounded-xl ring-1 ring-white/10 overflow-hidden">
        <div class="p-4 border-b border-white/10">
            <h3 class="text-sm font-semibold text-white">QPD Payment Schedule</h3>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-white/5">
                <tr>
                    <th class="px-4 py-3 text-left text-slate-300">Quarter</th>
                    <th class="px-4 py-3 text-left text-slate-300">Due Date</th>
                    <th class="px-4 py-3 text-right text-slate-300">Percentage</th>
                    <th class="px-4 py-3 text-right text-slate-300">Amount</th>
                    <th class="px-4 py-3 text-center text-slate-300">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/10">
                @foreach($qpdEstimates as $q => $estimate)
                <tr class="hover:bg-white/5">
                    <td class="px-4 py-3 font-medium">Quarter {{ $q }}</td>
                    <td class="px-4 py-3">{{ $estimate['formatted_due'] }}</td>
                    <td class="px-4 py-3 text-right">{{ $estimate['percentage'] }}%</td>
                    <td class="px-4 py-3 text-right font-semibold text-white">${{ number_format($estimate['amount'], 2) }}</td>
                    <td class="px-4 py-3 text-center">
                        @php
                            $isPast = now()->gt($estimate['due_date']);
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs 
                            @if($isPast) bg-amber-600/20 text-amber-300
                            @else bg-emerald-600/20 text-emerald-300
                            @endif">
                            {{ $isPast ? 'PAST DUE' : 'UPCOMING' }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Action Buttons --}}
    <div class="flex justify-end gap-3 mt-6">
        <a href="{{ route('modules.tax.qpd.create', ['tax_year' => $year]) }}" 
           class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">
            Make QPD Payment
        </a>
    </div>
</div>
@endsection