<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Livewire\Loan\CreateLoan;
use App\Livewire\Loan\LoanApproval;

Route::redirect('/', '/login');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {


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
        Route::get('/dashboard', function () {
            return view('dashboard-admin');
        })->name('admin.dashboard');

        Route::get('/categories', \App\Livewire\Admin\CategoryIndex::class)->name('admin.categories');
        Route::get('/assets', \App\Livewire\Admin\AssetIndex::class)->name('admin.assets');
        Route::get('/users', \App\Livewire\Admin\UserIndex::class)->name('admin.users');
        Route::get('/activity-logs', \App\Livewire\Admin\ActivityLog::class)->name('admin.logs');
        Route::get('/loans', \App\Livewire\Admin\LoanIndex::class)->name('admin.loans');
        Route::get('/returns', \App\Livewire\Admin\ReturnIndex::class)->name('admin.returns');

    });

    // 2. PETUGAS ROUTES
    Route::middleware(['role:petugas'])->prefix('petugas')->group(function () {

        // Pastikan ada ->name('petugas.dashboard') di ujungnya
        Route::get('/dashboard', \App\Livewire\Petugas\LoanApproval::class)->name('petugas.dashboard');

        // Route cetak laporan
        Route::get('/laporan/cetak', function () {
            $loans = \App\Models\Loan::with(['items.asset', 'user'])->latest()->get();
            return view('cetak-laporan', compact('loans'));
        })->name('petugas.laporan.cetak');

    });

    // 3. PEMINJAM ROUTES
    Route::middleware(['role:peminjam'])->prefix('peminjam')->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard-peminjam');
        })->name('peminjam.dashboard');

        Route::get('/loans/create', \App\Livewire\Peminjam\LoanCreate::class)->name('peminjam.loans.create');
        Route::get('/loans/history', \App\Livewire\Peminjam\LoanHistory::class)->name('peminjam.loans.history');
    });

});

require __DIR__ . '/auth.php';
