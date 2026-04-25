<div>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            {{-- Ikon Katalog (Diubah ke biru) --}}
            <div class="p-2.5 bg-blue-600 rounded-xl shadow-lg shadow-blue-200">
                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>

            {{-- Teks Header --}}
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Pantau Stok Alat') }}
                </h2>
                <p class="text-sm font-medium text-gray-500 mt-0.5">Lihat ketersediaan fisik aset di inventaris</p>
            </div>
        </div>
    </x-slot>

    <div class="py-10 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        {{-- Section Search & Filter --}}
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex flex-col sm:flex-row gap-4 justify-between items-center">
            <div class="w-full sm:w-1/2 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                {{-- Fokus ring diubah ke biru --}}
                <input wire:model.live.debounce.300ms="search" type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out sm:text-sm" placeholder="Cari nama alat yang ingin dicek...">
            </div>

            <div class="w-full sm:w-1/3">
                {{-- Fokus ring diubah ke biru --}}
                <select wire:model.live="category_id" class="block w-full py-2 px-3 border border-gray-200 bg-gray-50 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition duration-150 ease-in-out">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- KATALOG GRID SYSTEM DENGAN LOADING OVERLAY --}}
        <div class="relative min-h-[300px]">
            
            {{-- Indikator Loading Custom --}}
            <x-loading-overlay wire:loading wire:target="search, category_id, gotoPage, previousPage, nextPage" message="Memuat data stok..." />

            {{-- Grid Konten --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @forelse ($assets as $asset)
                    {{-- Hover border diubah ke biru --}}
                    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md hover:border-blue-200 transition-all group flex flex-col relative overflow-hidden">
                        
                        {{-- Badge Status Stok --}}
                        <div class="absolute top-7 right-7 z-10">
                            @if($asset->stock > 0)
                                {{-- Badge Sisa diubah ke biru --}}
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black tracking-wider bg-blue-600 text-white shadow-sm shadow-blue-500/30">
                                    SISA: {{ $asset->stock }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black tracking-wider bg-rose-500 text-white shadow-sm shadow-rose-500/30">
                                    HABIS
                                </span>
                            @endif
                        </div>

                        {{-- Area Foto Alat --}}
                        <div class="aspect-[4/3] bg-gray-50 rounded-xl mb-4 flex items-center justify-center overflow-hidden border border-gray-100 relative">
                            @if($asset->photo)
                                <img src="{{ asset('storage/' . $asset->photo) }}" wire:click="viewImage('{{ asset('storage/' . $asset->photo) }}', '{{ $asset->name }}')" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 cursor-pointer" alt="{{ $asset->name }}">
                            @else
                                <div class="flex flex-col items-center justify-center text-gray-300">
                                    <svg class="w-12 h-12 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    <span class="text-xs font-medium tracking-widest uppercase">No Image</span>
                                </div>
                            @endif
                            
                            <div class="absolute inset-0 bg-gradient-to-b from-black/10 to-transparent pointer-events-none"></div>
                        </div>

                        {{-- Detail Info Alat --}}
                        <div class="flex-1">
                            {{-- Badge kategori diubah ke biru --}}
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-600 mb-2 uppercase tracking-widest">
                                {{ $asset->category->name }}
                            </span>
                            <h4 class="font-bold text-gray-800 text-lg leading-tight mb-1 line-clamp-1" title="{{ $asset->name }}">
                                {{ $asset->name }}
                            </h4>
                            <p class="text-xs text-gray-400 font-mono mb-4">ID: #{{ str_pad($asset->id, 4, '0', STR_PAD_LEFT) }}</p>
                        </div>

                        {{-- Tombol Lihat Foto Full --}}
                        <div class="mt-auto pt-4 border-t border-gray-100 flex justify-end">
                            @if($asset->photo)
                                {{-- Tombol Detail Foto diubah ke biru --}}
                                <button type="button" wire:click="viewImage('{{ asset('storage/' . $asset->photo) }}', '{{ $asset->name }}')" class="w-full flex justify-center items-center px-3 py-2 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors border border-blue-100 font-bold text-sm" title="Lihat Foto Layar Penuh">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" /></svg>
                                    Lihat Detail Foto
                                </button>
                            @else
                                <button type="button" disabled class="w-full flex justify-center items-center px-3 py-2 bg-gray-50 text-gray-300 rounded-lg border border-gray-100 cursor-not-allowed font-bold text-sm">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" /></svg>
                                    Foto Tidak Tersedia
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-1 sm:col-span-2 md:col-span-3 bg-white rounded-2xl p-12 border border-gray-200 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="p-4 bg-gray-50 rounded-full mb-4">
                                <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h4 class="text-lg font-bold text-gray-800 mb-1">Alat Tidak Ditemukan</h4>
                            <p class="text-gray-500 text-sm max-w-md mx-auto">Coba ubah kata kunci pencarian atau bersihkan filter kategori untuk melihat stok alat lainnya.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Pagination Links --}}
        @if(method_exists($assets, 'links') && $assets->hasPages())
            <div class="pt-4 flex justify-center">
                {{ $assets->links() }}
            </div>
        @endif

    </div>

    {{-- LIGHTBOX MODAL (UNTUK ZOOM FOTO) --}}
    @if($showImageModal)
        <div class="fixed inset-0 z-[999] flex items-center justify-center bg-black/90 backdrop-blur-md transition-opacity" wire:click.self="closeImageModal">
            <div class="relative max-w-5xl w-full mx-4 flex flex-col items-center">
                <div class="w-full flex justify-between items-center mb-4 text-white">
                    <h3 class="text-lg font-medium">{{ $activeImageTitle }}</h3>
                    <button wire:click="closeImageModal" class="p-2 bg-gray-800 hover:bg-gray-700 rounded-full transition-colors">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <img src="{{ $activeImageUrl }}" class="max-h-[85vh] w-auto object-contain rounded-lg shadow-2xl border-4 border-white/10">
            </div>
        </div>
    @endif
</div>