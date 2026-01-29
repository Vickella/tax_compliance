<x-app-layout>
    <x-erp.page title="Create Customer" subtitle="Add customer master data with VAT and credit limits.">
        <x-slot name="actions">
            <x-erp.action-link href="{{ route('crm.customers.index') }}">Back to Customers</x-erp.action-link>
        </x-slot>

        <form method="POST" action="{{ route('crm.customers.store') }}" class="space-y-6">
            @csrf
            <x-erp.section>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="text-xs text-white/70">Customer code</label>
                        <input type="text" name="code" class="mt-2 w-full rounded-2xl bg-white/10 border border-white/10 text-white px-4 py-2.5" required />
                    </div>
                    <div>
                        <label class="text-xs text-white/70">Customer name</label>
                        <input type="text" name="name" class="mt-2 w-full rounded-2xl bg-white/10 border border-white/10 text-white px-4 py-2.5" required />
                    </div>
                    <div>
                        <label class="text-xs text-white/70">Currency</label>
                        <select name="currency" class="mt-2 w-full rounded-2xl bg-white/10 border border-white/10 text-white px-4 py-2.5">
                            <option value="">Default</option>
                            @foreach($currencies ?? [] as $currency)
                                <option value="{{ $currency->code }}">{{ $currency->code }}</option>
                            @endforeach
                        </select>
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
                        <label class="text-xs text-white/70">Credit limit</label>
                        <input type="number" step="0.01" name="credit_limit" class="mt-2 w-full rounded-2xl bg-white/10 border border-white/10 text-white px-4 py-2.5" />
                    </div>
                    <div class="lg:col-span-2">
                        <label class="text-xs text-white/70">Address</label>
                        <input type="text" name="address" class="mt-2 w-full rounded-2xl bg-white/10 border border-white/10 text-white px-4 py-2.5" />
                    </div>
                    <div>
                        <label class="text-xs text-white/70">Phone</label>
                        <input type="text" name="phone" class="mt-2 w-full rounded-2xl bg-white/10 border border-white/10 text-white px-4 py-2.5" />
                    </div>
                    <div>
                        <label class="text-xs text-white/70">Email</label>
                        <input type="email" name="email" class="mt-2 w-full rounded-2xl bg-white/10 border border-white/10 text-white px-4 py-2.5" />
                    </div>
                </div>
            </x-erp.section>

            <div class="flex justify-end gap-3">
                <x-erp.action-button variant="muted" type="reset">Reset</x-erp.action-button>
                <x-erp.action-button variant="primary" type="submit">Save Customer</x-erp.action-button>
            </div>
        </form>
    </x-erp.page>
</x-app-layout>
