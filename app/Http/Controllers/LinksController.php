<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLinkRequest;
use App\Http\Requests\UpdateLinkRequest;
use App\Models\Category;
use App\Models\Link;
use App\Models\Tag;
use App\Models\User;
use App\Services\LinkService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LinksController extends Controller
{
    public function __construct(private readonly LinkService $linkService)
    {
    }

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Link::class);
        $user = $request->user() ?? auth()->user();

        $links = Link::query()
            ->visibleTo($user)
            ->with(['category', 'tags', 'sharedUsers:id,name,email'])
            ->withCount('favoritedByUsers')
            ->search($request->string('search')->toString())
            ->when($request->boolean('favorites_only'), function ($query) use ($request) {
                $query->whereHas('favoritedByUsers', function ($sq) use ($request) {
                    $sq->where('users.id', $request->user()->id);
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $shareableUsers = User::query()
            ->whereKeyNot($user->id)
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('links.index', compact('links', 'shareableUsers'));
    }

    public function create(): View
    {
        $this->authorize('create', Link::class);

        $categories = Category::query()
            ->where('user_id', auth()->id())
            ->orderBy('name')
            ->get();

        $tags = Tag::query()->orderBy('name')->get();

        return view('links.create', compact('categories', 'tags'));
    }

    public function store(StoreLinkRequest $request): RedirectResponse
    {
        $this->linkService->create($request->user(), $request->validated());

        return redirect()->route('links.index')->with('success', 'Link created successfully.');
    }

    public function show(Link $link): View
    {
        $this->authorize('view', $link);

        $link->load(['category', 'tags', 'sharedUsers:id,name']);

        return view('links.show', compact('link'));
    }

    public function edit(Link $link): View
    {
        $this->authorize('update', $link);

        $categories = Category::query()
            ->where('user_id', auth()->id())
            ->orderBy('name')
            ->get();

        $tags = Tag::query()->orderBy('name')->get();

        return view('links.edit', compact('link', 'categories', 'tags'));
    }

    public function update(UpdateLinkRequest $request, Link $link): RedirectResponse
    {
        $this->linkService->update($request->user(), $link, $request->validated());

        return redirect()->route('links.index')->with('success', 'Link updated successfully.');
    }

    public function destroy(Link $link): RedirectResponse
    {
        $this->authorize('delete', $link);

        $this->linkService->softDelete(auth()->user(), $link);

        return redirect()->route('links.index')->with('success', 'Link moved to trash.');
    }

    public function trashed(Request $request): View
    {
        $this->authorize('viewAny', Link::class);
        $user = $request->user() ?? auth()->user();

        $links = Link::onlyTrashed()
            ->visibleTo($user)
            ->with(['category', 'tags'])
            ->latest('deleted_at')
            ->paginate(15);

        return view('links.trashed', compact('links'));
    }

    public function restore(int $link): RedirectResponse
    {
        $model = Link::withTrashed()->findOrFail($link);
        $this->authorize('restore', $model);

        $this->linkService->restore(auth()->user(), $model);

        return redirect()->route('links.trashed')->with('success', 'Link restored.');
    }

    public function forceDelete(int $link): RedirectResponse
    {
        $model = Link::withTrashed()->findOrFail($link);
        $this->authorize('forceDelete', $model);

        $this->linkService->forceDelete(auth()->user(), $model);

        return redirect()->route('links.trashed')->with('success', 'Link permanently deleted.');
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $this->authorize('viewAny', Link::class);
        $user = $request->user() ?? auth()->user();

        $query = Link::query()
            ->visibleTo($user)
            ->with(['category:id,name', 'tags:id,name'])
            ->search($request->string('search')->toString())
            ->latest();

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['id', 'title', 'url', 'category', 'tags', 'created_at']);

            $query->chunk(200, function ($links) use ($out) {
                foreach ($links as $link) {
                    fputcsv($out, [
                        $link->id,
                        $link->title,
                        $link->url,
                        optional($link->category)->name,
                        $link->tags->pluck('name')->implode('|'),
                        optional($link->created_at)?->toDateTimeString(),
                    ]);
                }
            });

            fclose($out);
        }, 'links.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }
}
