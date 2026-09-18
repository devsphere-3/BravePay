<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class AdminCheckinController extends Controller
{
    public function index(): View
    {
        // Ambil 10 tiket terakhir yang sudah check-in (status 'used') dari DB
        $recent_checkins = collect();

        Registration::where('status', 'paid')
            ->whereNotNull('tickets')
            ->latest('updated_at')
            ->get(['order_code', 'email', 'tickets', 'updated_at'])
            ->each(function ($reg) use (&$recent_checkins) {
                foreach ($reg->tickets ?? [] as $ticket) {
                    if (($ticket['status'] ?? null) === 'used') {
                        $recent_checkins->push((object) [
                            'ticket'        => (object) [
                                'ticket_code' => $ticket['ticket_code'] ?? '—',
                                'participant' => (object) [
                                    'name' => $ticket['participant'] ?? $reg->email,
                                ],
                            ],
                            'checked_in_at' => $reg->updated_at,
                        ]);
                    }
                }
            });

        $recent_checkins = $recent_checkins->sortByDesc('checked_in_at')->take(10)->values();

        return view('admin.checkin', compact('recent_checkins'));
    }

    /**
     * GET /admin/api/checkin/{code}
     * Validasi tiket: cek apakah tiket ada, sudah lunas, belum dipakai.
     */
    public function validate(string $code): JsonResponse
    {
        $code = strtoupper(trim($code));
        $found = $this->findTicket($code);

        if (! $found) {
            return response()->json([
                'status'  => 'not_found',
                'message' => 'Tiket tidak ditemukan dalam sistem.',
            ], 404);
        }

        ['registration' => $reg, 'ticket' => $ticket] = $found;

        // Registrasi belum lunas
        if ($reg->status !== 'paid') {
            return response()->json([
                'status'  => 'invalid',
                'message' => 'Pembayaran untuk tiket ini belum selesai.',
            ], 422);
        }

        // Tiket sudah dipakai
        if (($ticket['status'] ?? '') === 'used') {
            return response()->json([
                'status'           => 'used',
                'ticket_code'      => $code,
                'participant_name' => $ticket['participant'] ?? '—',
                'competition_name' => $reg->competition?->name ?? '—',
                'checked_in_at'    => $reg->updated_at?->format('d M Y H:i'),
                'message'          => 'Tiket ini sudah digunakan untuk check-in.',
            ]);
        }

        return response()->json([
            'status'           => 'valid',
            'ticket_code'      => $code,
            'participant_name' => $ticket['participant'] ?? '—',
            'competition_name' => $reg->competition?->name ?? '—',
            'category'         => $reg->competition?->category ?? '—',
            'order_code'       => $reg->order_code,
        ]);
    }

    /**
     * POST /admin/api/checkin/{code}
     * Lakukan check-in: update status tiket jadi 'used' di kolom JSON.
     */
    public function checkin(string $code): JsonResponse
    {
        $code  = strtoupper(trim($code));
        $found = $this->findTicket($code);

        if (! $found) {
            return response()->json([
                'status'  => 'not_found',
                'message' => 'Tiket tidak ditemukan.',
            ], 404);
        }

        ['registration' => $reg, 'ticket' => $ticket, 'index' => $idx] = $found;

        if ($reg->status !== 'paid') {
            return response()->json([
                'status'  => 'invalid',
                'message' => 'Pembayaran belum selesai.',
            ], 422);
        }

        if (($ticket['status'] ?? '') === 'used') {
            return response()->json([
                'status'           => 'used',
                'ticket_code'      => $code,
                'participant_name' => $ticket['participant'] ?? '—',
                'competition_name' => $reg->competition?->name ?? '—',
                'checked_in_at'    => $reg->updated_at?->format('d M Y H:i'),
                'message'          => 'Tiket sudah pernah check-in.',
            ]);
        }

        // Update status tiket di dalam array JSON
        $tickets        = $reg->tickets;
        $tickets[$idx]['status'] = 'used';

        $reg->update(['tickets' => $tickets]);

        return response()->json([
            'status'           => 'checked_in',
            'ticket_code'      => $code,
            'participant_name' => $ticket['participant'] ?? '—',
            'competition_name' => $reg->competition?->name ?? '—',
            'checked_in_at'    => now()->format('d M Y H:i'),
        ]);
    }

    // ── Helper ────────────────────────────────────────────────────

    /**
     * Cari tiket berdasarkan ticket_code di dalam kolom JSON tickets
     * pada semua baris Registration.
     *
     * @return array{registration: Registration, ticket: array, index: int}|null
     */
    private function findTicket(string $code): ?array
    {
        $registrations = Registration::with('competition')
            ->where('status', 'paid')
            ->whereNotNull('tickets')
            ->get();

        foreach ($registrations as $reg) {
            foreach ($reg->tickets as $idx => $ticket) {
                if (strtoupper($ticket['ticket_code'] ?? '') === $code) {
                    return [
                        'registration' => $reg,
                        'ticket'       => $ticket,
                        'index'        => $idx,
                    ];
                }
            }
        }

        return null;
    }
}
