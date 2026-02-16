<?php

namespace App\Listeners;

use App\Events\LinkShared;
use App\Notifications\LinkSharedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendLinkSharedNotificationListener implements ShouldQueue
{
    public function handle(LinkShared $event): void
    {
        if ($event->actor->id === $event->targetUser->id) {
            return;
        }

        $event->targetUser->notify(
            new LinkSharedNotification($event->link, $event->actor, $event->permission)
        );
    }
}
