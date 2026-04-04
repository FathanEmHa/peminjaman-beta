<?php

namespace App\Livewire\Peminjam;

use Livewire\Component;
use App\Models\Loan;
use Illuminate\Support\Facades\DB;

class LoanHistory extends Component
{
    public function render()
    {
        // Ambil data peminjaman khusus untuk user yang sedang login
        $loans = Loan::with(['items.asset', 'return'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('livewire.peminjam.loan-history', compact('loans'));
    }

    /**
     * Inisiasi pengembalian oleh Peminjam.
     * Mengubah status loan dari 'ongoing' menjadi 'awaiting_return'.
     * Petugas akan mengkonfirmasi pengembalian ini selanjutnya.
     */
    public function requestReturn(int $loanId): void
    {
        $loan = Loan::where('id', $loanId)
            ->where('user_id', auth()->id()) // Pastikan loan milik user yang login
            ->where('status', 'ongoing')     // Hanya bisa diinisiasi saat ongoing
            ->firstOrFail();

        $loan->update(['status' => 'awaiting_return']);

        DB::table('activity_logs')->insert([
            'user_id' => auth()->id(),
            'action' => 'Mengajukan pengembalian alat (Peminjaman ID: #' . $loanId . ')',
            'created_at' => now(),
        ]);

        session()->flash('message', 'Permintaan pengembalian berhasil dikirim. Menunggu konfirmasi Petugas.');
    }
}