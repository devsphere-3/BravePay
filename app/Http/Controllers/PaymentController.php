<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function create(Request $request, string $code)
    {
        // Create payment via payment gateway, redirect to payment page
        return redirect()->route('payment.show', $code);
    }

    public function show(string $code): View
    {
        // $registration = Registration::where('order_code', $code)->with('payment')->firstOrFail();
        return view('payment.show');
    }

    public function success(string $code): View
    {
        // $registration = Registration::where('order_code', $code)->with(['payment','tickets.participant'])->firstOrFail();
        return view('payment.success');
    }

    public function checkForm(): View
    {
        return view('payment.check');
    }

    public function check(Request $request)
    {
        $request->validate(['order_code' => ['required', 'string']]);
        // $registration = Registration::where('order_code', $request->order_code)->with('payment')->first();
        return back()->with('not_found', true);
    }

    public function webhook(Request $request)
    {
        // Verify webhook signature from payment gateway
        // Update payment status
        // Generate tickets if paid
        return response()->json(['status' => 'ok']);
    }
}
