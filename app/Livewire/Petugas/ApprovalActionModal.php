<?php

namespace App\Livewire\Petugas;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use App\Models\Loan;
use Illuminate\Support\Facades\DB;

/**
 * ApprovalActionModal — Child Component (SRP: Semua Aksi Persetujuan)
 *
 * Tanggung jawab:
 *  - Approve → kurangi stok, ubah status ke 'approved'
 *  - Reject  → ubah status ke 'rejected', simpan catatan penolakan
 *  - Handover → upload foto, ubah status ke 'ongoing'
 *  - Cancel  → kembalikan stok, ubah status ke 'cancelled'
 *
 * Komunikasi:
 *  - Listen: 'open-approval-modal' dari ApprovalTable (terima loan_id)
 *  - Kirim: dispatch('approval-processed', [message, type]) ke parent + sibling
 *
 * Mode modal dikontrol oleh $mode:
 *  'approve_confirm' | 'reject_form' | 'handover' | 'cancel_confirm' | null
 */
class ApprovalActionModal extends Component
{
    use WithFileUploads;

    // ─── Modal State ───────────────────────────────────────────────
    public bool    $show   = false;
    public ?string $mode   = null;   // mode menentukan tampilan modal
    public ?int    $loanId = null;
    public ?Loan   $loan   = null;   // eager-loaded loan untuk tampilan

    // ─── Form Fields ───────────────────────────────────────────────

    /** Catatan wajib diisi hanya saat reject. */
    public string $rejectionNote = '';

    /** Upload foto opsional saat handover. */
    public $photoBefore;

    // ─── Listen: buka modal dari tabel ────────────────────────────

    /**
     * ApprovalTable mengirim:
     * $this->dispatch('open-approval-modal', loanId: $id, mode: 'reject_form')
     *
     * Mode yang valid:
     *  - 'approve_confirm' : konfirmasi sebelum approve
     *  - 'reject_form'     : form dengan input catatan penolakan
     *  - 'handover'        : form upload foto serah terima
     *  - 'cancel_confirm'  : konfirmasi sebelum batalkan
     */
    #[On('open-approval-modal')]
    public function openModal(int $loanId, string $mode): void
    {
        $this->reset(['rejectionNote', 'photoBefore']);
        $this->resetErrorBag();

        $this->loanId = $loanId;
        $this->mode   = $mode;
        $this->loan   = Loan::with('user', 'items.asset')->findOrFail($loanId);
        $this->show   = true;
    }

    public function closeModal(): void
    {
        $this->show   = false;
        $this->mode   = null;
        $this->loanId = null;
        $this->loan   = null;
        $this->reset(['rejectionNote', 'photoBefore']);
    }

    // ─── Aksi: Approve ─────────────────────────────────────────────

    public function approve(): void
    {
        $loan = Loan::with('items.asset')->findOrFail($this->loanId);

        // Validasi stok sebelum approve
        foreach ($loan->items as $item) {
            if ($item->quantity > $item->asset->stock) {
                $this->dispatch('approval-processed',
                    message: "Gagal! Stok '{$item->asset->name}' tidak cukup. (Sisa: {$item->asset->stock})",
                    type: 'error'
                );
                $this->closeModal();
                return;
            }
        }

        DB::transaction(function () use ($loan) {
            $loan->update([
                'status'      => 'approved',
                'approved_by' => auth()->id(),
            ]);

            foreach ($loan->items as $item) {
                $item->asset->decrement('stock', $item->quantity);
            }

            DB::table('activity_logs')->insert([
                'user_id'    => auth()->id(),
                'action'     => 'Menyetujui peminjaman & memotong stok (ID: #' . $loan->id . ')',
                'created_at' => now(),
            ]);
        });

        $this->closeModal();
        $this->dispatch('approval-processed',
            message: 'Peminjaman #' . $loan->id . ' disetujui dan stok berhasil dikurangi.',
            type: 'success'
        );
    }

    // ─── Aksi: Reject ──────────────────────────────────────────────

    public function reject(): void
    {
        $this->validate([
            'rejectionNote' => 'nullable|string|max:500',
        ], [
            'rejectionNote.max' => 'Catatan penolakan maksimal 500 karakter.',
        ]);

        $loan = Loan::findOrFail($this->loanId);
        $loan->update([
            'status'      => 'rejected',
            'approved_by' => auth()->id(),
        ]);

        // Simpan catatan penolakan ke activity log (bisa juga ke kolom khusus jika ada)
        DB::table('activity_logs')->insert([
            'user_id'    => auth()->id(),
            'action'     => 'Menolak peminjaman (ID: #' . $loan->id . ')'
                            . ($this->rejectionNote ? ' — Catatan: ' . $this->rejectionNote : ''),
            'created_at' => now(),
        ]);

        $this->closeModal();
        $this->dispatch('approval-processed',
            message: 'Peminjaman #' . $loan->id . ' telah ditolak.',
            type: 'success'
        );
    }

    // ─── Aksi: Handover (Serah Terima → Ongoing) ───────────────────

    public function confirmHandover(): void
    {
        $this->validate([
            'photoBefore' => 'nullable|image|max:2048',
        ]);

        $loan = Loan::findOrFail($this->loanId);
        $path = $this->photoBefore
            ? $this->photoBefore->store('peminjaman/before', 'public')
            : null;

        $loan->update([
            'status'       => 'ongoing',
            'photo_before' => $path,
        ]);

        DB::table('activity_logs')->insert([
            'user_id'    => auth()->id(),
            'action'     => 'Menyerahkan alat ke peminjam (ID: #' . $loan->id . ' - Ongoing)',
            'created_at' => now(),
        ]);

        $this->closeModal();
        $this->dispatch('approval-processed',
            message: 'Alat diserahkan. Status peminjaman #' . $loan->id . ' menjadi Ongoing.',
            type: 'success'
        );
    }

    // ─── Aksi: Cancel (batalkan yang sudah Approved) ───────────────

    public function cancel(): void
    {
        $loan = Loan::with('items.asset')->findOrFail($this->loanId);

        if ($loan->status !== 'approved') {
            $this->dispatch('approval-processed',
                message: 'Peminjaman ini tidak bisa dibatalkan karena statusnya bukan Approved.',
                type: 'error'
            );
            $this->closeModal();
            return;
        }

        DB::transaction(function () use ($loan) {
            foreach ($loan->items as $item) {
                $item->asset->increment('stock', $item->quantity);
            }

            $loan->update([
                'status'      => 'cancelled',
                'approved_by' => auth()->id(),
            ]);

            DB::table('activity_logs')->insert([
                'user_id'    => auth()->id(),
                'action'     => 'Petugas membatalkan peminjaman (ID: #' . $loan->id . '). Stok dikembalikan.',
                'created_at' => now(),
            ]);
        });

        $this->closeModal();
        $this->dispatch('approval-processed',
            message: 'Peminjaman #' . $loan->id . ' dibatalkan dan stok telah dikembalikan.',
            type: 'success'
        );
    }

    // ─── Render ────────────────────────────────────────────────────

    public function render()
    {
        return view('livewire.petugas.approval-action-modal');
    }
}
