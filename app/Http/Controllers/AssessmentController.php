<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Question;
use App\Models\TrainingSession;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    public function index(TrainingSession $session)
    {
        $tests = $session->assessments()->with('questions')->get();
        return response()->json($tests);
    }

    public function store(Request $request, TrainingSession $session)
    {
        $validated = $request->validate([
            'type'          => 'required|in:pretest,posttest',
            'title'         => 'required|string|max:255',
            'passing_score' => 'required|integer|min:1',
        ]);

        $test = $session->assessments()->updateOrCreate(
            ['type' => $validated['type']],
            $validated
        );

        return response()->json([
            'success' => true,
            'message' => ucfirst($validated['type']) . ' configured successfully.',
            'data'    => $test
        ]);
    }

    public function addQuestion(Request $request, Assessment $assessment)
    {
        $validated = $request->validate([
            'question_text'  => 'required|string',
            'question_type'  => 'required|in:multiple_choice,true_false',
            'options'        => 'required|array|min:2',
            'correct_answer' => 'required|string',
            'points'         => 'nullable|integer|min:1',
        ]);

        $question = $assessment->questions()->create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Question added successfully.',
            'data'    => $question
        ], 201);
    }

    public function deleteQuestion(Question $question)
    {
        $question->delete();

        return response()->json([
            'success' => true,
            'message' => 'Question deleted successfully.'
        ]);
    }
}
