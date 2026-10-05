<?php

namespace Tests\Feature;

use App\Models\Assessment;
use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssessmentWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_assessment_form(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('assessments.create'));
        $response->assertStatus(200);
        $response->assertSee('Tamizaje de Síntomas de Ansiedad y Estrés');
    }

    public function test_user_can_submit_assessment_and_be_redirected_to_results(): void
    {
        $user = User::factory()->create();

        $questions = [];
        for ($i = 1; $i <= 10; $i++) {
            $questions[] = Question::create([
                'type' => $i <= 5 ? 'ansiedad' : 'estres',
                'text' => "Pregunta {$i}",
                'weight' => 1,
                'order' => $i,
                'active' => true,
            ]);
        }

        $answers = [];
        foreach ($questions as $q) {
            $answers[$q->id] = 2; // Valor 2
        }

        $response = $this->actingAs($user)->post(route('assessments.store'), [
            'answers' => $answers,
        ]);

        $assessment = Assessment::where('user_id', $user->id)->first();
        $this->assertNotNull($assessment);
        $this->assertEquals(20, $assessment->total_score);
        $this->assertEquals('alto', $assessment->risk_level);

        $response->assertRedirect(route('assessments.show', $assessment));
    }

    public function test_user_cannot_view_another_users_assessment(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $assessment = Assessment::create([
            'user_id' => $user1->id,
            'type' => 'general',
            'score_anxiety' => 2,
            'score_stress' => 2,
            'total_score' => 4,
            'risk_level' => 'bajo',
            'non_diagnostic_feedback' => 'Feedback',
            'completed_at' => now(),
        ]);

        $response = $this->actingAs($user2)->get(route('assessments.show', $assessment));
        $response->assertStatus(403);
    }
}
