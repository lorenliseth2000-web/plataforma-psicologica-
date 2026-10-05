@extends('layouts.app')

@section('title', 'Gestión de Rutas de Atención - Admin')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <!-- Configuración Rápida de Teléfonos de Emergencia Globales -->
        <div class="bg-gradient-to-r from-rose-900 to-slate-900 text-white p-6 sm:p-8 rounded-3xl shadow-md space-y-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-rose-300 bg-rose-500/20 px-3 py-1 rounded-full">
                    Ajustes de Emergencia del Sistema
                </span>
                <h2 class="text-xl font-extrabold mt-2">Teléfonos de Emergencia Configurables</h2>
                <p class="text-xs text-rose-200 mt-1">Estos números se muestran directamente a los usuarios en caso de nivel de riesgo alto.</p>
            </div>

            <form method="POST" action="{{ route('admin.routes.settings') }}" class="grid sm:grid-cols-3 gap-4 pt-2">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-rose-200 mb-1">Línea Principal (Local / Distrital)</label>
                    <input type="text" name="emergency_primary_phone" value="{{ $emergencyPrimary }}" class="w-full p-2.5 rounded-xl border border-rose-700 bg-white/10 text-white text-xs font-bold focus:bg-white focus:text-slate-900" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-rose-200 mb-1">Línea Nacional de Orientación</label>
                    <input type="text" name="emergency_national_phone" value="{{ $emergencyNational }}" class="w-full p-2.5 rounded-xl border border-rose-700 bg-white/10 text-white text-xs font-bold focus:bg-white focus:text-slate-900" required>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full py-2.5 bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs rounded-xl transition shadow-xs">
                        Guardar Teléfonos
                    </button>
                </div>
            </form>
        </div>

        <!-- Directorio de Rutas -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs">
            <div>
                <div class="flex items-center gap-2 text-xs text-slate-400 font-semibold mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600">&larr; Volver al Panel Admin</a>
                </div>
                <h1 class="text-2xl font-extrabold text-slate-900">Directorio de Rutas de Atención</h1>
                <p class="text-xs text-slate-500 mt-1">Instituciones, servicios CAP universitarios y entidades de apoyo.</p>
            </div>

            <a href="{{ route('admin.routes.create') }}" class="px-5 py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-xs hover:bg-indigo-700 transition shadow-md shadow-indigo-100 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Nueva Ruta
            </a>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-bold">
                        <tr>
                            <th class="py-4 px-6">Orden</th>
                            <th class="py-4 px-6">Entidad / Nombre</th>
                            <th class="py-4 px-6">Categoría</th>
                            <th class="py-4 px-6">Teléfono / Contacto</th>
                            <th class="py-4 px-6">Nivel Asociado</th>
                            <th class="py-4 px-6">Emergencia</th>
                            <th class="py-4 px-6">Estado</th>
                            <th class="py-4 px-6 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($routes as $route)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-4 px-6 font-bold text-slate-400">
                                    #{{ $route->order }}
                                </td>
                                <td class="py-4 px-6">
                                    <div class="font-bold text-slate-900">{{ $route->name }}</div>
                                    <div class="text-xs text-slate-400">{{ $route->institution }}</div>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase bg-slate-100 text-slate-800">
                                        {{ $route->category }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-xs font-semibold text-slate-800">
                                    {{ $route->phone ?: ($route->whatsapp ?: 'Web/Presencial') }}
                                </td>
                                <td class="py-4 px-6">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $route->risk_level === 'alto' ? 'bg-rose-100 text-rose-800' : ($route->risk_level === 'moderado' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-700') }}">
                                        {{ $route->risk_level }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    @if($route->is_emergency)
                                        <span class="text-rose-600 font-extrabold text-xs">Sí (24/7)</span>
                                    @else
                                        <span class="text-slate-400 text-xs">No</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $route->active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $route->active ? 'Activa' : 'Inactiva' }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right space-x-2">
                                    <a href="{{ route('admin.routes.edit', $route) }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-bold bg-indigo-50 px-3 py-1.5 rounded-xl">
                                        Editar
                                    </a>
                                    <form method="POST" action="{{ route('admin.routes.destroy', $route) }}" onsubmit="return confirm('¿Eliminar ruta?')" class="inline-block">
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
