<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Validation\Rules\Password;
use Yajra\Address\Entities\Province;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{

    /**
     * Display the user's profile.
     */
    public function index(Request $request): View
    {
        // dd($request->user());
        $provinces = Province::orderBy('name', 'asc')->get();
        $departments = Department::orderBy('name', 'asc')->get();
        // dd($request->user());
        return view('profile.index', [
            'user' => $request->user(),
            'provinces' => $provinces,
            'departments' => $departments,
        ]);
    }

    public function password(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => [
                'required',
                'confirmed',
                Password::defaults(),
            ],
        ]);

        $user = Auth::user();

        // Verify current password
        if (! Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors([
                    'current_password' => 'The current password is incorrect.',
                ])
                ->withInput();
        }

        // Prevent using the same password
        if (Hash::check($request->password, $user->password)) {
            return back()
                ->withErrors([
                    'password' => 'The new password must be different from your current password.',
                ]);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()
            ->route('profile.index')
            ->with('success', 'Your password has been changed successfully.');
    }
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // 1. Gather all validated input fields
        $data = $request->validated();

        // 2. Handle File Upload for Avatar
        if ($request->hasFile('avatar')) {
            // Delete the old avatar file from disk if it exists
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Store the new file in the "avatars" folder under public disk
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }
        $data['name'] =  $request->first_name . ' ' . $request->middle_name . ' ' . $request->last_name . ' ' . $request->suffix;
        // 3. Fill and save database attributes
        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // 4. Redirect with a 'success' key to match your Blade's session('success') check
        return Redirect::route('profile.index')->with('success', 'Your profile details have been successfully updated.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Serve the user's avatar or a default fallback.
     */
    public function avatar()
    {
        $user = auth()->user();

        // Check if user has an avatar set and if it exists on the public disk
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            return response()->file(Storage::disk('public')->path($user->avatar));
        }

        return redirect('https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&color=FFFFFF&background=861408');
    }

    public function showAvatar(\App\Models\User $user)
    {
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            return response()->file(Storage::disk('public')->path($user->avatar));
        }

        return redirect('https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&color=FFFFFF&background=861408');
    }
}
