<div>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            {{-- Ikon Katalog --}}
            <div class="p-2.5 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl shadow-lg shadow-emerald-200">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>

            {{-- Teks Header --}}
            <div>
                <h2 class="font-extrabold text-2xl text-transparent bg-clip-text bg-gradient-to-r from-gray-900 to-gray-600 tracking-tight">
                    {{ __('Pantau Stok Alat') }}
                </h2>
                <p class="text-sm font-medium text-gray-500 mt-0.5">Lihat ketersediaan fisik aset di inventaris</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Section Search & Filter --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-8 flex flex-col md:flex-row gap-4 justify-between items-center">
                <div class="w-full md:w-1/2 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-xl leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition duration-150 ease-in-out sm:text-sm" placeholder="Cari nama alat yang ingin dicek...">
                </div>

                <div class="w-full md:w-1/3">
                    <select wire:model.live="category_id" class="block w-full py-2 px-3 border border-gray-200 bg-gray-50 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm transition duration-150 ease-in-out">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Grid Katalog Card --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($assets as $asset)
                    <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 border border-gray-100 flex flex-col overflow-hidden">
                        
                        {{-- Bagian Atas Card (Info) --}}
                        <div class="p-6 flex-grow">
                            <div class="flex justify-between items-start mb-4">
                                <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $asset->category->name }}
                                </div>
                                
                                @if($asset->stock > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                        Tersedia
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700">
                                        Kosong
                                    </span>
                                @endif
                            </div>

                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $asset->name }}</h3>
                            <p class="text-sm text-gray-500 line-clamp-2">Pastikan alat ini dalam kondisi baik sebelum diserahkan kepada peminjam.</p>
                        </div>

                        {{-- Bagian Bawah Card (Action Diganti Jadi Info Stok) --}}
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 mt-auto">
                            @if($asset->stock > 0)
                                <div class="w-full flex justify-between items-center px-4 py-2 border border-emerald-200 rounded-xl shadow-sm text-sm font-bold text-emerald-700 bg-emerald-50">
                                    <span>Sisa Fisik Barang:</span>
                                    <span class="text-lg">{{ $asset->stock }} Unit</span>
                                </div>
                            @else
                                <div class="w-full flex justify-between items-center px-4 py-2 border border-red-200 rounded-xl shadow-sm text-sm font-bold text-red-700 bg-red-50">
                                    <span>Sisa Fisik Barang:</span>
                                    <span class="text-lg">0 Unit</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-1 sm:col-span-2 lg:col-span-3 bg-white p-10 rounded-2xl shadow-sm border border-gray-100 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Alat tidak ditemukan</h3>
                        <p class="mt-1 text-sm text-gray-500">Coba ubah kata kunci pencarian atau filter kategori.</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination Links --}}
            <div class="mt-8">
                {{ $assets->links() }}
            </div>

        </div>
    </div>
</div>