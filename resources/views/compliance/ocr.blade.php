<x-app-layout>
    <x-erp.page title="OCR / QR Import" subtitle="Scan fiscal invoices and auto-populate VAT fields.">
        <x-slot name="actions">
            <x-erp.action-button variant="primary">New Scan</x-erp.action-button>
            <x-erp.action-button>Export</x-erp.action-button>
        </x-slot>

        <x-erp.section>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <div class="rounded-2xl bg-black/10 border border-white/10 p-5">
                    <p class="text-xs text-white/70">Scans in queue</p>
                    <p class="text-lg font-semibold text-white">0</p>
                </div>
                <div class="rounded-2xl bg-black/10 border border-white/10 p-5">
                    <p class="text-xs text-white/70">Processed today</p>
                    <p class="text-lg font-semibold text-white">0</p>
                </div>
                <div class="rounded-2xl bg-black/10 border border-white/10 p-5">
                    <p class="text-xs text-white/70">Avg confidence</p>
                    <p class="text-lg font-semibold text-white">--</p>
                </div>
            </div>
            <div class="mt-6">
                <div class="border border-dashed border-white/20 rounded-2xl p-10 text-center text-white/70">
                    Drop fiscal invoice images or PDFs here to extract QR and VAT fields.
                </div>
            </div>
        </x-erp.section>

        <x-erp.section>
            <div class="mt-1 overflow-x-auto">
                <table class="min-w-full text-sm text-white/80">
                    <thead class="text-xs uppercase text-white/50 border-b border-white/10">
                        <tr>
                            <th class="py-3 text-left">Scan ID</th>
                            <th class="py-3 text-left">Source</th>
                            <th class="py-3 text-left">Confidence</th>
                            <th class="py-3 text-left">Status</th>
                            <th class="py-3 text-left">Processed At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="py-4 text-white/50" colspan="5">No scans processed yet.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-erp.section>
    </x-erp.page>
</x-app-layout>
