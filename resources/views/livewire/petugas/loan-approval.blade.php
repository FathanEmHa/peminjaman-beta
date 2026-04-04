<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-black leading-tight">Kelola Peminjaman Alat</h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if (session()->has('message'))
            <div class="bg-green-100 text-green-800 font-bold p-3 rounded mb-4">
                {{ session('message') }}
            </div>
        @endif

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-300">
            
            <div class="flex justify-between items-center mb-4 pb-2 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-800">Daftar Transaksi Peminjaman</h3>
                
                <a href="{{ route('petugas.laporan.cetak') }}" target="_blank" class="bg-gray-900 hover:bg-black text-white font-bold py-2 px-4 rounded flex items-center text-sm border border-black shadow-sm transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Cetak Laporan
                </a>
            </div>

            <div class="overflow-x-auto">
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
                        <td class="px-4 py-3">#{{ $loan->id }}</td>
                        <td class="px-4 py-3">{{ $loan->user->name }}</td>
                        <td class="px-4 py-3">
                            <ul class="list-disc ml-4 text-sm">
                                @foreach($loan->items as $item)
                                    <li>{{ $item->asset->name }} ({{ $item->quantity }})</li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            Pinjam: {{ $loan->loan_date }}<br>
                            Kembali: {{ $loan->return_date }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($loan->status == 'pending')
                                <span class="bg-yellow-200 text-yellow-800 py-1 px-2 rounded text-xs font-bold uppercase">Pending</span>
                            @elseif($loan->status == 'approved')
                                <span class="bg-blue-200 text-blue-800 py-1 px-2 rounded text-xs font-bold uppercase">Approved</span>
                            @elseif($loan->status == 'ongoing')
                                <span class="bg-purple-200 text-purple-800 py-1 px-2 rounded text-xs font-bold uppercase">Ongoing</span>
                            @elseif($loan->status == 'returned')
                                <span class="bg-green-200 text-green-800 py-1 px-2 rounded text-xs font-bold uppercase">Returned</span>
                            @else
                                <span class="bg-red-200 text-red-800 py-1 px-2 rounded text-xs font-bold uppercase">Rejected</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center space-y-1">
                            @if($loan->status == 'pending')
                                <button wire:click="approve({{ $loan->id }})" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold py-1 px-2 rounded">Approve</button>
                                <button wire:click="reject({{ $loan->id }})" class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold py-1 px-2 rounded">Reject</button>
                            @elseif($loan->status == 'approved')
                                <button wire:click="markOngoing({{ $loan->id }})" class="bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold py-1 px-2 rounded w-full">Serahkan Alat (Ongoing)</button>
                            @elseif($loan->status == 'ongoing')
                                <button wire:click="markReturned({{ $loan->id }})" wire:confirm="Selesaikan peminjaman ini?" class="bg-green-600 hover:bg-green-700 text-white text-xs font-bold py-1 px-2 rounded w-full">Terima Alat (Returned)</button>
                            @else
                                <span class="text-gray-500 text-xs italic">Selesai</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-4 text-center text-gray-500 italic">Belum ada data peminjaman.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>