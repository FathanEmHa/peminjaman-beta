<?php

namespace App\Livewire\Peminjam;

use Livewire\Component;
use App\Models\Asset;
use App\Models\Loan;
use App\Models\LoanItem;
use Illuminate\Support\Facades\DB;

class LoanCreate extends Component
{
    public $return_date;
    public $selected_asset;
    public $quantity = 1;
    public $cart = []; // Untuk simpan list barang sementara

    public function render()
    {
        $assets = Asset::where('stock', '>', 0)->get();
        return view('livewire.peminjam.loan-create', compact('assets'))
            ->layout('layouts.app');
    }

    public function addToCart()
    {
        $this->validate([
            'selected_asset' => 'required|exists:assets,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $asset = Asset::find($this->selected_asset);

        // Cek stok manual sebelum masuk cart
        if ($this->quantity > $asset->stock) {
            session()->flash('error', 'Stok tidak mencukupi.');
            return;
        }

        // Tambah ke array cart
        $this->cart[] = [
            'asset_id' => $asset->id,
            'name' => $asset->name,
            'quantity' => $this->quantity,
        ];

        $this->reset(['selected_asset', 'quantity']);
    }

    public function removeFromCart($index)
    {
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart); // Reset index array
    }

    public function store()
    {
        $this->validate([
            'return_date' => 'required|date|after:today',
            'cart' => 'required|array|min:1',
        ]);

        DB::transaction(function () {
            $loan = Loan::create([
                'user_id' => auth()->id(),
                'status' => 'pending',
                'loan_date' => now(),
                'return_date' => $this->return_date,
            ]);

            foreach ($this->cart as $item) {
                LoanItem::create([
                    'loan_id' => $loan->id,
                    'asset_id' => $item['asset_id'],
                    'quantity' => $item['quantity'],
                ]);
                
                // TAMBAHKAN BARIS INI UNTUK MEMOTONG STOK
                Asset::find($item['asset_id'])->decrement('stock', $item['quantity']);
            }

            // EKSEKUSI LOG DI SINI
            DB::table('activity_logs')->insert([
                'user_id' => auth()->id(),
                'action' => 'Mengajukan peminjaman baru (ID: #' . $loan->id . ')',
                'created_at' => now(),
            ]);
        });

        session()->flash('message', 'Peminjaman berhasil diajukan! Menunggu persetujuan petugas.');
        return redirect()->route('peminjam.dashboard');
    }
}