<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Models\Facilitator;
use App\Models\TrainingSession;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    /**
     * Display a listing of the sessions for a specific training.
     */
    public function index(Training $training, Request $request)
    {
        $sessions = $training->sessions()
            ->with(['facilitator', 'topics' => function ($query) {
                $query->orderBy('order', 'asc');
            }])
            ->orderBy('day', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();

        if ($request->ajax()) {
            return response()->json($sessions);
        }

        $facilitators = Facilitator::orderBy('name', 'asc')->get();

        return view('pages.sessions.index', compact('training', 'sessions', 'facilitators'));
    }

    /**
     * Store a newly created session in storage.
     */
    public function store(Request $request, Training $training)
    {
        $validated = $request->validate([
            'day'            => 'required|integer|min:1',
            'title'          => 'required|string|max:255',
            'facilitator_id' => 'nullable|exists:facilitators,id',
            'start_time'     => 'required|date',
            'end_time'       => 'required|date|after:start_time',
        ]);

        $session = $training->sessions()->create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Session created successfully.',
            'data'    => $session->load('facilitator')
        ], 201);
    }

    /**
     * Show the form for editing the specified session.
     */
    public function edit(TrainingSession $session)
    {
        return response()->json($session);
    }

    /**
     * Update the specified session in storage.
     */
    public function update(Request $request, TrainingSession $session)
    {
        $validated = $request->validate([
            'day'            => 'required|integer|min:1',
            'title'          => 'required|string|max:255',
            'facilitator_id' => 'nullable|exists:facilitators,id',
            'start_time'     => 'required|date',
            'end_time'       => 'required|date|after:start_time',
        ]);

        $session->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Session updated successfully.',
            'data'    => $session->load('facilitator')
        ]);
    }

    /**
     * Remove the specified session from storage.
     */
    public function destroy(TrainingSession $session)
    {
        $session->delete();

        return response()->json([
            'success' => true,
            'message' => 'Session deleted successfully.'
        ]);
    }
}
