<div class="mt-8">
    <h3 class="font-bold text-black text-xl mb-4">Riwayat Peminjaman Saya</h3>
    
    <div class="bg-white rounded-lg shadow-sm border border-gray-300 overflow-x-auto">
        <table class="w-full text-black">
            <thead>
                <tr class="border-b border-gray-400 text-left bg-gray-100">
                    <th class="px-4 py-3 font-bold text-gray-900">ID</th>
                    <th class="px-4 py-3 font-bold text-gray-900">Alat (Qty)</th>
                    <th class="px-4 py-3 font-bold text-gray-900">Tanggal Pinjam</th>
                    <th class="px-4 py-3 font-bold text-gray-900">Rencana Kembali</th>
                    <th class="px-4 py-3 font-bold text-gray-900 text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($loans as $loan)
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium">#{{ $loan->id }}</td>
                    <td class="px-4 py-3">
                        <ul class="list-disc ml-4 text-sm">
                            @foreach($loan->items as $item)
                                <li>{{ $item->asset->name }} ({{ $item->quantity }})</li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="px-4 py-3 text-sm">{{ $loan->loan_date }}</td>
                    <td class="px-4 py-3 text-sm">{{ $loan->return_date }}</td>
                    <td class="px-4 py-3 text-center">
                        @if($loan->status == 'pending')
                            <span class="bg-yellow-200 text-yellow-800 py-1 px-3 rounded-full text-xs font-bold uppercase">Pending</span>
                        @elseif($loan->status == 'approved')
                            <span class="bg-blue-200 text-blue-800 py-1 px-3 rounded-full text-xs font-bold uppercase">Disetujui</span>
                        @elseif($loan->status == 'ongoing')
                            <span class="bg-purple-200 text-purple-800 py-1 px-3 rounded-full text-xs font-bold uppercase">Dipinjam</span>
                        @elseif($loan->status == 'returned')
                            <span class="bg-green-200 text-green-800 py-1 px-3 rounded-full text-xs font-bold uppercase">Selesai</span>
                        @else
                            <span class="bg-red-200 text-red-800 py-1 px-3 rounded-full text-xs font-bold uppercase">Ditolak</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-gray-600 font-medium italic">Anda belum pernah melakukan peminjaman.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>