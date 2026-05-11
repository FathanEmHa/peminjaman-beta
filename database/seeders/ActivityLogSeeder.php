<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ActivityLogSeeder extends Seeder
{
    /**
     * Seed beberapa log aktivitas realistis agar halaman Activity Log
     * tidak kosong saat demo ke HRD/Recruiter.
     */
    public function run(): void
    {
        $admin   = User::where('email', 'admin@mail.com')->first();
        $petugas = User::where('email', 'petugas@mail.com')->first();

        $now = now();

        // Bersihkan log lama agar idempoten
        DB::table('activity_logs')->delete();

        $logs = [
            // ── Aktivitas Admin ──────────────────────────────────────────
            [
                'user_id'    => $admin->id,
                'action'     => 'Admin menambahkan master data aset baru: Laptop Asus ROG.',
                'created_at' => $now->copy()->subDays(15)->toDateTimeString(),
                'updated_at' => $now->copy()->subDays(15)->toDateTimeString(),
            ],
            [
                'user_id'    => $admin->id,
                'action'     => 'Admin menambahkan master data aset baru: Printer Canon.',
                'created_at' => $now->copy()->subDays(14)->toDateTimeString(),
                'updated_at' => $now->copy()->subDays(14)->toDateTimeString(),
            ],
            [
                'user_id'    => $admin->id,
                'action'     => 'Admin menambahkan master data aset baru: Teleskop Bintang.',
                'created_at' => $now->copy()->subDays(13)->toDateTimeString(),
                'updated_at' => $now->copy()->subDays(13)->toDateTimeString(),
            ],
            [
                'user_id'    => $admin->id,
                'action'     => 'Admin memperbarui stok aset: Proyektor Epson (stok diperbarui menjadi 3).',
                'created_at' => $now->copy()->subDays(12)->toDateTimeString(),
                'updated_at' => $now->copy()->subDays(12)->toDateTimeString(),
            ],
            [
                'user_id'    => $admin->id,
                'action'     => 'Admin menambahkan kategori baru: Sains & Alat Ukur.',
                'created_at' => $now->copy()->subDays(11)->toDateTimeString(),
                'updated_at' => $now->copy()->subDays(11)->toDateTimeString(),
            ],

            // ── Aktivitas Petugas ─────────────────────────────────────────
            [
                'user_id'    => $petugas->id,
                'action'     => 'Petugas menyetujui peminjaman #5 oleh Andi Pratama (Laptop Asus ROG).',
                'created_at' => $now->copy()->subDays(10)->toDateTimeString(),
                'updated_at' => $now->copy()->subDays(10)->toDateTimeString(),
            ],
            [
                'user_id'    => $petugas->id,
                'action'     => 'Petugas mengkonfirmasi pengembalian peminjaman #5 dari Andi Pratama. Kondisi: Aman, berfungsi baik.',
                'created_at' => $now->copy()->subDays(7)->toDateTimeString(),
                'updated_at' => $now->copy()->subDays(7)->toDateTimeString(),
            ],
            [
                'user_id'    => $petugas->id,
                'action'     => 'Petugas menyetujui peminjaman #4 oleh Andi Pratama (Proyektor Epson).',
                'created_at' => $now->copy()->subDays(6)->toDateTimeString(),
                'updated_at' => $now->copy()->subDays(6)->toDateTimeString(),
            ],
            [
                'user_id'    => $petugas->id,
                'action'     => 'Petugas menolak peminjaman #6. Alasan: alat sedang dikalibrasi untuk persiapan UKK.',
                'created_at' => $now->copy()->subDays(5)->toDateTimeString(),
                'updated_at' => $now->copy()->subDays(5)->toDateTimeString(),
            ],
            [
                'user_id'    => $petugas->id,
                'action'     => 'Petugas menyetujui peminjaman #3 oleh Andi Pratama (Laptop Asus ROG).',
                'created_at' => $now->copy()->subDays(3)->toDateTimeString(),
                'updated_at' => $now->copy()->subDays(3)->toDateTimeString(),
            ],
            [
                'user_id'    => $petugas->id,
                'action'     => 'Petugas menyetujui peminjaman #2 oleh Andi Pratama (Bor Listrik Bosch).',
                'created_at' => $now->copy()->toDateTimeString(),
                'updated_at' => $now->copy()->toDateTimeString(),
            ],
        ];

        DB::table('activity_logs')->insert($logs);
    }
}
