@extends('layouts.app')

@section('title', 'Editar Pregunta - Admin')

@section('content')
<div class="py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <a href="{{ route('admin.questions.index') }}" class="text-xs text-slate-400 font-bold hover:text-indigo-600">&larr; Volver al Listado</a>
                <h1 class="text-2xl font-extrabold text-slate-900 mt-1">Editar Pregunta de Tamizaje #{{ $question->id }}</h1>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.questions.update', $question) }}" class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-6">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Bloque / Categoría</label>
                    <select name="type" class="w-full p-3 rounded-2xl border border-slate-200 text-xs font-semibold" required>
                        <option value="ansiedad" {{ $question->type === 'ansiedad' ? 'selected' : '' }}>Ansiedad (Inquietud, nerviosismo, tensión)</option>
                        <option value="estres" {{ $question->type === 'estres' ? 'selected' : '' }}>Estrés (Sobrecarga, fatiga, concentración, sueño)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Texto de la Pregunta</label>
                    <textarea name="text" rows="3" class="w-full p-3 rounded-2xl border border-slate-200 text-xs text-slate-800" required>{{ old('text', $question->text) }}</textarea>
                </div>

                <div class="grid sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Foco del Síntoma</label>
                        <input type="text" name="symptom_focus" value="{{ old('symptom_focus', $question->symptom_focus) }}" class="w-full p-3 rounded-2xl border border-slate-200 text-xs font-semibold">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Ponderación / Peso</label>
                        <input type="number" name="weight" value="{{ old('weight', $question->weight) }}" min="1" max="5" class="w-full p-3 rounded-2xl border border-slate-200 text-xs font-semibold" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Número de Orden</label>
                        <input type="number" name="order" value="{{ old('order', $question->order) }}" class="w-full p-3 rounded-2xl border border-slate-200 text-xs font-semibold" required>
                    </div>
                </div>

                <div class="flex items-center gap-2 pt-4">
                    <input type="checkbox" id="active" name="active" value="1" {{ $question->active ? 'checked' : '' }} class="rounded-sm border-slate-300 text-indigo-600">
                    <label for="active" class="text-xs font-bold text-slate-700">Pregunta activa en el cuestionario de usuarios</label>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.questions.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-700">Cancelar</a>
                <button type="submit" class="px-6 py-3 rounded-2xl bg-indigo-600 text-white font-bold text-xs hover:bg-indigo-700 transition">
                    Guardar Cambios
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
