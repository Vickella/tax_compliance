@extends('layouts.app')

@section('page_title', 'Manage User Access')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-white">Manage Access: {{ $user->name }}</h2>
        <p class="text-sm text-slate-400">{{ $user->email }}</p>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 rounded-lg bg-emerald-600/20 text-emerald-300 ring-1 ring-emerald-600/30">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="bg-black/20 rounded-xl ring-1 ring-white/10 p-6">
        @csrf
        @method('PUT')

        <label class="flex items-center gap-3 mb-6 text-white">
            <input type="checkbox" name="is_active" value="1" {{ $user->is_active ? 'checked' : '' }}>
            Active user
        </label>

        <h3 class="text-white font-semibold mb-3">Assign Roles</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 mb-6">
            @foreach($roles as $role)
                <label class="p-4 rounded-lg bg-black/30 ring-1 ring-white/10 text-white flex items-start gap-3">
                    <input type="checkbox" name="role_ids[]" value="{{ $role->id }}" {{ in_array($role->id, $userRoleIds) ? 'checked' : '' }}>
                    <span>
                        <span class="font-semibold">{{ $role->name }}</span>
                        <span class="block text-xs text-slate-400">{{ $role->permissions->count() }} permission(s)</span>
                    </span>
                </label>
            @endforeach
        </div>

        <div class="border-t border-white/10 pt-5 mt-5">
            <h3 class="text-white font-semibold mb-3">Permission Reference</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($permissions as $module => $modulePermissions)
                    <div class="rounded-lg bg-black/30 ring-1 ring-white/10 p-4">
                        <h4 class="text-indigo-300 font-semibold mb-2">{{ ucfirst($module) }}</h4>
                        <ul class="text-xs text-slate-300 space-y-1">
                            @foreach($modulePermissions as $permission)
                                <li><code>{{ $permission->code }}</code> — {{ $permission->description }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-6">
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 rounded-lg bg-white/5 hover:bg-white/10 text-white">Back</a>
            <button class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white">Save Access</button>
        </div>
    </form>
</div>
@endsection
