<?php

namespace App\Http\Controllers;

use App\Models\UserReminder;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    public function index(Request $request)
    {
        $reminders = $request->user()->reminders()->orderBy('reminder_time')->get();
        $typeLabels = UserReminder::typeLabels();
        $frequencyLabels = UserReminder::frequencyLabels();

        return view('reminders.index', compact('reminders', 'typeLabels', 'frequencyLabels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type'          => ['required', 'in:respiracion,relajacion,registro_emocional,pausa,progreso'],
            'label'         => ['required', 'string', 'max:120'],
            'reminder_time' => ['required', 'date_format:H:i'],
            'frequency'     => ['required', 'in:daily,weekdays,weekends,custom'],
            'days_of_week'  => ['nullable', 'array'],
            'days_of_week.*'=> ['integer', 'between:0,6'],
        ], [
            'type.required' => 'Selecciona el tipo de recordatorio.',
            'reminder_time.required' => 'Indica la hora del recordatorio.',
            'reminder_time.date_format' => 'La hora debe tener formato HH:MM.',
        ]);

        // Limitar a 5 recordatorios activos por usuario
        $activeCount = $request->user()->reminders()->where('active', true)->count();
        if ($activeCount >= 5) {
            return back()->withErrors(['limit' => 'Puedes tener máximo 5 recordatorios activos.']);
        }

        $request->user()->reminders()->create([
            'type'          => $validated['type'],
            'label'         => $validated['label'],
            'reminder_time' => $validated['reminder_time'],
            'frequency'     => $validated['frequency'],
            'days_of_week'  => $validated['frequency'] === 'custom' ? ($validated['days_of_week'] ?? []) : null,
            'active'        => true,
        ]);

        return back()->with('status', 'Recordatorio creado correctamente.');
    }

    public function toggle(Request $request, UserReminder $reminder)
    {
        $this->authorize('update', $reminder);
        $reminder->update(['active' => !$reminder->active]);

        return back()->with('status', $reminder->active ? 'Recordatorio activado.' : 'Recordatorio pausado.');
    }

    public function destroy(UserReminder $reminder)
    {
        $this->authorize('delete', $reminder);
        $reminder->delete();

        return back()->with('status', 'Recordatorio eliminado.');
    }
}
