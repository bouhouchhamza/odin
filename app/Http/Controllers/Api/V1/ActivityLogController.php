<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $query = ActivityLog::query()->latest('created_at');

        if (!$user->isAdmin()) {
            $query->where(function ($q) use ($user) {
                $q->where('actor_id', $user->id)
                    ->orWhere('target_user_id', $user->id);
            });
        }

        return JsonResource::collection($query->paginate(30));
    }
}
