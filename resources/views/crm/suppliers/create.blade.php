<x-app-layout>
    <x-erp.page title="Create Supplier" subtitle="Register supplier VAT details and withholding flags.">
        <x-slot name="actions">
            <x-erp.action-link href="{{ route('crm.suppliers.index') }}">Back to Suppliers</x-erp.action-link>
        </x-slot>

        <form method="POST" action="{{ route('crm.suppliers.store') }}" class="space-y-6">
            @csrf
            <x-erp.section>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="text-xs text-white/70">Supplier code</label>
                        <input type="text" name="code" class="mt-2 w-full rounded-2xl bg-white/10 border border-white/10 text-white px-4 py-2.5" required />
                    </div>
                    <div>
                        <label class="text-xs text-white/70">Supplier name</label>
                        <input type="text" name="name" class="mt-2 w-full rounded-2xl bg-white/10 border border-white/10 text-white px-4 py-2.5" required />
                    </div>
                    <div>
                        <label class="text-xs text-white/70">TIN</label>
                        <input type="text" name="tin" class="mt-2 w-full rounded-2xl bg-white/10 border border-white/10 text-white px-4 py-2.5" />
                    </div>
                    <div>
                        <label class="text-xs text-white/70">VAT number</label>
                        <input type="text" name="vat_number" class="mt-2 w-full rounded-2xl bg-white/10 border border-white/10 text-white px-4 py-2.5" />
                    </div>
                    <div>
                        <label class="text-xs text-white/70">Withholding tax flag</label>
                        <select name="withholding_tax_flag" class="mt-2 w-full rounded-2xl bg-white/10 border border-white/10 text-white px-4 py-2.5">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                    <div class="lg:col-span-3">
                        <label class="text-xs text-white/70">Bank details</label>
                        <textarea name="bank_details" rows="3" class="mt-2 w-full rounded-2xl bg-white/10 border border-white/10 text-white px-4 py-2.5"></textarea>
                    </div>
                </div>
            </x-erp.section>

            <div class="flex justify-end gap-3">
                <x-erp.action-button variant="muted" type="reset">Reset</x-erp.action-button>
                <x-erp.action-button variant="primary" type="submit">Save Supplier</x-erp.action-button>
            </div>
        </form>
    </x-erp.page>
</x-app-layout>
