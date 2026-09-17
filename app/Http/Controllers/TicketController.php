<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class TicketController extends Controller
{
    /**
     * Tampilkan satu tiket individual berdasarkan ticket_code.
     */
    public function show(string $code): View
    {
        $ticketData = session('ticket');
        $ticket = null;

        if ($ticketData && isset($ticketData['tickets'])) {
            foreach ($ticketData['tickets'] as $item) {
                if (($item['ticket_code'] ?? null) === $code) {
                    $ticket = $item;
                    break;
                }
            }
        }

        if (!$ticket) {
            abort(404, 'E-Ticket tidak ditemukan.');
        }

        return view('ticket.show', [
            'ticket'       => (object) $ticket,
            'registration' => (object) ($ticketData['registration'] ?? []),
        ]);
    }

    /**
     * Tampilkan semua tiket dalam satu grup order — satu halaman untuk semua peserta.
     */
    public function group(string $orderCode): View
    {
        $ticketData   = session('ticket');
        $registration = session('registration');

        // Coba ambil dari session ticket dulu, fallback ke session registration
        $tickets  = [];
        $regData  = null;

        if ($ticketData && ($ticketData['order_code'] ?? null) === $orderCode) {
            $tickets = $ticketData['tickets'] ?? [];
            $regData = $ticketData['registration'] ?? null;
        } elseif ($registration && ($registration['order_code'] ?? null) === $orderCode) {
            $tickets = $registration['tickets'] ?? [];
            $regData = $registration;
        }

        if (empty($tickets)) {
            abort(404, 'Grup e-ticket tidak ditemukan.');
        }

        $competition = null;
        if ($regData) {
            $comp = $regData['competition'] ?? $regData->competition ?? null;
            if (is_object($comp)) {
                $competition = $comp;
            } elseif (is_array($comp)) {
                $competition = (object) $comp;
            }
        }

        return view('ticket.group', [
            'order_code'   => $orderCode,
            'tickets'      => collect($tickets)->map(fn ($t) => (object) $t),
            'registration' => $regData ? (object) $regData : null,
            'competition'  => $competition,
        ]);
    }

    public function download(string $code): Response
    {
        abort(501, 'PDF generation requires a PDF library (e.g. barryvdh/laravel-dompdf)');
    }
}
