@extends('layouts.app')

@section('page_title', 'Transactions')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white">Transactions</h1>
        <p class="text-slate-400">Manage your business transactions across all modules.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Sales -->
        <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-6 hover:bg-black/30 transition-colors">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-lg bg-blue-600 flex items-center justify-center">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-white">Sales</h3>
                    <p class="text-sm text-slate-400">Manage invoices and customers</p>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('modules.sales.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    Go to Sales
                    <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Purchases -->
        <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-6 hover:bg-black/30 transition-colors">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-lg bg-green-600 flex items-center justify-center">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-white">Purchases</h3>
                    <p class="text-sm text-slate-400">Handle supplier invoices</p>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('modules.purchases.index') }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                    Go to Purchases
                    <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Inventory -->
        <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-6 hover:bg-black/30 transition-colors">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-lg bg-purple-600 flex items-center justify-center">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-white">Inventory</h3>
                    <p class="text-sm text-slate-400">Stock and warehouse management</p>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('modules.inventory.index') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors">
                    Go to Inventory
                    <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Accounting -->
        <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-6 hover:bg-black/30 transition-colors">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-lg bg-orange-600 flex items-center justify-center">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-white">Accounting</h3>
                    <p class="text-sm text-slate-400">General ledger and journals</p>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('modules.accounting.index') }}" class="inline-flex items-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg transition-colors">
                    Go to Accounting
                    <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Payroll -->
        <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-6 hover:bg-black/30 transition-colors">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-lg bg-red-600 flex items-center justify-center">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-white">Payroll</h3>
                    <p class="text-sm text-slate-400">Employee salary processing</p>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('modules.payroll.index') }}" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                    Go to Payroll
                    <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection