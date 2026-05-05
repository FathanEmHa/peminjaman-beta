<?php

namespace App\Livewire\Petugas\Reports;

use App\Models\Asset;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class AssetKatalog extends Component
{
    use WithPagination;

    public $search = '';
    public $category_id = '';

    // --- State untuk Modal Foto (Lightbox) ---
    public $showImageModal = false;
    public $activeImageUrl = '';
    public $activeImageTitle = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryId()
    {
        $this->resetPage();
    }

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

    public function render()
    {
        $assets = Asset::with('category')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->category_id, function ($query) {
                $query->where('category_id', $this->category_id);
            })
            ->paginate(9);

        $categories = Category::all();

        return view('livewire.petugas.reports.asset-katalog', [
            'assets' => $assets,
            'categories' => $categories,
        ])->layout('layouts.app'); // Jangan lupa set layout
    }
}