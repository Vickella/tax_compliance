@extends('layouts.app')

@section('page_title', 'Tax Module')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-4">
    <div class="rounded-2xl ring-1 ring-white/10 bg-slate-950/40 overflow-hidden">
        <div class="p-4 border-b border-white/10 flex items-center justify-between">
            <div>
                <div class="text-base font-semibold">Tax Module</div>
                <div class="text-xs text-slate-300">ZIMRA print-format returns and compliance outputs.</div>
            </div>
            <a href="{{ route('dashboard') }}"
               class="text-xs rounded-lg px-3 py-2 bg-white/5 hover:bg-white/10 ring-1 ring-white/10 transition-colors">
                Back to Dashboard
            </a>
        </div>

        {{-- Quick Stats from Controller --}}
        @if(isset($stats))
        <div class="p-4 border-b border-white/10 grid grid-cols-2 md:grid-cols-4 gap-3">
            <div class="bg-white/5 rounded-lg p-3">
                <div class="text-xs text-slate-400">VAT Returns</div>
                <div class="text-lg font-semibold">{{ $stats['vat_returns'] ?? 0 }}</div>
                <div class="text-xs text-slate-500">{{ $stats['vat_pending'] ?? 0 }} pending</div>
            </div>
            <div class="bg-white/5 rounded-lg p-3">
                <div class="text-xs text-slate-400">QPD Payments</div>
                <div class="text-lg font-semibold">{{ $stats['qpd_payments'] ?? 0 }}</div>
                <div class="text-xs {{ ($stats['qpd_overdue'] ?? 0) > 0 ? 'text-rose-400' : 'text-slate-500' }}">
                    {{ $stats['qpd_overdue'] ?? 0 }} overdue
                </div>
            </div>
            <div class="bg-white/5 rounded-lg p-3">
                <div class="text-xs text-slate-400">Income Tax</div>
                <div class="text-lg font-semibold">{{ $stats['income_returns'] ?? 0 }}</div>
                <div class="text-xs text-slate-500">{{ now()->year }} year</div>
            </div>
            <div class="bg-white/5 rounded-lg p-3">
                <div class="text-xs text-slate-400">Next Deadline</div>
                <div class="text-sm font-semibold text-amber-400">{{ $stats['next_deadline'] ?? '25th ' . now()->addMonth()->format('M') }}</div>
                <div class="text-xs text-slate-500">{{ $stats['deadline_type'] ?? 'VAT' }}</div>
            </div>
        </div>
        @endif

        {{-- Main Navigation Cards --}}
        <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-3">
            {{-- VAT Return Card --}}
            <a href="{{ route('modules.tax.vat.index') }}"
               class="rounded-xl ring-1 ring-white/10 bg-white/5 p-4 hover:bg-white/10 transition-all hover:scale-[1.02] group">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-lg bg-amber-600/20 flex items-center justify-center text-amber-400 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-semibold">VAT Return (VAT 7)</div>
                        <div class="text-xs text-slate-300 mt-1">Compute from GL + export exact VAT7 print PDF.</div>
                        <div class="text-xs text-amber-400 mt-2">Due: 25th of each month</div>
                    </div>
                </div>
            </a>

            {{-- QPD Card --}}
            <a href="{{ route('modules.tax.qpd.index') }}"
               class="rounded-xl ring-1 ring-white/10 bg-white/5 p-4 hover:bg-white/10 transition-all hover:scale-[1.02] group">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-600/20 flex items-center justify-center text-blue-400 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-semibold">QPDs (ITF12B)</div>
                        <div class="text-xs text-slate-300 mt-1">Forecast + compute quarter amounts (10/25/30/35).</div>
                        <div class="text-xs text-blue-400 mt-2">Due: Mar 25, Jun 25, Sep 25, Dec 20</div>
                    </div>
                </div>
            </a>

            {{-- Income Tax Card --}}
            <a href="{{ route('modules.tax.income.index') }}"
               class="rounded-xl ring-1 ring-white/10 bg-white/5 p-4 hover:bg-white/10 transition-all hover:scale-[1.02] group">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-lg bg-emerald-600/20 flex items-center justify-center text-emerald-400 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-semibold">Income Tax (ITF12C)</div>
                        <div class="text-xs text-slate-300 mt-1">Compute taxable income + export ITF12C print PDF.</div>
                        <div class="text-xs text-emerald-400 mt-2">Annual filing: 30 April {{ now()->year + 1 }}</div>
                    </div>
                </div>
            </a>

            {{-- Settings Card --}}
            <a href="{{ route('modules.tax.settings') }}"
               class="rounded-xl ring-1 ring-white/10 bg-white/5 p-4 hover:bg-white/10 transition-all hover:scale-[1.02] group">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-lg bg-purple-600/20 flex items-center justify-center text-purple-400 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-semibold">Tax Settings</div>
                        <div class="text-xs text-slate-300 mt-1">Rates, VAT accounts, QPD due dates, percentages.</div>
                        <div class="text-xs text-purple-400 mt-2">Configure tax rules and mappings</div>
                    </div>
                </div>
            </a>
        </div>

        {{-- Recent Activity Section --}}
        @if(isset($recentActivity) && count($recentActivity) > 0)
        <div class="p-4 border-t border-white/10">
            <div class="text-sm font-semibold mb-3">Recent Tax Activity</div>
            <div class="space-y-2">
                @foreach($recentActivity as $activity)
                <a href="{{ $activity['link'] }}" class="block">
                    <div class="flex items-center justify-between text-xs bg-white/5 rounded-lg p-2 hover:bg-white/10 transition-colors">
                        <div>
                            <span class="text-slate-300">{{ $activity['description'] }}</span>
                            <span class="text-slate-500 ml-2">{{ $activity['date'] }}</span>
                        </div>
                        <div>
                            <span class="px-2 py-0.5 rounded-full text-xs
                                @if($activity['status'] === 'SUBMITTED') bg-green-600/20 text-green-300
                                @elseif($activity['status'] === 'PAID') bg-green-600/20 text-green-300
                                @elseif($activity['status'] === 'DRAFT') bg-yellow-600/20 text-yellow-300
                                @else bg-slate-600/20 text-slate-300
                                @endif">
                                {{ $activity['status'] }}
                            </span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Quick Actions --}}
        <div class="p-4 border-t border-white/10 grid grid-cols-2 sm:grid-cols-4 gap-2">
            <a href="{{ route('modules.tax.vat.create') }}" 
               class="text-xs text-center p-2 bg-white/5 rounded-lg hover:bg-white/10 transition-colors">
                + New VAT Return
            </a>
            <a href="{{ route('modules.tax.qpd.create') }}" 
               class="text-xs text-center p-2 bg-white/5 rounded-lg hover:bg-white/10 transition-colors">
                + Make QPD Payment
            </a>
            <a href="{{ route('modules.tax.income.create') }}" 
               class="text-xs text-center p-2 bg-white/5 rounded-lg hover:bg-white/10 transition-colors">
                + New Income Tax
            </a>
            <a href="{{ route('modules.tax.qpd.forecast') }}" 
               class="text-xs text-center p-2 bg-white/5 rounded-lg hover:bg-white/10 transition-colors">
                📊 View Forecast
            </a>
        </div>
    </div>

    {{-- Compliance Status Card (Optional) --}}
    @if(isset($compliance))
    <div class="rounded-2xl ring-1 ring-white/10 bg-slate-950/40 overflow-hidden mt-6">
        <div class="p-4 border-b border-white/10">
            <div class="text-base font-semibold">Compliance Status</div>
        </div>
        <div class="p-4 grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- VAT Status --}}
            <div class="bg-white/5 rounded-xl p-4">
                <div class="flex items-center justify-between mb-3">
                    <div class="text-sm font-semibold">VAT</div>
                    <span class="px-2 py-1 rounded-full text-xs 
                        @if($compliance['vat']['compliant']) bg-emerald-600/20 text-emerald-300
                        @else bg-amber-600/20 text-amber-300
                        @endif">
                        {{ $compliance['vat']['compliant'] ? 'Compliant' : 'Attention' }}
                    </span>
                </div>
                <div class="space-y-2 text-xs">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Last Filed:</span>
                        <span>{{ $compliance['vat']['last_filed'] ?? 'Never' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Next Due:</span>
                        <span class="text-amber-400">{{ $compliance['vat']['next_due'] ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            {{-- QPD Status --}}
            <div class="bg-white/5 rounded-xl p-4">
                <div class="flex items-center justify-between mb-3">
                    <div class="text-sm font-semibold">QPD</div>
                    <span class="px-2 py-1 rounded-full text-xs 
                        @if($compliance['qpd']['compliant']) bg-emerald-600/20 text-emerald-300
                        @else bg-amber-600/20 text-amber-300
                        @endif">
                        {{ $compliance['qpd']['compliant'] ? 'Compliant' : 'Action Needed' }}
                    </span>
                </div>
                <div class="space-y-2 text-xs">
                    @foreach($compliance['qpd']['quarters'] ?? [] as $q => $status)
                    <div class="flex justify-between">
                        <span class="text-slate-400">Q{{ $q }}:</span>
                        <span class="{{ $status['paid'] ? 'text-emerald-400' : ($status['overdue'] ? 'text-rose-400' : 'text-amber-400') }}">
                            {{ $status['paid'] ? 'Paid' : ($status['overdue'] ? 'Overdue' : 'Pending') }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Income Tax Status --}}
            <div class="bg-white/5 rounded-xl p-4">
                <div class="flex items-center justify-between mb-3">
                    <div class="text-sm font-semibold">Income Tax</div>
                    <span class="px-2 py-1 rounded-full text-xs 
                        @if($compliance['income']['compliant']) bg-emerald-600/20 text-emerald-300
                        @else bg-amber-600/20 text-amber-300
                        @endif">
                        {{ $compliance['income']['compliant'] ? 'Compliant' : 'Pending' }}
                    </span>
                </div>
                <div class="space-y-2 text-xs">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Status:</span>
                        <span>{{ $compliance['income']['status'] ?? 'Not Filed' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Deadline:</span>
                        <span class="text-amber-400">{{ $compliance['income']['deadline'] ?? '30 Apr ' . (now()->year + 1) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection