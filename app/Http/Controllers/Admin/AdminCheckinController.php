<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class AdminCheckinController extends Controller
{
    public function index(): View
    {
        $recent_checkins = collect([]);
        return view('admin.checkin', compact('recent_checkins'));
    }

    public function validate(string $code): JsonResponse
    {
        // Ticket lookup logic when models are available:
        // $ticket = Ticket::where('ticket_code', $code)->with(['participant','registration.competition'])->first();
        // if (!$ticket) return response()->json(['status' => 'not_found', 'message' => 'Tiket tidak ditemukan.'], 404);
        // if ($ticket->status === 'used') return response()->json(['status' => 'used', ...]);

        // Placeholder
        return response()->json([
            'status'           => 'valid',
            'ticket_code'      => $code,
            'participant_name' => 'Contoh Peserta',
            'competition_name' => 'Basket Competition',
            'category'         => 'Pelajar',
        ]);
    }

    public function checkin(string $code): JsonResponse
    {
        // Perform check-in
        // $ticket->update(['status' => 'used']);
        // TicketCheckin::create([...]);

        return response()->json([
            'status'           => 'checked_in',
            'ticket_code'      => $code,
            'participant_name' => 'Contoh Peserta',
            'checked_in_at'    => now()->format('d M Y H:i'),
        ]);
    }
}
