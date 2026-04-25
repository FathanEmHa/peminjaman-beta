<div>
    {{--
        return-form.blade.php — View milik ReturnForm (Child)
        Selalu di-mount agar listener #[On] tetap aktif.
        Tampilan dikontrol oleh $showForm internal.
    --}}

    @if($showForm)
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">

        {{-- ── Header Form ──────────────────────────────────────── --}}
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
            <button wire:click="resetForm"
                class="text-gray-400 hover:text-gray-600 transition-colors bg-gray-50 hover:bg-gray-100 p-2 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- ── Dua Kolom ────────────────────────────────────────── --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            {{-- ── Kolom Kiri ──────────────────────────────────── --}}
            <div class="space-y-5">

                {{-- Peminjaman (dropdown → mode create | read-only → mode edit) --}}
                @if(!$isEdit)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Peminjaman <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="loanId"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all bg-white">
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

                        @if($eligibleLoans->isEmpty())
                            <div class="flex gap-2 p-3 mt-3 bg-amber-50 rounded-lg border border-amber-100 text-amber-700 text-xs">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <p>Tidak ada peminjaman aktif. Pastikan status adalah <strong>Ongoing</strong> atau <strong>Overdue</strong>.</p>
                            </div>
                        @endif
                    </div>
                @else
                    {{-- Mode Edit: Peminjaman read-only --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Peminjaman</label>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-700 flex justify-between items-center">
                            <span>Peminjaman ID #{{ $loanId }}</span>
                            <span class="text-xs px-2 py-1 bg-gray-200 rounded-md text-gray-600">Read-only</span>
                        </div>
                    </div>
                @endif

                {{-- Diterima Oleh --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Diterima Oleh <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="receivedBy"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all bg-white">
                        <option value="">-- Pilih Penerima --</option>
                        @foreach($staffUsers as $staff)
                            <option value="{{ $staff->id }}">{{ $staff->name }} ({{ ucfirst($staff->role) }})</option>
                        @endforeach
                    </select>
                    @error('receivedBy') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- ── Kolom Kanan ─────────────────────────────────── --}}
            <div class="space-y-5">

                {{-- Tanggal Pengembalian Aktual --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Tanggal Pengembalian Aktual <span class="text-red-500">*</span>
                    </label>
                    <input type="date" wire:model="returnDate"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                    @error('returnDate') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Catatan Kondisi --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Kondisi Alat</label>
                    <textarea wire:model="conditionNotes" rows="3"
                        placeholder="Contoh: Semua alat dikembalikan dalam kondisi baik."
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all resize-none">
                    </textarea>
                    @error('conditionNotes') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Info Box per mode --}}
                @if(!$isEdit)
                    <div class="flex gap-2 p-3 bg-blue-50 rounded-lg border border-blue-100 text-blue-700 text-xs">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p>Menyimpan form ini otomatis mengubah status peminjaman menjadi <strong>Returned</strong> dan mengembalikan stok alat ke inventaris.</p>
                    </div>
                @else
                    <div class="flex gap-2 p-3 bg-amber-50 rounded-lg border border-amber-100 text-amber-700 text-xs">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <p>Edit hanya mengubah data catatan & tanggal. Stok alat dan status peminjaman <strong>tidak akan berubah</strong>.</p>
                    </div>
                @endif

            </div>
        </div>

        {{-- ── Footer Tombol ────────────────────────────────────── --}}
        <div class="mt-8 pt-5 border-t border-gray-100 flex items-center gap-3">
            <button
                wire:click="{{ $isEdit ? 'update' : 'store' }}"
                wire:loading.attr="disabled"
                class="inline-flex justify-center items-center px-5 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-200 transition-all disabled:opacity-70"
            >
                <span wire:loading.remove wire:target="{{ $isEdit ? 'update' : 'store' }}">
                    {{ $isEdit ? 'Simpan Perubahan' : 'Buat Record Pengembalian' }}
                </span>
                <span wire:loading wire:target="{{ $isEdit ? 'update' : 'store' }}" class="flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Menyimpan...
                </span>
            </button>
            <button wire:click="resetForm"
                class="inline-flex justify-center items-center px-5 py-2.5 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-gray-100 transition-all">
                Batal
            </button>
        </div>

    </div>
    @endif
</div>
