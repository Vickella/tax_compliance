@extends('layouts.erp')

@section('page_title', 'VAT Returns')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-semibold text-white">VAT Returns</h2>
            <p class="text-sm text-slate-400">VAT7 - Monthly Value Added Tax Returns</p>
        </div>
        <a href="{{ route('modules.tax.vat.create') }}" 
           class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            New VAT Return
        </a>
    </div>

    {{-- Filters --}}
    <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-4 mb-6">
        <form method="GET" class="flex gap-4">
            <div>
                <label class="block text-xs text-slate-400 mb-1">Year</label>
                <select name="year" onchange="this.form.submit()" 
                        class="px-3 py-2 rounded-lg bg-black/30 text-white border border-white/10 focus:border-indigo-500 outline-none">
                    @for($y = now()->year; $y >= now()->year - 3; $y--)
                        <option value="{{ $y }}" {{ request('year', now()->year) == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-xs text-slate-400 mb-1">Status</label>
                <select name="status" onchange="this.form.submit()" 
                        class="px-3 py-2 rounded-lg bg-black/30 text-white border border-white/10 focus:border-indigo-500 outline-none">
                    <option value="">All</option>
                    <option value="DRAFT" {{ request('status') == 'DRAFT' ? 'selected' : '' }}>Draft</option>
                    <option value="SUBMITTED" {{ request('status') == 'SUBMITTED' ? 'selected' : '' }}>Submitted</option>
                    <option value="PAID" {{ request('status') == 'PAID' ? 'selected' : '' }}>Paid</option>
                </select>
            </div>
        </form>
    </div>

    {{-- VAT Returns Table --}}
    <div class="bg-black/20 rounded-xl ring-1 ring-white/10 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-white/5">
                <tr>
                    <th class="px-4 py-3 text-left text-slate-300">Return No</th>
                    <th class="px-4 py-3 text-left text-slate-300">Period</th>
                    <th class="px-4 py-3 text-right text-slate-300">Output VAT</th>
                    <th class="px-4 py-3 text-right text-slate-300">Input VAT</th>
                    <th class="px-4 py-3 text-right text-slate-300">VAT Payable</th>
                    <th class="px-4 py-3 text-center text-slate-300">Status</th>
                    <th class="px-4 py-3 text-right text-slate-300">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/10">
                @forelse($returns as $return)
                <tr class="hover:bg-white/5">
                    <td class="px-4 py-3 font-mono">{{ $return->return_no }}</td>
                    <td class="px-4 py-3">{{ $return->period_start->format('M Y') }}</td>
                    <td class="px-4 py-3 text-right">${{ number_format($return->output_vat, 2) }}</td>
                    <td class="px-4 py-3 text-right">${{ number_format($return->input_vat, 2) }}</td>
                    <td class="px-4 py-3 text-right font-semibold 
                        {{ $return->vat_payable > 0 ? 'text-amber-400' : 'text-emerald-400' }}">
                        ${{ number_format($return->vat_payable, 2) }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-2 py-1 rounded-full text-xs 
                            @if($return->status === 'PAID') bg-emerald-600/20 text-emerald-300
                            @elseif($return->status === 'SUBMITTED') bg-blue-600/20 text-blue-300
                            @else bg-yellow-600/20 text-yellow-300
                            @endif">
                            {{ $return->status }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('modules.tax.vat.show', $return) }}" class="text-indigo-400 hover:text-indigo-300">View</a>
                        <a href="{{ route('modules.tax.vat.pdf', $return) }}" class="text-slate-400 hover:text-slate-300">PDF</a>
                        <a href="{{ route('modules.tax.vat.csv', $return) }}" class="text-slate-400 hover:text-slate-300">CSV</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-slate-400">
                        No VAT returns found. 
                        <a href="{{ route('modules.tax.vat.create') }}" class="text-indigo-400 hover:underline">Create your first return</a>
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