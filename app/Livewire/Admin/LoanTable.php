<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Loan;
use Illuminate\Support\Facades\DB;

/**
 * LoanTable — Child Component (SRP: Tabel + Pagination + Delete)
 *
 * Tanggung jawab:
 *  - Menerima $search dari parent sebagai reactive prop
 *  - Me-render tabel data peminjaman
 *  - Menangani aksi delete (karena delete tidak butuh form)
 *  - Mendengarkan 'loan-saved' dari LoanForm → re-render otomatis
 *    (Livewire 3 me-render ulang setiap kali ada perubahan reactive prop)
 *
 * Komunikasi:
 *  - Menerima: prop $search dari parent
 *  - Mengirim: dispatch('loan-deleted') ke parent setelah delete
 *  - Mengirim: dispatch('open-loan-edit', [id]) ke LoanForm
 */
class LoanTable extends Component
{
    // ─── Reactive Prop dari Parent ─────────────────────────────────
    // Dideklarasikan public agar Livewire 3 bisa melewatkannya dari parent.
    // Setiap kali parent mengubah $search, komponen ini otomatis re-render.
    public string $search = '';

    // ─── State lokal: track baris yang sedang di-edit (highlight) ──
    // LoanForm akan dispatch 'loan-edit-opened' dengan id saat edit dimulai.
    public ?int $editingId = null;

    // ─── Listen dari LoanForm ───────────────────────────────────────

    /**
     * Saat LoanForm membuka edit, highlight baris yang sedang di-edit.
     * LoanForm dispatch: $this->dispatch('loan-edit-opened', id: $loanId)
     */
    #[On('loan-edit-opened')]
    public function onEditOpened(int $id): void
    {
        $this->editingId = $id;
    }

    /**
     * Saat form ditutup / selesai, reset highlight.
     * LoanForm dispatch: $this->dispatch('loan-form-closed')
     */
    #[On('loan-form-closed')]
    public function onFormClosed(): void
    {
        $this->editingId = null;
    }

    /**
     * Saat data berhasil disimpan, reset highlight.
     * Tabel re-render otomatis karena reactive prop.
     */
    #[On('loan-saved')]
    public function onLoanSaved(): void
    {
        $this->editingId = null;
    }

    // ─── Delete (dikelola di sini karena tidak butuh form) ─────────

    public function delete(int $id): void
    {
        $loan = Loan::findOrFail($id);
        $loan->delete();

        DB::table('activity_logs')->insert([
            'user_id'    => auth()->id(),
            'action'     => 'Admin menghapus data peminjaman (ID: #' . $id . ')',
            'created_at' => now(),
        ]);

        // Beritahu parent untuk tampilkan flash
        $this->dispatch('loan-deleted', message: 'Data peminjaman #' . $id . ' berhasil dihapus.');
    }

    // ─── Render ────────────────────────────────────────────────────

    public function render()
    {
        $loans = Loan::with(['items.asset', 'user', 'approver', 'return'])
            ->when(
                $this->search,
                fn($q) => $q
                    ->whereHas('user', fn($u) => $u->where('name', 'like', '%' . $this->search . '%'))
                    ->orWhere('id', 'like', '%' . $this->search . '%')
            )
            ->latest()
            ->get();

        return view('livewire.admin.loan-table', compact('loans'));
    }
}
