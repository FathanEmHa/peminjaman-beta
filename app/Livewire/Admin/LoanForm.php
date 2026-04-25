<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use App\Models\Loan;
use App\Models\LoanItem;
use App\Models\Asset;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * LoanForm — Child Component (SRP: Create & Edit Form + Cart)
 *
 * Tanggung jawab:
 *  - Menerima event 'open-loan-create' → reset & buka form mode create
 *  - Menerima event 'open-loan-edit'   → load data loan ke form mode edit
 *  - Mengelola Cart: addToCart(), removeFromCart()
 *  - Validasi stok sebelum masuk cart
 *  - store() dan update() dalam DB::transaction
 *  - Setelah sukses → dispatch('loan-saved') ke parent & sibling
 *  - Setelah tutup  → dispatch('loan-form-closed') ke LoanTable
 */
class LoanForm extends Component
{
    // ─── Visibility ────────────────────────────────────────────────
    public bool $showForm = false;
    public bool $isEdit   = false;
    public ?int $editId   = null;

    // ─── Header Fields ─────────────────────────────────────────────
    public ?int    $userId     = null;
    public ?int    $approvedBy = null;
    public string  $status     = 'pending';
    public string  $loanDate   = '';
    public string  $returnDate = '';

    // ─── Cart ──────────────────────────────────────────────────────
    public mixed  $selectedAsset = null;
    public mixed  $quantity      = 1;
    public array  $cart          = [];

    // ─── Listen: buka form Create ──────────────────────────────────

    /** Dipanggil oleh tombol "Tambah" di LoanTable blade via $dispatch('open-loan-create') */
    #[On('open-loan-create')]
    public function openCreate(): void
    {
        $this->resetForm();
        $this->showForm = true;
        $this->isEdit   = false;
    }

    // ─── Listen: buka form Edit ────────────────────────────────────

    /**
     * Dipanggil oleh tombol Edit di LoanTable blade via:
     * $dispatch('open-loan-edit', { id: loanId })
     */
    #[On('open-loan-edit')]
    public function openEdit(int $id): void
    {
        $loan = Loan::with('items.asset')->findOrFail($id);

        $this->editId     = $loan->id;
        $this->userId     = $loan->user_id;
        $this->approvedBy = $loan->approved_by;
        $this->status     = $loan->status;
        $this->loanDate   = $loan->loan_date;
        $this->returnDate = $loan->return_date;

        // Load items ke cart (read-only di mode edit)
        $this->cart = $loan->items->map(fn($item) => [
            'asset_id' => $item->asset_id,
            'name'     => $item->asset->name,
            'quantity' => $item->quantity,
        ])->toArray();

        $this->isEdit   = true;
        $this->showForm = true;

        // Beritahu LoanTable agar highlight baris ini
        $this->dispatch('loan-edit-opened', id: $id);
    }

    // ─── Reset / Tutup Form ────────────────────────────────────────

    public function resetForm(): void
    {
        $this->reset([
            'userId', 'approvedBy', 'loanDate', 'returnDate',
            'selectedAsset', 'cart', 'editId', 'isEdit',
        ]);
        $this->status   = 'pending';
        $this->quantity = 1;
        $this->showForm = false;
        $this->resetErrorBag();

        $this->dispatch('loan-form-closed');
    }

    // ─── Cart Management ───────────────────────────────────────────

    public function addToCart(): void
    {
        $this->validate([
            'selectedAsset' => 'required|exists:assets,id',
            'quantity'      => 'required|numeric|min:1',
        ], [
            'selectedAsset.required' => 'Silakan pilih alat terlebih dahulu.',
            'selectedAsset.exists'   => 'Alat yang dipilih tidak valid.',
            'quantity.required'      => 'Jumlah harus diisi.',
            'quantity.min'           => 'Minimal peminjaman adalah 1 unit.',
        ]);

        $asset = Asset::findOrFail($this->selectedAsset);

        // Validasi stok
        if ((int) $this->quantity > $asset->stock) {
            $this->addError('quantity', "Stok tidak mencukupi! Sisa stok: {$asset->stock}");
            return;
        }

        // Cegah duplikat di cart
        foreach ($this->cart as $item) {
            if ($item['asset_id'] == $asset->id) {
                $this->addError('selectedAsset', 'Alat ini sudah ada di keranjang.');
                return;
            }
        }

        $this->cart[] = [
            'asset_id' => $asset->id,
            'name'     => $asset->name,
            'quantity' => (int) $this->quantity,
        ];

        $this->reset('selectedAsset');
        $this->quantity = 1;
    }

    public function removeFromCart(int $index): void
    {
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart);
    }

    // ─── Store (Create) ────────────────────────────────────────────

    public function store(): void
    {
        $this->validate([
            'userId'     => 'required|exists:users,id',
            'status'     => 'required|in:pending,approved,ongoing,awaiting_return,returned,rejected,overdue,cancelled',
            'loanDate'   => 'required|date',
            'returnDate' => 'required|date|after_or_equal:loanDate',
            'cart'       => 'required|array|min:1',
        ], [
            'cart.required' => 'Tambahkan minimal satu alat ke keranjang.',
            'cart.min'      => 'Tambahkan minimal satu alat ke keranjang.',
        ]);

        DB::transaction(function () {
            $loan = Loan::create([
                'user_id'     => $this->userId,
                'approved_by' => $this->approvedBy ?: null,
                'status'      => $this->status,
                'loan_date'   => $this->loanDate,
                'return_date' => $this->returnDate,
            ]);

            foreach ($this->cart as $item) {
                LoanItem::create([
                    'loan_id'  => $loan->id,
                    'asset_id' => $item['asset_id'],
                    'quantity' => $item['quantity'],
                ]);

                // Kurangi stok hanya jika bukan status awal (pending / rejected)
                if (!in_array($this->status, ['pending', 'rejected'])) {
                    Asset::find($item['asset_id'])->decrement('stock', $item['quantity']);
                }
            }

            // Jika admin langsung set returned saat create, buat record return otomatis
            if ($this->status === 'returned') {
                $this->insertReturnRecord($loan);
            }

            DB::table('activity_logs')->insert([
                'user_id'    => auth()->id(),
                'action'     => 'Admin membuat data peminjaman baru (ID: #' . $loan->id . ')',
                'created_at' => now(),
            ]);
        });

        $this->resetForm();
        $this->dispatch('loan-saved', message: 'Data peminjaman berhasil dibuat.');
    }

    // ─── Update (Edit) ─────────────────────────────────────────────

    public function update(): void
    {
        $this->validate([
            'userId'     => 'required|exists:users,id',
            'status'     => 'required|in:pending,approved,ongoing,awaiting_return,returned,rejected,overdue,cancelled',
            'loanDate'   => 'required|date',
            'returnDate' => 'required|date|after_or_equal:loanDate',
        ]);

        $loan = Loan::with('items.asset')->findOrFail($this->editId);

        DB::transaction(function () use ($loan) {
            $loan->update([
                'user_id'     => $this->userId,
                'approved_by' => $this->approvedBy ?: null,
                'status'      => $this->status,
                'loan_date'   => $this->loanDate,
                'return_date' => $this->returnDate,
            ]);

            // Jika admin memaksa status jadi returned lewat edit
            if ($this->status === 'returned') {
                $this->insertReturnRecord($loan);
            }

            DB::table('activity_logs')->insert([
                'user_id'    => auth()->id(),
                'action'     => 'Admin mengubah data peminjaman (ID: #' . $this->editId . ')',
                'created_at' => now(),
            ]);
        });

        $this->resetForm();
        $this->dispatch('loan-saved', message: 'Data peminjaman #' . $this->editId . ' berhasil diperbarui.');
    }

    // ─── Helper: Auto-insert return record ─────────────────────────

    private function insertReturnRecord(Loan $loan): void
    {
        // Guard: jangan double-insert
        if (DB::table('returns')->where('loan_id', $loan->id)->exists()) {
            return;
        }

        $lateFee = 0;
        $expected = Carbon::parse($this->returnDate)->startOfDay();
        $today    = now()->startOfDay();

        if ($today->greaterThan($expected)) {
            $lateFee = $today->diffInDays($expected) * 5000;
        }

        DB::table('returns')->insert([
            'loan_id'         => $loan->id,
            'returned_by'     => $this->userId,
            'received_by'     => auth()->id(),
            'return_date'     => now()->toDateString(),
            'condition_notes' => 'Status diselesaikan manual oleh Admin lewat Edit Form',
            'late_fee'        => $lateFee,
            'damage_fee'      => 0,
            'fine_status'     => $lateFee > 0 ? 'unpaid' : 'none',
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        // Kembalikan stok karena barang dianggap sudah kembali
        foreach ($loan->items as $item) {
            $item->asset->increment('stock', $item->quantity);
        }
    }

    // ─── Render ────────────────────────────────────────────────────

    public function render()
    {
        // Data dropdown hanya di-load saat form terbuka (efisiensi query)
        $users      = $this->showForm ? User::where('role', 'peminjam')->orderBy('name')->get()                      : collect();
        $assets     = $this->showForm ? Asset::where('stock', '>', 0)->orderBy('name')->get()                        : collect();
        $staffUsers = $this->showForm ? User::whereIn('role', ['petugas', 'admin'])->orderBy('name')->get()          : collect();

        return view('livewire.admin.loan-form', compact('users', 'assets', 'staffUsers'));
    }
}
