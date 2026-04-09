<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Loan;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function print(Request $request)
    {
        $query = Loan::with(['items.asset', 'user']);

        // 1. Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 2. Filter Alat (Asset)
        if ($request->filled('asset_id')) {
            $query->whereHas('items', function ($q) use ($request) {
                $q->where('asset_id', $request->asset_id);
            });
        }

        // 3. Filter Periode (Waktu)
        if ($request->filled('period')) {
            if ($request->period === 'today') {
                $query->whereDate('created_at', Carbon::today());
            } elseif ($request->period === 'this_week') {
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            } elseif ($request->period === 'this_month') {
                $query->whereMonth('created_at', Carbon::now()->month)
                      ->whereYear('created_at', Carbon::now()->year);
            }
        }

        // Ambil data yang sudah difilter
        $loans = $query->latest()->get();

        // Lempar ke view cetak-laporan yang udah lu buat
        return view('cetak-laporan', compact('loans'));
    }
}