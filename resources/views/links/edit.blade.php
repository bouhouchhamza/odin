<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Link</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <form class="bg-white p-6 shadow rounded" action="{{ route('links.update', $link) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="mb-4">
                    <label class="block mb-1">Title</label>
                    <input type="text" name="title" value="{{ old('title', $link->title) }}" class="w-full border rounded p-2" required>
                    @error('title')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="mb-4">
                    <label class="block mb-1">URL</label>
                    <input type="url" name="url" value="{{ old('url', $link->url) }}" class="w-full border rounded p-2" required>
                    @error('url')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="mb-4">
                    <label class="block mb-1">Description</label>
                    <textarea name="description" class="w-full border rounded p-2" rows="3">{{ old('description', $link->description) }}</textarea>
                    @error('description')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="mb-4">
                    <label class="block mb-1">Category</label>
                    <select name="category_id" class="w-full border rounded p-2" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $link->category_id) == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="mb-4">
                    <label class="block mb-1">Tags</label>
                    <select name="tags[]" multiple class="w-full border rounded p-2">
                        @foreach($tags as $tag)
                            <option value="{{ $tag->id }}"
                                @selected(in_array($tag->id, old('tags', $link->tags->pluck('id')->all())))>
                                {{ $tag->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end gap-2">
                    <a href="{{ route('links.index') }}" class="text-gray-700">Cancel</a>
                    <button class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
