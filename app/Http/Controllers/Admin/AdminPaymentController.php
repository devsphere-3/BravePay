<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminPaymentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Registration::with('competition')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('order_code', 'like', "%{$q}%")
                    ->orWhere('email',      'like', "%{$q}%");
            });
        }

        $payments = $query->get()->map(function ($reg) {
            return (object) [
                'registration_id'    => $reg->order_code,
                'registration'       => (object) ['order_code' => $reg->order_code],
                'payment_reference'  => $reg->order_code,
                'payment_method'     => 'QRIS',
                'amount'             => $reg->total_amount,
                'status'             => $reg->status,
                'paid_at'            => $reg->paid_at,
                'competition_name'   => $reg->competition?->name ?? '—',
                'email'              => $reg->email,
            ];
        });

        return view('admin.payments.index', compact('payments'));
    }

    public function show(string $id): View
    {
        $reg = Registration::with('competition')
            ->where('order_code', $id)
            ->firstOrFail();

        $payments = collect([(object) [
            'registration_id'   => $reg->order_code,
            'registration'      => (object) ['order_code' => $reg->order_code],
            'payment_reference' => $reg->order_code,
            'payment_method'    => 'QRIS',
            'amount'            => $reg->total_amount,
            'status'            => $reg->status,
            'paid_at'           => $reg->paid_at,
        ]]);

        return view('admin.payments.index', compact('payments'));
    }
}
