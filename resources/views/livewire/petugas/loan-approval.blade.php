<div>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-indigo-100 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
            </div>
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                Kelola Peminjaman Alat
            </h2>
        </div>
    </x-slot>

    <div class="py-10 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        {{-- Flash Message --}}
        @if (session()->has('message'))
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                {{ session('message') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
                {{ session('error') }}
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
                    <option value="awaiting_return">Tunggu Konfirmasi</option>
                    <option value="returned">Returned</option>
                    <option value="rejected">Rejected</option>
                    <option value="overdue">Overdue</option>
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

            <div class="overflow-x-auto">
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
                                                {{ $item->asset->name }} <span class="text-xs font-bold text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded ml-1">x{{ $item->quantity }}</span>
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
                                    @php
                                        $badges = [
                                            'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                            'approved' => 'bg-blue-100 text-blue-700 border-blue-200',
                                            'ongoing' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                                            'awaiting_return' => 'bg-orange-100 text-orange-700 border-orange-200',
                                            'returned' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                            'rejected' => 'bg-red-100 text-red-700 border-red-200',
                                            'overdue' => 'bg-rose-100 text-rose-700 border-rose-200',
                                        ];
                                        
                                        $badgeClass = $badges[$loan->status] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                                        $labelText = $loan->status === 'overdue' ? 'OVERDUE' : $loan->status;
                                    @endphp

                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border {{ $badgeClass }} uppercase tracking-wide">
                                        {{ $labelText }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2 w-full">
                                        @if($loan->status == 'pending')
                                            <button wire:click="approve({{ $loan->id }})" class="flex-1 inline-flex justify-center px-3 py-1.5 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 rounded text-xs font-bold transition-colors">Setujui</button>
                                            <button wire:click="reject({{ $loan->id }})" wire:confirm="Yakin menolak peminjaman ini?" class="flex-1 inline-flex justify-center px-3 py-1.5 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded text-xs font-bold transition-colors">Tolak</button>
                                        
                                        @elseif($loan->status == 'approved')
                                            <button wire:click="markOngoing({{ $loan->id }})" class="flex-1 inline-flex justify-center px-2 py-1.5 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded text-[11px] font-bold transition-colors">
                                                Serahkan
                                            </button>
                                            <button wire:click="cancel({{ $loan->id }})" wire:confirm="Yakin ingin membatalkan peminjaman yang sudah disetujui ini? Stok akan dikembalikan." class="flex-1 inline-flex justify-center px-2 py-1.5 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded text-[11px] font-bold transition-colors">
                                                Batal
                                            </button>
                                            
                                        @elseif(in_array($loan->status, ['awaiting_return', 'overdue']))
                                            <button wire:click="openReturnConfirmation({{ $loan->id }})" class="w-full px-4 py-1.5 bg-amber-50 text-amber-600 hover:bg-amber-100 rounded text-xs font-bold transition-colors shadow-sm">
                                                Proses Pengembalian
                                            </button>
                                            
                                        @elseif($loan->status == 'returned')
                                            @php
                                                $hasFine = false;
                                                if($loan->return) {
                                                    $total = ($loan->return->late_fee ?? 0) + ($loan->return->damage_fee ?? 0);
                                                    if($total > 0) $hasFine = true;
                                                }
                                            @endphp

                                            @if($hasFine)
                                                <button wire:click="openFineModal({{ $loan->id }})" class="w-full px-4 py-1.5 bg-rose-50 text-rose-600 border border-rose-200 hover:bg-rose-100 rounded text-[10px] font-bold uppercase tracking-wider transition-colors">
                                                    Kelola Denda
                                                </button>
                                            @else
                                                <span class="text-xs font-bold text-emerald-600 px-3 py-1.5 bg-emerald-50 rounded">Selesai</span>
                                            @endif
                                            
                                        @else
                                            <span class="text-gray-400 text-xs italic">—</span>
                                        @endif
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

            @if(method_exists($loans, 'links'))
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                    {{ $loans->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- MODAL 1: PROSES PENGEMBALIAN --}}
    @if($showReturnModal && $selectedLoan)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity">
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

                    <label class="block text-[11px] font-medium text-gray-500 mb-1 uppercase tracking-wider">Catatan Kondisi Alat</label>
                    <textarea wire:model="conditionNotes" rows="2" placeholder="Cth: Kondisi baik..."
                        class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 mb-3 focus:ring-2 focus:ring-amber-500 outline-none resize-none"></textarea>
                    
                    @if($calculatedLateFee > 0)
                        <div class="mb-3 px-3 py-2 bg-rose-50 border border-rose-100 rounded-lg flex justify-between items-center">
                            <span class="text-xs font-bold text-rose-600 uppercase tracking-wider">Denda Telat</span>
                            <span class="text-sm font-bold text-rose-700">Rp {{ number_format($calculatedLateFee, 0, ',', '.') }}</span>
                        </div>
                    @endif

                    <label class="block text-[11px] font-medium text-gray-500 mb-1 uppercase tracking-wider">Denda Kerusakan FIsik (Rp)</label>
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
                        <span wire:loading.remove wire:target="confirmReturn">Terima Barang</span>
                        <span wire:loading wire:target="confirmReturn">Memproses...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL 2: KELOLA DENDA (BILA ADA) --}}
    @if($showFineModal && $selectedLoan && $selectedLoan->return)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity">
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
                        <button wire:click="markFineAsPaid({{ $selectedLoan->return->id }})" wire:confirm="Selesaikan pembayaran denda?" class="w-full px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-sm font-bold rounded-lg transition-colors">
                            Tandai Lunas
                        </button>
                    @endif
                    <button wire:click="closeFineModal" class="w-full px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-bold rounded-lg transition-colors">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>