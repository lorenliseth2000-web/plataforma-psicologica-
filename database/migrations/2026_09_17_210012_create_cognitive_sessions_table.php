<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cognitive_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Paso 1 — Situación y emoción
            $table->text('situation');
            $table->string('emotion')->nullable();          // ansiedad | estrés | miedo | enojo | tristeza | otro
            $table->unsignedTinyInteger('emotion_before'); // 1-10

            // Paso 2 — Pensamiento negativo
            $table->text('negative_thought');

            // Paso 3 — Evidencias
            $table->text('evidence_for')->nullable();      // Evidencias a favor
            $table->text('evidence_against')->nullable();  // Evidencias en contra

            // Paso 4 — Trampas cognitivas (JSON array de strings)
            $table->json('cognitive_traps')->nullable();

            // Paso 5 — Pensamiento alternativo
            $table->text('alternative_thought');

            // Paso 6 — Emoción después
            $table->unsignedTinyInteger('emotion_after');  // 1-10

            // Metadata
            $table->unsignedSmallInteger('duration_seconds')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cognitive_sessions');
    }
};
