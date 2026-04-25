<?php

namespace App\Livewire\Shared;

use Livewire\Component;
use App\Models\Loan;

class LoanDetail extends Component
{
    public $loan;
    public $backRoute; 
    
    // State buat Lightbox (Zoom Foto)
    public $showImageModal = false;
    public $activeImageUrl = '';
    public $activeImageTitle = '';

    public function mount(Loan $loan)
    {
        // Pastikan load relasi yang dibutuhin
        $this->loan = $loan->load('return', 'items.asset', 'user'); 

        // Tangkap query parameter '?ref=' dari URL
        $ref = request()->query('ref');

        // Tentukan rute kembali berdasarkan role dan asal halaman (ref)
        if (auth()->user()->role === 'petugas') {
            if ($ref === 'returns') {
                $this->backRoute = route('petugas.returns');
            } elseif ($ref === 'dashboard') {
                $this->backRoute = route('petugas.dashboard');
            } else {
                $this->backRoute = route('petugas.approval'); // Default fallback
            }
        } else {
            // Untuk Peminjam
            if ($ref === 'dashboard') {
                $this->backRoute = route('peminjam.dashboard');
            } else {
                $this->backRoute = route('peminjam.loans.history'); // Default fallback
            }
        }
    }

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
        return view('livewire.shared.loan-detail')->layout('layouts.app'); 
    }
}