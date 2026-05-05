<div>
    {{--
        loan-form.blade.php — View milik LoanForm (Child)
        Hanya dirender saat $showForm === true.
        Selalu di-mount agar listener #[On] aktif.
    --}}

    @if($showForm)
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">

        {{-- ── Header Form ──────────────────────────────────────── --}}
        <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-100">
            <div class="flex items-center gap-2">
                <div class="p-1.5 bg-indigo-50 rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800">
                    {{ $isEdit ? 'Edit Peminjaman #' . $editId : 'Tambah Peminjaman Baru' }}
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
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            {{-- ── Kolom Kiri: Informasi Peminjaman ───────────── --}}
            <div class="space-y-5">
                <h4 class="font-semibold text-gray-800 text-sm border-l-4 border-indigo-500 pl-2">
                    Informasi Peminjaman
                </h4>

                {{-- Peminjam --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Peminjam <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="userId"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all bg-white">
                        <option value="">-- Pilih Peminjam --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('userId') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Disetujui Oleh --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Disetujui Oleh
                        <span class="text-gray-400 font-normal text-xs">(opsional)</span>
                    </label>
                    <select wire:model="approvedBy"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all bg-white">
                        <option value="">-- Belum Disetujui --</option>
                        @foreach($staffUsers as $staff)
                            <option value="{{ $staff->id }}">{{ $staff->name }} ({{ ucfirst($staff->role) }})</option>
                        @endforeach
                    </select>
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="status"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all bg-white">
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="ongoing">Ongoing</option>
                        <option value="returned">Returned</option>
                        <option value="rejected">Rejected</option>
                        <option value="overdue">Overdue</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                    @error('status') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Timeline --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Waktu Pinjam <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" wire:model="loanDate"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                        @error('loanDate') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Waktu Kembali <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" wire:model="returnDate"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                        @error('returnDate') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            {{-- ── Kolom Kanan: Daftar Alat / Cart ────────────── --}}
            <div class="space-y-5">
                <h4 class="font-semibold text-gray-800 text-sm border-l-4 border-emerald-500 pl-2">
                    Daftar Alat
                </h4>

                {{-- Input Cart (hanya mode Create) --}}
                @if(!$isEdit)
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 space-y-4">

                    {{-- Pilih Alat --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Alat</label>
                        <select wire:model="selectedAsset"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all bg-white">
                            <option value="">-- Pilih Alat --</option>
                            @foreach($assets as $asset)
                                <option value="{{ $asset->id }}">{{ $asset->name }} (Stok: {{ $asset->stock }})</option>
                            @endforeach
                        </select>
                        @error('selectedAsset') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- Jumlah + Tombol Tambah --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                        <div class="flex gap-2">
                            <input type="number" wire:model="quantity" min="1"
                                class="flex-1 border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                            <button wire:click="addToCart"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors shadow-sm">
                                Tambah
                            </button>
                        </div>
                        @error('quantity') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                </div>
                @endif

                {{-- Tabel Cart --}}
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 border-b border-gray-200 text-xs uppercase text-gray-500">
                            <tr>
                                <th class="px-4 py-3 font-semibold">Nama Alat</th>
                                <th class="px-4 py-3 font-semibold text-center w-20">Qty</th>
                                @if(!$isEdit)
                                    <th class="px-4 py-3 font-semibold text-center w-16">Hapus</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($cart as $index => $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-gray-800 font-medium">{{ $item['name'] }}</td>
                                    <td class="px-4 py-3 text-center text-gray-600 bg-gray-50/50">{{ $item['quantity'] }}</td>
                                    @if(!$isEdit)
                                        <td class="px-4 py-3 text-center">
                                            <button wire:click="removeFromCart({{ $index }})"
                                                class="p-1.5 bg-red-50 text-red-500 hover:bg-red-100 rounded-md transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $isEdit ? 2 : 3 }}" class="px-4 py-6 text-center text-gray-400 text-sm">
                                        Belum ada alat yang dipilih
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @error('cart') <span class="text-red-500 text-xs block">{{ $message }}</span> @enderror

                {{-- Catatan mode edit --}}
                @if($isEdit)
                    <div class="flex gap-2 p-3 bg-amber-50 rounded-lg border border-amber-100 text-amber-700 text-xs">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p>Edit hanya mengubah detail peminjaman. Untuk mengubah daftar alat, buat peminjaman baru.</p>
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
                    {{ $isEdit ? 'Simpan Perubahan' : 'Buat Peminjaman' }}
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
