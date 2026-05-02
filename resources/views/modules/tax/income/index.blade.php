@extends('layouts.app')

@section('page_title', 'Income Tax Returns')

@section('content')
<div class="max-w-7xl mx-auto">
    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-semibold text-white">Income Tax Returns</h2>
            <p class="text-sm text-slate-400">ITF12C - Annual Income Tax Returns</p>
        </div>
        <a href="{{ route('modules.tax.income.create', ['tax_year' => now()->year]) }}" 
           class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            New Return
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-5">
            <div class="text-3xl font-bold text-white">{{ $returns->total() }}</div>
            <div class="text-sm text-slate-400">Total Returns</div>
        </div>
        <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-5">
            <div class="text-3xl font-bold text-green-400">{{ $submittedCount }}</div>
            <div class="text-sm text-slate-400">Submitted</div>
        </div>
        <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-5">
            <div class="text-3xl font-bold text-yellow-400">{{ $draftCount }}</div>
            <div class="text-sm text-slate-400">Draft</div>
        </div>
    </div>

    {{-- Tax Year Summary --}}
    <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-5 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-white">Year {{ now()->year }}</h3>
            <a href="{{ route('modules.tax.income.create', ['tax_year' => now()->year]) }}" 
               class="text-sm text-indigo-400 hover:text-indigo-300 transition-colors">
                + New Return
            </a>
        </div>
        
        @if($returns->count() > 0)
            {{-- Returns Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-white/5">
                        <tr>
                            <th class="px-3 py-2 text-left text-slate-300">Return No</th>
                            <th class="px-3 py-2 text-left text-slate-300">Period</th>
                            <th class="px-3 py-2 text-right text-slate-300">Taxable Income</th>
                            <th class="px-3 py-2 text-right text-slate-300">Total Tax</th>
                            <th class="px-3 py-2 text-center text-slate-300">Status</th>
                            <th class="px-3 py-2 text-right text-slate-300">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        @foreach($returns as $return)
                        <tr class="hover:bg-white/5">
                            <td class="px-3 py-3 font-mono">{{ $return->return_no }}</td>
                            <td class="px-3 py-3">{{ $return->period_start ? $return->period_start->format('M Y') : $return->tax_year }}</td>
                            <td class="px-3 py-3 text-right">${{ number_format($return->taxable_income, 2) }}</td>
                            <td class="px-3 py-3 text-right">${{ number_format($return->total_tax, 2) }}</td>
                            <td class="px-3 py-3 text-center">
                                <span class="px-2 py-1 rounded text-xs 
                                    @if($return->status === 'DRAFT') bg-yellow-600/20 text-yellow-400
                                    @elseif($return->status === 'SUBMITTED') bg-blue-600/20 text-blue-400
                                    @elseif($return->status === 'APPROVED') bg-green-600/20 text-green-400
                                    @endif">
                                    {{ $return->status }}
                                </span>
                            </td>
                            <td class="px-3 py-3 text-right">
                                <a href="{{ route('modules.tax.income.show', $return) }}" 
                                   class="text-indigo-400 hover:text-indigo-300 transition-colors">
                                    View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            {{-- Pagination --}}
            <div class="mt-4">
                {{ $returns->links() }}
            </div>
        @else
            {{-- Empty State --}}
            <div class="text-center py-8">
                <svg class="w-16 h-16 mx-auto text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-slate-400 mb-1">No income tax returns found</p>
                <p class="text-sm text-slate-500">Create your first return for tax year {{ now()->year }}</p>
                <a href="{{ route('modules.tax.income.create', ['tax_year' => now()->year]) }}" 
                   class="inline-flex items-center gap-1 mt-3 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Create First Return
                </a>
            </div>
        @endif
    </div>

    {{-- Summary Card --}}
    <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-5">
        <div class="flex justify-between items-center">
            <span class="text-slate-400">Total Tax (All Returns)</span>
            <span class="text-2xl font-bold text-white">${{ number_format($totalTax, 2) }}</span>
        </div>
    </div>
</div>
@endsection