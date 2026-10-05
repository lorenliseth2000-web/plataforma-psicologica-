<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', [
                'respiracion', 'relajacion', 'registro_emocional', 'pausa', 'progreso'
            ]);
            $table->string('label', 120);
            $table->time('reminder_time');
            $table->enum('frequency', ['daily', 'weekdays', 'weekends', 'custom'])->default('daily');
            $table->json('days_of_week')->nullable(); // [1,2,3,4,5] = lun-vie
            $table->boolean('active')->default(true);
            $table->timestamp('last_triggered_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_reminders');
    }
};
