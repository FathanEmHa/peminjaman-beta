<?php

namespace App\Livewire\Peminjam;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Loan;
use Illuminate\Support\Facades\DB;

class LoanHistory extends Component
{
    use WithPagination;

    public $search = '';
    public $status_filter = '';

    // Properti untuk Modal Info Denda
    public $showModal = false;
    public $selectedLoan = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $loans = Loan::with(['items.asset', 'return'])
            ->where('user_id', auth()->id())
            ->when($this->search, function ($query) {
                $query->whereHas('items.asset', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->status_filter, function ($query) {
                $query->where('status', $this->status_filter);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.peminjam.loan-history', compact('loans'))
            ->layout('layouts.app');
    }

    public function requestReturn(int $loanId): void
    {
        $loan = Loan::where('id', $loanId)
            ->where('user_id', auth()->id()) 
            ->whereIn('status', ['ongoing', 'overdue'])     
            ->firstOrFail();

        $loan->update(['status' => 'awaiting_return']);

        DB::table('activity_logs')->insert([
            'user_id' => auth()->id(),
            'action' => 'Mengajukan pengembalian alat (Peminjaman ID: #' . $loanId . ')',
            'created_at' => now(),
        ]);

        session()->flash('message', 'Permintaan pengembalian berhasil dikirim. Menunggu konfirmasi Petugas.');
    }

    // --- LOGIC MODAL ---
    public function openInfoModal($id)
    {
        $this->selectedLoan = Loan::with('return')->findOrFail($id);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedLoan = null;
    }
}