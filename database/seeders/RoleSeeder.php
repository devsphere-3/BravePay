<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name'         => 'superadmin',
                'display_name' => 'Super Administrator',
                'description'  => 'Akses penuh ke seluruh sistem. Mengelola role, permission, user, dan konfigurasi.',
                'level'        => 100,
            ],
            [
                'name'         => 'admin',
                'display_name' => 'Administrator',
                'description'  => 'Operasional harian: kelola tiket, peserta, kompetisi, dan scan check-in.',
                'level'        => 50,
            ],
            [
                'name'         => 'finance',
                'display_name' => 'Team Finance',
                'description'  => 'Akses ke data transaksi, pembayaran, dan laporan keuangan.',
                'level'        => 40,
            ],
            [
                'name'         => 'sponsor',
                'display_name' => 'Sponsor',
                'description'  => 'Akses terbatas ke statistik peserta dan informasi event yang disponsori.',
                'level'        => 30,
            ],
            [
                'name'         => 'customer',
                'display_name' => 'Customer',
                'description'  => 'Pengguna akhir yang melakukan pendaftaran dan pembelian tiket.',
                'level'        => 10,
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['name' => $role['name']], $role);
        }

        $this->command->info('✓ Roles seeded.');
        $this->command->table(
            ['Name', 'Display Name', 'Level'],
            collect($roles)->map(fn ($r) => [$r['name'], $r['display_name'], $r['level']])->toArray()
        );
    }
}
