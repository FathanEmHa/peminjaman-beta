<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('returns', function (Blueprint $table) {
            $table->unsignedInteger('late_fee')->default(0)->after('condition_notes');
            $table->unsignedInteger('damage_fee')->default(0)->after('late_fee');
            $table->enum('fine_status', ['none', 'unpaid', 'paid'])
                  ->default('none')
                  ->after('damage_fee');
        });
    }

    public function down(): void
    {
        Schema::table('returns', function (Blueprint $table) {
            $table->dropColumn(['late_fee', 'damage_fee', 'fine_status']);
        });
    }
};
