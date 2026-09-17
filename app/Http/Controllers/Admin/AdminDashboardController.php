<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        // ── Stats dari database ───────────────────────────────────
        $stats = [
            'total_registrations'  => Registration::count(),
            'total_participants'   => (int) Registration::sum('participant_count'),
            'total_transactions'   => Registration::count(),
            'paid_transactions'    => Registration::where('status', 'paid')->count(),
            'pending_transactions' => Registration::where('status', 'pending')->count(),
            'failed_transactions'  => Registration::where('status', 'failed')->count(),
            'total_revenue'        => (int) Registration::where('status', 'paid')->sum('total_amount'),
            'checked_in'           => $this->countCheckedIn(),
        ];

        // ── 10 pendaftaran terbaru ────────────────────────────────
        $recent_registrations = Registration::with('competition')
            ->latest()
            ->limit(10)
            ->get()
            ->map(function ($reg) {
                // Normalise supaya view bisa akses ->payment->status seperti sebelumnya
                $reg->payment = (object) ['status' => $reg->status, 'amount' => $reg->total_amount];
                return $reg;
            });

        return view('admin.dashboard', compact('stats', 'recent_registrations'));
    }

    /** Hitung tiket yang sudah check-in dari kolom tickets JSON */
    private function countCheckedIn(): int
    {
        $count = 0;
        Registration::whereNotNull('tickets')
            ->where('status', 'paid')
            ->get(['tickets'])
            ->each(function ($reg) use (&$count) {
                foreach ($reg->tickets ?? [] as $ticket) {
                    if (($ticket['status'] ?? null) === 'used') {
                        $count++;
                    }
                }
            });
        return $count;
    }
}
