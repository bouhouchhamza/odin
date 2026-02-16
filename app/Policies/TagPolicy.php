<?php

namespace App\Policies;

use App\Models\Tag;
use App\Models\User;

class TagPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isEditor() || $user->isViewer();
    }

    public function view(User $user, Tag $tag): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isEditor();
    }

    public function update(User $user, Tag $tag): bool
    {
        return $user->isAdmin() || $user->isEditor();
    }

    public function delete(User $user, Tag $tag): bool
    {
        return $user->isAdmin() || $user->isEditor();
    }
}
