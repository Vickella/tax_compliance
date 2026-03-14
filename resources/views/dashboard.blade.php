@extends('layouts.app')

@section('content')
<div class="space-y-6">
    {{-- Page Title --}}
    <div>
        <h1 class="text-2xl font-semibold text-white">Dashboard</h1>
        <p class="text-sm text-slate-400 mt-1">Quick access to core actions</p>
    </div>

    {{-- Quick Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card">
            <div class="text-sm text-slate-400">Total Sales (MTD)</div>
            <div class="text-2xl font-semibold mt-1 text-white">$45,678</div>
            <div class="text-xs text-green-400 mt-2">↑ 12% from last month</div>
        </div>
        <div class="card">
            <div class="text-sm text-slate-400">Open Invoices</div>
            <div class="text-2xl font-semibold mt-1 text-white">23</div>
            <div class="text-xs text-slate-400 mt-2">$12,345 total</div>
        </div>
        <div class="card">
            <div class="text-sm text-slate-400">Overdue</div>
            <div class="text-2xl font-semibold mt-1 text-white">5</div>
            <div class="text-xs text-red-400 mt-2">$3,200 due</div>
        </div>
        <div class="card">
            <div class="text-sm text-slate-400">This Period</div>
            <div class="text-xl font-semibold mt-1 text-white">VAT Due</div>
            <div class="text-xs text-yellow-400 mt-2">March 2026</div>
        </div>
    </div>

    {{-- Shortcuts Section --}}
    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-white">Shortcuts</h2>
            <span class="text-xs text-slate-400">Common actions</span>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <a href="{{ route('modules.sales.invoices.create') }}" 
               class="bg-white/5 hover:bg-white/10 border border-white/10 rounded-lg p-4 text-center transition-all hover:border-indigo-500/50">
                <div class="text-2xl mb-2">📄</div>
                <div class="text-sm font-medium text-white">Sales Invoice</div>
            </a>
            
            <a href="{{ route('modules.purchases.invoices.create') }}" 
               class="bg-white/5 hover:bg-white/10 border border-white/10 rounded-lg p-4 text-center transition-all hover:border-indigo-500/50">
                <div class="text-2xl mb-2">📦</div>
                <div class="text-sm font-medium text-white">Purchase Invoice</div>
            </a>
            
            <a href="{{ route('modules.accounting.journals.create') }}" 
               class="bg-white/5 hover:bg-white/10 border border-white/10 rounded-lg p-4 text-center transition-all hover:border-indigo-500/50">
                <div class="text-2xl mb-2">📊</div>
                <div class="text-sm font-medium text-white">Journal Entry</div>
            </a>
            
            <a href="{{ route('modules.payroll.runs.create') }}" 
               class="bg-white/5 hover:bg-white/10 border border-white/10 rounded-lg p-4 text-center transition-all hover:border-indigo-500/50">
                <div class="text-2xl mb-2">👥</div>
                <div class="text-sm font-medium text-white">Payroll Run</div>
            </a>
        </div>
    </div>

    {{-- Common actions --}}
    <div class="card">
        <h2 class="text-lg font-semibold text-white mb-4">Common actions</h2>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('modules.sales.items.index') }}" 
               class="px-4 py-2 bg-white/5 hover:bg-white/10 border border-white/10 rounded-lg text-sm text-white transition">
                Items
            </a>
            <a href="{{ route('modules.sales.customers.index') }}" 
               class="px-4 py-2 bg-white/5 hover:bg-white/10 border border-white/10 rounded-lg text-sm text-white transition">
                Customers
            </a>
            <a href="{{ route('modules.purchases.suppliers.index') }}" 
               class="px-4 py-2 bg-white/5 hover:bg-white/10 border border-white/10 rounded-lg text-sm text-white transition">
                Suppliers
            </a>
        </div>
    </div>

    {{-- Today and This Period Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Today --}}
        <div class="card">
            <h2 class="text-lg font-semibold text-white mb-4">Today</h2>
            <div class="space-y-3">
                <a href="{{ route('modules.sales.invoices.index') }}" class="flex justify-between items-center p-2 hover:bg-white/5 rounded-lg transition">
                    <span class="text-slate-300">Sales Invoices</span>
                    <span class="text-white font-medium">8</span>
                </a>
                <a href="{{ route('modules.purchases.invoices.index') }}" class="flex justify-between items-center p-2 hover:bg-white/5 rounded-lg transition">
                    <span class="text-slate-300">Purchase Invoices</span>
                    <span class="text-white font-medium">3</span>
                </a>
                <a href="{{ route('modules.accounting.payments.index') }}" class="flex justify-between items-center p-2 hover:bg-white/5 rounded-lg transition">
                    <span class="text-slate-300">Payments</span>
                    <span class="text-white font-medium">5</span>
                </a>
                <a href="{{ route('modules.inventory.stock-ledger.index') }}" class="flex justify-between items-center p-2 hover:bg-white/5 rounded-lg transition">
                    <span class="text-slate-300">Stock Moves</span>
                    <span class="text-white font-medium">12</span>
                </a>
            </div>
        </div>

        {{-- This Period --}}
        <div class="card">
            <h2 class="text-lg font-semibold text-white mb-4">This Period</h2>
            <div class="space-y-3">
                <a href="{{ route('modules.tax.vat.index') }}" class="flex justify-between items-center p-2 hover:bg-white/5 rounded-lg transition">
                    <span class="text-slate-300">VAT (Draft)</span>
                    <span class="text-yellow-400 text-sm">Due 25 Mar</span>
                </a>
                <a href="{{ route('modules.payroll.runs.index') }}" class="flex justify-between items-center p-2 hover:bg-white/5 rounded-lg transition">
                    <span class="text-slate-300">Payroll (Draft)</span>
                    <span class="text-yellow-400 text-sm">Due 28 Mar</span>
                </a>
                <a href="{{ route('modules.tax.qpd.index') }}" class="flex justify-between items-center p-2 hover:bg-white/5 rounded-lg transition">
                    <span class="text-slate-300">QPD (Due)</span>
                    <span class="text-red-400 text-sm">Overdue</span>
                </a>
                <div class="flex justify-between items-center p-2">
                    <span class="text-slate-300">Compliance Flags</span>
                    <span class="text-orange-400 text-sm">2 Issues</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Setup --}}
    <div class="card">
        <h2 class="text-lg font-semibold text-white mb-4">Quick Setup</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <a href="{{ route('modules.company-settings.company.edit') }}" 
               class="p-3 bg-white/5 hover:bg-white/10 border border-white/10 rounded-lg text-sm text-white transition">
                → Company Profile
            </a>
            <a href="{{ route('modules.company-settings.tax-rates.index') }}" 
               class="p-3 bg-white/5 hover:bg-white/10 border border-white/10 rounded-lg text-sm text-white transition">
                → Tax Rates
            </a>
            <a href="{{ route('modules.company-settings.paye-brackets.index') }}" 
               class="p-3 bg-white/5 hover:bg-white/10 border border-white/10 rounded-lg text-sm text-white transition">
                → PAYE Bands
            </a>
        </div>
    </div>
</div>
@endsection