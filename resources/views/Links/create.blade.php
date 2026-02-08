<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">➕ Ajouter un lien</h2>
    </x-slot>
    <div class="py-6 max-w-3xl mx-auto">
        <form class="bg-white p-6 shadow rounded" action="{{ route('links.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block">Titre</label>*
                <input type="text" name="title" class="w-full border rounded p-2" required>
            </div>
            <div class="mb-4">
                <label class="block">Url</label>
                <input type="url" name="url" class="w-full border rounded p-2" required>
            </div>
            <div class="mb-4">
               <label class="block">Catégorie</label>
               <select name="category_id" class="w-full border rounded p-2">
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>                
                @endforeach
               </select>
            </div>
            <div class="mb-4">
                <label class="block">Tags</label>
                <select name="tags[]" multiple class="w-full border rounded p-2">
                    @foreach ($tags as $tag)
                        <option value="{{ $tag->id }}">{{ $tag->name }}</option>                    
                    @endforeach
                </select>
            </div>
            <div class="flex justify-end gap-2">
                <a href="{{ route('links.index') }}" class="text-gray-600">Annuler</a>
                <button class="bg-green-600 text-white px-4 py-2 rounded">Enregistrer</button>
            </div>
        </form>
    </div>
</x-app-layout>
