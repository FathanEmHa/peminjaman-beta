<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Loan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CheckOverdueLoans extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'loans:check-overdue';

    /**
     * The console command description.
     */
    protected $description = 'Otomatis menandai peminjaman yang sudah melewati batas waktu pengembalian sebagai overdue';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $now = Carbon::now();

        // Cari semua loan yang masih ongoing/awaiting_return
        // DAN return_date sudah lewat dari waktu sekarang
        $overdueLoans = Loan::whereIn('status', ['ongoing', 'awaiting_return'])
            ->where('return_date', '<', $now)
            ->get();

        if ($overdueLoans->isEmpty()) {
            $this->info('Tidak ada peminjaman yang overdue.');
            return self::SUCCESS;
        }

        $count = 0;

        DB::transaction(function () use ($overdueLoans, &$count) {
            foreach ($overdueLoans as $loan) {
                $loan->update(['status' => 'overdue']);

                DB::table('activity_logs')->insert([
                    'user_id'    => $loan->user_id, // Dicatat atas nama peminjam
                    'action'     => "[SYSTEM] Peminjaman ID #{$loan->id} otomatis ditandai OVERDUE (batas: {$loan->return_date})",
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $count++;
            }
        });

        $this->info("Berhasil menandai {$count} peminjaman sebagai overdue.");

        return self::SUCCESS;
    }
}
