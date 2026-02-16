<?php

namespace App\Listeners;

use App\Events\Favorited;
use App\Events\LinkCreated;
use App\Events\LinkDeleted;
use App\Events\LinkRestored;
use App\Events\LinkShared;
use App\Events\LinkUpdated;
use App\Events\SharePermissionUpdated;
use App\Services\ActivityLogService;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogActivityListener implements ShouldQueue
{
    public function __construct(private readonly ActivityLogService $activityLogService)
    {
    }

    public function handle(object $event): void
    {
        if ($event instanceof LinkCreated) {
            $this->activityLogService->log($event->actor, 'link.created', $event->link);
            return;
        }

        if ($event instanceof LinkUpdated) {
            $this->activityLogService->log($event->actor, 'link.updated', $event->link);
            return;
        }

        if ($event instanceof LinkDeleted) {
            $this->activityLogService->log($event->actor, 'link.deleted', $event->link);
            return;
        }

        if ($event instanceof LinkRestored) {
            $this->activityLogService->log($event->actor, 'link.restored', $event->link);
            return;
        }

        if ($event instanceof Favorited) {
            $this->activityLogService->log($event->actor, 'link.favorited', $event->link);
            return;
        }

        if ($event instanceof LinkShared) {
            $this->activityLogService->log(
                $event->actor,
                'link.shared',
                $event->link,
                ['permission' => $event->permission],
                $event->targetUser
            );
            return;
        }

        if ($event instanceof SharePermissionUpdated) {
            $this->activityLogService->log(
                $event->actor,
                'link.share.permission.updated',
                $event->link,
                ['old_permission' => $event->oldPermission, 'new_permission' => $event->newPermission],
                $event->targetUser
            );
        }
    }
}
