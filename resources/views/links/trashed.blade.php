<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Trashed Links
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded p-4">
                <table class="w-full table-auto border">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2 border">Title</th>
                            <th class="p-2 border">Deleted At</th>
                            <th class="p-2 border">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($links as $link)
                        <tr>
                            <td class="p-2 border">{{ $link->title }}</td>
                            <td class="p-2 border">{{ $link->deleted_at }}</td>
                            <td class="p-2 border">
                                @can('restore', $link)
                                <form method="POST" action="{{ route('links.restore', $link->id) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button class="text-green-600">Restore</button>
                                </form>
                                @endcan
                                @can('forceDelete', $link)
                                <form method="POST" action="{{ route('links.forceDelete', $link->id) }}" class="inline ml-2">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600">Delete Permanently</button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-4 text-center text-gray-500">No trashed links.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $links->links() }}</div>
        </div>
    </div>
</x-app-layout>
