<div>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2.5 bg-indigo-600 rounded-xl shadow-lg shadow-indigo-200">
                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    Data Pengembalian Alat
                </h2>
                <p class="text-sm font-medium text-gray-500 mt-0.5">Konfirmasi penerimaan alat dan kelola denda keterlambatan</p>
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

        {{-- Section Search & Filter --}}
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex flex-col sm:flex-row gap-4 justify-between items-center">
            <div class="w-full sm:w-1/3 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-amber-500 transition duration-150 ease-in-out sm:text-sm" placeholder="Cari nama peminjam...">
            </div>

            <div class="w-full sm:w-1/3 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <input wire:model.live.debounce.300ms="searchAlat" type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-amber-500 transition duration-150 ease-in-out sm:text-sm" placeholder="Cari nama alat...">
            </div>

            <div class="w-full sm:w-1/3">
                <select wire:model.live="status_filter" class="block w-full py-2 px-3 border border-gray-200 bg-gray-50 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-500 sm:text-sm transition duration-150 ease-in-out">
                    <option value="">Semua (Aktif & Selesai)</option>
                    <option value="ongoing">Ongoing (Sedang Dipinjam)</option>
                    <option value="overdue">Overdue (Telat)</option>
                    <option value="returned">Returned (Dikembalikan)</option>
                </select>
            </div>
        </div>

        {{-- Card Container Tabel --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex items-center gap-2">
                <div class="p-1.5 bg-gray-50 rounded-md border border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Daftar Peminjaman Aktif</h3>
            </div>

            <div class="overflow-x-auto relative min-h-[200px]">
                <x-loading-overlay wire:loading wire:target="search, searchAlat, status_filter, gotoPage, previousPage, nextPage" message="Menyaring data..." />

                <table class="w-full text-left whitespace-nowrap text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-gray-500 uppercase tracking-wider text-xs font-semibold">
                            <th class="px-6 py-4 w-16">ID</th>
                            <th class="px-6 py-4">Peminjam</th>
                            <th class="px-6 py-4">Alat (Qty)</th>
                            <th class="px-6 py-4">Batas Kembali</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Aksi Petugas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($loans as $loan)
                            <tr class="hover:bg-amber-50/30 transition-colors group">
                                <td class="px-6 py-4 font-medium text-amber-600">#{{ $loan->id }}</td>
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
                                    <div class="flex items-center gap-2 text-xs">
                                        <svg class="h-4 w-4 {{ $loan->status === 'overdue' ? 'text-rose-500' : 'text-amber-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        <span class="font-bold {{ $loan->status === 'overdue' ? 'text-rose-600' : 'text-gray-800' }}">{{ \Carbon\Carbon::parse($loan->return_date)->format('d M Y, H:i') }}</span>
                                    </div>
                                    @if($loan->status === 'overdue')
                                        <span class="text-[10px] font-bold text-rose-500 mt-1 block">Terlambat!</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    {{-- Panggil logic warna dan teks langsung dari Model --}}
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $loan->status_badge_class }} uppercase tracking-wider">
                                        {{ $loan->status_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex flex-col items-center justify-center gap-2 w-full max-w-[140px] mx-auto">
                                        @if(in_array($loan->status, ['ongoing', 'overdue']))
                                            <button wire:click="openReturnConfirmation({{ $loan->id }})" class="w-full px-4 py-1.5 bg-amber-500 text-white hover:bg-amber-600 rounded text-[11px] font-bold transition-colors shadow-sm uppercase tracking-wider">
                                                Terima Alat
                                            </button>
                                        
                                        @elseif($loan->status == 'returned')
                                            @php
                                                $hasFine = false;
                                                if($loan->return) {
                                                    $total = ($loan->return->late_fee ?? 0) + ($loan->return->damage_fee ?? 0);
                                                    if($total > 0) $hasFine = true;
                                                }
                                            @endphp

                                            @if($loan->has_fine)
                                                <button wire:click="openFineModal({{ $loan->id }})" class="w-full px-4 py-1.5 bg-rose-50 text-rose-600 border border-rose-200 hover:bg-rose-100 rounded text-[10px] font-bold uppercase tracking-wider transition-colors">
                                                    Kelola Denda
                                                </button>
                                            @else
                                                <span class="text-xs font-bold text-emerald-600 px-3 py-1.5 bg-emerald-50 border border-emerald-200 rounded w-full text-center block">Selesai</span>
                                            @endif
                                        @endif
                                        
                                        <a href="{{ route('petugas.loans.detail', ['loan' => $loan->id, 'ref' => 'returns']) }}" wire:navigate.hover class="w-full inline-flex items-center justify-center px-3 py-1.5 bg-gray-50 text-gray-600 border border-gray-200 hover:bg-gray-100 rounded text-[10px] font-bold transition-colors uppercase tracking-wider">
                                            Detail
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">Tidak ada data peminjaman yang aktif atau telat.</td>
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

    {{-- MODAL 1: PROSES PENGEMBALIAN --}}
    @if($showReturnModal && $selectedLoan)
        <div class="fixed inset-0 z-[1000] flex items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
                <div class="bg-amber-500 px-6 py-4 flex justify-between items-center">
                    <h3 class="text-white font-bold text-lg">Proses Pengembalian Alat</h3>
                    <button wire:click="cancelReturnConfirmation" class="text-amber-100 hover:text-white">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                
                <div class="p-6">
                    <div class="mb-4">
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Peminjam</p>
                        <p class="font-bold text-gray-800 text-lg">{{ $selectedLoan->user->name }} (ID: #{{ $selectedLoan->id }})</p>
                    </div>

                    <label class="block text-[11px] font-medium text-gray-500 mb-1 uppercase tracking-wider">Foto Kondisi (Saat Dikembalikan)</label>
                    <input type="file" wire:model="photo_after" accept="image/*"
                        class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 mb-3 focus:ring-2 focus:ring-amber-500 outline-none file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-amber-100 file:text-amber-700 hover:file:bg-amber-200 cursor-pointer">
                    @error('photo_after') <span class="text-red-500 text-xs block mb-2">{{ $message }}</span> @enderror

                    <label class="block text-[11px] font-medium text-gray-500 mb-1 uppercase tracking-wider">Catatan Kondisi Alat</label>
                    <textarea wire:model="conditionNotes" rows="2" placeholder="Cth: Kondisi baik..."
                        class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 mb-3 focus:ring-2 focus:ring-amber-500 outline-none resize-none"></textarea>
                    
                    @if($calculatedLateFee > 0)
                        <div class="mb-3 px-3 py-2 bg-rose-50 border border-rose-100 rounded-lg flex justify-between items-center">
                            <span class="text-xs font-bold text-rose-600 uppercase tracking-wider">Denda Telat</span>
                            <span class="text-sm font-bold text-rose-700">Rp {{ number_format($calculatedLateFee, 0, ',', '.') }}</span>
                        </div>
                    @endif

                    <label class="block text-[11px] font-medium text-gray-500 mb-1 uppercase tracking-wider">Denda Kerusakan Fisik (Rp)</label>
                    <input wire:model.live.debounce.300ms="damageFee" type="number" min="0" placeholder="0"
                        class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 mb-4 focus:ring-2 focus:ring-amber-500 outline-none">
                    
                    @php $totalFee = ($calculatedLateFee ?? 0) + ((int) ($damageFee ?: 0)); @endphp
                    @if($totalFee > 0)
                        <div class="flex justify-between items-center border-t border-gray-100 pt-3 mb-2">
                            <span class="text-xs font-bold text-gray-600 uppercase">Total Denda Tagihan</span>
                            <span class="text-lg font-black text-rose-600">Rp {{ number_format($totalFee, 0, ',', '.') }}</span>
                        </div>
                    @endif
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex gap-3 justify-end">
                    <button wire:click="cancelReturnConfirmation" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-bold rounded-lg hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button wire:click="confirmReturn" wire:loading.attr="disabled" class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-lg transition-colors flex items-center">
                        <span wire:loading.remove wire:target="confirmReturn">Terima Barang & Foto</span>
                        <span wire:loading wire:target="confirmReturn">Memproses...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL 2: KELOLA DENDA --}}
    @if($showFineModal && $selectedLoan && $selectedLoan->return)
        <div class="fixed inset-0 z-[1000] flex items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden">
                <div class="bg-rose-600 px-6 py-4 flex justify-between items-center">
                    <h3 class="text-white font-bold text-lg">Kelola Denda</h3>
                    <button wire:click="closeFineModal" class="text-rose-200 hover:text-white">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="p-6 bg-rose-50/30">
                    <div class="text-center mb-6">
                        <p class="text-sm text-gray-500">Peminjam: <span class="font-bold text-gray-800">{{ $selectedLoan->user->name }}</span></p>
                        <p class="text-xs text-gray-400 mt-1">ID Transaksi: #{{ $selectedLoan->id }}</p>
                    </div>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between items-center border-b border-gray-200 pb-2">
                            <span class="text-gray-600">Denda Keterlambatan</span>
                            <span class="font-medium text-gray-800">Rp {{ number_format($selectedLoan->return->late_fee ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-gray-200 pb-2">
                            <span class="text-gray-600">Denda Kerusakan</span>
                            <span class="font-medium text-gray-800">Rp {{ number_format($selectedLoan->return->damage_fee ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-2">
                            <span class="font-bold text-gray-800 uppercase tracking-wider text-xs">Total Tagihan</span>
                            <span class="font-black text-rose-600 text-lg">Rp {{ number_format(($selectedLoan->return->late_fee ?? 0) + ($selectedLoan->return->damage_fee ?? 0), 0, ',', '.') }}</span>
                        </div>

                        <div class="mt-6 flex justify-center">
                            @if($selectedLoan->return->fine_status == 'paid')
                                <span class="px-4 py-1.5 bg-emerald-100 text-emerald-700 border border-emerald-200 font-black rounded-lg tracking-widest">L U N A S</span>
                            @else
                                <span class="px-4 py-1.5 bg-rose-100 text-rose-700 border border-rose-200 font-black rounded-lg tracking-widest">B E L U M  L U N A S</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex gap-2">
                    @if($selectedLoan->return->fine_status == 'unpaid')
                        <x-confirm-modal action="markFineAsPaid({{ $selectedLoan->return->id }})" title="Konfirmasi Pelunasan" message="Apakah denda ini sudah dibayar lunas oleh peminjam?" confirm-text="Tandai Lunas" confirm-color="emerald">
                            <x-slot name="trigger">
                                <button type="button" class="w-full px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-sm font-bold rounded-lg transition-colors">
                                    Tandai Lunas
                                </button>
                            </x-slot>
                        </x-confirm-modal>
                    @endif
                    <button wire:click="closeFineModal" class="w-full px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-bold rounded-lg transition-colors">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>