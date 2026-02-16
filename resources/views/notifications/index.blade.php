<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Notifications</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('notifications.read-all') }}" class="mb-3">
                @csrf
                @method('PATCH')
                <button class="px-3 py-2 bg-indigo-600 text-white rounded">Mark all as read</button>
            </form>

            <div class="bg-white shadow rounded">
                <table class="w-full border">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-2 border text-left">Type</th>
                            <th class="p-2 border text-left">Data</th>
                            <th class="p-2 border text-left">Read At</th>
                            <th class="p-2 border text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($notifications as $notification)
                            <tr>
                                <td class="p-2 border">{{ $notification->type }}</td>
                                <td class="p-2 border">{{ json_encode($notification->data) }}</td>
                                <td class="p-2 border">{{ $notification->read_at ?? 'Unread' }}</td>
                                <td class="p-2 border">
                                    @if(!$notification->read_at)
                                        <form method="POST" action="{{ route('notifications.read', $notification) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button class="text-blue-600">Mark read</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-4 text-center text-gray-500">No notifications.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $notifications->links() }}</div>
        </div>
    </div>
</x-app-layout>
