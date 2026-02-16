<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Link;
use App\Services\FavoriteService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class FavoriteController extends Controller
{
    public function __construct(private readonly FavoriteService $favoriteService)
    {
    }

    public function store(Link $link): RedirectResponse
    {
        Gate::authorize('create', [Favorite::class, $link]);

        $this->favoriteService->add(auth()->user(), $link);

        return back()->with('success', 'Added to favorites.');
    }

    public function destroy(Link $link): RedirectResponse
    {
        Gate::authorize('delete', [Favorite::class, $link]);

        $this->favoriteService->remove(auth()->user(), $link);

        return back()->with('success', 'Removed from favorites.');
    }
}
