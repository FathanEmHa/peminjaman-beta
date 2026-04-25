<div>
    {{-- loan-approval.blade.php — Parent shell --}}

    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2.5 bg-indigo-600 rounded-xl shadow-lg shadow-indigo-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">Kelola Peminjaman Alat</h2>
                <p class="text-sm font-medium text-gray-500 mt-0.5">Tinjau, setujui, dan serahkan alat ke peminjam</p>
            </div>
        </div>
    </x-slot>

    <div class="py-10 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        {{-- ── Flash Notification ──────────────────────────────────── --}}
        @if($flashMessage)
            <div class="flex items-center justify-between gap-3 px-4 py-3 rounded-xl shadow-sm border
                {{ $flashType === 'success' ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'bg-red-50 border-red-200 text-red-700' }}">
                <div class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                        @if($flashType === 'success')
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        @else
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        @endif
                    </svg>
                    <span class="font-medium text-sm">{{ $flashMessage }}</span>
                </div>
                <button wire:click="dismissFlash" class="opacity-60 hover:opacity-100 transition-opacity ml-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        {{-- ── Search & Filter Bar ────────────────────────────────── --}}
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex flex-col sm:flex-row gap-4 justify-between items-center">
            {{-- Cari Nama Peminjam --}}
            <div class="w-full sm:w-1/3 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text"
                    class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 sm:text-sm transition"
                    placeholder="Cari nama peminjam...">
            </div>

            {{-- Cari Nama Alat --}}
            <div class="w-full sm:w-1/3 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="searchAlat" type="text"
                    class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 sm:text-sm transition"
                    placeholder="Cari nama alat...">
            </div>

            {{-- Filter Status --}}
            <div class="w-full sm:w-1/3">
                <select wire:model.live="statusFilter"
                    class="block w-full py-2 px-3 border border-gray-200 bg-gray-50 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:text-sm transition">
                    <option value="">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="ongoing">Ongoing</option>
                    <option value="returned">Returned</option>
                    <option value="rejected">Rejected</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
        </div>

        {{-- ── Child: Tabel ────────────────────────────────────────── --}}
        @livewire('petugas.approval-table', [
            'search'       => $search,
            'searchAlat'   => $searchAlat,
            'statusFilter' => $statusFilter,
        ])

        {{-- ── Child: Modal Aksi (always mounted, show dikontrol internal) ── --}}
        @livewire('petugas.approval-action-modal')

    </div>
</div>