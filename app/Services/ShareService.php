<?php

namespace App\Services;

use App\Events\LinkShared;
use App\Events\SharePermissionUpdated;
use App\Models\Link;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ShareService
{
    public function share(User $actor, Link $link, User $targetUser, string $permission): void
    {
        DB::transaction(function () use ($actor, $link, $targetUser, $permission) {
            $existing = $link->sharedUsers()
                ->wherePivot('user_id', $targetUser->id)
                ->first();

            if ($existing) {
                $oldPermission = $existing->pivot->permission;

                $link->sharedUsers()->updateExistingPivot($targetUser->id, [
                    'permission' => $permission,
                    'shared_by' => $actor->id,
                ]);

                DB::afterCommit(fn () => event(new SharePermissionUpdated(
                    $link,
                    $actor,
                    $targetUser,
                    $oldPermission,
                    $permission
                )));
            } else {
                $link->sharedUsers()->attach($targetUser->id, [
                    'permission' => $permission,
                    'shared_by' => $actor->id,
                ]);

                DB::afterCommit(fn () => event(new LinkShared(
                    $link,
                    $actor,
                    $targetUser,
                    $permission
                )));
            }
        });
    }

    public function updatePermission(User $actor, Link $link, User $targetUser, string $permission): void
    {
        DB::transaction(function () use ($actor, $link, $targetUser, $permission) {
            $existing = $link->sharedUsers()
                ->wherePivot('user_id', $targetUser->id)
                ->firstOrFail();

            $oldPermission = $existing->pivot->permission;

            $link->sharedUsers()->updateExistingPivot($targetUser->id, [
                'permission' => $permission,
                'shared_by' => $actor->id,
            ]);

            DB::afterCommit(fn () => event(new SharePermissionUpdated(
                $link,
                $actor,
                $targetUser,
                $oldPermission,
                $permission
            )));
        });
    }

    public function revoke(User $actor, Link $link, User $targetUser): void
    {
        DB::transaction(function () use ($link, $targetUser) {
            $link->sharedUsers()->detach($targetUser->id);
        });
    }
}
