<div>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            {{-- Ikon Katalog --}}
            <div class="p-2 bg-emerald-100 rounded-lg">
                <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>

            {{-- Teks Header --}}
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Katalog Alat') }}
                </h2>
                <p class="text-sm font-medium text-gray-500 mt-0.5">Cari dan pinjam aset yang tersedia</p>
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
                <input wire:model.live.debounce.300ms="search" type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition duration-150 ease-in-out sm:text-sm" placeholder="Cari nama alat...">
            </div>

            <div class="w-full sm:w-1/3">
                <select wire:model.live="category_id" class="block w-full py-2 px-3 border border-gray-200 bg-gray-50 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm transition duration-150 ease-in-out">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Table Container --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-800">Daftar Alat Tersedia</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-gray-500 uppercase tracking-wider text-xs font-semibold">
                            <th class="px-6 py-4">Nama Alat</th>
                            <th class="px-6 py-4">Kategori</th>
                            <th class="px-6 py-4 text-center">Status Stok</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($assets as $asset)
                            <tr class="hover:bg-emerald-50/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900">{{ $asset->name }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5 font-mono">ID: #{{ str_pad($asset->id, 4, '0', STR_PAD_LEFT) }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200">
                                        {{ $asset->category->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($asset->stock > 0)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                            Sisa: {{ $asset->stock }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-700 border border-rose-200">
                                            Habis
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if($asset->stock > 0)
                                        <a href="{{ route('peminjam.loans.create', ['asset_id' => $asset->id]) }}" wire:navigate 
                                           class="inline-flex justify-center items-center px-4 py-1.5 bg-emerald-600 text-white hover:bg-emerald-700 rounded-lg text-xs font-bold transition-colors shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                            Pinjam
                                        </a>
                                    @else
                                        <button disabled class="inline-flex justify-center items-center px-4 py-1.5 bg-gray-100 text-gray-400 border border-gray-200 rounded-lg text-xs font-bold cursor-not-allowed">
                                            Tidak Tersedia
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="p-4 bg-gray-50 rounded-full mb-3">
                                            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <p class="text-gray-500 text-sm">Alat tidak ditemukan. Coba ubah kata kunci pencarian atau filter kategori.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Links --}}
            @if(method_exists($assets, 'links'))
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                    {{ $assets->links() }}
                </div>
            @endif

        </div>
    </div>
</div>