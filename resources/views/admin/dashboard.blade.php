@extends('layouts.app')

@section('title', 'Panel de Administración - Sukha')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <!-- Encabezado Admin -->
        <div class="bg-gradient-to-r from-purple-900 via-indigo-900 to-slate-900 text-white p-6 sm:p-8 rounded-3xl shadow-lg flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-purple-300 bg-purple-500/20 px-3 py-1 rounded-full border border-purple-400/30">
                    Centro de Control Clínico y Operativo
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold mt-2">
                    Panel Administrativo Sukha
                </h1>
                <p class="text-xs text-purple-200 mt-1">
                    Supervisión global de usuarios, evaluaciones por nivel de riesgo, uso de técnicas y auditoría de datos.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs transition">
                    Gestionar Usuarios
                </a>
                <a href="{{ route('admin.techniques.index') }}" class="px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs transition">
                    Técnicas
                </a>
                <a href="{{ route('admin.questions.index') }}" class="px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs transition">
                    Preguntas
                </a>
                <a href="{{ route('admin.routes.index') }}" class="px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs transition">
                    Rutas & Emergencia
                </a>
                <a href="{{ route('admin.logs.index') }}" class="px-4 py-2 rounded-xl bg-purple-500 text-slate-950 font-bold text-xs hover:bg-purple-400 transition">
                    Auditoría
                </a>
            </div>
        </div>

        <!-- 4 Tarjetas de Métricas Principales -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Usuarios Registrados</span>
                <span class="text-3xl font-black text-indigo-600 mt-1 block">{{ $totalUsers }}</span>
                <span class="text-[11px] text-slate-400 mt-1 block">Cuentas activas en el sistema</span>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Tamizajes Completados</span>
                <span class="text-3xl font-black text-teal-600 mt-1 block">{{ $totalAssessments }}</span>
                <span class="text-[11px] text-slate-400 mt-1 block">Evaluaciones clínicas aplicadas</span>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Sesiones de Técnicas</span>
                <span class="text-3xl font-black text-purple-600 mt-1 block">{{ $totalSessions }}</span>
                <span class="text-[11px] text-slate-400 mt-1 block">Prácticas terapéuticas realizadas</span>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Satisfacción Promedio</span>
                <span class="text-3xl font-black text-amber-500 mt-1 block">{{ $avgSatisfaction }} <span class="text-sm text-slate-400">/ 5</span></span>
                <span class="text-[11px] text-slate-400 mt-1 block">Calificación de retroalimentación</span>
            </div>
        </div>

        <!-- Gráfico de Distribución de Riesgo + Técnicas Más Populares -->
        <div class="grid lg:grid-cols-12 gap-8">
            
            <!-- Distribución por Nivel de Riesgo (5 columnas) -->
            <div class="lg:col-span-5 bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-6">
                <div>
                    <h2 class="text-base font-extrabold text-slate-900">Distribución de Niveles de Riesgo</h2>
                    <p class="text-xs text-slate-500">Proporción de resultados en las evaluaciones realizadas.</p>
                </div>

                <div class="space-y-4">
                    <div class="space-y-1.5">
                        <div class="flex justify-between text-xs font-bold">
                            <span class="text-emerald-700">Riesgo Bajo (Adaptativo)</span>
                            <span class="text-slate-900">{{ $riskDistribution['bajo'] }} evaluaciones</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                            <div class="bg-emerald-500 h-3 rounded-full" style="width: {{ $totalAssessments > 0 ? round(($riskDistribution['bajo'] / $totalAssessments) * 100) : 0 }}%"></div>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <div class="flex justify-between text-xs font-bold">
                            <span class="text-amber-700">Riesgo Moderado (Tensión Media)</span>
                            <span class="text-slate-900">{{ $riskDistribution['moderado'] }} evaluaciones</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                            <div class="bg-amber-500 h-3 rounded-full" style="width: {{ $totalAssessments > 0 ? round(($riskDistribution['moderado'] / $totalAssessments) * 100) : 0 }}%"></div>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <div class="flex justify-between text-xs font-bold">
                            <span class="text-rose-700">Riesgo Alto (Atención Prioritaria)</span>
                            <span class="text-slate-900">{{ $riskDistribution['alto'] }} evaluaciones</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                            <div class="bg-rose-500 h-3 rounded-full" style="width: {{ $totalAssessments > 0 ? round(($riskDistribution['alto'] / $totalAssessments) * 100) : 0 }}%"></div>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 text-xs text-slate-600 leading-relaxed">
                    💡 <strong>Nota del Sistema:</strong> Los usuarios con nivel alto reciben automáticamente notificación destacada con acceso directo a la Línea 106 y servicios de urgencia en su panel.
                </div>
            </div>

            <!-- Técnicas Más Utilizadas (7 columnas) -->
            <div class="lg:col-span-7 bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-extrabold text-slate-900">Técnicas Más Practicadas</h2>
                        <p class="text-xs text-slate-500">Ranking según cantidad de sesiones completadas.</p>
                    </div>
                    <a href="{{ route('admin.techniques.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">
                        Gestionar Técnicas &rarr;
                    </a>
                </div>

                <div class="space-y-3">
                    @forelse($topTechniques as $tech)
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-xl {{ $tech->category === 'ansiedad' ? 'bg-indigo-100 text-indigo-700' : 'bg-teal-100 text-teal-700' }} font-bold text-xs flex items-center justify-center">
                                    {{ $loop->iteration }}
                                </span>
                                <div>
                                    <h4 class="text-xs font-bold text-slate-900">{{ $tech->name }}</h4>
                                    <span class="text-[10px] text-slate-400 uppercase font-semibold">{{ $tech->category }} • {{ $tech->duration_minutes }} min</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-base font-black text-slate-800">{{ $tech->sessions_count }}</span>
                                <span class="text-[10px] text-slate-400 block">sesiones</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 text-center py-6">No hay registros de sesiones aún.</p>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- Registro de Actividad Reciente (Auditoría) -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-base font-extrabold text-slate-900">Registro de Auditoría de Acciones Administrativas</h2>
                    <p class="text-xs text-slate-500">Historial reciente de modificaciones sobre datos sensibles del sistema.</p>
                </div>
                <a href="{{ route('admin.logs.index') }}" class="text-xs font-bold text-purple-600 hover:text-purple-800">
                    Ver Registro Completo &rarr;
                </a>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($recentLogs as $log)
                    <div class="py-3 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs">
                        <div class="flex items-center gap-3">
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold uppercase {{ $log->action === 'delete' ? 'bg-rose-100 text-rose-800' : ($log->action === 'create' ? 'bg-emerald-100 text-emerald-800' : 'bg-indigo-100 text-indigo-800') }}">
                                {{ $log->action }}
                            </span>
                            <span class="font-semibold text-slate-800">{{ $log->description }}</span>
                        </div>
                        <div class="text-slate-400 text-[11px] shrink-0">
                            {{ $log->created_at->diffForHumans() }} • IP: {{ $log->ip_address }}
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 text-center py-4">No hay eventos de auditoría registrados.</p>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
