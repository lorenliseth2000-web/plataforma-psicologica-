<?php

namespace Tests\Unit;

use App\Models\Assessment;
use App\Models\EmotionalLog;
use App\Models\Technique;
use App\Models\User;
use App\Services\AIRecommendationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AIRecommendationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected AIRecommendationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AIRecommendationService();
    }

    public function test_it_generates_clinical_fallback_without_errors(): void
    {
        $user = User::factory()->create(['name' => 'Loren']);

        $technique = Technique::create([
            'name' => 'Respiración Diafragmática',
            'slug' => 'respiracion-diafragmatica',
            'category' => 'ansiedad',
            'description' => 'Descripción de prueba',
            'benefits' => 'Beneficio de prueba',
            'instructions' => ['Paso 1', 'Paso 2'],
            'animation_type' => 'breathing',
            'duration_minutes' => 5,
            'active' => true,
        ]);

        Assessment::create([
            'user_id' => $user->id,
            'type' => 'general',
            'score_anxiety' => 4,
            'score_stress' => 8,
            'total_score' => 12,
            'risk_level' => 'moderado',
            'non_diagnostic_feedback' => 'Indicadores moderados.',
            'completed_at' => now(),
        ]);

        EmotionalLog::create([
            'user_id' => $user->id,
            'anxiety_level' => 6,
            'stress_level' => 7,
            'mood_level' => 3,
            'sleep_hours' => 6.0,
            'log_date' => now()->toDateString(),
        ]);

        $recommendation = $this->service->generate($user);

        $this->assertNotNull($recommendation);
        $this->assertStringContainsString('Loren', $recommendation->message);
        $this->assertEquals('rule_based_fallback', $recommendation->source);
        $this->assertDatabaseHas('ai_recommendations', [
            'id' => $recommendation->id,
            'user_id' => $user->id,
        ]);
    }
}
