<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\View\View;

class AdminActivityLogController extends Controller
{
    public function index(): View
    {
        $activities = ActivityLog::with('user')->latest()->paginate(25);

        return view('admin.activity-logs.index', compact('activities'));
    }
}
