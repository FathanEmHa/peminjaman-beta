<?php

namespace App\Livewire\Admin\Returns;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Models\ReturnModel;
use App\Models\Loan;
use Illuminate\Support\Facades\DB;

/**
 * ReturnTable — Child Component (SRP: Tabel + Pagination + Delete)
 *
 * Tanggung jawab:
 *  - Menerima $search dari parent sebagai reactive prop
 *  - Me-render tabel riwayat pengembalian dengan paginasi
 *  - Aksi delete (tidak butuh form, cukup di sini)
 *  - Dispatch 'open-return-edit' ke ReturnForm saat tombol Edit diklik
 *
 * Komunikasi:
 *  - Menerima prop: $search dari ReturnIndex parent
 *  - Listen: 'return-saved'       → resetPage agar data fresh
 *  - Listen: 'return-edit-opened' → highlight baris yang sedang di-edit
 *  - Listen: 'return-form-closed' → clear highlight
 *  - Kirim:  dispatch('return-deleted') ke parent setelah delete sukses
 */
class Table extends Component
{
    use WithPagination;

    // ─── Reactive Prop dari Parent ─────────────────────────────────
    public string $search = '';

    // ─── State lokal: highlight baris yang sedang di-edit ──────────
    public ?int $editingId = null;

    // ─── Reset page saat search berubah ────────────────────────────
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    // ─── Listen dari ReturnForm ─────────────────────────────────────

    /** Saat record berhasil disimpan, refresh tabel ke halaman pertama. */
    #[On('return-saved')]
    public function onReturnSaved(): void
    {
        $this->editingId = null;
        $this->resetPage();
    }

    /** Saat ReturnForm membuka edit, highlight baris terkait. */
    #[On('return-edit-opened')]
    public function onEditOpened(int $id): void
    {
        $this->editingId = $id;
    }

    /** Saat form ditutup, hapus highlight. */
    #[On('return-form-closed')]
    public function onFormClosed(): void
    {
        $this->editingId = null;
    }

    // ─── Edit: Delegate ke ReturnForm via event ─────────────────────

    public function edit(int $id): void
    {
        // ReturnForm listen 'open-return-edit' via #[On]
        $this->dispatch('open-return-edit', id: $id);
    }

    // ─── Delete ─────────────────────────────────────────────────────

    public function delete(int $id): void
    {
        $record = ReturnModel::with('loan.items.asset')->findOrFail($id);
        $loan   = $record->loan;

        DB::transaction(function () use ($record, $loan) {
            // Kurangi stok kembali karena pengembalian dibatalkan
            foreach ($loan->items as $item) {
                $item->asset->decrement('stock', $item->quantity);
            }

            // Kembalikan status loan ke ongoing/overdue berdasarkan tanggal
            $newStatus = now()->greaterThan($loan->return_date) ? 'overdue' : 'ongoing';
            $loan->update(['status' => $newStatus]);

            DB::table('activity_logs')->insert([
                'user_id'    => auth()->id(),
                'action'     => 'Admin menghapus record pengembalian (Peminjaman ID: #' . $loan->id
                                . '). Status dikembalikan ke ' . $newStatus . '.',
                'created_at' => now(),
            ]);

            $record->delete();
        });

        // Beritahu parent untuk tampilkan flash
        $this->dispatch('return-deleted',
            message: 'Record pengembalian #' . $id . ' dihapus. Status peminjaman dikembalikan.'
        );
    }

    // ─── Render ────────────────────────────────────────────────────

    public function render()
    {
        $returnRecords = ReturnModel::with([
            'loan.user',
            'loan.items.asset',
            'returnedByUser',
            'receivedByUser',
        ])
        ->when($this->search, fn($q) =>
            $q->whereHas('loan.user', fn($u) =>
                $u->where('name', 'like', '%' . $this->search . '%')
            )->orWhereHas('loan', fn($l) =>
                $l->where('id', 'like', '%' . $this->search . '%')
            )
        )
        ->latest()
        ->paginate(10);

        return view('livewire.admin.returns.table', compact('returnRecords'));
    }
}
