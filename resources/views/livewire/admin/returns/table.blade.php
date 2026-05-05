<div>
    {{--
        return-table.blade.php — View milik ReturnTable (Child)
        Tanggung jawab: render tabel riwayat pengembalian, aksi edit & delete.
    --}}

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- ── Header Kartu: Search + Tombol Manual Entry ──────────── --}}
        <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Riwayat Pengembalian</h3>
                <p class="text-xs text-gray-500 mt-1">Master data seluruh aktivitas pengembalian alat.</p>
            </div>
            <div class="flex items-center gap-3 w-full md:w-auto">

                {{-- Search Input --}}
                <div class="relative w-full md:w-64">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Cari peminjam atau ID..."
                        class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                    >
                </div>

                {{-- Tombol Manual Entry → dispatch ke ReturnForm --}}
                <button
                    wire:click="$dispatch('open-return-create')"
                    class="shrink-0 bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg text-sm transition-colors flex items-center gap-2"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Manual Entry
                </button>

            </div>
        </div>

        {{-- ── Tabel ────────────────────────────────────────────────── --}}
        <div class="overflow-x-auto relative min-h-[200px]">
            <x-loading-overlay
                wire:loading
                wire:target="search, gotoPage, previousPage, nextPage"
                message="Memuat data..."
            />

            <table class="w-full text-left whitespace-nowrap text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-gray-500 uppercase tracking-wider text-xs font-semibold">
                        <th class="px-6 py-4 w-16">ID Ret</th>
                        <th class="px-6 py-4">Peminjam &amp; Alat</th>
                        <th class="px-6 py-4">Tgl Kembali</th>
                        <th class="px-6 py-4">Status Loan</th>
                        <th class="px-6 py-4">Diterima Oleh</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($returnRecords as $record)
                        <tr class="hover:bg-indigo-50/50 transition-colors group
                            {{ $editingId === $record->id ? 'bg-indigo-50/50 ring-1 ring-inset ring-indigo-200' : '' }}">

                            {{-- ID --}}
                            <td class="px-6 py-4 font-medium text-indigo-600">#{{ $record->id }}</td>

                            {{-- Peminjam & Alat --}}
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-900 mb-1">
                                    {{ $record->loan->user->name ?? '—' }}
                                    <span class="text-xs text-gray-500 font-normal">(Pinjam #{{ $record->loan_id }})</span>
                                </p>
                                <p class="text-xs text-gray-600 truncate max-w-[220px]"
                                   title="{{ $record->loan->items->map(fn($i) => $i->asset->name . ' (' . $i->quantity . ')')->implode(', ') }}">
                                    {{ $record->loan->items->map(fn($i) => $i->asset->name)->implode(', ') }}
                                </p>
                            </td>

                            {{-- Tanggal Kembali --}}
                            <td class="px-6 py-4 text-gray-700">
                                {{ \Carbon\Carbon::parse($record->return_date)->format('d M Y') }}
                            </td>

                            {{-- Status Loan (pakai accessor dari Model atau x-status-badge) --}}
                            <td class="px-6 py-4">
                                <x-status-badge :status="$record->loan->status" />
                            </td>

                            {{-- Diterima Oleh --}}
                            <td class="px-6 py-4">
                                <span class="text-gray-900">{{ $record->receivedByUser?->name ?? '—' }}</span>
                                @if($record->receivedByUser)
                                    <span class="block text-xs text-gray-500">{{ ucfirst($record->receivedByUser->role) }}</span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">

                                    {{-- Tombol Edit: delegate ke ReturnForm via event --}}
                                    <button
                                        wire:click="edit({{ $record->id }})"
                                        class="inline-flex items-center justify-center p-2 bg-amber-50 text-amber-600 hover:bg-amber-100 rounded-lg transition-colors"
                                        title="Edit"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>

                                    {{-- Tombol Delete: diproses di komponen ini --}}
                                    <button
                                        wire:click="delete({{ $record->id }})"
                                        wire:confirm="Hapus record pengembalian #{{ $record->id }}? Status peminjaman akan dikembalikan ke Ongoing/Overdue dan stok aset akan dikurangi kembali."
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
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 text-sm">
                                        @if($search)
                                            Tidak ditemukan data untuk "<strong>{{ $search }}</strong>".
                                        @else
                                            Belum ada riwayat pengembalian.
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ── Pagination ───────────────────────────────────────────── --}}
        @if($returnRecords->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $returnRecords->links() }}
            </div>
        @endif

    </div>
</div>
