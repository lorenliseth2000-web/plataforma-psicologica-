@extends('layouts.app')

@section('title', 'Reestructuración Cognitiva — Práctica Guiada')

@push('styles')
<style>
:root {
    --sage: #7D9B76;
    --sage-light: #EAF2E9;
    --sage-mid: #B5C9B3;
    --sage-dark: #4A6A55;
    --ivory: #FAF7F0;
    --dark: #2C3E35;
    --terra: #C4856A;
    --shadow: 0 4px 24px rgba(125,155,118,0.12);
}

.cog-wrap {
    max-width: 820px;
    margin: 0 auto;
    padding: 0 16px 60px;
}

.progress-track {
    display: flex;
    gap: 8px;
    margin-bottom: 28px;
}
.progress-step {
    flex: 1;
    height: 6px;
    border-radius: 99px;
    background: #D4E5D2;
    transition: background 0.4s ease;
}
.progress-step.done   { background: var(--sage); }
.progress-step.active { background: var(--sage-dark); }

.step-card {
    background: white;
    border: 1.5px solid #D4E5D2;
    border-radius: 28px;
    padding: 36px 32px;
    box-shadow: var(--shadow);
}

.step-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: var(--sage-light);
    color: var(--sage-dark);
    font-weight: 800;
    font-size: 15px;
    margin-bottom: 14px;
}

.step-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.45rem;
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 8px;
}

.step-guide {
    font-size: 0.9rem;
    color: #6B7B6E;
    line-height: 1.65;
    margin-bottom: 22px;
}

textarea.cog-input, input.cog-input {
    width: 100%;
    border: 1.5px solid #D4E5D2;
    border-radius: 16px;
    padding: 14px 18px;
    font-size: 0.925rem;
    color: var(--dark);
    font-family: 'Inter', sans-serif;
    background: #FAFDF9;
    transition: all 0.2s;
    outline: none;
}
textarea.cog-input:focus, input.cog-input:focus {
    border-color: var(--sage);
    box-shadow: 0 0 0 3px rgba(125,155,118,0.18);
    background: white;
}

.emotion-pills {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 22px;
}
.emotion-pill {
    padding: 8px 18px;
    border-radius: 99px;
    border: 1.5px solid #D4E5D2;
    font-size: 0.825rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    color: #4A6A55;
    background: white;
}
.emotion-pill:hover, .emotion-pill.selected {
    background: var(--sage);
    color: white;
    border-color: var(--sage);
}

.intensity-wrap {
    background: #FAFDF9;
    border: 1.5px solid #EAF2E9;
    border-radius: 20px;
    padding: 20px;
    margin-top: 18px;
}
.intensity-val {
    font-family: 'Playfair Display', serif;
    font-size: 2.2rem;
    font-weight: 800;
    color: var(--sage-dark);
    text-align: center;
    margin: 8px 0;
}
input[type=range].cog-slider {
    -webkit-appearance: none;
    width: 100%;
    height: 8px;
    border-radius: 99px;
    background: #D4E5D2;
    outline: none;
}
input[type=range].cog-slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: var(--sage);
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(125,155,118,0.4);
}

.evidence-cols {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}
@media (max-width: 640px) {
    .evidence-cols { grid-template-columns: 1fr; }
}

.traps-grid {
    display: grid;
    gap: 10px;
}
.trap-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 14px 16px;
    border-radius: 16px;
    border: 1.5px solid #D4E5D2;
    cursor: pointer;
    transition: all 0.2s;
    background: white;
}
.trap-item:hover, .trap-item.checked {
    border-color: var(--sage);
    background: var(--sage-light);
}
.trap-item input[type=checkbox] {
    accent-color: var(--sage);
    width: 18px;
    height: 18px;
    margin-top: 2px;
}

.btn-primary {
    background: linear-gradient(135deg, var(--sage), var(--sage-dark));
    color: white;
    border: none;
    border-radius: 14px;
    padding: 13px 26px;
    font-weight: 700;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.btn-primary:hover { opacity: 0.95; transform: translateY(-1px); }
.btn-primary:disabled { opacity: 0.45; cursor: not-allowed; transform: none; }

.btn-secondary {
    background: white;
    color: var(--sage-dark);
    border: 1.5px solid #D4E5D2;
    border-radius: 14px;
    padding: 13px 22px;
    font-weight: 600;
    font-size: 0.875rem;
    cursor: pointer;
    transition: background 0.2s;
}
.btn-secondary:hover { background: var(--sage-light); }

@media print {
    nav, .no-print, header, footer { display: none !important; }
    body { background: white !important; }
    .cog-wrap { max-width: 100% !important; padding: 0 !important; }
    .step-card { border: none !important; box-shadow: none !important; padding: 0 !important; }
}
</style>
@endpush

@section('content')
<div class="py-8" x-data="cognitiveExercise()" x-init="init()">
<div class="cog-wrap">

    <div class="flex items-center justify-between mb-6 no-print">
        <a href="{{ route('techniques.index') }}"
           class="inline-flex items-center gap-2 text-xs font-bold px-4 py-2.5 rounded-xl border bg-white"
           style="border-color: #D4E5D2; color: #4A6A55;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Técnicas
        </a>
        <div class="flex items-center gap-2">
            <button type="button" @click="toggleVoice()"
                    class="inline-flex items-center gap-1.5 text-xs font-bold px-3.5 py-2 rounded-xl border transition-colors"
                    :style="voiceEnabled ? 'background: #EAF2E9; border-color: #7D9B76; color: #2C5E28;' : 'background: white; border-color: #D4E5D2; color: #6B7B6E;'">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                </svg>
                <span x-text="voiceEnabled ? 'Voz guiada activa' : 'Activar voz'"></span>
            </button>
            <a href="{{ route('techniques.cognitive.history') }}"
               class="inline-flex items-center gap-1.5 text-xs font-semibold px-3.5 py-2 rounded-xl border bg-white text-gray-700"
               style="border-color: #D4E5D2;">
                Ver historial
            </a>
        </div>
    </div>

    <!-- Encabezado -->
    <div class="mb-6">
        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide mb-2"
              style="background: #EAF2E9; color: #2C5E28;">
            Terapia Cognitivo-Conductual (TCC)
        </span>
        <h1 class="text-2xl sm:text-3xl font-bold" style="font-family: 'Playfair Display', serif; color: #2C3E35;">
            {{ $technique->name }}
        </h1>
        <p class="text-sm mt-1" style="color: #6B7B6E;">
            Identifica pensamientos automáticos que te generan malestar, examina su veracidad y transfórmalos en alternativas equilibradas y útiles.
        </p>
    </div>

    {{-- Barra de audio y ambiente: Voz Rudra + Fondo Ambiental con Ducking --}}
    <div class="rounded-2xl p-3 flex flex-wrap items-center justify-between gap-3 text-xs mb-6 no-print"
         style="background: #F4F8F3; border: 1.5px solid #D4E5D2;">
        <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full" :style="voiceEnabled ? 'background: #7D9B76;' : 'background: #C4856A;'"></span>
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

    <!-- Barra de progreso -->
    <div class="progress-track no-print">
        <template x-for="i in 6" :key="i">
            <div class="progress-step" :class="{ 'done': i < currentStep, 'active': i === currentStep }"></div>
        </template>
    </div>

    <!-- PASO 1 -->
    <div x-show="currentStep === 1" x-cloak>
        <div class="step-card">
            <div class="step-badge">1</div>
            <h2 class="step-title">¿Qué está pasando y qué sientes?</h2>
            <p class="step-guide">
                Describe brevemente la situación concreta que te genera malestar y selecciona la emoción principal junto con su intensidad actual.
            </p>

            <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color: #4A6A55;">
                Situación o detonante
            </label>
            <textarea class="cog-input mb-5" rows="4"
                      placeholder="Ej: Tengo una reunión de evaluación laboral mañana por la mañana y no he podido concentrarme..."
                      x-model="form.situation" @input="autoSave()"></textarea>

            <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color: #4A6A55;">
                Emoción predominante
            </label>
            <div class="emotion-pills">
                @foreach(['Ansiedad', 'Estrés', 'Miedo', 'Enojo / Frustración', 'Tristeza', 'Culpa / Vergüenza', 'Agobio'] as $em)
                <button type="button" class="emotion-pill"
                        :class="{ selected: form.emotion === '{{ $em }}' }"
                        @click="form.emotion = '{{ $em }}'; autoSave()">{{ $em }}</button>
                @endforeach
            </div>

            <div class="intensity-wrap">
                <div class="flex items-center justify-between text-xs font-semibold" style="color: #4A6A55;">
                    <span>Intensidad inicial de tu emoción</span>
                    <span x-text="form.emotion_before + ' / 10'"></span>
                </div>
                <div class="intensity-val" x-text="form.emotion_before"></div>
                <input type="range" min="1" max="10" class="cog-slider" x-model="form.emotion_before" @input="autoSave()">
                <div class="flex justify-between text-xs mt-2" style="color: #8FAF88;">
                    <span>1 (Leve, tolerable)</span>
                    <span>5 (Moderada)</span>
                    <span>10 (Muy intensa / desbordante)</span>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-8">
                <button type="button" class="btn-primary" @click="nextStep(2)" :disabled="!form.situation.trim()">
                    <span>Continuar al Paso 2</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- PASO 2 -->
    <div x-show="currentStep === 2" x-cloak>
        <div class="step-card">
            <div class="step-badge">2</div>
            <h2 class="step-title">Atrapa tu pensamiento negativo</h2>
            <p class="step-guide">
                Escribe exactamente qué mensaje o frase se repite en tu mente sobre esta situación. ¿Qué temes que pase o qué estás asumiendo?
            </p>

            <div class="p-3.5 rounded-2xl mb-4 text-xs leading-relaxed" style="background: #FAF7F0; border: 1px solid #EAE4D7; color: #6B7B6E;">
                <strong style="color: #2C3E35;">Ejemplo:</strong> "Seguro me van a criticar, me quedaré en blanco y pensarán que no soy competente para este puesto."
            </div>

            <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color: #4A6A55;">
                Pensamiento automático (tal como suena en tu cabeza)
            </label>
            <textarea class="cog-input" rows="5"
                      placeholder="Escribe sin juzgarte, con total sinceridad..."
                      x-model="form.negative_thought" @input="autoSave()"></textarea>

            <div class="flex items-center justify-between gap-3 mt-8">
                <button type="button" class="btn-secondary" @click="goTo(1)">
                    Atrás
                </button>
                <button type="button" class="btn-primary" @click="nextStep(3)" :disabled="!form.negative_thought.trim()">
                    <span>Continuar al Paso 3</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- PASO 3 -->
    <div x-show="currentStep === 3" x-cloak>
        <div class="step-card">
            <div class="step-badge">3</div>
            <h2 class="step-title">Examinemos las evidencias</h2>
            <p class="step-guide">
                Toma distancia como un observador o juez imparcial. Ponemos el pensamiento a prueba separando hechos objetivos de suposiciones.
            </p>

            <div class="evidence-cols">
                <div class="p-4 rounded-2xl" style="background: #FAF7F0; border: 1.5px solid #EAE4D7;">
                    <label class="block text-xs font-bold uppercase tracking-wider mb-1" style="color: #C4856A;">
                        Hechos que lo respaldan
                    </label>
                    <p class="text-xs mb-3" style="color: #8FAF88;">¿Qué datos 100% reales apoyan este pensamiento?</p>
                    <textarea class="cog-input" rows="5"
                              placeholder="Ej: Aún me falta repasar el último informe..."
                              x-model="form.evidence_for" @input="autoSave()"></textarea>
                </div>

                <div class="p-4 rounded-2xl" style="background: #F4F8F3; border: 1.5px solid #D4E5D2;">
                    <label class="block text-xs font-bold uppercase tracking-wider mb-1" style="color: #4A6A55;">
                        Hechos que lo cuestionan
                    </label>
                    <p class="text-xs mb-3" style="color: #8FAF88;">¿Qué experiencias previas demuestran que puedes sobrellevarlo?</p>
                    <textarea class="cog-input" rows="5"
                              placeholder="Ej: He tenido evaluaciones antes y siempre he respondido bien..."
                              x-model="form.evidence_against" @input="autoSave()"></textarea>
                </div>
            </div>

            <div class="flex items-center justify-between gap-3 mt-8">
                <button type="button" class="btn-secondary" @click="goTo(2)">
                    Atrás
                </button>
                <button type="button" class="btn-primary" @click="nextStep(4)">
                    <span>Continuar al Paso 4</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- PASO 4 -->
    <div x-show="currentStep === 4" x-cloak>
        <div class="step-card">
            <div class="step-badge">4</div>
            <h2 class="step-title">Identifica las trampas del pensamiento</h2>
            <p class="step-guide">
                La mente humana suele caer en patrones de distorsión cognitiva. Marca los sesgos que reconoces en este pensamiento:
            </p>

            <div class="traps-grid">
                @php
                $cognitiveDistortions = [
                    ['id' => 'catastrofismo', 'name' => 'Catastrofismo', 'desc' => 'Imaginar inmediatamente el peor escenario posible como si fuera un hecho inevitable.'],
                    ['id' => 'todo_o_nada', 'name' => 'Pensamiento Todo o Nada (Blanco o Negro)', 'desc' => 'Ver las cosas en extremos opuestos: éxito rotundo o fracaso total, sin matices intermedios.'],
                    ['id' => 'lectura_de_mente', 'name' => 'Lectura de mente', 'desc' => 'Asumir con certeza lo que los demás piensan o juzgan de ti sin confirmarlo.'],
                    ['id' => 'filtro_mental', 'name' => 'Filtro negativo', 'desc' => 'Centrarte exclusivamente en un detalle adverso e ignorar todo lo positivo o neutro.'],
                    ['id' => 'sobregeneralizacion', 'name' => 'Sobregeneralización', 'desc' => 'Usar palabras absolutas como "siempre", "nunca", "todo me sale mal" por una sola experiencia.'],
                    ['id' => 'los_deberias', 'name' => 'Exigencias rígidas ("Los Deberías")', 'desc' => 'Imponerte reglas inflexibles sobre cómo deberías actuar o sentirte sin margen de error.'],
                    ['id' => 'personalizacion', 'name' => 'Personalización', 'desc' => 'Atribuirte la culpa de situaciones o reacciones ajenas que están fuera de tu control.'],
                ];
                @endphp

                @foreach($cognitiveDistortions as $t)
                <label class="trap-item" :class="{ checked: form.cognitive_traps.includes('{{ $t['id'] }}') }" @click.prevent="toggleTrap('{{ $t['id'] }}')">
                    <input type="checkbox" :checked="form.cognitive_traps.includes('{{ $t['id'] }}')" @click.stop="toggleTrap('{{ $t['id'] }}')">
                    <div class="flex-1">
                        <p class="text-xs sm:text-sm font-bold" style="color: #2C3E35;">{{ $t['name'] }}</p>
                        <p class="text-xs mt-0.5" style="color: #6B7B6E;">{{ $t['desc'] }}</p>
                    </div>
                </label>
                @endforeach
            </div>

            <div class="flex items-center justify-between gap-3 mt-8">
                <button type="button" class="btn-secondary" @click="goTo(3)">
                    Atrás
                </button>
                <button type="button" class="btn-primary" @click="nextStep(5)">
                    <span>Continuar al Paso 5</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- PASO 5 -->
    <div x-show="currentStep === 5" x-cloak>
        <div class="step-card">
            <div class="step-badge">5</div>
            <h2 class="step-title">Construye un pensamiento alternativo</h2>
            <p class="step-guide">
                No se trata de positivismo irreal, sino de un enfoque equilibrado, compasivo y objetivo que considere la evidencia que encontraste.
            </p>

            <div class="p-3.5 rounded-2xl mb-4 text-xs leading-relaxed" style="background: #F4F8F3; border: 1px solid #D4E5D2; color: #4A6A55;">
                <strong style="color: #2C5E28;">Alternativa realista:</strong> "Es natural sentir nervios ante una evaluación, pero me he preparado con dedicación y si surge una pregunta que no sé, puedo responder con honestidad y resolverla luego."
            </div>

            <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color: #4A6A55;">
                Tu pensamiento equilibrado y útil
            </label>
            <textarea class="cog-input" rows="5"
                      placeholder="Redacta tu nuevo pensamiento realista y sostenible..."
                      x-model="form.alternative_thought" @input="autoSave()"></textarea>

            <div class="flex items-center justify-between gap-3 mt-8">
                <button type="button" class="btn-secondary" @click="goTo(4)">
                    Atrás
                </button>
                <button type="button" class="btn-primary" @click="nextStep(6)" :disabled="!form.alternative_thought.trim()">
                    <span>Continuar al Paso 6</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- PASO 6 -->
    <div x-show="currentStep === 6" x-cloak>
        <div class="step-card">
            <div class="step-badge">6</div>
            <h2 class="step-title">Evalúa tu nuevo estado emocional</h2>
            <p class="step-guide">
                Vuelve a medir la intensidad de tu emoción tras haber explorado hechos y redactado una perspectiva realista.
            </p>

            <div class="intensity-wrap">
                <div class="flex items-center justify-between text-xs font-semibold" style="color: #4A6A55;">
                    <span>Intensidad actual tras el ejercicio</span>
                    <span x-text="form.emotion_after + ' / 10'"></span>
                </div>
                <div class="intensity-val" x-text="form.emotion_after"></div>
                <input type="range" min="1" max="10" class="cog-slider" x-model="form.emotion_after" @input="autoSave()">
                <div class="flex justify-between text-xs mt-2" style="color: #8FAF88;">
                    <span>1 (Muy leve o en calma)</span>
                    <span>5 (Moderada)</span>
                    <span>10 (Muy intensa)</span>
                </div>
            </div>

            <div class="mt-6 p-4 rounded-2xl text-center text-xs sm:text-sm font-medium"
                 :style="parseInt(form.emotion_after) < parseInt(form.emotion_before) ? 'background: #EAF2E9; color: #2C5E28; border: 1px solid #B5C9B3;' : 'background: #FAF7F0; color: #6B7B6E; border: 1px solid #EAE4D7;'">
                Pasaste de una intensidad de <strong x-text="form.emotion_before"></strong> a <strong x-text="form.emotion_after"></strong>.
                <span x-show="parseInt(form.emotion_after) < parseInt(form.emotion_before)">
                    — Lograste reducir el impacto de tu pensamiento automático. Has dado un gran paso en autorregulación.
                </span>
                <span x-show="parseInt(form.emotion_after) >= parseInt(form.emotion_before)">
                    — Dar luz a tus pensamientos requiere práctica y tiempo. El proceso de escribirlos y observarlos ya es valioso.
                </span>
            </div>

            <div class="flex items-center justify-between gap-3 mt-8">
                <button type="button" class="btn-secondary" @click="goTo(5)">
                    Atrás
                </button>
                <button type="button" class="btn-primary" @click="finishExercise()">
                    <span>Generar informe del ejercicio</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- INFORME FINAL (PASO 7) -->
    <div x-show="currentStep === 7" x-cloak>
        <div class="step-card" id="printableReport">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 mb-6" style="border-bottom: 1.5px solid #EAF2E9;">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider" style="color: #7D9B76;">Informe de Práctica Guiada</span>
                    <h2 class="text-xl sm:text-2xl font-bold mt-1" style="font-family: 'Playfair Display', serif; color: #2C3E35;">
                        Reestructuración Cognitiva Completada
                    </h2>
                    <p class="text-xs mt-1 text-gray-500" x-text="'Fecha: ' + new Date().toLocaleDateString('es-ES', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })"></p>
                </div>
                <div class="shrink-0 flex items-center gap-2">
                    <span class="px-4 py-2 rounded-2xl text-xs font-bold inline-flex items-center gap-2"
                          :style="parseInt(form.emotion_after) < parseInt(form.emotion_before) ? 'background: #EAF2E9; color: #2C5E28;' : 'background: #FAF7F0; color: #7A3A1E;'">
                        <span x-text="'Impacto: ' + (parseInt(form.emotion_before) - parseInt(form.emotion_after) > 0 ? '-' + Math.round(((parseInt(form.emotion_before) - parseInt(form.emotion_after))/parseInt(form.emotion_before))*100) + '% malestar' : 'Explorado')"></span>
                    </span>
                </div>
            </div>

            <div class="space-y-4 text-xs sm:text-sm">
                <div class="p-4 rounded-2xl" style="background: #FAFDF9; border: 1px solid #EAF2E9;">
                    <p class="text-xs font-bold uppercase tracking-wider mb-1" style="color: #4A6A55;">1. Situación y Emoción inicial</p>
                    <p class="text-gray-800" x-text="form.situation"></p>
                    <p class="mt-2 text-xs" style="color: #6B7B6E;">
                        Emoción: <strong style="color: #2C3E35;" x-text="form.emotion || 'No especificada'"></strong> &bull; Intensidad inicial: <strong style="color: #2C3E35;" x-text="form.emotion_before + '/10'"></strong>
                    </p>
                </div>

                <div class="p-4 rounded-2xl" style="background: #FAF7F0; border: 1px solid #EAE4D7;">
                    <p class="text-xs font-bold uppercase tracking-wider mb-1" style="color: #C4856A;">2. Pensamiento automático analizado</p>
                    <p class="text-gray-800" x-text="form.negative_thought"></p>
                </div>

                <template x-if="form.evidence_for || form.evidence_against">
                    <div class="grid sm:grid-cols-2 gap-3">
                        <div class="p-3.5 rounded-2xl" style="background: #FAFDF9; border: 1px solid #EAF2E9;">
                            <p class="text-xs font-bold uppercase tracking-wider mb-1" style="color: #C4856A;">Hechos que lo respaldaban</p>
                            <p class="text-xs text-gray-700" x-text="form.evidence_for || 'Ninguno anotado'"></p>
                        </div>
                        <div class="p-3.5 rounded-2xl" style="background: #F4F8F3; border: 1px solid #D4E5D2;">
                            <p class="text-xs font-bold uppercase tracking-wider mb-1" style="color: #4A6A55;">Hechos que lo cuestionaban</p>
                            <p class="text-xs text-gray-700" x-text="form.evidence_against || 'Ninguno anotado'"></p>
                        </div>
                    </div>
                </template>

                <template x-if="form.cognitive_traps.length > 0">
                    <div class="p-4 rounded-2xl" style="background: #FAFDF9; border: 1px solid #EAF2E9;">
                        <p class="text-xs font-bold uppercase tracking-wider mb-2" style="color: #4A6A55;">Trampas de la mente identificadas</p>
                        <div class="flex flex-wrap gap-1.5">
                            <template x-for="trap in form.cognitive_traps" :key="trap">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium" style="background: #EAF2E9; color: #4A6A55; border: 1px solid #D4E5D2;" x-text="trap.replace('_', ' ')"></span>
                            </template>
                        </div>
                    </div>
                </template>

                <div class="p-4 rounded-2xl" style="background: #F4F8F3; border: 1.5px solid #7D9B76;">
                    <p class="text-xs font-bold uppercase tracking-wider mb-1" style="color: #2C5E28;">Nuevo pensamiento alternativo y realista</p>
                    <p class="text-gray-900 font-medium italic" x-text="'«' + form.alternative_thought + '»'"></p>
                    <p class="mt-2 text-xs" style="color: #4A6A55;">
                        Intensidad emocional final: <strong style="color: #2C3E35;" x-text="form.emotion_after + '/10'"></strong>
                    </p>
                </div>
            </div>

            <!-- Acciones de guardar e imprimir -->
            <div class="mt-8 pt-6 no-print space-y-3" style="border-top: 1.5px solid #EAF2E9;">
                <button type="button" class="w-full btn-primary justify-center py-3.5" @click="saveSession()" :disabled="saved || saving">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                    </svg>
                    <span x-text="saved ? '✓ Guardado correctamente en tu historial' : (saving ? 'Guardando en tu cuenta...' : 'Guardar este ejercicio en mi cuenta')"></span>
                </button>

                <button type="button" class="w-full btn-secondary justify-center py-3" @click="window.print()">
                    <svg class="w-4 h-4 inline-block mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Imprimir / Guardar en PDF
                </button>

                <p x-show="saveError" class="text-xs text-center text-red-600 mt-2" x-text="saveError"></p>

                <div class="text-center pt-2">
                    <a href="{{ route('techniques.cognitive.history') }}" class="text-xs font-bold hover:underline" style="color: #7D9B76;">
                        Ir a mi historial de ejercicios &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
</div>
@endsection

@push('scripts')
<script>
function cognitiveExercise() {
    return {
        currentStep: 1,
        saved: false,
        saving: false,
        saveError: '',
        startTime: Date.now(),
        voiceEnabled: true,
        ambientMode: 'stream', // 'stream' (arroyo) | 'meditation' (432Hz) | 'none'
        ambientVolume: 0.12,   // Suave (12%) para que la voz de Rudra resalte
        audioCtx: null,
        ambientGain: null,
        ambientNodes: [],
        currentVoiceAudio: null,
        speechSynthesis: null,

        form: {
            situation: '',
            emotion: 'Ansiedad',
            emotion_before: 7,
            negative_thought: '',
            evidence_for: '',
            evidence_against: '',
            cognitive_traps: [],
            alternative_thought: '',
            emotion_after: 4,
        },

        stepGuides: {
            1: "Paso uno. Tómate un momento para respirar con tranquilidad. Describe brevemente qué situación te genera malestar y qué emoción sientes.",
            2: "Paso dos. Atrapa ese pensamiento automático negativo. ¿Qué es exactamente lo que te estás diciendo a ti mismo en este momento?",
            3: "Paso tres. Miremos la situación como observadores imparciales. ¿Qué hechos objetivos apoyan tu pensamiento y qué experiencias pasadas demuestran lo contrario?",
            4: "Paso cuatro. Identifica las trampas o sesgos de la mente. Selecciona aquellos patrones de distorsión que resuenen con lo que escribiste.",
            5: "Paso cinco. Construyamos juntos un pensamiento alternativo realista. No tiene que ser forzado ni perfecto, solo equilibrado, útil y compasivo.",
            6: "Paso seis. Respira profundamente y vuelve a conectar contigo. Evalúa nuevamente la intensidad de tu emoción tras haber explorado este pensamiento.",
            7: "Has completado tu ejercicio de reestructuración cognitiva. Excelente trabajo cuidando de tu bienestar mental."
        },

        init() {
            if ('speechSynthesis' in window) {
                this.speechSynthesis = window.speechSynthesis;
            }

            const draft = localStorage.getItem('sukha_cognitive_draft');
            if (draft) {
                try {
                    this.form = Object.assign(this.form, JSON.parse(draft));
                } catch (e) {}
            }

            // Iniciar fondo relajante e instrucción inicial
            setTimeout(() => {
                this.startAmbient();
                this.speak(this.stepGuides[1]);
            }, 600);
        },

        startAmbient() {
            if (!this.voiceEnabled || this.ambientMode === 'none') {
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
                    // Pista A: Bosque y Arroyo Serena
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
                    // Pista B: Calma Meditativa 432Hz
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
            } catch(e) {}
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
            if (this.voiceEnabled) {
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

        toggleVoice() {
            this.voiceEnabled = !this.voiceEnabled;
            if (!this.voiceEnabled) {
                this.stopVoice();
                this.stopAmbient();
            } else {
                this.startAmbient();
                this.speak(this.stepGuides[this.currentStep]);
            }
        },

        async speak(text) {
            if (!this.voiceEnabled) return;

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
                        audio.volume = 1.0;
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

            // 2. Fallback de alta fidelidad: Calibrado al perfil acústico de Rudra
            if ('speechSynthesis' in window) {
                window.speechSynthesis.cancel();
                const utterance = new SpeechSynthesisUtterance(text);
                utterance.lang = 'es-ES';
                utterance.rate = 0.78;  // Pausado e íntimo
                utterance.pitch = 0.82; // Tono grave y sereno (Rudra)
                utterance.volume = 1.0;

                const voices = window.speechSynthesis.getVoices();
                const preferred = voices.find(v =>
                    v.lang.startsWith('es') && (v.name.includes('Jorge') || v.name.includes('Pablo') || v.name.includes('Alvaro') || v.name.includes('Natural') || v.name.includes('Male') || v.name.includes('Google español'))
                ) || voices.find(v => v.lang.startsWith('es'));

                if (preferred) utterance.voice = preferred;

                utterance.onend = () => this.duckAmbient(false);
                utterance.onerror = () => this.duckAmbient(false);

                window.speechSynthesis.speak(utterance);
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

        goTo(step) {
            this.currentStep = step;
            window.scrollTo({ top: 0, behavior: 'smooth' });
            this.speak(this.stepGuides[step]);
        },

        nextStep(step) {
            this.goTo(step);
        },

        toggleTrap(id) {
            const idx = this.form.cognitive_traps.indexOf(id);
            if (idx > -1) {
                this.form.cognitive_traps.splice(idx, 1);
            } else {
                this.form.cognitive_traps.push(id);
            }
            this.autoSave();
        },

        autoSave() {
            localStorage.setItem('sukha_cognitive_draft', JSON.stringify(this.form));
        },

        finishExercise() {
            this.goTo(7);
        },

        async saveSession() {
            if (this.saved || this.saving) return;
            this.saving = true;
            this.saveError = '';

            const durationSeconds = Math.max(1, Math.floor((Date.now() - this.startTime) / 1000));

            try {
                const response = await fetch('{{ route("techniques.cognitive.store", $technique) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        ...this.form,
                        duration_seconds: durationSeconds
                    })
                });

                const res = await response.json();
                if (res.success) {
                    this.saved = true;
                    localStorage.removeItem('sukha_cognitive_draft');
                } else {
                    this.saveError = res.message || 'No se pudo guardar el ejercicio. Intenta nuevamente.';
                }
            } catch (err) {
                this.saveError = 'Error al conectar con el servidor para guardar.';
            } finally {
                this.saving = false;
            }
        }
    };
}
</script>
@endpush
