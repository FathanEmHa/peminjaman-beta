<?php

namespace App\Livewire\Petugas;

use Livewire\Component;
use Livewire\WithPagination; // Tambahkan ini buat fitur halaman (pagination)
use App\Models\Loan;
use Illuminate\Support\Facades\DB;

class LoanApproval extends Component
{
    use WithPagination; // Aktifkan pagination

    // Properti untuk fitur Search & Filter
    public $search = '';
    public $status_filter = '';

    // Property untuk input catatan kondisi alat saat konfirmasi pengembalian
    public string $conditionNotes = '';
    public ?int $confirmingReturnId = null;

    // Reset ke halaman 1 kalau petugas ngetik pencarian baru
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Reset ke halaman 1 kalau petugas ganti dropdown filter
    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Query dimodifikasi buat nangkep input search & filter
        $loans = Loan::with(['items.asset', 'user'])
            ->when($this->search, function ($query) {
                // Cari berdasarkan nama peminjam
                $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->status_filter, function ($query) {
                // Filter berdasarkan status
                $query->where('status', $this->status_filter);
            })
            ->latest()
            ->paginate(10); // Pakai paginate, bukan get()

        return view('livewire.petugas.loan-approval', compact('loans'))
            ->layout('layouts.app');
    }

    // =======================================================
    // LOGIC LU DI BAWAH INI TETAP AMAN DAN GAK GUE UBAH-UBAH
    // =======================================================

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

    public function openReturnConfirmation(int $loanId): void
    {
        // Validasi: pastikan status memang awaiting_return
        $loan = Loan::where('id', $loanId)
            ->where('status', 'awaiting_return')
            ->firstOrFail();

        $this->confirmingReturnId = $loanId;
        $this->conditionNotes = ''; // Reset catatan
    }

    public function cancelReturnConfirmation(): void
    {
        $this->confirmingReturnId = null;
        $this->conditionNotes = '';
    }

    public function confirmReturn(): void
    {
        $this->validate([
            'conditionNotes' => 'nullable|string|max:500',
        ]);

        $loan = Loan::where('id', $this->confirmingReturnId)
            ->where('status', 'awaiting_return') 
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