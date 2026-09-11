<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminPaymentController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.payments.index');
    }

    public function show(int $id): View
    {
        return view('admin.payments.index');
    }
}
