<?php

use App\Http\Controllers\Admin\AdminActivityLogController;
use App\Http\Controllers\Admin\AdminCheckinController;
use App\Http\Controllers\Admin\AdminCompetitionController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminRegistrationController;
use App\Http\Controllers\Admin\AdminSettingsController;
use App\Http\Controllers\Admin\AdminTicketController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompetitionController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

// ══════════════════════════════════════════════════════════════════
//  PUBLIC ROUTES  (tidak perlu login)
// ══════════════════════════════════════════════════════════════════

Route::get('/', [HomeController::class, 'index'])->name('home');

// Event / kompetisi
Route::prefix('competitions')->name('competitions.')->group(function () {
    Route::get('/', [CompetitionController::class, 'index'])->name('index');
    Route::get('/{slug}', [CompetitionController::class, 'show'])->name('show');
});

// Alur pendaftaran publik
Route::prefix('register')->name('register.')->group(function () {
    Route::get('/{slug}', [RegistrationController::class, 'form'])->name('form');
    Route::post('/{slug}', [RegistrationController::class, 'store'])->name('store');
    Route::get('/review/{code}', [RegistrationController::class, 'review'])->name('review');
});

// Alur pembayaran publik
Route::prefix('payment')->name('payment.')->group(function () {
    Route::post('/create/{code}', [PaymentController::class, 'create'])->name('create');
    Route::get('/pay/{code}', [PaymentController::class, 'show'])->name('show');
    Route::get('/success/{code}', [PaymentController::class, 'success'])->name('success');
    Route::get('/check', [PaymentController::class, 'checkForm'])->name('check');
    Route::post('/check', [PaymentController::class, 'check'])->name('check.post');
    // Webhook: tanpa middleware web (CSRF dikecualikan)
    Route::post('/webhook', [PaymentController::class, 'webhook'])
         ->name('webhook')
         ->withoutMiddleware(['web']);
});

// Tiket publik (QR link di e-ticket)
Route::prefix('ticket')->name('ticket.')->group(function () {
    Route::get('/group/{order_code}', [TicketController::class, 'group'])->name('group');
    Route::get('/{code}', [TicketController::class, 'show'])->name('show');
    Route::get('/{code}/download', [TicketController::class, 'download'])->name('download');
});

// Help center
Route::post('/help/resend-ticket', [HelpController::class, 'resendTicket'])->name('help.resend');

// ══════════════════════════════════════════════════════════════════
//  AUTH — Satu halaman login untuk semua role
// ══════════════════════════════════════════════════════════════════

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])
     ->name('logout')
     ->middleware('auth');

// ══════════════════════════════════════════════════════════════════
//  ADMIN  (role: admin)
// ══════════════════════════════════════════════════════════════════

Route::prefix('admin')
     ->name('admin.')
     ->middleware(['auth', 'role:admin,superadmin', 'admin.activity'])
     ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->middleware('permission:admin.view_dashboard')
            ->name('dashboard');

    // Kompetisi
        Route::prefix('competitions')->name('competitions.')
            ->middleware('permission:admin.manage_competition')
            ->group(function () {
        Route::get('/',            [AdminCompetitionController::class, 'index'])->name('index');
        Route::get('/create',      [AdminCompetitionController::class, 'create'])->name('create');
        Route::post('/',           [AdminCompetitionController::class, 'store'])->name('store');
        Route::get('/{id}/edit',   [AdminCompetitionController::class, 'edit'])->name('edit');
        Route::put('/{id}',        [AdminCompetitionController::class, 'update'])->name('update');
        Route::delete('/{id}',     [AdminCompetitionController::class, 'destroy'])->name('destroy');
    });

    // Registrasi peserta
    Route::prefix('registrations')->name('registrations.')
         ->middleware('permission:admin.view_registrations')
         ->group(function () {
        Route::get('/',        [AdminRegistrationController::class, 'index'])->name('index');
        Route::get('/{id}',    [AdminRegistrationController::class, 'show'])->name('show');
        Route::delete('/{id}', [AdminRegistrationController::class, 'destroy'])
             ->middleware('permission:admin.delete_registration')
             ->name('destroy');
    });

    // Pembayaran (admin view — read only)
        Route::prefix('payments')->name('payments.')
            ->middleware('permission:admin.view_payments')
            ->group(function () {
        Route::get('/',      [AdminPaymentController::class, 'index'])->name('index');
        Route::get('/{id}',  [AdminPaymentController::class, 'show'])->name('show');
    });

    // Tiket
        Route::prefix('tickets')->name('tickets.')
            ->middleware('permission:admin.manage_ticket')
            ->group(function () {
        Route::get('/',            [AdminTicketController::class, 'index'])->name('index');
        Route::post('/{id}/resend',[AdminTicketController::class, 'resend'])->name('resend');
    });

    // Check-in / Scan
        Route::get('/checkin',              [AdminCheckinController::class, 'index'])
            ->middleware('permission:admin.scan_ticket')
            ->name('checkin');
        Route::get('/api/checkin/{code}',   [AdminCheckinController::class, 'validate'])
            ->middleware('permission:admin.scan_ticket')
            ->name('checkin.validate');
        Route::post('/api/checkin/{code}',  [AdminCheckinController::class, 'checkin'])
            ->middleware('permission:admin.verify_checkin')
            ->name('checkin.post');

    // Settings & logs
        Route::get('/settings',         [AdminSettingsController::class, 'index'])
            ->middleware('permission:admin.manage_settings')
            ->name('settings');
        Route::post('/settings',        [AdminSettingsController::class, 'update'])
            ->middleware('permission:admin.manage_settings')
            ->name('settings.update');
        Route::get('/activity-logs',    [AdminActivityLogController::class, 'index'])
            ->middleware('permission:admin.view_activity_logs')
            ->name('activity-logs.index');
});

// ══════════════════════════════════════════════════════════════════
//  FINANCE  (role: finance)
// ══════════════════════════════════════════════════════════════════

Route::prefix('finance')
     ->name('finance.')
     ->middleware(['auth', 'role:finance,superadmin', 'admin.activity'])
     ->group(function () {

    Route::get('/dashboard', function () {
        return view('finance.dashboard');
    })->middleware('permission:finance.view_dashboard')->name('dashboard');

        Route::prefix('payments')->name('payments.')
            ->middleware('permission:finance.view_payments')
            ->group(function () {
        Route::get('/',     [AdminPaymentController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminPaymentController::class, 'show'])->name('show');
    });

        Route::prefix('registrations')->name('registrations.')
            ->middleware('permission:finance.view_registrations')
            ->group(function () {
        Route::get('/',     [AdminRegistrationController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminRegistrationController::class, 'show'])->name('show');
    });

    Route::get('/reports', function () {
        return view('finance.reports');
    })->middleware('permission:finance.view_reports')->name('reports');
});

// ══════════════════════════════════════════════════════════════════
//  SPONSOR  (role: sponsor)
// ══════════════════════════════════════════════════════════════════

Route::prefix('sponsor')
     ->name('sponsor.')
     ->middleware(['auth', 'role:sponsor,superadmin'])
     ->group(function () {

    Route::get('/dashboard', function () {
        return view('sponsor.dashboard');
    })->middleware('permission:sponsor.view_dashboard')->name('dashboard');

    Route::get('/statistics', function () {
        return view('sponsor.statistics');
    })->middleware('permission:sponsor.view_statistics')->name('statistics');

    Route::get('/participants', function () {
        return view('sponsor.participants');
    })->middleware('permission:sponsor.view_participants')->name('participants');

    Route::get('/events', function () {
        return view('sponsor.events');
    })->middleware('permission:sponsor.view_event_info')->name('events');

    Route::get('/reports', function () {
        return view('sponsor.reports');
    })->middleware('permission:sponsor.view_reports')->name('reports');
});

// ══════════════════════════════════════════════════════════════════
//  SUPERADMIN  (role: superadmin only)
// ══════════════════════════════════════════════════════════════════

Route::prefix('superadmin')
     ->name('superadmin.')
     ->middleware(['auth', 'role:superadmin', 'admin.activity'])
     ->group(function () {

    Route::get('/dashboard', function () {
        return view('superadmin.dashboard');
    })->middleware('permission:superadmin.view_dashboard')->name('dashboard');

    // ── User Management ────────────────────────────────────────
    Route::prefix('users')->name('users.')
         ->middleware('permission:superadmin.manage_customers')
         ->group(function () {
        Route::get('/',              [AdminUserController::class, 'index'])->name('index');
        Route::get('/create',        [AdminUserController::class, 'create'])->name('create');
        Route::post('/',             [AdminUserController::class, 'store'])->name('store');
        Route::get('/{id}',          [AdminUserController::class, 'show'])->name('show');
        Route::get('/{id}/edit',     [AdminUserController::class, 'edit'])->name('edit');
        Route::put('/{id}',          [AdminUserController::class, 'update'])->name('update');
        Route::patch('/{id}/status', [AdminUserController::class, 'toggleStatus'])->name('status');
        Route::delete('/{id}',        [AdminUserController::class, 'destroy'])->name('destroy');
    });

    // ── Role & Permission Management ───────────────────────────
        Route::get('/roles',       function () { return view('superadmin.roles.index'); })
            ->middleware('permission:superadmin.manage_roles')
            ->name('roles.index');
        Route::get('/permissions', function () { return view('superadmin.permissions.index'); })
            ->middleware('permission:superadmin.manage_permissions')
            ->name('permissions.index');

    // ── Event & Kompetisi (via admin controller) ───────────────
        Route::prefix('competitions')->name('competitions.')
            ->middleware('permission:superadmin.manage_events')
            ->group(function () {
        Route::get('/',            [AdminCompetitionController::class, 'index'])->name('index');
        Route::get('/create',      [AdminCompetitionController::class, 'create'])->name('create');
        Route::post('/',           [AdminCompetitionController::class, 'store'])->name('store');
        Route::get('/{id}/edit',   [AdminCompetitionController::class, 'edit'])->name('edit');
        Route::put('/{id}',        [AdminCompetitionController::class, 'update'])->name('update');
        Route::delete('/{id}',     [AdminCompetitionController::class, 'destroy'])->name('destroy');
    });

    // ── Payments & Registrations ───────────────────────────────
        Route::prefix('payments')->name('payments.')
            ->middleware('permission:superadmin.view_transactions')
            ->group(function () {
        Route::get('/',     [AdminPaymentController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminPaymentController::class, 'show'])->name('show');
    });

        Route::prefix('registrations')->name('registrations.')
            ->middleware('permission:superadmin.view_all_data')
            ->group(function () {
        Route::get('/',     [AdminRegistrationController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminRegistrationController::class, 'show'])->name('show');
    });

    // ── Reports & Logs ─────────────────────────────────────────
        Route::get('/reports', function () { return view('superadmin.reports'); })
            ->middleware('permission:superadmin.view_reports')
            ->name('reports');
        Route::get('/logs',    [AdminActivityLogController::class, 'index'])
            ->middleware('permission:superadmin.view_logs')
            ->name('logs.index');

    // ── Settings ───────────────────────────────────────────────
        Route::get('/settings',  [AdminSettingsController::class, 'index'])
            ->middleware('permission:superadmin.manage_settings')
            ->name('settings');
        Route::post('/settings', [AdminSettingsController::class, 'update'])
            ->middleware('permission:superadmin.manage_settings')
            ->name('settings.update');
});

// ══════════════════════════════════════════════════════════════════
//  CUSTOMER  (role: customer — authenticated)
// ══════════════════════════════════════════════════════════════════

Route::prefix('customer')
     ->name('customer.')
     ->middleware(['auth', 'role:customer'])
     ->group(function () {

    Route::get('/dashboard', function () {
        return view('customer.dashboard');
    })->name('dashboard');

    // History & tiket milik sendiri (dilindungi owner check di controller)
    Route::get('/history',        function () { return view('customer.history'); })->name('history');
    Route::get('/history/{code}', function () { return view('customer.history-detail'); })->name('history.show');
    Route::get('/tickets',        function () { return view('customer.tickets'); })->name('tickets');
    Route::get('/tickets/{code}', function () { return view('customer.ticket-detail'); })->name('tickets.show');

    Route::get('/profile',  function () { return view('customer.profile'); })->name('profile');
    Route::put('/profile',  function () { return back(); })->name('profile.update');

    Route::get('/report',   function () { return view('customer.report'); })->name('report');
    Route::post('/report',  function () { return back()->with('success', 'Laporan terkirim.'); })->name('report.store');
});
