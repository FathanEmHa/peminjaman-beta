<div>
    {{-- approval-table.blade.php — Child: tabel daftar transaksi --}}

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- Header Kartu --}}
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
            <a href="{{ route('petugas.laporan') }}" wire:navigate
                class="inline-flex justify-center items-center px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-900 transition-all shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Filter & Cetak Laporan
            </a>
        </div>

        {{-- Tabel --}}
        <div class="overflow-x-auto relative min-h-[200px]">
            <x-loading-overlay wire:loading
                wire:target="search, searchAlat, statusFilter, gotoPage, previousPage, nextPage"
                message="Menyaring data..." />

            <table class="w-full text-left whitespace-nowrap text-sm">
                <thead>
                    <tr
                        class="bg-gray-50 border-b border-gray-100 text-gray-500 uppercase tracking-wider text-xs font-semibold">
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
                                            {{ $item->asset->name }}
                                            <span
                                                class="text-xs font-bold text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded ml-1 border border-gray-200">x{{ $item->quantity }}</span>
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
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $loan->status_badge_class }} uppercase tracking-wider">
                                    {{ $loan->status_label }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <div class="flex flex-col items-center gap-2 w-full">

                                    {{-- Tombol Lihat Detail --}}
                                    <a href="{{ route('petugas.loans.detail', ['loan' => $loan->id, 'ref' => 'approval']) }}"
                                        wire:navigate.hover
                                        class="w-full inline-flex items-center justify-center px-3 py-1.5 bg-gray-50 text-gray-700 border border-gray-200 hover:bg-gray-100 rounded text-[11px] font-bold transition-colors uppercase tracking-wider">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Lihat Detail
                                    </a>

                                    {{-- Tombol Aksi per Status --}}
                                    <div class="flex items-center justify-center gap-2 w-full">

                                        @if($loan->status === 'pending')
                                            {{-- Dispatch ke ApprovalActionModal dengan mode 'approve_confirm' --}}
                                            <button
                                                wire:click="$dispatch('open-approval-modal', { loanId: {{ $loan->id }}, mode: 'approve_confirm' })"
                                                class="flex-1 inline-flex justify-center px-3 py-1.5 bg-emerald-50 text-emerald-600 border border-emerald-200 hover:bg-emerald-100 rounded text-xs font-bold transition-colors">
                                                Setujui
                                            </button>
                                            <button
                                                wire:click="$dispatch('open-approval-modal', { loanId: {{ $loan->id }}, mode: 'reject_form' })"
                                                class="flex-1 inline-flex justify-center px-3 py-1.5 bg-rose-50 text-rose-600 border border-rose-200 hover:bg-rose-100 rounded text-xs font-bold transition-colors">
                                                Tolak
                                            </button>

                                        @elseif($loan->status === 'approved')
                                            <button
                                                wire:click="$dispatch('open-approval-modal', { loanId: {{ $loan->id }}, mode: 'handover' })"
                                                class="flex-1 inline-flex justify-center px-2 py-1.5 bg-indigo-50 text-indigo-600 border border-indigo-200 hover:bg-indigo-100 rounded text-[11px] font-bold transition-colors">
                                                Serahkan Alat
                                            </button>
                                            <button
                                                wire:click="$dispatch('open-approval-modal', { loanId: {{ $loan->id }}, mode: 'cancel_confirm' })"
                                                class="flex-1 inline-flex justify-center px-2 py-1.5 bg-rose-50 text-rose-600 border border-rose-200 hover:bg-rose-100 rounded text-[11px] font-bold transition-colors">
                                                Batal
                                            </button>

                                        @elseif(in_array($loan->status, ['ongoing', 'overdue', 'returned']))
                                            <a href="{{ route('petugas.returns', ['search' => $loan->id]) }}"
                                                wire:navigate.hover
                                                class="w-full px-4 py-1.5 bg-amber-50 text-amber-600 border border-amber-200 hover:bg-amber-100 rounded text-[11px] font-bold transition-colors uppercase tracking-wider text-center block">
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
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500 text-sm">
                                Belum ada data transaksi yang sesuai filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if(method_exists($loans, 'links') && $loans->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $loans->links() }}
            </div>
        @endif
    </div>
</div>