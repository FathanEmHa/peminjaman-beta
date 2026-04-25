<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\ReturnModel;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;

class ReturnIndex extends Component
{
    // ─── Form State ────────────────────────────────────────────────
    public bool $showForm = false;
    public bool $isEdit = false;
    public ?int $editId = null;

    // ─── Fields ────────────────────────────────────────────────────
    public ?int $loanId = null;
    public ?int $returnedBy = null;
    public ?int $receivedBy = null;
    public string $returnDate = '';
    public string $conditionNotes = '';

    // ─── Search ────────────────────────────────────────────────────
    #[Url]
    public string $search = '';

    public function render()
    {
        $returnRecords = ReturnModel::with([
            'loan.user',
            'loan.items.asset',
            'returnedByUser',
            'receivedByUser',
        ])
            ->when(
                $this->search,
                fn($q) =>
                $q->whereHas(
                    'loan.user',
                    fn($u) =>
                    $u->where('name', 'like', '%' . $this->search . '%')
                )->orWhereHas(
                        'loan',
                        fn($l) =>
                        $l->where('id', 'like', '%' . $this->search . '%')
                    )
            )
            ->latest()
            ->get();

        // Loan yang bisa dibuatkan record pengembalian manual (ongoing atau overdue)
        $eligibleLoans = Loan::with('user')
            ->whereIn('status', ['ongoing', 'overdue'])
            ->whereDoesntHave('return') // Belum punya record pengembalian
            ->orderBy('id')
            ->get();

        // Staff yang bisa jadi penerima alat
        $staffUsers = User::whereIn('role', ['petugas', 'admin'])->orderBy('name')->get();

        return view('livewire.admin.return-index', compact('returnRecords', 'eligibleLoans', 'staffUsers'))
            ->layout('layouts.app');
    }

    // ─── Form Toggle ───────────────────────────────────────────────

    public function openCreateForm(): void
    {
        $this->resetFields();
        $this->showForm = true;
        $this->isEdit = false;
        $this->returnDate = now()->toDateString();
        $this->receivedBy = auth()->id();
    }

    public function resetFields(): void
    {
        $this->reset([
            'loanId',
            'returnedBy',
            'receivedBy',
            'returnDate',
            'conditionNotes',
            'editId',
            'isEdit',
        ]);
        $this->showForm = false;
    }

    // ─── CRUD Operations ───────────────────────────────────────────

    public function store(): void
    {
        $this->validate([
            'loanId' => 'required|exists:loans,id',
            'receivedBy' => 'required|exists:users,id',
            'returnDate' => 'required|date',
            'conditionNotes' => 'nullable|string|max:500',
        ]);

        $loan = Loan::with('items.asset')->findOrFail($this->loanId);

        // Pastikan loan belum punya record pengembalian
        if ($loan->return()->exists()) {
            $this->addError('loanId', 'Peminjaman ini sudah memiliki record pengembalian.');
            return;
        }

        DB::transaction(function () use ($loan) {
            // Hitung keterlambatan (Jika Admin entry manual, default kita asumsikan gada denda, cuma lewat form petugas yg ada modalnya. Klo admin mau nambahin, dia bisa edit datanya atau pake modal petugas)
            $lateFee = 0;
            $expected = \Carbon\Carbon::parse($loan->return_date)->startOfDay();
            $actual = \Carbon\Carbon::parse($this->returnDate)->startOfDay();
            
            if ($actual->greaterThan($expected)) {
                $lateFee = $actual->diffInDays($expected) * 5000;
            }

            // Buat record di tabel returns
            ReturnModel::create([
                'loan_id' => $loan->id,
                'returned_by' => $loan->user_id,
                'received_by' => $this->receivedBy,
                'return_date' => $this->returnDate,
                'condition_notes' => $this->conditionNotes ?: 'Dikembalikan dalam kondisi baik (Entry Admin)',
                'late_fee' => $lateFee,
                'damage_fee' => 0,
                'fine_status' => $lateFee > 0 ? 'unpaid' : 'none',
            ]);

            // Update status loan
            $loan->update(['status' => 'returned']);

            // Kembalikan stok asset
            foreach ($loan->items as $item) {
                $item->asset->increment('stock', $item->quantity);
            }

            DB::table('activity_logs')->insert([
                'user_id' => auth()->id(),
                'action' => 'Admin membuat record pengembalian manual (Peminjaman ID: #' . $loan->id . ')',
                'created_at' => now(),
            ]);
        });

        $this->resetFields();
        session()->flash('message', 'Record pengembalian berhasil dibuat. Stok alat telah diperbarui.');
    }

    public function edit(int $id): void
    {
        $record = ReturnModel::findOrFail($id);

        $this->editId = $record->id;
        $this->loanId = $record->loan_id;
        $this->returnedBy = $record->returned_by;
        $this->receivedBy = $record->received_by;
        $this->returnDate = $record->return_date;
        $this->conditionNotes = $record->condition_notes ?? '';

        $this->isEdit = true;
        $this->showForm = true;
    }

    public function update(): void
    {
        $this->validate([
            'receivedBy' => 'required|exists:users,id',
            'returnDate' => 'required|date',
            'conditionNotes' => 'nullable|string|max:500',
        ]);

        $record = ReturnModel::findOrFail($this->editId);
        $record->update([
            'received_by' => $this->receivedBy,
            'return_date' => $this->returnDate,
            'condition_notes' => $this->conditionNotes,
        ]);

        DB::table('activity_logs')->insert([
            'user_id' => auth()->id(),
            'action' => 'Admin mengubah data pengembalian (ID: #' . $this->editId . ')',
            'created_at' => now(),
        ]);

        $this->resetFields();
        session()->flash('message', 'Data pengembalian berhasil diperbarui.');
    }

    public function delete(int $id): void
    {
        $record = ReturnModel::with('loan.items.asset')->findOrFail($id);
        $loan = $record->loan;

        DB::transaction(function () use ($record, $loan) {
            // Kurangi kembali stok (karena pengembalian dibatalkan)
            foreach ($loan->items as $item) {
                $item->asset->decrement('stock', $item->quantity);
            }

            // Cek apakah masih telat atau nggak berdasarkan loan_date dan return_date asli
            $now = now();
            $expected = \Carbon\Carbon::parse($loan->return_date);
            $newStatus = $now->greaterThan($expected) ? 'overdue' : 'ongoing';

            $loan->update(['status' => $newStatus]);

            DB::table('activity_logs')->insert([
                'user_id' => auth()->id(),
                'action' => 'Admin menghapus record pengembalian (Peminjaman ID: #' . $loan->id . '). Status dikembalikan ke ' . $newStatus . '.',
                'created_at' => now(),
            ]);

            $record->delete();
        });

        session()->flash('message', 'Record pengembalian dihapus. Status peminjaman dikembalikan.');
    }
}