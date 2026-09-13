<?php

namespace App\Http\Controllers;

use App\Models\Facilitator;
use Illuminate\Http\Request;

class FacilitatorController extends Controller
{
    public function index(Request $request)
    {
        $facilitators = Facilitator::latest()->get();

        if ($request->ajax()) {
            return response()->json($facilitators);
        }

        return view('pages.facilitators.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|max:255|unique:facilitators,email',
            'position'        => 'nullable|string|max:255',
            'office_division' => 'nullable|string|max:255',
        ]);

        $facilitator = Facilitator::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Facilitator added successfully.',
            'data'    => $facilitator
        ], 201);
    }

    public function edit(Facilitator $facilitator)
    {
        return response()->json($facilitator);
    }

    public function update(Request $request, Facilitator $facilitator)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|max:255|unique:facilitators,email,' . $facilitator->id,
            'position'        => 'nullable|string|max:255',
            'office_division' => 'nullable|string|max:255',
        ]);

        $facilitator->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Facilitator details updated successfully.',
            'data'    => $facilitator
        ]);
    }

    public function destroy(Facilitator $facilitator)
    {
        $facilitator->delete();

        return response()->json([
            'success' => true,
            'message' => 'Facilitator deleted successfully.'
        ]);
    }
}
