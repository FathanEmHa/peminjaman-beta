<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Loan;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Redirect otomatis berdasarkan Role saat user akses /dashboard
     */
    public function index()
    {
        $role = Auth::user()->role; 

        if ($role === 'admin') {
            return redirect()->route('admin.dashboard'); // Lempar ke halaman Admin
        } elseif ($role === 'petugas') {
            return redirect()->route('petugas.dashboard'); // Lempar ke halaman Petugas
        }

        // Kalau bukan admin/petugas, pasti peminjam
        return redirect()->route('peminjam.dashboard'); 
    }

    /**
     * Logic untuk Dashboard Peminjam
     */
    public function peminjam()
    {
        // Ambil 4 aset terbaru yang stoknya masih ada
        $recentAssets = Asset::where('stock', '>', 0)
            ->latest()
            ->take(4)
            ->get();

        // Ambil 5 riwayat peminjaman terakhir milik user ini
        $recentLoans = Loan::with('items.asset')
            ->where('user_id', Auth::id())
            ->latest()
            ->take(3)
            ->get();

        return view('dashboard-peminjam', compact('recentAssets', 'recentLoans'));
    }

    /**
     * Logic untuk Dashboard Petugas
     */
    public function petugas()
    {
        // Hitung statistik untuk 4 kartu di atas
        $stats = [
            'pending' => Loan::where('status', 'pending')->count(),
            'ongoing' => Loan::where('status', 'ongoing')->count(),
            'overdue' => Loan::where('status', 'overdue')->count(),
            'total_assets' => Asset::sum('stock'), // Menghitung total fisik barang
        ];

        // Ambil 5 transaksi terbaru yang butuh perhatian petugas
        $recentLoans = Loan::with(['user', 'items.asset'])
            ->whereIn('status', ['pending', 'approved', 'ongoing', 'overdue'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard-petugas', compact('stats', 'recentLoans'));
    }

    public function admin()
    {
        // Hitung statistik untuk 4 kartu di atas
        $stats = [
            'total_users'  => User::count(),
            'total_assets' => Asset::sum('stock'), // Total fisik barang
            'active_loans' => Loan::whereIn('status', ['ongoing', 'overdue'])->count(),
            'unpaid_fines' => DB::table('returns')->where('fine_status', 'unpaid')->sum(DB::raw('late_fee + damage_fee')),
        ];

        // Ambil 5 log aktivitas terbaru
        $recentLogs = ActivityLog::with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard-admin', compact('stats', 'recentLogs'));
    }
}