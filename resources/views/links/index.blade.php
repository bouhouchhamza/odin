<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Links</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="GET" action="{{ route('links.index') }}" class="mb-4 bg-white p-4 rounded shadow flex gap-3 items-center">
                <input
                    type="text"
                    name="search"
                    placeholder="Search title or URL..."
                    value="{{ request('search') }}"
                    class="border rounded px-3 py-2 w-full"
                >

                <label class="text-sm flex items-center gap-1">
                    <input type="checkbox" name="favorites_only" value="1" @checked(request('favorites_only'))>
                    Favorites only
                </label>

                <button class="bg-blue-600 text-white px-4 py-2 rounded">Search</button>
            </form>

            <div class="flex justify-between mb-4">
                <div class="flex gap-2">
                    <a href="{{ route('links.trashed') }}" class="px-3 py-2 bg-gray-200 rounded">Trashed</a>
                    <a href="{{ route('links.export.csv', request()->query()) }}" class="px-3 py-2 bg-gray-200 rounded">Export CSV</a>
                </div>
                <a href="{{ route('links.create') }}" class="px-3 py-2 bg-indigo-600 text-white rounded">+ Add Link</a>
            </div>

            <div class="bg-white shadow rounded p-4">
                <table class="w-full table-auto border">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2 border text-left">Title</th>
                            <th class="p-2 border text-left">URL</th>
                            <th class="p-2 border text-left">Category</th>
                            <th class="p-2 border text-left">Tags</th>
                            <th class="p-2 border text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($links as $link)
                            <tr>
                                <td class="p-2 border">{{ $link->title }}</td>
                                <td class="p-2 border">
                                    <a href="{{ $link->url }}" target="_blank" class="text-blue-600 underline">Visit</a>
                                </td>
                                <td class="p-2 border">{{ optional($link->category)->name ?? 'N/A' }}</td>
                                <td class="p-2 border">
                                    @foreach($link->tags as $tag)
                                        <span class="bg-gray-100 px-2 py-1 rounded text-xs">{{ $tag->name }}</span>
                                    @endforeach
                                </td>
                                <td class="p-2 border space-x-2">
                                    <a href="{{ route('links.show', $link) }}" class="text-gray-700">View</a>
                                    @can('update', $link)
                                        <a href="{{ route('links.edit', $link) }}" class="text-yellow-700">Edit</a>
                                    @endcan
                                    @can('delete', $link)
                                        <form action="{{ route('links.destroy', $link) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('Delete this link?')" class="text-red-600">Delete</button>
                                        </form>
                                    @endcan
                                    <form action="{{ route('links.favorite.store', $link) }}" method="POST" class="inline">
                                        @csrf
                                        <button class="text-indigo-600">Favorite</button>
                                    </form>
                                    @can('share', $link)
                                        <button
                                            type="button"
                                            data-share-modal-open="share-modal-{{ $link->id }}"
                                            class="text-blue-600"
                                        >
                                            Share
                                        </button>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-4 text-center text-gray-500">No links found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $links->links() }}
            </div>

            @foreach($links as $link)
                @can('share', $link)
                    @include('links.partials.share-modal', ['link' => $link, 'shareableUsers' => $shareableUsers])
                @endcan
            @endforeach
        </div>
    </div>

    @once
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

                const openModal = function (modal) {
                    modal.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                };

                const closeModal = function (modal) {
                    modal.classList.add('hidden');
                    if (!document.querySelector('[data-share-modal]:not(.hidden)')) {
                        document.body.classList.remove('overflow-hidden');
                    }
                };

                const bindModal = function (modal) {
                    if (!modal) {
                        return;
                    }

                    modal.querySelectorAll('[data-share-modal-close]').forEach(function (element) {
                        if (element.dataset.shareModalCloseBound === '1') {
                            return;
                        }

                        element.dataset.shareModalCloseBound = '1';
                        element.addEventListener('click', function () {
                            closeModal(modal);
                        });
                    });

                    modal.querySelectorAll('[data-share-form]').forEach(function (form) {
                        if (form.dataset.shareFormBound === '1') {
                            return;
                        }

                        form.dataset.shareFormBound = '1';
                        form.addEventListener('submit', async function (event) {
                            event.preventDefault();

                            try {
                                const payload = new URLSearchParams(new FormData(form));
                                const response = await fetch(form.action, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': csrfToken,
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'Accept': 'text/html',
                                        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                                    },
                                    body: payload.toString()
                                });

                                const html = await response.text();
                                const parsed = new DOMParser().parseFromString(html, 'text/html');
                                const updatedModal = parsed.getElementById(modal.id);

                                if (!updatedModal) {
                                    window.location.reload();
                                    return;
                                }

                                modal.replaceWith(updatedModal);
                                bindModal(updatedModal);
                                openModal(updatedModal);
                            } catch (error) {
                                console.error(error);
                                window.location.reload();
                            }
                        });
                    });
                };

                document.querySelectorAll('[data-share-modal-open]').forEach(function (button) {
                    button.addEventListener('click', function () {
                        const modalId = button.getAttribute('data-share-modal-open');
                        const modal = document.getElementById(modalId);
                        if (modal) {
                            bindModal(modal);
                            openModal(modal);
                        }
                    });
                });

                document.querySelectorAll('[data-share-modal]').forEach(function (modal) {
                    bindModal(modal);
                });

                document.addEventListener('keydown', function (event) {
                    if (event.key !== 'Escape') {
                        return;
                    }

                    document.querySelectorAll('[data-share-modal]').forEach(function (modal) {
                        if (!modal.classList.contains('hidden')) {
                            closeModal(modal);
                        }
                    });
                });

                const reopenShareModalId = @json(old('_share_link_id'));
                if (reopenShareModalId) {
                    const modal = document.getElementById('share-modal-' + reopenShareModalId);
                    if (modal) {
                        bindModal(modal);
                        openModal(modal);
                    }
                }
            });
        </script>
    @endonce
</x-app-layout>
