<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Technique;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TechniqueManagementController extends Controller
{
    public function index()
    {
        $techniques = Technique::withCount('sessions')->orderBy('category')->orderBy('order')->get();
        return view('admin.techniques.index', compact('techniques'));
    }

    public function create()
    {
        return view('admin.techniques.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', Rule::in(['ansiedad', 'estres'])],
            'description' => ['required', 'string'],
            'benefits' => ['required', 'string'],
            'instructions' => ['required', 'string'], // Entrado como texto multilínea
            'animation_type' => ['required', 'string'],
            'duration_minutes' => ['required', 'integer', 'min:1', 'max:60'],
            'order' => ['nullable', 'integer'],
            'active' => ['nullable', 'boolean'],
        ]);

        // Convertir instrucciones multilínea a array
        $instructionSteps = array_values(array_filter(
            array_map('trim', explode("\n", $validated['instructions']))
        ));

        $slug = Str::slug($validated['name']);
        // Asegurar unicidad de slug
        $baseSlug = $slug;
        $counter = 1;
        while (Technique::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        $technique = Technique::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'category' => $validated['category'],
            'description' => $validated['description'],
            'benefits' => $validated['benefits'],
            'instructions' => $instructionSteps,
            'animation_type' => $validated['animation_type'],
            'duration_minutes' => $validated['duration_minutes'],
            'order' => $validated['order'] ?? 0,
            'active' => $request->has('active'),
        ]);

        ActivityLog::log(
            action: 'create',
            module: 'techniques',
            description: "Creación de nueva técnica terapéutica: '{$technique->name}' ({$technique->category}).",
            details: ['technique_id' => $technique->id, 'name' => $technique->name]
        );

        return redirect()->route('admin.techniques.index')
            ->with('status', "Técnica '{$technique->name}' creada exitosamente.");
    }

    public function edit(Technique $technique)
    {
        return view('admin.techniques.edit', compact('technique'));
    }

    public function update(Request $request, Technique $technique)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', Rule::in(['ansiedad', 'estres'])],
            'description' => ['required', 'string'],
            'benefits' => ['required', 'string'],
            'instructions' => ['required', 'string'],
            'animation_type' => ['required', 'string'],
            'duration_minutes' => ['required', 'integer', 'min:1', 'max:60'],
            'order' => ['nullable', 'integer'],
            'active' => ['nullable', 'boolean'],
        ]);

        $instructionSteps = array_values(array_filter(
            array_map('trim', explode("\n", $validated['instructions']))
        ));

        $technique->update([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'benefits' => $validated['benefits'],
            'instructions' => $instructionSteps,
            'animation_type' => $validated['animation_type'],
            'duration_minutes' => $validated['duration_minutes'],
            'order' => $validated['order'] ?? 0,
            'active' => $request->has('active'),
        ]);

        ActivityLog::log(
            action: 'update',
            module: 'techniques',
            description: "Modificación de técnica: '{$technique->name}'.",
            details: ['technique_id' => $technique->id, 'name' => $technique->name]
        );

        return redirect()->route('admin.techniques.index')
            ->with('status', "Técnica '{$technique->name}' actualizada correctamente.");
    }

    public function destroy(Technique $technique)
    {
        $name = $technique->name;
        $id = $technique->id;
        $technique->delete();

        ActivityLog::log(
            action: 'delete',
            module: 'techniques',
            description: "Eliminación de técnica: '{$name}'.",
            details: ['technique_id' => $id, 'name' => $name]
        );

        return redirect()->route('admin.techniques.index')
            ->with('status', "Técnica '{$name}' eliminada.");
    }
}
