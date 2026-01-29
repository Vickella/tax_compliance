<x-app-layout>
    <x-erp.page title="VAT Return" subtitle="Prepare VAT 7 / VAT 7A schedules with input and output tax.">
        <x-slot name="actions">
            <x-erp.action-button variant="primary">Generate Return</x-erp.action-button>
            <x-erp.action-button>Export</x-erp.action-button>
            <x-erp.action-button>Print</x-erp.action-button>
            <x-erp.action-button variant="danger" x-on:click="$dispatch('open-modal', 'submit-vat')">Submit</x-erp.action-button>
        </x-slot>

        <x-erp.section>
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                <div class="lg:col-span-4">
                    <label class="text-xs text-white/70">Tax period</label>
                    <input type="text" placeholder="2026-01" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                </div>
                <div class="lg:col-span-4">
                    <label class="text-xs text-white/70">Return type</label>
                    <select class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white">
                        <option>VAT7</option>
                        <option>VAT7A</option>
                    </select>
                </div>
                <div class="lg:col-span-4 flex items-end">
                    <x-erp.action-button variant="muted" class="w-full justify-center">Preview Schedule</x-erp.action-button>
                </div>
            </div>
        </x-erp.section>

        <x-erp.section>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <div class="rounded-2xl bg-black/10 border border-white/10 p-5">
                    <p class="text-xs text-white/70">Output VAT</p>
                    <p class="text-lg font-semibold text-white">ZIG 0.00</p>
                </div>
                <div class="rounded-2xl bg-black/10 border border-white/10 p-5">
                    <p class="text-xs text-white/70">Input VAT</p>
                    <p class="text-lg font-semibold text-white">ZIG 0.00</p>
                </div>
                <div class="rounded-2xl bg-black/10 border border-white/10 p-5">
                    <p class="text-xs text-white/70">Net VAT</p>
                    <p class="text-lg font-semibold text-white">ZIG 0.00</p>
                </div>
            </div>
            <div class="mt-6 overflow-x-auto">
                <table class="min-w-full text-sm text-white/80">
                    <thead class="text-xs uppercase text-white/50 border-b border-white/10">
                        <tr>
                            <th class="py-3 text-left">Invoice #</th>
                            <th class="py-3 text-left">Party</th>
                            <th class="py-3 text-left">TIN/VAT</th>
                            <th class="py-3 text-right">Taxable Value</th>
                            <th class="py-3 text-right">VAT</th>
                            <th class="py-3 text-left">Direction</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="py-4 text-white/50" colspan="6">No VAT schedule lines generated yet.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-erp.section>

        <x-modal name="submit-vat" maxWidth="lg">
            <div class="bg-slate-950 text-white p-6">
                <h3 class="text-lg font-semibold">Submit VAT return</h3>
                <p class="text-sm text-white/70 mt-2">Confirm the VAT return is complete with invoice references and fiscal details.</p>
                <div class="mt-6 flex justify-end gap-3">
                    <x-erp.action-button variant="muted" x-on:click="$dispatch('close-modal', 'submit-vat')">Review Again</x-erp.action-button>
                    <x-erp.action-button variant="primary">Submit to ZIMRA</x-erp.action-button>
                </div>
            </div>
        </x-modal>
    </x-erp.page>
</x-app-layout>
