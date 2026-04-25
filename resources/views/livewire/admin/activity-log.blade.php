<div>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            {{-- Ikon Log Aktivitas --}}
            <div class="p-2.5 bg-blue-600 rounded-xl shadow-lg shadow-blue-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            
            {{-- Teks Header --}}
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    Log Aktivitas Sistem
                </h2>
                <p class="text-sm font-medium text-gray-500 mt-0.5">Rekam jejak seluruh perubahan dan akses pengguna</p>
            </div>
        </div>
    </x-slot>

    <div class="py-10 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-4 w-1/4">Waktu</th>
                            <th class="px-6 py-4 w-1/4">Pengguna</th>
                            <th class="px-6 py-4 w-1/2">Aktivitas</th>
                        </tr>
                    </thead>
                    
                    <tbody class="divide-y divide-gray-100">
                        @forelse($logs as $log)
                        <tr class="hover:bg-indigo-50/50 transition-colors duration-200 ease-in-out group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 group-hover:text-indigo-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="font-medium text-gray-700">{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y') }}</span>
                                    <span class="text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded-md">{{ \Carbon\Carbon::parse($log->created_at)->format('H:i:s') }}</span>
                                </div>
                            </td>
                            
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xs mr-3 shadow-sm">
                                        {{ strtoupper(substr($log->user_name, 0, 1)) }}
                                    </div>
                                    <span class="font-medium text-sm text-gray-900">{{ $log->user_name }}</span>
                                </div>
                            </td>
                            
                            <td class="px-6 py-4 text-sm text-gray-600 whitespace-normal leading-relaxed">
                                {{ $log->action }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="p-4 bg-gray-50 rounded-full mb-3">
                                        <svg class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-gray-900 font-medium text-lg">Belum ada aktivitas</h3>
                                    <p class="text-gray-500 text-sm mt-1">Log sistem akan muncul di sini setelah ada pengguna yang beraktivitas.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($logs->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $logs->links() }}
                </div>
            @endif
            
        </div>
    </div>
</div>