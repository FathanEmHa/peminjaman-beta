<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            {{-- Logo / Ikon Dashboard Petugas --}}
            <div class="p-2.5 bg-gradient-to-br from-indigo-600 to-blue-600 rounded-xl shadow-lg shadow-indigo-200">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
            </div>

            {{-- Teks Header --}}
            <div>
                <h2 class="font-extrabold text-2xl text-transparent bg-clip-text bg-gradient-to-r from-gray-900 to-gray-600 tracking-tight">
                    {{ __('Dashboard Petugas') }}
                </h2>
                <p class="text-sm font-medium text-gray-500 mt-0.5">Pusat Kendali Operasional Peminjaman Alat</p>
            </div>
        </div>
    </x-slot>
    
    <div class="min-h-screen bg-gray-50/50 py-10 mt-4">
        <div class="max-w-7xl mx-auto space-y-12">
            
            {{-- 1. HERO CONTENT --}}
            <div class="px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <h2 class="text-3xl sm:text-4xl font-black tracking-tight text-gray-900 mb-2">
                        Halo, {{ Auth::user()->name }}! 👋
                    </h2>
                    <p class="text-gray-500 text-sm sm:text-base max-w-xl leading-relaxed">
                        Pantau terus arus inventaris lab hari ini. Segera tindak lanjuti pengajuan peminjaman yang masuk dan pastikan semua alat kembali tepat waktu.
                    </p>
                </div>
                <div class="shrink-0 flex flex-col sm:flex-row gap-3 w-full md:w-auto mt-4 md:mt-0">
                    {{-- Tombol 1: Kelola Pengembalian (Gaya Outline Putih) --}}
                    <a href="{{ route('petugas.returns') }}" wire:navigate.hover class="w-full sm:w-auto text-center px-7 py-3 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-bold rounded-xl transition-colors shadow-sm flex justify-center items-center gap-2">
                        Kelola Pengembalian
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" /></svg>
                    </a>
                    
                    {{-- Tombol 2: Kelola Peminjaman (Gaya Solid Indigo) --}}
                    <a href="{{ route('petugas.approval') }}" wire:navigate.hover class="w-full sm:w-auto text-center px-7 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition-all shadow-md shadow-indigo-600/20 flex justify-center items-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                        Kelola Peminjaman
                    </a>
                </div>
            </div>

            {{-- 2. QUICK STATS --}}
            <div class="px-4 sm:px-6 lg:px-8">
                <h3 class="text-lg font-bold text-gray-800 mb-4 px-1">Ringkasan Operasional</h3>
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                    
                    {{-- Stat 1: Pending --}}
                    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4 hover:border-yellow-200 transition-colors">
                        <div class="p-3 bg-yellow-50 text-yellow-600 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Menunggu ACC</p>
                            <h4 class="text-2xl font-black text-gray-900">{{ $stats['pending'] ?? 0 }}</h4>
                        </div>
                    </div>

                    {{-- Stat 2: Ongoing --}}
                    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4 hover:border-indigo-200 transition-colors">
                        <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Sedang Dipinjam</p>
                            <h4 class="text-2xl font-black text-gray-900">{{ $stats['ongoing'] ?? 0 }}</h4>
                        </div>
                    </div>

                    {{-- Stat 3: Overdue --}}
                    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4 hover:border-rose-200 transition-colors">
                        <div class="p-3 bg-rose-50 text-rose-600 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Telat Kembali</p>
                            <h4 class="text-2xl font-black text-rose-600">{{ $stats['overdue'] ?? 0 }}</h4>
                        </div>
                    </div>

                    {{-- Stat 4: Asset Stok --}}
                    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4 hover:border-blue-200 transition-colors">
                        <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Alat Aktif</p>
                            <h4 class="text-2xl font-black text-gray-900">{{ $stats['total_assets'] ?? 0 }}</h4>
                        </div>
                    </div>

                </div>
            </div>

            {{-- 3. MINI TABLE --}}
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-end mb-4 px-1">
                    <h3 class="text-lg font-bold text-gray-800">Transaksi Perlu Tindakan</h3>
                    {{-- Link ubah jadi Indigo --}}
                    <a href="{{ route('petugas.approval') }}" wire:navigate.hover class="text-sm font-bold text-indigo-600 hover:text-indigo-800 transition flex items-center gap-1 group">
                        Buka Manajemen
                        <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </div>
                
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left whitespace-nowrap text-sm">
                            <thead class="bg-gray-50 border-b border-gray-100 text-gray-500 uppercase tracking-wider text-xs font-semibold">
                                <tr>
                                    <th class="px-6 py-4 w-16">ID</th>
                                    <th class="px-6 py-4">Peminjam</th>
                                    <th class="px-6 py-4">Alat (Qty)</th>
                                    <th class="px-6 py-4 text-center">Status</th>
                                    <th class="px-6 py-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($recentLoans as $loan)
                                    <tr class="hover:bg-gray-50/50 transition-colors group">
                                        <td class="px-6 py-4 font-medium text-gray-900">#{{ $loan->id }}</td>
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-gray-800">{{ $loan->user->name ?? 'User' }}</div>
                                            <div class="text-xs text-gray-500 mt-0.5">{{ \Carbon\Carbon::parse($loan->loan_date)->format('d M, H:i') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-normal min-w-[200px]">
                                            <ul class="space-y-1 text-gray-700">
                                                @if($loan->items->count() > 0)
                                                    @php $firstItem = $loan->items->first(); @endphp
                                                    <li class="flex items-center gap-2">
                                                        <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                                                        {{ $firstItem->asset->name }} 
                                                        <span class="text-xs font-bold text-gray-500 ml-1">x{{ $firstItem->quantity }}</span>
                                                    </li>
                                                @endif
                                            </ul>
                                            @if($loan->items->count() > 1)
                                                <span class="inline-block mt-1 text-[10px] font-medium text-gray-400">
                                                    + {{ $loan->items->count() - 1 }} alat lainnya
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @php
                                                $badges = [
                                                    'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                                    'approved' => 'bg-blue-100 text-blue-700 border-blue-200',
                                                    'ongoing' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                                                    'overdue' => 'bg-rose-100 text-rose-700 border-rose-200',
                                                    // Jika ada status returned, ubah ke biru juga
                                                    'returned' => 'bg-blue-100 text-blue-700 border-blue-200', 
                                                ];
                                                $badgeClass = $badges[$loan->status] ?? 'bg-gray-100 text-gray-700';
                                                $labelText = $loan->status === 'overdue' ? 'OVERDUE' : $loan->status;
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $badgeClass }} uppercase tracking-wider">
                                                {{ $labelText }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            {{-- Hover text ubah jadi Indigo --}}
                                            <a href="{{ route('petugas.loans.detail', $loan->id) }}" wire:navigate class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 hover:text-indigo-600 rounded-lg text-xs font-bold transition-all shadow-sm">
                                                Proses
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                            Tidak ada transaksi yang perlu tindakan saat ini. Santai dulu! ☕
                                        </td>
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