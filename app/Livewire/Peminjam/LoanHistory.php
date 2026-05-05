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

    public $showRejectionModal = false;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function openRejectionModal($id)
    {
        $this->selectedLoan = Loan::findOrFail($id);
        $this->showRejectionModal = true;
    }

    public function closeRejectionModal()
    {
        $this->showRejectionModal = false;
        // Hanya kosongkan selectedLoan jika showModal (Denda) juga sedang false
        if (!$this->showModal) {
            $this->selectedLoan = null;
        }
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

    public function cancelLoan($id)
    {
        // Pastikan user cuma bisa ngebatalin peminjaman milik dia sendiri
        $loan = Loan::with('items.asset')->where('user_id', auth()->id())->findOrFail($id);

        // Proteksi ganda (backend validation) biar user gak iseng inject script pas statusnya ongoing
        if (!in_array($loan->status, ['pending', 'approved'])) {
            session()->flash('error', 'Peminjaman ini sudah tidak bisa dibatalkan.');
            return;
        }

        DB::transaction(function () use ($loan) {
            // Kalau statusnya udah approved, balikin stoknya!
            if ($loan->status === 'approved') {
                foreach ($loan->items as $item) {
                    $item->asset->increment('stock', $item->quantity);
                }
            }

            // Ubah status jadi cancelled
            $loan->update(['status' => 'cancelled']);

            DB::table('activity_logs')->insert([
                'user_id' => auth()->id(),
                'action' => 'User membatalkan peminjaman (ID: #' . $loan->id . ')',
                'created_at' => now(),
            ]);
        });

        session()->flash('message', 'Peminjaman berhasil dibatalkan.');
    }
}