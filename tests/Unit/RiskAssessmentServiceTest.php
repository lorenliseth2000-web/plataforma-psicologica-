<?php

namespace Tests\Unit;

use App\Models\Question;
use App\Models\User;
use App\Services\RiskAssessmentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RiskAssessmentServiceTest extends TestCase
{
    use RefreshDatabase;

    protected RiskAssessmentService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new RiskAssessmentService();
    }

    public function test_it_determines_low_risk_level_for_scores_up_to_seven(): void
    {
        $this->assertEquals(RiskAssessmentService::RISK_LOW, $this->service->determineRiskLevel(0));
        $this->assertEquals(RiskAssessmentService::RISK_LOW, $this->service->determineRiskLevel(4));
        $this->assertEquals(RiskAssessmentService::RISK_LOW, $this->service->determineRiskLevel(7));
    }

    public function test_it_determines_moderate_risk_level_for_scores_between_eight_and_fifteen(): void
    {
        $this->assertEquals(RiskAssessmentService::RISK_MODERATE, $this->service->determineRiskLevel(8));
        $this->assertEquals(RiskAssessmentService::RISK_MODERATE, $this->service->determineRiskLevel(12));
        $this->assertEquals(RiskAssessmentService::RISK_MODERATE, $this->service->determineRiskLevel(15));
    }

    public function test_it_determines_high_risk_level_for_scores_sixteen_or_greater(): void
    {
        $this->assertEquals(RiskAssessmentService::RISK_HIGH, $this->service->determineRiskLevel(16));
        $this->assertEquals(RiskAssessmentService::RISK_HIGH, $this->service->determineRiskLevel(22));
        $this->assertEquals(RiskAssessmentService::RISK_HIGH, $this->service->determineRiskLevel(30));
    }

    public function test_generated_feedback_uses_non_diagnostic_language(): void
    {
        $lowFeedback = $this->service->generateFeedback(RiskAssessmentService::RISK_LOW, 2, 3);
        $this->assertStringContainsString('Presentas indicadores leves o mínimos', $lowFeedback);
        $this->assertStringNotContainsString('Tienes un trastorno', $lowFeedback);
        $this->assertStringNotContainsString('Sufres de patología', $lowFeedback);

        $highFeedback = $this->service->generateFeedback(RiskAssessmentService::RISK_HIGH, 9, 8);
        $this->assertStringContainsString('Presentas indicadores significativos', $highFeedback);
        $this->assertStringContainsString('profesional de la salud mental', $highFeedback);
        $this->assertStringNotContainsString('Tienes depresión', $highFeedback);
    }

    public function test_it_processes_and_persists_complete_assessment(): void
    {
        $user = User::factory()->create();

        $q1 = Question::create([
            'type' => 'ansiedad',
            'text' => '¿Inquietud constante?',
            'weight' => 1,
            'order' => 1,
            'active' => true,
        ]);

        $q2 = Question::create([
            'type' => 'estres',
            'text' => '¿Sobrecarga de trabajo?',
            'weight' => 1,
            'order' => 2,
            'active' => true,
        ]);

        $answersMap = [
            $q1->id => 3,
            $q2->id => 2,
        ];

        $assessment = $this->service->processAssessment($user, $answersMap);

        $this->assertDatabaseHas('assessments', [
            'id' => $assessment->id,
            'user_id' => $user->id,
            'score_anxiety' => 3,
            'score_stress' => 2,
            'total_score' => 5,
            'risk_level' => 'bajo',
        ]);

        $this->assertDatabaseCount('assessment_answers', 2);
    }
}
