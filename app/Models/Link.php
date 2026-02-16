<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Link extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'url',
        'normalized_url',
        'description',
        'user_id',
        'category_id',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    public function sharedUsers()
    {
        return $this->belongsToMany(
            User::class,
            'link_user'
        )->withPivot(['permission', 'shared_by'])->withTimestamps();
    }

    public function favoritedByUsers()
    {
        return $this->belongsToMany(
            User::class,
            'favorites'
        )->withTimestamps();
    }

    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        if ($user->isAdmin()) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($user) {
            $q->where('user_id', $user->id)
                ->orWhereHas('sharedUsers', function (Builder $shared) use ($user) {
                    $shared->where('users.id', $user->id);
                });
        });
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        $search = trim((string) $search);

        if ($search === '') {
            return $query;
        }

        if (strlen($search) >= 3 && config('app.fulltext_search', false)) {
            return $query->whereRaw("MATCH(title, url) AGAINST (? IN BOOLEAN MODE)", [$search.'*']);
        }

        return $query->where(function (Builder $q) use ($search) {
            $q->where('title', 'like', '%'.$search.'%')
                ->orWhere('url', 'like', '%'.$search.'%');
        });
    }
}
