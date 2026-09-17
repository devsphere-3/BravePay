<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\Registration;
use App\Support\UserActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    public function form(string $slug): View
    {
        $competition = Competition::where('slug', $slug)->firstOrFail();

        return view('register.form', compact('competition'));
    }

    public function store(Request $request, string $slug)
    {
        $competition = Competition::where('slug', $slug)->firstOrFail();
        $minPurchase = max(1, (int) ($competition->min_purchase ?? 1));

        $validated = $request->validate([
            'email'             => ['required', 'email', 'max:255'],
            'phone'             => ['required', 'string', 'max:20'],
            'participant_count' => ['required', 'integer', 'min:' . $minPurchase],
            'participants'      => ['required', 'array', 'min:' . $minPurchase],
            'participants.*.name'          => ['required', 'string', 'max:255'],
            'participants.*.date_of_birth' => ['required', 'date'],
        ]);

        $orderCode    = 'BRV-' . date('Ymd') . '-' . strtoupper(Str::random(4));
        $participants = collect($validated['participants'])->values()->map(fn ($p) => [
            'name'          => $p['name'],
            'date_of_birth' => $p['date_of_birth'],
        ])->all();
        $totalAmount  = (int) $competition->price * (int) $validated['participant_count'];

        // ── Simpan ke database ────────────────────────────────────
        Registration::create([
            'order_code'        => $orderCode,
            'competition_id'    => $competition->id,
            'email'             => $validated['email'],
            'phone'             => $validated['phone'],
            'participant_count' => (int) $validated['participant_count'],
            'total_amount'      => $totalAmount,
            'status'            => 'pending',
            'participants'      => $participants,
            'tickets'           => [],
        ]);

        // ── Tetap simpan ke session untuk alur UI ─────────────────
        $registration = [
            'order_code'       => $orderCode,
            'competition_slug' => $competition->slug,
            'competition'      => (object) [
                'name'         => $competition->name,
                'event_name'   => $competition->event_name,
                'price'        => $competition->price,
                'location'     => $competition->location,
                'event_date'   => $competition->event_date,
                'category'     => $competition->category,
                'slug'         => $competition->slug,
                'unit'         => $competition->unit,
                'min_purchase' => $competition->min_purchase,
            ],
            'email'             => $validated['email'],
            'phone'             => $validated['phone'],
            'participant_count' => (int) $validated['participant_count'],
            'participants'      => $participants,
            'total_amount'      => $totalAmount,
            'status'            => 'pending',
        ];

        session(['registration' => $registration]);
        session(['payment' => [
            'order_code' => $orderCode,
            'status'     => 'pending',
            'amount'     => $totalAmount,
            'expires_at' => now()->addMinutes(15)->toDateTimeString(),
        ]]);

        UserActivityLog::saveRegistration($registration);

        return redirect()->route('register.review', $orderCode);
    }

    public function review(string $code): View
    {
        $registration = session('registration');

        if (!$registration || ($registration['order_code'] ?? null) !== $code) {
            abort(404, 'Data pendaftaran tidak ditemukan.');
        }

        return view('register.review', [
            'registration' => (object) $registration,
            'competition'  => (object) $registration['competition'],
        ]);
    }
}
