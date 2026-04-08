<?php

namespace App\Livewire\Peminjam;

use Livewire\Component;
use App\Models\Asset;
use App\Models\Loan;
use App\Models\LoanItem;
use Illuminate\Support\Facades\DB;

class LoanCreate extends Component
{
    public $loan_date;
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
            'loan_date'   => 'required|date|after_or_equal:today',
            'return_date' => 'required|date|after:loan_date',
            'cart'        => 'required|array|min:1',
        ], [
            'loan_date.after_or_equal' => 'Tanggal pinjam minimal hari ini.',
            'return_date.after'        => 'Tanggal kembali harus setelah tanggal pinjam.',
        ]);

        DB::transaction(function () {
            $loan = Loan::create([
                'user_id' => auth()->id(),
                'status' => 'pending',
                'loan_date' => $this->loan_date,
                'return_date' => $this->return_date,
            ]);

            foreach ($this->cart as $item) {
                LoanItem::create([
                    'loan_id' => $loan->id,
                    'asset_id' => $item['asset_id'],
                    'quantity' => $item['quantity'],
                ]);
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

    public function mount()
    {
        // 1. Tangkap parameter query 'asset_id' dari URL (berasal dari halaman Katalog)
        $asset_id = request()->query('asset_id');

        if ($asset_id) {
            // Cek apakah aset dengan ID tersebut ada di database dan stoknya masih ada
            $asset = Asset::where('id', $asset_id)->where('stock', '>', 0)->first();

            if ($asset) {
                // Jika valid, set properti ini biar otomatis terpilih di dropdown form
                $this->selected_asset = $asset->id;
            } else {
                // Jika user iseng ubah ID di URL ke barang yang habis, kasih peringatan
                session()->flash('error', 'Alat yang Anda pilih tidak tersedia atau stok habis.');
            }
        }
        
        // (Opsional) 2. Set default tanggal ke waktu sekarang biar user ga usah ngetik dari nol
        // Format disesuaikan dengan input type="datetime-local" HTML5
        $this->loan_date = now()->format('Y-m-d\TH:i');
        // Default pinjam selama 1 hari
        $this->return_date = now()->addDay()->format('Y-m-d\TH:i'); 
    }
}