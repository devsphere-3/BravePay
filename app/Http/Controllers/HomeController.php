<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        // Will be populated when Competition model & DB are set up
        $featured_competitions = collect([]);

        return view('welcome', compact('featured_competitions'));
    }
}
