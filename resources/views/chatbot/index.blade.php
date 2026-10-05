@extends('layouts.app')
@section('title', 'Acompañante — Sukha')

@push('styles')
<style>
.chat-bubble-bot  { background: white; border: 1px solid #D4E5D2; color: #2C3E35; border-radius: 0 18px 18px 18px; }
.chat-bubble-user { background: linear-gradient(135deg,#7D9B76,#8FAF88); color: white; border-radius: 18px 18px 0 18px; }
.chat-bubble-crisis{ background: #FFF5F0; border: 1.5px solid #E8C4B8; color: #7A2E1A; border-radius: 0 18px 18px 18px; }
.dot-typing span { display:inline-block; width:7px; height:7px; border-radius:50%; background:#B5C9B3; animation:dotPulse 1.4s ease-in-out infinite; }
.dot-typing span:nth-child(2){animation-delay:.2s}
.dot-typing span:nth-child(3){animation-delay:.4s}
@keyframes dotPulse{0%,80%,100%{transform:scale(.7);opacity:.5}40%{transform:scale(1);opacity:1}}
#chatMessages { scroll-behavior: smooth; }
</style>
@endpush

@section('content')
<div class="py-6">
<div class="max-w-2xl mx-auto px-4 sm:px-6" x-data="chatbot()" x-init="init()">

    {{-- Header --}}
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-widest mb-1" style="color: #7D9B76;">Sukha · Acompañante emocional</p>
                <h1 class="font-playfair text-2xl sm:text-3xl font-bold" style="color: #2C3E35;">Un espacio seguro para ti</h1>
            </div>
            <a href="{{ route('chatbot.index', ['nueva'=>1]) }}"
               class="text-xs px-3 py-1.5 rounded-xl border"
               style="border-color: #D4E5D2; color: #6B7B6E;"
               title="Nueva conversación">
                Nueva conversación
            </a>
        </div>
        <p class="mt-2 text-sm" style="color: #6B7B6E;">Puedes contarme lo que estás sintiendo. No necesitas tener una respuesta clara ahora.</p>
        <div class="mt-3 text-xs px-4 py-2 rounded-xl" style="background: #EAF2E9; color: #4A6A55;">
            Este espacio es confidencial. Sukha no diagnostica ni toma decisiones por ti. Si estás en una emergencia, llama a los servicios de tu país.
        </div>
    </div>

    {{-- Chat window --}}
    <div id="chatMessages"
         class="rounded-3xl p-5 space-y-4 overflow-y-auto mb-4"
         style="background: #FAF7F0; border: 1px solid #D4E5D2; min-height: 380px; max-height: 55vh;">

        {{-- Mensaje de bienvenida --}}
        <div class="flex gap-3">
            <div class="w-8 h-8 rounded-xl flex-shrink-0 flex items-center justify-center" style="background: linear-gradient(135deg,#7D9B76,#8FAF88);">
                <svg width="16" height="16" viewBox="0 0 64 64" fill="white"><path d="M32 44 C22 40 16 28 20 16 C24 24 28 34 32 44Z"/><path d="M32 44 C42 40 48 28 44 16 C40 24 36 34 32 44Z"/><path d="M32 44 C26 32 26 18 32 8 C38 18 38 32 32 44Z" fill="rgba(255,255,255,0.7)"/></svg>
            </div>
            <div class="chat-bubble-bot px-4 py-3 text-sm leading-relaxed max-w-[85%]" style="box-shadow:0 2px 8px rgba(125,155,118,.07)">
                Estoy aquí para escucharte. Puedes contarme lo que estás viviendo, aunque no tengas palabras exactas para describir lo que sientes. No hay respuestas correctas o incorrectas en este espacio.
            </div>
        </div>

        {{-- Mensajes dinámicos --}}
        <template x-for="msg in messages" :key="msg.id">
            <div :class="msg.role === 'user' ? 'flex justify-end' : 'flex gap-3'">
                <div x-show="msg.role !== 'user'" class="w-8 h-8 rounded-xl flex-shrink-0 flex items-center justify-center" style="background: linear-gradient(135deg,#7D9B76,#8FAF88);">
                    <svg width="16" height="16" viewBox="0 0 64 64" fill="white"><path d="M32 44 C22 40 16 28 20 16 C24 24 28 34 32 44Z"/><path d="M32 44 C42 40 48 28 44 16 C40 24 36 34 32 44Z"/><path d="M32 44 C26 32 26 18 32 8 C38 18 38 32 32 44Z" fill="rgba(255,255,255,.7)"/></svg>
                </div>
                <div :class="msg.role === 'user' ? 'chat-bubble-user' : (msg.isCrisis ? 'chat-bubble-crisis' : 'chat-bubble-bot')"
                     class="px-4 py-3 text-sm leading-relaxed max-w-[85%]"
                     style="box-shadow:0 2px 8px rgba(0,0,0,.06); white-space:pre-wrap;"
                     x-text="msg.content"></div>
            </div>
        </template>

        {{-- Typing indicator --}}
        <div x-show="typing" class="flex gap-3">
            <div class="w-8 h-8 rounded-xl flex-shrink-0 flex items-center justify-center" style="background: linear-gradient(135deg,#7D9B76,#8FAF88);">
                <svg width="16" height="16" viewBox="0 0 64 64" fill="white"><path d="M32 44 C22 40 16 28 20 16 C24 24 28 34 32 44Z"/><path d="M32 44 C42 40 48 28 44 16 C40 24 36 34 32 44Z"/></svg>
            </div>
            <div class="chat-bubble-bot px-4 py-3">
                <div class="dot-typing flex gap-1 items-center h-5">
                    <span></span><span></span><span></span>
                </div>
            </div>
        </div>
    </div>

    {{-- Sugerencias rápidas --}}
    <div x-show="messages.length === 0" class="flex flex-wrap gap-2 mb-3">
        <button @click="sendQuick('Hoy tuve un día muy difícil')"
                class="text-xs px-3 py-1.5 rounded-xl border transition-all hover:bg-[#EAF2E9]"
                style="border-color: #D4E5D2; color: #4A6A55;">Hoy tuve un día difícil</button>
        <button @click="sendQuick('Me siento ansioso o ansiosa')"
                class="text-xs px-3 py-1.5 rounded-xl border transition-all hover:bg-[#EAF2E9]"
                style="border-color: #D4E5D2; color: #4A6A55;">Me siento ansioso/a</button>
        <button @click="sendQuick('No sé qué hacer con una situación')"
                class="text-xs px-3 py-1.5 rounded-xl border transition-all hover:bg-[#EAF2E9]"
                style="border-color: #D4E5D2; color: #4A6A55;">No sé qué hacer</button>
        <button @click="sendQuick('Solo quiero hablar')"
                class="text-xs px-3 py-1.5 rounded-xl border transition-all hover:bg-[#EAF2E9]"
                style="border-color: #D4E5D2; color: #4A6A55;">Solo quiero hablar</button>
    </div>

    {{-- Input area --}}
    <div class="flex gap-3 items-end">
        <textarea x-model="inputText"
                  @keydown.enter.exact.prevent="send()"
                  @keydown.enter.shift.exact="null"
                  :disabled="typing"
                  rows="2"
                  placeholder="Escribe aquí... (Enter para enviar, Shift+Enter para salto de línea)"
                  class="flex-1 resize-none px-4 py-3 rounded-2xl text-sm border outline-none transition-all"
                  style="border-color: #D4E5D2; background: white; color: #2C3E35; line-height: 1.5;"
                  onfocus="this.style.borderColor='#7D9B76'"
                  onblur="this.style.borderColor='#D4E5D2'"></textarea>
        <button @click="send()"
                :disabled="!inputText.trim() || typing"
                class="w-12 h-12 rounded-2xl flex items-center justify-center text-white transition-all flex-shrink-0"
                :style="inputText.trim() && !typing ? 'background: linear-gradient(135deg,#7D9B76,#8FAF88);' : 'background: #D4E5D2;'">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/>
            </svg>
        </button>
    </div>

    {{-- Error --}}
    <div x-show="error" x-transition class="mt-3 text-xs px-4 py-2 rounded-xl" style="background: #FFF5F0; color: #9B4D35;">
        <span x-text="error"></span>
    </div>

    {{-- Footer nota --}}
    <p class="mt-4 text-[11px] text-center" style="color: #B5C9B3;">
        Sukha no es un servicio de crisis. Si estás en peligro,
        <a href="{{ route('emergency.index') }}" class="underline" style="color: #C4856A;">llama a emergencias</a>.
    </p>
</div>
</div>
@endsection

@push('scripts')
<script>
function chatbot() {
    return {
        messages: [],
        inputText: '',
        typing: false,
        error: '',
        msgCounter: 0,

        init() {
            this.scrollBottom();
        },

        sendQuick(text) {
            this.inputText = text;
            this.send();
        },

        async send() {
            const text = this.inputText.trim();
            if (!text || this.typing) return;

            this.inputText = '';
            this.error = '';
            this.messages.push({ id: ++this.msgCounter, role: 'user', content: text, isCrisis: false });
            this.typing = true;
            this.$nextTick(() => this.scrollBottom());

            try {
                const res = await fetch('{{ route('chatbot.send') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '',
                    },
                    body: JSON.stringify({ message: text }),
                });

                if (!res.ok) throw new Error('Error ' + res.status);

                const data = await res.json();
                this.messages.push({
                    id: ++this.msgCounter,
                    role: 'assistant',
                    content: data.response,
                    isCrisis: data.is_crisis || false,
                });
            } catch (e) {
                this.error = 'No pude obtener una respuesta. Verifica tu conexión e inténtalo nuevamente.';
            } finally {
                this.typing = false;
                this.$nextTick(() => this.scrollBottom());
            }
        },

        scrollBottom() {
            const el = document.getElementById('chatMessages');
            if (el) el.scrollTop = el.scrollHeight;
        },
    };
}
</script>
@endpush
