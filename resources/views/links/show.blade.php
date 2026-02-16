<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Link Details
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow rounded">
                <h3 class="text-lg font-semibold mb-2">{{ $link->title }}</h3>
                <p class="mb-3">
                    <a href="{{ $link->url }}" class="text-blue-600 underline" target="_blank">{{ $link->url }}</a>
                </p>
                <p class="mb-3">
                    Category: {{ optional($link->category)->name ?? 'N/A' }}
                </p>
                <p class="mb-3">
                    Tags:
                    @foreach($link->tags as $tag)
                        <span class="bg-gray-100 px-2 py-1 rounded text-xs">{{ $tag->name }}</span>
                    @endforeach
                </p>
                <a href="{{ route('links.index') }}" class="text-gray-700 underline">Back</a>
            </div>
        </div>
    </div>
</x-app-layout>
