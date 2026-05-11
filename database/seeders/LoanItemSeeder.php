<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Asset;

class LoanItemSeeder extends Seeder
{
    /**
     * Kaitkan aset ke masing-masing transaksi loan.
     *
     * Mapping foto ↔ aset (PENTING untuk konsistensi visual demo):
     *   Loan #3 (ongoing)  → Laptop Asus ROG  (foto: laptop-sebelum.jpeg)
     *   Loan #4 (overdue)  → Proyektor Epson   (foto: proyektor-sebelum.jpeg)
     *   Loan #5 (returned) → Laptop Asus ROG  (foto: laptop-sebelum/sesudah.jpeg)
     *
     * Loan #1 (pending), #2 (approved) → Printer Canon (belum ada foto, wajar)
     * Loan #6 (rejected)               → Teleskop Bintang (ditolak, tidak jadi dipinjam)
     */
    public function run(): void
    {
        // Ambil semua loan berurutan sesuai status yang di-seed di LoanSeeder
        $loans = DB::table('loans')->orderBy('id')->get();

        // Ambil aset berdasarkan nama agar tidak bergantung pada ID yang bisa berubah
        $laptop    = Asset::where('name', 'Laptop Asus ROG')->first();
        $proyektor = Asset::where('name', 'Proyektor Epson')->first();
        $printer   = Asset::where('name', 'Printer Canon')->first();
        $teleskop  = Asset::where('name', 'Teleskop Bintang')->first();
        $bor       = Asset::where('name', 'Bor Listrik Bosch')->first();

        // Pastikan tabel bersih sebelum insert (sudah dibersihkan di LoanSeeder)
        // Mapping: [loan_index_0_based => [asset, quantity]]
        $mapping = [
            0 => [$printer,   1], // Loan 1: pending     → Printer Canon
            1 => [$bor,       1], // Loan 2: approved    → Bor Listrik Bosch
            2 => [$laptop,    2], // Loan 3: ongoing     → Laptop Asus ROG (qty 2)
            3 => [$proyektor, 1], // Loan 4: overdue     → Proyektor Epson
            4 => [$laptop,    1], // Loan 5: returned    → Laptop Asus ROG
            5 => [$teleskop,  1], // Loan 6: rejected    → Teleskop Bintang
        ];

        $now = now();

        foreach ($mapping as $index => $item) {
            [$asset, $quantity] = $item;

            if (! isset($loans[$index]) || $asset === null) {
                continue;
            }

            DB::table('loan_items')->insert([
                'loan_id'    => $loans[$index]->id,
                'asset_id'   => $asset->id,
                'quantity'   => $quantity,
                'created_at' => $now->toDateTimeString(),
                'updated_at' => $now->toDateTimeString(),
            ]);
        }
    }
}
