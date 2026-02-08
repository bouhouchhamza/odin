<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Stats cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white p-5 rounded shadow">
                    <p class="text-gray-500">Links</p>
                    <p class="text-3xl font-bold">{{ $linksCount }}</p>
                    <a href="{{ route('links.index') }}" class="text-indigo-600 underline text-sm">Voir les liens</a>
                </div>

                <div class="bg-white p-5 rounded shadow">
                    <p class="text-gray-500">Catégories</p>
                    <p class="text-3xl font-bold">{{ $categoriesCount }}</p>
                    <a href="{{ route('categories.index') }}" class="text-indigo-600 underline text-sm">Voir les catégories</a>
                </div>

                <div class="bg-white p-5 rounded shadow">
                    <p class="text-gray-500">Tags utilisés</p>
                    <p class="text-3xl font-bold">{{ $tagsUsedCount }}</p>
                    <a href="{{ route('links.index') }}" class="text-indigo-600 underline text-sm">Gérer les tags</a>
                </div>
            </div>

            {{-- Quick actions --}}
            <div class="bg-white p-5 rounded shadow flex flex-wrap gap-3">
                <a href="{{ route('links.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded">+ Ajouter un lien</a>
                <a href="{{ route('categories.create') }}" class="px-4 py-2 bg-gray-800 text-white rounded">+ Ajouter une catégorie</a>
                <a href="{{ route('links.index') }}" class="px-4 py-2 bg-gray-100 rounded">Liste des liens</a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Latest links --}}
                <div class="bg-white p-5 rounded shadow">
                    <h3 class="font-semibold mb-4">Derniers liens</h3>

                    @forelse($latestLinks as $link)
                        <div class="border-b py-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium">{{ $link->title }}</p>
                                    <p class="text-sm text-gray-500">
                                        {{ optional($link->category)->name ?? 'Sans catégorie' }}
                                    </p>
                                </div>
                                <a href="{{ $link->url }}" target="_blank" class="text-indigo-600 underline text-sm">Visiter</a>
                            </div>

                            <div class="mt-2 flex flex-wrap gap-2">
                                @foreach($link->tags as $tag)
                                    <span class="bg-gray-100 px-2 py-1 rounded text-xs">{{ $tag->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500">Aucun lien pour le moment.</p>
                    @endforelse
                </div>

                {{-- Top categories --}}
                <div class="bg-white p-5 rounded shadow">
                    <h3 class="font-semibold mb-4">Top catégories</h3>

                    @forelse($topCategories as $cat)
                        <div class="flex items-center justify-between border-b py-3">
                            <span>{{ $cat->name }}</span>
                            <span class="text-sm text-gray-600">{{ $cat->links_count }} liens</span>
                        </div>
                    @empty
                        <p class="text-gray-500">Aucune catégorie.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
