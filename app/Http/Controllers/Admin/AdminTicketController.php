<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminTicketController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.tickets.index');
    }

    public function resend(Request $request, int $id)
    {
        return back()->with('success', 'E-Ticket berhasil dikirim ulang.');
    }
}
