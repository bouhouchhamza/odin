<?php

namespace App\Services;

use App\Events\Favorited;
use App\Models\Link;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class FavoriteService
{
    public function add(User $actor, Link $link): void
    {
        DB::transaction(function () use ($actor, $link) {
            $actor->favorites()->syncWithoutDetaching([$link->id]);

            DB::afterCommit(fn () => event(new Favorited($link, $actor)));
        });
    }

    public function remove(User $actor, Link $link): void
    {
        DB::transaction(function () use ($actor, $link) {
            $actor->favorites()->detach($link->id);
        });
    }
}
