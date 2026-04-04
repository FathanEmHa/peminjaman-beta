<?php

namespace App\Livewire\Peminjam;

use Livewire\Component;
use App\Models\Loan;

class LoanHistory extends Component
{
    public function render()
    {
        // Ambil data peminjaman khusus untuk user yang sedang login
        $loans = Loan::with('items.asset')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();
            
        return view('livewire.peminjam.loan-history', compact('loans'));
    }
}