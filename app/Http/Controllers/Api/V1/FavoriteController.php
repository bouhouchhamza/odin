<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Link;
use App\Services\FavoriteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class FavoriteController extends Controller
{
    public function __construct(private readonly FavoriteService $favoriteService)
    {
    }

    public function store(Link $link): JsonResponse
    {
        Gate::authorize('create', [Favorite::class, $link]);

        $this->favoriteService->add(auth()->user(), $link);

        return response()->json(['message' => 'Added to favorites']);
    }

    public function destroy(Link $link): JsonResponse
    {
        Gate::authorize('delete', [Favorite::class, $link]);

        $this->favoriteService->remove(auth()->user(), $link);

        return response()->json(['message' => 'Removed from favorites']);
    }
}
