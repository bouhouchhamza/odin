<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mes liens
        </h2>
    </x-slot>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <form method="GET" action="{{ route('links.index') }}"
      class="mb-4 bg-white p-4 rounded shadow flex gap-3">

    <input
        type="text"
        name="search"
        placeholder="Rechercher par titre..."
        value="{{ request('search') }}"
        class="border rounded px-3 py-2"
    >

    <button class="bg-blue-600 text-white px-4 py-2 rounded">
        Search
    </button>

</form>

        <div class="flex justify-end mb-4">
            <a href="{{ route('links.create') }}">+ Ajouter</a>
        </div>
        <div class="bg-white shadow rounded p-4">
                <table class="w-full table-auto border">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2 border">Titre</th>
                            <th class="p-2 border">Url</th>
                            <th class="p-2 border">Categorie</th>
                            <th class="p-2 border">Tags</th>
                            <th class="p-2 border">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($links as $link)
                            <tr class="text-center">
                                <td class="p-2 border">{{ $link->title }}</td>
                                <td class="p-2 border">
                                    <a href="{{ $link->url }}" target="_blank" class="text-blue-600 underline">
                                        Visiter
                                    </a>
                                </td>
                                <td class="p-2 border">{{ $link->category->name }}</td>
                                <td class="p-2 border">
                                    @foreach($link->tags as $tag)
                                        <span class="bg-gray-200 px-2 py-1 rounded text-sm">
                                            {{ $tag->name }}
                                        </span>
                                    @endforeach    
                                </td>
                                <td class="p-2 border space-x-2">
                                    <a href="{{ route('links.edit', $link) }}" class="text-yellow-600">✏️</a>
                                    <form action="{{ route('links.destroy', $link) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('supprimer ?')" class="text-red-600">🗑️</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-4 text-center text-gray-500">Aucun lien trouvé</td>
                            </tr>
                            @endforelse
                    </tbody>
                </table>
        </div>
    </div>
    </div>
</x-app-layout>
