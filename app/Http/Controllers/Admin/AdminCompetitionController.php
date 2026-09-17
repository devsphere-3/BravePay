<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminCompetitionController extends Controller
{
    public function index(): View
    {
        $competitions = Competition::withCount([
            'registrations',
            'registrations as paid_registrations_count' => fn ($q) => $q->where('status', 'paid'),
        ])->orderByDesc('created_at')->get();

        return view('admin.competitions.index', compact('competitions'));
    }

    public function create(): View
    {
        return view('admin.competitions.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'event_name'   => ['nullable', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'category'     => ['required', 'string', 'max:100'],
            'price'        => ['required', 'numeric', 'min:0'],
            'min_purchase' => ['required', 'integer', 'min:1'],
            'quota'        => ['nullable', 'integer', 'min:0'],
            'event_date'   => ['required', 'date'],
            'location'     => ['required', 'string', 'max:255'],
            'status'       => ['required', 'in:open,closed,coming_soon,full'],
            'unit'         => ['required', 'in:peserta,team'],
            'rules'        => ['nullable', 'string'],
            'requirements' => ['nullable', 'string'],
            'schedule'     => ['nullable', 'string'],
            'poster'       => ['nullable', 'image', 'max:2048'],
        ]);

        if (!empty($validated['schedule']) && !is_array(json_decode($validated['schedule'], true))) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'schedule' => ['Format jadwal tidak valid. Gunakan JSON seperti [{"time":"09:00","event":"Registrasi"}].'],
            ]);
        }

        $validated['slug'] = Str::slug($validated['name']) . '-' . time();

        if ($request->hasFile('poster')) {
            $validated['poster'] = $request->file('poster')->store('posters', 'public');
        }

        Competition::create($validated);

        return redirect()->route('admin.competitions.index')
            ->with('success', 'Lomba "' . $validated['name'] . '" berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        $competition = Competition::findOrFail($id);
        return view('admin.competitions.form', compact('competition'));
    }

    public function update(Request $request, int $id)
    {
        $competition = Competition::findOrFail($id);

        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'event_name'   => ['nullable', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'category'     => ['required', 'string', 'max:100'],
            'price'        => ['required', 'numeric', 'min:0'],
            'min_purchase' => ['required', 'integer', 'min:1'],
            'quota'        => ['nullable', 'integer', 'min:0'],
            'event_date'   => ['required', 'date'],
            'location'     => ['required', 'string', 'max:255'],
            'status'       => ['required', 'in:open,closed,coming_soon,full'],
            'unit'         => ['required', 'in:peserta,team'],
            'rules'        => ['nullable', 'string'],
            'requirements' => ['nullable', 'string'],
            'schedule'     => ['nullable', 'string'],
            'poster'       => ['nullable', 'image', 'max:2048'],
        ]);

        if (!empty($validated['schedule']) && !is_array(json_decode($validated['schedule'], true))) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'schedule' => ['Format jadwal tidak valid. Gunakan JSON seperti [{"time":"09:00","event":"Registrasi"}].'],
            ]);
        }

        if ($request->hasFile('poster')) {
            $validated['poster'] = $request->file('poster')->store('posters', 'public');
        }

        $competition->update($validated);

        return redirect()->route('admin.competitions.index')
            ->with('success', 'Lomba "' . $competition->name . '" berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $competition = Competition::findOrFail($id);
        $name = $competition->name;
        $competition->delete();

        return redirect()->route('admin.competitions.index')
            ->with('success', 'Lomba "' . $name . '" berhasil dihapus.');
    }
}
