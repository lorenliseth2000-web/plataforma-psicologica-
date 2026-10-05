<?php

namespace Database\Seeders;

use App\Models\Technique;
use Illuminate\Database\Seeder;

class CognitiveTechniqueSeeder extends Seeder
{
    public function run(): void
    {
        Technique::updateOrCreate(
            ['slug' => 'reestructuracion-cognitiva'],
            [
                'name'             => 'Reestructuración Cognitiva',
                'slug'             => 'reestructuracion-cognitiva',
                'category'         => 'ansiedad',
                'description'      => 'Aprende a identificar y transformar pensamientos automáticos negativos. Una técnica basada en la Terapia Cognitivo-Conductual para cambiar patrones de pensamiento que generan malestar.',
                'benefits'         => "• Permite detectar pensamientos automáticos distorsionados que amplifican el malestar.\n• Sustituye interpretaciones negativas por alternativas más realistas y equilibradas.\n• Reduce la intensidad de emociones difíciles como la ansiedad, la tristeza y la ira.\n• Desarrolla una perspectiva más flexible y compasiva ante los eventos cotidianos.",
                'instructions'     => [
                    'Identifica la situación: Describe brevemente el evento o situación que desencadenó la emoción incómoda.',
                    'Registra la emoción: Nombra la emoción que sentiste (ej. ansiedad, tristeza, rabia) y ponle una intensidad del 0 al 10.',
                    'Detecta el pensamiento automático: ¿Qué frase o imagen cruzó por tu mente justo en ese momento? Escríbela tal como surgió.',
                    'Examina la evidencia: Escribe los hechos reales que apoyan ese pensamiento y los hechos que lo contradicen.',
                    'Cuestiona las distorsiones: ¿Estás catastrofizando, generalizando o leyendo la mente? Identifica el tipo de distorsión cognitiva presente.',
                    'Construye un pensamiento alternativo: Formula una interpretación más equilibrada y realista de la situación, basada en la evidencia.',
                    'Re-evalúa la emoción: Vuelve a puntuar la intensidad de la emoción del 0 al 10 con el nuevo pensamiento en mente.',
                ],
                'animation_type'   => 'visualization',
                'duration_minutes' => 10,
                'order'            => 18,
                'active'           => true,
            ]
        );
    }
}
