<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $departments = Department::latest()->get();
            return response()->json($departments);
        }

        return view('departments.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
        ]);

        $department = Department::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Department created successfully!',
            'data' => $department
        ], 201);
    }

    public function edit(Department $department)
    {
        return response()->json($department);
    }

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
        ]);

        $department->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Department updated successfully!',
            'data' => $department
        ]);
    }

    public function destroy(Department $department)
    {
        $department->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Department deleted successfully!'
        ]);
    }
}
