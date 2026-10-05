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
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type')->default('general'); // 'ansiedad', 'estres', 'general'
            $table->integer('score_anxiety')->default(0);
            $table->integer('score_stress')->default(0);
            $table->integer('total_score')->default(0);
            $table->enum('risk_level', ['bajo', 'moderado', 'alto'])->default('bajo');
            $table->text('non_diagnostic_feedback');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('assessment_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained()->onDelete('cascade');
            $table->foreignId('question_id')->constrained()->onDelete('cascade');
            $table->integer('value'); // 0 a 3
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_answers');
        Schema::dropIfExists('assessments');
    }
};
