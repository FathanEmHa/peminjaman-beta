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
                    placeholder="Cari nama peminjam...">
            </div>

            <div class="w-full sm:w-1/3">
                <select wire:model.live="status_filter" 
                    class="block w-full py-2 px-3 border border-gray-200 bg-gray-50 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out">
                    <option value="">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="ongoing">Ongoing</option>
                    <option value="awaiting_return">Tunggu Konfirmasi</option>
                    <option value="returned">Returned</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
        </div>

        {{-- Card Container --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

            {{-- Header Table --}}
            <div
                class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex items-center gap-2">
                    <div class="p-1.5 bg-gray-50 rounded-md border border-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Daftar Transaksi Peminjaman</h3>
                </div>

                <a href="{{ route('petugas.laporan.cetak') }}" target="_blank"
                    class="inline-flex justify-center items-center px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-900 focus:ring-4 focus:ring-gray-200 transition-all shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                        </path>
                    </svg>
                    Cetak Laporan
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap text-sm">
                    <thead>
                        <tr
                            class="bg-gray-50 border-b border-gray-100 text-gray-500 uppercase tracking-wider text-xs font-semibold">
                            <th class="px-6 py-4 w-16">ID</th>
                            <th class="px-6 py-4">Peminjam</th>
                            <th class="px-6 py-4">Alat (Qty)</th>
                            <th class="px-6 py-4">Timeline</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center w-56">Aksi Petugas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($loans as $loan)
                            <tr
                                class="hover:bg-indigo-50/30 transition-colors group {{ $confirmingReturnId === $loan->id ? 'bg-orange-50/30' : '' }}">
                                <td class="px-6 py-4 font-medium text-indigo-600">#{{ $loan->id }}</td>
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $loan->user->name }}</td>
                                <td class="px-6 py-4 whitespace-normal min-w-[180px]">
                                    <ul class="space-y-1 text-gray-700">
                                        @foreach($loan->items as $item)
                                            <li class="flex items-center gap-2">
                                                <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                                                {{ $item->asset->name }} <span
                                                    class="text-xs font-bold text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded ml-1">x{{ $item->quantity }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    <div class="flex items-center gap-2 mb-1.5 text-xs">
                                        <span class="w-14 text-gray-500">Pinjam</span>
                                        <svg class="h-3.5 w-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span
                                            class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($loan->loan_date)->format('d M Y, H:i') }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-xs">
                                        <span class="w-14 text-gray-500">Kembali</span>
                                        <svg class="h-3.5 w-3.5 text-amber-500" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span
                                            class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($loan->return_date)->format('d M Y, H:i') }}</span>
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
                                        ];
                                        $labels = [
                                            'pending' => 'Pending',
                                            'approved' => 'Approved',
                                            'ongoing' => 'Ongoing',
                                            'awaiting_return' => 'Tunggu Konfirmasi',
                                            'returned' => 'Returned',
                                            'rejected' => 'Rejected',
                                        ];
                                        $badgeClass = $badges[$loan->status] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border {{ $badgeClass }} uppercase tracking-wide">
                                        {{ $labels[$loan->status] ?? $loan->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($loan->status == 'pending')
                                        <div class="flex items-center gap-2 justify-center">
                                            <button wire:click="approve({{ $loan->id }})"
                                                class="flex-1 inline-flex justify-center items-center px-3 py-1.5 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 hover:text-emerald-700 rounded-md text-xs font-bold transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                                Setujui
                                            </button>
                                            <button wire:click="reject({{ $loan->id }})"
                                                wire:confirm="Yakin menolak peminjaman ini?"
                                                class="flex-1 inline-flex justify-center items-center px-3 py-1.5 bg-rose-50 text-rose-600 hover:bg-rose-100 hover:text-rose-700 rounded-md text-xs font-bold transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                Tolak
                                            </button>
                                        </div>
                                    @elseif($loan->status == 'approved')
                                        <button wire:click="markOngoing({{ $loan->id }})"
                                            class="w-full inline-flex justify-center items-center px-4 py-1.5 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 hover:text-indigo-700 border border-indigo-100 rounded-md text-xs font-bold transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                            </svg>
                                            Serahkan Alat
                                        </button>
                                    @elseif($loan->status == 'awaiting_return')
                                        @if($confirmingReturnId !== $loan->id)
                                            <button wire:click="openReturnConfirmation({{ $loan->id }})"
                                                class="w-full inline-flex justify-center items-center px-4 py-1.5 bg-amber-50 text-amber-600 hover:bg-amber-100 hover:text-amber-700 border border-amber-100 rounded-md text-xs font-bold transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                                </svg>
                                                Proses Pengembalian
                                            </button>
                                        @else
                                            {{-- Inline Form Konfirmasi --}}
                                            <div
                                                class="text-left bg-white border-2 border-amber-200 shadow-md rounded-xl p-4 min-w-[240px] z-10 relative">
                                                <div class="flex items-center gap-2 mb-2">
                                                    <div class="h-2 w-2 rounded-full bg-amber-500 animate-pulse"></div>
                                                    <p class="text-xs font-bold text-gray-800">Terima Alat (ID #{{ $loan->id }})</p>
                                                </div>
                                                <label
                                                    class="block text-[11px] font-medium text-gray-500 mb-1 uppercase tracking-wider">Catatan
                                                    Kondisi Alat</label>
                                                <textarea wire:model="conditionNotes" rows="2" placeholder="Cth: Kondisi baik..."
                                                    class="w-full text-xs border border-gray-300 rounded-lg px-3 py-2 mb-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition-all resize-none"></textarea>
                                                @error('conditionNotes')
                                                    <span class="text-red-500 text-[11px] block -mt-1 mb-2">{{ $message }}</span>
                                                @enderror

                                                <div class="flex gap-2">
                                                    <button wire:click="confirmReturn" wire:loading.attr="disabled"
                                                        class="flex-1 inline-flex justify-center items-center bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold py-1.5 px-2 rounded-md transition-colors disabled:opacity-50">
                                                        <span wire:loading.remove wire:target="confirmReturn">Terima Alat</span>
                                                        <span wire:loading wire:target="confirmReturn">
                                                            <svg class="animate-spin h-3 w-3 text-white"
                                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                                    stroke="currentColor" stroke-width="4"></circle>
                                                                <path class="opacity-75" fill="currentColor"
                                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                                </path>
                                                            </svg>
                                                        </span>
                                                    </button>
                                                    <button wire:click="cancelReturnConfirmation"
                                                        class="inline-flex justify-center items-center bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold py-1.5 px-3 rounded-md transition-colors">
                                                        Batal
                                                    </button>
                                                </div>
                                            </div>
                                        @endif
                                    @elseif($loan->status == 'returned')
                                        <div
                                            class="flex justify-center items-center text-emerald-600 text-xs font-bold bg-emerald-50 py-1.5 px-3 rounded-md border border-emerald-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                            Selesai
                                        </div>
                                    @elseif($loan->status == 'rejected')
                                        <div
                                            class="flex justify-center items-center text-rose-600 text-xs font-bold bg-rose-50 py-1.5 px-3 rounded-md border border-rose-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Ditolak
                                        </div>
                                    @else
                                        <div class="text-center">
                                            <span class="text-gray-400 text-xs italic">—</span>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="p-4 bg-gray-50 rounded-full mb-3">
                                            <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <p class="text-gray-500 text-sm">Belum ada data transaksi peminjaman alat.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Tambahkan Pagination jika diperlukan --}}
            @if(method_exists($loans, 'links'))
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                    {{ $loans->links() }}
                </div>
            @endif
        </div>
    </div>
</div>