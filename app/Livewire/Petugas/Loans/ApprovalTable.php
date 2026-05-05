<?php

namespace App\Livewire\Petugas\Loans;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use App\Models\Loan;

/**
 * ApprovalTable — Child Component (SRP: Tabel + Pencarian lokal)
 *
 * Tanggung jawab:
 *  - Menerima filter dari parent sebagai reactive props
 *  - Me-render tabel daftar transaksi peminjaman dengan paginasi
 *  - Tombol aksi di tabel hanya dispatch event ke ApprovalActionModal
 *
 * Komunikasi:
 *  - Menerima prop: $search, $searchAlat, $statusFilter dari parent
 *  - Mengirim: dispatch('open-approval-modal', [loanId]) ke modal child
 *  - Listen: 'approval-processed' → reset ke halaman pertama & re-render
 */
class ApprovalTable extends Component
{
    use WithPagination;

    // ─── Reactive Props dari Parent ────────────────────────────────
    #[Reactive]
    public string $search       = '';

    #[Reactive]
    public string $searchAlat   = '';

    #[Reactive]
    public string $statusFilter = '';

    // ─── Reset page saat filter berubah ────────────────────────────
    public function updatedSearch(): void       { $this->resetPage(); }
    public function updatedSearchAlat(): void   { $this->resetPage(); }
    public function updatedStatusFilter(): void { $this->resetPage(); }

    /** Saat aksi approval selesai, refresh tabel kembali ke page 1. */
    #[On('approval-processed')]
    public function onApprovalProcessed(): void
    {
        $this->resetPage();
    }

    // ─── Render ────────────────────────────────────────────────────

    public function render()
    {
        $loans = Loan::with(['items.asset', 'user', 'return'])
            ->when($this->search, fn($q) =>
                $q->whereHas('user', fn($u) =>
                    $u->where('name', 'like', '%' . $this->search . '%')
                )
            )
            ->when($this->searchAlat, fn($q) =>
                $q->whereHas('items.asset', fn($a) =>
                    $a->where('name', 'like', '%' . $this->searchAlat . '%')
                )
            )
            ->when($this->statusFilter, fn($q) =>
                $q->where('status', $this->statusFilter)
            )
            ->latest()
            ->paginate(10);

        return view('livewire.petugas.loans.approval-table', compact('loans'));
    }
}
