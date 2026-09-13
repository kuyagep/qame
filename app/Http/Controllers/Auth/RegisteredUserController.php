<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\SystemAlertNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => "pending",
        ]);

        // 2. Safely locate and assign the default Staff role profile
        $staffRole = Role::where('name', 'Staff')->first();
        if ($staffRole) {
            $user->assignRole($staffRole);
        }

        // 3. Notify Super Admins that a profile is awaiting approval
        $admins = User::role('Super Admin')->get();
        $payload = [
            'title' => 'New Registration Awaiting Review',
            'message' => "Account application submitted by {$user->name} ({$user->email}).",
            'icon' => 'fas fa-user-clock text-warning',
            'action_url' => route('users.index'),
        ];
        foreach ($admins as $admin) {
            $admin->notify(new SystemAlertNotification($payload));
        }

        // event(new Registered($user));

        // Auth::login($user);

        // return redirect(route('dashboard', absolute: false));

        // CRITICAL CHANGE FOR BREEZE: Remove the original Auth::login($user) line!
        // We redirect them to a notice landing area instead of logging them in automatically.
        return redirect()->route('registration.pending')
            ->with('success', 'Your account profile has been logged. Access is pending administrative activation approval.');
    }
}
