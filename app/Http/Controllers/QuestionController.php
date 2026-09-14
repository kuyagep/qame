<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Assessment $assessment)
    {
        return response()->json($assessment->questions);
    }

    public function store(Request $request, Assessment $assessment)
    {
        $validated = $request->validate([
            'question_text'  => 'required|string',
            'type'           => 'required|in:multiple_choice,true_false,open_text',
            'options'        => 'nullable|array',
            'options.*'      => 'nullable|string',
            'correct_answer' => 'nullable|string',
            'points'         => 'nullable|integer|min:1',
        ]);

        $question = $assessment->questions()->create([
            'question_text'  => $validated['question_text'],
            'type'           => $validated['type'],
            'options'        => $validated['type'] === 'multiple_choice' ? array_values(array_filter($validated['options'] ?? [])) : null,
            'correct_answer' => $validated['correct_answer'] ?? null,
            'points'         => $validated['points'] ?? 1,
        ]);

        return response()->json([
            'message'  => 'Question added successfully.',
            'question' => $question,
        ]);
    }

    public function destroy(Question $question)
    {
        $question->delete();

        return response()->json(['message' => 'Question deleted successfully.']);
    }
}
