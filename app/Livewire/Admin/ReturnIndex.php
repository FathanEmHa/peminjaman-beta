<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\ReturnModel;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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

        // Loan yang bisa dibuatkan record pengembalian manual (awaiting_return atau ongoing)
        $eligibleLoans = Loan::with('user')
            ->whereIn('status', ['awaiting_return', 'ongoing'])
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

    /**
     * Buat record pengembalian secara manual (Admin override).
     * Mengubah status loan menjadi 'returned' dan mengembalikan stok.
     */
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
            // Buat record di tabel returns
            ReturnModel::create([
                'loan_id' => $loan->id,
                'returned_by' => $loan->user_id,
                'received_by' => $this->receivedBy,
                'return_date' => $this->returnDate,
                'condition_notes' => $this->conditionNotes ?: 'Dikembalikan dalam kondisi baik',
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

    /**
     * Load data pengembalian ke form untuk diedit.
     */
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

    /**
     * Update data pengembalian (tanggal, catatan, penerima).
     * Tidak mengubah stok — hanya koreksi data.
     */
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

    /**
     * Hapus record pengembalian.
     * Mengembalikan status loan ke 'awaiting_return' agar bisa diproses kembali.
     */
    public function delete(int $id): void
    {
        $record = ReturnModel::with('loan.items.asset')->findOrFail($id);
        $loan = $record->loan;

        DB::transaction(function () use ($record, $loan) {
            // Kurangi kembali stok (karena pengembalian dibatalkan)
            foreach ($loan->items as $item) {
                $item->asset->decrement('stock', $item->quantity);
            }

            // Kembalikan status loan ke awaiting_return (agar bisa diproses ulang)
            $loan->update(['status' => 'awaiting_return']);

            DB::table('activity_logs')->insert([
                'user_id' => auth()->id(),
                'action' => 'Admin menghapus record pengembalian (Peminjaman ID: #' . $loan->id . '). Status dikembalikan ke awaiting_return.',
                'created_at' => now(),
            ]);

            $record->delete();
        });

        session()->flash('message', 'Record pengembalian dihapus. Status peminjaman dikembalikan ke "Menunggu Konfirmasi".');
    }
}
