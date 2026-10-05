@extends('layouts.app')

@section('title', 'Historial de Tamizajes')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900">Evaluaciones de Ansiedad y Estrés</h1>
                <p class="text-sm text-slate-500 mt-1">Historial de tus tamizajes clínicos y evolución de indicadores de tensión.</p>
            </div>

            <a href="{{ route('assessments.create') }}" class="px-6 py-3 rounded-2xl bg-indigo-600 text-white font-bold text-sm hover:bg-indigo-700 transition shadow-md shadow-indigo-100 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Realizar Nueva Evaluación
            </a>
        </div>

        @if($assessments->count() > 0)
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-bold">
                            <tr>
                                <th class="py-4 px-6">Fecha de Realización</th>
                                <th class="py-4 px-6">Nivel de Riesgo / Tensión</th>
                                <th class="py-4 px-6">Puntaje Ansiedad</th>
                                <th class="py-4 px-6">Puntaje Estrés</th>
                                <th class="py-4 px-6">Puntaje Total</th>
                                <th class="py-4 px-6 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($assessments as $item)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="py-4 px-6 font-semibold text-slate-800">
                                        {{ $item->completed_at->format('d/m/Y - h:i A') }}
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $item->risk_level === 'alto' ? 'bg-rose-100 text-rose-800' : ($item->risk_level === 'moderado' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800') }}">
                                            {{ ucfirst($item->risk_level) }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="font-bold text-indigo-600">{{ $item->score_anxiety }}</span> / 15
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="font-bold text-teal-600">{{ $item->score_stress }}</span> / 15
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="font-black text-slate-900">{{ $item->total_score }}</span> / 30
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <a href="{{ route('assessments.show', $item) }}" class="inline-flex items-center gap-1 text-xs font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 px-3 py-1.5 rounded-xl hover:bg-indigo-100 transition">
                                            Ver Informe Completo &rarr;
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t border-slate-100">
                    {{ $assessments->links() }}
                </div>
            </div>
        @else
            <div class="text-center py-16 bg-white rounded-3xl border border-slate-200 p-8 space-y-4">
                <div class="w-16 h-16 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center mx-auto">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Aún no tienes evaluaciones registradas</h3>
                <p class="text-sm text-slate-500 max-w-md mx-auto">Realiza tu primer tamizaje para conocer tus niveles actuales de ansiedad y estrés y recibir recomendaciones terapéuticas personalizadas.</p>
                <a href="{{ route('assessments.create') }}" class="inline-block px-6 py-3 rounded-2xl bg-indigo-600 text-white font-bold text-sm hover:bg-indigo-700 transition shadow-md shadow-indigo-100">
                    Comenzar Evaluación
                </a>
            </div>
        @endif

    </div>
</div>
@endsection
