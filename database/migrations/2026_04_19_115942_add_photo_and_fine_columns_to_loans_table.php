<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('loans', function (Blueprint $table) {
            // Kolom untuk simpan path gambar
            $table->string('photo_before')->nullable()->after('status'); 
            $table->string('photo_after')->nullable()->after('photo_before');
            
            // Kolom untuk nominal dan alasan denda
            $table->integer('fine_amount')->default(0)->after('photo_after');
            $table->text('fine_reason')->nullable()->after('fine_amount');
        });
    }

    public function down()
    {
        Schema::table('loans', function (Blueprint $table) {
            // Drop kolom kalau misal lu butuh nge-rollback migration ini
            $table->dropColumn(['photo_before', 'photo_after', 'fine_amount', 'fine_reason']);
        });
    }
};
