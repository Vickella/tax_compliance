@extends('layouts.app')

@section('page_title', 'Create VAT Return')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-white">VAT Return - {{ \Carbon\Carbon::parse($periodStart)->format('F Y') }}</h2>
        <p class="text-sm text-slate-400">Period: {{ \Carbon\Carbon::parse($periodStart)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($periodEnd)->format('d/m/Y') }}</p>
    </div>

    <form method="POST" action="{{ route('modules.tax.vat.store') }}">
        @csrf
        <input type="hidden" name="period_start" value="{{ $periodStart }}">
        <input type="hidden" name="period_end" value="{{ $periodEnd }}">
        <input type="hidden" name="vat_rate" value="{{ $calculation['vat_rate'] }}">
        <input type="hidden" name="output_vat" value="{{ $calculation['output_vat'] }}">
        <input type="hidden" name="input_vat" value="{{ $calculation['input_vat'] }}">
        <input type="hidden" name="vat_payable" value="{{ $calculation['net_vat_payable'] ?? $calculation['vat_payable'] ?? 0 }}">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left Column - Output VAT --}}
            <div class="lg:col-span-1">
                <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-5 h-full">
                    <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
                        <span class="w-2 h-2 bg-amber-400 rounded-full"></span>
                        Output VAT
                    </h3>
                    <div class="text-2xl font-bold text-amber-400 mb-4">${{ number_format($calculation['output_vat'], 2) }}</div>
                    
                    <div class="space-y-2 max-h-96 overflow-y-auto">
                        @foreach($calculation['details']['output'] ?? [] as $item)
                        <div class="flex justify-between items-center p-2 bg-black/30 rounded-lg">
                            <div>
                                <div class="text-xs text-slate-400">{{ $item['code'] }}</div>
                                <div class="text-sm text-white">{{ $item['name'] }}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm text-white">${{ number_format($item['amount'], 2) }}</div>
                                <div class="text-xs text-amber-400">VAT: ${{ number_format($item['vat'], 2) }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Middle Column - Input VAT --}}
            <div class="lg:col-span-1">
                <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-5 h-full">
                    <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
                        <span class="w-2 h-2 bg-emerald-400 rounded-full"></span>
                        Input VAT
                    </h3>
                    <div class="text-2xl font-bold text-emerald-400 mb-4">${{ number_format($calculation['input_vat'], 2) }}</div>
                    
                    <div class="space-y-2 max-h-96 overflow-y-auto">
                        @foreach($calculation['details']['input'] ?? [] as $item)
                        <div class="flex justify-between items-center p-2 bg-black/30 rounded-lg">
                            <div>
                                <div class="text-xs text-slate-400">{{ $item['code'] }}</div>
                                <div class="text-sm text-white">{{ $item['name'] }}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm text-white">${{ number_format($item['amount'], 2) }}</div>
                                <div class="text-xs text-emerald-400">VAT: ${{ number_format($item['vat'], 2) }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Right Column - Summary --}}
            <div class="lg:col-span-1">
                <div class="bg-black/20 rounded-xl ring-1 ring-white/10 p-5">
                    <h3 class="text-sm font-semibold text-white mb-4">VAT Summary</h3>
                    
                    <div class="space-y-4">
                        <div class="p-4 bg-black/40 rounded-lg">
                            <div class="text-xs text-slate-400">VAT Rate</div>
                            <div class="text-xl font-semibold text-white">{{ $calculation['vat_rate'] }}%</div>
                        </div>

                        <div class="p-4 bg-black/40 rounded-lg">
                            <div class="text-xs text-slate-400">Tax Fraction</div>
                            <div class="text-xl font-semibold text-white">{{ $calculation['tax_fraction'] }}</div>
                        </div>

                        <div class="border-t border-white/10 my-4"></div>

                        <div class="flex justify-between items-center">
                            <span class="text-slate-400">Output VAT</span>
                            <span class="text-white font-semibold">${{ number_format($calculation['output_vat'], 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-400">Input VAT</span>
                            <span class="text-white font-semibold">${{ number_format($calculation['input_vat'], 2) }}</span>
                        </div>

                        <div class="border-t border-white/10 my-4"></div>

                        @php
                            $netPayable = $calculation['net_vat_payable'] ?? $calculation['vat_payable'] ?? 0;
                        @endphp
                        <div class="p-4 rounded-lg {{ $netPayable > 0 ? 'bg-amber-600/20' : 'bg-emerald-600/20' }}">
                            <div class="text-xs text-slate-400">VAT {{ $netPayable > 0 ? 'Payable' : 'Refundable' }}</div>
                            <div class="text-2xl font-bold {{ $netPayable > 0 ? 'text-amber-400' : 'text-emerald-400' }}">
                                ${{ number_format(abs($netPayable), 2) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Notes --}}
        <div class="mt-6 bg-black/20 rounded-xl ring-1 ring-white/10 p-5">
            <label class="block text-xs text-slate-400 mb-1">Notes / Comments</label>
            <textarea name="notes" rows="2" 
                      class="w-full px-3 py-2 rounded-lg bg-black/30 text-white border border-white/10 focus:border-indigo-500 outline-none"
                      placeholder="Any additional notes about this return...">{{ old('notes') }}</textarea>
        </div>

        {{-- Form Actions --}}
        <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-white/10">
            <a href="{{ route('modules.tax.vat.index') }}" 
               class="px-4 py-2 rounded-lg bg-white/5 hover:bg-white/10 ring-1 ring-white/10 text-sm transition-colors">
                Cancel
            </a>
            <button type="submit" name="action" value="save" 
                    class="px-4 py-2 rounded-lg bg-white/10 hover:bg-white/15 ring-1 ring-white/10 text-sm transition-colors">
                💾 Save Draft
            </button>
            <button type="submit" name="action" value="submit" 
                    class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Submit Return
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const saveBtn = document.querySelector('button[value="save"]');
    const submitBtn = document.querySelector('button[value="submit"]');
    
    console.log('Form found:', !!form);
    console.log('Save button found:', !!saveBtn);
    console.log('Submit button found:', !!submitBtn);
    
    if (form) {
        form.addEventListener('submit', function(e) {
            console.log('Form submitting...');
            console.log('Action:', new FormData(this).get('action'));
            console.log('Period:', new FormData(this).get('period_start'), 'to', new FormData(this).get('period_end'));
            
            // Don't prevent default, just log
        });
    }
    
    // Also log any flash messages
    @if(session('error'))
        console.error('Flash error:', '{{ session('error') }}');
    @endif
    
    @if(session('success'))
        console.log('Flash success:', '{{ session('success') }}');
    @endif
    
    @if($errors->any())
        console.error('Validation errors:', @json($errors->all()));
    @endif
});
</script>
@endpush