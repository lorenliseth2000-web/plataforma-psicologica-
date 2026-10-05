@extends('layouts.app')

@section('title', 'Mi Historial Cognitivo')

@section('content')
<div class="py-8">
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold" style="font-family:'Playfair Display',serif;color:#2C3E35;">Mi Historial Cognitivo</h1>
            <p class="text-sm mt-1" style="color:#6B7B6E;">Tus ejercicios de reestructuración cognitiva guardados</p>
        </div>
        <a href="{{ route('techniques.show', App\Models\Technique::where('slug','reestructuracion-cognitiva')->first() ?? 1) }}"
           class="px-4 py-2.5 rounded-xl text-sm font-bold text-white transition-all hover:opacity-95 shadow-sm"
           style="background:linear-gradient(135deg,#7D9B76,#4A6A55);">
            Nuevo ejercicio
        </a>
    </div>

    @if($sessions->count() === 0)
    <div class="text-center py-16 rounded-3xl" style="background:white;border:1.5px solid #D4E5D2;">
        <div class="w-16 h-16 rounded-2xl mx-auto mb-4 flex items-center justify-center" style="background:#EAF2E9;">
            <svg class="w-8 h-8" style="color:#7D9B76;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <h3 class="text-base font-bold mb-2" style="color:#2C3E35;">Aún no tienes ejercicios guardados</h3>
        <p class="text-sm max-w-md mx-auto" style="color:#6B7B6E;">Completa tu primer ejercicio de reestructuración cognitiva para ver aquí tu evolución y cómo has transformado tus pensamientos.</p>
        <div class="mt-6">
            <a href="{{ route('techniques.show', App\Models\Technique::where('slug','reestructuracion-cognitiva')->first() ?? 1) }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold text-white shadow-sm"
               style="background:#7D9B76;">
                Comenzar ejercicio
            </a>
        </div>
    </div>
    @else
    <div class="space-y-4">
        @foreach($sessions as $session)
        <div class="rounded-3xl p-6 transition-shadow hover:shadow-md" style="background:white;border:1.5px solid #D4E5D2;box-shadow:0 2px 12px rgba(125,155,118,0.08);">
            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3 mb-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color:#8FAF88;">
                        {{ $session->created_at->translatedFormat('l, d \d\e F \d\e Y · H:i') }}
                    </p>
                    <p class="text-sm font-bold" style="color:#2C3E35;">
                        Situación: <span class="font-normal text-gray-700">{{ Str::limit($session->situation, 120) }}</span>
                    </p>
                </div>
                <div class="shrink-0 flex items-center gap-2">
                    @if($session->emotion)
                    <span class="px-3 py-1 rounded-full text-xs font-semibold" style="background:#EAF2E9;color:#4A6A55;">
                        {{ $session->emotion }}
                    </span>
                    @endif
                    @php $imp = $session->moodImprovement(); @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-bold"
                          style="{{ $imp > 0 ? 'background:#EAF2E9;color:#2C5E28;' : 'background:#FFF5EE;color:#7A3A1E;' }}">
                        {{ $imp > 0 ? '-'.$imp.'% malestar' : 'Sin variación' }}
                    </span>
                </div>
            </div>

            <div class="grid sm:grid-cols-2 gap-4 text-sm pt-2">
                <div class="p-3.5 rounded-2xl" style="background:#FAF7F0;border:1px solid #EAE4D7;">
                    <p class="text-xs font-bold uppercase tracking-wider mb-1" style="color:#C4856A;">Pensamiento automático inicial</p>
                    <p class="text-gray-700 text-xs sm:text-sm leading-relaxed">{{ Str::limit($session->negative_thought, 140) }}</p>
                </div>
                <div class="p-3.5 rounded-2xl" style="background:#F4F8F3;border:1px solid #D4E5D2;">
                    <p class="text-xs font-bold uppercase tracking-wider mb-1" style="color:#4A6A55;">Pensamiento alternativo realista</p>
                    <p class="text-gray-800 text-xs sm:text-sm italic leading-relaxed">"{{ Str::limit($session->alternative_thought, 140) }}"</p>
                </div>
            </div>

            @if($session->cognitive_traps && count($session->cognitive_traps))
            <div class="flex flex-wrap items-center gap-1.5 mt-4">
                <span class="text-xs font-medium mr-1" style="color:#8FAF88;">Trampas identificadas:</span>
                @foreach($session->cognitive_traps as $trap)
                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium" style="background:#EAF2E9;color:#4A6A55;border:1px solid #D4E5D2;">
                    {{ str_replace('_', ' ', ucfirst($trap)) }}
                </span>
                @endforeach
            </div>
            @endif

            <div class="flex flex-wrap items-center justify-between gap-4 mt-4 pt-4 text-xs" style="border-top:1px solid #EAF2E9;color:#6B7B6E;">
                <div class="flex items-center gap-4">
                    <span>Intensidad inicial: <strong style="color:#2C3E35;">{{ $session->emotion_before }}/10</strong></span>
                    <span>Intensidad final: <strong style="color:#2C3E35;">{{ $session->emotion_after }}/10</strong></span>
                </div>
                @if($session->duration_seconds > 0)
                <span>Duración: <strong style="color:#2C3E35;">{{ gmdate('i:s', $session->duration_seconds) }}</strong></span>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $sessions->links() }}
    </div>
    @endif

</div>
</div>
@endsection
