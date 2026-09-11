<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        // Stats will be populated from models once DB is set up
        $stats = [
            'total_registrations'  => 0,
            'total_participants'   => 0,
            'total_transactions'   => 0,
            'paid_transactions'    => 0,
            'pending_transactions' => 0,
            'failed_transactions'  => 0,
            'total_revenue'        => 0,
            'checked_in'           => 0,
        ];

        $recent_registrations = collect([]);

        return view('admin.dashboard', compact('stats', 'recent_registrations'));
    }
}
