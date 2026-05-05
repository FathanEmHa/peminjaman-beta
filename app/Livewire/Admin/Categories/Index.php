<?php

namespace App\Livewire\Admin\Categories;

use Livewire\Component;
use App\Models\Category;

class Index extends Component
{
    public $name = '';
    public $categoryId = null;
    public $isEdit = false;

    public function render()
    {
        $categories = Category::latest()->get();
        return view('livewire.admin.categories.index', compact('categories'))
            ->layout('layouts.app');
    }

    public function store()
    {
        $this->validate(['name' => 'required|min:3|max:100']);
        Category::create(['name' => $this->name]);
        $this->resetFields();
        session()->flash('message', 'Kategori berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->isEdit = true;
    }

    public function update()
    {
        $this->validate(['name' => 'required|min:3|max:100']);
        $category = Category::findOrFail($this->categoryId);
        $category->update(['name' => $this->name]);
        $this->resetFields();
        session()->flash('message', 'Kategori berhasil diupdate.');
    }

    public function delete($id)
    {
        Category::findOrFail($id)->delete();
        session()->flash('message', 'Kategori berhasil dihapus.');
    }

    public function resetFields()
    {
        $this->reset(['name', 'categoryId', 'isEdit']);
        $this->resetErrorBag();
    }
}