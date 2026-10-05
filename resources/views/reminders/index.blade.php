@extends('layouts.app')

@section('title', 'Mis Recordatorios')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Encabezado --}}
        <div class="rounded-3xl p-6 sm:p-8" style="background: white; border: 1px solid #D4E5D2; box-shadow: 0 4px 20px rgba(125,155,118,0.08);">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <svg class="w-5 h-5" style="color: #7D9B76;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <h1 class="text-2xl font-bold" style="color: #2C3E35; font-family: 'Playfair Display', Georgia, serif;">
                            Mis Recordatorios
                        </h1>
                    </div>
                    <p class="text-sm" style="color: #6B7B6E;">
                        Configura notificaciones del navegador para tu práctica de bienestar diario.
                    </p>
                </div>
                <div class="rounded-2xl px-4 py-2 text-center" style="background: #EAF2E9;">
                    <span class="text-2xl font-black" style="color: #2C5E28;">{{ $reminders->where('active', true)->count() }}</span>
                    <span class="text-xs block font-semibold" style="color: #4A6A55;">activos de 5 máx.</span>
                </div>
            </div>
        </div>

        {{-- Mensajes de estado --}}
        @if(session('status'))
            <div class="rounded-2xl px-5 py-3.5 flex items-center gap-3 text-sm font-medium" style="background: #EAF2E9; border: 1px solid #B5C9B3; color: #2C5E28;">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div class="rounded-2xl px-5 py-3.5 text-sm" style="background: #FFF5F0; border: 1px solid #FBBAA0; color: #7A3A1E;">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Info sobre notificaciones del navegador --}}
        <div class="rounded-2xl px-5 py-4 flex items-start gap-3 text-sm" style="background: #FAF7F0; border: 1px solid #EDE8DC;">
            <svg class="w-5 h-5 shrink-0 mt-0.5" style="color: #C4856A;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p style="color: #6B7B6E; line-height: 1.6;">
                Los recordatorios funcionan como notificaciones del navegador <strong style="color: #4A6A55;">mientras tienes Sukha abierto</strong>.
                Para que aparezcan, acepta los permisos cuando el navegador lo solicite.
            </p>
        </div>

        <div class="grid lg:grid-cols-12 gap-6">

            {{-- LISTA DE RECORDATORIOS --}}
            <div class="lg:col-span-7 space-y-4">
                <h2 class="text-sm font-bold uppercase tracking-wide" style="color: #4A6A55;">Recordatorios configurados</h2>

                @forelse($reminders as $reminder)
                <div class="rounded-2xl p-5 flex items-center justify-between gap-4 transition"
                     style="background: white; border: 1px solid {{ $reminder->active ? '#D4E5D2' : '#EDE8DC' }};">
                    <div class="flex items-center gap-4">
                        {{-- Ícono del tipo --}}
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
                             style="background: {{ $reminder->active ? '#EAF2E9' : '#F5F0E8' }};">
                            <svg class="w-5 h-5" style="color: {{ $reminder->active ? '#7D9B76' : '#B5A898' }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ \App\Models\UserReminder::typeIcon($reminder->type) }}"/>
                            </svg>
                        </div>

                        <div>
                            <p class="font-semibold text-sm" style="color: {{ $reminder->active ? '#2C3E35' : '#8A8A8A' }};">
                                {{ $reminder->label }}
                            </p>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="text-xs font-semibold" style="color: #7D9B76;">
                                    {{ \Carbon\Carbon::createFromFormat('H:i:s', $reminder->reminder_time)->format('g:i A') }}
                                </span>
                                <span class="text-[10px]" style="color: #B5C9B3;">·</span>
                                <span class="text-xs" style="color: #6B7B6E;">
                                    {{ ($frequencyLabels[$reminder->frequency] ?? $reminder->frequency) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        {{-- Toggle activo/inactivo --}}
                        <form method="POST" action="{{ route('reminders.toggle', $reminder) }}">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    class="px-3 py-1.5 rounded-xl text-xs font-bold transition"
                                    style="{{ $reminder->active
                                        ? 'background: #EAF2E9; color: #2C5E28; border: 1px solid #B5C9B3;'
                                        : 'background: #F5F0E8; color: #8A8A8A; border: 1px solid #DDD;' }}">
                                {{ $reminder->active ? 'Activo' : 'Pausado' }}
                            </button>
                        </form>

                        {{-- Eliminar --}}
                        <form method="POST" action="{{ route('reminders.destroy', $reminder) }}"
                              onsubmit="return confirm('¿Eliminar este recordatorio?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="w-8 h-8 rounded-xl flex items-center justify-center transition"
                                    style="background: #FFF5F0; border: 1px solid #FBBAA0; color: #C4856A;">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="rounded-2xl p-8 text-center" style="background: #F5FAF4; border: 1.5px dashed #B5C9B3;">
                    <svg class="w-10 h-10 mx-auto mb-3" style="color: #B5C9B3;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <p class="text-sm font-semibold" style="color: #6B7B6E;">No tienes recordatorios configurados</p>
                    <p class="text-xs mt-1" style="color: #B5C9B3;">Crea uno usando el formulario</p>
                </div>
                @endforelse
            </div>

            {{-- FORMULARIO NUEVO RECORDATORIO --}}
            <div class="lg:col-span-5">
                <div class="rounded-3xl p-6 sticky top-4" style="background: white; border: 1px solid #D4E5D2;">
                    <h2 class="text-sm font-bold uppercase tracking-wide mb-5" style="color: #4A6A55;">
                        Nuevo recordatorio
                    </h2>

                    <form method="POST" action="{{ route('reminders.store') }}" class="space-y-4">
                        @csrf

                        {{-- Tipo --}}
                        <div>
                            <label class="block text-xs font-semibold mb-1.5" style="color: #4A6A55;">¿Qué quieres recordar?</label>
                            <select name="type" required
                                    class="w-full px-3 py-2.5 rounded-xl text-sm border outline-none transition"
                                    style="border-color: #D4E5D2; color: #2C3E35;"
                                    onfocus="this.style.borderColor='#7D9B76'"
                                    onblur="this.style.borderColor='#D4E5D2'">
                                <option value="">Seleccionar tipo...</option>
                                @foreach($typeLabels as $value => $label)
                                    <option value="{{ $value }}" {{ old('type') === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('type')" class="mt-1"/>
                        </div>

                        {{-- Etiqueta personalizada --}}
                        <div>
                            <label class="block text-xs font-semibold mb-1.5" style="color: #4A6A55;">Nombre del recordatorio</label>
                            <input type="text" name="label" value="{{ old('label') }}" required
                                   placeholder="Ej: Respiración de la mañana"
                                   maxlength="120"
                                   class="w-full px-3 py-2.5 rounded-xl text-sm border outline-none transition"
                                   style="border-color: #D4E5D2; color: #2C3E35;"
                                   onfocus="this.style.borderColor='#7D9B76'"
                                   onblur="this.style.borderColor='#D4E5D2'">
                            <x-input-error :messages="$errors->get('label')" class="mt-1"/>
                        </div>

                        {{-- Hora --}}
                        <div>
                            <label class="block text-xs font-semibold mb-1.5" style="color: #4A6A55;">Hora</label>
                            <input type="time" name="reminder_time" value="{{ old('reminder_time', '08:00') }}" required
                                   class="w-full px-3 py-2.5 rounded-xl text-sm border outline-none transition"
                                   style="border-color: #D4E5D2; color: #2C3E35;"
                                   onfocus="this.style.borderColor='#7D9B76'"
                                   onblur="this.style.borderColor='#D4E5D2'">
                            <x-input-error :messages="$errors->get('reminder_time')" class="mt-1"/>
                        </div>

                        {{-- Frecuencia --}}
                        <div>
                            <label class="block text-xs font-semibold mb-1.5" style="color: #4A6A55;">Frecuencia</label>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach($frequencyLabels as $value => $label)
                                <label class="flex items-center gap-2 cursor-pointer rounded-xl px-3 py-2 border transition"
                                       style="border-color: #D4E5D2; font-size: 12px; color: #4A6A55;">
                                    <input type="radio" name="frequency" value="{{ $value }}"
                                           {{ old('frequency', 'daily') === $value ? 'checked' : '' }}
                                           class="w-3.5 h-3.5" style="accent-color: #7D9B76;">
                                    {{ $label }}
                                </label>
                                @endforeach
                            </div>
                            <x-input-error :messages="$errors->get('frequency')" class="mt-1"/>
                        </div>

                        <button type="submit"
                                class="w-full py-3 rounded-xl font-bold text-sm text-white transition"
                                style="background: linear-gradient(135deg, #7D9B76, #8FAF88);"
                                onmouseover="this.style.opacity='0.9'"
                                onmouseout="this.style.opacity='1'">
                            Crear recordatorio
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Script para activar notificaciones del navegador --}}
@push('scripts')
<script>
(async function() {
    // Solicitar permiso de notificaciones del navegador
    if ('Notification' in window && Notification.permission === 'default') {
        await Notification.requestPermission();
    }

    // Programar notificaciones para recordatorios activos
    const reminders = @json($reminders->where('active', true)->values());

    reminders.forEach(reminder => {
        scheduleReminder(reminder);
    });

    function scheduleReminder(reminder) {
        if (Notification.permission !== 'granted') return;

        const now = new Date();
        const [hours, minutes] = reminder.reminder_time.split(':');
        const target = new Date();
        target.setHours(parseInt(hours), parseInt(minutes), 0, 0);

        // Si ya pasó la hora hoy, programar para mañana
        if (target <= now) target.setDate(target.getDate() + 1);

        const msUntil = target - now;

        setTimeout(() => {
            const notif = new Notification('Sukha — ' + reminder.label, {
                body: getLabelForType(reminder.type),
                icon: '/favicon.ico',
                badge: '/favicon.ico',
                tag: 'sukha-reminder-' + reminder.id,
                requireInteraction: true,
            });

            notif.onclick = () => {
                window.focus();
                window.location.href = '/plataformapsicologica/public/tecnicas';
                notif.close();
            };

            // Reagendar para el siguiente día si es daily
            if (reminder.frequency === 'daily') {
                setTimeout(() => scheduleReminder(reminder), 24 * 60 * 60 * 1000);
            }
        }, msUntil);
    }

    function getLabelForType(type) {
        const labels = {
            'respiracion': 'Es momento de tu ejercicio de respiración. Tómate un instante.',
            'relajacion': 'Tu práctica de relajación te espera. Unos minutos para ti.',
            'registro_emocional': '¿Cómo te sientes hoy? Registra tu estado emocional.',
            'pausa': 'Haz una pausa consciente. Tu mente lo necesita.',
            'progreso': 'Revisa tu progreso en Sukha. Cada paso cuenta.',
        };
        return labels[type] || 'Es momento de tu práctica de bienestar.';
    }
})();
</script>
@endpush
@endsection
