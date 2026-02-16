<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TagController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Tag::class);

        $tags = Tag::query()->orderBy('name')->paginate(20);

        return view('tags.index', compact('tags'));
    }

    public function create(): View
    {
        $this->authorize('create', Tag::class);

        return view('tags.create');
    }

    public function store(StoreTagRequest $request): RedirectResponse
    {
        Tag::create([
            'name' => $request->string('name')->toString(),
        ]);

        return redirect()->route('tags.index')->with('success', 'Tag created.');
    }

    public function show(Tag $tag): View
    {
        $this->authorize('view', $tag);

        return view('tags.show', compact('tag'));
    }

    public function edit(Tag $tag): View
    {
        $this->authorize('update', $tag);

        return view('tags.edit', compact('tag'));
    }

    public function update(UpdateTagRequest $request, Tag $tag): RedirectResponse
    {
        $tag->update([
            'name' => $request->string('name')->toString(),
        ]);

        return redirect()->route('tags.index')->with('success', 'Tag updated.');
    }

    public function destroy(Tag $tag): RedirectResponse
    {
        $this->authorize('delete', $tag);

        $tag->delete();

        return redirect()->route('tags.index')->with('success', 'Tag deleted.');
    }
}
