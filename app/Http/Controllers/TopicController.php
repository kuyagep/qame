<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\TrainingSession;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    /**
     * Store a newly created topic in storage for a given session.
     */
    public function store(Request $request, TrainingSession $session)
    {
        $validated = $request->validate([
            'title'               => 'required|string|max:255',
            'learning_objectives' => 'nullable|string',
            'order'               => 'nullable|integer|min:1',
        ]);

        // Default to order 1 if not provided
        $validated['order'] = $validated['order'] ?? 1;

        $topic = $session->topics()->create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Topic added successfully.',
            'data'    => $topic
        ], 201);
    }

    /**
     * Show the specified topic for editing.
     */
    public function edit(Topic $topic)
    {
        return response()->json($topic);
    }

    /**
     * Update the specified topic in storage.
     */
    public function update(Request $request, Topic $topic)
    {
        $validated = $request->validate([
            'title'               => 'required|string|max:255',
            'learning_objectives' => 'nullable|string',
            'order'               => 'nullable|integer|min:1',
        ]);

        $topic->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Topic updated successfully.',
            'data'    => $topic
        ]);
    }

    /**
     * Remove the specified topic from storage.
     */
    public function destroy(Topic $topic)
    {
        $topic->delete();

        return response()->json([
            'success' => true,
            'message' => 'Topic deleted successfully.'
        ]);
    }
}
