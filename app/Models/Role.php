<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $fillable = [
        'name',
        'display_name',
        'description',
        'level',
    ];

    protected $casts = [
        'level' => 'integer',
    ];

    // ── Relasi ────────────────────────────────────────────────────

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    // ── Helpers ───────────────────────────────────────────────────

    public function hasPermission(string $permission): bool
    {
        return $this->permissions->pluck('name')->contains($permission);
    }

    /** Dashboard redirect path berdasarkan role */
    public function dashboardRoute(): string
    {
        return match ($this->name) {
            'superadmin' => 'superadmin.dashboard',
            'admin'      => 'admin.dashboard',
            'finance'    => 'finance.dashboard',
            'sponsor'    => 'sponsor.dashboard',
            'customer'   => 'customer.dashboard',
            default      => 'home',
        };
    }
}
