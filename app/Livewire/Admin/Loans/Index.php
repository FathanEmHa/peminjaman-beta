<?php

namespace App\Livewire\Admin\Loans;

use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;

/**
 * LoanIndex — Parent Component (SRP: Layout + Search State Only)
 *
 * Tanggung jawab:
 *  - Menyimpan state pencarian (sync ke URL)
 *  - Menampilkan flash notification dari event child
 *  - Menyusun layout shell: header, flash, @livewire children
 *
 * TIDAK mengandung: query DB, logika CRUD, cart, form state.
 */
class Index extends Component
{
    // ─── Search State ──────────────────────────────────────────────
    #[Url(as: 'q')]
    public string $search = '';

    // ─── Flash Notification ────────────────────────────────────────
    public ?string $flashMessage = null;
    public string  $flashType    = 'success'; // 'success' | 'error'

    // ─── Listen dari LoanForm child ────────────────────────────────

    /** Dipanggil setelah LoanForm::store() atau LoanForm::update() berhasil. */
    #[On('loan-saved')]
    public function onLoanSaved(string $message = 'Operasi berhasil.'): void
    {
        $this->flashMessage = $message;
        $this->flashType    = 'success';
    }

    /** Dipanggil setelah LoanTable::delete() berhasil. */
    #[On('loan-deleted')]
    public function onLoanDeleted(string $message = 'Data dihapus.'): void
    {
        $this->flashMessage = $message;
        $this->flashType    = 'success';
    }

    public function dismissFlash(): void
    {
        $this->flashMessage = null;
    }

    public function render()
    {
        return view('livewire.admin.loans.index')
            ->layout('layouts.app');
    }
}