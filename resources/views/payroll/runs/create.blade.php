<x-app-layout>
    <x-erp.page title="Run Payroll" subtitle="Process monthly payroll with PAYE, NSSA, ZIMDEF, and AIDS levy.">
        <x-slot name="actions">
            <x-erp.action-button variant="muted">Save Draft</x-erp.action-button>
            <x-erp.action-button variant="primary">Process Payroll</x-erp.action-button>
            <x-erp.action-button variant="danger">Cancel</x-erp.action-button>
        </x-slot>

        <x-erp.section>
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                <div>
                    <label class="text-xs text-white/70">Payroll run #</label>
                    <input type="text" placeholder="PR-2026-01" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                </div>
                <div>
                    <label class="text-xs text-white/70">Period</label>
                    <input type="text" placeholder="2026-01" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                </div>
                <div>
                    <label class="text-xs text-white/70">Currency</label>
                    <select class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white">
                        <option>ZIG</option>
                        <option>USD</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs text-white/70">Exchange rate</label>
                    <input type="number" step="0.0001" placeholder="1.0000" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                </div>
            </div>
        </x-erp.section>

        <x-erp.section>
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                <div>
                    <label class="text-xs text-white/70">Basic Salary</label>
                    <input type="number" step="0.01" value="{{ $earnings['basic_salary'] ?? 0 }}" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                </div>
                <div>
                    <label class="text-xs text-white/70">Housing Allowance</label>
                    <input type="number" step="0.01" value="{{ $earnings['housing_allowance'] ?? 0 }}" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                </div>
                <div>
                    <label class="text-xs text-white/70">Transport Allowance</label>
                    <input type="number" step="0.01" value="{{ $earnings['transport_allowance'] ?? 0 }}" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                </div>
                <div>
                    <label class="text-xs text-white/70">Other Income</label>
                    <input type="number" step="0.01" value="{{ $earnings['other_income'] ?? 0 }}" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                </div>
            </div>
        </x-erp.section>

        <x-erp.section>
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-white">Payroll preview</h3>
                <x-erp.action-button variant="ghost">Load Employees</x-erp.action-button>
            </div>
            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full text-sm text-white/80">
                    <thead class="text-xs uppercase text-white/50 border-b border-white/10">
                        <tr>
                            <th class="py-3 text-left">Employee</th>
                            <th class="py-3 text-right">Gross Pay</th>
                            <th class="py-3 text-right">PAYE</th>
                            <th class="py-3 text-right">AIDS Levy</th>
                            <th class="py-3 text-right">NSSA</th>
                            <th class="py-3 text-right">Net Pay</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="py-4 text-white/50" colspan="6">No employees loaded yet.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-erp.section>

        <x-erp.section>
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                <div class="rounded-2xl bg-black/10 border border-white/10 p-5">
                    <p class="text-xs text-white/70">Total Gross</p>
                    <p class="text-lg font-semibold text-white">ZIG {{ number_format($calculated['gross'] ?? 0, 2) }}</p>
                </div>
                <div class="rounded-2xl bg-black/10 border border-white/10 p-5">
                    <p class="text-xs text-white/70">Total Deductions</p>
                    <p class="text-lg font-semibold text-white">ZIG {{ number_format($calculated['total_deductions'] ?? 0, 2) }}</p>
                </div>
                <div class="rounded-2xl bg-black/10 border border-white/10 p-5">
                    <p class="text-xs text-white/70">Total Net Pay</p>
                    <p class="text-lg font-semibold text-white">ZIG {{ number_format($calculated['net_pay'] ?? 0, 2) }}</p>
                </div>
                <div class="rounded-2xl bg-black/10 border border-white/10 p-5">
                    <p class="text-xs text-white/70">PAYE + AIDS Levy</p>
                    <p class="text-lg font-semibold text-white">ZIG {{ number_format(($calculated['paye'] ?? 0) + ($calculated['aids_levy'] ?? 0), 2) }}</p>
                </div>
            </div>
        </x-erp.section>

        <x-erp.section>
            <h3 class="text-sm font-semibold text-white">Payroll summary</h3>
            <div class="mt-4 grid grid-cols-1 lg:grid-cols-4 gap-4 text-sm text-white/80">
                <div class="rounded-2xl bg-black/10 border border-white/10 p-4">
                    <p class="text-xs text-white/60">Basic Salary</p>
                    <p class="text-lg font-semibold text-white">ZIG {{ number_format($summary['basic_salary'] ?? 0, 2) }}</p>
                </div>
                <div class="rounded-2xl bg-black/10 border border-white/10 p-4">
                    <p class="text-xs text-white/60">Housing Allowance</p>
                    <p class="text-lg font-semibold text-white">ZIG {{ number_format($summary['housing_allowance'] ?? 0, 2) }}</p>
                </div>
                <div class="rounded-2xl bg-black/10 border border-white/10 p-4">
                    <p class="text-xs text-white/60">Transport Allowance</p>
                    <p class="text-lg font-semibold text-white">ZIG {{ number_format($summary['transport_allowance'] ?? 0, 2) }}</p>
                </div>
                <div class="rounded-2xl bg-black/10 border border-white/10 p-4">
                    <p class="text-xs text-white/60">Other Income</p>
                    <p class="text-lg font-semibold text-white">ZIG {{ number_format($summary['other_income'] ?? 0, 2) }}</p>
                </div>
                <div class="rounded-2xl bg-black/10 border border-white/10 p-4">
                    <p class="text-xs text-white/60">NSSA (4.5%)</p>
                    <p class="text-lg font-semibold text-white">ZIG {{ number_format($summary['nssa'] ?? 0, 2) }}</p>
                </div>
                <div class="rounded-2xl bg-black/10 border border-white/10 p-4">
                    <p class="text-xs text-white/60">NEC (1.5%)</p>
                    <p class="text-lg font-semibold text-white">ZIG {{ number_format($summary['nec'] ?? 0, 2) }}</p>
                </div>
                <div class="rounded-2xl bg-black/10 border border-white/10 p-4">
                    <p class="text-xs text-white/60">PAYE</p>
                    <p class="text-lg font-semibold text-white">ZIG {{ number_format($summary['paye'] ?? 0, 2) }}</p>
                </div>
                <div class="rounded-2xl bg-black/10 border border-white/10 p-4">
                    <p class="text-xs text-white/60">AIDS Levy (3%)</p>
                    <p class="text-lg font-semibold text-white">ZIG {{ number_format($summary['aids_levy'] ?? 0, 2) }}</p>
                </div>
            </div>
        </x-erp.section>
    </x-erp.page>
</x-app-layout>
