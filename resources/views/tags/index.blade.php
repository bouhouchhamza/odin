<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tags</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('tags.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded">+ Add Tag</a>
            </div>

            <div class="bg-white p-4 shadow rounded">
                <table class="w-full border">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-2 border text-left">Name</th>
                            <th class="p-2 border text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tags as $tag)
                            <tr>
                                <td class="p-2 border">{{ $tag->name }}</td>
                                <td class="p-2 border">
                                    <a href="{{ route('tags.edit', $tag) }}" class="text-blue-600">Edit</a>
                                    <form action="{{ route('tags.destroy', $tag) }}" method="POST" class="inline ml-2">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="p-3 text-gray-500 text-center">No tags found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $tags->links() }}</div>
        </div>
    </div>
</x-app-layout>
