<?php

namespace Database\Seeders;

use App\Models\EmotionalLog;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Usuario Administrador Garantizado
        $admin = User::updateOrCreate(
            ['email' => 'admin@menteguia.com'],
            [
                'name' => 'Administrador MenteGuía',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'preferences' => [
                    'ambient_sound' => 'ocean_waves',
                    'daily_reminder' => true,
                    'notifications_enabled' => true,
                ],
                'birthdate' => '1990-05-15',
                'occupation' => 'Especialista en Psicología y Administración',
            ]
        );

        // 2. Usuario Regular Demo Garantizado
        $user = User::updateOrCreate(
            ['email' => 'usuario@menteguia.com'],
            [
                'name' => 'Estudiante / Usuario Demo',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'preferences' => [
                    'ambient_sound' => 'rain',
                    'daily_reminder' => true,
                    'notifications_enabled' => true,
                ],
                'birthdate' => '2001-09-20',
                'occupation' => 'Estudiante Universitario',
            ]
        );

        // 3. Crear registros emocionales previos de prueba para los últimos 14 días para el usuario demo
        for ($i = 14; $i >= 1; $i--) {
            $date = now()->subDays($i)->toDateString();
            EmotionalLog::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'log_date' => $date,
                ],
                [
                    'anxiety_level' => rand(3, 7),
                    'stress_level' => rand(4, 8),
                    'mood_level' => rand(2, 4),
                    'sleep_hours' => rand(55, 85) / 10,
                    'notes' => $i % 3 === 0 ? 'Día con actividades académicas y práctica de respiración.' : null,
                ]
            );
        }
    }
}
