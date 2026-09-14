<?php

namespace App\Http\Controllers;

use App\Models\DayEvaluation;
use App\Models\Training;
use Illuminate\Http\Request;

class DayEvaluationController extends Controller
{
    public function create(Training $training)
    {
        return view('pages.evaluations.day', compact('training'));
    }

    public function store(Request $request, Training $training)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'sex' => 'nullable|in:Male,Female,Prefer not to say',
            'school_office' => 'nullable|string|max:255',
            'day' => 'required|string|max:100',

            // Program Management
            'pm1_time_management' => 'required|integer|between:1,4',
            'pm2_clear_instructions' => 'required|integer|between:1,4',
            'pm3_logical_organization' => 'required|integer|between:1,4',
            'pm4_time_allotted' => 'required|integer|between:1,4',
            'pm5_adequate_breaks' => 'required|integer|between:1,4',
            'pm6_proper_structure' => 'required|integer|between:1,4',
            'pm7_inclusive_language' => 'required|integer|between:1,4',
            'pm8_efficient_management' => 'required|integer|between:1,4',
            'pm9_pmt_responsiveness' => 'required|integer|between:1,4',

            // Training Venue
            'tv1_lighting_ventilation' => 'required|integer|between:1,4',
            'tv2_space' => 'required|integer|between:1,4',
            'tv3_soundproofing' => 'required|integer|between:1,4',
            'tv4_cleanliness_restrooms' => 'required|integer|between:1,4',
            'tv5_internet_connection' => 'required|integer|between:1,4',
            'tv6_meal_quality' => 'required|integer|between:1,4',
            'tv7_meal_nutrition' => 'required|integer|between:1,4',

            'important_insights' => 'nullable|string',
            'suggestions' => 'nullable|string',
            'privacy_consent' => 'required|accepted',
        ]);

        $validated['training_id'] = $training->id;

        DayEvaluation::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Thank you! Your End of the Day Evaluation has been submitted successfully.'
        ]);
    }
}
