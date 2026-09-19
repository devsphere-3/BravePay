<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Registration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminRegistrationController extends Controller
{
    public function index(Request $request): View
    {
        $query = Registration::with('competition')->latest();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('order_code', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('competition_id')) {
            $query->where('competition_id', $request->competition_id);
        }

        $registrations = $query->get()->map(function ($reg) {
            // Normalise untuk kompatibilitas view lama
            $reg->payment = (object) ['status' => $reg->status, 'amount' => $reg->total_amount];
            $reg->id = $reg->order_code;

            return $reg;
        });

        $competitions = Competition::orderBy('name')->get();

        return view('admin.registrations.index', compact('registrations', 'competitions'));
    }

    public function show(string $id): View
    {
        // Cari berdasarkan order_code atau numeric id
        $reg = Registration::with('competition')
            ->where(function ($query) use ($id) {
                $query->where('order_code', $id);

                if (is_numeric($id)) {
                    $query->orWhereKey((int) $id);
                }
            })
            ->firstOrFail();

        // Normalise tickets: pastikan participant adalah string, bukan object
        $tickets = collect($reg->tickets ?? [])->map(function ($t) {
            if (is_array($t)) {
                $t['participant'] = is_array($t['participant'] ?? null)
                    ? ($t['participant']['name'] ?? '—')
                    : ($t['participant'] ?? '—');

                return (object) $t;
            }

            return $t;
        });

        $participants = collect($reg->participants ?? [])->map(fn ($p) => (object) $p);

        // Bungkus registration sebagai object dengan property yang diharapkan view
        $registration = (object) $reg->toArray();
        $registration->competition = $reg->competition;
        $registration->payment = (object) [
            'status' => $reg->status,
            'amount' => $reg->total_amount,
            'payment_method' => 'QRIS',
            'paid_at' => $reg->paid_at,
        ];
        $registration->participants = $participants->all();
        $registration->tickets = $tickets->all();

        return view('admin.registrations.show', compact('registration'));
    }

    public function destroy(string $id): RedirectResponse
    {
        $registration = Registration::where('order_code', $id)
            ->orWhere('id', is_numeric($id) ? $id : 0)
            ->firstOrFail();

        $registration->delete();

        return redirect()->route('admin.registrations.index')
            ->with('success', 'Pendaftar berhasil dihapus.');
    }
}
