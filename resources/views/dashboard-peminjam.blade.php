<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            {{-- Logo / Ikon Dashboard --}}
            <div class="p-2.5 bg-gradient-to-br from-indigo-600 to-blue-600 rounded-xl shadow-lg shadow-indigo-200">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 5a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h4a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h4a1 1 0 011 1v6a1 1 0 01-1 1h-4a1 1 0 01-1-1v-6zM14 5a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1h-4a1 1 0 01-1-1V5z">
                    </path>
                </svg>
            </div>

            {{-- Teks Header --}}
            <div>
                <h2
                    class="font-extrabold text-2xl text-transparent bg-clip-text bg-gradient-to-r from-gray-900 to-gray-600 tracking-tight">
                    {{ __('Dashboard Peminjam') }}
                </h2>
                <p class="text-sm font-medium text-gray-500 mt-0.5">Pusat Kendali Sistem Manajemen Aset</p>
            </div>
        </div>
    </x-slot>
    
    <div class="min-h-screen bg-gray-50/50 py-10 mt-4">
        <div class="max-w-7xl mx-auto space-y-12">
            
            {{-- 1. HERO CONTENT (Normal Background) --}}
            <div class="px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <h2 class="text-3xl sm:text-4xl font-black tracking-tight text-gray-900 mb-2">
                        Halo, {{ Auth::user()->name }}! 👋
                    </h2>
                    <p class="text-gray-500 text-sm sm:text-base max-w-xl leading-relaxed">
                        Siap mengeksekusi project hari ini? Eksplorasi alat yang kamu butuhkan atau pantau status pengajuanmu dengan mudah lewat panel ini.
                    </p>
                </div>
                <div class="shrink-0 flex flex-col sm:flex-row gap-3 w-full md:w-auto mt-4 md:mt-0">
                    <a href="{{ route('peminjam.katalog') }}" wire:navigate.hover class="w-full sm:w-auto text-center px-7 py-3 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-bold rounded-xl transition-colors shadow-sm flex justify-center items-center gap-2">
                        Eksplor Katalog
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                    </a>
                    <a href="{{ route('peminjam.loans.create') }}" wire:navigate.hover class="w-full sm:w-auto text-center px-7 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition-all shadow-md shadow-indigo-600/20 flex justify-center items-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Ajukan Pinjaman
                    </a>
                </div>
            </div>

            {{-- 2. MINI KATALOG (Quick Access) --}}
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-end mb-4 px-1">
                    <h3 class="text-lg font-bold text-gray-800">Rekomendasi Alat</h3>
                    <a href="{{ route('peminjam.katalog') }}" wire:navigate.hover class="text-sm font-bold text-indigo-600 hover:text-indigo-800 transition flex items-center gap-1 group">
                        Lihat Semua Alat
                        <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
                    @forelse($recentAssets as $asset)
                        <div class="bg-white rounded-2xl p-4 border border-gray-200 shadow-sm hover:shadow-md hover:border-indigo-300 transition-all group flex flex-col">
                            <div class="aspect-square bg-gray-50 rounded-xl mb-3 flex items-center justify-center overflow-hidden">
                                @if($asset->photo)
                                    <img src="{{ asset('storage/' . $asset->photo) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <svg class="w-10 h-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                @endif
                            </div>
                            <h4 class="font-bold text-gray-800 text-sm line-clamp-1">{{ $asset->name }}</h4>
                            <p class="text-xs text-gray-500 mt-1 mb-3">Tersedia: <span class="font-bold text-emerald-600">{{ $asset->stock }} unit</span></p>
                            <a href="{{ route('peminjam.loans.create') }}?asset={{ $asset->id }}" class="mt-auto block w-full text-center py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold rounded-lg transition-colors">
                                Pinjam
                            </a>
                        </div>
                    @empty
                        <div class="col-span-2 md:col-span-4 bg-white rounded-2xl p-8 border border-gray-200 text-center text-gray-500">
                            Belum ada alat yang tersedia saat ini.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- 3. MINI LOAN HISTORY --}}
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-end mb-4 px-1">
                    <h3 class="text-lg font-bold text-gray-800">Aktivitas Peminjaman Terakhir</h3>
                    <a href="{{ route('peminjam.loans.history') }}" wire:navigate.hover class="text-sm font-bold text-indigo-600 hover:text-indigo-800 transition flex items-center gap-1 group">
                        Lihat Semua Riwayat
                        <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </div>
                
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left whitespace-nowrap text-sm">
                            <thead class="bg-gray-50 border-b border-gray-100 text-gray-500 uppercase tracking-wider text-xs font-semibold">
                                <tr>
                                    <th class="px-6 py-4 w-16">ID</th>
                                    <th class="px-6 py-4">Alat (Qty)</th>
                                    <th class="px-6 py-4">Timeline</th>
                                    <th class="px-6 py-4 text-center">Status</th>
                                    <th class="px-6 py-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($recentLoans as $loan)
                                    <tr class="hover:bg-indigo-50/50 transition-colors group">
                                        <td class="px-6 py-4 font-medium text-indigo-600">#{{ $loan->id }}</td>
                                        <td class="px-6 py-4 whitespace-normal min-w-[200px]">
                                    <ul class="space-y-1 text-gray-700">
                                        {{-- Ambil item pertama saja dari relasi --}}
                                        @if($loan->items->count() > 0)
                                            @php $firstItem = $loan->items->first(); @endphp
                                            <li class="flex items-center gap-2">
                                                <span class="h-1.5 w-1.5 rounded-full bg-indigo-300"></span>
                                                {{ $firstItem->asset->name }} 
                                                <span class="text-xs font-bold text-gray-500 ml-1">x{{ $firstItem->quantity }}</span>
                                            </li>
                                        @endif
                                    </ul>
                                    
                                    {{-- Tampilkan sisa jumlah alat jika ada lebih dari 1 --}}
                                    @if($loan->items->count() > 1)
                                        <a href="{{ route('peminjam.loans.detail', $loan->id) }}" wire:navigate class="inline-block mt-2 text-xs font-medium text-indigo-600 hover:text-indigo-800 transition-colors">
                                            + {{ $loan->items->count() - 1 }} alat lainnya...
                                        </a>
                                    @endif
                                </td>
                                        <td class="px-6 py-4 text-gray-600">
                                            <div class="flex items-center gap-2 mb-1 text-xs">
                                                <span class="text-gray-400 w-12">Pinjam:</span>
                                                <span class="font-medium">{{ \Carbon\Carbon::parse($loan->loan_date)->format('d M Y') }}</span>
                                            </div>
                                            <div class="flex items-center gap-2 text-xs">
                                                <span class="text-gray-400 w-12">Kembali:</span>
                                                <span class="font-medium">{{ \Carbon\Carbon::parse($loan->return_date)->format('d M Y') }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @php
                                                $badges = [
                                                    'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                                    'approved' => 'bg-blue-100 text-blue-700 border-blue-200',
                                                    'ongoing' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                                                    'returned' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                                    'rejected' => 'bg-red-100 text-red-700 border-red-200',
                                                    'overdue' => 'bg-rose-100 text-rose-700 border-rose-200',
                                                ];
                                                $badgeClass = $badges[$loan->status] ?? 'bg-gray-100 text-gray-700';
                                                $labelText = $loan->status === 'overdue' ? 'OVERDUE' : $loan->status;
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $badgeClass }} uppercase tracking-wider">
                                                {{ $labelText }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <a href="{{ route('peminjam.loans.detail', $loan->id) }}" wire:navigate.hover class="inline-flex items-center justify-center px-3 py-1.5 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 hover:text-indigo-600 rounded-lg text-xs font-bold transition-all shadow-sm">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">Belum ada riwayat peminjaman.</td>
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