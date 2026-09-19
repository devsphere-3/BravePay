<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role_id',
        'status',
        'last_login_at',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at'     => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ── Relasi ────────────────────────────────────────────────────

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    /** Override permissions (user_permissions table) */
    public function permissionOverrides(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'user_permissions')
                    ->withPivot(['granted', 'reason', 'granted_by', 'expires_at', 'created_at']);
    }

    // ── Role Helpers ──────────────────────────────────────────────

    public function hasRole(string $role): bool
    {
        return $this->role?->name === $role;
    }

    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role?->name, $roles, true);
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('superadmin');
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isFinance(): bool
    {
        return $this->hasRole('finance');
    }

    public function isSponsor(): bool
    {
        return $this->hasRole('sponsor');
    }

    public function isCustomer(): bool
    {
        return $this->hasRole('customer');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    // ── Permission Helpers ────────────────────────────────────────

    /**
     * Cek apakah user memiliki permission tertentu.
     * Urutan cek: user_permissions override → role_permissions.
     * Hasil di-cache 5 menit per user.
     */
    public function hasPermission(string $permission): bool
    {
        $permissions = $this->resolvePermissions();

        return in_array($permission, $permissions, true);
    }

    /**
     * Resolve permission akhir setelah memperhitungkan override.
     * Hasil di-cache di memory request (bukan Redis) agar simple.
     */
    public function resolvePermissions(): array
    {
        return Cache::remember(
            "user_permissions_{$this->id}",
            now()->addMinutes(5),
            function () {
                // 1. Ambil semua permission dari role
                $rolePermissions = $this->role
                    ? $this->role->permissions->pluck('name')->toArray()
                    : [];

                // 2. Ambil override dari user_permissions (belum expired)
                $overrides = $this->permissionOverrides()
                    ->where(function ($query) {
                        $query->whereNull('user_permissions.expires_at')
                              ->orWhere('user_permissions.expires_at', '>', now());
                    })
                    ->get();

                // 3. Terapkan override
                $granted  = $overrides->where('pivot.granted', true)->pluck('name')->toArray();
                $revoked  = $overrides->where('pivot.granted', false)->pluck('name')->toArray();

                $result = array_unique(array_merge($rolePermissions, $granted));
                $result = array_values(array_diff($result, $revoked));

                return $result;
            }
        );
    }

    /** Hapus cache permissions user ini (panggil saat role/permission diubah) */
    public function clearPermissionCache(): void
    {
        Cache::forget("user_permissions_{$this->id}");
    }

    /** URL dashboard berdasarkan role */
    public function dashboardUrl(): string
    {
        return match ($this->role?->name) {
            'superadmin' => route('superadmin.dashboard'),
            'admin'      => route('admin.dashboard'),
            'finance'    => route('finance.dashboard'),
            'sponsor'    => route('sponsor.dashboard'),
            'customer'   => route('customer.dashboard'),
            default      => route('home'),
        };
    }
}
