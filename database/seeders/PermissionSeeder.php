<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [

            // ── CUSTOMER ──────────────────────────────────────────────────
            ['name' => 'customer.view_dashboard',   'display_name' => 'Lihat Dashboard Customer',          'group_name' => 'customer'],
            ['name' => 'customer.purchase',         'display_name' => 'Beli / Daftar Tiket',               'group_name' => 'customer'],
            ['name' => 'customer.view_own_ticket',  'display_name' => 'Lihat E-Ticket Milik Sendiri',      'group_name' => 'customer'],
            ['name' => 'customer.download_ticket',  'display_name' => 'Download E-Ticket',                 'group_name' => 'customer'],
            ['name' => 'customer.view_own_payment', 'display_name' => 'Lihat Status Pembayaran Sendiri',   'group_name' => 'customer'],
            ['name' => 'customer.view_own_history', 'display_name' => 'Lihat History Transaksi Sendiri',   'group_name' => 'customer'],
            ['name' => 'customer.report_error',     'display_name' => 'Laporkan Kesalahan / Error',        'group_name' => 'customer'],
            ['name' => 'customer.view_events',      'display_name' => 'Lihat Daftar Event & Tiket',        'group_name' => 'customer'],

            // ── ADMIN ─────────────────────────────────────────────────────
            ['name' => 'admin.view_dashboard',      'display_name' => 'Lihat Dashboard Admin',             'group_name' => 'admin'],
            ['name' => 'admin.view_customers',      'display_name' => 'Lihat Daftar Customer',             'group_name' => 'admin'],
            ['name' => 'admin.view_registrations',  'display_name' => 'Lihat Data Registrasi',             'group_name' => 'admin'],
            ['name' => 'admin.view_participants',   'display_name' => 'Lihat Data Peserta',                'group_name' => 'admin'],
            ['name' => 'admin.scan_ticket',         'display_name' => 'Scan QR Tiket (Check-in)',          'group_name' => 'admin'],
            ['name' => 'admin.verify_checkin',      'display_name' => 'Verifikasi Kehadiran Peserta',      'group_name' => 'admin'],
            ['name' => 'admin.manage_ticket',       'display_name' => 'Kelola Tiket (Resend dll)',         'group_name' => 'admin'],
            ['name' => 'admin.manage_competition',  'display_name' => 'CRUD Data Kompetisi / Event',       'group_name' => 'admin'],
            ['name' => 'admin.manage_price',        'display_name' => 'Atur Harga Tiket',                  'group_name' => 'admin'],
            ['name' => 'admin.contact_customer',    'display_name' => 'Kirim Pesan ke Customer',           'group_name' => 'admin'],
            ['name' => 'admin.view_reports',        'display_name' => 'Lihat Laporan Peserta',             'group_name' => 'admin'],
            ['name' => 'admin.view_activity_logs',  'display_name' => 'Lihat Log Aktivitas Sendiri',       'group_name' => 'admin'],
            ['name' => 'admin.delete_registration', 'display_name' => 'Hapus Data Registrasi',             'group_name' => 'admin'],

            // ── FINANCE ───────────────────────────────────────────────────
            ['name' => 'finance.view_dashboard',    'display_name' => 'Lihat Dashboard Finance',           'group_name' => 'finance'],
            ['name' => 'finance.view_payments',     'display_name' => 'Lihat Semua Data Pembayaran',       'group_name' => 'finance'],
            ['name' => 'finance.view_transactions', 'display_name' => 'Lihat Semua Transaksi',             'group_name' => 'finance'],
            ['name' => 'finance.view_reports',      'display_name' => 'Lihat Laporan Keuangan',            'group_name' => 'finance'],
            ['name' => 'finance.export_reports',    'display_name' => 'Export Laporan Keuangan',           'group_name' => 'finance'],
            ['name' => 'finance.view_registrations','display_name' => 'Lihat Data Registrasi (Read Only)', 'group_name' => 'finance'],

            // ── SPONSOR ───────────────────────────────────────────────────
            ['name' => 'sponsor.view_dashboard',    'display_name' => 'Lihat Dashboard Sponsor',           'group_name' => 'sponsor'],
            ['name' => 'sponsor.view_statistics',   'display_name' => 'Lihat Statistik Jumlah Peserta',    'group_name' => 'sponsor'],
            ['name' => 'sponsor.view_participants', 'display_name' => 'Lihat Data Peserta (Agregat)',       'group_name' => 'sponsor'],
            ['name' => 'sponsor.view_event_info',   'display_name' => 'Lihat Info Event yang Disponsori',  'group_name' => 'sponsor'],
            ['name' => 'sponsor.view_reports',      'display_name' => 'Lihat Laporan Sponsorship',         'group_name' => 'sponsor'],

            // ── SUPERADMIN ────────────────────────────────────────────────
            ['name' => 'superadmin.view_dashboard',      'display_name' => 'Lihat Dashboard SuperAdmin',      'group_name' => 'superadmin'],
            ['name' => 'superadmin.view_all_data',        'display_name' => 'Lihat Semua Data Sistem',          'group_name' => 'superadmin'],
            ['name' => 'superadmin.manage_customers',     'display_name' => 'Kelola Akun Customer',             'group_name' => 'superadmin'],
            ['name' => 'superadmin.manage_admins',        'display_name' => 'Kelola Akun Admin',                'group_name' => 'superadmin'],
            ['name' => 'superadmin.manage_finance',       'display_name' => 'Kelola Akun Finance',              'group_name' => 'superadmin'],
            ['name' => 'superadmin.manage_sponsors',      'display_name' => 'Kelola Akun Sponsor',              'group_name' => 'superadmin'],
            ['name' => 'superadmin.manage_roles',         'display_name' => 'Kelola Role Sistem',               'group_name' => 'superadmin'],
            ['name' => 'superadmin.manage_permissions',   'display_name' => 'Kelola Permission Sistem',         'group_name' => 'superadmin'],
            ['name' => 'superadmin.manage_events',        'display_name' => 'Kelola Event / Kompetisi',         'group_name' => 'superadmin'],
            ['name' => 'superadmin.manage_tickets',       'display_name' => 'Kelola Tiket Sistem',              'group_name' => 'superadmin'],
            ['name' => 'superadmin.manage_price',         'display_name' => 'Atur Harga Tiket (SuperAdmin)',    'group_name' => 'superadmin'],
            ['name' => 'superadmin.view_transactions',    'display_name' => 'Lihat Semua Transaksi',            'group_name' => 'superadmin'],
            ['name' => 'superadmin.view_reports',         'display_name' => 'Lihat Laporan Sistem',             'group_name' => 'superadmin'],
            ['name' => 'superadmin.view_logs',            'display_name' => 'Lihat Audit Log Sistem',           'group_name' => 'superadmin'],
            ['name' => 'superadmin.monitor_system',       'display_name' => 'Monitoring Aktivitas Sistem',      'group_name' => 'superadmin'],
            ['name' => 'superadmin.manage_settings',      'display_name' => 'Kelola Konfigurasi Sistem',        'group_name' => 'superadmin'],
        ];

        foreach ($permissions as $perm) {
            Permission::updateOrCreate(
                ['name' => $perm['name']],
                array_merge($perm, ['description' => $perm['description'] ?? null])
            );
        }

        $this->command->info('✓ ' . count($permissions) . ' permissions seeded.');
    }
}
