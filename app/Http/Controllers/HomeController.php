<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featured_competitions = Competition::where('status', 'open')
            ->orderBy('event_date')
            ->limit(3)
            ->get();

        return view('welcome', compact('featured_competitions'));
    }
}
