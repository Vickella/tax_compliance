@extends('layouts.app')

@section('page_title', 'Forecast Profiles')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-semibold text-white">Forecast Profiles - {{ $year }}</h2>
            <p class="text-sm text-slate-400">Configure how each account is forecasted</p>
        </div>
        <a href="{{ route('modules.tax.qpd.forecast.dashboard', ['year' => $year]) }}" 
           class="px-4 py-2 bg-white/10 hover:bg-white/15 rounded-lg">
            ← Back to Dashboard
        </a>
    </div>

    <div class="bg-black/20 rounded-xl ring-1 ring-white/10 overflow-hidden">
        <div class="p-4 border-b border-white/10">
            <h3 class="text-sm font-semibold text-white">Account Forecast Settings</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-white/5">
                    <tr>
                        <th class="px-4 py-3 text-left text-slate-300">Account Code</th>
                        <th class="px-4 py-3 text-left text-slate-300">Account Name</th>
                        <th class="px-4 py-3 text-left text-slate-300">Type</th>
                        <th class="px-4 py-3 text-left text-slate-300">Forecast Method</th>
                        <th class="px-4 py-3 text-right text-slate-300">Fixed Amount</th>
                        <th class="px-4 py-3 text-right text-slate-300">Growth Rate</th>
                        <th class="px-4 py-3 text-center text-slate-300">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                    @foreach($profiles as $profile)
                    <tr>
                        <td class="px-4 py-3 font-mono">{{ $profile->account->code ?? 'N/A' }}</td>
                        <td class="px-4 py-3">{{ $profile->account->name ?? 'Company Level' }}</td>
                        <td class="px-4 py-3">{{ $profile->account->type ?? 'N/A' }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs 
                                @if($profile->forecast_method == 'linear') bg-blue-600/20 text-blue-300
                                @elseif($profile->forecast_method == 'fixed') bg-emerald-600/20 text-emerald-300
                                @else bg-amber-600/20 text-amber-300
                                @endif">
                                {{ ucfirst($profile->forecast_method) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">${{ number_format($profile->fixed_amount ?? 0, 2) }}</td>
                        <td class="px-4 py-3 text-right">{{ $profile->growth_rate ?? 0 }}%</td>
                        <td class="px-4 py-3 text-center">
                            <button onclick="editProfile({{ $profile->id }})" 
                                    class="text-indigo-400 hover:text-indigo-300 text-xs">
                                Edit
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Edit Modal (simplified) --}}
    <div id="editModal" class="fixed inset-0 bg-black/80 hidden items-center justify-center z-50">
        <div class="bg-slate-900 rounded-xl ring-1 ring-white/10 p-6 max-w-md w-full">
            <h3 class="text-lg font-semibold text-white mb-4">Edit Forecast Profile</h3>
            <form method="POST" action="{{ route('modules.tax.qpd.forecast.profiles.store') }}">
                @csrf
                <input type="hidden" name="profile_id" id="profile_id">
                <input type="hidden" name="tax_year" value="{{ $year }}">
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs text-slate-400 mb-1">Forecast Method</label>
                        <select name="forecast_method" id="edit_method" required
                                class="w-full px-3 py-2 rounded-lg bg-black/30 text-white border border-white/10 focus:border-indigo-500 outline-none">
                            <option value="linear">Linear Growth</option>
                            <option value="fixed">Fixed Amount</option>
                            <option value="average">Monthly Average</option>
                        </select>
                    </div>
                    
                    <div id="fixed_amount_field" class="hidden">
                        <label class="block text-xs text-slate-400 mb-1">Fixed Monthly Amount</label>
                        <input type="number" name="fixed_amount" id="edit_fixed" step="0.01" min="0"
                               class="w-full px-3 py-2 rounded-lg bg-black/30 text-white border border-white/10 focus:border-indigo-500 outline-none">
                    </div>
                    
                    <div id="growth_rate_field" class="hidden">
                        <label class="block text-xs text-slate-400 mb-1">Annual Growth Rate (%)</label>
                        <input type="number" name="growth_rate" id="edit_growth" step="0.1" min="-100" max="100"
                               class="w-full px-3 py-2 rounded-lg bg-black/30 text-white border border-white/10 focus:border-indigo-500 outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-xs text-slate-400 mb-1">Notes</label>
                        <textarea name="notes" id="edit_notes" rows="2"
                                  class="w-full px-3 py-2 rounded-lg bg-black/30 text-white border border-white/10 focus:border-indigo-500 outline-none"></textarea>
                    </div>
                </div>
                
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeModal()" 
                            class="px-4 py-2 bg-white/5 hover:bg-white/10 rounded-lg">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editProfile(id) {
    // Fetch profile data via AJAX or use data attributes
    // For now, just show modal
    document.getElementById('editModal').classList.remove('hidden');
    document.getElementById('editModal').classList.add('flex');
}

function closeModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.getElementById('editModal').classList.remove('flex');
}

document.getElementById('edit_method').addEventListener('change', function() {
    const method = this.value;
    document.getElementById('fixed_amount_field').classList.add('hidden');
    document.getElementById('growth_rate_field').classList.add('hidden');
    
    if (method === 'fixed') {
        document.getElementById('fixed_amount_field').classList.remove('hidden');
    } else if (method === 'linear') {
        document.getElementById('growth_rate_field').classList.remove('hidden');
    }
});
</script>
@endsection