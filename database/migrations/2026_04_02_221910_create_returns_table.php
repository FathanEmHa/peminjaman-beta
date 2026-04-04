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
        Schema::create('returns', function (Blueprint $table) {
            $table->id();
        
            $table->foreignId('loan_id')
                  ->unique()
                  ->constrained('loans')
                  ->cascadeOnDelete();
        
            $table->foreignId('returned_by')
                  ->constrained('users')
                  ->cascadeOnDelete();
        
            $table->foreignId('received_by')
                  ->constrained('users')
                  ->cascadeOnDelete();
        
            $table->date('return_date');
            $table->text('condition_notes')->nullable();
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('returns');
    }
};
