<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLinkRequest;
use App\Http\Requests\UpdateLinkRequest;
use App\Http\Resources\LinkResource;
use App\Models\Link;
use App\Services\LinkService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function __construct(private readonly LinkService $linkService)
    {
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Link::class);

        $links = Link::query()
            ->visibleTo($request->user())
            ->with(['category:id,name', 'tags:id,name'])
            ->search($request->string('search')->toString())
            ->latest()
            ->paginate(20);

        return LinkResource::collection($links);
    }

    public function store(StoreLinkRequest $request): JsonResponse
    {
        $link = $this->linkService->create($request->user(), $request->validated());

        return response()->json([
            'message' => 'Link created',
            'data' => new LinkResource($link),
        ], 201);
    }

    public function show(Link $link): LinkResource
    {
        $this->authorize('view', $link);

        return new LinkResource($link->load(['category:id,name', 'tags:id,name']));
    }

    public function update(UpdateLinkRequest $request, Link $link): JsonResponse
    {
        $updated = $this->linkService->update($request->user(), $link, $request->validated());

        return response()->json([
            'message' => 'Link updated',
            'data' => new LinkResource($updated),
        ]);
    }

    public function destroy(Link $link): JsonResponse
    {
        $this->authorize('delete', $link);

        $this->linkService->softDelete(auth()->user(), $link);

        return response()->json(['message' => 'Link moved to trash']);
    }

    public function restore(int $link): JsonResponse
    {
        $model = Link::withTrashed()->findOrFail($link);
        $this->authorize('restore', $model);

        $this->linkService->restore(auth()->user(), $model);

        return response()->json(['message' => 'Link restored']);
    }

    public function forceDelete(int $link): JsonResponse
    {
        $model = Link::withTrashed()->findOrFail($link);
        $this->authorize('forceDelete', $model);

        $this->linkService->forceDelete(auth()->user(), $model);

        return response()->json(['message' => 'Link permanently deleted']);
    }
}
