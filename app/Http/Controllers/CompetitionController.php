<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class CompetitionController extends Controller
{
    public function index(Request $request): View
    {
        // Will query Competition model when available
        // $competitions = Competition::query()->paginate(12);
        return view('competitions.index');
    }

    public function show(string $slug): View
    {
        // Will be: $competition = Competition::where('slug', $slug)->firstOrFail();
        return view('competitions.show');
    }
}
