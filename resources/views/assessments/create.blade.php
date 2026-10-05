@extends('layouts.app')

@section('title', 'Tamizaje de Ansiedad y Estrés')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6"
         x-data="{
            step: 1,
            answers: {},
            totalQuestions: {{ $allQuestions->count() }},
            answeredCount() {
                return Object.keys(this.answers).length;
            },
            progressPercentage() {
                return Math.round((this.answeredCount() / this.totalQuestions) * 100);
            },
            isAnxietyComplete() {
                const anxietyIds = [{{ $anxietyQuestions->pluck('id')->implode(',') }}];
                return anxietyIds.every(id => this.answers[id] !== undefined);
            },
            isStressComplete() {
                const stressIds = [{{ $stressQuestions->pluck('id')->implode(',') }}];
                return stressIds.every(id => this.answers[id] !== undefined);
            },
            canSubmit() {
                return this.answeredCount() === this.totalQuestions;
            }
         }">

        <!-- Encabezado y Barra de Progreso -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">
                        Evaluación Clínica Inicial
                    </span>
                    <h1 class="text-2xl font-extrabold text-slate-900 mt-2">
                        Tamizaje de Síntomas de Ansiedad y Estrés
                    </h1>
                </div>
                <div class="text-right">
                    <span class="text-2xl font-black text-indigo-600" x-text="answeredCount() + '/' + totalQuestions"></span>
                    <span class="text-xs text-slate-400 block">respondidas</span>
                </div>
            </div>

            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                Por favor, responde considerando <strong>cómo te has sentido durante las últimas dos semanas</strong>. Tus respuestas son totalmente confidenciales y servirán para personalizar tus ejercicios de bienestar.
            </p>

            <!-- Barra de Progreso -->
            <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-600 to-teal-500 h-3 rounded-full transition-all duration-300"
                     :style="'width: ' + progressPercentage() + '%'"></div>
            </div>

            <!-- Pasos -->
            <div class="grid grid-cols-2 gap-2 pt-2 text-xs font-bold text-center">
                <button type="button" @click="step = 1" :class="step === 1 ? 'text-indigo-600 border-b-2 border-indigo-600 pb-2' : 'text-slate-400 pb-2'">
                    1. Bloque Ansiedad (5 Preguntas)
                </button>
                <button type="button" @click="step = 2" :class="step === 2 ? 'text-indigo-600 border-b-2 border-indigo-600 pb-2' : 'text-slate-400 pb-2'">
                    2. Bloque Estrés (5 Preguntas)
                </button>
            </div>
        </div>

        <form method="POST" action="{{ route('assessments.store') }}">
            @csrf

            <!-- ================= PASO 1: ANSIEDAD ================= -->
            <div x-show="step === 1" class="space-y-6" x-transition>
                <div class="bg-indigo-50/70 border border-indigo-100 rounded-2xl p-4 flex items-center justify-between">
                    <div class="flex items-center gap-2 text-indigo-900 font-bold text-sm">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        <span>Módulo 1: Manifestaciones de Inquietud y Ansiedad</span>
                    </div>
                    <span class="text-xs text-indigo-700 font-semibold">5 Preguntas</span>
                </div>

                @foreach($anxietyQuestions as $index => $q)
                    <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
                        <div class="flex items-start gap-3">
                            <span class="w-7 h-7 rounded-xl bg-indigo-100 text-indigo-700 font-extrabold text-xs flex items-center justify-center shrink-0">
                                {{ $index + 1 }}
                            </span>
                            <h3 class="text-base font-bold text-slate-800 leading-snug">
                                {{ $q->text }}
                            </h3>
                        </div>

                        <!-- Opciones Escala Likert -->
                        <div class="grid sm:grid-cols-4 gap-2.5 pt-2">
                            @foreach([
                                0 => 'Nunca o rara vez',
                                1 => 'Varios días',
                                2 => 'Más de la mitad de los días',
                                3 => 'Casi todos los días'
                            ] as $val => $label)
                                <label class="relative flex flex-col p-3 rounded-2xl border cursor-pointer text-center transition select-none"
                                       :class="answers[{{ $q->id }}] == {{ $val }} ? 'bg-indigo-50 border-indigo-600 text-indigo-900 font-bold ring-2 ring-indigo-500/20' : 'bg-slate-50/50 border-slate-200 text-slate-600 hover:bg-slate-100/70'">
                                    <input type="radio"
                                           name="answers[{{ $q->id }}]"
                                           value="{{ $val }}"
                                           x-model="answers[{{ $q->id }}]"
                                           class="sr-only"
                                           required>
                                    <span class="text-xs font-semibold" :class="answers[{{ $q->id }}] == {{ $val }} ? 'text-indigo-900' : 'text-slate-700'">
                                        {{ $label }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 mt-1 font-normal">
                                        ({{ $val }} {{ $val === 1 ? 'punto' : 'puntos' }})
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <div class="flex justify-end pt-4">
                    <button type="button"
                            @click="step = 2; window.scrollTo({top: 0, behavior: 'smooth'})"
                            class="px-8 py-3.5 rounded-2xl bg-indigo-600 text-white font-bold text-sm hover:bg-indigo-700 transition shadow-md shadow-indigo-100 flex items-center gap-2">
                        <span>Continuar al Bloque de Estrés</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>
            </div>

            <!-- ================= PASO 2: ESTRÉS ================= -->
            <div x-show="step === 2" class="space-y-6" x-transition>
                <div class="bg-teal-50/70 border border-teal-100 rounded-2xl p-4 flex items-center justify-between">
                    <div class="flex items-center gap-2 text-teal-900 font-bold text-sm">
                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Módulo 2: Sobrecarga, Fatiga y Estrés</span>
                    </div>
                    <span class="text-xs text-teal-700 font-semibold">5 Preguntas</span>
                </div>

                @foreach($stressQuestions as $index => $q)
                    <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
                        <div class="flex items-start gap-3">
                            <span class="w-7 h-7 rounded-xl bg-teal-100 text-teal-700 font-extrabold text-xs flex items-center justify-center shrink-0">
                                {{ $index + 6 }}
                            </span>
                            <h3 class="text-base font-bold text-slate-800 leading-snug">
                                {{ $q->text }}
                            </h3>
                        </div>

                        <!-- Opciones Escala Likert -->
                        <div class="grid sm:grid-cols-4 gap-2.5 pt-2">
                            @foreach([
                                0 => 'Nunca o rara vez',
                                1 => 'Varios días',
                                2 => 'Más de la mitad de los días',
                                3 => 'Casi todos los días'
                            ] as $val => $label)
                                <label class="relative flex flex-col p-3 rounded-2xl border cursor-pointer text-center transition select-none"
                                       :class="answers[{{ $q->id }}] == {{ $val }} ? 'bg-teal-50 border-teal-600 text-teal-900 font-bold ring-2 ring-teal-500/20' : 'bg-slate-50/50 border-slate-200 text-slate-600 hover:bg-slate-100/70'">
                                    <input type="radio"
                                           name="answers[{{ $q->id }}]"
                                           value="{{ $val }}"
                                           x-model="answers[{{ $q->id }}]"
                                           class="sr-only"
                                           required>
                                    <span class="text-xs font-semibold" :class="answers[{{ $q->id }}] == {{ $val }} ? 'text-teal-900' : 'text-slate-700'">
                                        {{ $label }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 mt-1 font-normal">
                                        ({{ $val }} {{ $val === 1 ? 'punto' : 'puntos' }})
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <!-- Botones de Navegación Final -->
                <div class="flex items-center justify-between pt-6 border-t border-slate-200">
                    <button type="button"
                            @click="step = 1; window.scrollTo({top: 0, behavior: 'smooth'})"
                            class="px-6 py-3 rounded-2xl bg-white border border-slate-300 text-slate-700 font-semibold text-sm hover:bg-slate-50 transition">
                        &larr; Volver al Bloque 1
                    </button>

                    <button type="submit"
                            :disabled="!canSubmit()"
                            class="px-8 py-3.5 rounded-2xl bg-gradient-to-r from-indigo-600 to-teal-600 text-white font-bold text-sm hover:opacity-95 transition shadow-lg shadow-indigo-100 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Finalizar Evaluación y Ver Resultados</span>
                    </button>
                </div>
            </div>

        </form>

    </div>
</div>
@endsection
