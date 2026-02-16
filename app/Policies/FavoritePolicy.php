<?php

namespace App\Policies;

use App\Models\Favorite;
use App\Models\Link;
use App\Models\User;

class FavoritePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isEditor() || $user->isViewer();
    }

    public function create(User $user, Link $link): bool
    {
        return $user->can('view', $link);
    }

    public function delete(User $user, Link $link): bool
    {
        return $user->can('view', $link);
    }
}
