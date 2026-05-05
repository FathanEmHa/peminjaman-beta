<?php

namespace App\Livewire\Petugas\Returns;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Url;
use App\Models\Loan;
use Illuminate\Support\Facades\DB;

class Management extends Component
{
    use WithPagination, WithFileUploads;

    #[Url]
    public $search = '';
    public $searchAlat = '';
    public $status_filter = '';

    // Properti Form Return & Handover
    public string $conditionNotes = '';
    public ?int $confirmingReturnId = null;
    public ?int $damageFee = 0;
    public ?int $calculatedLateFee = 0;
    
    public $photo_after;

    // Properti State Modal
    public bool $showReturnModal = false;
    public bool $showFineModal = false;
    public $selectedLoan = null;

    public function updatingSearch() { $this->resetPage(); }
    public function updatingSearchAlat() { $this->resetPage(); }
    public function updatingStatusFilter() { $this->resetPage(); }

    public function render()
    {
        // HANYA TAMPILKAN STATUS ONGOING, OVERDUE, DAN RETURNED
        $loans = Loan::with(['items.asset', 'user', 'return'])
            ->whereIn('status', ['ongoing', 'overdue', 'returned'])
            ->when($this->search, function ($query) {
                // 3. UBAH LOGIC SEARCH BIAR BISA CARI NAMA ATAU ID TRANSAKSI
                $query->where(function($q) {
                    $q->whereHas('user', function ($u) {
                        $u->where('name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhere('id', str_replace('#', '', $this->search)); // Cari berdasarkan ID juga
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
            ->orderByRaw("FIELD(status, 'overdue', 'ongoing', 'returned')") 
            ->latest()
            ->paginate(10);

        return view('livewire.petugas.returns.management', compact('loans'))
            ->layout('layouts.app');
    }

    // --- LOGIC MODAL PROSES PENGEMBALIAN ---
    public function openReturnConfirmation(int $loanId): void
    {
        $this->selectedLoan = Loan::with('user')->findOrFail($loanId);
        $this->confirmingReturnId = $loanId;
        $this->conditionNotes = '';
        $this->damageFee = 0;
        $this->photo_after = null; 
        $this->calculatedLateFee = $this->selectedLoan->nominal_denda; 
        
        $this->showReturnModal = true;
    }

    public function cancelReturnConfirmation(): void
    {
        $this->confirmingReturnId = null;
        $this->conditionNotes = '';
        $this->damageFee = 0;
        $this->photo_after = null;
        $this->calculatedLateFee = 0;
        $this->selectedLoan = null;
        $this->showReturnModal = false;
    }

    public function confirmReturn(): void
    {
        $this->validate([
            'conditionNotes' => 'nullable|string|max:500',
            'damageFee' => 'nullable|numeric|min:0',
            'photo_after' => 'nullable|image|max:2048', 
        ]);

        $loan = Loan::where('id', $this->confirmingReturnId)
            ->whereIn('status', ['ongoing', 'overdue'])
            ->firstOrFail();

        $pathAfter = $this->photo_after ? $this->photo_after->store('peminjaman/after', 'public') : null;

        DB::transaction(function () use ($loan, $pathAfter) {
            $loan->update([
                'status' => 'returned',
                'photo_after' => $pathAfter 
            ]);

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
        session()->flash('message', 'Pengembalian dikonfirmasi. Alat diterima beserta fotonya.');
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
}