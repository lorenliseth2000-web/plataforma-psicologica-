@extends('layouts.app')

@section('title', 'Biblioteca de Técnicas de Regulación')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <!-- Encabezado y Filtros -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-teal-600 bg-teal-50 px-3 py-1 rounded-full">
                        Biblioteca Terapéutica Basada en Evidencia
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">
                        Técnicas Guiadas de Regulación Emocional
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">
                        Ejercicios interactivos con animación visual de respiración, temporizador y sonidos relajantes.
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('assessments.create') }}" class="px-4 py-2.5 rounded-xl bg-indigo-50 text-indigo-700 font-bold text-xs hover:bg-indigo-100 transition flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Descubrir cuál necesito hoy
                    </a>
                </div>
            </div>

            <!-- Barra de Búsqueda y Pestañas de Categoría -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-4 border-t border-slate-100">
                <div class="flex items-center gap-2 overflow-x-auto pb-1 sm:pb-0">
                    <a href="{{ route('techniques.index') }}"
                       class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ !$category ? 'bg-slate-900 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        Todas las Técnicas ({{ \App\Models\Technique::active()->count() }})
                    </a>
                    <a href="{{ route('techniques.index', ['category' => 'ansiedad']) }}"
                       class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $category === 'ansiedad' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-indigo-50 text-indigo-700 hover:bg-indigo-100' }}">
                        Para Ansiedad ({{ \App\Models\Technique::active()->anxiety()->count() }})
                    </a>
                    <a href="{{ route('techniques.index', ['category' => 'estres']) }}"
                       class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $category === 'estres' ? 'bg-teal-600 text-white shadow-xs' : 'bg-teal-50 text-teal-700 hover:bg-teal-100' }}">
                        Para Estrés ({{ \App\Models\Technique::active()->stress()->count() }})
                    </a>
                </div>

                <form method="GET" action="{{ route('techniques.index') }}" class="flex items-center gap-2">
                    @if($category)
                        <input type="hidden" name="category" value="{{ $category }}">
                    @endif
                    <div class="relative w-full sm:w-64">
                        <input type="text"
                               name="search"
                               value="{{ $search }}"
                               placeholder="Buscar técnica o beneficio..."
                               class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:outline-hidden focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    @if($search)
                        <a href="{{ route('techniques.index', array_filter(['category' => $category])) }}" class="text-xs text-slate-400 hover:text-slate-600 font-bold">&times; Limpiar</a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Grid de Técnicas -->
        @if($techniques->count() > 0)
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($techniques as $technique)
                    @php
                        $isCompleted = in_array($technique->id, $completedTechniqueIds);
                    @endphp
                    <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-xs hover:shadow-lg transition duration-200 flex flex-col justify-between group">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider {{ $technique->category === 'ansiedad' ? 'bg-indigo-100 text-indigo-800' : 'bg-teal-100 text-teal-800' }}">
                                    {{ ucfirst($technique->category) }}
                                </span>

                                <div class="flex items-center gap-2">
                                    @if($isCompleted)
                                        <span class="text-[10px] bg-emerald-50 text-emerald-700 border border-emerald-200 px-2 py-0.5 rounded-full font-bold flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            Practicada
                                        </span>
                                    @endif
                                    <span class="text-xs font-bold text-slate-500 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        {{ $technique->duration_minutes }} min
                                    </span>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-black text-slate-900 group-hover:text-indigo-600 transition">
                                    {{ $technique->name }}
                                </h3>
                                <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed mt-1.5">
                                    {{ $technique->description }}
                                </p>
                            </div>

                            <!-- Beneficios Destacados -->
                            <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-100 space-y-1">
                                <div class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Beneficio Clave:</div>
                                <p class="text-xs text-slate-700 font-medium line-clamp-2">
                                    {{ Str::limit($technique->benefits, 110) }}
                                </p>
                            </div>
                        </div>

                        <div class="pt-5 border-t border-slate-100 mt-6 flex items-center gap-2">
                            <a href="{{ route('techniques.show', $technique) }}" class="w-full py-3 rounded-2xl bg-slate-900 group-hover:bg-indigo-600 text-white font-bold text-xs text-center transition flex items-center justify-center gap-2 shadow-xs">
                                <span>Iniciar Práctica Guiada</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16 bg-white rounded-3xl border border-slate-200 p-8 space-y-4">
                <p class="text-base font-bold text-slate-700">No se encontraron técnicas con los filtros seleccionados.</p>
                <a href="{{ route('techniques.index') }}" class="inline-block px-5 py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-xs hover:bg-indigo-700 transition">
                    Ver Catálogo Completo
                </a>
            </div>
        @endif

    </div>
</div>
@endsection
