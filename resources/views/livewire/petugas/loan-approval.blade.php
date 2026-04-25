<div>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2.5 bg-indigo-600 rounded-xl shadow-lg shadow-indigo-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    Kelola Peminjaman Alat
                </h2>
                <p class="text-sm font-medium text-gray-500 mt-0.5">Tinjau, setujui, dan serahkan alat ke peminjam</p>
            </div>
        </div>
    </x-slot>

    <div class="py-10 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        {{-- Flash Message --}}
        @if (session()->has('message'))
            <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl shadow-sm mb-6">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                <span class="font-medium text-sm">{{ session('message') }}</span>
            </div>
        @endif
        @if (session()->has('error'))
            <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl shadow-sm mb-6">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span class="font-medium text-sm">{{ session('error') }}</span>
            </div>
        @endif

        {{-- Section Search & Filter --}}
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex flex-col sm:flex-row gap-4 justify-between items-center">
            <div class="w-full sm:w-1/3 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out sm:text-sm" placeholder="Cari nama peminjam...">
            </div>

            <div class="w-full sm:w-1/3 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <input wire:model.live.debounce.300ms="searchAlat" type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out sm:text-sm" placeholder="Cari nama alat...">
            </div>

            <div class="w-full sm:w-1/3">
                <select wire:model.live="status_filter" class="block w-full py-2 px-3 border border-gray-200 bg-gray-50 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:text-sm transition duration-150 ease-in-out">
                    <option value="">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="ongoing">Ongoing</option>
                    <option value="returned">Returned</option>
                    <option value="rejected">Rejected</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
        </div>

        {{-- Card Container Tabel --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex items-center gap-2">
                    <div class="p-1.5 bg-gray-50 rounded-md border border-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Daftar Transaksi Peminjaman</h3>
                </div>

                <a href="{{ route('petugas.laporan') }}" wire:navigate class="inline-flex justify-center items-center px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-900 focus:ring-4 focus:ring-gray-200 transition-all shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Filter & Cetak Laporan
                </a>
            </div>

            <div class="overflow-x-auto relative min-h-[200px]">
                
                {{-- Indikator Loading Tabel --}}
                <x-loading-overlay wire:loading wire:target="search, searchAlat, status_filter, gotoPage, previousPage, nextPage" message="Menyaring data..." />

                <table class="w-full text-left whitespace-nowrap text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-gray-500 uppercase tracking-wider text-xs font-semibold">
                            <th class="px-6 py-4 w-16">ID</th>
                            <th class="px-6 py-4">Peminjam</th>
                            <th class="px-6 py-4">Alat (Qty)</th>
                            <th class="px-6 py-4">Timeline</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Aksi Petugas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($loans as $loan)
                            <tr class="hover:bg-indigo-50/30 transition-colors group">
                                <td class="px-6 py-4 font-medium text-indigo-600">#{{ $loan->id }}</td>
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $loan->user->name }}</td>
                                <td class="px-6 py-4 whitespace-normal min-w-[180px]">
                                    <ul class="space-y-1 text-gray-700">
                                        @foreach($loan->items as $item)
                                            <li class="flex items-center gap-2">
                                                <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                                                {{ $item->asset->name }} <span class="text-xs font-bold text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded ml-1 border border-gray-200">x{{ $item->quantity }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    <div class="flex items-center gap-2 mb-1.5 text-xs">
                                        <span class="w-14 text-gray-500">Pinjam</span>
                                        <svg class="h-3.5 w-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                        <span class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($loan->loan_date)->format('d M Y, H:i') }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-xs">
                                        <span class="w-14 text-gray-500">Kembali</span>
                                        <svg class="h-3.5 w-3.5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                        <span class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($loan->return_date)->format('d M Y, H:i') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    {{-- Panggil logic warna dan teks langsung dari Model --}}
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $loan->status_badge_class }} uppercase tracking-wider">
                                        {{ $loan->status_label }}
                                    </span>
                                </td>                                <td class="px-6 py-4 text-center">
                                    <div class="flex flex-col items-center gap-2 w-full">
                                        
                                        {{-- Tombol Lihat Detail --}}
                                        <a href="{{ route('petugas.loans.detail', ['loan' => $loan->id, 'ref' => 'approval']) }}" class="w-full inline-flex items-center justify-center px-3 py-1.5 bg-gray-50 text-gray-700 border border-gray-200 hover:bg-gray-100 rounded text-[11px] font-bold transition-colors uppercase tracking-wider">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                            Lihat Detail
                                        </a>

                                        {{-- Kumpulan Tombol Action Pake Konfirmasi --}}
                                        <div class="flex items-center justify-center gap-2 w-full">
                                            
                                            @if($loan->status == 'pending')
                                                <x-confirm-modal action="approve({{ $loan->id }})" title="Setujui Peminjaman?" message="Apakah Anda yakin ingin menyetujui peminjaman ini?" confirm-text="Setujui" confirm-color="emerald">
                                                    <x-slot name="trigger">
                                                        <button type="button" class="w-full inline-flex justify-center px-3 py-1.5 bg-emerald-50 text-emerald-600 border border-emerald-200 hover:bg-emerald-100 rounded text-xs font-bold transition-colors">Setujui</button>
                                                    </x-slot>
                                                </x-confirm-modal>

                                                <x-confirm-modal action="reject({{ $loan->id }})" title="Tolak Peminjaman?" message="Apakah Anda yakin ingin menolak peminjaman ini?" confirm-text="Tolak" confirm-color="red">
                                                    <x-slot name="trigger">
                                                        <button type="button" class="w-full inline-flex justify-center px-3 py-1.5 bg-rose-50 text-rose-600 border border-rose-200 hover:bg-rose-100 rounded text-xs font-bold transition-colors">Tolak</button>
                                                    </x-slot>
                                                </x-confirm-modal>
                                            
                                            @elseif($loan->status == 'approved')
                                                <button wire:click="openHandoverModal({{ $loan->id }})" class="flex-1 inline-flex justify-center px-2 py-1.5 bg-indigo-50 text-indigo-600 border border-indigo-200 hover:bg-indigo-100 rounded text-[11px] font-bold transition-colors">
                                                    Serahkan Alat
                                                </button>

                                                <x-confirm-modal action="cancel({{ $loan->id }})" title="Batalkan Peminjaman?" message="Yakin ingin membatalkan peminjaman yang sudah disetujui ini? Stok akan dikembalikan." confirm-text="Ya, Batalkan" confirm-color="red">
                                                    <x-slot name="trigger">
                                                        <button type="button" class="flex-1 inline-flex justify-center px-2 py-1.5 bg-rose-50 text-rose-600 border border-rose-200 hover:bg-rose-100 rounded text-[11px] font-bold transition-colors">Batal</button>
                                                    </x-slot>
                                                </x-confirm-modal>
                                            
                                            @elseif(in_array($loan->status, ['ongoing', 'overdue', 'returned']))
                                                {{-- Arahkan ke halaman Pengembalian --}}
                                                <a href="{{ route('petugas.returns', ['search' => $loan->id]) }}" wire:navigate.hover class="w-full px-4 py-1.5 bg-amber-50 text-amber-600 border border-amber-200 hover:bg-amber-100 rounded text-[11px] font-bold transition-colors shadow-sm uppercase tracking-wider text-center block">
                                                    Cek Pengembalian
                                                </a>
                                            
                                            @else
                                                <span class="text-gray-400 text-xs italic text-center w-full block">—</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">Belum ada data transaksi.</td>
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
    </div>

    {{-- MODAL PENYERAHAN ALAT (Handover) --}}
    @if($showHandoverModal && $selectedLoan)
        <div class="fixed inset-0 z-[1000] flex items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
                <div class="bg-indigo-600 px-6 py-4 flex justify-between items-center">
                    <h3 class="text-white font-bold text-lg">Serah Terima Alat</h3>
                    <button wire:click="closeHandoverModal" class="text-indigo-200 hover:text-white">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                
                <div class="p-6">
                    <div class="mb-4">
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Peminjam</p>
                        <p class="font-bold text-gray-800 text-lg">{{ $selectedLoan->user->name }} (ID: #{{ $selectedLoan->id }})</p>
                        <p class="text-xs text-gray-500 mt-1">Pastikan alat diserahkan sesuai dengan daftar permohonan.</p>
                    </div>

                    <label class="block text-[11px] font-medium text-gray-500 mb-1 uppercase tracking-wider">Bukti Foto Sebelum (Opsional/Disarankan)</label>
                    <input type="file" wire:model="photo_before" accept="image/*"
                        class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 mb-2 focus:ring-2 focus:ring-indigo-500 outline-none file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                    @error('photo_before') <span class="text-red-500 text-xs block mb-2">{{ $message }}</span> @enderror
                    <p class="text-xs text-gray-400 italic mb-4">*Foto ini akan muncul di halaman riwayat peminjam.</p>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex gap-3 justify-end">
                    <button wire:click="closeHandoverModal" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-bold rounded-lg hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button wire:click="confirmHandover" wire:loading.attr="disabled" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-lg transition-colors flex items-center">
                        <span wire:loading.remove wire:target="confirmHandover">Konfirmasi Penyerahan</span>
                        <span wire:loading wire:target="confirmHandover">Mengupload...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>