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
        $competitions = Competition::orderByDesc('created_at')->get();
        return view('admin.competitions.index', compact('competitions'));
    }

    public function create(): View
    {
        return view('admin.competitions.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'event_name'      => ['nullable', 'string', 'max:255'],
            'description'     => ['nullable', 'string'],
            'category'        => ['required', 'string', 'max:100'],
            'price'           => ['required', 'numeric', 'min:0'],
            'price_community' => ['nullable', 'numeric', 'min:0'],
            'price_early_bird'=> ['nullable', 'numeric', 'min:0'],
            'quota'           => ['nullable', 'integer', 'min:0'],
            'event_date'      => ['required', 'date'],
            'location'        => ['required', 'string', 'max:255'],
            'status'          => ['required', 'in:open,closed,coming_soon,full'],
            'unit'            => ['required', 'in:peserta,team'],
            'rules'           => ['nullable', 'string'],
            'requirements'    => ['nullable', 'string'],
            'poster'          => ['nullable', 'image', 'max:2048'],
        ]);

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
            'name'            => ['required', 'string', 'max:255'],
            'event_name'      => ['nullable', 'string', 'max:255'],
            'description'     => ['nullable', 'string'],
            'category'        => ['required', 'string', 'max:100'],
            'price'           => ['required', 'numeric', 'min:0'],
            'price_community' => ['nullable', 'numeric', 'min:0'],
            'price_early_bird'=> ['nullable', 'numeric', 'min:0'],
            'quota'           => ['nullable', 'integer', 'min:0'],
            'event_date'      => ['required', 'date'],
            'location'        => ['required', 'string', 'max:255'],
            'status'          => ['required', 'in:open,closed,coming_soon,full'],
            'unit'            => ['required', 'in:peserta,team'],
            'rules'           => ['nullable', 'string'],
            'requirements'    => ['nullable', 'string'],
            'poster'          => ['nullable', 'image', 'max:2048'],
        ]);

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
