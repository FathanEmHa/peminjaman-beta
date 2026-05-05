<div>
    {{--
        loan-table.blade.php — View milik LoanTable (Child)
        Tanggung jawab: render tabel, aksi edit (dispatch ke LoanForm), delete.
    --}}

    {{-- Card Wrapper --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- ── Header Kartu: Search + Tombol Tambah ────────────────── --}}
        <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Data Peminjaman</h3>
            </div>
            <div class="flex items-center gap-3 w-full md:w-auto">

                {{-- Search: dikontrol parent via prop, tapi input ada di sini --}}
                {{-- Kita dispatch event ke parent agar $search parent ikut update --}}
                <div class="relative w-full md:w-64">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    {{--
                        Pola: child punya $search lokal yang disync dengan prop dari parent.
                        Karena Livewire 3 reactive props bersifat one-way (parent → child),
                        untuk two-way search kita manfaatkan wire:model lokal di LoanTable.
                        LoanTable punya $search sendiri yang diisi dari prop parent saat mount.
                    --}}
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Cari peminjam atau ID..."
                        class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                    >
                </div>

                {{-- Tombol Tambah: dispatch event agar LoanForm membuka form create --}}
                <button
                    wire:click="$dispatch('open-loan-create')"
                    class="shrink-0 bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg text-sm transition-colors flex items-center gap-2"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah
                </button>
            </div>
        </div>

        {{-- ── Tabel ────────────────────────────────────────────────── --}}
        <div class="overflow-x-auto relative min-h-[200px]">
            <x-loading-overlay wire:loading wire:target="search" message="Mencari data..." />

            <table class="w-full text-left whitespace-nowrap text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-gray-500 uppercase tracking-wider text-xs font-semibold">
                        <th class="px-6 py-4 w-16">ID</th>
                        <th class="px-6 py-4">Peminjam</th>
                        <th class="px-6 py-4">Alat (Qty)</th>
                        <th class="px-6 py-4">Timeline</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($loans as $loan)
                        <tr class="hover:bg-indigo-50/50 transition-colors group {{ $editingId === $loan->id ? 'bg-indigo-50/50 ring-1 ring-inset ring-indigo-200' : '' }}">

                            <td class="px-6 py-4 font-medium text-indigo-600">#{{ $loan->id }}</td>

                            <td class="px-6 py-4">
                                <span class="font-medium text-gray-900">{{ $loan->user->name }}</span>
                            </td>

                            <td class="px-6 py-4 whitespace-normal min-w-[200px]">
                                <ul class="space-y-1 text-gray-600">
                                    @foreach($loan->items as $item)
                                        <li class="flex items-center gap-2">
                                            <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                                            {{ $item->asset->name }}
                                            <span class="font-medium text-gray-900">x{{ $item->quantity }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                <div class="flex items-center gap-2 mb-1">
                                    <svg class="h-4 w-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ \Carbon\Carbon::parse($loan->loan_date)->format('d M Y, H:i') }}
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="h-4 w-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    {{ \Carbon\Carbon::parse($loan->return_date)->format('d M Y, H:i') }}
                                </div>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $loan->status_badge_class }} uppercase tracking-wider">
                                    {{ $loan->status_label }}
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">

                                    {{-- Link ke halaman pengembalian (jika status aktif) --}}
                                    @if(in_array($loan->status, ['ongoing', 'overdue', 'returned']))
                                        <a
                                            href="{{ route('admin.returns', ['q' => $loan->id]) }}"
                                            wire:navigate
                                            class="inline-flex items-center justify-center p-2 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-lg transition-colors"
                                            title="Kelola Pengembalian"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                    @endif

                                    {{-- Tombol Edit: dispatch event ke LoanForm --}}
                                    <button
                                        wire:click="$dispatch('open-loan-edit', { id: {{ $loan->id }} })"
                                        class="inline-flex items-center justify-center p-2 bg-amber-50 text-amber-600 hover:bg-amber-100 rounded-lg transition-colors"
                                        title="Edit"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>

                                    {{-- Tombol Delete: diproses di component ini --}}
                                    <button
                                        wire:click="delete({{ $loan->id }})"
                                        wire:confirm="Yakin hapus peminjaman #{{ $loan->id }}? Tindakan ini tidak dapat dibatalkan."
                                        class="inline-flex items-center justify-center p-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-colors"
                                        title="Hapus"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="p-4 bg-gray-50 rounded-full mb-3">
                                        <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 text-sm">
                                        @if($search)
                                            Tidak ditemukan data untuk "<strong>{{ $search }}</strong>".
                                        @else
                                            Belum ada transaksi peminjaman.
                                        @endif
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
