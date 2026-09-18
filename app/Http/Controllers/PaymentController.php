<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Support\UserActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function create(Request $request, string $code)
    {
        $registration = session('registration');

        if (!$registration || ($registration['order_code'] ?? null) !== $code) {
            abort(404, 'Data pembayaran tidak ditemukan.');
        }

        $competition = $registration['competition'] ?? null;
        $competitionData = is_object($competition) ? (array) $competition : (is_array($competition) ? $competition : []);
        $unit = $competitionData['unit'] ?? ($registration['unit'] ?? 'peserta');
        $minPurchase = max(1, (int) ($competitionData['min_purchase'] ?? $registration['min_purchase'] ?? $registration['participant_count'] ?? 1));
        $participantCount = max(1, (int) ($registration['participant_count'] ?? count($registration['participants'] ?? [])));

        if ($participantCount < $minPurchase) {
            $unitLabel = ($unit === 'team') ? 'team' : 'peserta';

            return redirect()->route('register.form', $competitionData['slug'] ?? $registration['competition_slug'] ?? '')->with('error', 'Minimal pembelian untuk event ini adalah ' . $minPurchase . ' ' . $unitLabel . '. Anda belum memenuhi jumlah minimum.');
        }

        session(['payment' => [
            'order_code' => $code,
            'status' => 'pending',
            'amount' => $registration['total_amount'],
            'expires_at' => now()->addMinutes(15)->toDateTimeString(),
        ]]);

        return redirect()->route('payment.show', $code);
    }

    public function show(string $code): View
    {
        $registration = session('registration');
        $payment = session('payment');

        if (!$registration || ($registration['order_code'] ?? null) !== $code) {
            abort(404, 'Data pembayaran tidak ditemukan.');
        }

        return view('payment.show', [
            'registration' => (object) $registration,
            'payment' => (object) ($payment ?? [
                'order_code' => $code,
                'status' => 'pending',
                'amount' => $registration['total_amount'],
                'expires_at' => now()->addMinutes(15)->toDateTimeString(),
            ]),
        ]);
    }

    public function success(string $code): View
    {
        $registration = session('registration');

        if (!$registration || ($registration['order_code'] ?? null) !== $code) {
            abort(404, 'Data pembayaran tidak ditemukan.');
        }

        $tickets = [];
        foreach ($registration['participants'] as $index => $participant) {
            $tickets[] = [
                'ticket_code' => 'BRV-TKT-' . strtoupper(Str::random(6)),
                'participant' => $participant['name'],
                'status'      => 'active',
            ];
        }

        // ── Update database ───────────────────────────────────────
        $dbReg = Registration::where('order_code', $code)->first();
        if ($dbReg && $dbReg->status !== 'paid') {
            $dbReg->update([
                'status'  => 'paid',
                'tickets' => $tickets,
                'paid_at' => now(),
            ]);

            // Kurangi kuota competition jika kuota > 0 (0 = tidak terbatas)
            $competition = $dbReg->competition;
            if ($competition && $competition->quota > 0) {
                $competition->decrement('quota');
            }
        }

        // ── Update session ────────────────────────────────────────
        session(['payment' => array_merge(session('payment', []), ['status' => 'paid'])]);
        session(['ticket' => [
            'order_code'   => $code,
            'tickets'      => $tickets,
            'registration' => (object) $registration,
        ]]);

        $registration['status']  = 'paid';
        $registration['payment'] = ['status' => 'paid', 'amount' => $registration['total_amount']];
        $registration['tickets'] = $tickets;
        UserActivityLog::updateRegistration($code, $registration);

        return view('payment.success', [
            'registration' => (object) $registration,
            'payment'      => (object) array_merge(session('payment', []), ['status' => 'paid']),
            'tickets'      => collect($tickets),
        ]);
    }

    public function checkForm(): View
    {
        return view('payment.check');
    }

    public function check(Request $request)
    {
        $request->validate(['order_code' => ['required', 'string', 'max:50']]);

        $code = strtoupper(trim($request->order_code));
        $registration = Registration::with('competition')
            ->where('order_code', $code)
            ->first();

        if (! $registration) {
            return back()
                ->withInput()
                ->with('not_found', true);
        }

        // Normalise untuk view (view expects ->payment->status)
        $registration->payment = (object) [
            'status' => $registration->status,
            'amount' => $registration->total_amount,
        ];

        return view('payment.check', compact('registration'));
    }

    public function webhook(Request $request)
    {
        return response()->json(['status' => 'ok']);
    }
}
