@extends('layouts.app')

@section('page_title', 'Income Tax Return')

@section('content')
<div class="max-w-7xl mx-auto">
    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-semibold text-white">Income Tax Return {{ $return->return_no }}</h2>
            <p class="text-sm text-slate-400">Tax Year {{ $return->tax_year }} • Filed {{ $return->filing_date->format('d M Y') }}</p>
        </div>
        <div class="flex gap-3">
            @if($return->status === 'DRAFT')
            <a href="{{ route('modules.tax.income.edit', $return) }}" 
               class="px-4 py-2 bg-white/10 hover:bg-white/15 ring-1 ring-white/10 rounded-lg transition-colors">
                Edit Return
            </a>
            <form method="POST" action="{{ route('modules.tax.income.submit', $return) }}" class="inline">
                @csrf
                <button type="submit" 
                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors">
                    Submit Return
                </button>
            </form>
            @endif
            <a href="{{ route('modules.tax.income.pdf', $return) }}" 
               class="px-4 py-2 bg-white/5 hover:bg-white/10 ring-1 ring-white/10 rounded-lg transition-colors">
                Download PDF
            </a>
            <a href="{{ route('modules.tax.income.csv', $return) }}" 
               class="px-4 py-2 bg-white/5 hover:bg-white/10 ring-1 ring-white/10 rounded-lg transition-colors">
                Export CSV
            </a>
        </div>
    </div>

    {{-- Status Banner --}}
    <div class="mb-6 p-4 rounded-lg ring-1 
        @if($return->status === 'APPROVED') bg-green-600/20 ring-green-600/30
        @elseif($return->status === 'SUBMITTED') bg-blue-600/20 ring-blue-600/30
        @else bg-yellow-600/20 ring-yellow-600/30
        @endif">
        <div class="flex items-center gap-3">
            <span class="text-sm font-medium">
                Status: <span class="font-bold">{{ $return->status }}</span>
            </span>
            @if($return->status === 'DRAFT')
            <span class="text-xs opacity-80">This return has not been submitted to ZIMRA yet</span>
            @elseif($return->status === 'SUBMITTED')
            <span class="text-xs opacity-80">Submitted on {{ $return->submitted_at?->format('d M Y H:i') }}</span>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left Column - Summary Cards --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-5">
                <h3 class="text-sm font-semibold text-white mb-4">Tax Summary</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Tax Year</span>
                        <span class="text-white font-semibold">{{ $return->tax_year }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Return No</span>
                        <span class="text-white font-mono">{{ $return->return_no }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Filing Date</span>
                        <span class="text-white">{{ $return->filing_date->format('d/m/Y') }}</span>
                    </div>
                    <div class="border-t border-white/10 my-3"></div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Taxable Income</span>
                        <span class="text-white font-bold">${{ number_format($return->taxable_income, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Tax Rate</span>
                        <span class="text-white">{{ $return->tax_rate }}%</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Income Tax</span>
                        <span class="text-white">${{ number_format($return->income_tax, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">AIDS Levy</span>
                        <span class="text-white">${{ number_format($return->aids_levy, 2) }}</span>
                    </div>
                    <div class="border-t border-white/10 my-3"></div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Total Tax</span>
                        <span class="text-indigo-400 font-bold text-lg">${{ number_format($return->total_tax, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">QPD Paid</span>
                        <span class="text-emerald-400">${{ number_format($return->qpd_paid, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Balance Due</span>
                        <span class="{{ $return->balance_due > 0 ? 'text-amber-400' : 'text-emerald-400' }} font-bold">
                            ${{ number_format($return->balance_due, 2) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-5">
                <h3 class="text-sm font-semibold text-white mb-4">Assessed Losses</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Brought Forward</span>
                        <span class="text-white">${{ number_format($return->assessed_loss_bf, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Carried Forward</span>
                        <span class="text-white">${{ number_format($return->assessed_loss_cf, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column - Detailed Breakdowns --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Income Breakdown --}}
            <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-5">
                <h3 class="text-sm font-semibold text-white mb-3">Income Breakdown</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-white/5">
                            <tr>
                                <th class="px-3 py-2 text-left text-slate-300">Code</th>
                                <th class="px-3 py-2 text-left text-slate-300">Account Name</th>
                                <th class="px-3 py-2 text-right text-slate-300">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            @foreach($return->metadata['income_breakdown'] ?? [] as $item)
                            <tr>
                                <td class="px-3 py-2 font-mono">{{ $item['code'] }}</td>
                                <td class="px-3 py-2">{{ $item['name'] }}</td>
                                <td class="px-3 py-2 text-right">${{ number_format($item['amount'], 2) }}</td>
                            </tr>
                            @endforeach
                            <tr class="bg-white/5 font-semibold">
                                <td colspan="2" class="px-3 py-2 text-right">Total Income</td>
                                <td class="px-3 py-2 text-right">${{ number_format($return->total_income, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Expense Breakdown --}}
            <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-5">
                <h3 class="text-sm font-semibold text-white mb-3">Expense Breakdown</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-white/5">
                            <tr>
                                <th class="px-3 py-2 text-left text-slate-300">Code</th>
                                <th class="px-3 py-2 text-left text-slate-300">Account Name</th>
                                <th class="px-3 py-2 text-right text-slate-300">Total</th>
                                <th class="px-3 py-2 text-right text-slate-300">Deductible %</th>
                                <th class="px-3 py-2 text-right text-slate-300">Deductible</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            @foreach($return->metadata['expense_breakdown'] ?? [] as $item)
                            <tr>
                                <td class="px-3 py-2 font-mono">{{ $item['code'] }}</td>
                                <td class="px-3 py-2">{{ $item['name'] }}</td>
                                <td class="px-3 py-2 text-right">${{ number_format($item['amount'], 2) }}</td>
                                <td class="px-3 py-2 text-right">{{ $item['deductible_percent'] }}%</td>
                                <td class="px-3 py-2 text-right">${{ number_format($item['deductible'], 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Add Backs --}}
            @if(!empty($return->metadata['addback_breakdown']))
            <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-5">
                <h3 class="text-sm font-semibold text-white mb-3">Non-Deductible Add Backs</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-white/5">
                            <tr>
                                <th class="px-3 py-2 text-left text-slate-300">Code</th>
                                <th class="px-3 py-2 text-left text-slate-300">Account Name</th>
                                <th class="px-3 py-2 text-right text-slate-300">Amount</th>
                                <th class="px-3 py-2 text-left text-slate-300">Reason</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            @foreach($return->metadata['addback_breakdown'] as $item)
                            <tr>
                                <td class="px-3 py-2 font-mono">{{ $item['code'] }}</td>
                                <td class="px-3 py-2">{{ $item['name'] }}</td>
                                <td class="px-3 py-2 text-right text-amber-400">${{ number_format($item['amount'], 2) }}</td>
                                <td class="px-3 py-2 text-slate-300">{{ $item['reason'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            {{-- Notes --}}
            @if(!empty($return->metadata['notes']))
            <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-5">
                <h3 class="text-sm font-semibold text-white mb-3">Notes</h3>
                <p class="text-slate-300">{{ $return->metadata['notes'] }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection