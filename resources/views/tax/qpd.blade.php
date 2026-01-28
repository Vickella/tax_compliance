<x-app-layout>
    <x-erp.page title="QPD Schedules" subtitle="Quarterly Provisional Tax schedules and installment tracking.">
        <x-slot name="actions">
            <x-erp.action-button variant="primary">Generate Schedule</x-erp.action-button>
            <x-erp.action-button>Export</x-erp.action-button>
            <x-erp.action-button>Print</x-erp.action-button>
        </x-slot>

        <x-erp.section>
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                <div class="lg:col-span-4">
                    <label class="text-xs text-white/70">Income tax year</label>
                    <input type="text" placeholder="2026" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                </div>
                <div class="lg:col-span-4">
                    <label class="text-xs text-white/70">Basis</label>
                    <select class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white">
                        <option>Projected</option>
                        <option>Actual</option>
                    </select>
                </div>
                <div class="lg:col-span-4 flex items-end">
                    <x-erp.action-button variant="muted" class="w-full justify-center">View Schedule</x-erp.action-button>
                </div>
            </div>
        </x-erp.section>

        <x-erp.section>
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                <div class="rounded-2xl bg-black/10 border border-white/10 p-5">
                    <p class="text-xs text-white/70">Estimated annual tax</p>
                    <p class="text-lg font-semibold text-white">ZIG 0.00</p>
                </div>
                <div class="rounded-2xl bg-black/10 border border-white/10 p-5">
                    <p class="text-xs text-white/70">Paid to date</p>
                    <p class="text-lg font-semibold text-white">ZIG 0.00</p>
                </div>
                <div class="rounded-2xl bg-black/10 border border-white/10 p-5">
                    <p class="text-xs text-white/70">Next installment</p>
                    <p class="text-lg font-semibold text-white">ZIG 0.00</p>
                </div>
                <div class="rounded-2xl bg-black/10 border border-white/10 p-5">
                    <p class="text-xs text-white/70">Variance</p>
                    <p class="text-lg font-semibold text-white">ZIG 0.00</p>
                </div>
            </div>
            <div class="mt-6 overflow-x-auto">
                <table class="min-w-full text-sm text-white/80">
                    <thead class="text-xs uppercase text-white/50 border-b border-white/10">
                        <tr>
                            <th class="py-3 text-left">Installment</th>
                            <th class="py-3 text-right">Rate</th>
                            <th class="py-3 text-right">Amount Due</th>
                            <th class="py-3 text-right">Amount Paid</th>
                            <th class="py-3 text-left">Due Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="py-4 text-white/50" colspan="5">No QPD schedule generated yet.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-erp.section>
    </x-erp.page>
</x-app-layout>
