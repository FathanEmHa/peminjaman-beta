<?php

namespace App\Livewire\Peminjam;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Asset;
use App\Models\Loan;
use App\Models\LoanItem;
use Illuminate\Support\Facades\DB;

class LoanCreate extends Component
{
    use WithPagination;

    public $loan_date;
    public $return_date;
    public $search = ''; // Fitur cari di etalase
    public $quantities = []; // Nyimpen input qty masing-masing alat di etalase
    public $cart = []; 

    // --- State untuk Modal Foto (Lightbox) ---
    public $showImageModal = false;
    public $activeImageUrl = '';
    public $activeImageTitle = '';

    // --- Fungsi Buka/Tutup Modal Foto ---
    public function viewImage($url, $title)
    {
        $this->activeImageUrl = $url;
        $this->activeImageTitle = $title;
        $this->showImageModal = true;
    }

    public function closeImageModal()
    {
        $this->showImageModal = false;
        $this->activeImageUrl = '';
    }

    public function mount()
    {
        // Tangkap parameter query 'asset_id' dari URL (Kalau dari Katalog)
        $asset_id = request()->query('asset_id');

        if ($asset_id) {
            $asset = Asset::where('id', $asset_id)->where('stock', '>', 0)->first();

            if ($asset) {
                // Langsung masukin ke keranjang otomatis sebagai "hadiah" klik dari katalog
                $this->cart[] = [
                    'asset_id' => $asset->id,
                    'name' => $asset->name,
                    'quantity' => 1,
                ];
                session()->flash('success_cart', 'Alat berhasil ditambahkan ke keranjang!');
            } else {
                session()->flash('error', 'Alat yang Anda pilih tidak tersedia atau stok habis.');
            }
        }
        
        $this->loan_date = now()->format('Y-m-d\TH:i');
        $this->return_date = now()->addDay()->format('Y-m-d\TH:i'); 
    }

    public function render()
    {
        // Pakai paginate(9) jangan get()
        $paginatedAssets = Asset::where('stock', '>', 0)
            ->when($this->search, function($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->paginate(9);

        // Map collection dari paginator untuk nambahin realtime_stock
        $paginatedAssets->getCollection()->transform(function ($asset) {
            $qtyInCart = 0;
            foreach ($this->cart as $item) {
                if ($item['asset_id'] == $asset->id) {
                    $qtyInCart += $item['quantity'];
                }
            }
            
            $asset->realtime_stock = $asset->stock - $qtyInCart;
            return $asset;
        });

        // Lempar paginatornya ke view
        return view('livewire.peminjam.loan-create', ['assets' => $paginatedAssets])
            ->layout('layouts.app');
    }

    // Fungsi Add To Cart dimodif nerima ID barang langsung dari Card
    public function addToCart($assetId)
    {
        // Ambil qty dari input card tersebut, default 1
        $qty = $this->quantities[$assetId] ?? 1;

        if ($qty < 1) {
            session()->flash('error', 'Jumlah harus minimal 1.');
            return;
        }

        $asset = Asset::find($assetId);

        $qtyInCart = 0;
        $existingIndex = null;
        
        foreach ($this->cart as $index => $item) {
            if ($item['asset_id'] == $asset->id) {
                $qtyInCart = $item['quantity'];
                $existingIndex = $index;
                break;
            }
        }

        $totalRequested = $qtyInCart + $qty;

        // Validasi Realtime Stock
        if ($totalRequested > $asset->stock) {
            $sisa = $asset->stock - $qtyInCart;
            session()->flash('error', "Stok {$asset->name} tidak cukup! Sisa yang bisa ditambah: {$sisa} unit.");
            return;
        }

        if ($existingIndex !== null) {
            $this->cart[$existingIndex]['quantity'] = $totalRequested;
        } else {
            $this->cart[] = [
                'asset_id' => $asset->id,
                'name' => $asset->name,
                'quantity' => $qty,
            ];
        }

        // Reset input qty di card jadi 1 lagi
        $this->quantities[$assetId] = 1;
        session()->flash('success_cart', "{$asset->name} berhasil ditambahkan!");
    }

    public function removeFromCart($index)
    {
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart); 
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
            'cart.min'                 => 'Keranjang masih kosong, pilih alat dulu dari etalase.',
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

            DB::table('activity_logs')->insert([
                'user_id' => auth()->id(),
                'action' => 'Mengajukan peminjaman baru (ID: #' . $loan->id . ')',
                'created_at' => now(),
            ]);
        });

        session()->flash('message', 'Peminjaman berhasil diajukan! Menunggu persetujuan petugas.');
        return redirect()->route('peminjam.loans.history');
    }
}