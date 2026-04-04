<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Loan;
use App\Models\ReturnModel;

class ReturnController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'loan_id' => 'required|exists:loans,id',
            'condition_notes' => 'nullable|string'
        ]);

        $loan = Loan::with('items.asset')->findOrFail($request->loan_id);

        if ($loan->status !== 'ongoing') {
            return response()->json(['error' => 'Loan not ongoing'], 400);
        }

        // 🔥 Tambah stok balik
        foreach ($loan->items as $item) {
            $item->asset->increment('stock', $item->quantity);
        }

        ReturnModel::create([
            'loan_id' => $loan->id,
            'returned_by' => auth()->id(),
            'received_by' => auth()->id(), // sementara
            'return_date' => now(),
            'condition_notes' => $request->condition_notes
        ]);

        $loan->update([
            'status' => 'returned'
        ]);

        return response()->json(['message' => 'Returned successfully']);
    }
}
