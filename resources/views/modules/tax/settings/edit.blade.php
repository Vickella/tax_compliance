@extends('layouts.erp')

@section('page_title', 'Tax Settings')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-white">Tax Settings</h2>
        <p class="text-sm text-slate-400">Configure tax rates and rules</p>
    </div>

    <form method="POST" action="{{ route('modules.tax.settings.update') }}">
        @csrf

        {{-- VAT Settings --}}
        <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-5 mb-6">
            <h3 class="text-sm font-semibold text-white mb-4">VAT Settings</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs text-slate-400 mb-1">VAT Rate (%)</label>
                    <input type="number" name="vat_rate" step="0.01" min="0" max="100" 
                           value="{{ $settings->vat_rate ?? 15 }}"
                           class="w-full px-3 py-2 rounded-lg bg-black/30 text-white border border-white/10 focus:border-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs text-slate-400 mb-1">VAT Registration Threshold</label>
                    <input type="number" name="vat_threshold" step="0.01" min="0" 
                           value="{{ $settings->vat_rules['threshold'] ?? 240000 }}"
                           class="w-full px-3 py-2 rounded-lg bg-black/30 text-white border border-white/10 focus:border-indigo-500 outline-none">
                </div>
            </div>
        </div>

        {{-- Income Tax Settings --}}
        <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-5 mb-6">
            <h3 class="text-sm font-semibold text-white mb-4">Income Tax Settings</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Corporate Tax Rate (%)</label>
                    <input type="number" name="income_tax_rate" step="0.01" min="0" max="100" 
                           value="{{ $settings->income_tax_rate ?? 25.75 }}"
                           class="w-full px-3 py-2 rounded-lg bg-black/30 text-white border border-white/10 focus:border-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs text-slate-400 mb-1">AIDS Levy Rate (%)</label>
                    <input type="number" name="aids_levy_rate" step="0.01" min="0" max="100" 
                           value="{{ $settings->income_tax_rules['aids_levy_rate'] ?? 3 }}"
                           class="w-full px-3 py-2 rounded-lg bg-black/30 text-white border border-white/10 focus:border-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs text-slate-400 mb-1">SME Threshold</label>
                    <input type="number" name="sme_threshold" step="0.01" min="0" 
                           value="{{ $settings->income_tax_rules['sme_threshold'] ?? 240000 }}"
                           class="w-full px-3 py-2 rounded-lg bg-black/30 text-white border border-white/10 focus:border-indigo-500 outline-none">
                </div>
            </div>
        </div>

        {{-- QPD Settings --}}
        <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-5 mb-6">
            <h3 class="text-sm font-semibold text-white mb-4">QPD (Provisional Tax) Settings</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Q1 Percentage (%)</label>
                    <input type="number" name="qpd_q1_percent" step="0.01" min="0" max="100" 
                           value="{{ $settings->qpd_q1_percent ?? 10 }}"
                           class="w-full px-3 py-2 rounded-lg bg-black/30 text-white border border-white/10 focus:border-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Q2 Percentage (%)</label>
                    <input type="number" name="qpd_q2_percent" step="0.01" min="0" max="100" 
                           value="{{ $settings->qpd_q2_percent ?? 25 }}"
                           class="w-full px-3 py-2 rounded-lg bg-black/30 text-white border border-white/10 focus:border-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Q3 Percentage (%)</label>
                    <input type="number" name="qpd_q3_percent" step="0.01" min="0" max="100" 
                           value="{{ $settings->qpd_q3_percent ?? 30 }}"
                           class="w-full px-3 py-2 rounded-lg bg-black/30 text-white border border-white/10 focus:border-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Q4 Percentage (%)</label>
                    <input type="number" name="qpd_q4_percent" step="0.01" min="0" max="100" 
                           value="{{ $settings->qpd_q4_percent ?? 35 }}"
                           class="w-full px-3 py-2 rounded-lg bg-black/30 text-white border border-white/10 focus:border-indigo-500 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Q1 Due Date</label>
                    <input type="date" name="qpd_q1_due" 
                           value="{{ $settings->qpd_q1_due ?? (date('Y') . '-03-25') }}"
                           class="w-full px-3 py-2 rounded-lg bg-black/30 text-white border border-white/10 focus:border-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Q2 Due Date</label>
                    <input type="date" name="qpd_q2_due" 
                           value="{{ $settings->qpd_q2_due ?? (date('Y') . '-06-25') }}"
                           class="w-full px-3 py-2 rounded-lg bg-black/30 text-white border border-white/10 focus:border-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Q3 Due Date</label>
                    <input type="date" name="qpd_q3_due" 
                           value="{{ $settings->qpd_q3_due ?? (date('Y') . '-09-25') }}"
                           class="w-full px-3 py-2 rounded-lg bg-black/30 text-white border border-white/10 focus:border-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Q4 Due Date</label>
                    <input type="date" name="qpd_q4_due" 
                           value="{{ $settings->qpd_q4_due ?? (date('Y') . '-12-20') }}"
                           class="w-full px-3 py-2 rounded-lg bg-black/30 text-white border border-white/10 focus:border-indigo-500 outline-none">
                </div>
            </div>
        </div>

        {{-- Penalty Settings --}}
        <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-5 mb-6">
            <h3 class="text-sm font-semibold text-white mb-4">Penalty Settings</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Late Submission Penalty ($)</label>
                    <input type="number" name="late_submission_penalty" step="0.01" min="0" 
                           value="{{ $settings->penalty_rules['late_submission']['amount'] ?? 1000 }}"
                           class="w-full px-3 py-2 rounded-lg bg-black/30 text-white border border-white/10 focus:border-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Late Payment Monthly Rate (%)</label>
                    <input type="number" name="late_payment_rate" step="0.01" min="0" 
                           value="{{ $settings->penalty_rules['late_payment']['rate'] ?? 5 }}"
                           class="w-full px-3 py-2 rounded-lg bg-black/30 text-white border border-white/10 focus:border-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Max Penalty Months</label>
                    <input type="number" name="max_penalty_months" min="1" max="60" 
                           value="{{ $settings->penalty_rules['late_payment']['max_months'] ?? 12 }}"
                           class="w-full px-3 py-2 rounded-lg bg-black/30 text-white border border-white/10 focus:border-indigo-500 outline-none">
                </div>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-white/10">
            <a href="{{ route('modules.tax.index') }}" 
               class="px-4 py-2 rounded-lg bg-white/5 hover:bg-white/10 ring-1 ring-white/10 text-sm transition-colors">
                Cancel
            </a>
            <button type="submit" 
                    class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium transition-colors">
                Save Settings
            </button>
        </div>
            </form>
        </div>
    </div>
</div>
@endsection