<?php

namespace App\Livewire\Peminjam;

use Livewire\Component;
use Livewire\WithPagination; // Wajib ditambahkan untuk fitur filter/halaman
use App\Models\Loan;
use Illuminate\Support\Facades\DB;

class LoanHistory extends Component
{
    use WithPagination;

    public $search = '';
    public $status_filter = '';

    // Reset ke halaman 1 kalau peminjam ngetik pencarian baru
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Reset ke halaman 1 kalau peminjam ganti status filter
    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $loans = Loan::with(['items.asset', 'return'])
            ->where('user_id', auth()->id()) // Pastikan hanya data miliknya sendiri
            ->when($this->search, function ($query) {
                // Pencarian berdasarkan nama alat yang dipinjam
                $query->whereHas('items.asset', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->status_filter, function ($query) {
                // Filter berdasarkan status
                $query->where('status', $this->status_filter);
            })
            ->latest()
            ->paginate(10); // Ubah dari take(3)->get() menjadi paginate()

        return view('livewire.peminjam.loan-history', compact('loans'))
            ->layout('layouts.app'); // Jangan lupa set layout
    }

    public function requestReturn(int $loanId): void
    {
        $loan = Loan::where('id', $loanId)
            ->where('user_id', auth()->id()) 
            ->where('status', 'ongoing')     
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