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
        Schema::create('techniques', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('category', ['ansiedad', 'estres']);
            $table->text('description');
            $table->text('benefits');
            $table->json('instructions'); // Array con pasos estructurados
            $table->string('animation_type')->default('breathing'); // 'breathing', 'grounding', 'muscle_relaxation', 'visualization'
            $table->string('animation_path')->nullable();
            $table->string('audio_path')->nullable();
            $table->integer('duration_minutes')->default(5);
            $table->boolean('active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('technique_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('technique_id')->constrained()->onDelete('cascade');
            $table->string('resource_type'); // 'audio', 'animation', 'pdf'
            $table->string('title')->nullable();
            $table->string('path');
            $table->timestamps();
        });

        Schema::create('technique_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('technique_id')->constrained()->onDelete('cascade');
            $table->integer('duration_seconds')->default(0);
            $table->integer('rating_before')->nullable(); // Nivel de tensión 1-10 antes
            $table->integer('rating_after')->nullable(); // Nivel de tensión 1-10 después
            $table->tinyInteger('satisfaction')->nullable(); // 1-5 estrellas
            $table->text('feedback_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('technique_sessions');
        Schema::dropIfExists('technique_media');
        Schema::dropIfExists('techniques');
    }
};
