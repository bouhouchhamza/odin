<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $query = ActivityLog::query()
            ->with(['actor:id,name', 'targetUser:id,name'])
            ->latest('created_at');

        if (!$user->isAdmin()) {
            $query->where(function ($q) use ($user) {
                $q->where('actor_id', $user->id)
                    ->orWhere('target_user_id', $user->id);
            });
        }

        $logs = $query->paginate(30);

        return view('activity-logs.index', compact('logs'));
    }
}
