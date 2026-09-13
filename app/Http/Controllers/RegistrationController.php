<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use App\Notifications\SystemAlertNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;
use Yajra\Address\Entities\Province;

/**
 * Handle an incoming registration request.
 */

class RegistrationController extends Controller
{
    /**
     * Display the registration view with the top-level Province list.
     */
    public function showRegistrationForm()
    {
        // Fetch all provinces ordered alphabetically to populate step 2
        $departments = Department::orderBy('name', 'asc')->get();
        $provinces = Province::orderBy('name', 'asc')->get();

        return view('auth.registration', compact('provinces', 'departments'));
    }



    public function store(Request $request)
    {
        // 1. Validate form fields
        $validated = $request->validate([
            // Step 1: Personal & Institutional Profile Info
            'prefix'        => ['nullable', 'string', 'in:Mr.,Ms.,Mrs.'],
            'first_name'    => ['required', 'string', 'max:100'],
            'middle_name'   => ['nullable', 'string', 'max:100'],
            'last_name'     => ['required', 'string', 'max:100'],
            'suffix'        => ['nullable', 'string', 'max:20'],

            'birthdate'     => ['required', 'date', 'before:today'],
            'sex'        => ['required', 'string', 'in:Male,Female'],
            'religion'      => ['required', 'string', 'max:100'],
            'disability'    => ['required', 'string', 'in:No Data,No,Yes'],
            'ethnic_group'  => ['nullable', 'string', 'max:100'],

            // Institutional Info (Matches added form fields & enforces relationship)
            'position'      => ['required', 'string', 'max:150'],
            'department_id' => ['required', 'exists:departments,id'],
            'office_id'     => [
                'required',
                Rule::exists('offices', 'id')->where(function ($query) use ($request) {
                    return $query->where('department_id', $request->department_id);
                }),
            ],

            // Step 2: Address Data (Matched 'purok' with the form field input name)
            'province_id'   => ['required', 'exists:provinces,province_id'],
            'city_id'       => ['required', 'exists:cities,city_id'],
            'barangay_id'   => ['required', 'exists:barangays,id'],
            'street'         => ['required', 'string', 'max:255'],

            // Step 3: Account Credentials
            'email'         => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'mobile_number' => ['required', 'string', 'regex:/^9\d{9}$/'], // Match PH 9xxxxxxxxx
            // 'username'      => ['required', 'string', 'min:4', 'max:50', 'unique:users,username'],
            'password'      => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'agree_terms'   => ['accepted'],
        ]);

        // 2. Execute user creation and role assignment within a DB Transaction
        $user = DB::transaction(function () use ($request, $validated) {
            $user = User::create([
                'prefix'        => $validated['prefix'],
                'first_name'    => $validated['first_name'],
                'middle_name'   => $validated['middle_name'],
                'last_name'     => $validated['last_name'],
                'suffix'        => $validated['suffix'],
                'name'          => trim("{$request->first_name} {$request->middle_name} {$request->last_name} {$request->suffix}"),
                'email'         => $validated['email'],
                'mobile_number' => '+63' . $validated['mobile_number'],
                'birthdate'     => $validated['birthdate'],
                'sex'        => $validated['sex'],
                'religion'      => $validated['religion'],
                'disability'    => $validated['disability'],
                'ethnic_group'  => $validated['ethnic_group'],

                // Institutional & Work Mappings
                'position'      => $validated['position'],
                'department_id' => $validated['department_id'],
                'office_id'     => $validated['office_id'],

                // Address Details
                'province_id'   => $validated['province_id'],
                'city_id'       => $validated['city_id'],
                'barangay_id'   => $validated['barangay_id'],
                'street'         => $validated['street'],

                // Auth
                'username'      => $validated['email'], // Using email as username for simplicity
                'password'      => Hash::make($validated['password']),
                'status'        => 'pending',
            ]);

            // Assign default role safely
            $staffRole = Role::where('name', 'Staff')->first();
            if ($staffRole) {
                $user->assignRole($staffRole);
            }

            return $user;
        });

        // 3. Notify Super Admins
        $admins = User::role('Super Admin')->get();
        $payload = [
            'title'      => 'New Registration Awaiting Review',
            'message'    => "Account application submitted by {$user->name} ({$user->email}).",
            'icon'       => 'fas fa-user-clock text-warning',
            'action_url' => route('users.index'),
        ];

        foreach ($admins as $admin) {
            $admin->notify(new SystemAlertNotification($payload));
        }

        // 4. Trigger email verification event
        event(new Registered($user));

        // 5. Authenticate session & Redirect
        Auth::login($user);

        return redirect()->route('verification.notice');
    }
}
