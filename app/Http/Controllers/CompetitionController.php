<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompetitionController extends Controller
{
    public function index(Request $request): View
    {
        $query = Competition::query();

        // Search
        if ($q = $request->get('q')) {
            $query->where(function ($qb) use ($q) {
                $qb->where('name', 'like', "%{$q}%")
                   ->orWhere('event_name', 'like', "%{$q}%")
                   ->orWhere('location', 'like', "%{$q}%");
            });
        }

        // Category filter
        if ($cat = $request->get('category')) {
            $query->where('category', $cat);
        }

        // Status filter
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        // Sort
        match ($request->get('sort', 'latest')) {
            'oldest'     => $query->orderBy('created_at'),
            'price_asc'  => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'name'       => $query->orderBy('name'),
            default      => $query->orderByDesc('created_at'),
        };

        $competitions = $query->paginate(12)->withQueryString();

        return view('competitions.index', compact('competitions'));
    }

    public function show(string $slug): View
    {
        $competition = Competition::where('slug', $slug)->firstOrFail();

        return view('competitions.show', compact('competition'));
    }
}
