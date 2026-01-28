<x-app-layout>
    <x-erp.page title="VAT Validation" subtitle="Audit VAT completeness for invoices and fiscal references.">
        <x-slot name="actions">
            <x-erp.action-button>Export</x-erp.action-button>
            <x-erp.action-button>Print</x-erp.action-button>
        </x-slot>

        <x-erp.section>
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                <div class="lg:col-span-4">
                    <label class="text-xs text-white/70">Period</label>
                    <input type="text" placeholder="2026-01" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                </div>
                <div class="lg:col-span-4">
                    <label class="text-xs text-white/70">Document type</label>
                    <select class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white">
                        <option>All</option>
                        <option>Sales Invoices</option>
                        <option>Purchase Invoices</option>
                    </select>
                </div>
                <div class="lg:col-span-4 flex items-end">
                    <x-erp.action-button variant="muted" class="w-full justify-center">Run Checks</x-erp.action-button>
                </div>
            </div>
        </x-erp.section>

        <x-erp.section>
            <div class="mt-1 overflow-x-auto">
                <table class="min-w-full text-sm text-white/80">
                    <thead class="text-xs uppercase text-white/50 border-b border-white/10">
                        <tr>
                            <th class="py-3 text-left">Document</th>
                            <th class="py-3 text-left">Issue</th>
                            <th class="py-3 text-left">Severity</th>
                            <th class="py-3 text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="py-4 text-white/50" colspan="4">No VAT compliance findings for the selected period.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-erp.section>
    </x-erp.page>
</x-app-layout>
