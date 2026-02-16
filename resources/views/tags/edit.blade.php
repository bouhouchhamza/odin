<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Tag</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('tags.update', $tag) }}" method="POST" class="bg-white p-6 shadow rounded">
                @csrf
                @method('PATCH')
                <label class="block mb-2">Name</label>
                <input name="name" value="{{ old('name', $tag->name) }}" class="w-full border rounded p-2 mb-2" required>
                @error('name')
                    <p class="text-red-600 text-sm mb-2">{{ $message }}</p>
                @enderror
                <button class="bg-indigo-600 text-white px-4 py-2 rounded">Update</button>
            </form>
        </div>
    </div>
</x-app-layout>
