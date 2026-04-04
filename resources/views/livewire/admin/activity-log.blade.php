<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-black leading-tight">Log Aktivitas Sistem</h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-300 overflow-x-auto">
            <table class="w-full text-black">
                <thead>
                    <tr class="border-b border-gray-400 text-left bg-gray-100">
                        <th class="px-4 py-3 font-bold text-gray-900 w-1/4">Waktu</th>
                        <th class="px-4 py-3 font-bold text-gray-900 w-1/4">Pengguna</th>
                        <th class="px-4 py-3 font-bold text-gray-900 w-1/2">Aktivitas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr class="border-b border-gray-200 hover:bg-gray-50 text-sm">
                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($log->created_at)->format('d-M-Y H:i:s') }}</td>
                        <td class="px-4 py-3 font-medium">{{ $log->user_name }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $log->action }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-4 py-6 text-center text-gray-500 italic">Belum ada log aktivitas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>