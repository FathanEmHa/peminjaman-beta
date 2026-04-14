<?php

namespace App\Livewire\Petugas;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Loan;
use Illuminate\Support\Facades\DB;

class LoanApproval extends Component
{
    use WithPagination;

    public $search = '';
    public $searchAlat = '';
    public $status_filter = '';

    // Properti Form Return
    public string $conditionNotes = '';
    public ?int $confirmingReturnId = null;
    public ?int $damageFee = 0;
    public ?int $calculatedLateFee = 0;

    // Properti State Modal
    public bool $showReturnModal = false;
    public bool $showFineModal = false;
    public $selectedLoan = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSearchAlat()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $loans = Loan::with(['items.asset', 'user', 'return']) // Tambah relasi return
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

        // 1. Cek ketersediaan stok fisik langsung
        foreach ($loan->items as $item) {
            $asset = $item->asset;

            if ($item->quantity > $asset->stock) {
                session()->flash('error', "Gagal menyetujui! Stok '{$asset->name}' tidak cukup. (Sisa: {$asset->stock})");
                return;
            }
        }

        // 2. Kalau stok aman, update status dan kurangi stok pakai Transaction
        DB::transaction(function () use ($loan, $id) {
            $loan->update([
                'status' => 'approved',
                'approved_by' => auth()->id()
            ]);

            // Stok langsung dikurangi di sini
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

    public function markOngoing($id)
    {
        $loan = Loan::findOrFail($id);
        $loan->update(['status' => 'ongoing']);

        DB::table('activity_logs')->insert([
            'user_id' => auth()->id(),
            'action' => 'Menyerahkan alat ke peminjam (ID: #' . $id . ' - Ongoing)',
            'created_at' => now(),
        ]);

        session()->flash('message', 'Status diperbarui: Alat sedang dipinjam (Ongoing).');
    }

    // --- LOGIC MODAL PROSES PENGEMBALIAN ---
    public function openReturnConfirmation(int $loanId): void
    {
        $this->selectedLoan = Loan::with('user')->findOrFail($loanId);
        
        $this->confirmingReturnId = $loanId;
        $this->conditionNotes = '';
        $this->damageFee = 0;
        $this->calculatedLateFee = 0;

        if ($this->selectedLoan->return_date) {
            $expectedReturnDate = \Carbon\Carbon::parse($this->selectedLoan->return_date)->startOfDay();
            $today = now()->startOfDay();

            if ($today->greaterThan($expectedReturnDate)) {
                $diffInDays = $today->diffInDays($expectedReturnDate);
                $this->calculatedLateFee = $diffInDays * 5000;
            }
        }
        
        $this->showReturnModal = true;
    }

    public function cancelReturnConfirmation(): void
    {
        $this->confirmingReturnId = null;
        $this->conditionNotes = '';
        $this->damageFee = 0;
        $this->calculatedLateFee = 0;
        $this->selectedLoan = null;
        $this->showReturnModal = false;
    }

    public function confirmReturn(): void
    {
        $this->validate([
            'conditionNotes' => 'nullable|string|max:500',
            'damageFee' => 'nullable|numeric|min:0',
        ]);

        $loan = Loan::where('id', $this->confirmingReturnId)
            ->whereIn('status', ['awaiting_return', 'overdue'])
            ->firstOrFail();

        DB::transaction(function () use ($loan) {
            $loan->update(['status' => 'returned']);

            $late = (int) ($this->calculatedLateFee ?? 0);
            $damage = (int) ($this->damageFee ?? 0);
            $totalFine = $late + $damage;
            $fineStatus = ($totalFine > 0) ? 'unpaid' : 'none';

            DB::table('returns')->insert([
                'loan_id' => $loan->id,
                'returned_by' => $loan->user_id,
                'received_by' => auth()->id(),
                'return_date' => now()->toDateString(),
                'condition_notes' => $this->conditionNotes ?: 'Dikembalikan dalam kondisi baik',
                'late_fee' => $late,
                'damage_fee' => $damage,
                'fine_status' => $fineStatus,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($loan->items as $item) {
                $item->asset->increment('stock', $item->quantity);
            }

            DB::table('activity_logs')->insert([
                'user_id' => auth()->id(),
                'action' => 'Mengkonfirmasi pengembalian alat (Peminjaman ID: #' . $loan->id . ')',
                'created_at' => now(),
            ]);
        });

        $this->cancelReturnConfirmation();
        session()->flash('message', 'Pengembalian dikonfirmasi. Stok alat telah diperbarui.');
    }

    // --- LOGIC MODAL INFO DENDA ---
    public function openFineModal(int $loanId): void
    {
        $this->selectedLoan = Loan::with('return', 'user')->findOrFail($loanId);
        $this->showFineModal = true;
    }

    public function closeFineModal(): void
    {
        $this->showFineModal = false;
        $this->selectedLoan = null;
    }

    public function markFineAsPaid($returnId)
    {
        $returnRecord = \App\Models\ReturnModel::findOrFail($returnId);

        $returnRecord->update([
            'fine_status' => 'paid'
        ]);

        DB::table('activity_logs')->insert([
            'user_id' => auth()->id(),
            'action' => 'Melunasi denda pengembalian alat (Peminjaman ID: #' . $returnRecord->loan_id . ')',
            'created_at' => now(),
        ]);

        $this->closeFineModal();
        session()->flash('message', 'Status denda berhasil diperbarui menjadi Lunas.');
    }

    public function cancel($id)
    {
        $loan = Loan::with('items.asset')->findOrFail($id);

        // Pastikan hanya status pending dan approved yang bisa dibatalkan
        if (!in_array($loan->status, ['approved'])) {
            session()->flash('error', 'Peminjaman ini sudah tidak bisa dibatalkan (mungkin sedang ongoing atau sudah selesai).');
            return;
        }

        DB::transaction(function () use ($loan, $id) {
            // Kalau statusnya sudah approved (stok sudah terpotong), maka kembalikan stoknya
            if ($loan->status === 'approved') {
                foreach ($loan->items as $item) {
                    $item->asset->increment('stock', $item->quantity);
                }
            }

            // Update status menjadi cancelled dan catat siapa petugas yang membatalkan
            $loan->update([
                'status' => 'cancelled',
                'approved_by' => auth()->id() // Menyimpan ID petugas yang membatalkan
            ]);

            // Catat ke log aktivitas
            DB::table('activity_logs')->insert([
                'user_id' => auth()->id(),
                'action' => 'Petugas membatalkan peminjaman (ID: #' . $id . ')',
                'created_at' => now(),
            ]);
        });

        session()->flash('message', 'Peminjaman berhasil dibatalkan dan stok telah disesuaikan.');
    }
}