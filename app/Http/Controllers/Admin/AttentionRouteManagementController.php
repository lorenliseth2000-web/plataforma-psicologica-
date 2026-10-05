<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\AttentionRoute;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AttentionRouteManagementController extends Controller
{
    public function index()
    {
        $routes = AttentionRoute::orderBy('is_emergency', 'desc')->orderBy('order', 'asc')->get();
        $emergencyPrimary = SystemSetting::get('emergency_primary_phone', '106');
        $emergencyNational = SystemSetting::get('emergency_national_phone', '192');

        return view('admin.routes.index', compact('routes', 'emergencyPrimary', 'emergencyNational'));
    }

    public function create()
    {
        $nextOrder = (AttentionRoute::max('order') ?? 0) + 1;
        return view('admin.routes.create', compact('nextOrder'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'institution' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string'],
            'phone' => ['nullable', 'string', 'max:255'],
            'whatsapp' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'available_hours' => ['required', 'string', 'max:255'],
            'risk_level' => ['required', Rule::in(['bajo', 'moderado', 'alto', 'todos'])],
            'description' => ['required', 'string'],
            'is_emergency' => ['nullable', 'boolean'],
            'active' => ['nullable', 'boolean'],
            'order' => ['required', 'integer'],
        ]);

        $route = AttentionRoute::create([
            'name' => $validated['name'],
            'institution' => $validated['institution'],
            'category' => $validated['category'],
            'phone' => $validated['phone'],
            'whatsapp' => $validated['whatsapp'],
            'email' => $validated['email'],
            'website' => $validated['website'],
            'available_hours' => $validated['available_hours'],
            'risk_level' => $validated['risk_level'],
            'description' => $validated['description'],
            'is_emergency' => $request->has('is_emergency'),
            'active' => $request->has('active'),
            'order' => $validated['order'],
        ]);

        ActivityLog::log(
            action: 'create',
            module: 'routes',
            description: "Creación de ruta de atención: '{$route->name}' ({$route->institution}).",
            details: ['route_id' => $route->id, 'name' => $route->name]
        );

        return redirect()->route('admin.routes.index')
            ->with('status', "Ruta '{$route->name}' creada exitosamente.");
    }

    public function edit(AttentionRoute $route)
    {
        return view('admin.routes.edit', compact('route'));
    }

    public function update(Request $request, AttentionRoute $route)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'institution' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string'],
            'phone' => ['nullable', 'string', 'max:255'],
            'whatsapp' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'available_hours' => ['required', 'string', 'max:255'],
            'risk_level' => ['required', Rule::in(['bajo', 'moderado', 'alto', 'todos'])],
            'description' => ['required', 'string'],
            'is_emergency' => ['nullable', 'boolean'],
            'active' => ['nullable', 'boolean'],
            'order' => ['required', 'integer'],
        ]);

        $route->update([
            'name' => $validated['name'],
            'institution' => $validated['institution'],
            'category' => $validated['category'],
            'phone' => $validated['phone'],
            'whatsapp' => $validated['whatsapp'],
            'email' => $validated['email'],
            'website' => $validated['website'],
            'available_hours' => $validated['available_hours'],
            'risk_level' => $validated['risk_level'],
            'description' => $validated['description'],
            'is_emergency' => $request->has('is_emergency'),
            'active' => $request->has('active'),
            'order' => $validated['order'],
        ]);

        ActivityLog::log(
            action: 'update',
            module: 'routes',
            description: "Actualización de ruta de atención: '{$route->name}'.",
            details: ['route_id' => $route->id, 'name' => $route->name]
        );

        return redirect()->route('admin.routes.index')
            ->with('status', "Ruta '{$route->name}' actualizada correctamente.");
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'emergency_primary_phone' => ['required', 'string', 'max:50'],
            'emergency_national_phone' => ['required', 'string', 'max:50'],
        ]);

        SystemSetting::set('emergency_primary_phone', $validated['emergency_primary_phone'], 'emergencia');
        SystemSetting::set('emergency_national_phone', $validated['emergency_national_phone'], 'emergencia');

        ActivityLog::log(
            action: 'update',
            module: 'settings',
            description: 'Actualización de teléfonos de emergencia del sistema.',
            details: $validated
        );

        return back()->with('status', 'Teléfonos de emergencia actualizados correctamente.');
    }

    public function destroy(AttentionRoute $route)
    {
        $name = $route->name;
        $id = $route->id;
        $route->delete();

        ActivityLog::log(
            action: 'delete',
            module: 'routes',
            description: "Eliminación de ruta de atención '{$name}'.",
            details: ['route_id' => $id, 'name' => $name]
        );

        return redirect()->route('admin.routes.index')
            ->with('status', "Ruta '{$name}' eliminada.");
    }
}
