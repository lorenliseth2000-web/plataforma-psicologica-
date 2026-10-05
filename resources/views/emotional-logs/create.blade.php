@extends('layouts.app')

@section('title', 'Check-in Diario de Bienestar')

@section('content')
<div class="py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6"
         x-data="{
            anxiety: {{ $todayLog ? $todayLog->anxiety_level : 4 }},
            stress: {{ $todayLog ? $todayLog->stress_level : 5 }},
            mood: {{ $todayLog ? $todayLog->mood_level : 3 }},
            sleep: {{ $todayLog ? $todayLog->sleep_hours : 7.0 }}
         }">

        <!-- Encabezado -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-2">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-teal-600 bg-teal-50 px-3 py-1 rounded-full">
                    Registro Diario (1 minuto)
                </span>
                <span class="text-xs font-semibold text-slate-400">
                    {{ now()->translatedFormat('l, d \d\e F') }}
                </span>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900">
                {{ $todayLog ? 'Actualizar Mi Registro de Hoy' : '¿Cómo te sientes hoy?' }}
            </h1>
            <p class="text-xs text-slate-500">
                Monitorea tus estados para que la inteligencia artificial personalice tus recomendaciones.
            </p>
        </div>

        <form method="POST" action="{{ route('emotional-logs.store') }}" class="space-y-6">
            @csrf

            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-6">
                
                <!-- Fecha del registro -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Fecha del Registro</label>
                    <input type="date"
                           name="log_date"
                           value="{{ $todayLog ? $todayLog->log_date->toDateString() : $today }}"
                           class="w-full p-3 rounded-2xl border border-slate-200 text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500"
                           required>
                </div>

                <!-- Slider Ansiedad (1-10) -->
                <div class="space-y-2 pt-2 border-t border-slate-100">
                    <div class="flex items-center justify-between">
                        <label class="text-sm font-bold text-slate-800 flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-indigo-500"></span>
                            Nivel de Inquietud / Ansiedad
                        </label>
                        <span class="px-3 py-1 rounded-xl bg-indigo-100 text-indigo-900 font-black text-sm" x-text="anxiety + ' / 10'"></span>
                    </div>
                    <input type="range" name="anxiety_level" min="1" max="10" x-model="anxiety"
                           class="w-full h-2.5 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-indigo-600">
                    <div class="flex justify-between text-[11px] text-slate-400 font-medium">
                        <span>1 (Tranquilidad total)</span>
                        <span>5 (Inquietud moderada)</span>
                        <span>10 (Alerta máxima)</span>
                    </div>
                </div>

                <!-- Slider Estrés (1-10) -->
                <div class="space-y-2 pt-4 border-t border-slate-100">
                    <div class="flex items-center justify-between">
                        <label class="text-sm font-bold text-slate-800 flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-teal-500"></span>
                            Nivel de Sobrecarga / Estrés
                        </label>
                        <span class="px-3 py-1 rounded-xl bg-teal-100 text-teal-900 font-black text-sm" x-text="stress + ' / 10'"></span>
                    </div>
                    <input type="range" name="stress_level" min="1" max="10" x-model="stress"
                           class="w-full h-2.5 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-teal-600">
                    <div class="flex justify-between text-[11px] text-slate-400 font-medium">
                        <span>1 (Relajado / En control)</span>
                        <span>5 (Carga moderada)</span>
                        <span>10 (Agotamiento extremo)</span>
                    </div>
                </div>

                <!-- Estado de Ánimo (1-5) -->
                <div class="space-y-3 pt-4 border-t border-slate-100">
                    <label class="text-sm font-bold text-slate-800 block">
                        Estado de Ánimo General
                    </label>
                    <div class="grid grid-cols-5 gap-2 text-center select-none">
                        @foreach([
                            1 => ['emoji' => '😞', 'label' => 'Muy bajo'],
                            2 => ['emoji' => '🙁', 'label' => 'Bajo'],
                            3 => ['emoji' => '😐', 'label' => 'Neutral'],
                            4 => ['emoji' => '🙂', 'label' => 'Bueno'],
                            5 => ['emoji' => '😄', 'label' => 'Excelente']
                        ] as $val => $data)
                            <label class="p-3 rounded-2xl border cursor-pointer transition flex flex-col items-center justify-center gap-1"
                                   :class="mood == {{ $val }} ? 'bg-indigo-50 border-indigo-600 ring-2 ring-indigo-500/20 font-bold' : 'bg-slate-50/70 border-slate-200 hover:bg-slate-100'">
                                <input type="radio" name="mood_level" value="{{ $val }}" x-model="mood" class="sr-only" required>
                                <span class="text-2xl">{{ $data['emoji'] }}</span>
                                <span class="text-[10px] text-slate-600">{{ $data['label'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Horas de Sueño -->
                <div class="space-y-2 pt-4 border-t border-slate-100">
                    <div class="flex items-center justify-between">
                        <label class="text-sm font-bold text-slate-800">
                            Horas de Sueño y Descanso Anoche
                        </label>
                        <span class="font-black text-purple-700 text-sm" x-text="sleep + ' horas'"></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="number" step="0.5" min="0" max="24" name="sleep_hours" x-model="sleep"
                               class="w-32 p-3 rounded-2xl border border-slate-200 text-sm font-bold text-slate-800 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500"
                               required>
                        <div class="flex items-center gap-1.5">
                            <button type="button" @click="sleep = 5.0" class="px-2.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-xs font-semibold text-slate-700">5h</button>
                            <button type="button" @click="sleep = 6.0" class="px-2.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-xs font-semibold text-slate-700">6h</button>
                            <button type="button" @click="sleep = 7.0" class="px-2.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-xs font-semibold text-slate-700">7h</button>
                            <button type="button" @click="sleep = 8.0" class="px-2.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-xs font-semibold text-slate-700">8h</button>
                        </div>
                    </div>
                </div>

                <!-- Notas opcionales -->
                <div class="space-y-1 pt-4 border-t border-slate-100">
                    <label class="text-xs font-bold text-slate-700 block uppercase tracking-wider">
                        Factores o Notas Relevantes (Opcional)
                    </label>
                    <textarea name="notes" rows="3" placeholder="Ej: Tuve entrega de proyecto final en la universidad, sentí tensión en hombros..."
                              class="w-full p-3 rounded-2xl border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">{{ $todayLog ? $todayLog->notes : '' }}</textarea>
                </div>

                <!-- Botón de Envío -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                    <a href="{{ route('emotional-logs.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-700">
                        Cancelar
                    </a>

                    <button type="submit" class="px-8 py-3.5 rounded-2xl bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold text-sm hover:opacity-95 transition shadow-lg shadow-purple-100 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Guardar Registro Diario</span>
                    </button>
                </div>

            </div>
        </form>

    </div>
</div>
@endsection
