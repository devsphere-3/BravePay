<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->defineGates();
    }

    /**
     * Daftarkan Gate untuk digunakan dengan @can / Gate::allows() di Blade dan controller.
     *
     * Semua permission mengikuti pola: 'group.action'
     * SuperAdmin otomatis melewati semua Gate (before callback).
     */
    private function defineGates(): void
    {
        // ── SuperAdmin bypass: lewati semua Gate check ─────────────────
        Gate::before(function (User $user, string $ability) {
            // SuperAdmin bisa melakukan segalanya kecuali ability khusus yang dimulai 'only.'
            if ($user->isSuperAdmin() && ! str_starts_with($ability, 'only.')) {
                return true;
            }
        });

        // ── Customer ───────────────────────────────────────────────────
        $this->definePermissionGate('customer.view_dashboard');
        $this->definePermissionGate('customer.purchase');
        $this->definePermissionGate('customer.view_own_ticket');
        $this->definePermissionGate('customer.download_ticket');
        $this->definePermissionGate('customer.view_own_payment');
        $this->definePermissionGate('customer.view_own_history');
        $this->definePermissionGate('customer.report_error');
        $this->definePermissionGate('customer.view_events');

        // ── Admin ──────────────────────────────────────────────────────
        $this->definePermissionGate('admin.view_dashboard');
        $this->definePermissionGate('admin.view_customers');
        $this->definePermissionGate('admin.view_registrations');
        $this->definePermissionGate('admin.view_participants');
        $this->definePermissionGate('admin.scan_ticket');
        $this->definePermissionGate('admin.verify_checkin');
        $this->definePermissionGate('admin.manage_ticket');
        $this->definePermissionGate('admin.manage_competition');
        $this->definePermissionGate('admin.manage_price');
        $this->definePermissionGate('admin.contact_customer');
        $this->definePermissionGate('admin.view_reports');
        $this->definePermissionGate('admin.view_activity_logs');
        $this->definePermissionGate('admin.delete_registration');

        // ── Finance ────────────────────────────────────────────────────
        $this->definePermissionGate('finance.view_dashboard');
        $this->definePermissionGate('finance.view_payments');
        $this->definePermissionGate('finance.view_transactions');
        $this->definePermissionGate('finance.view_reports');
        $this->definePermissionGate('finance.export_reports');
        $this->definePermissionGate('finance.view_registrations');

        // ── Sponsor ────────────────────────────────────────────────────
        $this->definePermissionGate('sponsor.view_dashboard');
        $this->definePermissionGate('sponsor.view_statistics');
        $this->definePermissionGate('sponsor.view_participants');
        $this->definePermissionGate('sponsor.view_event_info');
        $this->definePermissionGate('sponsor.view_reports');

        // ── SuperAdmin ─────────────────────────────────────────────────
        $this->definePermissionGate('superadmin.view_dashboard');
        $this->definePermissionGate('superadmin.view_all_data');
        $this->definePermissionGate('superadmin.manage_customers');
        $this->definePermissionGate('superadmin.manage_admins');
        $this->definePermissionGate('superadmin.manage_finance');
        $this->definePermissionGate('superadmin.manage_sponsors');
        $this->definePermissionGate('superadmin.manage_roles');
        $this->definePermissionGate('superadmin.manage_permissions');
        $this->definePermissionGate('superadmin.manage_events');
        $this->definePermissionGate('superadmin.manage_tickets');
        $this->definePermissionGate('superadmin.manage_price');
        $this->definePermissionGate('superadmin.view_transactions');
        $this->definePermissionGate('superadmin.view_reports');
        $this->definePermissionGate('superadmin.view_logs');
        $this->definePermissionGate('superadmin.monitor_system');
        $this->definePermissionGate('superadmin.manage_settings');

        // ── Role Gates (untuk @can('is-admin') dll di Blade) ───────────
        Gate::define('is-superadmin', fn (User $user) => $user->isSuperAdmin());
        Gate::define('is-admin',      fn (User $user) => $user->isAdmin());
        Gate::define('is-finance',    fn (User $user) => $user->isFinance());
        Gate::define('is-sponsor',    fn (User $user) => $user->isSponsor());
        Gate::define('is-customer',   fn (User $user) => $user->isCustomer());

        // ── Staff gate (admin atau superadmin) ─────────────────────────
        Gate::define('is-staff', fn (User $user) => $user->hasAnyRole(['superadmin', 'admin', 'finance']));
    }

    /**
     * Daftarkan satu Gate yang resolusinya via User::hasPermission().
     * Contoh penggunaan di Blade: @can('admin.scan_ticket')
     */
    private function definePermissionGate(string $permission): void
    {
        Gate::define($permission, function (User $user) use ($permission) {
            return $user->hasPermission($permission);
        });
    }
}
