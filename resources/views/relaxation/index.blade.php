@extends('layouts.app')

@section('title', 'Relajación guiada')

@section('content')
<div class="py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <div class="rounded-3xl p-6 sm:p-10" style="background: linear-gradient(135deg, #EAF2E9 0%, #FAF7F0 70%); border: 1px solid #D4E5D2;">
            <p class="text-[11px] font-bold uppercase tracking-widest mb-2" style="color: #7D9B76;">Relajación guiada por voz</p>
            <h1 class="font-playfair text-3xl sm:text-4xl font-bold" style="color: #2C3E35;">Elige una sesión y ponte los audífonos</h1>
            <p class="mt-3 text-sm leading-relaxed max-w-2xl" style="color: #6B7B6E;">
                Una voz cálida y pausada te acompaña. No hay meta que cumplir. Puedes pausar o salir cuando lo necesites.
                La reproducción no empieza sola: tú decides cuándo comenzar.
            </p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($sessions as $item)
                <article class="rounded-3xl overflow-hidden flex flex-col" style="background: white; border: 1px solid #D4E5D2; box-shadow: 0 4px 20px rgba(125,155,118,0.08);">
                    <div class="h-28 relative" style="background: linear-gradient(135deg, {{ $item['accent'] }}22, #FAF7F0);">
                        <svg class="absolute right-4 bottom-2 w-20 h-20 opacity-40" viewBox="0 0 64 64" fill="none">
                            <path d="M32 44 C22 40 16 28 20 16 C24 24 28 34 32 44Z" fill="{{ $item['accent'] }}"/>
                            <path d="M32 44 C42 40 48 28 44 16 C40 24 36 34 32 44Z" fill="{{ $item['accent'] }}"/>
                            <path d="M32 44 C26 32 26 18 32 8 C38 18 38 32 32 44Z" fill="{{ $item['accent'] }}"/>
                        </svg>
                        <span class="absolute left-4 top-4 text-xs font-bold px-3 py-1 rounded-full" style="background: white; color: {{ $item['accent'] }};">{{ $item['duration_label'] }}</span>
                    </div>
                    <div class="p-6 flex flex-col flex-1 gap-3">
                        <h2 class="font-playfair text-xl font-semibold" style="color: #2C3E35;">{{ $item['name'] }}</h2>
                        <p class="text-sm leading-relaxed flex-1" style="color: #6B7B6E;">{{ $item['description'] }}</p>
                        <a href="{{ route('relaxation.show', $item['slug']) }}"
                           class="text-center py-3 rounded-2xl text-sm font-bold text-white transition"
                           style="background: linear-gradient(135deg, #7D9B76, #8FAF88);">
                            Comenzar
                        </a>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</div>
@endsection
