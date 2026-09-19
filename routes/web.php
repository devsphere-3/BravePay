<?php

use App\Http\Controllers\Admin\AdminActivityLogController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminCheckinController;
use App\Http\Controllers\Admin\AdminCompetitionController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminRegistrationController;
use App\Http\Controllers\Admin\AdminSettingsController;
use App\Http\Controllers\Admin\AdminTicketController;
use App\Http\Controllers\CompetitionController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

// ─── Public Routes ────────────────────────────────────────────

Route::get('/', [HomeController::class, 'index'])->name('home');

// Competitions (public)
Route::prefix('competitions')->name('competitions.')->group(function () {
    Route::get('/', [CompetitionController::class, 'index'])->name('index');
    Route::get('/{slug}', [CompetitionController::class, 'show'])->name('show');
});

// Registration flow
Route::prefix('register')->name('register.')->group(function () {
    Route::get('/{slug}', [RegistrationController::class, 'form'])->name('form');
    Route::post('/{slug}', [RegistrationController::class, 'store'])->name('store');
    Route::get('/review/{code}', [RegistrationController::class, 'review'])->name('review');
});

// Payment
Route::prefix('payment')->name('payment.')->group(function () {
    Route::post('/create/{code}', [PaymentController::class, 'create'])->name('create');
    Route::get('/pay/{code}', [PaymentController::class, 'show'])->name('show');
    Route::get('/success/{code}', [PaymentController::class, 'success'])->name('success');
    Route::get('/check', [PaymentController::class, 'checkForm'])->name('check');
    Route::post('/check', [PaymentController::class, 'check'])->name('check.post');
    Route::post('/webhook', [PaymentController::class, 'webhook'])->name('webhook')->withoutMiddleware(['web']);
});

// Tickets
Route::prefix('ticket')->name('ticket.')->group(function () {
    Route::get('/group/{order_code}', [TicketController::class, 'group'])->name('group');
    Route::get('/{code}', [TicketController::class, 'show'])->name('show');
    Route::get('/{code}/download', [TicketController::class, 'download'])->name('download');
});

// Help center
Route::post('/help/resend-ticket', [HelpController::class, 'resendTicket'])->name('help.resend');

// ─── Admin Auth ────────────────────────────────────────────────

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
});

// ─── Admin Protected Routes ────────────────────────────────────

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin.activity'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Competitions CRUD
    Route::prefix('competitions')->name('competitions.')->group(function () {
        Route::get('/', [AdminCompetitionController::class, 'index'])->name('index');
        Route::get('/create', [AdminCompetitionController::class, 'create'])->name('create');
        Route::post('/', [AdminCompetitionController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [AdminCompetitionController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminCompetitionController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminCompetitionController::class, 'destroy'])->name('destroy');
    });

    // Registrations
    Route::prefix('registrations')->name('registrations.')->group(function () {
        Route::get('/', [AdminRegistrationController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminRegistrationController::class, 'show'])->name('show');
        Route::delete('/{id}', [AdminRegistrationController::class, 'destroy'])->name('destroy');
    });

    // Payments
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [AdminPaymentController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminPaymentController::class, 'show'])->name('show');
    });

    // Tickets
    Route::prefix('tickets')->name('tickets.')->group(function () {
        Route::get('/', [AdminTicketController::class, 'index'])->name('index');
        Route::post('/{id}/resend', [AdminTicketController::class, 'resend'])->name('resend');
    });

    // Check-in
    Route::get('/checkin', [AdminCheckinController::class, 'index'])->name('checkin');
    Route::get('/api/checkin/{code}', [AdminCheckinController::class, 'validate'])->name('checkin.validate');
    Route::post('/api/checkin/{code}', [AdminCheckinController::class, 'checkin'])->name('checkin.post');

    // Settings
    Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings');
    Route::post('/settings', [AdminSettingsController::class, 'update'])->name('settings.update');
    Route::get('/activity-logs', [AdminActivityLogController::class, 'index'])->name('activity-logs.index');
});
