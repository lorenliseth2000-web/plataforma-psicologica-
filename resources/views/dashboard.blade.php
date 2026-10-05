@extends('layouts.app')

@section('title', 'Mi Espacio de Bienestar')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <!-- Header de Bienvenida -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs">
            <div>
                <div class="inline-flex items-center gap-2 text-xs font-semibold px-3 py-1 rounded-full mb-2" style="color: #7D9B76; background: #EAF2E9;">
                    <span class="w-2 h-2 rounded-full" style="background: #7D9B76;"></span>
                    {{ now()->translatedFormat('l, d \d\e F \d\e Y') }}
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 flex items-center gap-2">
                    Hola, {{ $user->name }}
                    <svg class="w-6 h-6" style="color: #7D9B76;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11"/>
                    </svg>
                </h1>
                <p class="text-slate-500 text-sm mt-1">
                    Bienvenido(a) a tu espacio diario de autorregulación y bienestar emocional.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                @if(!$todayLog)
                    <a href="{{ route('emotional-logs.create') }}" class="px-5 py-2.5 rounded-xl text-white font-bold text-sm hover:opacity-95 transition shadow-md flex items-center gap-2" style="background: linear-gradient(135deg, #7D9B76, #8FAF88);">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Registrar Estado de Hoy
                    </a>
                @else
                    <div class="px-4 py-2 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-semibold flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Check-in de hoy completado</span>
                    </div>
                @endif

                <a href="{{ route('assessments.create') }}" class="px-4 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-700 font-semibold text-sm hover:bg-slate-50 transition shadow-xs flex items-center gap-1.5">
                    <svg class="w-4 h-4" style="color: #7D9B76;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Nuevo Tamizaje
                </a>
            </div>
        </div>

        <!-- Alerta Prioritaria de Emergencia (Si el riesgo es Alto) -->
        @if($latestAssessment && $latestAssessment->isHighRisk())
            <div class="bg-rose-50 border-2 border-rose-300 rounded-3xl p-6 sm:p-8 text-rose-900 shadow-md">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                    <div class="space-y-2 max-w-3xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-200 text-rose-900 text-xs font-extrabold uppercase tracking-wide">
                            <svg class="w-4 h-4 text-rose-700 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            Orientación Prioritaria Recomendada
                        </div>
                        <h2 class="text-xl font-extrabold text-slate-900">
                            Tu última evaluación muestra indicadores significativos de malestar.
                        </h2>
                        <p class="text-xs sm:text-sm text-rose-800 leading-relaxed">
                            Cuidar de ti es lo más importante. Te sugerimos acudir a valoración con un profesional de la salud mental. Puedes comunicarte en cualquier momento de forma gratuita y confidencial con las siguientes líneas de atención:
                        </p>
                    </div>

                    <div class="flex flex-wrap md:flex-col gap-2 shrink-0">
                        <a href="tel:106" class="px-5 py-3 rounded-xl bg-rose-600 text-white font-bold text-xs hover:bg-rose-700 transition flex items-center justify-center gap-2 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            Llamar a Línea 106 (24/7)
                        </a>
                        <a href="{{ route('routes.index') }}" class="px-5 py-3 rounded-xl bg-white border border-rose-300 text-rose-800 font-bold text-xs hover:bg-rose-100/50 transition text-center">
                            Ver Directorio de Rutas
                        </a>
                    </div>
                </div>
            </div>
        @endif


        <!-- Grid Principal: Estado de Evaluación + Recomendación IA -->
        <div class="grid lg:grid-cols-12 gap-8">
            
            <!-- Columna Izquierda: Estado del Tamizaje (4 columnas) -->
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white p-6 rounded-3xl shadow-xs space-y-4" style="border: 1px solid #D4E5D2;">
                    <div class="flex items-center justify-between">
                        <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                            <svg class="w-5 h-5" style="color: #7D9B76;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            Estado Clínico Actual
                        </h2>
                        @if($latestAssessment)
                            <a href="{{ route('assessments.show', $latestAssessment) }}" class="text-xs font-semibold" style="color: #7D9B76;">Ver informe &rarr;</a>
                        @endif
                    </div>

                    @if($latestAssessment)
                        <!-- Badge de Nivel de Riesgo -->
                        <div class="p-4 rounded-2xl {{ $latestAssessment->risk_level === 'alto' ? 'bg-rose-50 border border-rose-200' : ($latestAssessment->risk_level === 'moderado' ? 'bg-amber-50 border border-amber-200' : 'bg-emerald-50 border border-emerald-200') }}">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Nivel de Tensión:</span>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-extrabold {{ $latestAssessment->risk_level === 'alto' ? 'bg-rose-200 text-rose-900' : ($latestAssessment->risk_level === 'moderado' ? 'bg-amber-200 text-amber-900' : 'bg-emerald-200 text-emerald-900') }}">
                                    {{ ucfirst($latestAssessment->risk_level) }}
                                </span>
                            </div>

                            <div class="grid grid-cols-2 gap-2 text-center py-2">
                                <div class="bg-white/80 p-2.5 rounded-xl">
                                    <div class="text-xs text-slate-500 font-medium">Ansiedad</div>
                                    <div class="text-lg font-black" style="color: #7D9B76;">{{ $latestAssessment->score_anxiety }} <span class="text-[10px] text-slate-400 font-normal">/15</span></div>
                                </div>
                                <div class="bg-white/80 p-2.5 rounded-xl">
                                    <div class="text-xs text-slate-500 font-medium">Estrés</div>
                                    <div class="text-lg font-black" style="color: #4A6A55;">{{ $latestAssessment->score_stress }} <span class="text-[10px] text-slate-400 font-normal">/15</span></div>
                                </div>
                            </div>

                            <div class="text-[11px] text-slate-500 mt-2 text-center">
                                Completado el {{ $latestAssessment->completed_at->format('d/m/Y h:i A') }}
                            </div>
                        </div>

                        <p class="text-xs text-slate-600 leading-relaxed italic bg-slate-50 p-3 rounded-xl border border-slate-100">
                            "{{ Str::limit($latestAssessment->non_diagnostic_feedback, 160) }}"
                        </p>
                    @else
                        <div class="text-center py-6 space-y-3 bg-slate-50 rounded-2xl border border-dashed border-slate-200 p-4">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center mx-auto" style="background: #EAF2E9; color: #7D9B76;">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-slate-800">Aún no has completado tu evaluación</h3>
                                <p class="text-xs text-slate-500 mt-1">Realiza el cuestionario de 10 preguntas para obtener recomendaciones personalizadas de autorregulación.</p>
                            </div>
                            <a href="{{ route('assessments.create') }}" class="inline-block px-4 py-2 rounded-xl text-white font-bold text-xs hover:opacity-90 transition shadow-xs" style="background: #7D9B76;">
                                Iniciar Tamizaje Ahora
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Resumen de Estadísticas de Uso -->
                <div class="grid grid-cols-3 gap-3">
                    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 text-center">
                        <div class="text-xl font-black" style="color: #7D9B76;">{{ $totalAssessments }}</div>
                        <div class="text-[10px] text-slate-500 font-medium">Tamizajes</div>
                    </div>
                    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 text-center">
                        <div class="text-xl font-black" style="color: #4A6A55;">{{ $totalSessions }}</div>
                        <div class="text-[10px] text-slate-500 font-medium">Sesiones</div>
                    </div>
                    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 text-center">
                        <div class="text-xl font-black" style="color: #7D9B76;">{{ $totalLogs }}</div>
                        <div class="text-[10px] text-slate-500 font-medium">Días Diario</div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Widget de Recomendación de IA (8 columnas) -->
            <div class="lg:col-span-8 space-y-6">
                <div class="rounded-3xl p-6 sm:p-8 shadow-lg relative overflow-hidden" style="background: linear-gradient(135deg, #2C3E35 0%, #3D5247 100%); color: white;">
                    <div class="flex items-center justify-between pb-4 border-b" style="border-color: rgba(181,201,179,0.25);">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl flex items-center justify-center font-black text-sm shadow-md text-white" style="background: linear-gradient(135deg, #7D9B76, #8FAF88);">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-white flex items-center gap-2">
                                    Recomendación Inteligente Personalizada
                                    <span class="text-[10px] px-2 py-0.5 rounded-full font-medium" style="background: rgba(125,155,118,0.2); color: #8FAF88; border: 1px solid rgba(125,155,118,0.3);">
                                        {{ $aiRecommendation->source === 'openai' ? 'OpenAI GPT' : ($aiRecommendation->source === 'gemini' ? 'Gemini IA' : 'Motor Clínico Inteligente') }}
                                    </span>
                                </h2>
                                <div class="text-[11px]" style="color: #B5C9B3;">
                                    Generada {{ $aiRecommendation->generated_at ? $aiRecommendation->generated_at->diffForHumans() : 'recientemente' }}
                                </div>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('ai.refresh') }}">
                            @csrf
                            <button type="submit" title="Regenerar sugerencia según tus últimos datos" class="p-2 rounded-xl text-xs font-semibold transition flex items-center gap-1" style="background: rgba(125,155,118,0.15); color: #B5C9B3;">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                <span class="hidden sm:inline">Actualizar</span>
                            </button>
                        </form>
                    </div>

                    <div class="py-5 text-sm sm:text-base font-normal leading-relaxed whitespace-pre-line space-y-3" style="color: #D4E5D2;">
                        {!! nl2br(e($aiRecommendation->message)) !!}
                    </div>

                    <div class="pt-4 border-t flex flex-wrap items-center justify-between gap-4" style="border-color: rgba(181,201,179,0.25);">
                        <div class="text-xs flex items-center gap-1.5" style="color: #B5C9B3;">
                            <svg class="w-4 h-4" style="color: #8FAF88;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            <span>Reglas de guardia activas • Lenguaje no patologizante</span>
                        </div>

                        <a href="{{ route('techniques.index') }}" class="px-4 py-2 rounded-xl font-bold text-xs transition flex items-center gap-1 shadow-sm" style="background: #7D9B76; color: white;">
                            Explorar Todas las Técnicas &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección de Analítica y Gráficas de Progreso (Chart.js) -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5" style="color: #7D9B76;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        Evolución de Tu Bienestar Emocional (Últimos 30 Días)
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Seguimiento comparativo entre niveles de ansiedad, estrés, estado de ánimo y horas de descanso.</p>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('emotional-logs.index') }}" class="text-xs font-semibold px-3 py-1.5 rounded-lg" style="color: #7D9B76; background: #EAF2E9;">
                        Ver Historial Completo &rarr;
                    </a>
                </div>
            </div>

            @if(count($chartLabels) > 0)
                <div class="relative h-72 sm:h-80 w-full">
                    <canvas id="emotionalProgressChart"></canvas>
                </div>
            @else
                <div class="text-center py-12 bg-slate-50 rounded-2xl border border-dashed border-slate-200 space-y-3">
                    <p class="text-sm font-semibold text-slate-700">Aún no hay registros en tu diario de bienestar</p>
                    <p class="text-xs text-slate-500 max-w-md mx-auto">Comienza a registrar tu nivel diario de ansiedad, estrés y horas de sueño para ver reflejadas tus tendencias aquí.</p>
                    <a href="{{ route('emotional-logs.create') }}" class="inline-block px-4 py-2 rounded-xl text-white font-bold text-xs hover:opacity-90 transition" style="background: #7D9B76;">
                        Crear Primer Registro
                    </a>
                </div>
            @endif
        </div>

        <!-- Técnicas Recomendadas para Hoy -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Técnicas Sugeridas para Ti</h2>
                    <p class="text-xs text-slate-500">Ejercicios seleccionados según tus niveles de tensión dominantes.</p>
                </div>
                <a href="{{ route('techniques.index') }}" class="text-xs font-semibold" style="color: #7D9B76;">Ver catálogo completo (11) &rarr;</a>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($recommendedTechniques as $technique)
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs hover:shadow-md transition flex flex-col justify-between">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider"
                                      style="{{ $technique->category === 'ansiedad' ? 'background: #EAF2E9; color: #4A6A55;' : 'background: #D4E5D2; color: #2C3E35;' }}">
                                    {{ ucfirst($technique->category) }}
                                </span>
                                <span class="text-xs font-semibold text-slate-500 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $technique->duration_minutes }} min
                                </span>
                            </div>

                            <h3 class="text-base font-extrabold text-slate-900">
                                {{ $technique->name }}
                            </h3>

                            <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed">
                                {{ $technique->description }}
                            </p>
                        </div>

                        <div class="pt-5 border-t border-slate-100 mt-4">
                            <a href="{{ route('techniques.show', $technique) }}" class="w-full py-2.5 rounded-xl text-white font-bold text-xs text-center transition flex items-center justify-center gap-2" style="background: linear-gradient(135deg, #7D9B76, #4A6A55);">
                                <span>Iniciar Práctica Guiada</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
@if(count($chartLabels) > 0)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('emotionalProgressChart');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [
                {
                    label: 'Ansiedad (1-10)',
                    data: @json($anxietyData),
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    borderWidth: 2.5,
                    tension: 0.35,
                    fill: false,
                    pointRadius: 4,
                    pointBackgroundColor: '#6366f1'
                },
                {
                    label: 'Estrés (1-10)',
                    data: @json($stressData),
                    borderColor: '#0d9488',
                    backgroundColor: 'rgba(13, 148, 136, 0.1)',
                    borderWidth: 2.5,
                    tension: 0.35,
                    fill: false,
                    pointRadius: 4,
                    pointBackgroundColor: '#0d9488'
                },
                {
                    label: 'Horas de Sueño',
                    data: @json($sleepData),
                    borderColor: '#8b5cf6',
                    borderDash: [5, 5],
                    borderWidth: 2,
                    tension: 0.35,
                    fill: false,
                    pointRadius: 3,
                    pointBackgroundColor: '#8b5cf6'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        boxWidth: 8,
                        font: { family: 'Plus Jakarta Sans', size: 12, weight: 600 }
                    }
                },
                tooltip: {
                    padding: 12,
                    cornerRadius: 12,
                    titleFont: { family: 'Plus Jakarta Sans', weight: 'bold' },
                    bodyFont: { family: 'Plus Jakarta Sans' }
                }
            },
            scales: {
                y: {
                    min: 0,
                    max: 10,
                    grid: { color: '#f1f5f9' },
                    ticks: { font: { family: 'Plus Jakarta Sans', size: 11 } }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { family: 'Plus Jakarta Sans', size: 11 } }
                }
            }
        }
    });
});
</script>
@endif
@endpush

{{-- ===== TOAST FRASE MOTIVACIONAL — 1 por inicio de sesión (secuencial 1..1000) ===== --}}
@php
    $loginPhrase = session('login_motivational_phrase');
    $showToast = session('show_login_toast', false);

    if (!$loginPhrase && auth()->check()) {
        $loginPhrase = app(\App\Services\MotivationalMessageService::class)->prepareLoginPhraseForSession(auth()->user());
        $showToast = true;
    }

    // Se muestra exclusivamente una vez por cada inicio de sesión
    session()->forget('show_login_toast');
@endphp

@if($showToast && !empty($loginPhrase))
<div id="motivational-toast"
     style="
         position: fixed;
         bottom: 28px;
         right: 24px;
         max-width: 340px;
         z-index: 9999;
         transform: translateY(20px);
         opacity: 0;
         transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
         pointer-events: none;
     "
     aria-live="polite"
     aria-label="Frase motivacional de bienvenida">
    <div style="
             background: linear-gradient(135deg, rgba(234,242,233,0.98) 0%, rgba(245,250,244,0.98) 100%);
             backdrop-filter: blur(16px);
             border: 1px solid #B5C9B3;
             border-radius: 20px;
             padding: 16px 18px;
             box-shadow: 0 8px 32px rgba(125,155,118,0.20), 0 2px 8px rgba(0,0,0,0.06);
         ">
        <div style="display: flex; align-items: flex-start; gap: 10px;">
            {{-- Ícono hoja --}}
            <div style="
                     width: 32px; height: 32px;
                     background: #7D9B76;
                     border-radius: 10px;
                     display: flex; align-items: center; justify-content: center;
                     flex-shrink: 0; margin-top: 2px;">
                <svg width="16" height="16" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </div>

            <div style="flex: 1; min-width: 0;">
                <p style="font-size: 10px; font-weight: 700; color: #8FAF88; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 4px;">
                    Sukha · Para ti hoy
                </p>
                <p style="font-size: 13px; line-height: 1.55; color: #2C3E35; font-family: 'Playfair Display', Georgia, serif; font-style: italic;">
                    "{{ $loginPhrase }}"
                </p>
            </div>

            {{-- Botón cerrar --}}
            <button onclick="dismissToast()"
                    style="
                        background: none; border: none; cursor: pointer;
                        color: #B5C9B3; font-size: 18px; line-height: 1;
                        padding: 0; flex-shrink: 0; margin-top: -2px;
                        pointer-events: all;
                    "
                    aria-label="Cerrar">
                &times;
            </button>
        </div>

        {{-- Barra de progreso de auto-cierre --}}
        <div id="toast-progress" style="
                 height: 2px;
                 background: #B5C9B3;
                 border-radius: 2px;
                 margin-top: 10px;
                 width: 100%;
                 transform-origin: left;
                 animation: toastProgress 7s 2s linear forwards;
             "></div>
    </div>
</div>

<style>
@keyframes toastProgress {
    from { transform: scaleX(1); }
    to   { transform: scaleX(0); }
}
</style>

<script>
(function() {
    const toast = document.getElementById('motivational-toast');
    if (!toast) return;

    let dismissTimer;

    // Muestra el toast 1.5s después de cargar
    setTimeout(() => {
        toast.style.opacity = '1';
        toast.style.transform = 'translateY(0)';
        toast.style.pointerEvents = 'all';
    }, 1500);

    // Auto-cierra a los 9s (1.5s de espera + 7.5s visible)
    dismissTimer = setTimeout(() => dismissToast(), 9000);

    window.dismissToast = function() {
        clearTimeout(dismissTimer);
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(12px)';
        toast.style.pointerEvents = 'none';
        setTimeout(() => toast.remove(), 500);
    };
})();
</script>
@endif
