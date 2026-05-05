<?php

namespace App\Livewire\Admin\Returns;

use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;

/**
 * ReturnIndex — Parent Component (SRP: Layout + Search State Only)
 *
 * Tanggung jawab:
 *  - Menyediakan state pencarian (#[Url] agar sync dengan URL)
 *  - Meneruskan event 'open-create-form' ke ReturnForm child
 *  - Menerima event 'return-saved' / 'return-deleted' dari children
 *    untuk menampilkan flash message
 *
 * TIDAK mengandung: query DB, logika CRUD, form state, modal state.
 */
class Index extends Component
{
    // ─── Search State (shared ke ReturnTable via @entangle atau $this->dispatch) ─
    #[Url(as: 'q')]
    public string $search = '';

    // ─── Flash Message State ────────────────────────────────────────
    public ?string $flashMessage = null;
    public string  $flashType    = 'success'; // 'success' | 'error'

    // ─── Listen dari child components ───────────────────────────────

    /**
     * ReturnForm mengirim event ini setelah store() atau update() berhasil.
     * Parent cukup menampilkan notifikasi; ReturnTable akan re-render sendiri
     * karena juga listen ke event yang sama via #[On].
     */
    #[On('return-saved')]
    public function onReturnSaved(string $message = 'Operasi berhasil.'): void
    {
        $this->flashMessage = $message;
        $this->flashType    = 'success';
    }

    /**
     * ReturnTable mengirim event ini setelah delete() berhasil.
     */
    #[On('return-deleted')]
    public function onReturnDeleted(string $message = 'Record dihapus.'): void
    {
        $this->flashMessage = $message;
        $this->flashType    = 'success';
    }

    /**
     * Dismiss flash message dari Blade (tombol ×).
     */
    public function dismissFlash(): void
    {
        $this->flashMessage = null;
    }

    public function render()
    {
        return view('livewire.admin.returns.index')
            ->layout('layouts.app');
    }
}