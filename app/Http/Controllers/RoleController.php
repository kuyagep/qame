<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::latest()->get();
        return view('roles.index', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        // Explicitly setting guard_name if using spatie/laravel-permission
        $validated['guard_name'] = 'web';
        $role = Role::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Role created successfully.',
            'role' => $role
        ]);
    }

    public function show($id)
    {
        $role = Role::findOrFail($id);
        return response()->json($role);
    }


    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles')->ignore($role->id)],
            'description' => 'nullable|string|max:500',
        ]);

        $role->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Role updated successfully.',
            'role' => $role
        ]);
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return response()->json([
            'success' => true,
            'message' => 'Role deleted successfully.'
        ]);
    }
}
