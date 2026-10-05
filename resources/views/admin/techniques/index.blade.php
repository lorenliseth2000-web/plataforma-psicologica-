@extends('layouts.app')

@section('title', 'Gestión de Técnicas - Admin')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs">
            <div>
                <div class="flex items-center gap-2 text-xs text-slate-400 font-semibold mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600">&larr; Volver al Panel Admin</a>
                </div>
                <h1 class="text-2xl font-extrabold text-slate-900">Catálogo de Técnicas de Regulación</h1>
                <p class="text-xs text-slate-500 mt-1">Administra los ejercicios interactivos disponibles en la plataforma.</p>
            </div>

            <a href="{{ route('admin.techniques.create') }}" class="px-5 py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-xs hover:bg-indigo-700 transition shadow-md shadow-indigo-100 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Nueva Técnica
            </a>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-bold">
                        <tr>
                            <th class="py-4 px-6">Orden</th>
                            <th class="py-4 px-6">Técnica</th>
                            <th class="py-4 px-6">Categoría</th>
                            <th class="py-4 px-6">Animación</th>
                            <th class="py-4 px-6">Duración</th>
                            <th class="py-4 px-6 text-center">Sesiones</th>
                            <th class="py-4 px-6">Estado</th>
                            <th class="py-4 px-6 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($techniques as $tech)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-4 px-6 font-bold text-slate-400">
                                    #{{ $tech->order }}
                                </td>
                                <td class="py-4 px-6">
                                    <div class="font-bold text-slate-900">{{ $tech->name }}</div>
                                    <div class="text-xs text-slate-400 truncate max-w-xs">{{ $tech->slug }}</div>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase {{ $tech->category === 'ansiedad' ? 'bg-indigo-100 text-indigo-800' : 'bg-teal-100 text-teal-800' }}">
                                        {{ $tech->category }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-xs text-slate-700">
                                    {{ ucfirst($tech->animation_type) }}
                                </td>
                                <td class="py-4 px-6 text-xs font-semibold text-slate-800">
                                    {{ $tech->duration_minutes }} min
                                </td>
                                <td class="py-4 px-6 text-center font-bold text-slate-800">
                                    {{ $tech->sessions_count }}
                                </td>
                                <td class="py-4 px-6">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $tech->active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $tech->active ? 'Activa' : 'Inactiva' }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right space-x-2">
                                    <a href="{{ route('admin.techniques.edit', $tech) }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-bold bg-indigo-50 px-3 py-1.5 rounded-xl">
                                        Editar
                                    </a>
                                    <form method="POST" action="{{ route('admin.techniques.destroy', $tech) }}" onsubmit="return confirm('¿Eliminar técnica?')" class="inline-block">
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
