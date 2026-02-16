<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShareLinkRequest;
use App\Http\Requests\UpdateShareRequest;
use App\Models\Link;
use App\Models\User;
use App\Services\ShareService;
use Illuminate\Http\RedirectResponse;

class ShareController extends Controller
{
    public function __construct(private readonly ShareService $shareService)
    {
    }

    public function store(ShareLinkRequest $request, Link $link): RedirectResponse
    {
        $targetUser = User::findOrFail($request->integer('user_id'));

        $this->shareService->share(
            $request->user(),
            $link,
            $targetUser,
            $request->string('permission')->toString()
        );

        return back()->with('success', 'Link shared successfully.');
    }

    public function update(UpdateShareRequest $request, Link $link, User $user): RedirectResponse
    {
        $this->shareService->updatePermission(
            $request->user(),
            $link,
            $user,
            $request->string('permission')->toString()
        );

        return back()->with('success', 'Share permission updated.');
    }

    public function destroy(Link $link, User $user): RedirectResponse
    {
        $this->authorize('revokeShare', $link);

        $this->shareService->revoke(auth()->user(), $link, $user);

        return back()->with('success', 'Share revoked.');
    }
}
