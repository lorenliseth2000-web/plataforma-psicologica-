<?php

namespace Database\Seeders;

use App\Models\AttentionRoute;
use App\Models\SystemSetting;
use Illuminate\Database\Seeder;

class AttentionRouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $routes = [
            [
                'name' => 'Línea 106 — Ayuda y Orientación en Salud Mental',
                'institution' => 'Secretaría de Salud / Red de Salud Pública',
                'category' => 'emergencia',
                'phone' => '106',
                'whatsapp' => '+573007548933',
                'email' => 'linea106@saludcapital.gov.co',
                'website' => 'https://saludcapital.gov.co',
                'available_hours' => '24 horas / 7 días a la semana (Gratuita)',
                'risk_level' => 'alto',
                'is_emergency' => true,
                'description' => 'Línea de escucha profesional, gratuita y confidencial para orientación en situaciones de angustia, crisis emocional y salud mental.',
                'order' => 1,
                'active' => true,
            ],
            [
                'name' => 'Línea 192 — Opción 4 (Apoyo Psicosocial)',
                'institution' => 'Ministerio de Salud y Protección Social',
                'category' => 'emergencia',
                'phone' => '192',
                'whatsapp' => null,
                'email' => 'contacto@minsalud.gov.co',
                'website' => 'https://www.minsalud.gov.co',
                'available_hours' => '24 horas / 7 días a la semana',
                'risk_level' => 'alto',
                'is_emergency' => true,
                'description' => 'Servicio nacional de teleorientación en salud mental atendido por psicólogos clínicos para contención en crisis y primeros auxilios psicológicos.',
                'order' => 2,
                'active' => true,
            ],
            [
                'name' => 'Línea Única de Emergencias 123',
                'institution' => 'Centro de Comando y Control de Emergencias',
                'category' => 'emergencia',
                'phone' => '123',
                'whatsapp' => null,
                'email' => null,
                'website' => null,
                'available_hours' => '24/7 Inmediata',
                'risk_level' => 'alto',
                'is_emergency' => true,
                'description' => 'Número único para la atención inmediata de cualquier emergencia médica o psicológica urgente.',
                'order' => 3,
                'active' => true,
            ],
            [
                'name' => 'Centro de Atención Psicológica Universitaria (CAP)',
                'institution' => 'Red de Bienestar Institucional y Universitario',
                'category' => 'universitaria',
                'phone' => '(601) 3239300 Ext. 1420',
                'whatsapp' => '+573124567890',
                'email' => 'bienestar.psicologia@institucion.edu.co',
                'website' => 'https://institucion.edu.co/bienestar/psicologia',
                'available_hours' => 'Lunes a Viernes de 8:00 a.m. a 6:00 p.m.',
                'risk_level' => 'moderado',
                'is_emergency' => false,
                'description' => 'Servicio de consulta y acompañamiento psicológico presencial y virtual para estudiantes y trabajadores de la comunidad académica.',
                'order' => 4,
                'active' => true,
            ],
            [
                'name' => 'Red de Prestadores de Salud / EPS',
                'institution' => 'Sistema General de Seguridad Social en Salud',
                'category' => 'eps',
                'phone' => 'Línea de atención de tu EPS',
                'whatsapp' => null,
                'email' => null,
                'website' => null,
                'available_hours' => 'Horario habitual de citas de tu entidad',
                'risk_level' => 'moderado',
                'is_emergency' => false,
                'description' => 'Puedes solicitar cita de medicina general o psicología directa a través de tu entidad de salud (EPS o medicina prepagada) para un seguimiento clínico regular.',
                'order' => 5,
                'active' => true,
            ],
            [
                'name' => 'Directorio de Talleres y Educación en Autocuidado',
                'institution' => 'Plataforma MenteGuía IA & Organizaciones Aliadas',
                'category' => 'publica',
                'phone' => null,
                'whatsapp' => null,
                'email' => 'contacto@menteguia.com',
                'website' => 'https://menteguia.com/recursos',
                'available_hours' => 'Acceso digital permanente',
                'risk_level' => 'bajo',
                'is_emergency' => false,
                'description' => 'Recursos educativos abiertos, guías de higiene del sueño, webinars de gestión del tiempo y estrategias de reducción del estrés.',
                'order' => 6,
                'active' => true,
            ],
        ];

        foreach ($routes as $route) {
            AttentionRoute::updateOrCreate(
                ['name' => $route['name']],
                $route
            );
        }

        // Configuraciones globales de emergencia
        SystemSetting::set('emergency_primary_phone', '106', 'emergencia', 'Teléfono principal de emergencia psicológica');
        SystemSetting::set('emergency_national_phone', '192', 'emergencia', 'Línea nacional de teleorientación psicológica');
        SystemSetting::set('emergency_general_phone', '123', 'emergencia', 'Línea de emergencias generales');
        SystemSetting::set('legal_disclaimer', 'MenteGuía IA es una herramienta preventiva y de orientación. No realiza diagnósticos médicos ni sustituye la consulta con un profesional de la salud mental.', 'legal', 'Texto legal visible en la plataforma');
    }
}
