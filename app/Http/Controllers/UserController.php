<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        // OR fetch with pagination (recommended for large datasets)
        $users = User::with('roles')->latest()->paginate(4);
        $roles = Role::all(); // Used to build our role selection list checkboxes/options

        return view('users.index', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username|max:255',
            'email' => 'required|string|email|unique:users,email|max:255',
            'password' => 'required|string|min:8',
            'status' => 'required|in:active,pending,suspended',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        // 1. Create the user
        $user = User::create($validated);

        // 2. Assign default "Staff" role
        $user->assignRole('Staff');

        // 3. Load roles relationship so response contains role data for AJAX rendering
        $user->load('roles');

        return response()->json([
            'success' => true,
            'message' => 'User created successfully.',
            'user' => $user
        ]);
    }

    public function assignRoles(Request $request, User $user)
    {
        $request->validate([
            'roles'   => 'nullable|array',
            'roles.*' => 'string|exists:roles,name'
        ]);

        // Sync roles via Spatie package array mapping helper
        // Passing an empty array or missing key implicitly clears out all old assignments
        $user->syncRoles($request->input('roles', []));

        return response()->json([
            'success' => true,
            'message' => "Access profiles for '{$user->name}' updated successfully."
        ]);
    }

    public function updateStatus(Request $request, User $user)
    {
        $request->validate([
            'status' => ['required', 'string', 'in:active,suspended,pending'],
        ]);

        $user->update([
            'status' => $request->status,
        ]);

        $statusMessage = match ($request->status) {
            'active' => 'User account has been activated.',
            'suspended' => 'User account has been suspended.',
            default => 'User status updated successfully.',
        };

        return response()->json([
            'success' => true,
            'message' => $statusMessage,
        ]);
    }

    public function show(User $user)
    {
        return response()->json($user);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
            'status' => 'required|in:active,inactive',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully.',
            'user' => $user
        ]);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully.'
        ]);
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'ids'    => 'required|array',
            'ids.*'  => 'exists:users,id',
            'action' => 'required|in:delete,suspend,activate',
        ]);

        $ids = $request->ids;
        $action = $request->action;

        // Prevent admin self-deletion/suspension
        if (in_array(auth()->id(), $ids) && ($action === 'delete' || $action === 'suspend')) {
            return response()->json([
                'message' => 'You cannot suspend or delete your own account.'
            ], 422);
        }

        switch ($action) {
            case 'delete':
                User::whereIn('id', $ids)->delete();
                $message = count($ids) . ' user(s) deleted successfully.';
                break;

            case 'suspend':
                User::whereIn('id', $ids)->update(['status' => 'inactive']);
                $message = count($ids) . ' user(s) suspended successfully.';
                break;

            case 'activate':
                User::whereIn('id', $ids)->update(['status' => 'active']);
                $message = count($ids) . ' user(s) activated successfully.';
                break;
        }

        return response()->json(['message' => $message]);
    }

    /**
     * Export Selected Users to CSV
     */
    public function exportSelected(Request $request)
    {
        $ids = explode(',', $request->query('ids', ''));
        $users = User::with('roles')->whereIn('id', $ids)->get();

        return $this->generateCsvDownload($users, 'selected_users.csv');
    }

    /**
     * Export All Users to CSV
     */
    public function exportAll()
    {
        $users = User::with('roles')->get();

        return $this->generateCsvDownload($users, 'all_users.csv');
    }

    /**
     * Helper to Stream CSV File
     */
    private function generateCsvDownload($users, $filename)
    {
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($users) {
            $file = fopen('php://output', 'w');

            // CSV Header Row
            fputcsv($file, ['ID', 'Name', 'Username', 'Email', 'Roles', 'Status', 'Created At']);

            foreach ($users as $user) {
                $roles = $user->roles->pluck('name')->implode(', ');
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->username ?? 'N/A',
                    $user->email,
                    $roles ?: 'User',
                    ucfirst($user->status),
                    $user->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
