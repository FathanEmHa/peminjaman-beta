<?php

namespace App\Livewire\Petugas;

use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;

/**
 * LoanApproval — Parent Component (SRP: Layout + Search State + Flash)
 *
 * Tanggung jawab:
 *  - Menyimpan state pencarian dan filter (#[Url])
 *  - Menerima event dari children dan menampilkan flash notification
 *
 * TIDAK mengandung: query DB, logika approve/reject/handover, modal state.
 */
class LoanApproval extends Component
{
    // ─── Search & Filter State ─────────────────────────────────────
    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'alat')]
    public string $searchAlat = '';

    #[Url(as: 'status')]
    public string $statusFilter = '';

    // ─── Flash Notification ────────────────────────────────────────
    public ?string $flashMessage = null;
    public string  $flashType    = 'success'; // 'success' | 'error'

    // ─── Listen dari children ───────────────────────────────────────

    /** ApprovalActionModal dan ApprovalTable dispatch event ini. */
    #[On('approval-processed')]
    public function onApprovalProcessed(string $message, string $type = 'success'): void
    {
        $this->flashMessage = $message;
        $this->flashType    = $type;
    }

    public function dismissFlash(): void
    {
        $this->flashMessage = null;
    }

    public function render()
    {
        return view('livewire.petugas.loan-approval')
            ->layout('layouts.app');
    }
}