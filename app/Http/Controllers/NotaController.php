<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;

class NotaController extends Controller
{
    public function cetakDenda(Loan $loan)
    {
        // Pastikan relasi penting (return, items, user) ikut ke-load biar nggak error
        $loan->load(['return', 'items.asset', 'user']);

        // Cek apakah data pengembaliannya ada
        if (!$loan->return) {
            abort(404, 'Data pengembalian belum tersedia.');
        }

        return view('nota.denda', compact('loan'));
    }
}