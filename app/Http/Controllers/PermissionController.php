<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::latest()->get();
        return view('permissions.index', compact('permissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:permissions,name|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $validated['guard_name'] = 'web';
        $permission = Permission::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Permission registered successfully.',
            'permission' => $permission
        ]);
    }

    public function show($id)
    {
        $permission = Permission::findOrFail($id);
        return response()->json($permission);
    }

    public function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('permissions')->ignore($permission->id)],
            'description' => 'nullable|string|max:500',
        ]);

        $permission->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Permission modified successfully.',
            'permission' => $permission
        ]);
    }

    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return response()->json([
            'success' => true,
            'message' => 'Permission removed successfully.'
        ]);
    }
}
