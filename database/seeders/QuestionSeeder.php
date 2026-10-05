<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questions = [
            // Cuestionario de Ansiedad (Items 1 a 5)
            [
                'type' => 'ansiedad',
                'symptom_focus' => 'preocupacion_constante',
                'text' => '¿Con qué frecuencia te has sentido abrumado(a) por pensamientos repetitivos o preocupaciones que te cuesta controlar?',
                'weight' => 1,
                'order' => 1,
                'active' => true,
            ],
            [
                'type' => 'ansiedad',
                'symptom_focus' => 'nerviosismo',
                'text' => '¿Con qué frecuencia has experimentado nerviosismo, sobresaltos inesperados o sensación de estar "al límite"?',
                'weight' => 1,
                'order' => 2,
                'active' => true,
            ],
            [
                'type' => 'ansiedad',
                'symptom_focus' => 'tension_corporal',
                'text' => '¿Con qué frecuencia notas tensión muscular (cuello, hombros, mandíbula apretada) ante situaciones de incertidumbre?',
                'weight' => 1,
                'order' => 3,
                'active' => true,
            ],
            [
                'type' => 'ansiedad',
                'symptom_focus' => 'inquietud_motora',
                'text' => '¿Con qué frecuencia experimentas inquietud interna o dificultad para permanecer en quietud y calma?',
                'weight' => 1,
                'order' => 4,
                'active' => true,
            ],
            [
                'type' => 'ansiedad',
                'symptom_focus' => 'dificultad_relajacion',
                'text' => '¿Con qué frecuencia sientes que te resulta difícil desconectar mentalmente y lograr un estado genuino de relajación?',
                'weight' => 1,
                'order' => 5,
                'active' => true,
            ],

            // Cuestionario de Estrés (Items 6 a 10)
            [
                'type' => 'estres',
                'symptom_focus' => 'sobrecarga_responsabilidades',
                'text' => '¿Con qué frecuencia sientes que las tareas académicas, laborales o personales superan tu capacidad de gestión o tiempo disponible?',
                'weight' => 1,
                'order' => 6,
                'active' => true,
            ],
            [
                'type' => 'estres',
                'symptom_focus' => 'fatiga_agotamiento',
                'text' => '¿Con qué frecuencia experimentas agotamiento físico o mental aun después de haber tenido momentos de pausa?',
                'weight' => 1,
                'order' => 7,
                'active' => true,
            ],
            [
                'type' => 'estres',
                'symptom_focus' => 'irritabilidad',
                'text' => '¿Con qué frecuencia sientes menor tolerancia a la frustración, impaciencia o irritabilidad ante pequeños imprevistos?',
                'weight' => 1,
                'order' => 8,
                'active' => true,
            ],
            [
                'type' => 'estres',
                'symptom_focus' => 'problemas_concentracion',
                'text' => '¿Con qué frecuencia te resulta difícil sostener la concentración en lecturas, proyectos o actividades laborales?',
                'weight' => 1,
                'order' => 9,
                'active' => true,
            ],
            [
                'type' => 'estres',
                'symptom_focus' => 'alteraciones_sueno',
                'text' => '¿Con qué frecuencia presentas dificultades para conciliar el sueño, despertares nocturnos o sensación de sueño no reparador?',
                'weight' => 1,
                'order' => 10,
                'active' => true,
            ],
        ];

        foreach ($questions as $q) {
            Question::updateOrCreate(
                ['order' => $q['order']],
                $q
            );
        }
    }
}
