<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShareLinkRequest;
use App\Http\Requests\UpdateShareRequest;
use App\Models\Link;
use App\Models\User;
use App\Services\ShareService;
use Illuminate\Http\JsonResponse;

class ShareController extends Controller
{
    public function __construct(private readonly ShareService $shareService)
    {
    }

    public function store(ShareLinkRequest $request, Link $link): JsonResponse
    {
        $target = User::query()->findOrFail($request->integer('user_id'));

        $this->shareService->share(
            $request->user(),
            $link,
            $target,
            $request->string('permission')->toString()
        );

        return response()->json(['message' => 'Link shared']);
    }

    public function update(UpdateShareRequest $request, Link $link, User $user): JsonResponse
    {
        $this->shareService->updatePermission(
            $request->user(),
            $link,
            $user,
            $request->string('permission')->toString()
        );

        return response()->json(['message' => 'Share permission updated']);
    }

    public function destroy(Link $link, User $user): JsonResponse
    {
        $this->authorize('revokeShare', $link);

        $this->shareService->revoke(auth()->user(), $link, $user);

        return response()->json(['message' => 'Share revoked']);
    }
}
