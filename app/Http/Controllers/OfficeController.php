<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Office;
use Illuminate\Http\Request;

class OfficeController extends Controller
{
    public function getOffice(string $officeId)
    {

        $offices = Office::where('department_id', $officeId)
            ->orderBy('name', 'asc')
            ->get(['id', 'name']);

        return response()->json($offices);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $offices = Office::with('department')->latest()->get();
            return response()->json($offices);
        }

        $departments = Department::orderBy('name')->get();
        return view('offices.index', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'code'          => 'nullable|string|max:50|unique:offices,code',
            'name'          => 'required|string|max:255',
            'latitude'      => 'nullable|numeric|between:-90,90',
            'longitude'     => 'nullable|numeric|between:-180,180',
        ]);

        $office = Office::create($validated);
        $office->load('department');

        return response()->json([
            'status' => 'success',
            'message' => 'Office created successfully!',
            'data' => $office
        ], 201);
    }

    public function edit(Office $office)
    {
        return response()->json($office);
    }

    public function update(Request $request, Office $office)
    {
        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'code'          => 'nullable|string|max:50|unique:offices,code,' . $office->id,
            'name'          => 'required|string|max:255',
            'latitude'      => 'nullable|numeric|between:-90,90',
            'longitude'     => 'nullable|numeric|between:-180,180',
        ]);

        $office->update($validated);
        $office->load('department');

        return response()->json([
            'status' => 'success',
            'message' => 'Office updated successfully!',
            'data' => $office
        ]);
    }

    public function destroy(Office $office)
    {
        $office->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Office deleted successfully!'
        ]);
    }
}
