<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\AssessmentResponse;
use App\Models\AssessmentSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AssessmentResponseController extends Controller
{
    /**
     * Display the assessment form.
     */
    public function show(Assessment $assessment)
    {
        $assessment->load(['training', 'questions']);

        // Check for existing submission to prevent re-taking
        $existingSubmission = AssessmentResponse::where('assessment_id', $assessment->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingSubmission) {
            return redirect()->route('participant.dashboard')
                ->with('info', 'You have already completed this assessment.');
        }

        return view('pages.assessments.assessment', compact('assessment'));
    }

    /**
     * Store submitted assessment responses and calculate scores.
     */
    public function store(Request $request)
    {
        $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
            'answers'       => 'required|array',
            'answers.*'     => 'required',
        ], [
            'answers.*.required' => 'Please answer all questions before submitting.'
        ]);

        $user = Auth::user();
        $assessment = Assessment::with('questions')->findOrFail($request->assessment_id);

        // Prevent duplicate submissions
        $alreadySubmitted = AssessmentResponse::where('assessment_id', $assessment->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($alreadySubmitted) {
            return response()->json([
                'message' => 'You have already submitted this assessment.'
            ], 422);
        }

        $submittedAnswers = $request->input('answers', []);
        $totalEarnedPoints = 0;
        $maxPossiblePoints = 0;

        DB::beginTransaction();
        try {
            // 1. Create overarching submission record
            $submission = AssessmentResponse::create([
                'assessment_id' => $assessment->id,
                'user_id'       => $user->id,
                'total_score'   => 0,
                'max_score'     => 0,
                'percentage'    => 0,
                'is_passed'     => false,
            ]);

            // 2. Loop through questions to grade & log individual answers
            foreach ($assessment->questions as $question) {
                $maxPossiblePoints += $question->points;
                $userAnswer = $submittedAnswers[$question->id] ?? null;
                $isCorrect = false;
                $earnedPoints = 0;

                // Auto-grade objective question types
                if (in_array($question->type, ['multiple_choice', 'true_false'])) {
                    if (strcasecmp(trim($userAnswer), trim($question->correct_answer)) === 0) {
                        $isCorrect = true;
                        $earnedPoints = $question->points;
                    }
                }

                $totalEarnedPoints += $earnedPoints;

                // Record detailed response item
                // AssessmentResponse::create([
                //     'assessment_id' => $submission->id,
                //     'question_id'              => $question->id,
                //     'user_answer'              => is_array($userAnswer) ? json_encode($userAnswer) : $userAnswer,
                //     'is_correct'               => $isCorrect,
                //     'points_awarded'           => $earnedPoints,
                // ]);
            }

            // 3. Calculate final percentages and pass status
            $percentage = $maxPossiblePoints > 0 ? round(($totalEarnedPoints / $maxPossiblePoints) * 100, 2) : 0;
            $passingScore = $assessment->passing_score ?? 70; // Defaults to 70% if null
            $isPassed = $percentage >= $passingScore;

            // 4. Update the parent submission record
            $submission->update([
                'total_score' => $totalEarnedPoints,
                'max_score'   => $maxPossiblePoints,
                'percentage'  => $percentage,
                'is_passed'   => $isPassed,
            ]);

            DB::commit();

            return response()->json([
                'status'       => 'success',
                'is_passed'    => $isPassed,
                'total_score'  => "{$totalEarnedPoints} / {$maxPossiblePoints}",
                'percentage'   => $percentage,
                'redirect_url' => route('participant.dashboard'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'An error occurred while saving your responses: ' . $e->getMessage()
            ], 500);
        }
    }

    public function checkStatus($assessmentId)
    {
        $submission = AssessmentResponse::where('assessment_id', $assessmentId)
            ->where('user_id', Auth::id())
            ->first();

        if ($submission) {
            return response()->json([
                'completed' => true,
                'score'     => $submission->total_score,
                'max'       => $submission->max_score,
                'percentage' => $submission->percentage,
                'passed'    => $submission->is_passed,
            ]);
        }

        return response()->json([
            'completed' => false
        ]);
    }
}
