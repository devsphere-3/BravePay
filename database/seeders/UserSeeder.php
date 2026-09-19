<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $superadminRole = Role::where('name', 'superadmin')->value('id');
        $adminRole      = Role::where('name', 'admin')->value('id');
        $financeRole    = Role::where('name', 'finance')->value('id');
        $sponsorRole    = Role::where('name', 'sponsor')->value('id');

        $accounts = [
            // ── SuperAdmin ──────────────────────────────────────────────
            // Akun lama admin@bravepay.id di-replace dengan superadmin@bravepay.id
            [
                'name'              => 'Super Administrator',
                'email'             => 'superadmin@bravepay.id',
                'password'          => Hash::make('SuperAdmin@2026'),
                'role_id'           => $superadminRole,
                'status'            => 'active',
                'email_verified_at' => now(),
            ],

            // ── Admin Operasional ───────────────────────────────────────
            [
                'name'              => 'Admin BravePay',
                'email'             => 'admin@bravepay.id',
                'password'          => Hash::make('Admin@2026'),
                'role_id'           => $adminRole,
                'status'            => 'active',
                'email_verified_at' => now(),
            ],

            // ── Team Finance ────────────────────────────────────────────
            [
                'name'              => 'Finance BravePay',
                'email'             => 'finance@bravepay.id',
                'password'          => Hash::make('Finance@2026'),
                'role_id'           => $financeRole,
                'status'            => 'active',
                'email_verified_at' => now(),
            ],

            // ── Sponsor ─────────────────────────────────────────────────
            [
                'name'              => 'Sponsor BravePay',
                'email'             => 'sponsor@bravepay.id',
                'password'          => Hash::make('Sponsor@2026'),
                'role_id'           => $sponsorRole,
                'status'            => 'active',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($accounts as $account) {
            User::updateOrCreate(
                ['email' => $account['email']],
                $account
            );
        }

        $this->command->info('✓ System accounts seeded:');
        $this->command->table(
            ['Email', 'Role', 'Password'],
            [
                ['superadmin@bravepay.id', 'superadmin', 'SuperAdmin@2026'],
                ['admin@bravepay.id',      'admin',      'Admin@2026'],
                ['finance@bravepay.id',    'finance',    'Finance@2026'],
                ['sponsor@bravepay.id',    'sponsor',    'Sponsor@2026'],
            ]
        );
    }
}
