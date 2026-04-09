<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan 'overdue' ke ENUM status pada tabel loans.
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE loans
            MODIFY COLUMN status ENUM(
                'pending',
                'approved',
                'ongoing',
                'awaiting_return',
                'returning',
                'returned',
                'rejected',
                'cancelled',
                'overdue'
            ) NOT NULL DEFAULT 'pending'
        ");
    }

    /**
     * Reverse the migrations.
     * Menghapus 'overdue' dari ENUM (rollback ke versi sebelumnya).
     */
    public function down(): void
    {
        // Kembalikan loan yang overdue ke ongoing sebelum mengubah ENUM
        DB::table('loans')->where('status', 'overdue')->update(['status' => 'ongoing']);

        DB::statement("
            ALTER TABLE loans
            MODIFY COLUMN status ENUM(
                'pending',
                'approved',
                'ongoing',
                'awaiting_return',
                'returning',
                'returned',
                'rejected',
                'cancelled'
            ) NOT NULL DEFAULT 'pending'
        ");
    }
};
