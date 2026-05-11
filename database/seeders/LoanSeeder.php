<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class LoanSeeder extends Seeder
{
    /**
     * Seed 6 transaksi peminjaman dengan status berbeda-beda untuk demo.
     *
     * Urutan ID loan yang dijamin (bergantung urutan insert):
     *   1 → pending
     *   2 → approved
     *   3 → ongoing      (Laptop, ada photo_before)
     *   4 → overdue      (Proyektor, ada photo_before)
     *   5 → returned     (Laptop, ada photo_before + data di tabel returns)
     *   6 → rejected     (ada rejection_note)
     */
    public function run(): void
    {
        $peminjam = User::where('email', 'peminjam@mail.com')->first();
        $petugas  = User::where('email', 'petugas@mail.com')->first();
        $admin    = User::where('email', 'admin@mail.com')->first();

        $now = now();

        $loans = [
            // ── 1. PENDING ──────────────────────────────────────────────────
            [
                'user_id'        => $peminjam->id,
                'approved_by'    => null,
                'status'         => 'pending',
                'rejection_note' => null,
                'photo_before'   => null,
                'photo_after'    => null,
                'fine_amount'    => 0,
                'fine_reason'    => null,
                'loan_date'      => $now->copy()->toDateTimeString(),
                'return_date'    => $now->copy()->addDays(3)->toDateTimeString(),
                'created_at'     => $now->copy()->toDateTimeString(),
                'updated_at'     => $now->copy()->toDateTimeString(),
            ],

            // ── 2. APPROVED (siap diambil) ───────────────────────────────
            [
                'user_id'        => $peminjam->id,
                'approved_by'    => $petugas->id,
                'status'         => 'approved',
                'rejection_note' => null,
                'photo_before'   => null,
                'photo_after'    => null,
                'fine_amount'    => 0,
                'fine_reason'    => null,
                'loan_date'      => $now->copy()->toDateTimeString(),
                'return_date'    => $now->copy()->addDays(5)->toDateTimeString(),
                'created_at'     => $now->copy()->subDays(1)->toDateTimeString(),
                'updated_at'     => $now->copy()->toDateTimeString(),
            ],

            // ── 3. ONGOING (sedang dipinjam – Laptop) ───────────────────
            [
                'user_id'        => $peminjam->id,
                'approved_by'    => $petugas->id,
                'status'         => 'ongoing',
                'rejection_note' => null,
                'photo_before'   => 'peminjaman/before/laptop-sebelum.jpeg',
                'photo_after'    => null,
                'fine_amount'    => 0,
                'fine_reason'    => null,
                'loan_date'      => $now->copy()->subDays(2)->toDateTimeString(),
                'return_date'    => $now->copy()->addDays(5)->toDateTimeString(),
                'created_at'     => $now->copy()->subDays(3)->toDateTimeString(),
                'updated_at'     => $now->copy()->subDays(2)->toDateTimeString(),
            ],

            // ── 4. OVERDUE (batas waktu sudah lewat – Proyektor) ────────
            [
                'user_id'        => $peminjam->id,
                'approved_by'    => $petugas->id,
                'status'         => 'overdue',
                'rejection_note' => null,
                'photo_before'   => 'peminjaman/before/proyektor-sebelum.jpeg',
                'photo_after'    => null,
                'fine_amount'    => 0,
                'fine_reason'    => null,
                'loan_date'      => $now->copy()->subDays(10)->toDateTimeString(),
                'return_date'    => $now->copy()->subDays(3)->toDateTimeString(), // sudah lewat
                'created_at'     => $now->copy()->subDays(11)->toDateTimeString(),
                'updated_at'     => $now->copy()->subDays(3)->toDateTimeString(),
            ],

            // ── 5. RETURNED (sudah dikembalikan – Laptop) ───────────────
            // Data di tabel `returns` di-insert oleh LoanSeeder sendiri di bawah
            [
                'user_id'        => $peminjam->id,
                'approved_by'    => $petugas->id,
                'status'         => 'returned',
                'rejection_note' => null,
                'photo_before'   => 'peminjaman/before/laptop-sebelum.jpeg',
                'photo_after'    => 'peminjaman/after/laptop-sesudah.jpeg',
                'fine_amount'    => 0,
                'fine_reason'    => null,
                'loan_date'      => $now->copy()->subDays(14)->toDateTimeString(),
                'return_date'    => $now->copy()->subDays(7)->toDateTimeString(),
                'created_at'     => $now->copy()->subDays(15)->toDateTimeString(),
                'updated_at'     => $now->copy()->subDays(7)->toDateTimeString(),
            ],

            // ── 6. REJECTED (ditolak) ────────────────────────────────────
            [
                'user_id'        => $peminjam->id,
                'approved_by'    => $petugas->id,
                'status'         => 'rejected',
                'rejection_note' => 'Mohon maaf, alat sedang dikalibrasi dan digunakan untuk persiapan Uji Kompetensi Keahlian (UKK).',
                'photo_before'   => null,
                'photo_after'    => null,
                'fine_amount'    => 0,
                'fine_reason'    => null,
                'loan_date'      => $now->copy()->subDays(5)->toDateTimeString(),
                'return_date'    => $now->copy()->subDays(2)->toDateTimeString(),
                'created_at'     => $now->copy()->subDays(6)->toDateTimeString(),
                'updated_at'     => $now->copy()->subDays(5)->toDateTimeString(),
            ],
        ];

        // Bersihkan dulu agar seeder idempoten (aman dijalankan ulang)
        DB::table('returns')->delete();
        DB::table('loan_items')->delete();
        DB::table('loans')->delete();

        foreach ($loans as $loan) {
            DB::table('loans')->insert($loan);
        }

        // ── Insert data ke tabel `returns` untuk Loan #5 (returned) ──────────
        $returnedLoanId = DB::table('loans')
            ->where('status', 'returned')
            ->value('id');

        DB::table('returns')->insert([
            'loan_id'         => $returnedLoanId,
            'returned_by'     => $peminjam->id,
            'received_by'     => $petugas->id,
            'return_date'     => $now->copy()->subDays(7)->toDateString(),
            'condition_notes' => 'Aman, berfungsi baik.',
            'late_fee'        => 0,
            'damage_fee'      => 0,
            'fine_status'     => 'paid',
            'created_at'      => $now->copy()->subDays(7)->toDateTimeString(),
            'updated_at'      => $now->copy()->subDays(7)->toDateTimeString(),
        ]);
    }
}
