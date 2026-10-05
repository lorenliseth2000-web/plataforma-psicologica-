<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('motivational_messages', function (Blueprint $table) {
            $table->id();
            $table->enum('category', [
                'ansiedad', 'depresion', 'estres', 'regulacion',
                'respiracion', 'autocuidado', 'descanso', 'perseverancia',
                'autoestima', 'habitos', 'busqueda_ayuda', 'progreso', 'adherencia'
            ])->index();
            $table->text('message');
            $table->json('context_tags')->nullable(); // ["alto_riesgo", "sin_ejercicios_3dias", etc.]
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('user_message_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('message_id')->constrained('motivational_messages')->onDelete('cascade');
            $table->timestamp('shown_at')->useCurrent();

            $table->index(['user_id', 'shown_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_message_log');
        Schema::dropIfExists('motivational_messages');
    }
};
