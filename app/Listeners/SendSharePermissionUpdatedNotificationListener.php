<?php

namespace App\Listeners;

use App\Events\SharePermissionUpdated;
use App\Notifications\SharePermissionUpdatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendSharePermissionUpdatedNotificationListener implements ShouldQueue
{
    public function handle(SharePermissionUpdated $event): void
    {
        if ($event->actor->id === $event->targetUser->id) {
            return;
        }

        $event->targetUser->notify(
            new SharePermissionUpdatedNotification(
                $event->link,
                $event->actor,
                $event->oldPermission,
                $event->newPermission
            )
        );
    }
}
