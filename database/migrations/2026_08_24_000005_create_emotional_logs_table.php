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
        Schema::create('emotional_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedTinyInteger('anxiety_level'); // 1 a 10
            $table->unsignedTinyInteger('stress_level');  // 1 a 10
            $table->unsignedTinyInteger('mood_level');    // 1 a 5 (Muy bajo a Muy bueno)
            $table->decimal('sleep_hours', 4, 1)->default(7.0); // Horas de sueño
            $table->text('notes')->nullable();
            $table->date('log_date');
            $table->timestamps();

            // Restricción: un único registro por usuario por día
            $table->unique(['user_id', 'log_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emotional_logs');
    }
};
