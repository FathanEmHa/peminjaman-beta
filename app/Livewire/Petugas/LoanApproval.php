<?php

namespace App\Livewire\Petugas;

use Livewire\Component;
use App\Models\Loan;
use Illuminate\Support\Facades\DB;

class LoanApproval extends Component
{
    // Property untuk input catatan kondisi alat saat konfirmasi pengembalian
    public string $conditionNotes = '';

    public ?int $confirmingReturnId = null;

    public function render()
    {
        // Ambil semua data peminjaman beserta relasi user dan barangnya
        $loans = Loan::with(['items.asset', 'user'])->latest()->get();
        return view('livewire.petugas.loan-approval', compact('loans'))
            ->layout('layouts.app');
    }

    public function approve($id)
    {
        $loan = Loan::findOrFail($id);
        $loan->update([
            'status' => 'approved',
            'approved_by' => auth()->id()
        ]);

        DB::table('activity_logs')->insert([
            'user_id' => auth()->id(),
            'action' => 'Menyetujui peminjaman (ID: #' . $id . ')',
            'created_at' => now(),
        ]);

        session()->flash('message', 'Peminjaman disetujui.');
    }

    public function reject($id)
    {
        $loan = Loan::findOrFail($id);
        $loan->update([
            'status' => 'rejected',
            'approved_by' => auth()->id()
        ]);

        DB::table('activity_logs')->insert([
            'user_id' => auth()->id(),
            'action' => 'Menolak peminjaman (ID: #' . $id . ')',
            'created_at' => now(),
        ]);

        session()->flash('message', 'Peminjaman ditolak.');
    }

    public function markOngoing($id)
    {
        $loan = Loan::findOrFail($id);
        $loan->update(['status' => 'ongoing']);

        // Kurangi stok alat karena fisik barang telah diserahkan (ongoing)
        foreach ($loan->items as $item) {
            $item->asset->decrement('stock', $item->quantity);
        }

        DB::table('activity_logs')->insert([
            'user_id' => auth()->id(),
            'action' => 'Menyerahkan alat ke peminjam (ID: #' . $id . ' - Ongoing)',
            'created_at' => now(),
        ]);

        session()->flash('message', 'Status diperbarui: Alat sedang dipinjam (Ongoing).');
    }

    /**
     * Tampilkan form konfirmasi pengembalian inline untuk loan tertentu.
     * Dipanggil saat Petugas klik tombol "Proses Pengembalian".
     */
    public function openReturnConfirmation(int $loanId): void
    {
        // Validasi: pastikan status memang awaiting_return
        $loan = Loan::where('id', $loanId)
            ->where('status', 'awaiting_return')
            ->firstOrFail();

        $this->confirmingReturnId = $loanId;
        $this->conditionNotes = ''; // Reset catatan
    }

    /**
     * Batalkan proses konfirmasi pengembalian (tutup form inline).
     */
    public function cancelReturnConfirmation(): void
    {
        $this->confirmingReturnId = null;
        $this->conditionNotes = '';
    }

    /**
     * Konfirmasi pengembalian oleh Petugas.
     * Hanya bisa dieksekusi jika loan berstatus 'awaiting_return'
     * (yang sudah diinisiasi oleh Peminjam sebelumnya).
     */
    public function confirmReturn(): void
    {
        $this->validate([
            'conditionNotes' => 'nullable|string|max:500',
        ]);

        $loan = Loan::where('id', $this->confirmingReturnId)
            ->where('status', 'awaiting_return') // Guard: hanya proses jika awaiting_return
            ->firstOrFail();

        DB::transaction(function () use ($loan) {
            // 1. Update status loan menjadi 'returned'
            $loan->update(['status' => 'returned']);

            // 2. Catat ke tabel returns
            DB::table('returns')->insert([
                'loan_id' => $loan->id,
                'returned_by' => $loan->user_id,
                'received_by' => auth()->id(),
                'return_date' => now()->toDateString(),
                'condition_notes' => $this->conditionNotes ?: 'Dikembalikan dalam kondisi baik',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 3. Kembalikan stok asset
            foreach ($loan->items as $item) {
                $item->asset->increment('stock', $item->quantity);
            }

            // 4. Log aktivitas
            DB::table('activity_logs')->insert([
                'user_id' => auth()->id(),
                'action' => 'Mengkonfirmasi pengembalian alat (Peminjaman ID: #' . $loan->id . ')',
                'created_at' => now(),
            ]);
        });

        $this->confirmingReturnId = null;
        $this->conditionNotes = '';

        session()->flash('message', 'Pengembalian dikonfirmasi. Stok alat telah diperbarui.');
    }
}