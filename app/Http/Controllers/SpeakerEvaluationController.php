<?php

namespace App\Http\Controllers;

use App\Models\SpeakerEvaluation;
use App\Models\Training;
use Illuminate\Http\Request;

class SpeakerEvaluationController extends Controller
{
    public function create(Training $training)
    {
        $sessions = $training->sessions()->with('facilitator')->get();
        return view('pages.evaluations.speaker', compact('training', 'sessions'));
    }

    public function store(Request $request, Training $training)
    {
        $validated = $request->validate([
            'session_id' => 'required|exists:sessions,id',
            'speaker_name' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'sex' => 'required|in:Male,Female,Prefer not to say',
            'district' => 'required|string|max:255',
            'school_office' => 'required|string|max:255',
            'day' => 'nullable|string|max:100',

            // Ratings validation (1-4)
            'q1_started_on_time' => 'required|integer|between:1,4',
            'q2_objectives_explained' => 'required|integer|between:1,4',
            'q3_topics_understandable' => 'required|integer|between:1,4',
            'q4_time_pace_sufficient' => 'required|integer|between:1,4',
            'q5_establishes_rapport' => 'required|integer|between:1,4',
            'q6_positive_environment' => 'required|integer|between:1,4',
            'q7_communication_skills' => 'required|integer|between:1,4',
            'q8_appropriate_technology' => 'required|integer|between:1,4',
            'q9_synthesized_responses' => 'required|integer|between:1,4',
            'q10_flexibility_adaptability' => 'required|integer|between:1,4',
            'q11_professional_manner' => 'required|integer|between:1,4',
            'q12_ended_on_time' => 'required|integer|between:1,4',

            'key_insights' => 'nullable|string',
            'suggestions' => 'nullable|string',
            'privacy_consent' => 'required|accepted',
        ]);

        $validated['training_id'] = $training->id;

        SpeakerEvaluation::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Thank you! Your speaker evaluation has been submitted successfully.'
        ]);
    }
}
