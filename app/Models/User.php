<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'role_user'
        )->withTimestamps();
    }

    public function links()
    {
        return $this->hasMany(Link::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function sharedLinks()
    {
        return $this->belongsToMany(
            Link::class,
            'link_user'
        )->withPivot(['permission', 'shared_by'])->withTimestamps();
    }

    public function favorites()
    {
        return $this->belongsToMany(
            Link::class,
            'favorites'
        )->withTimestamps();
    }

    public function hasRole(string $role): bool
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isEditor(): bool
    {
        return $this->hasRole('editor');
    }

    public function isViewer(): bool
    {
        return $this->hasRole('viewer');
    }
}
