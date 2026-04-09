<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('returns', function (Blueprint $table) {
            // Biaya keterlambatan dalam Rupiah (dihitung otomatis)
            $table->integer('late_fee')->default(0)->after('condition_notes');

            // Biaya kerusakan dalam Rupiah (diisi manual oleh Petugas)
            $table->integer('damage_fee')->default(0)->after('late_fee');

            // Status denda: 'none' = tidak ada denda, 'unpaid' = belum lunas, 'paid' = sudah lunas
            $table->enum('fine_status', ['none', 'unpaid', 'paid'])->default('none')->after('damage_fee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('returns', function (Blueprint $table) {
            $table->dropColumn(['late_fee', 'damage_fee', 'fine_status']);
        });
    }
};
