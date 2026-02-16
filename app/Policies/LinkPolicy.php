<?php

namespace App\Policies;

use App\Models\Link;
use App\Models\User;

class LinkPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isEditor() || $user->isViewer();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Link $link): bool
    {
       if ($link->user_id === $user->id) return true;

       return $link->sharedUsers()
           ->wherePivot('user_id', $user->id)
           ->exists();
       
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isEditor();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Link $link): bool
    {
        if ($link->user_id === $user->id) return true;

        return $link->sharedUsers()
            ->wherePivot('user_id', $user->id)
            ->wherePivot('permission', 'edit')
            ->exists();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Link $link): bool
    {
        return $link->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Link $link): bool
    {
        return $link->user_id === $user->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Link $link): bool
    {
        return $user->isAdmin();
    }

    public function share(User $user, Link $link): bool
    {
        return $link->user_id === $user->id;
    }

    public function updateSharePermission(User $user, Link $link): bool
    {
        return $link->user_id === $user->id;
    }

    public function revokeShare(User $user, Link $link): bool
    {
        return $link->user_id === $user->id;
    }
}
