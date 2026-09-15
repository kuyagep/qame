<?php

namespace App\Http\Controllers;

use App\Models\Facilitator;
use App\Models\Training;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    public function index(Request $request)
    {
        $trainings = Training::latest()->get();

        // Return JSON when requested via AJAX, otherwise render the Blade view
        if ($request->ajax()) {
            return response()->json($trainings);
        }

        return view('pages.trainings.index');
    }
    public function create()
    {
        return view('pages.trainings.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'              => 'required|string|max:255',
            'description'        => 'nullable|string',
            'date'               => 'nullable|string|max:255',
            'number_of_days'     => 'nullable|integer|min:1',
            'end_of_training'    => 'nullable|date',
            'with_accommodation' => 'boolean',
            'venue'              => 'nullable|string|max:255',
            'status'             => 'required|string|max:50',
        ]);

        $validated['with_accommodation'] = $request->has('with_accommodation');

        $training = Training::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Training program created successfully.',
            'data'    => $training
        ], 201);
    }

    // public function show(Training $training)
    // {
    //     $training->load(['sessions.facilitator', 'sessions.topics', 'assessments.questions']);
    //     $facilitators = Facilitator::all();

    //     return view('pages.trainings.show', compact('training', 'facilitators'));
    // }

    public function show(Training $training)
    {
        // Eager load participants with pivot timestamps
        $training->load(['participants' => function ($query) {
            $query->orderBy('training_user.created_at', 'desc');
        }]);

        return view('pages.trainings.show', compact('training'));
    }

    public function edit(Training $training)
    {
        return response()->json($training);
    }

    public function update(Request $request, Training $training)
    {
        $validated = $request->validate([
            'title'              => 'required|string|max:255',
            'description'        => 'nullable|string',
            'date'               => 'nullable|string|max:255',
            'number_of_days'     => 'nullable|integer|min:1',
            'end_of_training'    => 'nullable|date',
            'with_accommodation' => 'boolean',
            'venue'              => 'nullable|string|max:255',
            'status'             => 'required|string|max:50',
        ]);

        $validated['with_accommodation'] = $request->has('with_accommodation');

        $training->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Training updated successfully.',
            'data'    => $training
        ]);
    }

    public function destroy(Training $training)
    {
        $training->delete();

        return response()->json([
            'success' => true,
            'message' => 'Training deleted successfully.'
        ]);
    }
}
