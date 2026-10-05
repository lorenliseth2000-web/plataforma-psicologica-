@extends('layouts.app')

@section('title', 'Editar Técnica - Admin')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <a href="{{ route('admin.techniques.index') }}" class="text-xs text-slate-400 font-bold hover:text-indigo-600">&larr; Volver al Listado</a>
                <h1 class="text-2xl font-extrabold text-slate-900 mt-1">Editar Técnica: {{ $technique->name }}</h1>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.techniques.update', $technique) }}" class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-6">
            @csrf
            @method('PUT')

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nombre de la Técnica</label>
                    <input type="text" name="name" value="{{ old('name', $technique->name) }}" class="w-full p-3 rounded-2xl border border-slate-200 text-xs font-semibold" required>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Categoría</label>
                    <select name="category" class="w-full p-3 rounded-2xl border border-slate-200 text-xs font-semibold" required>
                        <option value="ansiedad" {{ $technique->category === 'ansiedad' ? 'selected' : '' }}>Para Ansiedad</option>
                        <option value="estres" {{ $technique->category === 'estres' ? 'selected' : '' }}>Para Estrés</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Tipo de Animación Visual</label>
                    <select name="animation_type" class="w-full p-3 rounded-2xl border border-slate-200 text-xs font-semibold" required>
                        <option value="breathing" {{ $technique->animation_type === 'breathing' ? 'selected' : '' }}>Respiración Guiada (Pacer expand/contract)</option>
                        <option value="grounding" {{ $technique->animation_type === 'grounding' ? 'selected' : '' }}>Anclaje Sensorial (Grounding)</option>
                        <option value="muscle_relaxation" {{ $technique->animation_type === 'muscle_relaxation' ? 'selected' : '' }}>Relajación Muscular</option>
                        <option value="visualization" {{ $technique->animation_type === 'visualization' ? 'selected' : '' }}>Visualización / Imaginería</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Duración Estimada (Minutos)</label>
                    <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $technique->duration_minutes) }}" min="1" max="60" class="w-full p-3 rounded-2xl border border-slate-200 text-xs font-semibold" required>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Orden en el Catálogo</label>
                    <input type="number" name="order" value="{{ old('order', $technique->order) }}" class="w-full p-3 rounded-2xl border border-slate-200 text-xs font-semibold">
                </div>

                <div class="flex items-center gap-2 pt-6">
                    <input type="checkbox" id="active" name="active" value="1" {{ $technique->active ? 'checked' : '' }} class="rounded-sm border-slate-300 text-indigo-600">
                    <label for="active" class="text-xs font-bold text-slate-700">Técnica activa y visible para usuarios</label>
                </div>
            </div>

            <div class="space-y-4 pt-4 border-t border-slate-100">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Descripción Breve</label>
                    <textarea name="description" rows="2" class="w-full p-3 rounded-2xl border border-slate-200 text-xs text-slate-800" required>{{ old('description', $technique->description) }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Beneficios Clínicos y Fisiológicos</label>
                    <textarea name="benefits" rows="3" class="w-full p-3 rounded-2xl border border-slate-200 text-xs text-slate-800" required>{{ old('benefits', $technique->benefits) }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Instrucciones Paso a Paso (Un paso por línea)</label>
                    <textarea name="instructions" rows="6" class="w-full p-3 rounded-2xl border border-slate-200 text-xs text-slate-800" required>{{ old('instructions', is_array($technique->instructions) ? implode("\n", $technique->instructions) : $technique->instructions) }}</textarea>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.techniques.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-700">Cancelar</a>
                <button type="submit" class="px-6 py-3 rounded-2xl bg-indigo-600 text-white font-bold text-xs hover:bg-indigo-700 transition">
                    Guardar Cambios
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
