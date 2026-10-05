@extends('layouts.app')

@section('title', 'Gestión de Usuarios - Admin')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs">
            <div>
                <div class="flex items-center gap-2 text-xs text-slate-400 font-semibold mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600">&larr; Volver al Panel Admin</a>
                </div>
                <h1 class="text-2xl font-extrabold text-slate-900">Gestión de Usuarios</h1>
                <p class="text-xs text-slate-500 mt-1">Control de roles, actividad y cuentas de usuarios en la plataforma.</p>
            </div>

            <!-- Buscador y filtro -->
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex items-center gap-2">
                <input type="text" name="search" value="{{ $search }}" placeholder="Buscar por nombre o email..." class="p-2 px-3 border border-slate-200 rounded-xl text-xs text-slate-800 w-56">
                <select name="role" class="p-2 border border-slate-200 rounded-xl text-xs text-slate-800">
                    <option value="">Todos los roles</option>
                    <option value="user" {{ $role === 'user' ? 'selected' : '' }}>Usuario</option>
                    <option value="admin" {{ $role === 'admin' ? 'selected' : '' }}>Administrador</option>
                </select>
                <button type="submit" class="px-3 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-indigo-600 transition">
                    Buscar
                </button>
            </form>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-bold">
                        <tr>
                            <th class="py-4 px-6">Usuario</th>
                            <th class="py-4 px-6">Ocupación</th>
                            <th class="py-4 px-6">Rol Actual</th>
                            <th class="py-4 px-6 text-center">Tamizajes</th>
                            <th class="py-4 px-6 text-center">Sesiones</th>
                            <th class="py-4 px-6 text-center">Diario</th>
                            <th class="py-4 px-6 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($users as $u)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-4 px-6">
                                    <div class="font-bold text-slate-900">{{ $u->name }}</div>
                                    <div class="text-xs text-slate-400">{{ $u->email }}</div>
                                </td>
                                <td class="py-4 px-6 text-xs text-slate-700">
                                    {{ $u->occupation ?: 'No especificada' }}
                                </td>
                                <td class="py-4 px-6">
                                    <form method="POST" action="{{ route('admin.users.role', $u) }}" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <select name="role" onchange="this.form.submit()" class="p-1 px-2.5 rounded-lg border border-slate-200 text-xs font-bold {{ $u->role === 'admin' ? 'bg-purple-50 text-purple-800 border-purple-200' : 'bg-slate-50 text-slate-700' }}">
                                            <option value="user" {{ $u->role === 'user' ? 'selected' : '' }}>Usuario Regular</option>
                                            <option value="admin" {{ $u->role === 'admin' ? 'selected' : '' }}>Administrador</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="py-4 px-6 text-center font-bold text-indigo-600">
                                    {{ $u->assessments_count }}
                                </td>
                                <td class="py-4 px-6 text-center font-bold text-teal-600">
                                    {{ $u->technique_sessions_count }}
                                </td>
                                <td class="py-4 px-6 text-center font-bold text-purple-600">
                                    {{ $u->emotional_logs_count }}
                                </td>
                                <td class="py-4 px-6 text-right">
                                    @if($u->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.destroy', $u) }}" onsubmit="return confirm('¿Seguro(a) de eliminar este usuario?')" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-rose-600 hover:text-rose-800 font-bold bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-xl transition">
                                                Eliminar
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-slate-400 italic">Tu cuenta</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-6 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        </div>

    </div>
</div>
@endsection
