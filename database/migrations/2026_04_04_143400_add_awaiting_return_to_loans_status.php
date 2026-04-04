<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update enum sekalian menambahkan awaiting_return (dari versi lama)
        // ditambah returning dan cancelled untuk fitur booking baru
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

        // Jadikan DATE -> DATETIME
        DB::statement("
            ALTER TABLE loans
            MODIFY COLUMN loan_date DATETIME NOT NULL,
            MODIFY COLUMN return_date DATETIME NOT NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            ALTER TABLE loans
            MODIFY COLUMN status ENUM(
                'pending',
                'approved',
                'ongoing',
                'returned',
                'rejected'
            ) NOT NULL DEFAULT 'pending'
        ");

        DB::statement("
            ALTER TABLE loans
            MODIFY COLUMN loan_date DATE NOT NULL,
            MODIFY COLUMN return_date DATE NOT NULL
        ");
    }
};
