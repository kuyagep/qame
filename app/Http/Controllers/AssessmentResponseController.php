<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\AssessmentAnswer;
use App\Models\AssessmentResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AssessmentResponseController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
            'answers' => 'required|array',
        ]);

        $assessment = Assessment::with('questions')->findOrFail($request->assessment_id);
        $user = Auth::user();

        // Calculate score inside a DB transaction
        $response = DB::transaction(function () use ($assessment, $user, $request) {
            $totalEarnedPoints = 0;
            $maxPossiblePoints = 0;

            $attempt = AssessmentResponse::create([
                'assessment_id' => $assessment->id,
                'user_id' => $user->id,
            ]);

            foreach ($assessment->questions as $question) {
                $userAnswer = $request->answers[$question->id] ?? null;
                $isCorrect = (trim(strtolower($userAnswer)) === trim(strtolower($question->correct_answer)));
                $points = $isCorrect ? $question->points : 0;

                $totalEarnedPoints += $points;
                $maxPossiblePoints += $question->points;

                AssessmentAnswer::create([
                    'assessment_response_id' => $attempt->id,
                    'question_id' => $question->id,
                    'user_answer' => $userAnswer ?? '',
                    'is_correct' => $isCorrect,
                    'points_earned' => $points,
                ]);
            }

            $percentage = $maxPossiblePoints > 0 ? ($totalEarnedPoints / $maxPossiblePoints) * 100 : 0;
            $isPassed = $percentage >= $assessment->passing_score;

            $attempt->update([
                'total_score' => $totalEarnedPoints,
                'percentage' => round($percentage, 2),
                'is_passed' => $isPassed,
                'submitted_at' => now(),
            ]);

            return $attempt;
        });

        return response()->json([
            'message' => 'Assessment submitted successfully.',
            'total_score' => $response->total_score,
            'percentage' => $response->percentage,
            'is_passed' => $response->is_passed,
            'redirect_url' => route('trainings.index'),
        ]);
    }
}
