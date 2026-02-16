<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Category Details</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow rounded">
                <p><strong>Name:</strong> {{ $category->name }}</p>
                <a href="{{ route('categories.index') }}" class="text-indigo-600 underline mt-3 inline-block">Back</a>
            </div>
        </div>
    </div>
</x-app-layout>
