<?php

namespace App\Services;

use App\Events\LinkCreated;
use App\Events\LinkDeleted;
use App\Events\LinkRestored;
use App\Events\LinkUpdated;
use App\Models\Link;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LinkService
{
    public function create(User $actor, array $data): Link
    {
        return DB::transaction(function () use ($actor, $data) {
            $link = Link::create([
                'title' => $data['title'],
                'url' => $data['url'],
                'normalized_url' => $data['normalized_url'],
                'description' => $data['description'] ?? null,
                'category_id' => $data['category_id'],
                'user_id' => $actor->id,
            ]);

            $link->tags()->sync($data['tags'] ?? []);

            DB::afterCommit(fn () => event(new LinkCreated($link, $actor)));

            return $link->fresh(['category', 'tags']);
        });
    }

    public function update(User $actor, Link $link, array $data): Link
    {
        return DB::transaction(function () use ($actor, $link, $data) {
            $link->update([
                'title' => $data['title'],
                'url' => $data['url'],
                'normalized_url' => $data['normalized_url'],
                'description' => $data['description'] ?? null,
                'category_id' => $data['category_id'],
            ]);

            $link->tags()->sync($data['tags'] ?? []);

            DB::afterCommit(fn () => event(new LinkUpdated($link, $actor)));

            return $link->fresh(['category', 'tags']);
        });
    }

    public function softDelete(User $actor, Link $link): void
    {
        DB::transaction(function () use ($actor, $link) {
            $link->delete();
            DB::afterCommit(fn () => event(new LinkDeleted($link, $actor)));
        });
    }

    public function restore(User $actor, Link $link): void
    {
        DB::transaction(function () use ($actor, $link) {
            $link->restore();
            DB::afterCommit(fn () => event(new LinkRestored($link, $actor)));
        });
    }

    public function forceDelete(User $actor, Link $link): void
    {
        DB::transaction(function () use ($link) {
            $link->tags()->detach();
            $link->sharedUsers()->detach();
            $link->favoritedByUsers()->detach();
            $link->forceDelete();
        });
    }
}
