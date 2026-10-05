@extends('layouts.app')

@section('title', $technique->name . ' — Práctica Guiada')

@php
    $avatarGender = auth()->user()->avatarGender();
@endphp

@push('styles')
<style>
/* ============================================================
   SUKHA AVATAR — Animación 3D / Ejercicio Guiado
   ============================================================ */

:root {
    --sage: #7D9B76;
    --sage-light: #EAF2E9;
    --sage-mid: #B5C9B3;
    --ivory: #FAF7F0;
    --dark: #2C3E35;
    --terra: #C4856A;
}

/* ---- Avatar base 3D ---- */
.avatar-scene {
    perspective: 800px;
    width: 200px;
    height: 260px;
    margin: 0 auto;
    position: relative;
}

.avatar-body {
    transform-style: preserve-3d;
    transition: transform 0.4s ease;
    width: 100%;
    height: 100%;
    position: relative;
}

/* ---- Aura de respiración ---- */
.breath-aura {
    position: absolute;
    border-radius: 50%;
    transition: all 1.5s cubic-bezier(0.4, 0, 0.2, 1);
    transform-origin: center;
}

.aura-outer {
    width: 220px; height: 220px;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    background: radial-gradient(circle, rgba(125,155,118,0.15) 0%, transparent 70%);
}

.aura-mid {
    width: 180px; height: 180px;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    background: radial-gradient(circle, rgba(125,155,118,0.2) 0%, transparent 70%);
}

/* ---- Fases de respiración ---- */
@keyframes inhale {
    0%   { transform: translate(-50%, -50%) scale(0.8); opacity: 0.3; }
    100% { transform: translate(-50%, -50%) scale(1.4); opacity: 0.7; }
}
@keyframes exhale {
    0%   { transform: translate(-50%, -50%) scale(1.4); opacity: 0.7; }
    100% { transform: translate(-50%, -50%) scale(0.8); opacity: 0.3; }
}
@keyframes hold {
    0%, 100% { transform: translate(-50%, -50%) scale(1.1); opacity: 0.5; }
}

.aura-inhale  { animation: inhale  var(--inhale-dur, 4s) ease-in-out forwards; }
.aura-hold    { animation: hold    var(--hold-dur, 4s)   ease-in-out infinite; }
.aura-exhale  { animation: exhale  var(--exhale-dur, 4s) ease-in-out forwards; }

/* ---- Avatar flotante ---- */
@keyframes avatarFloat {
    0%, 100% { transform: translateY(0px); }
    50%      { transform: translateY(-8px); }
}
@keyframes avatarFloatBreathing {
    0%, 100% { transform: translateY(0px) scaleY(1); }
    50%      { transform: translateY(-4px) scaleY(1.03); }
}

.avatar-floating { animation: avatarFloat 3s ease-in-out infinite; }
.avatar-breathing { animation: avatarFloatBreathing 4s ease-in-out infinite; }

/* ---- Círculo de progreso ---- */
.progress-ring-circle {
    transition: stroke-dashoffset 1s linear;
    transform-origin: 50% 50%;
    transform: rotate(-90deg);
}

/* ---- Fase badge ---- */
.phase-badge {
    transition: all 0.5s ease;
    backdrop-filter: blur(8px);
}

/* ---- Cards laterales ---- */
.step-card {
    transition: all 0.3s ease;
    border-left: 3px solid transparent;
}
.step-card.active {
    border-left-color: var(--sage);
    background: #F0F8EE;
}

/* ---- Muscle groups highlight ---- */
.muscle-group {
    transition: all 0.5s ease;
    opacity: 0.3;
}
.muscle-group.active-muscle {
    opacity: 1;
    filter: drop-shadow(0 0 8px rgba(125,155,118,0.6));
}

/* ---- Sensor de sentidos (grounding) ---- */
.sense-item {
    transition: all 0.4s ease;
    transform: scale(0.9);
    opacity: 0.4;
}
.sense-item.active-sense {
    transform: scale(1);
    opacity: 1;
}

/* ---- Pantalla de finalización ---- */
@keyframes successPulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}
.success-icon { animation: successPulse 1.5s ease-in-out infinite; }

/* ---- Botones ---- */
.btn-primary {
    background: linear-gradient(135deg, #7D9B76, #8FAF88);
    color: white;
    border: none;
    border-radius: 14px;
    padding: 12px 24px;
    font-weight: 700;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s ease;
}
.btn-primary:hover { opacity: 0.9; transform: translateY(-1px); }
.btn-primary:disabled { opacity: 0.4; cursor: not-allowed; transform: none; }

.btn-secondary {
    background: white;
    color: #4A6A55;
    border: 1.5px solid #D4E5D2;
    border-radius: 14px;
    padding: 12px 20px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s ease;
}
.btn-secondary:hover { background: #EAF2E9; }
</style>
@endpush

@section('content')
<div class="py-6" x-data="techniqueExercise()" x-init="init()">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- ======= BARRA SUPERIOR ======= --}}
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('techniques.index') }}"
               class="inline-flex items-center gap-2 text-xs font-bold px-4 py-2 rounded-xl border transition"
               style="background: white; border-color: #D4E5D2; color: #4A6A55;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Biblioteca
            </a>

            {{-- Badge categoría --}}
            <div class="flex items-center gap-3">
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide"
                      style="{{ $technique->category === 'ansiedad' ? 'background:#EAF2E9; color:#2C5E28;' : 'background:#FFF5F0; color:#7A3A1E;' }}">
                    {{ ucfirst($technique->category) }}
                </span>
                <span class="text-xs font-semibold flex items-center gap-1" style="color:#6B7B6E;">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ $technique->duration_minutes }} min
                </span>

                {{-- Botón sonido --}}
                <button type="button" @click="toggleSound()"
                        class="px-3 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5"
                        :style="soundEnabled
                            ? 'background:#7D9B76; color:white;'
                            : 'background:white; color:#4A6A55; border:1.5px solid #D4E5D2;'">
                    <svg class="w-3.5 h-3.5" :class="soundEnabled ? 'animate-pulse' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                    </svg>
                    <span x-text="soundEnabled ? 'Sonido: ON' : 'Sonido'"></span>
                </button>
            </div>
        </div>

        {{-- ======= PANTALLA: PREPARACIÓN ======= --}}
        <div x-show="screen === 'prep'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4">
            <div class="rounded-3xl p-8 text-center space-y-6" style="background: white; border: 1px solid #D4E5D2; box-shadow: 0 8px 40px rgba(125,155,118,0.12);">

                {{-- Título --}}
                <div>
                    <h1 class="text-3xl font-bold" style="color: #2C3E35; font-family: 'Playfair Display', Georgia, serif;">
                        {{ $technique->name }}
                    </h1>
                    <p class="text-sm mt-2 max-w-lg mx-auto leading-relaxed" style="color: #6B7B6E;">
                        {{ $technique->description }}
                    </p>
                </div>

                {{-- Avatar de preparación --}}
                <div class="avatar-scene mx-auto">
                    <div class="avatar-body avatar-floating">
                        @include('techniques.partials.avatar', ['gender' => $avatarGender, 'state' => 'prep', 'technique' => $technique->animation_type])
                    </div>
                </div>

                {{-- Info rápida --}}
                <div class="grid grid-cols-3 gap-4 max-w-sm mx-auto">
                    <div class="rounded-2xl p-3 text-center" style="background: #EAF2E9;">
                        <div class="text-lg font-black" style="color: #2C5E28;">{{ $technique->duration_minutes }}</div>
                        <div class="text-[10px] font-semibold uppercase tracking-wide" style="color: #6B7B6E;">minutos</div>
                    </div>
                    <div class="rounded-2xl p-3 text-center" style="background: #EAF2E9;">
                        <div class="text-lg font-black" style="color: #2C5E28;">{{ count($technique->instructions ?? []) }}</div>
                        <div class="text-[10px] font-semibold uppercase tracking-wide" style="color: #6B7B6E;">pasos</div>
                    </div>
                    <div class="rounded-2xl p-3 text-center" style="background: #EAF2E9;">
                        <div class="text-xs font-black capitalize" style="color: #2C5E28;">{{ $technique->animation_type }}</div>
                        <div class="text-[10px] font-semibold uppercase tracking-wide" style="color: #6B7B6E;">tipo</div>
                    </div>
                </div>

                {{-- Beneficios --}}
                <div class="rounded-2xl p-4 text-left max-w-lg mx-auto" style="background: #F5FAF4; border: 1px solid #D4E5D2;">
                    <p class="text-xs font-bold uppercase tracking-wide mb-2" style="color: #4A6A55;">¿Qué lograrás?</p>
                    <p class="text-sm leading-relaxed whitespace-pre-line" style="color: #2C3E35;">{{ $technique->benefits }}</p>
                </div>

                {{-- Instrucción previa --}}
                <p class="text-sm" style="color: #8FAF88;">Busca un lugar cómodo · Respira con normalidad · Cuando estés listo, presiona Iniciar</p>

                <button class="btn-primary px-10 py-4 text-base" @click="startExercise()">
                    Iniciar práctica guiada
                </button>
            </div>
        </div>

        {{-- ======= PANTALLA: EJERCICIO ======= --}}
        <div x-show="screen === 'exercise'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0">
            <div class="grid lg:grid-cols-12 gap-6">

                {{-- COLUMNA IZQUIERDA: Avatar + Animación --}}
                <div class="lg:col-span-7">
                    <div class="rounded-3xl p-8 text-center space-y-5" style="background: white; border: 1px solid #D4E5D2; box-shadow: 0 8px 40px rgba(125,155,118,0.10);">

                        {{-- Barra de audio: Voz Rudra + Fondo Ambiental --}}
                        <div class="rounded-2xl p-3 flex flex-wrap items-center justify-between gap-3 text-xs mb-2"
                             style="background: #F4F8F3; border: 1.5px solid #D4E5D2;">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full" :style="soundEnabled ? 'background: #7D9B76;' : 'background: #C4856A;'"></span>
                                <span class="font-bold" style="color: #2C3E35;">Voz:</span>
                                <span class="px-2.5 py-0.5 rounded-full font-semibold" style="background: #EAF2E9; color: #2C5E28;">
                                    Rudra · Serena & Íntima
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="font-bold" style="color: #2C3E35;">Fondo:</span>
                                <div class="inline-flex rounded-xl p-0.5 border" style="border-color: #D4E5D2; background: white;">
                                    <button type="button" @click="setAmbientMode('stream')"
                                            class="px-2.5 py-1 rounded-lg text-xs font-semibold transition"
                                            :style="ambientMode === 'stream' ? 'background: #7D9B76; color: white;' : 'color: #6B7B6E;'">
                                        🌿 Arroyo
                                    </button>
                                    <button type="button" @click="setAmbientMode('meditation')"
                                            class="px-2.5 py-1 rounded-lg text-xs font-semibold transition"
                                            :style="ambientMode === 'meditation' ? 'background: #7D9B76; color: white;' : 'color: #6B7B6E;'">
                                        🎵 Meditación 432Hz
                                    </button>
                                    <button type="button" @click="setAmbientMode('none')"
                                            class="px-2.5 py-1 rounded-lg text-xs font-semibold transition"
                                            :style="ambientMode === 'none' ? 'background: #7D9B76; color: white;' : 'color: #6B7B6E;'">
                                        🔇 Sin fondo
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Phase instruction -- GRANDE y claro --}}
                        <div class="phase-badge rounded-2xl px-6 py-4 mx-auto max-w-sm" style="background: #EAF2E9; border: 1.5px solid #B5C9B3;">
                            <p class="text-2xl font-black tracking-wide" style="color: #2C3E35;" x-text="currentPhaseLabel"></p>
                            <p class="text-5xl font-black mt-1" style="color: #7D9B76;" x-text="phaseCountdown > 0 ? phaseCountdown : ''"></p>
                        </div>

                        {{-- Avatar 3D animado --}}
                        <div class="relative" style="height: 280px;">
                            {{-- Auras de respiración --}}
                            <div class="breath-aura aura-outer" :class="auraClass"></div>
                            <div class="breath-aura aura-mid" :class="auraClassMid"></div>

                            {{-- Avatar SVG --}}
                            <div class="avatar-scene" :class="avatarAnimClass" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                                <div class="avatar-body">
                                    @include('techniques.partials.avatar', ['gender' => $avatarGender, 'state' => 'active', 'technique' => $technique->animation_type])
                                </div>
                            </div>

                            {{-- Grounding sense indicators --}}
                            @if($technique->animation_type === 'grounding')
                            <div class="absolute inset-0 flex items-center justify-around px-4" style="pointer-events:none;">
                                @foreach(['Ver', 'Tocar', 'Oír', 'Oler', 'Saborear'] as $i => $sense)
                                <div class="sense-item text-center" :class="currentStep === {{ $i }} ? 'active-sense' : ''">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center mx-auto mb-1" style="background: #EAF2E9; border: 2px solid #8FAF88;">
                                        @if($i === 0)
                                        <svg class="w-5 h-5" style="color:#7D9B76;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        @elseif($i === 1)
                                        <svg class="w-5 h-5" style="color:#7D9B76;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11"/></svg>
                                        @elseif($i === 2)
                                        <svg class="w-5 h-5" style="color:#7D9B76;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072M12 6v.01M8.464 8.464a5 5 0 000 7.072M3 9l1.5 6M21 9l-1.5 6"/></svg>
                                        @elseif($i === 3)
                                        <svg class="w-5 h-5" style="color:#7D9B76;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                        @else
                                        <svg class="w-5 h-5" style="color:#7D9B76;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                        @endif
                                    </div>
                                    <span class="text-[9px] font-bold" style="color: #4A6A55;">{{ $sense }}</span>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>

                        {{-- Progreso circular + Tiempo --}}
                        <div class="flex items-center justify-center gap-6">
                            <svg width="80" height="80" viewBox="0 0 80 80">
                                <circle cx="40" cy="40" r="34" fill="none" stroke="#EAF2E9" stroke-width="6"/>
                                <circle class="progress-ring-circle" cx="40" cy="40" r="34" fill="none"
                                        stroke="#7D9B76" stroke-width="6"
                                        stroke-linecap="round"
                                        :stroke-dasharray="2 * Math.PI * 34"
                                        :stroke-dashoffset="2 * Math.PI * 34 * (1 - progressRatio)"/>
                                <text x="40" y="45" text-anchor="middle" font-size="14" font-weight="800" fill="#2C3E35"
                                      x-text="formatTime(elapsedSeconds)"></text>
                            </svg>
                            <div class="text-left">
                                <p class="text-xs font-semibold uppercase tracking-wide" style="color: #8FAF88;">Tiempo transcurrido</p>
                                <p class="text-2xl font-black" style="color: #2C3E35;" x-text="formatTime(elapsedSeconds)"></p>
                                <p class="text-xs" style="color: #6B7B6E;">de {{ $technique->duration_minutes }}:00 min</p>
                            </div>
                        </div>

                        {{-- Controles --}}
                        <div class="flex items-center justify-center gap-3 flex-wrap">
                            <button class="btn-secondary" @click="pauseOrResume()"
                                    :style="isPaused ? 'background: #EAF2E9; border-color: #7D9B76; color: #2C5E28;' : ''">
                                <span x-text="isPaused ? 'Continuar' : 'Pausar'"></span>
                            </button>
                            <button class="btn-secondary" @click="resetExercise()">Reiniciar</button>
                            <button class="btn-primary"
                                    @click="finishExercise()"
                                    :disabled="elapsedSeconds < 30">
                                Finalizar práctica
                            </button>
                        </div>

                        <p class="text-xs" style="color: #B5C9B3;" x-show="elapsedSeconds < 30">
                            Puedes finalizar después de 30 segundos de práctica
                        </p>
                    </div>
                </div>

                {{-- COLUMNA DERECHA: Guía paso a paso --}}
                <div class="lg:col-span-5 space-y-4">

                    {{-- Instrucciones paso a paso --}}
                    <div class="rounded-3xl p-6 space-y-3" style="background: white; border: 1px solid #D4E5D2;">
                        <h3 class="text-sm font-bold uppercase tracking-wide" style="color: #4A6A55;">Guía de la práctica</h3>

                        @foreach($technique->instructions ?? [] as $i => $step)
                        <div class="step-card rounded-xl p-3 text-sm" :class="currentStep === {{ $i }} ? 'active' : ''" style="border-left: 3px solid transparent; transition: all 0.3s;">
                            <div class="flex items-start gap-3">
                                <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-black shrink-0 mt-0.5 transition"
                                      :style="currentStep === {{ $i }} ? 'background: #7D9B76; color: white;' : 'background: #EAF2E9; color: #4A6A55;'">
                                    {{ $i + 1 }}
                                </span>
                                <p class="leading-relaxed text-xs" style="color: #4A3E35;">{{ $step }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Beneficios --}}
                    <div class="rounded-3xl p-6" style="background: #EAF2E9; border: 1px solid #B5C9B3;">
                        <h3 class="text-xs font-bold uppercase tracking-wide mb-2" style="color: #4A6A55;">Qué está ocurriendo</h3>
                        <p class="text-xs leading-relaxed whitespace-pre-line" style="color: #2C3E35;">{{ $technique->benefits }}</p>
                    </div>

                    {{-- Consejo de momento --}}
                    <div class="rounded-3xl p-5 text-center" style="background: #FAF7F0; border: 1px solid #EDE8DC;">
                        <p class="text-xs italic leading-relaxed" style="color: #6B7B6E; font-family: 'Playfair Display', Georgia, serif;">
                            "No necesitas hacerlo perfectamente. Solo hazlo con atención."
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ======= PANTALLA: FINALIZACIÓN ======= --}}
        <div x-show="screen === 'finish'" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 scale-95">
            <div class="rounded-3xl p-8 text-center space-y-6" style="background: white; border: 1px solid #D4E5D2; box-shadow: 0 8px 40px rgba(125,155,118,0.15);">

                {{-- Ícono de éxito --}}
                <div class="success-icon w-20 h-20 rounded-full flex items-center justify-center mx-auto" style="background: linear-gradient(135deg, #7D9B76, #8FAF88);">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>

                <div>
                    <h2 class="text-3xl font-bold" style="color: #2C3E35; font-family: 'Playfair Display', Georgia, serif;">
                        ¡Muy bien hecho!
                    </h2>
                    <p class="text-sm mt-2" style="color: #6B7B6E;">
                        Completaste <strong style="color: #2C5E28;">{{ $technique->name }}</strong>
                    </p>
                </div>

                {{-- Avatar celebrando --}}
                <div class="avatar-scene mx-auto">
                    <div class="avatar-body avatar-floating">
                        @include('techniques.partials.avatar', ['gender' => $avatarGender, 'state' => 'finish', 'technique' => $technique->animation_type])
                    </div>
                </div>

                {{-- Estadísticas de sesión --}}
                <div class="grid grid-cols-2 gap-4 max-w-xs mx-auto">
                    <div class="rounded-2xl p-4 text-center" style="background: #EAF2E9;">
                        <div class="text-2xl font-black" style="color: #2C5E28;" x-text="formatTime(elapsedSeconds)"></div>
                        <div class="text-xs font-semibold" style="color: #6B7B6E;">Tiempo practicado</div>
                    </div>
                    <div class="rounded-2xl p-4 text-center" style="background: #EAF2E9;">
                        <div class="text-2xl font-black" style="color: #2C5E28;">✓</div>
                        <div class="text-xs font-semibold" style="color: #6B7B6E;">Sesión completada</div>
                    </div>
                </div>

                {{-- Valoración --}}
                <div class="space-y-4 max-w-sm mx-auto">
                    <p class="text-xs font-semibold uppercase tracking-wide" style="color: #4A6A55;">¿Cómo te sientes ahora?</p>
                    <div class="flex justify-center gap-3">
                        @foreach([1 => 'Igual', 2 => 'Un poco mejor', 3 => 'Mejor', 4 => 'Mucho mejor', 5 => 'Excelente'] as $val => $label)
                        <button @click="ratingAfter = {{ $val }}"
                                class="w-10 h-10 rounded-full text-sm font-bold transition"
                                :style="ratingAfter === {{ $val }}
                                    ? 'background: #7D9B76; color: white; transform: scale(1.1);'
                                    : 'background: #EAF2E9; color: #4A6A55;'"
                                :title="'{{ $label }}'">
                            {{ $val }}
                        </button>
                        @endforeach
                    </div>
                    <p class="text-xs" style="color: #8FAF88;" x-text="['', 'Sin cambio', 'Un poco mejor', 'Mejor', 'Mucho mejor', 'Excelente'][ratingAfter] || 'Selecciona cómo te sientes'"></p>
                </div>

                {{-- Guardar y navegar --}}
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <button class="btn-primary px-8" @click="saveSession()">
                        <span x-text="saved ? 'Guardado' : 'Guardar sesión'"></span>
                    </button>
                    <a href="{{ route('techniques.index') }}" class="btn-secondary px-8 inline-flex items-center justify-center">
                        Ver otras técnicas
                    </a>
                    <a href="{{ route('dashboard') }}" class="btn-secondary px-8 inline-flex items-center justify-center">
                        Ir al inicio
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
function techniqueExercise() {
    return {
        // ── Estado pantalla ──
        screen: 'prep',   // prep | exercise | finish

        // ── Técnica ──
        slug: '{{ $technique->slug }}',
        animationType: '{{ $technique->animation_type }}',
        totalDuration: {{ $technique->duration_minutes * 60 }},

        // ── Timer ──
        elapsedSeconds: 0,
        timerInterval: null,
        isPaused: false,

        // ── Fases de respiración ──
        phases: [],
        phaseIndex: 0,
        phaseCountdown: 0,
        phaseInterval: null,
        currentStep: 0,
        stepInterval: null,
        totalSteps: {{ count($technique->instructions ?? []) }},

        // ── Labels de fase ──
        currentPhaseLabel: 'Prepárate...',

        // ── Animación del avatar ──
        auraClass: '',
        auraClassMid: '',
        avatarAnimClass: 'avatar-floating',

        // ── Sonido y Voz (ElevenLabs Rudra + Ambiente con Ducking) ──
        soundEnabled: true,
        ambientMode: 'stream', // 'stream' (arroyo y bosque) | 'meditation' (432Hz) | 'none'
        ambientVolume: 0.12,   // Suave (12%) para que la voz predomine con claridad
        audioCtx: null,
        ambientGain: null,
        ambientNodes: [],
        currentVoiceAudio: null,

        // ── Finish ──
        ratingAfter: 0,
        saved: false,

        // ── Progress ──
        get progressRatio() {
            return Math.min(this.elapsedSeconds / this.totalDuration, 1);
        },

        init() {
            this.phases = this.buildPhases();
        },

        buildPhases() {
            const slug = this.slug;
            if (slug === 'respiracion-4-7-8') {
                return [
                    { label: 'Inhala', duration: 4, aura: 'inhale' },
                    { label: 'Sostén', duration: 7, aura: 'hold' },
                    { label: 'Exhala', duration: 8, aura: 'exhale' },
                ];
            } else if (slug === 'respiracion-en-caja') {
                return [
                    { label: 'Inhala', duration: 4, aura: 'inhale' },
                    { label: 'Sostén', duration: 4, aura: 'hold' },
                    { label: 'Exhala', duration: 4, aura: 'exhale' },
                    { label: 'Pausa', duration: 4, aura: 'hold' },
                ];
            } else if (slug === 'respiracion-diafragmatica') {
                return [
                    { label: 'Inhala con el abdomen', duration: 4, aura: 'inhale' },
                    { label: 'Sostén suavemente', duration: 2, aura: 'hold' },
                    { label: 'Exhala despacio', duration: 5, aura: 'exhale' },
                ];
            } else if (this.animationType === 'grounding') {
                return [
                    { label: 'Nombra 5 cosas que VES', duration: 20, aura: '' },
                    { label: 'Toca 4 cosas a tu alcance', duration: 20, aura: '' },
                    { label: 'Escucha 3 sonidos', duration: 20, aura: '' },
                    { label: 'Identifica 2 aromas', duration: 15, aura: '' },
                    { label: 'Nota 1 sabor en tu boca', duration: 15, aura: '' },
                ];
            } else if (this.animationType === 'muscle_relaxation') {
                return [
                    { label: 'Pies y piernas — Tensiona 5 seg', duration: 5, aura: '' },
                    { label: 'Suelta y siente la diferencia', duration: 10, aura: '' },
                    { label: 'Abdomen — Tensiona 5 seg', duration: 5, aura: '' },
                    { label: 'Suelta y respira', duration: 10, aura: '' },
                    { label: 'Brazos — Tensiona los puños 5 seg', duration: 5, aura: '' },
                    { label: 'Abre las manos — Suelta', duration: 10, aura: '' },
                    { label: 'Hombros hacia las orejas — 5 seg', duration: 5, aura: '' },
                    { label: 'Deja caer los hombros', duration: 10, aura: '' },
                    { label: 'Rostro — Arruga todo 5 seg', duration: 5, aura: '' },
                    { label: 'Relaja completamente la cara', duration: 10, aura: '' },
                ];
            } else {
                // visualization / mindfulness — respiración suave
                return [
                    { label: 'Inhala suavemente', duration: 4, aura: 'inhale' },
                    { label: 'Suelta despacio', duration: 6, aura: 'exhale' },
                ];
            }
        },

        startExercise() {
            this.screen = 'exercise';
            this.phaseIndex = 0;
            this.currentStep = 0;
            this.elapsedSeconds = 0;
            this.isPaused = false;
            this.startTimer();
            this.startPhaseCycle();
            this.startStepCycle();
            if (this.soundEnabled) {
                this.startSound();
                // Pequeña pausa antes del primer mensaje de voz
                setTimeout(() => this.speakPhase('Comencemos. Encuentra una posición cómoda y cierra los ojos si lo deseas.'), 800);
            }
        },

        pauseOrResume() {
            if (this.isPaused) {
                this.isPaused = false;
                this.startTimer();
                this.startPhaseCycle();
                this.startStepCycle();
                if (this.soundEnabled) {
                    this.startSound();
                    setTimeout(() => this.speakPhase('Continuamos.'), 400);
                }
            } else {
                this.isPaused = true;
                clearInterval(this.timerInterval);
                clearInterval(this.phaseInterval);
                clearInterval(this.stepInterval);
                this.currentPhaseLabel = 'Pausado...';
                this.auraClass = '';
                this.auraClassMid = '';
                this.avatarAnimClass = 'avatar-floating';
                this.stopSound();
                this.stopVoice();
            }
        },

        resetExercise() {
            clearInterval(this.timerInterval);
            clearInterval(this.phaseInterval);
            clearInterval(this.stepInterval);
            this.elapsedSeconds = 0;
            this.phaseIndex = 0;
            this.currentStep = 0;
            this.phaseCountdown = 0;
            this.isPaused = false;
            this.currentPhaseLabel = 'Prepárate...';
            this.auraClass = '';
            this.auraClassMid = '';
            this.avatarAnimClass = 'avatar-floating';
            this.stopSound();
            this.startExercise();
        },

        finishExercise() {
            clearInterval(this.timerInterval);
            clearInterval(this.phaseInterval);
            clearInterval(this.stepInterval);
            this.stopSound();
            this.screen = 'finish';
        },

        startTimer() {
            this.timerInterval = setInterval(() => {
                this.elapsedSeconds++;
                if (this.elapsedSeconds >= this.totalDuration) {
                    this.finishExercise();
                }
            }, 1000);
        },

        startPhaseCycle() {
            if (!this.phases.length) return;
            this.runPhase();
        },

        runPhase() {
            if (this.isPaused) return;
            const phase = this.phases[this.phaseIndex % this.phases.length];
            this.currentPhaseLabel = phase.label;
            this.phaseCountdown = phase.duration;
            this.updateAura(phase.aura);
            // Guía de voz: habla al inicio de cada fase
            this.speakPhase(phase.label);

            this.phaseInterval = setInterval(() => {
                this.phaseCountdown--;
                if (this.phaseCountdown <= 0) {
                    clearInterval(this.phaseInterval);
                    this.phaseIndex++;
                    if (!this.isPaused) this.runPhase();
                }
            }, 1000);
        },

        startStepCycle() {
            if (!this.totalSteps) return;
            // Advance step every N seconds based on total duration / steps
            const stepDuration = Math.floor((this.totalDuration / this.totalSteps) * 1000);
            this.stepInterval = setInterval(() => {
                if (this.currentStep < this.totalSteps - 1) {
                    this.currentStep++;
                }
            }, stepDuration);
        },

        updateAura(type) {
            this.auraClass = '';
            this.auraClassMid = '';
            this.avatarAnimClass = '';
            setTimeout(() => {
                if (type === 'inhale') {
                    this.auraClass = 'aura-inhale';
                    this.auraClassMid = 'aura-inhale';
                    this.avatarAnimClass = 'avatar-breathing';
                } else if (type === 'exhale') {
                    this.auraClass = 'aura-exhale';
                    this.auraClassMid = 'aura-exhale';
                    this.avatarAnimClass = 'avatar-breathing';
                } else if (type === 'hold') {
                    this.auraClass = 'aura-hold';
                    this.auraClassMid = 'aura-hold';
                    this.avatarAnimClass = 'avatar-floating';
                } else {
                    this.auraClass = '';
                    this.avatarAnimClass = 'avatar-floating';
                }
            }, 50);
        },

        // ── Sonido de Fondo y Ambiente (Arroyo de bosque & Meditación 432Hz) ──
        startAmbient() {
            if (!this.soundEnabled || this.ambientMode === 'none') {
                this.stopAmbientNodes();
                return;
            }
            try {
                if (!this.audioCtx || this.audioCtx.state === 'closed') {
                    this.audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                }
                if (this.audioCtx.state === 'suspended') {
                    this.audioCtx.resume();
                }

                this.stopAmbientNodes();

                const ctx = this.audioCtx;
                const masterGain = ctx.createGain();
                masterGain.gain.setValueAtTime(this.ambientVolume, ctx.currentTime);
                masterGain.connect(ctx.destination);
                this.ambientGain = masterGain;

                if (this.ambientMode === 'stream') {
                    // Pista A: Bosque y Arroyo Serena (agua fluyendo en la naturaleza)
                    const bufferSize = ctx.sampleRate * 2;
                    const noiseBuffer = ctx.createBuffer(1, bufferSize, ctx.sampleRate);
                    const output = noiseBuffer.getChannelData(0);
                    let b0 = 0, b1 = 0;
                    for (let i = 0; i < bufferSize; i++) {
                        const white = Math.random() * 2 - 1;
                        b0 = 0.99 * b0 + white * 0.05;
                        b1 = 0.95 * b1 + white * 0.08;
                        output[i] = (b0 + b1) * 0.5;
                    }
                    const noise = ctx.createBufferSource();
                    noise.buffer = noiseBuffer;
                    noise.loop = true;

                    const bandpass = ctx.createBiquadFilter();
                    bandpass.type = 'bandpass';
                    bandpass.frequency.setValueAtTime(460, ctx.currentTime);
                    bandpass.Q.setValueAtTime(1.2, ctx.currentTime);

                    const lowpass = ctx.createBiquadFilter();
                    lowpass.type = 'lowpass';
                    lowpass.frequency.setValueAtTime(1100, ctx.currentTime);

                    const lfo = ctx.createOscillator();
                    const lfoGain = ctx.createGain();
                    lfo.frequency.setValueAtTime(0.16, ctx.currentTime);
                    lfoGain.gain.setValueAtTime(160, ctx.currentTime);
                    lfo.connect(lfoGain);
                    lfoGain.connect(bandpass.frequency);

                    noise.connect(bandpass);
                    bandpass.connect(lowpass);
                    lowpass.connect(masterGain);

                    noise.start();
                    lfo.start();
                    this.ambientNodes = [noise, lfo, bandpass, lowpass];
                } else if (this.ambientMode === 'meditation') {
                    // Pista B: Calma Meditativa 432Hz (armónicos profundos de relajación)
                    const freqs = [216, 432, 648];
                    const gains = [0.35, 0.45, 0.18];
                    this.ambientNodes = [];

                    freqs.forEach((freq, idx) => {
                        const osc = ctx.createOscillator();
                        const oscGain = ctx.createGain();
                        osc.type = idx === 1 ? 'sine' : 'triangle';
                        osc.frequency.setValueAtTime(freq, ctx.currentTime);
                        oscGain.gain.setValueAtTime(gains[idx] * 0.4, ctx.currentTime);

                        const lfo = ctx.createOscillator();
                        const lfoG = ctx.createGain();
                        lfo.frequency.setValueAtTime(0.08, ctx.currentTime);
                        lfoG.gain.setValueAtTime(0.06, ctx.currentTime);
                        lfo.connect(lfoG);
                        lfoG.connect(oscGain.gain);

                        osc.connect(oscGain);
                        oscGain.connect(masterGain);

                        osc.start();
                        lfo.start();
                        this.ambientNodes.push(osc, lfo, oscGain, lfoG);
                    });
                }
            } catch(e) {
                console.warn('Ambient audio error:', e);
            }
        },

        stopAmbientNodes() {
            if (this.ambientNodes && this.ambientNodes.length) {
                this.ambientNodes.forEach(node => {
                    try { if (node.stop) node.stop(); } catch(e) {}
                    try { if (node.disconnect) node.disconnect(); } catch(e) {}
                });
                this.ambientNodes = [];
            }
        },

        stopAmbient() {
            this.stopAmbientNodes();
            if (this.audioCtx && this.audioCtx.state !== 'closed') {
                try { this.audioCtx.close(); } catch(e) {}
                this.audioCtx = null;
            }
            this.ambientGain = null;
        },

        setAmbientMode(mode) {
            this.ambientMode = mode;
            if (this.soundEnabled) {
                this.startAmbient();
            }
        },

        // Audio Ducking: baja el fondo al 4% cuando habla la voz y lo devuelve al 12%
        duckAmbient(shouldDuck) {
            if (!this.ambientGain || !this.audioCtx) return;
            try {
                const target = shouldDuck ? 0.04 : this.ambientVolume;
                const time = this.audioCtx.currentTime;
                this.ambientGain.gain.cancelScheduledValues(time);
                this.ambientGain.gain.linearRampToValueAtTime(target, time + (shouldDuck ? 0.25 : 0.7));
            } catch(e) {}
        },

        toggleSound() {
            if (this.soundEnabled) {
                this.stopAmbient();
                this.stopVoice();
                this.soundEnabled = false;
            } else {
                this.soundEnabled = true;
                this.startAmbient();
            }
        },

        // ── Voz guiada (ElevenLabs Rudra en español + Ducking automático) ──
        async speakPhase(text) {
            if (!this.soundEnabled) return;

            // Iniciar fondo suave si no está sonando
            if (!this.audioCtx && this.ambientMode !== 'none') {
                this.startAmbient();
            }

            this.stopVoice();
            this.duckAmbient(true);

            // 1. Intentar con ElevenLabs Rudra (API en español o caché)
            try {
                const response = await fetch('{{ route("voice.synthesize") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ text })
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.success && data.audio_url) {
                        const audio = new Audio(data.audio_url);
                        audio.volume = 1.0; // Voz al 100% (siempre predomina sobre el fondo)
                        this.currentVoiceAudio = audio;

                        audio.onended = () => {
                            this.duckAmbient(false);
                            this.currentVoiceAudio = null;
                        };
                        audio.onerror = () => {
                            this.duckAmbient(false);
                            this.currentVoiceAudio = null;
                        };

                        await audio.play();
                        return;
                    }
                }
            } catch(e) {}

            // 2. Fallback de alta fidelidad: Calibrado al perfil de Rudra
            if ('speechSynthesis' in window) {
                window.speechSynthesis.cancel();
                const msg = new SpeechSynthesisUtterance(text);
                msg.lang = 'es-ES';
                msg.rate = 0.78;  // Pausado, íntimo y sereno
                msg.pitch = 0.82; // Tono grave y cálido emulando a Rudra
                msg.volume = 1.0; // Voz al 100%

                const voices = window.speechSynthesis.getVoices();
                const preferred = voices.find(v =>
                    v.lang.startsWith('es') && (v.name.includes('Jorge') || v.name.includes('Pablo') || v.name.includes('Alvaro') || v.name.includes('Natural') || v.name.includes('Male') || v.name.includes('Google español'))
                ) || voices.find(v => v.lang.startsWith('es'));

                if (preferred) msg.voice = preferred;

                msg.onend = () => this.duckAmbient(false);
                msg.onerror = () => this.duckAmbient(false);

                window.speechSynthesis.speak(msg);
            } else {
                this.duckAmbient(false);
            }
        },

        stopVoice() {
            if (this.currentVoiceAudio) {
                try { this.currentVoiceAudio.pause(); } catch(e) {}
                this.currentVoiceAudio = null;
            }
            if ('speechSynthesis' in window) {
                window.speechSynthesis.cancel();
            }
            this.duckAmbient(false);
        },

        // ── Guardar sesión ──
        async saveSession() {
            if (this.saved) return;
            try {
                const response = await fetch('{{ route("techniques.session.store", $technique) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        duration_seconds: this.elapsedSeconds,
                        rating_after: this.ratingAfter || null,
                        satisfaction: this.ratingAfter || 5,
                    })
                });
                if (response.ok) { this.saved = true; }
            } catch(e) { console.error(e); }
        },

        formatTime(s) {
            const m = Math.floor(s / 60).toString().padStart(2, '0');
            const sec = (s % 60).toString().padStart(2, '0');
            return `${m}:${sec}`;
        },
    };
}
</script>
@endpush
@endsection
