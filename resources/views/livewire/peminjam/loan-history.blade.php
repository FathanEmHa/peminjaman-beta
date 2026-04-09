<div>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-indigo-100 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                Riwayat Peminjaman Saya
            </h2>
        </div>
    </x-slot>

    <div class="py-10 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        {{-- Flash Message --}}
        @if (session()->has('message'))
            <div
                class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <span class="font-medium text-sm">{{ session('message') }}</span>
            </div>
        @endif

        {{-- Section Search & Filter --}}
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex flex-col sm:flex-row gap-4 justify-between items-center">
            <div class="w-full sm:w-1/2 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text"
                    class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out sm:text-sm"
                    placeholder="Cari berdasarkan nama alat...">
            </div>

            <div class="w-full sm:w-1/3">
                <select wire:model.live="status_filter"
                    class="block w-full py-2 px-3 border border-gray-200 bg-gray-50 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out">
                    <option value="">Semua Status Riwayat</option>
                    <option value="pending">Menunggu Disetujui</option>
                    <option value="approved">Disetujui (Belum Diambil)</option>
                    <option value="ongoing">Sedang Dipinjam</option>
                    <option value="awaiting_return">Menunggu Konfirmasi Kembali</option>
                    <option value="returned">Selesai Dikembalikan</option>
                    <option value="rejected">Ditolak</option>
                    <option value="overdue">Overdue</option>
                </select>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-800">Daftar Transaksi</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-gray-500 uppercase tracking-wider text-xs font-semibold">
                            <th class="px-6 py-4 w-16">ID</th>
                            <th class="px-6 py-4">Alat (Qty)</th>
                            <th class="px-6 py-4">Tanggal Pinjam</th>
                            <th class="px-6 py-4">Rencana Kembali</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Aksi / Info</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($loans as $loan)
                            <tr class="hover:bg-indigo-50/50 transition-colors group">
                                <td class="px-6 py-4 font-medium text-indigo-600">#{{ $loan->id }}</td>
                                <td class="px-6 py-4 whitespace-normal min-w-[200px]">
                                    <ul class="space-y-1 text-gray-700">
                                        @foreach($loan->items as $item)
                                            <li class="flex items-center gap-2">
                                                <span class="h-1.5 w-1.5 rounded-full bg-indigo-300"></span>
                                                {{ $item->asset->name }} <span class="text-xs font-bold text-gray-500 ml-1">x{{ $item->quantity }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    <div class="flex items-center gap-2">
                                        <svg class="h-3.5 w-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ \Carbon\Carbon::parse($loan->loan_date)->format('d M Y, H:i') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    <div class="flex items-center gap-2">
                                        <svg class="h-4 w-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        {{ \Carbon\Carbon::parse($loan->return_date)->format('d M Y, H:i') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $hasFineRecord = false;
                                        $totalFineRecord = 0;

                                        // Cek apakah sudah returned dan ada rekam denda
                                        if ($loan->status == 'returned' && $loan->return) {
                                            $totalFineRecord = ($loan->return->late_fee ?? 0) + ($loan->return->damage_fee ?? 0);
                                            if ($totalFineRecord > 0) $hasFineRecord = true;
                                        }

                                        $badges = [
                                            'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                            'approved' => 'bg-blue-100 text-blue-700 border-blue-200',
                                            'ongoing' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                                            'awaiting_return' => 'bg-orange-100 text-orange-700 border-orange-200',
                                            'returned' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                            'rejected' => 'bg-red-100 text-red-700 border-red-200',
                                            'overdue' => 'bg-rose-100 text-rose-700 border-rose-200',
                                        ];
                                        $labels = [
                                            'pending' => 'Pending',
                                            'approved' => 'Approved',
                                            'ongoing' => 'Ongoing',
                                            'awaiting_return' => 'Awaiting Return',
                                            'returned' => 'Returned',
                                            'rejected' => 'Rejected',
                                            'overdue' => 'OVERDUE',
                                        ];

                                        $badgeClass = $badges[$loan->status] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                                        $label = $labels[$loan->status] ?? strtoupper($loan->status);
                                    @endphp

                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border {{ $badgeClass }}">
                                        {{ $label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        {{-- Aksi Utama --}}
                                        @if(in_array($loan->status, ['ongoing', 'overdue']))
                                            <button wire:click="requestReturn({{ $loan->id }})"
                                                wire:confirm="Apakah Anda yakin ingin mengajukan pengembalian untuk peminjaman #{{ $loan->id }}? Pastikan alat diserahkan kepada Petugas."
                                                class="inline-flex items-center justify-center px-4 py-1.5 bg-orange-50 text-orange-600 border border-orange-200 hover:bg-orange-100 hover:text-orange-700 rounded-md text-xs font-bold transition-colors w-full">
                                                Kembalikan
                                            </button>
                                        @elseif($loan->status == 'awaiting_return')
                                            <span class="text-xs text-orange-600 italic">Menunggu Petugas...</span>
                                        @elseif($loan->status == 'returned' && !$hasFineRecord)
                                            <span class="text-xs font-bold text-emerald-600">Alat Kembali</span>
                                        @elseif($loan->status == 'rejected' || $loan->status == 'pending' || $loan->status == 'approved')
                                            <span class="text-gray-400 text-xs">—</span>
                                        @endif

                                        {{-- Tombol Buka Modal Denda --}}
                                        @if($loan->status === 'overdue' || $hasFineRecord)
                                            <button wire:click="openInfoModal({{ $loan->id }})" 
                                                class="inline-flex items-center justify-center px-3 py-1 bg-rose-50 text-rose-600 border border-rose-200 hover:bg-rose-100 hover:text-rose-700 rounded text-[10px] font-bold transition-colors w-full uppercase tracking-wider">
                                                Info Denda
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">Tidak ada riwayat peminjaman.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($loans, 'links'))
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                    {{ $loans->links() }}
                </div>
            @endif
        </div>

    </div>

    {{-- MODAL INFO DENDA --}}
    @if($showModal && $selectedLoan)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden transform transition-all">
                
                {{-- Header Modal --}}
                <div class="bg-rose-600 px-6 py-4 flex justify-between items-center">
                    <h3 class="text-white font-bold text-lg">Informasi Tagihan Denda</h3>
                    <button wire:click="closeModal" class="text-rose-200 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Body Modal --}}
                <div class="p-6 bg-rose-50/30">
                    <div class="text-center mb-6">
                        <p class="text-sm text-gray-500">ID Transaksi</p>
                        <p class="text-xl font-black text-gray-800">#{{ $selectedLoan->id }}</p>
                    </div>

                    @php
                        $estLateFee = 0;
                        $actualLateFee = 0;
                        $actualDamageFee = 0;
                        $statusPembayaran = 'Menunggu';

                        // Jika masih Overdue (belum diselesaikan petugas)
                        if (in_array($selectedLoan->status, ['ongoing', 'awaiting_return', 'overdue']) && $selectedLoan->return_date) {
                            $expected = \Carbon\Carbon::parse($selectedLoan->return_date);
                            if (now()->greaterThan($expected)) {
                                $diffInDays = ceil(now()->diffInMinutes($expected) / 1440) ?: 1;
                                $estLateFee = $diffInDays * 5000;
                            }
                        }

                        // Jika sudah Returned (sudah dicatat petugas)
                        if ($selectedLoan->status == 'returned' && $selectedLoan->return) {
                            $actualLateFee = $selectedLoan->return->late_fee ?? 0;
                            $actualDamageFee = $selectedLoan->return->damage_fee ?? 0;
                            $statusPembayaran = $selectedLoan->return->fine_status == 'paid' ? 'LUNAS' : 'BELUM LUNAS';
                        }
                    @endphp

                    <div class="space-y-3 text-sm">
                        @if(in_array($selectedLoan->status, ['ongoing', 'awaiting_return', 'overdue']))
                            <div class="flex justify-between items-center border-b border-gray-200 pb-2">
                                <span class="text-gray-600">Estimasi Keterlambatan</span>
                                <span class="font-bold text-rose-600">Rp {{ number_format($estLateFee, 0, ',', '.') }}</span>
                            </div>
                            <div class="mt-4 p-3 bg-amber-50 text-amber-700 text-xs rounded-lg border border-amber-200 text-center">
                                *Total denda pasti akan ditentukan oleh Petugas saat pengembalian fisik alat.
                            </div>
                        @else
                            <div class="flex justify-between items-center border-b border-gray-200 pb-2">
                                <span class="text-gray-600">Denda Keterlambatan</span>
                                <span class="font-medium text-gray-800">Rp {{ number_format($actualLateFee, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center border-b border-gray-200 pb-2">
                                <span class="text-gray-600">Denda Kerusakan</span>
                                <span class="font-medium text-gray-800">Rp {{ number_format($actualDamageFee, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center pt-2">
                                <span class="font-bold text-gray-800 uppercase tracking-wider text-xs">Total Tagihan</span>
                                <span class="font-black text-rose-600 text-lg">Rp {{ number_format($actualLateFee + $actualDamageFee, 0, ',', '.') }}</span>
                            </div>

                            <div class="mt-6 flex justify-center">
                                @if($statusPembayaran == 'LUNAS')
                                    <span class="px-4 py-1.5 bg-emerald-100 text-emerald-700 border border-emerald-200 font-black rounded-lg tracking-widest">
                                        L U N A S
                                    </span>
                                @else
                                    <span class="px-4 py-1.5 bg-rose-100 text-rose-700 border border-rose-200 font-black rounded-lg tracking-widest">
                                        B E L U M  L U N A S
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Footer Modal --}}
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-center">
                    <button wire:click="closeModal" class="px-6 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-bold rounded-lg transition-colors w-full">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>