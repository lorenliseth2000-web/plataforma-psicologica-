@extends('layouts.app')

@section('title', 'Preguntas del Tamizaje - Admin')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs">
            <div>
                <div class="flex items-center gap-2 text-xs text-slate-400 font-semibold mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600">&larr; Volver al Panel Admin</a>
                </div>
                <h1 class="text-2xl font-extrabold text-slate-900">Banco de Preguntas Clínicas de Tamizaje</h1>
                <p class="text-xs text-slate-500 mt-1">Configuración y ponderación de los ítems para evaluar ansiedad y estrés.</p>
            </div>

            <a href="{{ route('admin.questions.create') }}" class="px-5 py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-xs hover:bg-indigo-700 transition shadow-md shadow-indigo-100 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Nueva Pregunta
            </a>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-bold">
                        <tr>
                            <th class="py-4 px-6">Orden</th>
                            <th class="py-4 px-6">Bloque</th>
                            <th class="py-4 px-6">Texto de la Pregunta</th>
                            <th class="py-4 px-6">Foco Clínico</th>
                            <th class="py-4 px-6 text-center">Peso</th>
                            <th class="py-4 px-6 text-center">Respuestas</th>
                            <th class="py-4 px-6">Estado</th>
                            <th class="py-4 px-6 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($questions as $q)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-4 px-6 font-black text-slate-400">
                                    #{{ $q->order }}
                                </td>
                                <td class="py-4 px-6">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase {{ $q->type === 'ansiedad' ? 'bg-indigo-100 text-indigo-800' : 'bg-teal-100 text-teal-800' }}">
                                        {{ ucfirst($q->type) }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 font-semibold text-slate-800 max-w-md">
                                    {{ $q->text }}
                                </td>
                                <td class="py-4 px-6 text-xs text-slate-500">
                                    {{ $q->symptom_focus ?: 'General' }}
                                </td>
                                <td class="py-4 px-6 text-center font-bold text-slate-800">
                                    x{{ $q->weight }}
                                </td>
                                <td class="py-4 px-6 text-center font-bold text-indigo-600">
                                    {{ $q->answers_count }}
                                </td>
                                <td class="py-4 px-6">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $q->active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $q->active ? 'Activa' : 'Inactiva' }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right space-x-2">
                                    <a href="{{ route('admin.questions.edit', $q) }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-bold bg-indigo-50 px-3 py-1.5 rounded-xl">
                                        Editar
                                    </a>
                                    <form method="POST" action="{{ route('admin.questions.destroy', $q) }}" onsubmit="return confirm('¿Eliminar esta pregunta?')" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-rose-600 hover:text-rose-800 font-bold bg-rose-50 px-3 py-1.5 rounded-xl">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
