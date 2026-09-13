<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    /**
     * Fetch the role along with its currently assigned permission names.
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);

        return response()->json([
            'role' => $role,
            // Pull all permission names attached to this role
            'assigned_permissions' => $role->permissions->pluck('name')->toArray()
        ]);
    }

    /**
     * Sync selected permissions to the target role.
     */
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name'
        ]);

        // syncPermissions automatically drops unselected permissions and links new ones
        $role->syncPermissions($request->permissions ?? []);

        return response()->json([
            'success' => true,
            'message' => "Permissions matrix for standard role '{$role->name}' updated successfully."
        ]);
    }
}
