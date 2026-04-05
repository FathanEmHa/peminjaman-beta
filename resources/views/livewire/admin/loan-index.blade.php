<div>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-indigo-100 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
            </div>
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                Kelola Data Peminjaman
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

        {{-- PANEL FORM: CREATE / EDIT --}}
        @if($showForm)
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 transition-all">
                <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-100">
                    <div class="flex items-center gap-2">
                        <div class="p-1.5 bg-indigo-50 rounded-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">
                            {{ $isEdit ? 'Edit Peminjaman #' . $editId : 'Tambah Peminjaman Baru' }}
                        </h3>
                    </div>
                    <button wire:click="resetFields"
                        class="text-gray-400 hover:text-gray-600 transition-colors bg-gray-50 hover:bg-gray-100 p-2 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    {{-- Kolom Kiri: Detail Peminjaman --}}
                    <div class="space-y-5">
                        <h4 class="font-semibold text-gray-800 text-sm border-l-4 border-indigo-500 pl-2">Informasi
                            Peminjaman</h4>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Peminjam <span
                                    class="text-red-500">*</span></label>
                            <select wire:model="userId"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all bg-white">
                                <option value="">-- Pilih Peminjam --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('userId') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Disetujui Oleh <span
                                    class="text-gray-400 font-normal text-xs">(opsional)</span></label>
                            <select wire:model="approvedBy"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all bg-white">
                                <option value="">-- Belum Disetujui --</option>
                                @foreach($staffUsers as $staff)
                                    <option value="{{ $staff->id }}">{{ $staff->name }} ({{ ucfirst($staff->role) }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status <span
                                    class="text-red-500">*</span></label>
                            <select wire:model="status"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all bg-white">
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="ongoing">Ongoing</option>
                                <option value="awaiting_return">Menunggu Konfirmasi</option>
                                <option value="returned">Returned</option>
                                <option value="rejected">Rejected</option>
                            </select>
                            @error('status') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Pinjam <span
                                        class="text-red-500">*</span></label>
                                <input type="datetime-local" wire:model="loanDate"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                                @error('loanDate') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Kembali <span
                                        class="text-red-500">*</span></label>
                                <input type="datetime-local" wire:model="returnDate"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                                @error('returnDate') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Kolom Kanan: Daftar Alat --}}
                    <div class="space-y-5">
                        <h4 class="font-semibold text-gray-800 text-sm border-l-4 border-emerald-500 pl-2">Daftar Alat</h4>

                        @if(!$isEdit)
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Alat</label>
                                    <select wire:model="selectedAsset"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all bg-white">
                                        <option value="">-- Pilih Alat --</option>
                                        @foreach($assets as $asset)
                                            <option value="{{ $asset->id }}">{{ $asset->name }} (Stok: {{ $asset->stock }})</option>
                                        @endforeach
                                    </select>
                                    @error('selectedAsset') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

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
                                    @error('quantity') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        @endif

                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-gray-50 border-b border-gray-200 text-xs uppercase text-gray-500">
                                    <tr>
                                        <th class="px-4 py-3 font-semibold">Nama Alat</th>
                                        <th class="px-4 py-3 font-semibold text-center w-20">Qty</th>
                                        @if(!$isEdit)
                                            <th class="px-4 py-3 font-semibold text-center w-20">Aksi</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($cart as $index => $item)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-gray-800 font-medium">{{ $item['name'] }}</td>
                                            <td class="px-4 py-3 text-center text-gray-600 bg-gray-50/50">
                                                {{ $item['quantity'] }}</td>
                                            @if(!$isEdit)
                                                <td class="px-4 py-3 text-center">
                                                    <button wire:click="removeFromCart({{ $index }})"
                                                        class="p-1.5 bg-red-50 text-red-500 hover:bg-red-100 rounded-md transition-colors">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </td>
                                            @endif
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ $isEdit ? '2' : '3' }}"
                                                class="px-4 py-6 text-center text-gray-400 text-sm">
                                                Belum ada alat yang dipilih
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @error('cart') <span class="text-red-500 text-xs block">{{ $message }}</span> @enderror

                        @if($isEdit)
                            <div class="flex gap-2 p-3 bg-amber-50 rounded-lg border border-amber-100 text-amber-700 text-xs">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p>Edit hanya mengubah detail peminjaman. Untuk mengubah daftar alat, buat peminjaman baru.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mt-8 pt-5 border-t border-gray-100 flex items-center gap-3">
                    <button wire:click="{{ $isEdit ? 'update' : 'store' }}" wire:loading.attr="disabled"
                        class="inline-flex justify-center items-center px-5 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-200 transition-all disabled:opacity-70">
                        <span wire:loading.remove wire:target="{{ $isEdit ? 'update' : 'store' }}">
                            {{ $isEdit ? 'Simpan Perubahan' : 'Buat Peminjaman' }}
                        </span>
                        <span wire:loading wire:target="{{ $isEdit ? 'update' : 'store' }}" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                                </circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Menyimpan...
                        </span>
                    </button>
                    <button wire:click="resetFields"
                        class="inline-flex justify-center items-center px-5 py-2.5 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-gray-100 transition-all">
                        Batal
                    </button>
                </div>
            </div>
        @endif

        {{-- TABEL DATA PEMINJAMAN --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Data Peminjaman</h3>
                </div>
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <div class="relative w-full md:w-64">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari peminjam..."
                            class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                    </div>
                    @if(!$showForm)
                        <button wire:click="openCreateForm"
                            class="shrink-0 bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg text-sm transition-colors flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah
                        </button>
                    @endif
                </div>
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
                            <th class="px-6 py-4">Penyetuju</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($loans as $loan)
                            <tr
                                class="hover:bg-indigo-50/50 transition-colors group {{ $editId === $loan->id ? 'bg-indigo-50/50' : '' }}">
                                <td class="px-6 py-4 font-medium text-indigo-600">#{{ $loan->id }}</td>
                                <td class="px-6 py-4">
                                    <span class="font-medium text-gray-900">{{ $loan->user->name }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-normal min-w-[200px]">
                                    <ul class="space-y-1 text-gray-600">
                                        @foreach($loan->items as $item)
                                            <li class="flex items-center gap-2">
                                                <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                                                {{ $item->asset->name }} <span
                                                    class="font-medium text-gray-900">x{{ $item->quantity }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    <div class="flex items-center gap-2 mb-1">
                                        <svg class="h-4 w-4 text-emerald-500" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ \Carbon\Carbon::parse($loan->loan_date)->format('d M Y, H:i') }}
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="h-4 w-4 text-amber-500" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        {{ \Carbon\Carbon::parse($loan->return_date)->format('d M Y, H:i') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $loan->approver?->name ?? '—' }}
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
                                            'awaiting_return' => 'Menunggu Konfirmasi',
                                            'returned' => 'Returned',
                                            'rejected' => 'Rejected',
                                        ];
                                        $badgeClass = $badges[$loan->status] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                                        $label = $labels[$loan->status] ?? strtoupper($loan->status);
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border {{ $badgeClass }}">
                                        {{ $label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <button wire:click="edit({{ $loan->id }})"
                                            class="inline-flex items-center justify-center p-2 bg-amber-50 text-amber-600 hover:bg-amber-100 rounded-lg transition-colors"
                                            title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>
                                        <button wire:click="delete({{ $loan->id }})"
                                            wire:confirm="Yakin hapus peminjaman #{{ $loan->id }}? Tindakan ini tidak dapat dibatalkan."
                                            class="inline-flex items-center justify-center p-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-colors"
                                            title="Hapus">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="p-4 bg-gray-50 rounded-full mb-3">
                                            <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                            </svg>
                                        </div>
                                        <p class="text-gray-500 text-sm">
                                            @if($search) Tidak ditemukan data peminjaman untuk
                                            "<strong>{{ $search }}</strong>". @else Belum ada transaksi peminjaman. @endif
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