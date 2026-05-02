<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserAccessController extends Controller
{
    public function index(): View
    {
        $users = User::with('roles')
            ->orderBy('name')
            ->paginate(30);

        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user): View
    {
        $companyId = $user->company_id ?: company_id();

        $roles = Role::with('permissions')
            ->where('company_id', $companyId)
            ->orderBy('name')
            ->get();

        $permissions = Permission::orderBy('module')
            ->orderBy('resource')
            ->orderBy('action')
            ->get()
            ->groupBy('module');

        $userRoleIds = $user->roles()->pluck('roles.id')->toArray();

        return view('admin.users.edit', compact('user', 'roles', 'permissions', 'userRoleIds'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'is_active' => ['nullable', 'boolean'],
            'role_ids' => ['array'],
            'role_ids.*' => ['integer', 'exists:roles,id'],
        ]);

        $user->update([
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ]);

        $allowedRoleIds = Role::where('company_id', $user->company_id ?: company_id())
            ->whereIn('id', $validated['role_ids'] ?? [])
            ->pluck('id')
            ->toArray();

        $user->roles()->sync($allowedRoleIds);

        return redirect()
            ->route('admin.users.edit', $user)
            ->with('success', 'User roles and access updated successfully.');
    }
}
