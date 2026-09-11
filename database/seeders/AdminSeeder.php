<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@bravepay.id'],
            [
                'name'              => 'Admin BravePay',
                'email'             => 'admin@bravepay.id',
                'password'          => Hash::make('BravePay@2026'),
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Admin user created/updated:');
        $this->command->table(
            ['Field', 'Value'],
            [
                ['Email',    'admin@bravepay.id'],
                ['Password', 'BravePay@2026'],
                ['URL',      url('/admin/login')],
            ]
        );
    }
}
