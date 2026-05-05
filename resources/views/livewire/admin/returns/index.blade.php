<div>
    {{--
        return-index.blade.php — View milik ReturnIndex (Parent)
        Tanggung jawab: layout shell, header, flash, dan memanggil child components.
        Semua logika ada di masing-masing child.
    --}}

    {{-- ── Page Header ─────────────────────────────────────────────── --}}
    <x-slot name="header">
        <x-page-header
            title="Kelola Data Pengembalian"
            subtitle="Catat penerimaan alat dan kelola status denda"
            color="blue"
        >
            <x-slot name="icon">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="py-10 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        {{-- ── Flash Notification ──────────────────────────────────── --}}
        @if($flashMessage)
            <div
                wire:key="flash-{{ now()->timestamp }}"
                class="flex items-center justify-between gap-3 px-4 py-3 rounded-xl shadow-sm border
                    {{ $flashType === 'success'
                        ? 'bg-emerald-50 border-emerald-200 text-emerald-700'
                        : 'bg-red-50 border-red-200 text-red-700' }}"
            >
                <div class="flex items-center gap-3">
                    @if($flashType === 'success')
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    @endif
                    <span class="font-medium text-sm">{{ $flashMessage }}</span>
                </div>
                <button wire:click="dismissFlash" class="opacity-60 hover:opacity-100 transition-opacity">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        {{-- ── Child: Form (Create / Edit) ────────────────────────── --}}
        {{--
            ReturnForm adalah child component yang mengelola:
            - showForm, isEdit, field-field form
            - store(), update(), resetFields()
            - Setelah berhasil, dispatch('return-saved', ['message' => '...'])
        --}}
        @livewire('admin.returns.form')

        {{-- ── Child: Tabel + Search + Pagination ─────────────────── --}}
        {{--
            ReturnTable menerima $search dari parent via wire:model / @entangle.
            Atau bisa juga dispatch event 'search-updated' — pilih salah satu pola.
            Di sini kita pakai reactive property: parent yang pegang $search,
            child menerimanya sebagai @prop.
        --}}
        @livewire('admin.returns.table', ['search' => $search])

    </div>
</div>