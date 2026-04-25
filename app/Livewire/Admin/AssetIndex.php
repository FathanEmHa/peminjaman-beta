<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Asset;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class AssetIndex extends Component
{
    use WithFileUploads;

    public $name, $stock, $category_id;
    public $photo; 
    public $existingPhoto; 
    public $assetId;
    public $isEdit = false;

    // --- State untuk Modal Foto (Lightbox) ---
    public $showImageModal = false;
    public $activeImageUrl = '';
    public $activeImageTitle = '';

    protected function rules()
    {
        return [
            'name' => 'required|min:3|max:100',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'photo' => $this->isEdit ? 'nullable|image|max:2048' : 'required|image|max:2048',
        ];
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
        $assets = Asset::with('category')->latest()->get();
        $categories = Category::all();

        return view('livewire.admin.asset-index', compact('assets', 'categories'))
            ->layout('layouts.app');
    }

    public function store()
    {
        $this->validate();

        $photoPath = null;
        if ($this->photo) {
            $photoPath = $this->photo->store('assets', 'public');
        }

        Asset::create([
            'name' => $this->name,
            'stock' => $this->stock,
            'category_id' => $this->category_id,
            'photo' => $photoPath,
        ]);

        $this->resetFields();
        session()->flash('message', 'Alat beserta foto berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $asset = Asset::findOrFail($id);
        $this->assetId = $asset->id;
        $this->name = $asset->name;
        $this->stock = $asset->stock;
        $this->category_id = $asset->category_id;
        $this->existingPhoto = $asset->photo;
        $this->isEdit = true;
    }

    public function update()
    {
        $this->validate();
        
        $asset = Asset::findOrFail($this->assetId);
        $photoPath = $asset->photo; 

        if ($this->photo) {
            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = $this->photo->store('assets', 'public');
        }

        $asset->update([
            'name' => $this->name,
            'stock' => $this->stock,
            'category_id' => $this->category_id,
            'photo' => $photoPath, 
        ]);

        $this->resetFields();
        session()->flash('message', 'Data alat berhasil diupdate.');
    }

    public function delete($id)
    {
        $asset = Asset::findOrFail($id);
        
        if ($asset->photo && Storage::disk('public')->exists($asset->photo)) {
            Storage::disk('public')->delete($asset->photo);
        }

        $asset->delete();
        session()->flash('message', 'Alat berhasil dihapus.');
    }

    public function resetFields()
    {
        $this->reset(['name', 'stock', 'category_id', 'photo', 'existingPhoto', 'assetId', 'isEdit']);
        $this->resetErrorBag();
    }
}