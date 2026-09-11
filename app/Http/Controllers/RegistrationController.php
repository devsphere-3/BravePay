<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    public function form(string $slug): View
    {
        // $competition = Competition::where('slug', $slug)->firstOrFail();
        return view('register.form');
    }

    public function store(Request $request, string $slug)
    {
        $validated = $request->validate([
            'email'             => ['required', 'email', 'max:255'],
            'phone'             => ['required', 'string', 'max:20'],
            'participant_count' => ['required', 'integer', 'min:1', 'max:20'],
            'participants'      => ['required', 'array', 'min:1'],
            'participants.*.name'          => ['required', 'string', 'max:255'],
            'participants.*.date_of_birth' => ['required', 'date'],
        ]);

        // Registration creation logic will go here once models are built
        // For now, redirect to a demo review page
        return redirect()->route('register.review', 'BRV-'.date('Ymd').'-0001');
    }

    public function review(string $code): View
    {
        // $registration = Registration::where('order_code', $code)->firstOrFail();
        return view('register.review');
    }
}
