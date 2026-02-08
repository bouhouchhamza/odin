<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Link;
use App\Models\Tag;
use Illuminate\Http\Request;

class LinksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $links = Link::where('user_id', auth()->id())
            ->with(['category', 'tags'])
            ->get();

        return view('links.index', compact('links'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('user_id', auth()->id())->get();
        $tags = Tag::all();

        return view('links.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'array',
        ]);

        $link = Link::create([
            'title' => $request->title,
            'url' => $request->url,
            'category_id' => $request->category_id,
            'user_id' => auth()->id(),
        ]);

        if ($request->has('tags')) {
            $link->tags()->sync($request->tags);
        }

        return redirect()->route('links.index')->with('success', 'Link ajoute avec succes');
    }

    /**
     * Display the specified resource.
     */
    public function show(Link $link)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Link $link)
    {
        $categories = Category::where('user_id', auth()->id())->get();
        $tags = Tag::all();

        return view('links.edit', compact('link', 'categories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Link $link)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        $link->update([
            'title' => $request->title,
            'url' => $request->url,
            'category_id' => $request->category_id,
        ]);

        if ($request->has('tags')) {
            $link->tags()->sync($request->tags);
        } else {
            $link->tags()->detach();
        }

        return redirect()->route('links.index')->with('success', 'link modifie');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Link $link)
    {
        $link->delete();

        return redirect()->route('links.index')->with('success', 'link supprime');
    }
}
