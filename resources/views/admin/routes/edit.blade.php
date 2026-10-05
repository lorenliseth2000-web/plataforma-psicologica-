@extends('layouts.app')

@section('title', 'Editar Ruta de Atención - Admin')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <a href="{{ route('admin.routes.index') }}" class="text-xs text-slate-400 font-bold hover:text-indigo-600">&larr; Volver al Listado</a>
                <h1 class="text-2xl font-extrabold text-slate-900 mt-1">Editar Ruta: {{ $route->name }}</h1>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.routes.update', $route) }}" class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-6">
            @csrf
            @method('PUT')

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nombre del Servicio / Línea</label>
                    <input type="text" name="name" value="{{ old('name', $route->name) }}" class="w-full p-3 rounded-2xl border border-slate-200 text-xs font-semibold" required>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Institución Responsable</label>
                    <input type="text" name="institution" value="{{ old('institution', $route->institution) }}" class="w-full p-3 rounded-2xl border border-slate-200 text-xs font-semibold" required>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Categoría</label>
                    <select name="category" class="w-full p-3 rounded-2xl border border-slate-200 text-xs font-semibold" required>
                        <option value="emergencia" {{ $route->category === 'emergencia' ? 'selected' : '' }}>Línea de Emergencia 24/7</option>
                        <option value="universitaria" {{ $route->category === 'universitaria' ? 'selected' : '' }}>Centro de Atención Psicológica Universitario (CAP)</option>
                        <option value="eps" {{ $route->category === 'eps' ? 'selected' : '' }}>Red de Salud / EPS</option>
                        <option value="publica" {{ $route->category === 'publica' ? 'selected' : '' }}>Recurso Abierto de Psicoeducación</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nivel de Riesgo Asociado</label>
                    <select name="risk_level" class="w-full p-3 rounded-2xl border border-slate-200 text-xs font-semibold" required>
                        <option value="todos" {{ $route->risk_level === 'todos' ? 'selected' : '' }}>Para Todos los Niveles</option>
                        <option value="bajo" {{ $route->risk_level === 'bajo' ? 'selected' : '' }}>Riesgo Bajo (Educación/Autocuidado)</option>
                        <option value="moderado" {{ $route->risk_level === 'moderado' ? 'selected' : '' }}>Riesgo Moderado (Cita Psicológica)</option>
                        <option value="alto" {{ $route->risk_level === 'alto' ? 'selected' : '' }}>Riesgo Alto (Atención Prioritaria/Urgencias)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Teléfono Principal</label>
                    <input type="text" name="phone" value="{{ old('phone', $route->phone) }}" class="w-full p-3 rounded-2xl border border-slate-200 text-xs font-semibold">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">WhatsApp de Orientación</label>
                    <input type="text" name="whatsapp" value="{{ old('whatsapp', $route->whatsapp) }}" class="w-full p-3 rounded-2xl border border-slate-200 text-xs font-semibold">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Correo Electrónico</label>
                    <input type="email" name="email" value="{{ old('email', $route->email) }}" class="w-full p-3 rounded-2xl border border-slate-200 text-xs font-semibold">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Sitio Web / Enlace</label>
                    <input type="url" name="website" value="{{ old('website', $route->website) }}" class="w-full p-3 rounded-2xl border border-slate-200 text-xs font-semibold">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Horario de Atención</label>
                    <input type="text" name="available_hours" value="{{ old('available_hours', $route->available_hours) }}" class="w-full p-3 rounded-2xl border border-slate-200 text-xs font-semibold" required>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Número de Orden</label>
                    <input type="number" name="order" value="{{ old('order', $route->order) }}" class="w-full p-3 rounded-2xl border border-slate-200 text-xs font-semibold" required>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Descripción del Servicio</label>
                <textarea name="description" rows="3" class="w-full p-3 rounded-2xl border border-slate-200 text-xs text-slate-800" required>{{ old('description', $route->description) }}</textarea>
            </div>

            <div class="flex items-center gap-6 pt-2 border-t border-slate-100">
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="is_emergency" name="is_emergency" value="1" {{ $route->is_emergency ? 'checked' : '' }} class="rounded-sm border-slate-300 text-rose-600">
                    <label for="is_emergency" class="text-xs font-bold text-rose-800">Marcar como servicio de emergencia crítico 24/7</label>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" id="active" name="active" value="1" {{ $route->active ? 'checked' : '' }} class="rounded-sm border-slate-300 text-indigo-600">
                    <label for="active" class="text-xs font-bold text-slate-700">Ruta activa</label>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.routes.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-700">Cancelar</a>
                <button type="submit" class="px-6 py-3 rounded-2xl bg-indigo-600 text-white font-bold text-xs hover:bg-indigo-700 transition">
                    Guardar Cambios
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
