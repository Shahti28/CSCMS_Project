<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Display activity logs with filtering (Feature 19).
     */
    public function index(Request $request)
    {
        $query = ActivityLog::orderByDesc('created_at');

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        if ($request->filled('user')) {
            $query->where('user', 'like', '%' . $request->user . '%');
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(20);

        $modules = ActivityLog::select('module')->distinct()->pluck('module');

        return view('activity_logs.index', compact('logs', 'modules'));
    }
}
