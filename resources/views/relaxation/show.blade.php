@extends('layouts.app')

@section('title', $session['name'])

@push('styles')
<style>
    .relax-shell nav, .relax-shell aside { display: none !important; }
    .relax-shell footer { display: none !important; }
    .relax-shell body, .relax-page { background: #1a2420 !important; }
</style>
@endpush

@section('content')
<div class="relax-page min-h-[90vh] flex items-center justify-center px-4 py-8"
     x-data="relaxationPlayer(@js($session))"
     x-init="init()">
    <div class="w-full max-w-xl text-center space-y-8">
        <template x-if="phase === 'ready'">
            <div class="space-y-6">
                <p class="text-[11px] uppercase tracking-widest" style="color: #8FAF88;">Sesión guiada</p>
                <h1 class="font-playfair text-3xl sm:text-4xl text-white" x-text="session.name"></h1>
                <p class="text-sm leading-relaxed" style="color: #B5C9B3;" x-text="session.description"></p>
                <p class="text-xs" style="color: #8FAF88;">Usa audífonos. Pulsa reproducir cuando estés lista o listo.</p>
                <button type="button" @click="start()" class="px-8 py-3.5 rounded-2xl text-sm font-bold text-white" style="background: linear-gradient(135deg, #7D9B76, #8FAF88);">
                    Reproducir
                </button>
                <div>
                    <a href="{{ route('relaxation.index') }}" class="text-xs underline" style="color: #8FAF88;">Salir de la sesión</a>
                </div>
            </div>
        </template>

        <template x-if="phase === 'playing' || phase === 'paused'">
            <div class="space-y-8">
                <p class="text-lg sm:text-2xl font-playfair leading-relaxed min-h-[4.5rem] text-white" x-text="currentCue"></p>

                <div class="rounded-3xl p-6 space-y-4" style="background: rgba(250,247,240,0.06); border: 1px solid rgba(181,201,179,0.25);">
                    <div class="flex justify-between text-xs" style="color: #B5C9B3;">
                        <span x-text="formatTime(elapsed)"></span>
                        <span x-text="'-' + formatTime(remaining)"></span>
                    </div>
                    <input type="range" min="0" :max="duration" step="0.1" :value="elapsed" @input="seek($event.target.value)"
                           class="w-full accent-[#8FAF88]">
                    <div class="flex items-center justify-center gap-4">
                        <button type="button" @click="toggle()" class="w-14 h-14 rounded-full text-white font-bold" style="background: #7D9B76;" x-text="phase === 'paused' ? '▶' : '❚❚'"></button>
                    </div>
                    <div class="flex items-center gap-3 text-xs" style="color: #B5C9B3;">
                        <span>Volumen</span>
                        <input type="range" min="0" max="1" step="0.05" x-model.number="volume" @input="applyVolume()" class="flex-1 accent-[#8FAF88]">
                    </div>
                    <button type="button" @click="exit()" class="text-xs underline" style="color: #C4856A;">Salir de la sesión</button>
                </div>
                <audio x-ref="audio" preload="none" class="hidden" :src="session.audio_url || ''"></audio>
            </div>
        </template>

        <template x-if="phase === 'done'">
            <div class="space-y-5">
                <h2 class="font-playfair text-3xl text-white">Has terminado tu sesión.</h2>
                <p class="text-sm" style="color: #B5C9B3;">Date unos segundos antes de continuar.</p>
                <a href="{{ route('relaxation.index') }}" class="inline-block px-6 py-3 rounded-2xl text-sm font-bold text-white" style="background: #7D9B76;">
                    Volver a relajación
                </a>
            </div>
        </template>
    </div>
</div>

@push('scripts')
<script>
function relaxationPlayer(session) {
    return {
        session,
        phase: 'ready',
        elapsed: 0,
        duration: session.duration_seconds || 0,
        volume: 0.85,
        currentCue: session.cues?.[0]?.text || '',
        timer: null,
        startedAt: 0,
        pausedAccum: 0,
        pauseStarted: 0,
        utterance: null,
        lastSpokenAt: -1,
        usingFile: false,

        get remaining() {
            return Math.max(0, this.duration - this.elapsed);
        },

        init() {
            document.documentElement.classList.add('relax-shell');
        },

        start() {
            this.usingFile = Boolean(this.session.audio_url);
            this.phase = 'playing';
            this.elapsed = 0;
            this.lastSpokenAt = -1;
            this.pausedAccum = 0;
            this.startedAt = performance.now();
            if (this.usingFile) {
                const audio = this.$refs.audio;
                audio.volume = this.volume;
                audio.currentTime = 0;
                audio.ontimeupdate = () => {
                    this.elapsed = audio.currentTime;
                    if (audio.duration && isFinite(audio.duration)) this.duration = audio.duration;
                    this.updateCue();
                };
                audio.onended = () => this.finish();
                audio.play();
            } else {
                this.speakCurrent();
                this.tickSpeech();
            }
        },

        tickSpeech() {
            this.timer = requestAnimationFrame(() => {
                if (this.phase !== 'playing') return;
                this.elapsed = (performance.now() - this.startedAt - this.pausedAccum) / 1000;
                if (this.elapsed >= this.duration) {
                    this.finish();
                    return;
                }
                this.updateCue(true);
                this.tickSpeech();
            });
        },

        updateCue(speak = false) {
            const cues = this.session.cues || [];
            let active = cues[0]?.text || '';
            let activeAt = cues[0]?.at ?? 0;
            for (const cue of cues) {
                if (this.elapsed >= cue.at) {
                    active = cue.text;
                    activeAt = cue.at;
                }
            }
            this.currentCue = active;
            if (speak && !this.usingFile && activeAt !== this.lastSpokenAt && this.elapsed >= activeAt) {
                this.lastSpokenAt = activeAt;
                this.speak(active);
            }
        },

        speakCurrent() {
            const first = this.session.cues?.[0];
            if (first) {
                this.lastSpokenAt = first.at;
                this.speak(first.text);
            }
        },

        speak(text) {
            if (!window.speechSynthesis) return;
            window.speechSynthesis.cancel();
            const u = new SpeechSynthesisUtterance(text);
            u.lang = 'es-ES';
            u.rate = 0.82;
            u.pitch = 0.95;
            u.volume = this.volume;
            const voices = window.speechSynthesis.getVoices();
            const es = voices.find(v => v.lang?.startsWith('es') && /female|samantha|monica|paulina|google/i.test(v.name))
                || voices.find(v => v.lang?.startsWith('es'));
            if (es) u.voice = es;
            this.utterance = u;
            window.speechSynthesis.speak(u);
        },

        toggle() {
            if (this.phase === 'playing') this.pause();
            else if (this.phase === 'paused') this.resume();
        },

        pause() {
            this.phase = 'paused';
            this.pauseStarted = performance.now();
            if (this.usingFile) this.$refs.audio.pause();
            else if (window.speechSynthesis) window.speechSynthesis.pause();
        },

        resume() {
            this.pausedAccum += performance.now() - this.pauseStarted;
            this.phase = 'playing';
            if (this.usingFile) this.$refs.audio.play();
            else {
                if (window.speechSynthesis) window.speechSynthesis.resume();
                this.tickSpeech();
            }
        },

        seek(value) {
            const t = Number(value);
            this.elapsed = t;
            if (this.usingFile) this.$refs.audio.currentTime = t;
            else {
                this.startedAt = performance.now() - (t * 1000) - this.pausedAccum;
                this.lastSpokenAt = -1;
                this.updateCue(true);
            }
        },

        applyVolume() {
            if (this.usingFile && this.$refs.audio) this.$refs.audio.volume = this.volume;
            if (this.utterance) this.utterance.volume = this.volume;
        },

        finish() {
            this.phase = 'done';
            if (window.speechSynthesis) window.speechSynthesis.cancel();
            if (this.$refs.audio) {
                this.$refs.audio.pause();
            }
        },

        exit() {
            if (window.speechSynthesis) window.speechSynthesis.cancel();
            window.location.href = @json(route('relaxation.index'));
        },

        formatTime(sec) {
            sec = Math.max(0, Math.floor(sec || 0));
            const m = Math.floor(sec / 60);
            const s = sec % 60;
            return `${m}:${String(s).padStart(2, '0')}`;
        }
    };
}
if (window.speechSynthesis) {
    window.speechSynthesis.getVoices();
    window.speechSynthesis.onvoiceschanged = () => window.speechSynthesis.getVoices();
}
</script>
@endpush
@endsection
