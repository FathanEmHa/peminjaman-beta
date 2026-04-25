<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            {{-- Logo / Ikon Dashboard Admin --}}
            <div class="p-2.5 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl shadow-lg shadow-indigo-200">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>

            {{-- Teks Header --}}
            <div>
                <h2 class="font-extrabold text-2xl text-transparent bg-clip-text bg-gradient-to-r from-gray-900 to-gray-600 tracking-tight">
                    {{ __('Dashboard Administrator') }}
                </h2>
                <p class="text-sm font-medium text-gray-500 mt-0.5">Pusat Pengaturan & Monitoring Sistem Manajemen Aset</p>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gray-50/50 py-10">
        <div class="max-w-7xl mx-auto space-y-8">

            {{-- 1. HERO CONTENT --}}
            <div class="px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <h2 class="text-3xl sm:text-4xl font-black tracking-tight text-gray-900 mb-2">
                        Halo, {{ Auth::user()->name }}! 🚀
                    </h2>
                    <p class="text-gray-500 text-sm sm:text-base max-w-2xl leading-relaxed">
                        Selamat datang di panel kontrol utama. Dari sini Anda bisa memantau kesehatan inventaris, mengelola akses pengguna, dan melacak seluruh aktivitas peminjaman.
                    </p>
                </div>
                <div class="shrink-0 flex flex-col sm:flex-row gap-3 w-full md:w-auto mt-4 md:mt-0">
                    <a href="{{ route('admin.logs') }}" wire:navigate.hover class="w-full sm:w-auto text-center px-7 py-3 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-bold rounded-xl transition-colors shadow-sm flex justify-center items-center gap-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        Cek Log Sistem
                    </a>
                    <a href="{{ route('admin.assets') }}" wire:navigate.hover class="w-full sm:w-auto text-center px-7 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition-all shadow-md shadow-indigo-600/20 flex justify-center items-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        Kelola Inventaris Alat
                    </a>
                </div>
            </div>

            {{-- 2. QUICK STATS (Ringkasan) --}}
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                    
                    {{-- Stat 1: Total Users --}}
                    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4 hover:border-purple-200 transition-colors group">
                        <div class="p-3 bg-purple-50 text-purple-600 rounded-xl group-hover:bg-purple-600 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Pengguna</p>
                            <h4 class="text-2xl font-black text-gray-900">{{ $stats['total_users'] ?? 0 }}</h4>
                        </div>
                    </div>

                    {{-- Stat 2: Total Assets --}}
                    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4 hover:border-blue-200 transition-colors group">
                        <div class="p-3 bg-blue-50 text-blue-600 rounded-xl group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Aset Fisik</p>
                            <h4 class="text-2xl font-black text-gray-900">{{ $stats['total_assets'] ?? 0 }}</h4>
                        </div>
                    </div>

                    {{-- Stat 3: Active Loans --}}
                    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4 hover:border-indigo-200 transition-colors group">
                        <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Peminjaman Aktif</p>
                            <h4 class="text-2xl font-black text-gray-900">{{ $stats['active_loans'] ?? 0 }}</h4>
                        </div>
                    </div>

                    {{-- Stat 4: Total Fines --}}
                    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4 hover:border-rose-200 transition-colors group">
                        <div class="p-3 bg-rose-50 text-rose-600 rounded-xl group-hover:bg-rose-600 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Denda Belum Dibayar</p>
                            <h4 class="text-xl font-black text-rose-600">Rp {{ number_format($stats['unpaid_fines'] ?? 0, 0, ',', '.') }}</h4>
                        </div>
                    </div>

                </div>
            </div>

            {{-- 3. SHORTCUT MENU (Menu Utama Admin) --}}
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    
                    {{-- Shortcut 1 --}}
                    <a href="{{ route('admin.categories') }}" wire:navigate class="group bg-white p-4 rounded-xl border border-gray-100 shadow-sm hover:shadow-md hover:border-indigo-200 flex items-center gap-4 transition-all">
                        <div class="p-2.5 bg-gray-50 text-gray-500 rounded-lg group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-800 group-hover:text-indigo-700">Kategori Alat</h4>
                            <p class="text-[10px] text-gray-500">Kelola master data</p>
                        </div>
                    </a>

                    {{-- Shortcut 2 --}}
                    <a href="{{ route('admin.users') }}" wire:navigate class="group bg-white p-4 rounded-xl border border-gray-100 shadow-sm hover:shadow-md hover:border-purple-200 flex items-center gap-4 transition-all">
                        <div class="p-2.5 bg-gray-50 text-gray-500 rounded-lg group-hover:bg-purple-50 group-hover:text-purple-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-800 group-hover:text-purple-700">Manajemen User</h4>
                            <p class="text-[10px] text-gray-500">Akses Peminjam & Petugas</p>
                        </div>
                    </a>

                    {{-- Shortcut 3 --}}
                    <a href="{{ route('admin.loans') }}" wire:navigate class="group bg-white p-4 rounded-xl border border-gray-100 shadow-sm hover:shadow-md hover:border-amber-200 flex items-center gap-4 transition-all">
                        <div class="p-2.5 bg-gray-50 text-gray-500 rounded-lg group-hover:bg-amber-50 group-hover:text-amber-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-800 group-hover:text-amber-700">Data Transaksi</h4>
                            <p class="text-[10px] text-gray-500">Histori Pinjam & Kembali</p>
                        </div>
                    </a>

                    {{-- Shortcut 4 --}}
                    <a href="{{ route('admin.logs') }}" wire:navigate class="group bg-white p-4 rounded-xl border border-gray-100 shadow-sm hover:shadow-md hover:border-gray-300 flex items-center gap-4 transition-all">
                        <div class="p-2.5 bg-gray-50 text-gray-500 rounded-lg group-hover:bg-gray-200 group-hover:text-gray-800 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-800 group-hover:text-gray-900">Log Aktivitas</h4>
                            <p class="text-[10px] text-gray-500">Jejak rekaman sistem</p>
                        </div>
                    </a>

                </div>
            </div>

            {{-- 4. RECENT ACTIVITY LOGS (Tabel Mini) --}}
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-end mb-4 px-1">
                    <h3 class="text-lg font-bold text-gray-800">Aktivitas Sistem Terakhir</h3>
                    <a href="{{ route('admin.logs') }}" wire:navigate.hover class="text-sm font-bold text-indigo-600 hover:text-indigo-800 transition flex items-center gap-1 group">
                        Lihat Semua Log
                        <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </div>
                
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left whitespace-nowrap text-sm">
                            <tbody class="divide-y divide-gray-100">
                                @forelse($recentLogs ?? [] as $log)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-3 w-12">
                                            <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs">
                                                {{ substr($log->user->name ?? 'S', 0, 1) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-3">
                                            <div class="font-medium text-gray-900">{{ $log->user->name ?? 'System' }}</div>
                                            <div class="text-xs text-gray-500">{{ $log->action }}</div>
                                        </td>
                                        <td class="px-6 py-3 text-right text-xs text-gray-400">
                                            {{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-8 text-center text-gray-500 text-sm">Belum ada aktivitas yang terekam.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>