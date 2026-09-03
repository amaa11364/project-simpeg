<?php
namespace App\Http\Controllers;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ActivityLogController extends Controller {
    public function index(Request $request) {
        $query = ActivityLog::latest();

        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('user_name', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%")
                  ->orWhere('action', 'like', "%$search%");
            });
        }

        if ($request->action) {
            $query->where('action', $request->action);
        }

        return Inertia::render('ActivityLog/Index', [
            'logs' => $query->paginate(20)->withQueryString(),
            'filters' => $request->only(['search', 'action']),
        ]);
    }
}
