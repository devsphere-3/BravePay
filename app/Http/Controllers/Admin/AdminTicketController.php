<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\UserActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminTicketController extends Controller
{
    public function index(Request $request): View
    {
        $q            = $request->input('q', '');
        $filterStatus = $request->input('status', '');

        // Kelompokkan tiket per order (grup)
        $groups = collect();

        foreach (UserActivityLog::all() as $reg) {
            $regTickets = collect($reg['tickets'] ?? []);

            if ($regTickets->isEmpty()) {
                continue;
            }

            $orderCode   = $reg['order_code'] ?? null;
            $compName    = is_array($reg['competition'] ?? null)
                ? ($reg['competition']['name'] ?? 'Event')
                : (is_object($reg['competition'] ?? null) ? ($reg['competition']->name ?? 'Event') : 'Event');
            $email       = $reg['email'] ?? '—';
            $phone       = $reg['phone'] ?? '—';
            $createdAt   = $reg['created_at'] ?? null;

            // Normalise tiket dalam grup ini
            $normalised = $regTickets->map(function ($ticket) use ($reg) {
                return (object) [
                    'ticket_code' => $ticket['ticket_code'] ?? ($reg['order_code'] ?? '—'),
                    'participant' => $ticket['participant'] ?? ($ticket['participant_name'] ?? ($reg['email'] ?? 'Peserta')),
                    'status'      => strtolower((string) ($ticket['status'] ?? ($reg['status'] ?? 'pending'))),
                ];
            });

            // Filter per tiket jika ada query/status
            if ($filterStatus !== '') {
                $normalised = $normalised->filter(fn ($t) => $t->status === $filterStatus);
            }

            if ($q !== '') {
                $ql = strtolower($q);
                $inGroup = str_contains(strtolower($orderCode ?? ''), $ql)
                    || str_contains(strtolower($email), $ql)
                    || str_contains(strtolower($compName), $ql);

                if (!$inGroup) {
                    $normalised = $normalised->filter(
                        fn ($t) => str_contains(strtolower($t->ticket_code), $ql)
                               || str_contains(strtolower($t->participant), $ql)
                    );
                }
            }

            if ($normalised->isEmpty()) {
                continue;
            }

            // Status keseluruhan grup = status registrasi
            $groupStatus = strtolower((string) ($reg['status'] ?? ($reg['payment']['status'] ?? 'pending')));

            $groups->push((object) [
                'order_code'   => $orderCode,
                'comp_name'    => $compName,
                'email'        => $email,
                'phone'        => $phone,
                'created_at'   => $createdAt,
                'status'       => $groupStatus,
                'ticket_count' => $normalised->count(),
                'tickets'      => $normalised->values(),
            ]);
        }

        // Sort terbaru dulu
        $groups = $groups->sortByDesc(fn ($g) => $g->created_at)->values();

        return view('admin.tickets.index', compact('groups', 'q', 'filterStatus'));
    }

    public function resend(Request $request, string $id)
    {
        return back()->with('success', 'E-Ticket berhasil dikirim ulang.');
    }
}
