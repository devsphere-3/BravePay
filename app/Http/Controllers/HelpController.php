<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HelpController extends Controller
{
    public function resendTicket(Request $request): JsonResponse
    {
        $request->validate([
            'phone'      => ['required', 'string'],
            'order_code' => ['nullable', 'string'],
        ]);

        // Find registration by phone (+ optional order_code)
        // Check payment status
        // Resend ticket via WhatsApp/Email
        // Placeholder response:
        return response()->json([
            'message' => 'Fitur kirim ulang tiket akan aktif setelah integrasi WhatsApp selesai.',
        ], 200);
    }
}
