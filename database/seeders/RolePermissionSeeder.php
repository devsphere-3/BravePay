<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $map = [

            'customer' => [
                'customer.view_dashboard',
                'customer.purchase',
                'customer.view_own_ticket',
                'customer.download_ticket',
                'customer.view_own_payment',
                'customer.view_own_history',
                'customer.report_error',
                'customer.view_events',
            ],

            'admin' => [
                'admin.view_dashboard',
                'admin.view_customers',
                'admin.view_registrations',
                'admin.view_participants',
                'admin.scan_ticket',
                'admin.verify_checkin',
                'admin.manage_ticket',
                'admin.manage_competition',
                'admin.manage_price',
                'admin.contact_customer',
                'admin.view_reports',
                'admin.view_activity_logs',
                'admin.delete_registration',
            ],

            'finance' => [
                'finance.view_dashboard',
                'finance.view_payments',
                'finance.view_transactions',
                'finance.view_reports',
                'finance.export_reports',
                'finance.view_registrations',
            ],

            'sponsor' => [
                'sponsor.view_dashboard',
                'sponsor.view_statistics',
                'sponsor.view_participants',
                'sponsor.view_event_info',
                'sponsor.view_reports',
            ],

            'superadmin' => [
                // Semua permission superadmin
                'superadmin.view_dashboard',
                'superadmin.view_all_data',
                'superadmin.manage_customers',
                'superadmin.manage_admins',
                'superadmin.manage_finance',
                'superadmin.manage_sponsors',
                'superadmin.manage_roles',
                'superadmin.manage_permissions',
                'superadmin.manage_events',
                'superadmin.manage_tickets',
                'superadmin.manage_price',
                'superadmin.view_transactions',
                'superadmin.view_reports',
                'superadmin.view_logs',
                'superadmin.monitor_system',
                'superadmin.manage_settings',
                // SuperAdmin juga bisa melakukan semua aksi admin
                'admin.view_dashboard',
                'admin.view_customers',
                'admin.view_registrations',
                'admin.view_participants',
                'admin.scan_ticket',
                'admin.verify_checkin',
                'admin.manage_ticket',
                'admin.manage_competition',
                'admin.manage_price',
                'admin.contact_customer',
                'admin.view_reports',
                'admin.view_activity_logs',
                'admin.delete_registration',
                // SuperAdmin juga bisa melakukan semua aksi finance
                'finance.view_dashboard',
                'finance.view_payments',
                'finance.view_transactions',
                'finance.view_reports',
                'finance.export_reports',
                'finance.view_registrations',
            ],
        ];

        foreach ($map as $roleName => $permissionNames) {
            $role = Role::where('name', $roleName)->first();

            if (! $role) {
                $this->command->warn("Role [{$roleName}] tidak ditemukan, skip.");
                continue;
            }

            $permissionIds = Permission::whereIn('name', $permissionNames)->pluck('id');
            $role->permissions()->sync($permissionIds);

            $this->command->info("✓ Role [{$roleName}] → {$permissionIds->count()} permissions.");
        }
    }
}
