<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminSettingsController extends Controller
{
    public function index(): View
    {
        return view('admin.settings');
    }

    public function update(Request $request)
    {
        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}
