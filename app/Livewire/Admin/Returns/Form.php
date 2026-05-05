<?php

namespace App\Livewire\Admin\Returns;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\ReturnModel;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * ReturnForm — Child Component (SRP: Create & Edit Return Record)
 *
 * Tanggung jawab:
 *  - Listen 'open-return-create' → reset form, buka mode create
 *  - Listen 'open-return-edit'   → load record ke form, mode edit
 *  - store()  → validasi, hitung denda terlambat, buat record returns,
 *               ubah status loan → 'returned', kembalikan stok aset
 *  - update() → update catatan & tanggal saja (tanpa ubah stok)
 *  - Setelah selesai → dispatch('return-saved') ke parent & ReturnTable
 *
 * Event yang dikirim:
 *  - 'return-saved'       → parent (flash) + ReturnTable (refresh)
 *  - 'return-form-closed' → ReturnTable (reset highlight)
 *
 * Event yang didengar:
 *  - 'open-return-create' → dari tombol "Manual Entry" di ReturnTable
 *  - 'open-return-edit'   → dari tombol Edit di ReturnTable
 */
class Form extends Component
{
    // ─── Visibility ────────────────────────────────────────────────
    public bool $showForm = false;
    public bool $isEdit   = false;
    public ?int $editId   = null;

    // ─── Form Fields ───────────────────────────────────────────────
    public ?int    $loanId         = null;
    public ?int    $receivedBy     = null;
    public string  $returnDate     = '';
    public string  $conditionNotes = '';

    // ─── Listen: buka form Create ──────────────────────────────────

    /**
     * ReturnTable blade dispatch:
     * $dispatch('open-return-create')
     */
    #[On('open-return-create')]
    public function openCreate(): void
    {
        $this->resetForm();
        $this->returnDate  = now()->toDateString();
        $this->receivedBy  = auth()->id();  // pre-fill dengan user yang login
        $this->showForm    = true;
        $this->isEdit      = false;
    }

    // ─── Listen: buka form Edit ────────────────────────────────────

    /**
     * ReturnTable blade dispatch:
     * $dispatch('open-return-edit', { id: recordId })
     */
    #[On('open-return-edit')]
    public function openEdit(int $id): void
    {
        $record = ReturnModel::findOrFail($id);

        $this->editId         = $record->id;
        $this->loanId         = $record->loan_id;
        $this->receivedBy     = $record->received_by;
        $this->returnDate     = $record->return_date;
        $this->conditionNotes = $record->condition_notes ?? '';

        $this->isEdit   = true;
        $this->showForm = true;

        // Beritahu ReturnTable untuk highlight baris
        $this->dispatch('return-edit-opened', id: $id);
    }

    // ─── Reset / Tutup ─────────────────────────────────────────────

    public function resetForm(): void
    {
        $this->reset([
            'loanId', 'receivedBy', 'returnDate', 'conditionNotes',
            'editId', 'isEdit',
        ]);
        $this->showForm = false;
        $this->resetErrorBag();

        $this->dispatch('return-form-closed');
    }

    // ─── Store (Create) ────────────────────────────────────────────

    public function store(): void
    {
        $this->validate([
            'loanId'         => 'required|exists:loans,id',
            'receivedBy'     => 'required|exists:users,id',
            'returnDate'     => 'required|date',
            'conditionNotes' => 'nullable|string|max:500',
        ], [
            'loanId.required'     => 'Pilih peminjaman terlebih dahulu.',
            'receivedBy.required' => 'Penerima barang harus dipilih.',
            'returnDate.required' => 'Tanggal pengembalian wajib diisi.',
        ]);

        $loan = Loan::with('items.asset')->findOrFail($this->loanId);

        // Guard: pastikan loan belum punya record pengembalian
        if ($loan->return()->exists()) {
            $this->addError('loanId', 'Peminjaman ini sudah memiliki record pengembalian.');
            return;
        }

        DB::transaction(function () use ($loan) {
            // ── Hitung keterlambatan ──────────────────────────────
            $expected = Carbon::parse($loan->return_date)->startOfDay();
            $actual   = Carbon::parse($this->returnDate)->startOfDay();
            $lateFee  = 0;

            if ($actual->greaterThan($expected)) {
                $lateFee = $actual->diffInDays($expected) * 5000; // Rp 5.000/hari
            }

            // ── Buat record di tabel returns ──────────────────────
            ReturnModel::create([
                'loan_id'         => $loan->id,
                'returned_by'     => $loan->user_id,
                'received_by'     => $this->receivedBy,
                'return_date'     => $this->returnDate,
                'condition_notes' => $this->conditionNotes ?: 'Dikembalikan dalam kondisi baik (Entry Admin)',
                'late_fee'        => $lateFee,
                'damage_fee'      => 0,
                'fine_status'     => $lateFee > 0 ? 'unpaid' : 'none',
            ]);

            // ── Ubah status loan → returned ───────────────────────
            $loan->update(['status' => 'returned']);

            // ── Kembalikan stok aset ──────────────────────────────
            foreach ($loan->items as $item) {
                $item->asset->increment('stock', $item->quantity);
            }

            DB::table('activity_logs')->insert([
                'user_id'    => auth()->id(),
                'action'     => 'Admin membuat record pengembalian manual (Peminjaman ID: #' . $loan->id . ')',
                'created_at' => now(),
            ]);
        });

        $this->resetForm();
        $this->dispatch('return-saved',
            message: 'Record pengembalian berhasil dibuat. Stok alat telah diperbarui.'
        );
    }

    // ─── Update (Edit) ─────────────────────────────────────────────

    public function update(): void
    {
        $this->validate([
            'receivedBy'     => 'required|exists:users,id',
            'returnDate'     => 'required|date',
            'conditionNotes' => 'nullable|string|max:500',
        ]);

        $record = ReturnModel::findOrFail($this->editId);

        $record->update([
            'received_by'     => $this->receivedBy,
            'return_date'     => $this->returnDate,
            'condition_notes' => $this->conditionNotes,
        ]);

        DB::table('activity_logs')->insert([
            'user_id'    => auth()->id(),
            'action'     => 'Admin mengubah data pengembalian (ID: #' . $this->editId . ')',
            'created_at' => now(),
        ]);

        $this->resetForm();
        $this->dispatch('return-saved',
            message: 'Data pengembalian #' . $this->editId . ' berhasil diperbarui.'
        );
    }

    // ─── Render ────────────────────────────────────────────────────

    public function render()
    {
        // Query dropdown hanya saat form terbuka (hemat query)
        $eligibleLoans = $this->showForm && !$this->isEdit
            ? Loan::with('user', 'items.asset')
                ->whereIn('status', ['ongoing', 'overdue'])
                ->whereDoesntHave('return')
                ->orderBy('id')
                ->get()
            : collect();

        $staffUsers = $this->showForm
            ? User::whereIn('role', ['petugas', 'admin'])->orderBy('name')->get()
            : collect();

        return view('livewire.admin.returns.form', compact('eligibleLoans', 'staffUsers'));
    }
}
