<?php

namespace App\Livewire\Petugas;

use Livewire\Component;
use App\Models\Loan;
use App\Models\ReturnRecord; // Asumsi model tabel returns lu namanya ReturnRecord atau sesuaikan
use Illuminate\Support\Facades\DB;

class LoanApproval extends Component
{
    public function render()
    {
        // Ambil semua data peminjaman beserta relasi user dan barangnya
        $loans = Loan::with(['items.asset', 'user'])->latest()->get();
        return view('livewire.petugas.loan-approval', compact('loans'))
            ->layout('layouts.app');
    }

    public function approve($id)
    {
        $loan = Loan::findOrFail($id);
        $loan->update([
            'status' => 'approved',
            'approved_by' => auth()->id()
        ]);
        
        DB::table('activity_logs')->insert([
            'user_id' => auth()->id(),
            'action' => 'Menyetujui peminjaman (ID: #' . $id . ')',
            'created_at' => now(),
        ]);
        
        session()->flash('message', 'Peminjaman disetujui.');
    }

    public function reject($id)
    {
        $loan = Loan::findOrFail($id);
        $loan->update([
            'status' => 'rejected',
            'approved_by' => auth()->id()
        ]);
        
        DB::table('activity_logs')->insert([
            'user_id' => auth()->id(),
            'action' => 'Menolak peminjaman (ID: #' . $id . ')',
            'created_at' => now(),
        ]);
        
        session()->flash('message', 'Peminjaman ditolak.');
    }

    public function markOngoing($id)
    {
        $loan = Loan::findOrFail($id);
        $loan->update(['status' => 'ongoing']);
        
        DB::table('activity_logs')->insert([
            'user_id' => auth()->id(),
            'action' => 'Menyerahkan alat ke peminjam (ID: #' . $id . ' - Ongoing)',
            'created_at' => now(),
        ]);
        
        session()->flash('message', 'Status diperbarui: Alat sedang dipinjam (Ongoing).');
    }

    public function markReturned($id)
    {
        $loan = Loan::findOrFail($id);

        DB::transaction(function () use ($loan, $id) {
            $loan->update(['status' => 'returned']);

            DB::table('returns')->insert([
                'loan_id' => $loan->id,
                'returned_by' => $loan->user_id,
                'received_by' => auth()->id(),
                'return_date' => now(),
                'condition_notes' => 'Dikembalikan dalam kondisi baik',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($loan->items as $item) {
                $item->asset->increment('stock', $item->quantity);
            }
            
            DB::table('activity_logs')->insert([
                'user_id' => auth()->id(),
                'action' => 'Menerima pengembalian alat (ID: #' . $id . ' - Returned)',
                'created_at' => now(),
            ]);
        });

        session()->flash('message', 'Barang telah dikembalikan dan stok diperbarui.');
    }
}