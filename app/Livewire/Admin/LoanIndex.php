<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Loan;
use App\Models\LoanItem;
use App\Models\Asset;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LoanIndex extends Component
{
    // ─── Form State ────────────────────────────────────────────────
    public bool $showForm = false;
    public bool $isEdit = false;
    public ?int $editId = null;

    // ─── Loan Header Fields ────────────────────────────────────────
    public ?int $userId = null;
    public ?int $approvedBy = null;
    public string $status = 'pending';
    public string $loanDate = '';
    public string $returnDate = '';

    // ─── Item Cart (untuk Create) ──────────────────────────────────
    public ?int $selectedAsset = null;
    public int $quantity = 1;
    public array $cart = [];

    // ─── Search & Filter ───────────────────────────────────────────
    public string $search = '';

    public function render()
    {
        $loans = Loan::with(['items.asset', 'user', 'approver'])
            ->when(
                $this->search,
                fn($q) =>
                $q->whereHas(
                    'user',
                    fn($u) =>
                    $u->where('name', 'like', '%' . $this->search . '%')
                )->orWhere('id', 'like', '%' . $this->search . '%')
            )
            ->latest()
            ->get();

        $users = User::where('role', 'peminjam')->orderBy('name')->get();
        $assets = Asset::where('stock', '>', 0)->orderBy('name')->get();

        // Untuk dropdown approved_by (petugas + admin bisa jadi approver)
        $staffUsers = User::whereIn('role', ['petugas', 'admin'])->orderBy('name')->get();

        return view('livewire.admin.loan-index', compact('loans', 'users', 'assets', 'staffUsers'))
            ->layout('layouts.app');
    }

    // ─── Form Toggle ───────────────────────────────────────────────

    public function openCreateForm(): void
    {
        $this->resetFields();
        $this->showForm = true;
        $this->isEdit = false;
    }

    public function resetFields(): void
    {
        $this->reset([
            'userId',
            'approvedBy',
            'status',
            'loanDate',
            'returnDate',
            'selectedAsset',
            'quantity',
            'cart',
            'editId',
            'isEdit',
        ]);
        $this->status = 'pending';
        $this->quantity = 1;
        $this->showForm = false;
    }

    // ─── Cart Management (untuk Create) ───────────────────────────

    public function addToCart(): void
    {
        $this->validate([
            'selectedAsset' => 'required|exists:assets,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $asset = Asset::findOrFail($this->selectedAsset);

        if ($this->quantity > $asset->stock) {
            $this->addError('quantity', "Stok tidak mencukupi. Stok tersedia: {$asset->stock}");
            return;
        }

        // Cek duplikat di cart
        foreach ($this->cart as $item) {
            if ($item['asset_id'] === $asset->id) {
                $this->addError('selectedAsset', 'Alat ini sudah ada di daftar.');
                return;
            }
        }

        $this->cart[] = [
            'asset_id' => $asset->id,
            'name' => $asset->name,
            'quantity' => $this->quantity,
        ];

        $this->reset(['selectedAsset', 'quantity']);
        $this->quantity = 1;
    }

    public function removeFromCart(int $index): void
    {
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart);
    }

    // ─── CRUD Operations ───────────────────────────────────────────

    public function store(): void
    {
        $this->validate([
            'userId' => 'required|exists:users,id',
            'status' => 'required|in:pending,approved,ongoing,awaiting_return,returned,rejected',
            'loanDate' => 'required|date',
            'returnDate' => 'required|date|after_or_equal:loanDate',
            'cart' => 'required|array|min:1',
        ]);

        DB::transaction(function () {
            $loan = Loan::create([
                'user_id' => $this->userId,
                'approved_by' => $this->approvedBy ?: null,
                'status' => $this->status,
                'loan_date' => $this->loanDate,
                'return_date' => $this->returnDate,
            ]);

            foreach ($this->cart as $item) {
                LoanItem::create([
                    'loan_id' => $loan->id,
                    'asset_id' => $item['asset_id'],
                    'quantity' => $item['quantity'],
                ]);

                // Kurangi stok jika status bukan pending/rejected
                if (!in_array($this->status, ['pending', 'rejected'])) {
                    Asset::find($item['asset_id'])->decrement('stock', $item['quantity']);
                }
            }

            DB::table('activity_logs')->insert([
                'user_id' => auth()->id(),
                'action' => 'Admin membuat data peminjaman baru (ID: #' . $loan->id . ')',
                'created_at' => now(),
            ]);
        });

        $this->resetFields();
        session()->flash('message', 'Data peminjaman berhasil dibuat.');
    }

    public function edit(int $id): void
    {
        $loan = Loan::with('items.asset')->findOrFail($id);

        $this->editId = $loan->id;
        $this->userId = $loan->user_id;
        $this->approvedBy = $loan->approved_by;
        $this->status = $loan->status;
        $this->loanDate = $loan->loan_date;
        $this->returnDate = $loan->return_date;

        // Load existing items ke cart
        $this->cart = $loan->items->map(fn($item) => [
            'asset_id' => $item->asset_id,
            'name' => $item->asset->name,
            'quantity' => $item->quantity,
        ])->toArray();

        $this->isEdit = true;
        $this->showForm = true;
    }

    public function update(): void
    {
        $this->validate([
            'userId' => 'required|exists:users,id',
            'status' => 'required|in:pending,approved,ongoing,awaiting_return,returned,rejected',
            'loanDate' => 'required|date',
            'returnDate' => 'required|date|after_or_equal:loanDate',
        ]);

        $loan = Loan::findOrFail($this->editId);

        $loan->update([
            'user_id' => $this->userId,
            'approved_by' => $this->approvedBy ?: null,
            'status' => $this->status,
            'loan_date' => $this->loanDate,
            'return_date' => $this->returnDate,
        ]);

        DB::table('activity_logs')->insert([
            'user_id' => auth()->id(),
            'action' => 'Admin mengubah data peminjaman (ID: #' . $this->editId . ')',
            'created_at' => now(),
        ]);

        $this->resetFields();
        session()->flash('message', 'Data peminjaman berhasil diperbarui.');
    }

    public function delete(int $id): void
    {
        Loan::findOrFail($id)->delete();

        DB::table('activity_logs')->insert([
            'user_id' => auth()->id(),
            'action' => 'Admin menghapus data peminjaman (ID: #' . $id . ')',
            'created_at' => now(),
        ]);

        session()->flash('message', 'Data peminjaman beserta detail barang berhasil dihapus.');
    }
}