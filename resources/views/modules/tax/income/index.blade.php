@extends('layouts.app')

@section('page_title', 'Income Tax Returns')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-semibold text-white">Income Tax Returns</h2>
            <p class="text-sm text-slate-400">ITF12C - Annual Income Tax Returns</p>
        </div>
        <div class="flex gap-3">
            <form method="GET" class="flex gap-2">
                <select name="tax_year" onchange="this.form.submit()" 
                        class="px-3 py-2 rounded-lg bg-black/30 text-white border border-white/10 focus:border-indigo-500 outline-none">
                    @for($y = now()->year; $y >= now()->year - 5; $y--)
                        <option value="{{ $y }}" {{ request('tax_year', now()->year) == $y ? 'selected' : '' }}>
                            Year {{ $y }}
                        </option>
                    @endfor
                </select>
            </form>
            <a href="{{ route('modules.tax.income.create') }}" 
               class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                New Return
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-4">
            <div class="text-xs text-slate-400">Total Returns</div>
            <div class="text-2xl font-bold text-white">{{ $stats['total_returns'] ?? 0 }}</div>
        </div>
        <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-4">
            <div class="text-xs text-slate-400">Submitted</div>
            <div class="text-2xl font-bold text-emerald-400">{{ $stats['submitted'] ?? 0 }}</div>
        </div>
        <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-4">
            <div class="text-xs text-slate-400">Draft</div>
            <div class="text-2xl font-bold text-amber-400">{{ $stats['draft'] ?? 0 }}</div>
        </div>
        <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-4">
            <div class="text-xs text-slate-400">Total Tax</div>
            <div class="text-2xl font-bold text-white">${{ number_format($stats['total_tax'] ?? 0, 2) }}</div>
        </div>
    </div>

    {{-- Returns Table --}}
    <div class="bg-black/20 rounded-xl ring-1 ring-white/10 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-white/5">
                <tr>
                    <th class="px-4 py-3 text-left text-slate-300">Return No</th>
                    <th class="px-4 py-3 text-left text-slate-300">Tax Year</th>
                    <th class="px-4 py-3 text-right text-slate-300">Taxable Income</th>
                    <th class="px-4 py-3 text-right text-slate-300">Tax Payable</th>
                    <th class="px-4 py-3 text-right text-slate-300">QPD Paid</th>
                    <th class="px-4 py-3 text-right text-slate-300">Balance Due</th>
                    <th class="px-4 py-3 text-center text-slate-300">Status</th>
                    <th class="px-4 py-3 text-right text-slate-300">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/10">
                @forelse($returns as $return)
                <tr class="hover:bg-white/5">
                    <td class="px-4 py-3 font-mono">{{ $return->return_no }}</td>
                    <td class="px-4 py-3">{{ $return->tax_year }}</td>
                    <td class="px-4 py-3 text-right">${{ number_format($return->taxable_income, 2) }}</td>
                    <td class="px-4 py-3 text-right">${{ number_format($return->total_tax, 2) }}</td>
                    <td class="px-4 py-3 text-right">${{ number_format($return->qpd_paid, 2) }}</td>
                    <td class="px-4 py-3 text-right font-semibold {{ $return->balance_due > 0 ? 'text-amber-400' : 'text-emerald-400' }}">
                        ${{ number_format($return->balance_due, 2) }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-2 py-1 rounded-full text-xs 
                            @if($return->status === 'SUBMITTED') bg-green-600/20 text-green-300
                            @elseif($return->status === 'APPROVED') bg-blue-600/20 text-blue-300
                            @elseif($return->status === 'DRAFT') bg-yellow-600/20 text-yellow-300
                            @else bg-slate-600/20 text-slate-300
                            @endif">
                            {{ $return->status }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('modules.tax.income.show', $return) }}" 
                           class="text-indigo-400 hover:text-indigo-300">View</a>
                        <a href="{{ route('modules.tax.income.pdf', $return) }}" 
                           class="text-slate-400 hover:text-slate-300">PDF</a>
                        <a href="{{ route('modules.tax.income.csv', $return) }}" 
                           class="text-slate-400 hover:text-slate-300">CSV</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-8 text-center text-slate-400">
                        No income tax returns found.
                        <a href="{{ route('modules.tax.income.create') }}" class="text-indigo-400 hover:underline">Create your first return</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $returns->links() }}
    </div>
</div>
@endsection