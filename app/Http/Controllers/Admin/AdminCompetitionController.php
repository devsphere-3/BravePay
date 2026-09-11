<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminCompetitionController extends Controller
{
    public function index(): View
    {
        return view('admin.competitions.index');
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
            'quota'        => ['nullable', 'integer', 'min:0'],
            'event_date'   => ['required', 'date'],
            'location'     => ['required', 'string', 'max:255'],
            'status'       => ['required', 'in:open,closed,coming_soon,full'],
            'rules'        => ['nullable', 'string'],
            'requirements' => ['nullable', 'string'],
            'poster'       => ['nullable', 'image', 'max:2048'],
        ]);

        // Competition::create($validated); // implement when model exists
        return redirect()->route('admin.competitions.index')->with('success', 'Lomba berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        // $competition = Competition::findOrFail($id);
        return view('admin.competitions.form');
    }

    public function update(Request $request, int $id)
    {
        return redirect()->route('admin.competitions.index')->with('success', 'Lomba berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        // Competition::findOrFail($id)->delete();
        return redirect()->route('admin.competitions.index')->with('success', 'Lomba berhasil dihapus.');
    }
}
