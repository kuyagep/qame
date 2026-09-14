<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Question;
use App\Models\Training;
use App\Models\TrainingSession;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    public function index(TrainingSession $session)
    {
        $tests = $session->assessments()->with('questions')->get();
        return response()->json($tests);
    }

    public function store(Request $request, Training $training)
    {
        $validated = $request->validate([
            'type'          => 'required|in:pretest,posttest',
            'title'         => 'required|string|max:255',
            'passing_score' => 'required|integer|min:0|max:100',
        ]);

        $assessment = Assessment::updateOrCreate(
            [
                'training_id' => $training->id,
                'type'        => $validated['type'],
            ],
            [
                'title'         => $validated['title'],
                'passing_score' => $validated['passing_score'],
            ]
        );

        return response()->json([
            'message' => 'Assessment configuration saved successfully.',
            'data'    => $assessment,
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
