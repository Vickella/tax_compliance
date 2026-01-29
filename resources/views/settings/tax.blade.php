<x-app-layout>
    <x-erp.page title="Tax Settings" subtitle="Configure VAT, withholding tax, and statutory parameters.">
        <x-slot name="actions">
            <x-erp.action-button variant="primary">Save Settings</x-erp.action-button>
        </x-slot>

        <x-erp.section>
            <h3 class="text-sm font-semibold text-white">VAT configuration</h3>
            <div class="mt-4 grid grid-cols-1 lg:grid-cols-3 gap-4">
                <div>
                    <label class="text-xs text-white/70">Standard VAT rate</label>
                    <input type="number" step="0.0001" placeholder="15.0000" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                </div>
                <div>
                    <label class="text-xs text-white/70">Zero-rated code</label>
                    <input type="text" placeholder="VAT_ZERO" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                </div>
                <div>
                    <label class="text-xs text-white/70">Exempt code</label>
                    <input type="text" placeholder="VAT_EXEMPT" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                </div>
            </div>
        </x-erp.section>

        <x-erp.section>
            <h3 class="text-sm font-semibold text-white">Withholding & levies</h3>
            <div class="mt-4 grid grid-cols-1 lg:grid-cols-3 gap-4">
                <div>
                    <label class="text-xs text-white/70">Withholding tax rate</label>
                    <input type="number" step="0.0001" placeholder="10.0000" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                </div>
                <div>
                    <label class="text-xs text-white/70">AIDS levy rate</label>
                    <input type="number" step="0.0001" placeholder="3.0000" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                </div>
                <div>
                    <label class="text-xs text-white/70">Enable ZIMDEF</label>
                    <select class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white">
                        <option>Yes</option>
                        <option>No</option>
                    </select>
                </div>
            </div>
        </x-erp.section>
    </x-erp.page>
</x-app-layout>
