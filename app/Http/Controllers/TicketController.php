<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class TicketController extends Controller
{
    public function show(string $code): View
    {
        // $ticket = Ticket::where('ticket_code', $code)->with(['participant','registration.competition'])->firstOrFail();
        return view('ticket.show');
    }

    public function download(string $code): Response
    {
        // Generate and stream PDF of the ticket
        // return response()->download($pdfPath, 'BRAVEPAY-E-TICKET-'.$code.'.pdf');
        abort(501, 'PDF generation requires a PDF library (e.g. barryvdh/laravel-dompdf)');
    }
}
