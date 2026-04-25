<div>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            {{-- Ikon Riwayat (Jam) --}}
            <div class="p-2.5 bg-blue-600 rounded-xl shadow-lg shadow-blue-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            
            {{-- Teks Header --}}
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    Riwayat Peminjaman
                </h2>
                <p class="text-sm font-medium text-gray-500 mt-0.5">Pantau status pengajuan dan seluruh aktivitas peminjaman alat Anda</p>
            </div>
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
                    placeholder="Cari nama alat...">
            </div>

            <div class="w-full sm:w-1/3">
                <select wire:model.live="status_filter"
                    class="block w-full py-2 px-3 border border-gray-200 bg-gray-50 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out">
                    <option value="">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="ongoing">Ongoing</option>
                    <option value="returned">Returned</option>
                    <option value="overdue">Overdue</option>
                    <option value="rejected">Rejected</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-800">Daftar Transaksi</h3>
            </div>

            <div class="overflow-x-auto">
                <x-loading-overlay wire:loading wire:target="search, status_filter, gotoPage, previousPage, nextPage" message="Menyaring data..." />
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
                                        @if($loan->items->count() > 0)
                                            @php $firstItem = $loan->items->first(); @endphp
                                            <li class="flex items-center gap-2">
                                                <span class="h-1.5 w-1.5 rounded-full bg-indigo-300"></span>
                                                {{ $firstItem->asset->name }} 
                                                <span class="text-xs font-bold text-gray-500 ml-1">x{{ $firstItem->quantity }}</span>
                                            </li>
                                        @endif
                                    </ul>
                                    
                                    @if($loan->items->count() > 1)
                                        <a href="{{ route('peminjam.loans.detail', $loan->id) }}" wire:navigate class="inline-block mt-2 text-xs font-medium text-indigo-600 hover:text-indigo-800 transition-colors">
                                            + {{ $loan->items->count() - 1 }} alat lainnya...
                                        </a>
                                    @endif
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
                                    {{-- Panggil logic warna dan teks langsung dari Model --}}
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $loan->status_badge_class }} uppercase tracking-wider">
                                        {{ $loan->status_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        
                                        {{-- Tombol Lihat Detail --}}
                                        <a href="{{ route('peminjam.loans.detail', $loan->id) }}" wire:navigate.hover
                                           class="inline-flex items-center justify-center px-3 py-1.5 bg-indigo-50 text-indigo-700 border border-indigo-200 hover:bg-indigo-100 rounded-lg text-[11px] font-bold transition-colors w-full uppercase tracking-wider">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Lihat Detail
                                        </a>

                                        {{-- Aksi Utama --}}
                                        @if($loan->status == 'returned' && !$loan->has_fine)
                                            <span class="text-xs font-bold text-emerald-600">Selesai</span>
                                        @elseif(in_array($loan->status, ['rejected', 'cancelled']))
                                            <span class="text-gray-400 text-xs italic">Dibatalkan</span>
                                        @endif

                                        {{-- Tombol Info Denda --}}
                                        @if($loan->status === 'overdue' || $loan->has_fine)
                                            <button wire:click="openInfoModal({{ $loan->id }})" 
                                                class="inline-flex items-center justify-center px-3 py-1 bg-rose-50 text-rose-600 border border-rose-200 hover:bg-rose-100 hover:text-rose-700 rounded text-[10px] font-bold transition-colors w-full uppercase tracking-wider">
                                                Info Denda
                                            </button>
                                        @endif

                                        {{-- Tombol Batal --}}
                                        @if(in_array($loan->status, ['pending', 'approved']))
                                            <x-confirm-modal 
                                                action="cancelLoan({{ $loan->id }})" 
                                                title="Batalkan Peminjaman?" 
                                                message="Apakah Anda yakin ingin membatalkan pengajuan ini?" 
                                                confirm-text="Ya, Batalkan" 
                                                cancel-text="Kembali" 
                                                confirm-color="red"
                                            >
                                                <x-slot name="trigger">
                                                    <button type="button" class="flex-1 inline-flex justify-center px-2 py-1.5 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded text-[11px] font-bold transition-colors w-full border border-rose-200">
                                                        Batalkan
                                                    </button>
                                                </x-slot>
                                            </x-confirm-modal>
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

            @if(method_exists($loans, 'links') && $loans->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                    {{ $loans->links() }}
                </div>
            @endif
        </div>

        {{-- MODAL INFO DENDA --}}
        @if($showModal && $selectedLoan)
        <template x-teleport="body">
            <div class="fixed inset-0 z-[1000] flex items-center justify-center bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity">
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
                            // Tambahin ?? 0 biar aman dari error null
                            $estLateFee = $selectedLoan->nominal_denda ?? 0; 
                            $actualLateFee = 0;
                            $actualDamageFee = 0;
                            $damageDescription = null; 
                            $statusPembayaran = 'Menunggu';

                            if ($selectedLoan->status == 'returned' && $selectedLoan->return) {
                                $actualLateFee = $selectedLoan->return->late_fee ?? 0;
                                $actualDamageFee = $selectedLoan->return->damage_fee ?? 0;
                                $damageDescription = $selectedLoan->return->condition_notes ?? null; 
                                $statusPembayaran = $selectedLoan->return->fine_status == 'paid' ? 'LUNAS' : 'BELUM LUNAS';
                            }
                        @endphp

                        <div class="space-y-3 text-sm">
                            @if(in_array($selectedLoan->status, ['ongoing', 'overdue']))
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
                                
                                <div class="border-b border-gray-200 pb-2">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Denda Kerusakan</span>
                                        <span class="font-medium text-gray-800">Rp {{ number_format($actualDamageFee, 0, ',', '.') }}</span>
                                    </div>
                                    
                                    @if($actualDamageFee > 0 && !empty($damageDescription))
                                        <div class="mt-2 p-2.5 bg-white/60 rounded-lg border border-rose-200 text-xs">
                                            <span class="block text-rose-800 font-bold mb-0.5">Catatan Kerusakan:</span>
                                            <span class="text-gray-600 italic break-words">"{{ $damageDescription }}"</span>
                                        </div>
                                    @endif
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

                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-center">
                        <button wire:click="closeModal" class="px-6 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-bold rounded-lg transition-colors w-full">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </template>
        @endif

    </div>
</div>