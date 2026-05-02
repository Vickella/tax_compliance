@extends('layouts.app')

@section('page_title', 'Create Income Tax Return')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-white">Income Tax Return - Year {{ $taxYear }}</h2>
        <p class="text-sm text-slate-400">ITF12C - Based on GL transactions for the year</p>
    </div>

    <form method="POST" action="{{ route('modules.tax.income.store') }}" id="taxForm">
        @csrf
        <input type="hidden" name="tax_year" value="{{ $taxYear }}">
        <input type="hidden" name="period_start" value="{{ $taxYear }}-01-01">
        <input type="hidden" name="period_end" value="{{ $taxYear }}-12-31">

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
            <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-4">
                <div class="text-xs text-slate-400">Total Income</div>
                <div class="text-xl font-bold text-white">${{ number_format($calculation['income']['total'] ?? 0, 2) }}</div>
                <input type="hidden" name="total_income" value="{{ $calculation['income']['total'] ?? 0 }}">
            </div>
            <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-4">
                <div class="text-xs text-slate-400">Total Expenses</div>
                <div class="text-xl font-bold text-white">${{ number_format($calculation['expenses']['total'] ?? 0, 2) }}</div>
                <input type="hidden" name="total_expenses" value="{{ $calculation['expenses']['total'] ?? 0 }}">
            </div>
            <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-4">
                <div class="text-xs text-slate-400">Add Backs</div>
                <div class="text-xl font-bold text-amber-400">${{ number_format($calculation['expenses']['addbacks'] ?? 0, 2) }}</div>
                <input type="hidden" name="add_back_amount" value="{{ $calculation['expenses']['addbacks'] ?? 0 }}">
            </div>
            <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-4">
                <div class="text-xs text-slate-400">Taxable Income</div>
                <div class="text-xl font-bold text-white">${{ number_format($calculation['taxable_income'] ?? 0, 2) }}</div>
                <input type="hidden" name="taxable_income" value="{{ $calculation['taxable_income'] ?? 0 }}">
            </div>
            <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-4">
                <div class="text-xs text-slate-400">Total Tax</div>
                <div class="text-xl font-bold text-indigo-400">${{ number_format($calculation['total_tax'] ?? 0, 2) }}</div>
                <input type="hidden" name="total_tax" value="{{ $calculation['total_tax'] ?? 0 }}">
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left Column - Income Breakdown --}}
            <div class="lg:col-span-1">
                <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-5">
                    <h3 class="text-sm font-semibold text-white mb-3">Income Breakdown</h3>
                    <div class="space-y-2 max-h-96 overflow-y-auto">
                        @forelse($calculation['income']['breakdown'] ?? [] as $item)
                        <div class="flex justify-between items-center p-2 bg-black/30 rounded-lg">
                            <div>
                                <div class="text-xs text-slate-400">{{ $item['code'] ?? 'N/A' }}</div>
                                <div class="text-sm text-white">{{ $item['name'] ?? 'Unknown' }}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-semibold text-emerald-400">
                                    ${{ number_format($item['amount'] ?? 0, 2) }}
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-slate-400 py-4">No income data found</div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Middle Column - Expense Breakdown --}}
            <div class="lg:col-span-1">
                <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-5">
                    <h3 class="text-sm font-semibold text-white mb-3">Expense Breakdown</h3>
                    <div class="space-y-2 max-h-96 overflow-y-auto">
                        @forelse($calculation['expenses']['breakdown'] ?? [] as $item)
                        @php
                            // Ensure all required keys exist with defaults
                            $itemCode = $item['code'] ?? 'N/A';
                            $itemName = $item['name'] ?? 'Unknown';
                            $itemAmount = $item['amount'] ?? 0;
                            $itemDeductible = $item['deductible'] ?? $itemAmount;
                            $itemDeductiblePercent = $item['deductible_percent'] ?? 100;
                        @endphp
                        <div class="p-2 bg-black/30 rounded-lg">
                            <div class="flex justify-between items-center">
                                <div>
                                    <div class="text-xs text-slate-400">{{ $itemCode }}</div>
                                    <div class="text-sm text-white">{{ $itemName }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm text-slate-400">Total: ${{ number_format($itemAmount, 2) }}</div>
                                    <div class="text-sm font-semibold text-emerald-400">
                                        Deductible: ${{ number_format($itemDeductible, 2) }}
                                    </div>
                                </div>
                            </div>
                            @if($itemDeductiblePercent < 100)
                            <div class="mt-1 text-xs text-amber-400">
                                {{ $itemDeductiblePercent }}% deductible
                            </div>
                            @endif
                        </div>
                        @empty
                        <div class="text-center text-slate-400 py-4">No expense data found</div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Right Column - Add Backs --}}
            <div class="lg:col-span-1">
                <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-5">
                    <h3 class="text-sm font-semibold text-white mb-3">Non-Deductible Add Backs</h3>
                    <div class="space-y-2 max-h-96 overflow-y-auto">
                        @forelse($calculation['expenses']['addback_breakdown'] ?? [] as $item)
                        @php
                            $itemCode = $item['code'] ?? 'N/A';
                            $itemName = $item['name'] ?? 'Unknown';
                            $itemAmount = $item['amount'] ?? 0;
                            $itemReason = $item['reason'] ?? 'Non-deductible';
                        @endphp
                        <div class="p-2 bg-amber-500/10 rounded-lg border border-amber-500/20">
                            <div class="flex justify-between items-center">
                                <div>
                                    <div class="text-xs text-slate-400">{{ $itemCode }}</div>
                                    <div class="text-sm text-white">{{ $itemName }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-semibold text-amber-400">
                                        ${{ number_format($itemAmount, 2) }}
                                    </div>
                                </div>
                            </div>
                            <div class="mt-1 text-xs text-amber-400/80">
                                {{ $itemReason }}
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-slate-400 py-4">
                            No non-deductible items found
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Assessed Loss Section --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
            <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-4">
                <label class="block text-xs text-slate-400 mb-1">Assessed Loss Brought Forward</label>
                <input type="number" name="assessed_loss_bf" step="0.01" value="{{ $calculation['assessed_loss_bf'] ?? 0 }}"
                       class="w-full px-3 py-2 rounded-lg bg-black/30 text-white border border-white/10 focus:border-indigo-500 outline-none">
                <p class="text-xs text-slate-500 mt-1">From previous year approved return</p>
            </div>
            <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-4">
                <label class="block text-xs text-slate-400 mb-1">Taxable Income After Loss</label>
                <input type="text" readonly value="${{ number_format($calculation['taxable_income_after_loss'] ?? $calculation['taxable_income'] ?? 0, 2) }}"
                       class="w-full px-3 py-2 rounded-lg bg-black/30 text-slate-300 border border-white/10">
                <input type="hidden" name="taxable_income_after_loss" value="{{ $calculation['taxable_income_after_loss'] ?? $calculation['taxable_income'] ?? 0 }}">
            </div>
            <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-4">
                <label class="block text-xs text-slate-400 mb-1">Assessed Loss Carried Forward</label>
                <input type="text" readonly value="${{ number_format($calculation['assessed_loss_cf'] ?? 0, 2) }}"
                       class="w-full px-3 py-2 rounded-lg bg-black/30 text-slate-300 border border-white/10">
                <input type="hidden" name="assessed_loss_cf" value="{{ $calculation['assessed_loss_cf'] ?? 0 }}">
            </div>
        </div>

        {{-- Tax Calculation Summary --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
            <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-4">
                <div class="text-xs text-slate-400">Tax Rate</div>
                <div class="text-lg font-semibold text-white">{{ ($calculation['tax_rate'] ?? 0) * 100 }}%</div>
                <input type="hidden" name="tax_rate" value="{{ $calculation['tax_rate'] ?? 0 }}">
            </div>
            <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-4">
                <div class="text-xs text-slate-400">Income Tax</div>
                <div class="text-lg font-semibold text-white">${{ number_format($calculation['income_tax'] ?? 0, 2) }}</div>
                <input type="hidden" name="income_tax" value="{{ $calculation['income_tax'] ?? 0 }}">
            </div>
            <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-4">
                <div class="text-xs text-slate-400">AIDS Levy</div>
                <div class="text-lg font-semibold text-white">${{ number_format($calculation['aids_levy'] ?? 0, 2) }}</div>
                <input type="hidden" name="aids_levy" value="{{ $calculation['aids_levy'] ?? 0 }}">
            </div>
            <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-4">
                <div class="text-xs text-slate-400">Total Tax</div>
                <div class="text-lg font-semibold text-indigo-400">${{ number_format($calculation['total_tax'] ?? 0, 2) }}</div>
            </div>
        </div>

        {{-- QPD Payments --}}
        <div class="mt-6 bg-black/20 rounded-xl ring-1 ring-white/10 p-5">
            <h3 class="text-sm font-semibold text-white mb-3">QPD Payments Made</h3>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div class="col-span-2">
                    <input type="number" name="qpd_paid" step="0.01" value="{{ $calculation['qpd_paid'] ?? 0 }}"
                           class="w-full px-3 py-2 rounded-lg bg-black/30 text-white border border-white/10 focus:border-indigo-500 outline-none"
                           placeholder="Total QPD paid" id="qpd-paid-input">
                </div>
                <div class="col-span-2">
                    @php
                        $totalTax = $calculation['total_tax'] ?? 0;
                        $qpdPaid = $calculation['qpd_paid'] ?? 0;
                        $balanceDue = $totalTax - $qpdPaid;
                    @endphp
                    <div class="text-sm text-white py-2">Balance Due: 
                        <span id="balance-display" class="font-bold {{ $balanceDue > 0 ? 'text-amber-400' : 'text-emerald-400' }}">
                            ${{ number_format($balanceDue, 2) }}
                        </span>
                    </div>
                    <input type="hidden" name="balance_due" value="{{ $balanceDue }}" id="balance-due-input">
                </div>
            </div>
        </div>

        {{-- Notes --}}
        <div class="mt-6 bg-black/20 rounded-xl ring-1 ring-white/10 p-5">
            <label class="block text-xs text-slate-400 mb-1">Notes / Comments</label>
            <textarea name="notes" rows="3" 
                      class="w-full px-3 py-2 rounded-lg bg-black/30 text-white border border-white/10 focus:border-indigo-500 outline-none"
                      placeholder="Any additional notes about this return...">{{ old('notes') }}</textarea>
        </div>

        {{-- Form Actions --}}
        <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-white/10">
            <a href="{{ route('modules.tax.income.index') }}" 
               class="px-4 py-2 rounded-lg bg-white/5 hover:bg-white/10 ring-1 ring-white/10 text-sm transition-colors">
                Cancel
            </a>
            <button type="submit" name="action" value="save" 
                    class="px-4 py-2 rounded-lg bg-white/10 hover:bg-white/15 ring-1 ring-white/10 text-sm transition-colors">
                💾 Save Draft
            </button>
            <button type="submit" name="action" value="submit" 
                    class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Submit Return
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
// Auto-calculate balance due when QPD amount changes
document.addEventListener('DOMContentLoaded', function() {
    const qpdInput = document.getElementById('qpd-paid-input');
    const balanceDisplay = document.getElementById('balance-display');
    const balanceInput = document.getElementById('balance-due-input');
    const totalTax = {{ $calculation['total_tax'] ?? 0 }};
    
    if (qpdInput && balanceDisplay && balanceInput) {
        qpdInput.addEventListener('input', function() {
            const qpdPaid = parseFloat(this.value) || 0;
            const balanceDue = totalTax - qpdPaid;
            
            balanceDisplay.textContent = '$' + balanceDue.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
            balanceDisplay.className = balanceDue > 0 ? 'font-bold text-amber-400' : 'font-bold text-emerald-400';
            balanceInput.value = balanceDue.toFixed(2);
        });
    }
});
</script>
@endpush
@endsection