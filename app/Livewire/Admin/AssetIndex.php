<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Asset;
use App\Models\Category;

class AssetIndex extends Component
{
    public $name, $stock, $category_id;
    public $assetId;
    public $isEdit = false;

    protected $rules = [
        'name' => 'required|min:3|max:100',
        'stock' => 'required|integer|min:0',
        'category_id' => 'required|exists:categories,id'
    ];

    public function render()
    {
        // Gunakan eager loading (with) untuk menghindari N+1 query problem
        $assets = Asset::with('category')->latest()->get();
        $categories = Category::all();

        return view('livewire.admin.asset-index', compact('assets', 'categories'))
            ->layout('layouts.app');
    }

    public function store()
    {
        $this->validate();
        Asset::create([
            'name' => $this->name,
            'stock' => $this->stock,
            'category_id' => $this->category_id
        ]);
        $this->resetFields();
        session()->flash('message', 'Alat berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $asset = Asset::findOrFail($id);
        $this->assetId = $asset->id;
        $this->name = $asset->name;
        $this->stock = $asset->stock;
        $this->category_id = $asset->category_id;
        $this->isEdit = true;
    }

    public function update()
    {
        $this->validate();
        $asset = Asset::findOrFail($this->assetId);
        $asset->update([
            'name' => $this->name,
            'stock' => $this->stock,
            'category_id' => $this->category_id
        ]);
        $this->resetFields();
        session()->flash('message', 'Alat berhasil diupdate.');
    }

    public function delete($id)
    {
        Asset::findOrFail($id)->delete();
        session()->flash('message', 'Alat berhasil dihapus.');
    }

    public function resetFields()
    {
        $this->reset(['name', 'stock', 'category_id', 'assetId', 'isEdit']);
        $this->resetErrorBag();
    }
}