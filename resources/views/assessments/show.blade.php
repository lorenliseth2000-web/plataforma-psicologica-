@extends('layouts.app')

@section('title', 'Resultados de Evaluación')

@section('content')
<div class="py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <!-- Encabezado de Informe -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-1">
                    <a href="{{ route('assessments.index') }}" class="hover:text-indigo-600">&larr; Volver al Historial</a>
                    <span>•</span>
                    <span>Evaluación #{{ $assessment->id }}</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">
                    Informe de Bienestar y Autorregulación
                </h1>
                <p class="text-xs text-slate-400 mt-1">
                    Completado el {{ $assessment->completed_at->format('d/m/Y \a \l\a\s h:i A') }}
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('assessments.create') }}" class="px-4 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-700 font-semibold text-xs hover:bg-slate-50 transition">
                    Repetir Tamizaje
                </a>
                <a href="{{ route('dashboard') }}" class="px-5 py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-xs hover:bg-indigo-700 transition shadow-xs">
                    Ir a Mi Dashboard
                </a>
            </div>
        </div>

        <!-- Banner de Nivel de Riesgo y Puntuaciones -->
        <div class="rounded-3xl p-6 sm:p-8 border shadow-sm {{ $assessment->risk_level === 'alto' ? 'bg-rose-50/80 border-rose-300 text-rose-950' : ($assessment->risk_level === 'moderado' ? 'bg-amber-50/80 border-amber-300 text-amber-950' : 'bg-emerald-50/80 border-emerald-300 text-emerald-950') }}">
            <div class="grid md:grid-cols-12 gap-8 items-center">
                <div class="md:col-span-7 space-y-3">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider {{ $assessment->risk_level === 'alto' ? 'bg-rose-200 text-rose-900' : ($assessment->risk_level === 'moderado' ? 'bg-amber-200 text-amber-900' : 'bg-emerald-200 text-emerald-900') }}">
                        <span class="w-2 h-2 rounded-full {{ $assessment->risk_level === 'alto' ? 'bg-rose-600' : ($assessment->risk_level === 'moderado' ? 'bg-amber-600' : 'bg-emerald-600') }}"></span>
                        Nivel de Tensión Identificado: {{ ucfirst($assessment->risk_level) }}
                    </div>

                    <h2 class="text-2xl font-extrabold tracking-tight">
                        @if($assessment->risk_level === 'bajo')
                            Indicadores Leves de Tensión (Rango Adaptativo)
                        @elseif($assessment->risk_level === 'moderado')
                            Indicadores Moderados de Malestar Emocional
                        @else
                            Indicadores Significativos de Sobrecarga / Alerta
                        @endif
                    </h2>

                    <p class="text-xs sm:text-sm leading-relaxed {{ $assessment->risk_level === 'alto' ? 'text-rose-900' : ($assessment->risk_level === 'moderado' ? 'text-amber-900' : 'text-emerald-900') }}">
                        {{ $assessment->non_diagnostic_feedback }}
                    </p>
                </div>

                <!-- Tarjetas de Puntaje -->
                <div class="md:col-span-5 grid grid-cols-2 gap-3">
                    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs text-center">
                        <div class="text-xs text-slate-500 font-semibold">Subescala Ansiedad</div>
                        <div class="text-2xl font-black text-indigo-600 mt-1">{{ $assessment->score_anxiety }} <span class="text-xs text-slate-400 font-normal">/15</span></div>
                        <div class="text-[10px] text-slate-400 mt-0.5">Inquietud y tensión</div>
                    </div>

                    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs text-center">
                        <div class="text-xs text-slate-500 font-semibold">Subescala Estrés</div>
                        <div class="text-2xl font-black text-teal-600 mt-1">{{ $assessment->score_stress }} <span class="text-xs text-slate-400 font-normal">/15</span></div>
                        <div class="text-[10px] text-slate-400 mt-0.5">Sobrecarga y fatiga</div>
                    </div>

                    <div class="col-span-2 bg-slate-900 text-white p-4 rounded-2xl text-center flex items-center justify-between px-6">
                        <span class="text-xs text-slate-300 font-medium">Puntaje Total Acumulado:</span>
                        <span class="text-xl font-black text-teal-400">{{ $assessment->total_score }} <span class="text-xs text-slate-400 font-normal">/30</span></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alerta de Alto Riesgo con Canales Directos -->
        @if($assessment->isHighRisk())
            <div class="bg-gradient-to-r from-rose-600 to-rose-700 text-white p-6 sm:p-8 rounded-3xl shadow-lg space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-white/20 flex items-center justify-center font-bold text-lg">
                        !
                    </div>
                    <div>
                        <h3 class="text-lg font-bold">Recomendación de Atención Profesional Prioritaria</h3>
                        <p class="text-xs text-rose-100">Dado que tus respuestas indican un nivel significativo de malestar, te invitamos a buscar acompañamiento profesional.</p>
                    </div>
                </div>

                <div class="grid sm:grid-cols-3 gap-3 pt-2">
                    <a href="tel:106" class="p-3 bg-white text-rose-900 rounded-2xl font-bold text-xs text-center hover:bg-rose-50 transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        Línea 106 (24/7 Gratis)
                    </a>
                    <a href="tel:192" class="p-3 bg-white/10 border border-white/20 text-white rounded-2xl font-bold text-xs text-center hover:bg-white/20 transition flex items-center justify-center gap-2">
                        Línea Nacional 192 (Opción 4)
                    </a>
                    <a href="{{ route('routes.index') }}" class="p-3 bg-white/10 border border-white/20 text-white rounded-2xl font-bold text-xs text-center hover:bg-white/20 transition flex items-center justify-center gap-2">
                        Ver Entidades y Centros CAP
                    </a>
                </div>
            </div>
        @endif

        <!-- Técnicas Recomendadas para este Resultado -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Técnicas de Regulación Sugeridas</h3>
                    <p class="text-xs text-slate-500">Prácticas guiadas para aliviar los indicadores detectados en tu evaluación.</p>
                </div>
                <a href="{{ route('techniques.index') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold">Ver todas &rarr;</a>
            </div>

            <div class="grid sm:grid-cols-3 gap-6">
                @foreach($recommendedTechniques as $tech)
                    <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs hover:shadow-md transition flex flex-col justify-between">
                        <div class="space-y-3">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $tech->category === 'ansiedad' ? 'bg-indigo-100 text-indigo-800' : 'bg-teal-100 text-teal-800' }}">
                                {{ ucfirst($tech->category) }}
                            </span>
                            <h4 class="text-base font-bold text-slate-900">{{ $tech->name }}</h4>
                            <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed">{{ $tech->description }}</p>
                        </div>

                        <div class="pt-4 border-t border-slate-100 mt-4">
                            <a href="{{ route('techniques.show', $tech) }}" class="w-full py-2.5 rounded-xl bg-slate-900 hover:bg-indigo-600 text-white font-bold text-xs text-center transition flex items-center justify-center gap-1.5">
                                <span>Practicar Ahora ({{ $tech->duration_minutes }} min)</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Desglose de Respuestas del Cuestionario -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-4" x-data="{ showAnswers: false }">
            <div class="flex items-center justify-between cursor-pointer" @click="showAnswers = !showAnswers">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Desglose de Preguntas y Respuestas</h3>
                    <p class="text-xs text-slate-500">Revisa tus respuestas individuales en esta evaluación.</p>
                </div>
                <button type="button" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 px-3 py-1.5 rounded-xl">
                    <span x-text="showAnswers ? 'Ocultar Detalle ▲' : 'Ver Detalle ▼'"></span>
                </button>
            </div>

            <div x-show="showAnswers" class="space-y-3 pt-4 border-t border-slate-100" x-transition>
                @foreach($assessment->answers as $ans)
                    <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs">
                        <div class="flex items-start gap-2">
                            <span class="w-5 h-5 rounded-md {{ $ans->question->type === 'ansiedad' ? 'bg-indigo-100 text-indigo-700' : 'bg-teal-100 text-teal-700' }} font-bold text-[10px] flex items-center justify-center shrink-0">
                                {{ $ans->question->order }}
                            </span>
                            <span class="text-slate-700 font-medium">{{ $ans->question->text }}</span>
                        </div>
                        <div class="shrink-0 text-right">
                            <span class="font-bold text-slate-900">
                                @switch($ans->value)
                                    @case(0) Nunca o rara vez (0 pts) @break
                                    @case(1) Varios días (1 pt) @break
                                    @case(2) Más de la mitad de los días (2 pts) @break
                                    @case(3) Casi todos los días (3 pts) @break
                                @endswitch
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
@endsection
