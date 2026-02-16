<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Activity Logs</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded p-4">
                <table class="w-full border">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-2 border text-left">Date</th>
                            <th class="p-2 border text-left">Actor</th>
                            <th class="p-2 border text-left">Action</th>
                            <th class="p-2 border text-left">Subject</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td class="p-2 border">{{ $log->created_at }}</td>
                                <td class="p-2 border">{{ optional($log->actor)->name ?? 'System' }}</td>
                                <td class="p-2 border">{{ $log->action }}</td>
                                <td class="p-2 border">{{ class_basename((string) $log->subject_type) }} #{{ $log->subject_id }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-4 text-center text-gray-500">No activity yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $logs->links() }}</div>
        </div>
    </div>
</x-app-layout>
