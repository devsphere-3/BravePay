<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class SuperAdminDashboardController extends Controller
{
    public function index(): View
    {
        // Data dihitung langsung di view dengan @php untuk kesederhanaan.
        // Untuk production, pindahkan query ke sini dan pass via compact().
        return view('superadmin.dashboard');
    }
}
