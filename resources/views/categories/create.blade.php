<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold ext-xl text-gray-800 leading-tight">
            {{ __('Add Category') }}
        </h2>
    </x-slot>
    <div class="py-8">
        <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
            <form method="POST" action="{{route('categories.store')}}">
                @csrf
                <div class="mb-4">
                   <x-input-label for="name" value="Category Name"/>
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" required/>
                    <x-input-error :messages="$errors->get('name')" class="mt-2"/>  
                </div>
                    <div class="flex justify-end">
                        <x-primary-button>
                            Save
                        </x-primary-button>
                    </div>
            </form>
        </div>
    </div>
</x-app-layout>