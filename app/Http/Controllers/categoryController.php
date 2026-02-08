<?php

namespace App\Http\Controllers;

use App\Models\category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $categories = category::where('user_id',auth()->id())->get();
      return view('categories.index',compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            ]);
        category::create([
            'name' =>$request->name,
            'user_id' =>auth()->id(),
        ]);
        return redirect()->route('categories.index')->with('success','Category ajoutée avec succés');
    }

    /**
     * Display the specified resource.
     */
    public function show(category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(category $category)
    {
        abort_if($category->user_id !== auth()->id(), 403);
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, category $category)
    {
        abort_if($category->user_id !== auth()->id(), 403);
        $request->validate([
            'name' =>'required|string|max:255',
        ]);
        $category->update([
            'name' => $request->name,
        ]);

        return redirect()->route('categories.index')->with('success','Category modifiée');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(category $category)
    {
        abort_if($category->user_id !== auth()->id(), 403);
        $category->delete();

        return redirect()->route('categories.index')->with('success','Category supprimée');
    }
}
