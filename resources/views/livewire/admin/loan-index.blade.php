<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-black leading-tight">Semua Data Peminjaman (Admin)</h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if (session()->has('message'))
            <div class="bg-green-100 text-green-800 font-bold p-3 rounded mb-4">
                {{ session('message') }}
            </div>
        @endif

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-300 overflow-x-auto">
            <table class="w-full text-black">
                <thead>
                    <tr class="border-b border-gray-400 text-left bg-gray-100">
                        <th class="px-4 py-3 font-bold text-gray-900">ID</th>
                        <th class="px-4 py-3 font-bold text-gray-900">Peminjam</th>
                        <th class="px-4 py-3 font-bold text-gray-900">Alat (Qty)</th>
                        <th class="px-4 py-3 font-bold text-gray-900">Tgl Pinjam/Kembali</th>
                        <th class="px-4 py-3 font-bold text-gray-900 text-center">Status</th>
                        <th class="px-4 py-3 font-bold text-gray-900 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loans as $loan)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium">#{{ $loan->id }}</td>
                        <td class="px-4 py-3">{{ $loan->user->name }}</td>
                        <td class="px-4 py-3 text-sm">
                            <ul class="list-disc ml-4">
                                @foreach($loan->items as $item)
                                    <li>{{ $item->asset->name }} ({{ $item->quantity }})</li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            P: {{ $loan->loan_date }}<br>
                            K: {{ $loan->return_date }}
                        </td>
                        <td class="px-4 py-3 text-center font-bold text-xs uppercase">{{ $loan->status }}</td>
                        <td class="px-4 py-3 text-center">
                            <button wire:click="delete({{ $loan->id }})" wire:confirm="Yakin hapus data transaksi ini secara permanen?" class="bg-red-600 hover:bg-red-700 text-white font-bold px-3 py-1 rounded text-sm">Hapus</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500 italic">Belum ada transaksi peminjaman.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>