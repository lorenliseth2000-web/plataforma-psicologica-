@extends('layouts.app')

@section('title', 'Diario de Bienestar y Seguimiento Emocional')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <!-- Encabezado -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-purple-600 bg-purple-50 px-3 py-1 rounded-full">
                    Seguimiento Diario
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">
                    Diario de Bienestar Emocional
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Registra tus estados diarios de ansiedad, sobrecarga, descanso y calidad del sueño.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('emotional-logs.printable') }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-700 font-semibold text-xs hover:bg-slate-50 transition shadow-xs flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Imprimir Reporte
                </a>

                <a href="{{ route('emotional-logs.create') }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold text-xs hover:opacity-95 transition shadow-md shadow-purple-100 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    {{ $todayLog ? 'Editar Registro de Hoy' : 'Registrar Estado de Hoy' }}
                </a>
            </div>
        </div>

        <!-- Promedios de los Últimos 30 Días -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs text-center">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Promedio Ansiedad</span>
                <span class="text-2xl font-black text-indigo-600 mt-1 block">{{ $avgAnxiety }} <span class="text-xs text-slate-400 font-normal">/10</span></span>
            </div>

            <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs text-center">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Promedio Estrés</span>
                <span class="text-2xl font-black text-teal-600 mt-1 block">{{ $avgStress }} <span class="text-xs text-slate-400 font-normal">/10</span></span>
            </div>

            <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs text-center">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Sueño Promedio</span>
                <span class="text-2xl font-black text-purple-600 mt-1 block">{{ $avgSleep }} <span class="text-xs text-slate-400 font-normal">horas</span></span>
            </div>

            <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs text-center">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Ánimo Promedio</span>
                <span class="text-2xl font-black text-emerald-600 mt-1 block">{{ $avgMood }} <span class="text-xs text-slate-400 font-normal">/5</span></span>
            </div>
        </div>

        <!-- Filtros por Fecha y Tabla de Historial -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden space-y-4">
            
            <div class="p-6 pb-0 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <h2 class="text-base font-extrabold text-slate-900">Historial Diario Detallado</h2>

                <form method="GET" action="{{ route('emotional-logs.index') }}" class="flex flex-wrap items-center gap-2">
                    <input type="date" name="start_date" value="{{ $startDate }}" class="p-2 border border-slate-200 rounded-xl text-xs text-slate-700">
                    <span class="text-xs text-slate-400">hasta</span>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="p-2 border border-slate-200 rounded-xl text-xs text-slate-700">
                    <button type="submit" class="px-3 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-indigo-600 transition">
                        Filtrar
                    </button>
                    @if($startDate || $endDate)
                        <a href="{{ route('emotional-logs.index') }}" class="text-xs text-slate-400 hover:text-slate-600 font-bold ml-1">Limpiar</a>
                    @endif
                </form>
            </div>

            @if($logs->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 border-y border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-bold">
                            <tr>
                                <th class="py-4 px-6">Fecha</th>
                                <th class="py-4 px-6">Ansiedad (1-10)</th>
                                <th class="py-4 px-6">Estrés (1-10)</th>
                                <th class="py-4 px-6">Ánimo</th>
                                <th class="py-4 px-6">Horas de Sueño</th>
                                <th class="py-4 px-6">Notas</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($logs as $log)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="py-4 px-6 font-bold text-slate-900">
                                        {{ $log->log_date->format('d/m/Y') }}
                                        @if($log->log_date->isToday())
                                            <span class="ml-1 text-[10px] bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-full font-bold">Hoy</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-2">
                                            <div class="w-12 bg-slate-100 rounded-full h-2 overflow-hidden">
                                                <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $log->anxiety_level * 10 }}%"></div>
                                            </div>
                                            <span class="font-black text-indigo-600">{{ $log->anxiety_level }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-2">
                                            <div class="w-12 bg-slate-100 rounded-full h-2 overflow-hidden">
                                                <div class="bg-teal-600 h-2 rounded-full" style="width: {{ $log->stress_level * 10 }}%"></div>
                                            </div>
                                            <span class="font-black text-teal-600">{{ $log->stress_level }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="px-2.5 py-1 rounded-xl text-xs font-semibold bg-slate-100 text-slate-800">
                                            @switch($log->mood_level)
                                                @case(1) 😞 Muy bajo @break
                                                @case(2) 🙁 Desanimado @break
                                                @case(3) 😐 Neutral @break
                                                @case(4) 🙂 Bueno @break
                                                @case(5) 😄 Excelente @break
                                            @endswitch
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 font-semibold text-slate-700">
                                        {{ $log->sleep_hours }} h
                                    </td>
                                    <td class="py-4 px-6 text-xs text-slate-500 max-w-xs truncate">
                                        {{ $log->notes ?: '—' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t border-slate-100">
                    {{ $logs->links() }}
                </div>
            @else
                <div class="text-center py-12 space-y-3">
                    <p class="text-sm font-semibold text-slate-700">No hay registros de seguimiento para las fechas seleccionadas.</p>
                </div>
            @endif

        </div>

    </div>
</div>
@endsection
