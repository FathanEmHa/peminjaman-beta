<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ajukan Peminjaman</h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if (session()->has('error'))
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">{{ session('error') }}</div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="font-bold mb-4">Pilih Alat</h3>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nama Alat</label>
                    <select wire:model="selected_asset" class="w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">-- Pilih Alat --</option>
                        @foreach($assets as $asset)
                            <option value="{{ $asset->id }}">{{ $asset->name }} (Stok: {{ $asset->stock }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                    <input type="number" wire:model="quantity" class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <button wire:click="addToCart"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                    Tambah ke Daftar
                </button>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="font-bold mb-4">Daftar Pinjaman Anda</h3>
                <table class="w-full mb-4">
                    <thead>
                        <tr class="border-b text-left">
                            <th class="py-2">Alat</th>
                            <th class="py-2 text-center">Qty</th>
                            <th class="py-2 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cart as $index => $item)
                            <tr class="border-b">
                                <td class="py-2">{{ $item['name'] }}</td>
                                <td class="py-2 text-center">{{ $item['quantity'] }}</td>
                                <td class="py-2 text-right">
                                    <button wire:click="removeFromCart({{ $index }})"
                                        class="text-red-500 text-sm">Batal</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-4 text-center text-gray-500 italic">Belum ada alat yang dipilih.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if(count($cart) > 0)
                    <div class="mt-6 border-t pt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rencana Tanggal Kembali</label>
                        <input type="date" wire:model="return_date"
                            class="w-full border-gray-300 rounded-md shadow-sm mb-4">
                        <button wire:click="store"
                            class="w-full bg-green-600 text-black font-bold py-2 px-4 rounded hover:bg-green-700">
                            Kirim Pengajuan
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>