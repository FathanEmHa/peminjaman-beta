<div>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            {{-- Ikon Alat --}}
            <div class="p-2.5 bg-blue-600 rounded-xl shadow-lg shadow-blue-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            
            {{-- Teks Header --}}
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    Kelola Alat (Assets)
                </h2>
                <p class="text-sm font-medium text-gray-500 mt-0.5">Inventarisasi dan manajemen data perangkat fisik</p>
            </div>
        </div>
    </x-slot>

    <div class="py-10 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        @if (session()->has('message'))
            <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl shadow-sm"
                role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <span class="font-medium text-sm">{{ session('message') }}</span>
            </div>
        @endif

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <div class="p-1.5 bg-indigo-50 rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                </div>
                {{ $isEdit ? 'Edit Data Alat' : 'Tambah Alat Baru' }}
            </h3>

            <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    
                    {{-- Input Nama Alat --}}
                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Alat</label>
                        <input type="text" wire:model="name" placeholder="Masukkan nama alat..."
                            class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all text-sm">
                        @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- Input Kategori --}}
                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <select wire:model="category_id"
                            class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all text-sm bg-white">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Input Stok --}}
                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stok Tersedia</label>
                        <input type="number" wire:model="stock" placeholder="0"
                            class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all text-sm">
                        @error('stock') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- Input Foto Baru --}}
                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Foto Alat <span class="text-gray-400 font-normal">(Max 2MB)</span>
                        </label>
                        <input type="file" wire:model="photo" accept="image/*"
                            class="w-full border border-gray-300 px-3 py-1.5 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all text-sm file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        @error('photo') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        
                        {{-- Preview Foto (Bisa Foto Lama / Preview Baru) --}}
                        <div class="mt-2" wire:loading.remove wire:target="photo">
                            @if ($photo)
                                <img src="{{ $photo->temporaryUrl() }}" class="h-16 rounded shadow-sm object-cover">
                            @elseif($isEdit && $existingPhoto)
                                <img src="{{ asset('storage/' . $existingPhoto) }}" class="h-16 rounded shadow-sm object-cover">
                            @endif
                        </div>
                        <div wire:loading wire:target="photo" class="text-xs text-indigo-500 mt-2 font-bold animate-pulse">
                            Mengunggah foto...
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 border-t border-gray-100 pt-4">
                    <button type="submit"
                        class="inline-flex justify-center items-center px-5 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-200 transition-all shadow-sm">
                        {{ $isEdit ? 'Update Alat' : 'Simpan Alat' }}
                    </button>
                    @if($isEdit)
                        <button type="button" wire:click="resetFields"
                            class="inline-flex justify-center items-center px-5 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-gray-100 transition-all">
                            Batal Edit
                        </button>
                    @endif
                </div>
            </form>
        </div>

        {{-- TABEL ASSETS DENGAN THUMBNAIL FOTO --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead>
                        <tr
                            class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-4 w-16">ID</th>
                            <th class="px-6 py-4 w-20 text-center">Foto</th>
                            <th class="px-6 py-4">Nama Alat</th>
                            <th class="px-6 py-4">Kategori</th>
                            <th class="px-6 py-4">Stok</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($assets as $asset)
                            <tr class="hover:bg-indigo-50/50 transition-colors group">
                                <td class="px-6 py-4 text-sm text-gray-500">#{{ str_pad($asset->id, 3, '0', STR_PAD_LEFT) }}</td>
                                
                                {{-- Kolom Thumbnail Foto (Clickable untuk Zoom) --}}
                                <td class="px-6 py-4">
                                    @if($asset->photo)
                                        <div wire:click="viewImage('{{ asset('storage/' . $asset->photo) }}', '{{ $asset->name }}')" 
                                             class="h-10 w-10 rounded-md overflow-hidden bg-gray-100 border border-gray-200 mx-auto cursor-pointer hover:opacity-80 transition-opacity"
                                             title="Klik untuk melihat foto asli">
                                            <img src="{{ asset('storage/' . $asset->photo) }}" class="h-full w-full object-cover">
                                        </div>
                                    @else
                                        <div class="h-10 w-10 rounded-md bg-gray-50 border border-gray-200 flex items-center justify-center text-gray-400 mx-auto"
                                             title="Foto belum tersedia">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                        </div>
                                    @endif
                                </td>
                                
                                <td class="px-6 py-4 font-medium text-sm text-gray-900">{{ $asset->name }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-indigo-100 text-indigo-800">
                                        {{ $asset->category->name ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="font-black {{ $asset->stock > 0 ? 'text-emerald-600' : 'text-red-500' }}">
                                        {{ $asset->stock }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 flex justify-end gap-2 items-center h-full">
                                    <button wire:click="edit({{ $asset->id }})"
                                        class="inline-flex items-center px-3 py-1.5 bg-amber-50 text-amber-600 hover:bg-amber-100 rounded-md text-xs font-medium transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                    <button wire:click="delete({{ $asset->id }})"
                                        wire:confirm="Yakin ingin menghapus alat ini beserta fotonya?"
                                        class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-md text-xs font-medium transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500 text-sm">
                                    Belum ada data alat yang ditambahkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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