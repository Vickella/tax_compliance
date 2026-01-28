<x-app-layout>
    <x-erp.page title="Income Tax Computation" subtitle="Annual income tax reconciliation and ITF 12B projections. Effective rate {{ number_format(($incomeTaxRate ?? config('tax.income_tax_rate')) * 100, 2) }}%.">
        <x-slot name="actions">
            <x-erp.action-button variant="primary">Compute Tax</x-erp.action-button>
            <x-erp.action-button>Export</x-erp.action-button>
            <x-erp.action-button>Print</x-erp.action-button>
        </x-slot>

        <x-erp.section>
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                <div class="lg:col-span-4">
                    <label class="text-xs text-white/70">Tax year</label>
                    <input type="text" placeholder="2026" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                </div>
                <div class="lg:col-span-4">
                    <label class="text-xs text-white/70">Taxable income</label>
                    <input type="number" step="0.01" value="{{ $taxableIncome ?? 0 }}" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                </div>
                <div class="lg:col-span-4">
                    <label class="text-xs text-white/70">Non-deductible expenses (add back)</label>
                    <input type="number" step="0.01" value="{{ $nonDeductible ?? 0 }}" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                </div>
                <div class="lg:col-span-4">
                    <label class="text-xs text-white/70">Basis</label>
                    <select class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white">
                        <option>Projected</option>
                        <option>Actual</option>
                    </select>
                </div>
                <div class="lg:col-span-4 flex items-end">
                    <x-erp.action-button variant="muted" class="w-full justify-center">Run Computation</x-erp.action-button>
                </div>
            </div>
        </x-erp.section>

        <x-erp.section>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <div class="rounded-2xl bg-black/10 border border-white/10 p-5">
                    <p class="text-xs text-white/70">Projected taxable income</p>
                    <p class="text-lg font-semibold text-white">ZIG {{ number_format($result['adjusted_income'] ?? 0, 2) }}</p>
                </div>
                <div class="rounded-2xl bg-black/10 border border-white/10 p-5">
                    <p class="text-xs text-white/70">Projected income tax</p>
                    <p class="text-lg font-semibold text-white">ZIG {{ number_format($result['income_tax'] ?? 0, 2) }}</p>
                </div>
                <div class="rounded-2xl bg-black/10 border border-white/10 p-5">
                    <p class="text-xs text-white/70">QPD paid to date</p>
                    <p class="text-lg font-semibold text-white">ZIG 0.00</p>
                </div>
            </div>
            <div class="mt-6 overflow-x-auto">
                <table class="min-w-full text-sm text-white/80">
                    <thead class="text-xs uppercase text-white/50 border-b border-white/10">
                        <tr>
                            <th class="py-3 text-left">Computation item</th>
                            <th class="py-3 text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-white/5">
                            <td class="py-4">Projected revenue</td>
                            <td class="py-4 text-right">ZIG 0.00</td>
                        </tr>
                        <tr>
                            <td class="py-4 text-white/50" colspan="2">No computation data available.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-erp.section>
    </x-erp.page>
</x-app-layout>
