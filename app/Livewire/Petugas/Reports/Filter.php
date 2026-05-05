<?php

namespace App\Livewire\Petugas\Reports;

use Livewire\Component;
use App\Models\Asset;

class Filter extends Component
{
    // Properti ini akan di-bind otomatis ke select dropdown
    public $period = '';
    public $status = '';
    public $asset_id = '';

    public function render()
    {
        // Ambil semua aset buat pilihan filter
        $assets = Asset::orderBy('name', 'asc')->get();
        
        return view('livewire.petugas.reports.filter', compact('assets'))
            ->layout('layouts.app');
    }
}