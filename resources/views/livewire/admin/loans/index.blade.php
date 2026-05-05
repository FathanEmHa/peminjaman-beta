<div>
    {{--
        loan-index.blade.php — View milik LoanIndex (Parent)
        Tanggung jawab: layout shell, header, flash notification, invoke children.
    --}}

    {{-- ── Page Header ─────────────────────────────────────────────── --}}
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2.5 bg-blue-600 rounded-xl shadow-lg shadow-blue-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">Kelola Data Peminjaman</h2>
                <p class="text-sm font-medium text-gray-500 mt-0.5">Pantau status, setujui, dan rekam peminjaman alat</p>
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
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
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

        {{-- ── Child: Form Create / Edit ──────────────────────────── --}}
        {{--
            LoanForm mengelola:
            - showForm, isEdit, semua field form, cart
            - store(), update(), resetFields()
            - Dispatch 'loan-saved', 'loan-edit-opened', 'loan-form-closed'
        --}}
        @livewire('admin.loans.form')

        {{-- ── Child: Data Table ───────────────────────────────────── --}}
        {{--
            LoanTable menerima $search sebagai reactive prop.
            Setiap kali parent update $search, child otomatis re-render.
        --}}
        @livewire('admin.loans.table', ['search' => $search])

    </div>
</div>