<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\NotaController;
use App\Http\Controllers\Shared\DashboardController;

Route::redirect('/', '/login');

Route::middleware(['auth'])->group(function () {

    Route::get('/nota/{loan}/denda', [NotaController::class, 'cetakDenda'])->name('nota.denda');

    Route::get('/dashboard', function () {
        $role = auth()->user()->role;
        if ($role === 'admin')
            return redirect()->route('admin.dashboard');
        if ($role === 'petugas')
            return redirect()->route('petugas.dashboard');
        return redirect()->route('peminjam.dashboard');
    })->name('dashboard');

    // 1. ADMIN ROUTES
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');

        Route::get('/categories', \App\Livewire\Admin\CategoryIndex::class)->name('admin.categories');
        Route::get('/assets', \App\Livewire\Admin\AssetIndex::class)->name('admin.assets');
        Route::get('/users', \App\Livewire\Admin\UserIndex::class)->name('admin.users');
        Route::get('/activity-logs', \App\Livewire\Admin\ActivityLog::class)->name('admin.logs');
        Route::get('/loans', \App\Livewire\Admin\LoanIndex::class)->name('admin.loans');
        Route::get('/returns', \App\Livewire\Admin\ReturnIndex::class)->name('admin.returns');

    });

    // 2. PETUGAS ROUTES
    Route::middleware(['role:petugas'])->prefix('petugas')->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'petugas'])->name('petugas.dashboard');

        // Pastikan ada ->name('petugas.dashboard') di ujungnya
        Route::get('/approval', \App\Livewire\Petugas\LoanApproval::class)->name('petugas.approval');

        Route::get('/returns', \App\Livewire\Petugas\ReturnManagement::class)->name('petugas.returns');

        // TAMBAHKAN ROUTE KATALOG PETUGAS DI SINI
        Route::get('/katalog-aset', \App\Livewire\Petugas\AssetKatalog::class)->name('petugas.katalog');

        // === TAMBAHIN BARIS INI BIAR GAK NGACO ===
        Route::get('/laporan', \App\Livewire\Petugas\ReportFilter::class)->name('petugas.laporan');

        // Route cetak laporan (yang proses filternya)
        Route::get('/laporan/cetak', [\App\Http\Controllers\Petugas\ReportController::class, 'print'])->name('petugas.laporan.cetak');

        Route::get('/loans/{loan}/detail', \App\Livewire\Shared\LoanDetail::class)->name('petugas.loans.detail');
    });

    // 3. PEMINJAM ROUTES
    Route::middleware(['role:peminjam'])->prefix('peminjam')->group(function () {
        // Di dalam Route::middleware(['role:peminjam'])
        Route::get('/dashboard', [DashboardController::class, 'peminjam'])->name('peminjam.dashboard');

        Route::get('/katalog', \App\Livewire\Peminjam\AssetList::class)->name('peminjam.katalog');

        Route::get('/loans/create', \App\Livewire\Peminjam\LoanCreate::class)->name('peminjam.loans.create');
        Route::get('/loans/history', \App\Livewire\Peminjam\LoanHistory::class)->name('peminjam.loans.history');
        Route::get('/loans/{loan}/detail', \App\Livewire\Shared\LoanDetail::class)->name('peminjam.loans.detail');
    });

});

require __DIR__ . '/auth.php';
