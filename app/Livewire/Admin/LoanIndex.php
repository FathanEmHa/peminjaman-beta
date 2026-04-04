<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Loan;

class LoanIndex extends Component
{
    public function render()
    {
        // Tarik semua data tanpa filter, lengkap dengan relasi
        $loans = Loan::with(['items.asset', 'user'])->latest()->get();
        return view('livewire.admin.loan-index', compact('loans'))
            ->layout('layouts.app');
    }

    public function delete($id)
    {
        Loan::findOrFail($id)->delete();
        session()->flash('message', 'Data peminjaman beserta detail barang berhasil dihapus.');
    }
}