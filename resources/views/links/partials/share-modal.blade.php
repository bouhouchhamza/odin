@php
    $modalId = 'share-modal-'.$link->id;
    $hasErrorsForThisModal = (string) old('_share_link_id') === (string) $link->id && $errors->any();
@endphp

<div id="{{ $modalId }}" class="fixed inset-0 z-50 hidden" data-share-modal>
    <div class="absolute inset-0 bg-black/50" data-share-modal-close></div>

    <div class="relative z-10 mx-auto mt-10 w-full max-w-2xl rounded-lg bg-white p-6 shadow-lg">
        <div class="mb-4 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Share "{{ $link->title }}"</h3>
            <button type="button" class="rounded p-1 text-gray-500 hover:bg-gray-100" data-share-modal-close aria-label="Close">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>

        @if($hasErrorsForThisModal)
            <div class="mb-4 rounded border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-6 rounded border p-4">
            <h4 class="mb-3 text-sm font-semibold text-gray-700">Add Share</h4>
            <form method="POST" action="{{ route('links.shares.store', $link) }}" class="grid gap-3 sm:grid-cols-3 sm:items-end" data-share-form>
                @csrf
                <input type="hidden" name="_share_link_id" value="{{ $link->id }}">

                <div class="sm:col-span-2">
                    <label class="mb-1 block text-sm text-gray-700">User</label>
                    <select name="user_id" class="w-full rounded border px-3 py-2 text-sm" required>
                        <option value="">Select user...</option>
                        @foreach($shareableUsers as $userOption)
                            <option value="{{ $userOption->id }}" @selected((string) old('user_id') === (string) $userOption->id)>
                                {{ $userOption->name }} ({{ $userOption->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-sm text-gray-700">Permission</label>
                    <select name="permission" class="w-full rounded border px-3 py-2 text-sm" required>
                        <option value="read" @selected(old('permission', 'read') === 'read')>read</option>
                        <option value="edit" @selected(old('permission') === 'edit')>edit</option>
                    </select>
                </div>

                <div class="sm:col-span-3">
                    <button class="rounded bg-indigo-600 px-4 py-2 text-sm text-white hover:bg-indigo-700">
                        Share
                    </button>
                </div>
            </form>
        </div>

        <div>
            <h4 class="mb-3 text-sm font-semibold text-gray-700">Current Shared Users</h4>

            @if($link->sharedUsers->isEmpty())
                <p class="text-sm text-gray-500">No users shared yet.</p>
            @else
                <div class="space-y-2">
                    @foreach($link->sharedUsers as $sharedUser)
                        <div class="flex flex-wrap items-center justify-between gap-3 rounded border p-3">
                            <div class="min-w-0">
                                <p class="truncate text-sm font-medium text-gray-900">{{ $sharedUser->name }}</p>
                                <p class="truncate text-xs text-gray-500">{{ $sharedUser->email }}</p>
                            </div>

                            <div class="flex items-center gap-2">
                                <form method="POST" action="{{ route('links.shares.update', [$link, $sharedUser]) }}" class="flex items-center gap-2" data-share-form>
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="_share_link_id" value="{{ $link->id }}">
                                    <select name="permission" class="rounded border px-2 py-1 text-sm">
                                        <option value="read" @selected($sharedUser->pivot->permission === 'read')>read</option>
                                        <option value="edit" @selected($sharedUser->pivot->permission === 'edit')>edit</option>
                                    </select>
                                    <button class="rounded bg-gray-100 px-2 py-1 text-sm text-gray-700 hover:bg-gray-200">Update</button>
                                </form>

                                <form method="POST" action="{{ route('links.shares.destroy', [$link, $sharedUser]) }}" data-share-form>
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="_share_link_id" value="{{ $link->id }}">
                                    <button class="rounded bg-red-50 px-2 py-1 text-sm text-red-600 hover:bg-red-100">Revoke</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
