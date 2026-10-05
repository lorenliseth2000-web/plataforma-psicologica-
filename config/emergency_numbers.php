<?php

/**
 * Números de emergencia oficiales por país (ISO 3166-1 alpha-2).
 * Fuentes: números únicos nacionales publicados por gobiernos y servicios 112/911.
 * Fácil de ampliar: agrega un código de país con sus líneas.
 */
return [
    'default' => [
        'label' => 'Número internacional de emergencias (cuando aplica)',
        'numbers' => [
            ['key' => 'emergencia_general', 'label' => 'Emergencia general', 'phone' => '112'],
        ],
        'note' => 'No pudimos confirmar un directorio local para este territorio. Usa el número de emergencias de tu país o el 112/911 si está disponible.',
    ],

    'countries' => [
        'CO' => [
            'name' => 'Colombia',
            'numbers' => [
                ['key' => 'emergencia_general', 'label' => 'Emergencias (NUSE)', 'phone' => '123'],
                ['key' => 'policia', 'label' => 'Policía', 'phone' => '123'],
                ['key' => 'ambulancia', 'label' => 'Ambulancia / salud', 'phone' => '123'],
                ['key' => 'bomberos', 'label' => 'Bomberos', 'phone' => '119'],
                ['key' => 'salud_mental', 'label' => 'Línea de la vida / salud mental', 'phone' => '106'],
            ],
            'note' => 'El 123 es el número único de emergencias en Colombia. El 106 opera como línea de escucha en varias ciudades. El 119 se usa para bomberos en muchos municipios.',
        ],
        'MX' => [
            'name' => 'México',
            'numbers' => [
                ['key' => 'emergencia_general', 'label' => 'Emergencias', 'phone' => '911'],
                ['key' => 'policia', 'label' => 'Policía', 'phone' => '911'],
                ['key' => 'ambulancia', 'label' => 'Ambulancia', 'phone' => '911'],
                ['key' => 'bomberos', 'label' => 'Bomberos', 'phone' => '911'],
                ['key' => 'salud_mental', 'label' => 'Línea de la vida', 'phone' => '8009112000'],
            ],
        ],
        'US' => [
            'name' => 'Estados Unidos',
            'numbers' => [
                ['key' => 'emergencia_general', 'label' => 'Emergencias', 'phone' => '911'],
                ['key' => 'salud_mental', 'label' => '988 Suicide & Crisis Lifeline', 'phone' => '988'],
            ],
        ],
        'CA' => [
            'name' => 'Canadá',
            'numbers' => [
                ['key' => 'emergencia_general', 'label' => 'Emergencias', 'phone' => '911'],
                ['key' => 'salud_mental', 'label' => 'Línea de crisis (988)', 'phone' => '988'],
            ],
        ],
        'ES' => [
            'name' => 'España',
            'numbers' => [
                ['key' => 'emergencia_general', 'label' => 'Emergencias', 'phone' => '112'],
                ['key' => 'policia', 'label' => 'Policía Nacional', 'phone' => '091'],
                ['key' => 'ambulancia', 'label' => 'Urgencias sanitarias', 'phone' => '061'],
                ['key' => 'salud_mental', 'label' => 'Línea de atención a la conducta suicida', 'phone' => '024'],
            ],
        ],
        'AR' => [
            'name' => 'Argentina',
            'numbers' => [
                ['key' => 'emergencia_general', 'label' => 'Emergencias', 'phone' => '911'],
                ['key' => 'ambulancia', 'label' => 'SAME / emergencias médicas', 'phone' => '107'],
                ['key' => 'bomberos', 'label' => 'Bomberos', 'phone' => '100'],
                ['key' => 'policia', 'label' => 'Policía', 'phone' => '911'],
            ],
        ],
        'CL' => [
            'name' => 'Chile',
            'numbers' => [
                ['key' => 'policia', 'label' => 'Carabineros', 'phone' => '133'],
                ['key' => 'ambulancia', 'label' => 'SAMU', 'phone' => '131'],
                ['key' => 'bomberos', 'label' => 'Bomberos', 'phone' => '132'],
                ['key' => 'salud_mental', 'label' => 'Salud Responde', 'phone' => '6003607777'],
            ],
        ],
        'PE' => [
            'name' => 'Perú',
            'numbers' => [
                ['key' => 'policia', 'label' => 'Policía Nacional', 'phone' => '105'],
                ['key' => 'bomberos', 'label' => 'Bomberos', 'phone' => '116'],
                ['key' => 'ambulancia', 'label' => 'SAMU', 'phone' => '106'],
            ],
        ],
        'EC' => [
            'name' => 'Ecuador',
            'numbers' => [
                ['key' => 'emergencia_general', 'label' => 'ECU 911', 'phone' => '911'],
            ],
        ],
        'VE' => [
            'name' => 'Venezuela',
            'numbers' => [
                ['key' => 'emergencia_general', 'label' => 'Emergencias', 'phone' => '171'],
            ],
        ],
        'BR' => [
            'name' => 'Brasil',
            'numbers' => [
                ['key' => 'policia', 'label' => 'Policía militar', 'phone' => '190'],
                ['key' => 'ambulancia', 'label' => 'SAMU', 'phone' => '192'],
                ['key' => 'bomberos', 'label' => 'Bomberos', 'phone' => '193'],
                ['key' => 'salud_mental', 'label' => 'CVV', 'phone' => '188'],
            ],
        ],
        'UY' => [
            'name' => 'Uruguay',
            'numbers' => [
                ['key' => 'emergencia_general', 'label' => 'Emergencias', 'phone' => '911'],
            ],
        ],
        'PY' => [
            'name' => 'Paraguay',
            'numbers' => [
                ['key' => 'emergencia_general', 'label' => 'Emergencias', 'phone' => '911'],
            ],
        ],
        'BO' => [
            'name' => 'Bolivia',
            'numbers' => [
                ['key' => 'policia', 'label' => 'Policía', 'phone' => '110'],
                ['key' => 'ambulancia', 'label' => 'Ambulancia', 'phone' => '118'],
                ['key' => 'bomberos', 'label' => 'Bomberos', 'phone' => '119'],
            ],
        ],
        'PA' => [
            'name' => 'Panamá',
            'numbers' => [
                ['key' => 'emergencia_general', 'label' => 'Emergencias', 'phone' => '911'],
            ],
        ],
        'CR' => [
            'name' => 'Costa Rica',
            'numbers' => [
                ['key' => 'emergencia_general', 'label' => 'Emergencias', 'phone' => '911'],
            ],
        ],
        'GT' => [
            'name' => 'Guatemala',
            'numbers' => [
                ['key' => 'policia', 'label' => 'Policía', 'phone' => '110'],
                ['key' => 'bomberos', 'label' => 'Bomberos', 'phone' => '122'],
                ['key' => 'ambulancia', 'label' => 'Ambulancia', 'phone' => '123'],
            ],
        ],
        'SV' => [
            'name' => 'El Salvador',
            'numbers' => [
                ['key' => 'emergencia_general', 'label' => 'Emergencias', 'phone' => '911'],
            ],
        ],
        'HN' => [
            'name' => 'Honduras',
            'numbers' => [
                ['key' => 'emergencia_general', 'label' => 'Emergencias', 'phone' => '911'],
            ],
        ],
        'NI' => [
            'name' => 'Nicaragua',
            'numbers' => [
                ['key' => 'emergencia_general', 'label' => 'Emergencias', 'phone' => '118'],
            ],
        ],
        'DO' => [
            'name' => 'República Dominicana',
            'numbers' => [
                ['key' => 'emergencia_general', 'label' => 'Emergencias', 'phone' => '911'],
            ],
        ],
        'PR' => [
            'name' => 'Puerto Rico',
            'numbers' => [
                ['key' => 'emergencia_general', 'label' => 'Emergencias', 'phone' => '911'],
                ['key' => 'salud_mental', 'label' => 'Línea 988', 'phone' => '988'],
            ],
        ],
        'CU' => [
            'name' => 'Cuba',
            'numbers' => [
                ['key' => 'policia', 'label' => 'Policía', 'phone' => '106'],
                ['key' => 'ambulancia', 'label' => 'Ambulancia', 'phone' => '104'],
                ['key' => 'bomberos', 'label' => 'Bomberos', 'phone' => '105'],
            ],
        ],
        'FR' => [
            'name' => 'Francia',
            'numbers' => [
                ['key' => 'emergencia_general', 'label' => 'Emergencias', 'phone' => '112'],
                ['key' => 'ambulancia', 'label' => 'SAMU', 'phone' => '15'],
                ['key' => 'policia', 'label' => 'Policía', 'phone' => '17'],
                ['key' => 'bomberos', 'label' => 'Bomberos', 'phone' => '18'],
                ['key' => 'salud_mental', 'label' => 'Prevención del suicidio', 'phone' => '3114'],
            ],
        ],
        'GB' => [
            'name' => 'Reino Unido',
            'numbers' => [
                ['key' => 'emergencia_general', 'label' => 'Emergencias', 'phone' => '999'],
                ['key' => 'emergencia_ue', 'label' => 'Emergencias (112)', 'phone' => '112'],
                ['key' => 'salud_mental', 'label' => 'Samaritans', 'phone' => '116123'],
            ],
        ],
        'DE' => [
            'name' => 'Alemania',
            'numbers' => [
                ['key' => 'emergencia_general', 'label' => 'Emergencias / bomberos', 'phone' => '112'],
                ['key' => 'policia', 'label' => 'Policía', 'phone' => '110'],
            ],
        ],
        'IT' => [
            'name' => 'Italia',
            'numbers' => [
                ['key' => 'emergencia_general', 'label' => 'Emergencias', 'phone' => '112'],
            ],
        ],
        'PT' => [
            'name' => 'Portugal',
            'numbers' => [
                ['key' => 'emergencia_general', 'label' => 'Emergencias', 'phone' => '112'],
            ],
        ],
        'AU' => [
            'name' => 'Australia',
            'numbers' => [
                ['key' => 'emergencia_general', 'label' => 'Emergencias', 'phone' => '000'],
                ['key' => 'salud_mental', 'label' => 'Lifeline', 'phone' => '131114'],
            ],
        ],
    ],
];
