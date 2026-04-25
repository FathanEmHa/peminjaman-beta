<div>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2.5 bg-blue-600 rounded-xl shadow-lg shadow-blue-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    Ajukan Peminjaman
                </h2>
                <p class="text-sm font-medium text-gray-500 mt-0.5">Pilih alat dari etalase, masukkan keranjang, dan tentukan jadwal</p>
            </div>
        </div>
    </x-slot>

    <div class="py-10 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        {{-- Pesan Error / Success (Global) --}}
        @if (session()->has('error'))
            <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl shadow-sm mb-6">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span class="font-medium text-sm">{{ session('error') }}</span>
            </div>
        @endif
        @if (session()->has('success_cart'))
            <div class="flex items-center gap-3 bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-xl shadow-sm mb-6" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                <span class="font-medium text-sm">{{ session('success_cart') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            {{-- ============================================== --}}
            {{-- PANEL KIRI: ETALASE ALAT (SHOPPING STYLE)      --}}
            {{-- ============================================== --}}
            <div class="lg:col-span-8 space-y-6">
                
                {{-- Search Bar Etalase --}}
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex gap-3 items-center">
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input wire:model.live.debounce.300ms="search" type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-colors" placeholder="Cari alat di etalase...">
                    </div>
                </div>

                {{-- Grid Produk --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    @forelse($assets as $asset)
                        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden flex flex-col group hover:shadow-md transition-shadow">
                            {{-- Foto Produk (Clickable untuk Zoom) --}}
                            <div class="h-32 bg-gray-100 relative flex items-center justify-center overflow-hidden">
                                @if($asset->photo)
                                    <img src="{{ asset('storage/' . $asset->photo) }}" 
                                         wire:click="viewImage('{{ asset('storage/' . $asset->photo) }}', '{{ $asset->name }}')" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 cursor-pointer" 
                                         title="Klik untuk perbesar">
                                @else
                                    <svg class="w-8 h-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                @endif

                                {{-- Badge Stok --}}
                                <div class="absolute top-2 right-2 pointer-events-none">
                                    @if($asset->realtime_stock > 0)
                                        {{-- Diubah ke biru --}}
                                        <span class="bg-blue-500 text-white text-[10px] font-bold px-2 py-0.5 rounded shadow-sm">Sisa: {{ $asset->realtime_stock }}</span>
                                    @else
                                        <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded shadow-sm">Habis di Keranjang</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Info Produk & Form Add --}}
                            <div class="p-4 flex-1 flex flex-col">
                                <h4 class="font-bold text-gray-800 text-sm leading-tight mb-1 line-clamp-2">{{ $asset->name }}</h4>
                                <p class="text-xs text-gray-500 mb-4">{{ $asset->category->name ?? 'Tanpa Kategori' }}</p>

                                <div class="mt-auto flex items-center gap-2">
                                    <input type="number" wire:model="quantities.{{ $asset->id }}" min="1" max="{{ $asset->realtime_stock }}" class="w-16 border border-gray-300 rounded-lg px-2 py-1.5 text-sm text-center focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" placeholder="1" {{ $asset->realtime_stock <= 0 ? 'disabled' : '' }}>
                                    
                                    <button wire:click="addToCart({{ $asset->id }})" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold py-2 px-2 rounded-lg flex items-center justify-center gap-1 transition-colors disabled:bg-gray-300 disabled:cursor-not-allowed" {{ $asset->realtime_stock <= 0 ? 'disabled' : '' }}>
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                                        Keranjang
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-1 sm:col-span-2 md:col-span-3 bg-white p-8 rounded-xl border border-gray-100 text-center">
                            <p class="text-gray-500">Alat tidak ditemukan. Coba kata kunci lain.</p>
                        </div>
                    @endforelse
                </div>
                @if(method_exists($assets, 'links') && $assets->hasPages())
                    <div class="pt-2 flex justify-center">
                        {{ $assets->links() }}
                    </div>
                @endif
            </div>

            {{-- ============================================== --}}
            {{-- PANEL KANAN: KERANJANG (STICKY)                --}}
            {{-- ============================================== --}}
            <div class="lg:col-span-4 lg:sticky lg:top-24">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col">
                    
                    {{-- Header Keranjang --}}
                    <div class="p-5 border-b border-gray-100 bg-gray-50/50 rounded-t-xl flex justify-between items-center">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                            Keranjang Anda
                        </h3>
                        <span class="bg-blue-100 text-blue-700 py-0.5 px-2.5 rounded-full text-xs font-bold">{{ count($cart) }}</span>
                    </div>

                    {{-- Isi Keranjang --}}
                    <div class="p-5 flex-1 max-h-[300px] overflow-y-auto">
                        @if(count($cart) > 0)
                            <ul class="space-y-3">
                                @foreach($cart as $index => $item)
                                    <li class="flex justify-between items-center bg-gray-50 p-3 rounded-lg border border-gray-100">
                                        <div class="flex-1 truncate mr-3">
                                            <p class="text-sm font-bold text-gray-800 truncate">{{ $item['name'] }}</p>
                                            <p class="text-xs text-gray-500 mt-0.5">Qty: <span class="font-bold text-blue-600">{{ $item['quantity'] }} unit</span></p>
                                        </div>
                                        <button wire:click="removeFromCart({{ $index }})" class="p-2 bg-white border border-rose-200 text-rose-500 hover:bg-rose-50 rounded-lg transition-colors shrink-0" title="Hapus dari keranjang">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="text-center py-6">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                                </div>
                                <p class="text-sm text-gray-400 font-medium">Keranjang masih kosong.<br>Pilih alat di etalase.</p>
                            </div>
                        @endif
                    </div>

                    {{-- Form Checkout & Tombol --}}
                    <div class="p-5 border-t border-gray-100 bg-gray-50/50 rounded-b-xl space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1">Tgl Pinjam <span class="text-red-500">*</span></label>
                            <input type="datetime-local" wire:model="loan_date" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                            @error('loan_date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1">Tgl Kembali <span class="text-red-500">*</span></label>
                            <input type="datetime-local" wire:model="return_date" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                            @error('return_date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        
                        {{-- Panggil Komponen Custom Modal Konfirmasi --}}
                        <x-confirm-modal 
                            action="store" 
                            title="Kirim Pengajuan Peminjaman?" 
                            message="Pastikan alat dan jadwal peminjaman sudah benar. Pengajuan akan diteruskan ke petugas untuk disetujui." 
                            confirm-text="Ya, Checkout" 
                            cancel-text="Batal" 
                            confirm-color="blue"
                        >
                            <x-slot name="trigger">
                                {{-- Tombol Asli (Trigger). Hapus atribut wire: biar ga dobel eksekusi --}}
                                <button type="button" 
                                        class="w-full mt-2 flex justify-center items-center px-4 py-3 bg-blue-600 text-white text-sm font-bold rounded-lg hover:bg-blue-700 transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed disabled:pointer-events-none" 
                                        {{ count($cart) === 0 ? 'disabled' : '' }}>
                                    Checkout Peminjaman
                                </button>
                            </x-slot>
                        </x-confirm-modal>
                    </div>

                </div>
            </div>

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
</div>