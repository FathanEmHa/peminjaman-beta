<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Loan;
use App\Models\LoanItem;

class LoanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.asset_id' => 'required|exists:assets,id',
            'items.*.quantity' => 'required|integer|min:1'
        ]);

        $loan = Loan::create([
            'user_id' => auth()->id(),
            'status' => 'pending',
            'loan_date' => now(),
            'return_date' => now()->addDays(3)
        ]);

        foreach ($request->items as $item) {
            LoanItem::create([
                'loan_id' => $loan->id,
                'asset_id' => $item['asset_id'],
                'quantity' => $item['quantity']
            ]);
        }

        return response()->json($loan, 201);
    }

    public function approve($id)
    {
        $loan = Loan::findOrFail($id);

        if ($loan->status !== 'pending') {
            return response()->json(['error' => 'Invalid status'], 400);
        }

        $loan->update([
            'status' => 'approved',
            'approved_by' => auth()->id()
        ]);

        return response()->json(['message' => 'Approved']);
    }

    public function reject($id)
    {
        $loan = Loan::findOrFail($id);

        if ($loan->status !== 'pending') {
            return response()->json(['error' => 'Invalid status'], 400);
        }

        $loan->update([
            'status' => 'rejected',
            'approved_by' => auth()->id()
        ]);

        return response()->json(['message' => 'Rejected']);
    }

    public function ongoing($id)
    {
        $loan = Loan::with('items.asset')->findOrFail($id);

        if ($loan->status !== 'approved') {
            return response()->json(['error' => 'Must be approved first'], 400);
        }

        // 🔥 Kurangi stok
        foreach ($loan->items as $item) {
            if ($item->asset->stock < $item->quantity) {
                return response()->json(['error' => 'Stock not enough'], 400);
            }

            $item->asset->decrement('stock', $item->quantity);
        }

        $loan->update([
            'status' => 'ongoing'
        ]);

        return response()->json(['message' => 'Loan is now ongoing']);
    }
}
