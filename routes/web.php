<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AttentionRouteManagementController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\QuestionManagementController;
use App\Http\Controllers\Admin\TechniqueManagementController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\AiRecommendationController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\AttentionRouteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmotionalLogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\TechniqueController;
use App\Models\AttentionRoute;
use App\Models\SystemSetting;
use App\Models\Technique;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - MenteGuía IA
|--------------------------------------------------------------------------
*/

// 1. Landing Page Pública (RF-01 a RF-04)
Route::get('/', function () {
    $featuredTechniques = Technique::active()->take(4)->get();
    $emergencyNumber = SystemSetting::get('emergency_primary_phone', '106');
    $emergencyRoutes = AttentionRoute::emergencies()->take(3)->get();
    return view('welcome', compact('featuredTechniques', 'emergencyNumber', 'emergencyRoutes'));
})->name('home');

// 2. Rutas del Usuario Autenticado
Route::middleware(['auth'])->group(function () {

    // Dashboard principal
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Módulo 2: Tamizaje y Scoring Clínico
    Route::prefix('evaluaciones')->name('assessments.')->group(function () {
        Route::get('/', [AssessmentController::class, 'index'])->name('index');
        Route::get('/nueva', [AssessmentController::class, 'create'])->name('create');
        Route::post('/', [AssessmentController::class, 'store'])->name('store');
        Route::get('/{assessment}', [AssessmentController::class, 'show'])->name('show');
    });

    // Módulo 3: Biblioteca de Técnicas de Regulación
    Route::prefix('tecnicas')->name('techniques.')->group(function () {
        Route::get('/', [TechniqueController::class, 'index'])->name('index');
        Route::get('/{technique:slug}', [TechniqueController::class, 'show'])->name('show');
        Route::post('/{technique}/sesion', [TechniqueController::class, 'storeSession'])->name('session.store');
    });

    // Módulo 4: Seguimiento Emocional Diario
    Route::prefix('diario-emocional')->name('emotional-logs.')->group(function () {
        Route::get('/', [EmotionalLogController::class, 'index'])->name('index');
        Route::get('/nuevo', [EmotionalLogController::class, 'create'])->name('create');
        Route::post('/', [EmotionalLogController::class, 'store'])->name('store');
        Route::get('/datos-grafica', [EmotionalLogController::class, 'chartData'])->name('chart-data');
        Route::get('/reporte-imprimible', [EmotionalLogController::class, 'printable'])->name('printable');
    });

    // Módulo 5: Motor de Recomendación IA
    Route::post('/recomendacion-ia/refrescar', [AiRecommendationController::class, 'refresh'])->name('ai.refresh');

    // Módulo 6: Rutas de Atención y Emergencias
    Route::get('/rutas-de-atencion', [AttentionRouteController::class, 'index'])->name('routes.index');

    // Perfil y Preferencias
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/perfil', [ProfileController::class, 'edit']);

    // Módulo 7: Recordatorios
    Route::prefix('recordatorios')->name('reminders.')->group(function () {
        Route::get('/', [ReminderController::class, 'index'])->name('index');
        Route::post('/', [ReminderController::class, 'store'])->name('store');
        Route::patch('/{reminder}', [ReminderController::class, 'toggle'])->name('toggle');
        Route::delete('/{reminder}', [ReminderController::class, 'destroy'])->name('destroy');
    });

    // Módulo 8: Relajación Guiada por Voz
    Route::prefix('relajacion')->name('relaxation.')->group(function () {
        Route::get('/', [\App\Http\Controllers\RelaxationController::class, 'index'])->name('index');
        Route::get('/{session}', [\App\Http\Controllers\RelaxationController::class, 'show'])->name('show');
    });

    // Módulo 9: Emergencias por Ubicación
    Route::get('/emergencias', [\App\Http\Controllers\EmergencyController::class, 'index'])->name('emergency.index');
    Route::post('/emergencias/buscar', [\App\Http\Controllers\EmergencyController::class, 'lookup'])->name('emergency.lookup');

    // Módulo 10: Chatbot Emocional
    Route::get('/acompanante', [\App\Http\Controllers\ChatbotController::class, 'index'])->name('chatbot.index');
    Route::post('/acompanante/mensaje', [\App\Http\Controllers\ChatbotController::class, 'send'])->name('chatbot.send')->middleware('throttle:40,1');

    // Módulo 11: Reestructuración Cognitiva — guardar sesión
    Route::post('/tecnicas/{technique}/cognitiva', [\App\Http\Controllers\TechniqueController::class, 'storeCognitive'])->name('techniques.cognitive.store');
    Route::get('/mi-historial-cognitivo', [\App\Http\Controllers\TechniqueController::class, 'cognitiveHistory'])->name('techniques.cognitive.history');

    // Servicio de Voz Guiada (ElevenLabs Rudra / Cache)
    Route::post('/voz-guiada/sintetizar', [\App\Http\Controllers\VoiceController::class, 'synthesize'])->name('voice.synthesize');
});

// 3. Panel Administrativo (Protegido por Middleware 'admin')
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Gestión de Usuarios
    Route::get('/usuarios', [UserManagementController::class, 'index'])->name('users.index');
    Route::patch('/usuarios/{user}/rol', [UserManagementController::class, 'updateRole'])->name('users.role');
    Route::delete('/usuarios/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');

    // Gestión de Técnicas
    Route::resource('tecnicas', TechniqueManagementController::class)->names('techniques');

    // Gestión de Preguntas de Tamizaje
    Route::resource('preguntas', QuestionManagementController::class)->names('questions');

    // Gestión de Rutas de Atención y Ajustes
    Route::resource('rutas', AttentionRouteManagementController::class)->names('routes');
    Route::post('/rutas/configuracion', [AttentionRouteManagementController::class, 'updateSettings'])->name('routes.settings');

    // Registro de Auditoría
    Route::get('/auditoria', [AuditLogController::class, 'index'])->name('logs.index');
});

// 4. Rutas de Autenticación de Breeze
require __DIR__.'/auth.php';

