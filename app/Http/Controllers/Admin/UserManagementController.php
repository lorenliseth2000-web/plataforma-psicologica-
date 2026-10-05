<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $role = $request->query('role');

        $query = User::withCount(['assessments', 'techniqueSessions', 'emotionalLogs']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('occupation', 'like', "%{$search}%");
            });
        }

        if ($role) {
            $query->where('role', $role);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(12);

        return view('admin.users.index', compact('users', 'search', 'role'));
    }

    public function updateRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => ['required', Rule::in(['user', 'admin'])],
        ]);

        $oldRole = $user->role;
        $user->update(['role' => $validated['role']]);

        ActivityLog::log(
            action: 'update',
            module: 'users',
            description: "Cambio de rol para el usuario #{$user->id} ({$user->name}) de '{$oldRole}' a '{$validated['role']}'.",
            details: ['user_id' => $user->id, 'old_role' => $oldRole, 'new_role' => $validated['role']]
        );

        return back()->with('status', "Rol de {$user->name} actualizado correctamente a {$validated['role']}.");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'No puedes eliminar tu propia cuenta de administrador.']);
        }

        $userName = $user->name;
        $userId = $user->id;
        $user->delete();

        ActivityLog::log(
            action: 'delete',
            module: 'users',
            description: "Eliminación de usuario #{$userId} ({$userName}).",
            details: ['user_id' => $userId, 'name' => $userName]
        );

        return back()->with('status', "Usuario {$userName} eliminado correctamente.");
    }
}
