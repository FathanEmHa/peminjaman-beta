<div>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-indigo-100 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                </svg>
            </div>
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                Kelola Data Pengembalian
            </h2>
        </div>
    </x-slot>

    <div class="py-10 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        {{-- Flash Message --}}
        @if (session()->has('message'))
            <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span class="font-medium text-sm">{{ session('message') }}</span>
            </div>
        @endif

        {{-- PANEL FORM: CREATE / EDIT --}}
        @if($showForm)
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 transition-all">
            <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <div class="p-1.5 bg-indigo-50 rounded-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">
                        {{ $isEdit ? 'Edit Record Pengembalian #' . $editId : 'Tambah Record Pengembalian' }}
                    </h3>
                </div>
                <button wire:click="resetFields" class="text-gray-400 hover:text-gray-600 transition-colors bg-gray-50 hover:bg-gray-100 p-2 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Kolom Kiri --}}
                <div class="space-y-5">
                    @if(!$isEdit)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Peminjaman <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="loanId" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all bg-white">
                            <option value="">-- Pilih Peminjaman --</option>
                            @forelse($eligibleLoans as $loan)
                                <option value="{{ $loan->id }}">
                                    #{{ $loan->id }} — {{ $loan->user->name }}
                                    ({{ $loan->items->map(fn($i) => $i->asset->name ?? '?')->implode(', ') }})
                                </option>
                            @empty
                                <option disabled>Tidak ada peminjaman yang siap dikembalikan</option>
                            @endforelse
                        </select>
                        @error('loanId') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror

                        @if(count($eligibleLoans) === 0)
                            <div class="flex gap-2 p-3 mt-3 bg-amber-50 rounded-lg border border-amber-100 text-amber-700 text-xs">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                <p>Tidak ada peminjaman aktif yang butuh pengembalian. Pastikan status peminjaman adalah <strong>Ongoing</strong> atau <strong>Menunggu Konfirmasi</strong>.</p>
                            </div>
                        @endif
                    </div>
                    @else
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Peminjaman</label>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-700 flex justify-between items-center">
                            <span>Peminjaman ID #{{ $loanId }}</span>
                            <span class="text-xs px-2 py-1 bg-gray-200 rounded-md text-gray-600">Read-only</span>
                        </div>
                    </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Diterima Oleh <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="receivedBy" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all bg-white">
                            <option value="">-- Pilih Penerima --</option>
                            @foreach($staffUsers as $staff)
                                <option value="{{ $staff->id }}">{{ $staff->name }} ({{ ucfirst($staff->role) }})</option>
                            @endforeach
                        </select>
                        @error('receivedBy') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Kolom Kanan --}}
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Tanggal Pengembalian Aktual <span class="text-red-500">*</span>
                        </label>
                        <input type="date" wire:model="returnDate" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                        @error('returnDate') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Kondisi Alat</label>
                        <textarea wire:model="conditionNotes" rows="3" placeholder="Contoh: Semua alat dikembalikan dalam kondisi baik." class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all resize-none"></textarea>
                        @error('conditionNotes') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    @if(!$isEdit)
                    <div class="flex gap-2 p-3 bg-blue-50 rounded-lg border border-blue-100 text-blue-700 text-xs">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <p>Menyimpan form ini otomatis merubah status peminjaman menjadi <strong>Returned</strong> dan mengembalikan stok barang.</p>
                    </div>
                    @else
                    <div class="flex gap-2 p-3 bg-amber-50 rounded-lg border border-amber-100 text-amber-700 text-xs">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        <p>Edit hanya mengubah data catatan. Stok alat dan status peminjaman tidak akan berubah ganda.</p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="mt-8 pt-5 border-t border-gray-100 flex items-center gap-3">
                <button wire:click="{{ $isEdit ? 'update' : 'store' }}" wire:loading.attr="disabled" class="inline-flex justify-center items-center px-5 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-200 transition-all disabled:opacity-70">
                    <span wire:loading.remove wire:target="{{ $isEdit ? 'update' : 'store' }}">
                        {{ $isEdit ? 'Simpan Perubahan' : 'Buat Record Pengembalian' }}
                    </span>
                    <span wire:loading wire:target="{{ $isEdit ? 'update' : 'store' }}" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Menyimpan...
                    </span>
                </button>
                <button wire:click="resetFields" class="inline-flex justify-center items-center px-5 py-2.5 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-gray-100 transition-all">
                    Batal
                </button>
            </div>
        </div>
        @endif

        {{-- TABEL RIWAYAT PENGEMBALIAN --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Riwayat Pengembalian</h3>
                    <p class="text-xs text-gray-500 mt-1">Master data seluruh aktivitas pengembalian alat.</p>
                </div>
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <div class="relative w-full md:w-64">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </div>
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari peminjam..." class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                    </div>
                    @if(!$showForm)
                    <button wire:click="openCreateForm" class="shrink-0 bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg text-sm transition-colors flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Manual
                    </button>
                    @endif
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-gray-500 uppercase tracking-wider text-xs font-semibold">
                            <th class="px-6 py-4 w-16">ID</th>
                            <th class="px-6 py-4">Peminjam & Alat</th>
                            <th class="px-6 py-4">Tgl Kembali</th>
                            <th class="px-6 py-4">Diterima Oleh</th>
                            <th class="px-6 py-4">Catatan</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($returnRecords as $record)
                        <tr class="hover:bg-indigo-50/50 transition-colors group {{ $editId === $record->id ? 'bg-indigo-50/50' : '' }}">
                            <td class="px-6 py-4 font-medium text-indigo-600">#{{ $record->id }}</td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-900 mb-1">{{ $record->loan->user->name ?? '—' }} <span class="text-xs text-gray-500 font-normal">(Pinjam #{{ $record->loan_id }})</span></p>
                                <p class="text-xs text-gray-600 truncate max-w-[200px]" title="{{ $record->loan->items->map(fn($i) => $i->asset->name . ' ('.$i->quantity.')')->implode(', ') }}">
                                    {{ $record->loan->items->map(fn($i) => $i->asset->name)->implode(', ') }}
                                </p>
                            </td>
                            <td class="px-6 py-4 text-gray-700">
                                {{ \Carbon\Carbon::parse($record->return_date)->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-gray-900">{{ $record->receivedByUser?->name ?? '—' }}</span>
                                @if($record->receivedByUser)
                                    <span class="block text-xs text-gray-500">{{ ucfirst($record->receivedByUser->role) }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($record->condition_notes)
                                    <span class="inline-block truncate max-w-[150px] text-gray-600" title="{{ $record->condition_notes }}">
                                        {{ $record->condition_notes }}
                                    </span>
                                @else
                                    <span class="text-gray-400 italic text-xs">Tidak ada catatan</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="edit({{ $record->id }})" class="inline-flex items-center justify-center p-2 bg-amber-50 text-amber-600 hover:bg-amber-100 rounded-lg transition-colors" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                    </button>
                                    <button wire:click="delete({{ $record->id }})" wire:confirm="Hapus record pengembalian #{{ $record->id }}? Status peminjaman akan dikembalikan." class="inline-flex items-center justify-center p-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-colors" title="Hapus">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="p-4 bg-gray-50 rounded-full mb-3">
                                        <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                                    </div>
                                    <p class="text-gray-500 text-sm">
                                        @if($search) Tidak ditemukan data untuk "<strong>{{ $search }}</strong>". @else Belum ada riwayat pengembalian. @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>