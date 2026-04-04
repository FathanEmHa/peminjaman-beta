<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kelola Alat (Assets)</h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if (session()->has('message'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('message') }}
            </div>
        @endif

        <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
            <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama Alat</label>
                        <input type="text" wire:model="name"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Kategori</label>
                        <select wire:model="category_id"
                            class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Stok Tersedia</label>
                        <input type="number" wire:model="stock"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @error('stock') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    {{ $isEdit ? 'Update Alat' : 'Simpan Alat' }}
                </button>
                @if($isEdit)
                    <button type="button" wire:click="resetFields"
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded ml-2">Batal</button>
                @endif
            </form>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="text-left font-bold border-b">
                        <th class="px-6 py-3">ID</th>
                        <th class="px-6 py-3">Nama Alat</th>
                        <th class="px-6 py-3">Kategori</th>
                        <th class="px-6 py-3">Stok</th>
                        <th class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($assets as $asset)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $asset->id }}</td>
                            <td class="px-6 py-4">{{ $asset->name }}</td>
                            <td class="px-6 py-4">{{ $asset->category->name ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $asset->stock }}</td>
                            <td class="px-6 py-4">
                                <button wire:click="edit({{ $asset->id }})"
                                    class="bg-yellow-500 text-white px-3 py-1 rounded text-sm">Edit</button>
                                <button wire:click="delete({{ $asset->id }})" wire:confirm="Yakin hapus alat ini?"
                                    class="bg-red-500 text-white px-3 py-1 rounded text-sm ml-2">Hapus</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>