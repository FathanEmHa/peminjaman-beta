<?php

namespace App\Livewire\Peminjam;

use App\Models\Asset;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class AssetList extends Component
{
    use WithPagination;

    public $search = '';
    public $category_id = '';

    // State buat Lightbox (Zoom Foto)
    public $showImageModal = false;
    public $activeImageUrl = '';
    public $activeImageTitle = '';

    // Reset pagination ketika user mengetik pencarian atau mengubah filter
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryId()
    {
        $this->resetPage();
    }

    // Fungsi buat buka foto full
    public function viewImage($url, $title)
    {
        $this->activeImageUrl = $url;
        $this->activeImageTitle = $title;
        $this->showImageModal = true;
    }

    // Fungsi buat tutup modal foto
    public function closeImageModal()
    {
        $this->showImageModal = false;
        $this->activeImageUrl = '';
    }

    public function render()
    {
        $assets = Asset::with('category')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->category_id, function ($query) {
                $query->where('category_id', $this->category_id);
            })
            // Menampilkan 9 item per halaman agar pas dengan grid 3 kolom
            ->paginate(9);

        $categories = Category::all();

        return view('livewire.peminjam.asset-list', [
            'assets' => $assets,
            'categories' => $categories,
        ])->layout('layouts.app');
    }
}