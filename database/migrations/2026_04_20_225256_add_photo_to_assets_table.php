<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            // Nambahin kolom photo. Dikasih nullable() biar data lama yang belum ada fotonya gak error.
            $table->string('photo')->nullable()->after('name'); // Posisi 'after' bisa lu sesuaikan
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            // Buat ngehapus kolom kalau lu ngetik php artisan migrate:rollback
            $table->dropColumn('photo');
        });
    }
};
