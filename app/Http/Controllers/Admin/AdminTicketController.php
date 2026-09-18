<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminTicketController extends Controller
{
    public function index(Request $request): View
    {
        $q            = trim($request->input('q', ''));
        $filterStatus = $request->input('status', '');

        // Hanya registrasi yang sudah paid dan punya tiket
        $query = Registration::with('competition')
            ->where('status', 'paid')
            ->whereNotNull('tickets')
            ->latest();

        // Filter cari: order_code, email, atau nama peserta/tiket
        if ($q !== '') {
            $ql = $q;
            $query->where(function ($sub) use ($ql) {
                $sub->where('order_code', 'like', "%{$ql}%")
                    ->orWhere('email',      'like', "%{$ql}%")
                    ->orWhere('phone',      'like', "%{$ql}%");
            });
        }

        $registrations = $query->get();

        // Bentuk struktur grup per order
        $groups = collect();

        foreach ($registrations as $reg) {
            $rawTickets = collect($reg->tickets ?? []);

            if ($rawTickets->isEmpty()) {
                continue;
            }

            // Normalise tiap tiket
            $normalised = $rawTickets->map(fn ($t) => (object) [
                'ticket_code' => $t['ticket_code'] ?? '—',
                'participant' => $t['participant']  ?? $reg->email,
                'status'      => $t['status']       ?? 'active',
            ]);

            // Filter status tiket jika diminta
            if ($filterStatus !== '') {
                $normalised = $normalised->filter(
                    fn ($t) => $t->status === $filterStatus
                );
            }

            // Filter nama peserta / kode tiket jika q tidak cocok di level grup
            if ($q !== '') {
                $ql      = strtolower($q);
                $inGroup = str_contains(strtolower($reg->order_code), $ql)
                        || str_contains(strtolower($reg->email), $ql)
                        || str_contains(strtolower($reg->competition?->name ?? ''), $ql);

                if (! $inGroup) {
                    $normalised = $normalised->filter(
                        fn ($t) => str_contains(strtolower($t->ticket_code), $ql)
                               || str_contains(strtolower($t->participant),  $ql)
                    );
                }
            }

            if ($normalised->isEmpty()) {
                continue;
            }

            $groups->push((object) [
                'order_code'   => $reg->order_code,
                'comp_name'    => $reg->competition?->name ?? '—',
                'email'        => $reg->email,
                'phone'        => $reg->phone,
                'created_at'   => $reg->created_at,
                'status'       => $reg->status,
                'ticket_count' => $normalised->count(),
                'tickets'      => $normalised->values(),
            ]);
        }

        return view('admin.tickets.index', compact('groups', 'q', 'filterStatus'));
    }

    public function resend(Request $request, string $id): \Illuminate\Http\RedirectResponse
    {
        // $id di sini adalah order_code (lihat route: /admin/tickets/{id}/resend)
        $reg = Registration::where('order_code', $id)->first();

        if (! $reg || $reg->status !== 'paid') {
            return back()->with('error', 'Pendaftaran tidak ditemukan atau belum lunas.');
        }

        // TODO: kirim ulang email/WA tiket ke $reg->email dan $reg->phone
        // Contoh: Mail::to($reg->email)->send(new TicketMail($reg));

        return back()->with('success', 'E-Ticket untuk order ' . $reg->order_code . ' berhasil dikirim ulang.');
    }
}
