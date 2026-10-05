<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class QuestionManagementController extends Controller
{
    public function index()
    {
        $questions = Question::withCount('answers')->orderBy('order', 'asc')->get();
        return view('admin.questions.index', compact('questions'));
    }

    public function create()
    {
        $nextOrder = (Question::max('order') ?? 0) + 1;
        return view('admin.questions.create', compact('nextOrder'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', Rule::in(['ansiedad', 'estres'])],
            'text' => ['required', 'string'],
            'symptom_focus' => ['nullable', 'string', 'max:255'],
            'weight' => ['required', 'integer', 'min:1', 'max:5'],
            'order' => ['required', 'integer'],
            'active' => ['nullable', 'boolean'],
        ]);

        $question = Question::create([
            'type' => $validated['type'],
            'text' => $validated['text'],
            'symptom_focus' => $validated['symptom_focus'],
            'weight' => $validated['weight'],
            'order' => $validated['order'],
            'active' => $request->has('active'),
        ]);

        ActivityLog::log(
            action: 'create',
            module: 'questions',
            description: "Creación de pregunta de tamizaje: #{$question->id} ({$question->type}).",
            details: ['question_id' => $question->id, 'text' => $question->text]
        );

        return redirect()->route('admin.questions.index')
            ->with('status', 'Pregunta de evaluación agregada exitosamente.');
    }

    public function edit(Question $question)
    {
        return view('admin.questions.edit', compact('question'));
    }

    public function update(Request $request, Question $question)
    {
        $validated = $request->validate([
            'type' => ['required', Rule::in(['ansiedad', 'estres'])],
            'text' => ['required', 'string'],
            'symptom_focus' => ['nullable', 'string', 'max:255'],
            'weight' => ['required', 'integer', 'min:1', 'max:5'],
            'order' => ['required', 'integer'],
            'active' => ['nullable', 'boolean'],
        ]);

        $question->update([
            'type' => $validated['type'],
            'text' => $validated['text'],
            'symptom_focus' => $validated['symptom_focus'],
            'weight' => $validated['weight'],
            'order' => $validated['order'],
            'active' => $request->has('active'),
        ]);

        ActivityLog::log(
            action: 'update',
            module: 'questions',
            description: "Modificación de pregunta de tamizaje #{$question->id}.",
            details: ['question_id' => $question->id]
        );

        return redirect()->route('admin.questions.index')
            ->with('status', 'Pregunta actualizada correctamente.');
    }

    public function destroy(Question $question)
    {
        $id = $question->id;
        $question->delete();

        ActivityLog::log(
            action: 'delete',
            module: 'questions',
            description: "Eliminación de pregunta de tamizaje #{$id}.",
            details: ['question_id' => $id]
        );

        return redirect()->route('admin.questions.index')
            ->with('status', "Pregunta #{$id} eliminada.");
    }
}
