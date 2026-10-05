@extends('layouts.app')

@section('title', 'Registro de Auditoría - Admin')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs">
            <div>
                <div class="flex items-center gap-2 text-xs text-slate-400 font-semibold mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600">&larr; Volver al Panel Admin</a>
                </div>
                <h1 class="text-2xl font-extrabold text-slate-900">Auditoría y Trazabilidad del Sistema</h1>
                <p class="text-xs text-slate-500 mt-1">Registro inmutable de todas las acciones administrativas realizadas sobre datos sensibles.</p>
            </div>

            <!-- Filtros de auditoría -->
            <form method="GET" action="{{ route('admin.logs.index') }}" class="flex items-center gap-2">
                <select name="module" class="p-2 border border-slate-200 rounded-xl text-xs text-slate-800">
                    <option value="">Todos los módulos</option>
                    <option value="users" {{ $module === 'users' ? 'selected' : '' }}>Usuarios</option>
                    <option value="techniques" {{ $module === 'techniques' ? 'selected' : '' }}>Técnicas</option>
                    <option value="questions" {{ $module === 'questions' ? 'selected' : '' }}>Preguntas</option>
                    <option value="routes" {{ $module === 'routes' ? 'selected' : '' }}>Rutas</option>
                    <option value="settings" {{ $module === 'settings' ? 'selected' : '' }}>Configuración</option>
                </select>

                <select name="action" class="p-2 border border-slate-200 rounded-xl text-xs text-slate-800">
                    <option value="">Todas las acciones</option>
                    <option value="create" {{ $action === 'create' ? 'selected' : '' }}>Crear</option>
                    <option value="update" {{ $action === 'update' ? 'selected' : '' }}>Actualizar</option>
                    <option value="delete" {{ $action === 'delete' ? 'selected' : '' }}>Eliminar</option>
                </select>

                <button type="submit" class="px-3 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-indigo-600 transition">
                    Filtrar
                </button>
            </form>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-bold">
                        <tr>
                            <th class="py-4 px-6">Fecha / Hora</th>
                            <th class="py-4 px-6">Administrador</th>
                            <th class="py-4 px-6">Acción</th>
                            <th class="py-4 px-6">Módulo</th>
                            <th class="py-4 px-6">Descripción del Evento</th>
                            <th class="py-4 px-6">Dirección IP</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($logs as $log)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-4 px-6 font-semibold text-slate-800 whitespace-nowrap text-xs">
                                    {{ $log->created_at->format('d/m/Y H:i:s') }}
                                </td>
                                <td class="py-4 px-6">
                                    <div class="font-bold text-slate-900">{{ $log->user ? $log->user->name : 'Sistema' }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $log->user ? $log->user->email : 'automático' }}</div>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="px-2.5 py-0.5 rounded-md text-[10px] font-black uppercase {{ $log->action === 'delete' ? 'bg-rose-100 text-rose-800' : ($log->action === 'create' ? 'bg-emerald-100 text-emerald-800' : 'bg-indigo-100 text-indigo-800') }}">
                                        {{ $log->action }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-xs font-bold text-slate-700 uppercase">
                                    {{ $log->module }}
                                </td>
                                <td class="py-4 px-6 text-xs text-slate-800 max-w-md">
                                    {{ $log->description }}
                                </td>
                                <td class="py-4 px-6 text-xs text-slate-400 font-mono">
                                    {{ $log->ip_address ?: '127.0.0.1' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-12 text-slate-400 text-xs">
                                    No se encontraron registros de auditoría para los criterios seleccionados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-6 border-t border-slate-100">
                {{ $logs->links() }}
            </div>
        </div>

    </div>
</div>
@endsection
