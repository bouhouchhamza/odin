<?php

namespace App\Providers;

use App\Events\Favorited;
use App\Events\LinkCreated;
use App\Events\LinkDeleted;
use App\Events\LinkRestored;
use App\Events\LinkShared;
use App\Events\LinkUpdated;
use App\Events\SharePermissionUpdated;
use App\Listeners\LogActivityListener;
use App\Listeners\SendLinkSharedNotificationListener;
use App\Listeners\SendSharePermissionUpdatedNotificationListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        LinkCreated::class => [LogActivityListener::class],
        LinkUpdated::class => [LogActivityListener::class],
        LinkDeleted::class => [LogActivityListener::class],
        LinkRestored::class => [LogActivityListener::class],
        Favorited::class => [LogActivityListener::class],
        LinkShared::class => [
            LogActivityListener::class,
            SendLinkSharedNotificationListener::class,
        ],
        SharePermissionUpdated::class => [
            LogActivityListener::class,
            SendSharePermissionUpdatedNotificationListener::class,
        ],
    ];
}
