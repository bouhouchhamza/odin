<?php

namespace App\Events;

use App\Models\Link;
use App\Models\User;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SharePermissionUpdated implements ShouldDispatchAfterCommit
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Link $link,
        public User $actor,
        public User $targetUser,
        public string $oldPermission,
        public string $newPermission
    ) {
    }
}
