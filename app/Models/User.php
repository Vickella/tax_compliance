<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'company_id',
        'name',
        'email',
        'password',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function permissions()
    {
        return Permission::query()
            ->select('permissions.*')
            ->join('role_permissions', 'permissions.id', '=', 'role_permissions.permission_id')
            ->join('user_roles', 'role_permissions.role_id', '=', 'user_roles.role_id')
            ->where('user_roles.user_id', $this->id)
            ->distinct();
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('Admin') || $this->hasRole('Administrator');
    }

    public function hasRole(string $role): bool
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function hasPermission(string $code): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        return $this->permissions()->where('permissions.code', $code)->exists();
    }

    public function assignRoleByName(string $roleName, ?int $companyId = null): void
    {
        $companyId = $companyId ?: $this->company_id;

        if (! $companyId) {
            return;
        }

        $role = Role::firstOrCreate([
            'company_id' => $companyId,
            'name' => $roleName,
        ]);

        $this->roles()->syncWithoutDetaching([$role->id]);
    }
}
