<?php

namespace App\Livewire\Petugas;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Loan;
use Illuminate\Support\Facades\DB;

class LoanApproval extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $searchAlat = '';
    public $status_filter = '';

    // Properti Foto & State Modal Serah Terima
    public $photo_before;
    public bool $showHandoverModal = false; 
    public $handoverLoanId = null; 
    public $selectedLoan = null;

    public function updatingSearch() { $this->resetPage(); }
    public function updatingSearchAlat() { $this->resetPage(); }
    public function updatingStatusFilter() { $this->resetPage(); }

    public function render()
    {
        $loans = Loan::with(['items.asset', 'user', 'return'])
            ->when($this->search, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->searchAlat, function ($query) {
                $query->whereHas('items.asset', function ($q) {
                    $q->where('name', 'like', '%' . $this->searchAlat . '%');
                });
            })
            ->when($this->status_filter, function ($query) {
                $query->where('status', $this->status_filter);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.petugas.loan-approval', compact('loans'))
            ->layout('layouts.app');
    }

    public function approve($id)
    {
        $loan = Loan::with('items.asset')->findOrFail($id);

        foreach ($loan->items as $item) {
            $asset = $item->asset;
            if ($item->quantity > $asset->stock) {
                session()->flash('error', "Gagal menyetujui! Stok '{$asset->name}' tidak cukup. (Sisa: {$asset->stock})");
                return;
            }
        }

        DB::transaction(function () use ($loan, $id) {
            $loan->update([
                'status' => 'approved',
                'approved_by' => auth()->id()
            ]);

            foreach ($loan->items as $item) {
                $item->asset->decrement('stock', $item->quantity);
            }

            DB::table('activity_logs')->insert([
                'user_id' => auth()->id(),
                'action' => 'Menyetujui peminjaman & memotong stok (ID: #' . $id . ')',
                'created_at' => now(),
            ]);
        });

        session()->flash('message', 'Peminjaman disetujui dan stok berhasil dikurangi.');
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

    // --- LOGIC MODAL SERAH TERIMA ALAT ---
    public function openHandoverModal($id)
    {
        $this->handoverLoanId = $id;
        $this->selectedLoan = Loan::with('user')->findOrFail($id);
        $this->photo_before = null; 
        $this->showHandoverModal = true;
    }

    public function closeHandoverModal()
    {
        $this->showHandoverModal = false;
        $this->handoverLoanId = null;
        $this->selectedLoan = null;
        $this->photo_before = null;
    }

    public function confirmHandover()
    {
        $this->validate([
            'photo_before' => 'nullable|image|max:2048', 
        ]);

        $loan = Loan::findOrFail($this->handoverLoanId);
        
        $path = $this->photo_before ? $this->photo_before->store('peminjaman/before', 'public') : null;

        $loan->update([
            'status' => 'ongoing',
            'photo_before' => $path
        ]);

        DB::table('activity_logs')->insert([
            'user_id' => auth()->id(),
            'action' => 'Menyerahkan alat ke peminjam (ID: #' . $loan->id . ' - Ongoing)',
            'created_at' => now(),
        ]);

        $this->closeHandoverModal();
        session()->flash('message', 'Status diperbarui: Alat diserahkan dan foto disimpan.');
    }

    public function cancel($id)
    {
        $loan = Loan::with('items.asset')->findOrFail($id);

        if (!in_array($loan->status, ['approved'])) {
            session()->flash('error', 'Peminjaman ini sudah tidak bisa dibatalkan (mungkin sedang ongoing atau sudah selesai).');
            return;
        }

        DB::transaction(function () use ($loan, $id) {
            foreach ($loan->items as $item) {
                $item->asset->increment('stock', $item->quantity);
            }

            $loan->update([
                'status' => 'cancelled',
                'approved_by' => auth()->id() 
            ]);

            DB::table('activity_logs')->insert([
                'user_id' => auth()->id(),
                'action' => 'Petugas membatalkan peminjaman (ID: #' . $id . ')',
                'created_at' => now(),
            ]);
        });

        session()->flash('message', 'Peminjaman berhasil dibatalkan dan stok telah dikembalikan.');
    }
}