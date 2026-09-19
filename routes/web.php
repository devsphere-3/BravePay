<?php

use App\Http\Controllers\Admin\AdminActivityLogController;
use App\Http\Controllers\Admin\AdminCheckinController;
use App\Http\Controllers\Admin\AdminCompetitionController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminRegistrationController;
use App\Http\Controllers\Admin\AdminSettingsController;
use App\Http\Controllers\Admin\AdminTicketController;
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

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Kompetisi
    Route::prefix('competitions')->name('competitions.')->group(function () {
        Route::get('/',            [AdminCompetitionController::class, 'index'])->name('index');
        Route::get('/create',      [AdminCompetitionController::class, 'create'])->name('create');
        Route::post('/',           [AdminCompetitionController::class, 'store'])->name('store');
        Route::get('/{id}/edit',   [AdminCompetitionController::class, 'edit'])->name('edit');
        Route::put('/{id}',        [AdminCompetitionController::class, 'update'])->name('update');
        Route::delete('/{id}',     [AdminCompetitionController::class, 'destroy'])->name('destroy');
    });

    // Registrasi peserta
    Route::prefix('registrations')->name('registrations.')->group(function () {
        Route::get('/',        [AdminRegistrationController::class, 'index'])->name('index');
        Route::get('/{id}',    [AdminRegistrationController::class, 'show'])->name('show');
        Route::delete('/{id}', [AdminRegistrationController::class, 'destroy'])->name('destroy');
    });

    // Pembayaran (admin view — read only)
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/',      [AdminPaymentController::class, 'index'])->name('index');
        Route::get('/{id}',  [AdminPaymentController::class, 'show'])->name('show');
    });

    // Tiket
    Route::prefix('tickets')->name('tickets.')->group(function () {
        Route::get('/',            [AdminTicketController::class, 'index'])->name('index');
        Route::post('/{id}/resend',[AdminTicketController::class, 'resend'])->name('resend');
    });

    // Check-in / Scan
    Route::get('/checkin',              [AdminCheckinController::class, 'index'])->name('checkin');
    Route::get('/api/checkin/{code}',   [AdminCheckinController::class, 'validate'])->name('checkin.validate');
    Route::post('/api/checkin/{code}',  [AdminCheckinController::class, 'checkin'])->name('checkin.post');

    // Settings & logs
    Route::get('/settings',         [AdminSettingsController::class, 'index'])->name('settings');
    Route::post('/settings',        [AdminSettingsController::class, 'update'])->name('settings.update');
    Route::get('/activity-logs',    [AdminActivityLogController::class, 'index'])->name('activity-logs.index');
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
    })->name('dashboard');

    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/',     [AdminPaymentController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminPaymentController::class, 'show'])->name('show');
    });

    Route::prefix('registrations')->name('registrations.')->group(function () {
        Route::get('/',     [AdminRegistrationController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminRegistrationController::class, 'show'])->name('show');
    });

    Route::get('/reports', function () {
        return view('finance.reports');
    })->name('reports');
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
    })->name('dashboard');

    Route::get('/statistics', function () {
        return view('sponsor.statistics');
    })->name('statistics');

    Route::get('/participants', function () {
        return view('sponsor.participants');
    })->name('participants');

    Route::get('/events', function () {
        return view('sponsor.events');
    })->name('events');

    Route::get('/reports', function () {
        return view('sponsor.reports');
    })->name('reports');
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
    })->name('dashboard');

    // ── User Management ────────────────────────────────────────
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/',        function () { return view('superadmin.users.index'); })->name('index');
        Route::get('/create',  function () { return view('superadmin.users.create'); })->name('create');
        Route::get('/{id}',    function () { return view('superadmin.users.show'); })->name('show');
        Route::get('/{id}/edit', function () { return view('superadmin.users.edit'); })->name('edit');
    });

    // ── Role & Permission Management ───────────────────────────
    Route::get('/roles',       function () { return view('superadmin.roles.index'); })->name('roles.index');
    Route::get('/permissions', function () { return view('superadmin.permissions.index'); })->name('permissions.index');

    // ── Event & Kompetisi (via admin controller) ───────────────
    Route::prefix('competitions')->name('competitions.')->group(function () {
        Route::get('/',            [AdminCompetitionController::class, 'index'])->name('index');
        Route::get('/create',      [AdminCompetitionController::class, 'create'])->name('create');
        Route::post('/',           [AdminCompetitionController::class, 'store'])->name('store');
        Route::get('/{id}/edit',   [AdminCompetitionController::class, 'edit'])->name('edit');
        Route::put('/{id}',        [AdminCompetitionController::class, 'update'])->name('update');
        Route::delete('/{id}',     [AdminCompetitionController::class, 'destroy'])->name('destroy');
    });

    // ── Payments & Registrations ───────────────────────────────
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/',     [AdminPaymentController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminPaymentController::class, 'show'])->name('show');
    });

    Route::prefix('registrations')->name('registrations.')->group(function () {
        Route::get('/',     [AdminRegistrationController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminRegistrationController::class, 'show'])->name('show');
    });

    // ── Reports & Logs ─────────────────────────────────────────
    Route::get('/reports', function () { return view('superadmin.reports'); })->name('reports');
    Route::get('/logs',    [AdminActivityLogController::class, 'index'])->name('logs.index');

    // ── Settings ───────────────────────────────────────────────
    Route::get('/settings',  [AdminSettingsController::class, 'index'])->name('settings');
    Route::post('/settings', [AdminSettingsController::class, 'update'])->name('settings.update');
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
