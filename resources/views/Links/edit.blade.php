<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ✏️ Modifier le lien
        </h2>
    </x-slot>
    <div class="py-6">
        <div class="bg-white shadow rounded p-6">
            <form action="{{ route('links.update',$link) }}" class="bg-white shadow rounded p-6" method="POST">
                @csrf
                @method('PUT')

                    {{-- titre --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">
                            Titre
                        </label>
                        <input type="text" name="title" value="{{ old('title', $link->title) }}" class="w-full border-gray-300" required>
                    </div>
                    {{-- URL --}}
                    <div class="mb-">
                        <label class="block text-sm font-medium mb-1">
                            URL
                        </label>
                        <input type="url" name="url" value="{{ old('url', $link->url) }}" class="w-full border-gray-300 rounded px-3 py-2" required>
                    </div>
                    {{-- Catégorie --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Catégorie</label>
                        <select name="category_id" class="w-full border-gray-300 rounded px-3 py-2">
                            @foreach ($categories as $category )
                            <option value="{{ $category->id }}" @selected(old('category_id', $link->category_id))>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Tags</label>
                        <select name="tags[]" multiple class="w-full border-gray-300 rounded px-3 py-2">
                            @foreach ($tags as $tag )
                                <option value="{{ $tag->id }}" @selected(old('tags') ?in_array($tag->id, old('tags')): $link->tags->contains($tag)) >
                                    {{ $tag->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    {{-- Actions --}}
                    <div class="flex justify-end gap-3">
                        <a class="text-gray-600 hover:underline" href="{{ route('links.index') }}">
                            Annuler
                        </a>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Mettre à jour</button>
                    </div>
            </form>
        </div>
    </div>
</x-app-layout>