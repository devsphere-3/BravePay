<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminRegistrationController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.registrations.index');
    }

    public function show(int $id): View
    {
        return view('admin.registrations.index');
    }
}
