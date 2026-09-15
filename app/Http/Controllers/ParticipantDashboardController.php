<?php

namespace App\Http\Controllers;

use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ParticipantDashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.participant');
    }

    public function getTrainingsData()
    {
        $user = Auth::user();
        $userId = $user ? $user->id : null;
        $today = Carbon::today();

        $trainings = Training::with(['assessments'])
            ->withCount('participants')
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($training) use ($userId, $today) {

                $isJoined = false;
                if ($userId) {
                    $isJoined = DB::table('training_user')
                        ->where('training_id', $training->id)
                        ->where('user_id', $userId)
                        ->exists();
                }

                // Find Pretest & Posttest records
                $pretest = $training->assessments ? $training->assessments->first(function ($item) {
                    return in_array(strtolower($item->type), ['pretest', 'pre-test', 'pre_test']);
                }) : null;

                $posttest = $training->assessments ? $training->assessments->first(function ($item) {
                    return in_array(strtolower($item->type), ['posttest', 'post-test', 'post_test']);
                }) : null;

                // Check if current date is equal to or past the end date
                // Uses 'end_date' column or falls back to 'date' column
                $endDate = $training->end_date ?? $training->date;
                $isPosttestOpen = false;

                if ($endDate) {
                    $isPosttestOpen = $today->gte(Carbon::parse($endDate)->startOfDay());
                }

                return [
                    'id'                 => $training->id,
                    'title'              => $training->title,
                    'venue'              => $training->venue ?? 'N/A',
                    'date'               => $training->date ?? 'TBA',
                    'status'             => $training->status,
                    'is_joined'          => $isJoined,
                    'participants_count' => $training->participants_count,
                    'pretest_id'         => $pretest ? $pretest->id : null,
                    'posttest_id'        => $posttest ? $posttest->id : null,
                    'is_posttest_open'   => $isPosttestOpen, // Boolean flag for frontend
                ];
            });

        return response()->json($trainings);
    }

    // Action to Join Training
    public function joinTraining(Training $training)
    {
        $user = Auth::user();

        if ($user->trainings()->where('training_id', $training->id)->exists()) {
            return response()->json([
                'status' => 'info',
                'message' => 'You have already joined this training.'
            ]);
        }

        $user->trainings()->attach($training->id);

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully joined ' . $training->title . '!'
        ]);
    }
}
