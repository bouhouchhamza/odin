<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-grey-800 leading-tight">
            {{ __('categories') }}
        </h2>
    </x-slot>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Button add --}}
            <div class="mb-4">
                <a href="{{ route('categories.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded">
                    + Add Category
                </a>
            </div>
        </div>
        {{-- -Flash message --}}
        @if (session('success'))
        <div class="mb-4 p-3 bg-green-100 bg-text-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
        @endif

        {{-- Categories Table --}}
        <div class="bg-white shadow rounded">
            <table class="w-full border">
                <thead class="bg-grey-100">
                    <tr>
                        <th class="p-3 border text-left"></th>
                        <th class="p-3 border text-left"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td class="p-3 border">
                                {{ $category->name }}
                            </td>
                            <td class="p-3 border flex gap-2">
                                <a href="{{ route('categories.edit', $category) }}" class="text-blue-600"> Edit</a>

                                <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete this category?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="p-3 text-center text-grey-500">
                                No categories found.
                            </td>
                        </tr>
                        @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>