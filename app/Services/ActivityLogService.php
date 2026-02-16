<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ActivityLogService
{
    public function log(
        ?User $actor,
        string $action,
        ?Model $subject = null,
        array $properties = [],
        ?User $targetUser = null,
        ?string $ipAddress = null,
        ?string $userAgent = null
    ): ActivityLog {
        return ActivityLog::create([
            'actor_id' => $actor?->id,
            'action' => $action,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject?->getKey(),
            'target_user_id' => $targetUser?->id,
            'properties' => $properties,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'created_at' => now(),
        ]);
    }
}
