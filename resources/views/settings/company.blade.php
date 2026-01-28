<x-app-layout>
    <x-erp.page title="Company Settings" subtitle="Maintain company profile and base currency.">
        <x-slot name="actions">
            <x-erp.action-button variant="primary">Save Changes</x-erp.action-button>
        </x-slot>

        <x-erp.section>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <div>
                    <label class="text-xs text-white/70">Company name</label>
                    <input type="text" placeholder="Tax Compliance" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                </div>
                <div>
                    <label class="text-xs text-white/70">Trading name</label>
                    <input type="text" placeholder="Tax Compliance" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                </div>
                <div>
                    <label class="text-xs text-white/70">Company code</label>
                    <input type="text" placeholder="TC-01" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                </div>
                <div>
                    <label class="text-xs text-white/70">TIN</label>
                    <input type="text" placeholder="TIN-1001" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                </div>
                <div>
                    <label class="text-xs text-white/70">VAT number</label>
                    <input type="text" placeholder="263-445566-2" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                </div>
                <div>
                    <label class="text-xs text-white/70">Base currency</label>
                    <select class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white">
                        <option>ZIG</option>
                        <option>USD</option>
                        <option>ZAR</option>
                    </select>
                </div>
                <div class="lg:col-span-2">
                    <label class="text-xs text-white/70">Address</label>
                    <input type="text" placeholder="1 Enterprise Road, Harare" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                </div>
                <div>
                    <label class="text-xs text-white/70">Phone</label>
                    <input type="text" placeholder="+263 4 700 000" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                </div>
                <div>
                    <label class="text-xs text-white/70">Email</label>
                    <input type="email" placeholder="info@tax.com" class="mt-2 w-full rounded-xl bg-white/10 border border-white/10 text-white" />
                </div>
            </div>
        </x-erp.section>
    </x-erp.page>
</x-app-layout>
