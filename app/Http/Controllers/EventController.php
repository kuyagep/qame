<?php

namespace App\Http\Controllers;

use App\Mail\PreRegistrationConfirmation;
use App\Models\Event;
use App\Models\Events;
use App\Models\User;
use App\Notifications\SystemAlertNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Yajra\Address\Entities\Province;

class EventController extends Controller
{
    // List all events
    public function index()
    {
        $events = Event::withCount('participants')->latest()->paginate(10);
        return view('events.index', compact('events'));
    }

    // Admin: Store new event
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'      => 'required|string|max:255',
            'location'   => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'capacity'   => 'nullable|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $event = Event::create($validated);

        return response()->json([
            'message'  => 'Event created successfully!',
            'event'    => $event,
            'join_url' => $event->join_url
        ]);
    }

    public function joinByLink($code)
    {
        $event = Event::where('join_code', $code)->firstOrFail();
        $provinces = Province::orderBy('name', 'asc')->get();
        if (!Auth::check()) {
            $departments = \App\Models\Department::all();
            return view('events.pre-register', compact('event', 'departments', 'provinces'));
        }

        return $this->processUserJoin($event, Auth::user());
    }

    public function storePreRegistration(Request $request, $code)
    {


        $event = Event::where('join_code', $code)->firstOrFail();

        if ($event->capacity && $event->participants()->count() >= $event->capacity) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Sorry, this event has reached its maximum participant capacity.'], 422);
            }
            return back()->withErrors(['event' => 'Sorry, this event has reached its maximum participant capacity.']);
        }

        // Match input names strictly with the Blade template
        $validated = $request->validate([
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'sex'           => 'required|in:Male,Female',
            'position'      => 'required|string|max:255',
            'department_id' => ['required', 'exists:departments,id'],
            'office_id'     => [
                'required',
                Rule::exists('offices', 'id')->where(function ($query) use ($request) {
                    return $query->where('department_id', $request->department_id);
                }),
            ],
            'province_id'   => ['required', 'exists:provinces,province_id'],
            'city_id'       => ['required', 'exists:cities,city_id'],
            'barangay_id'   => ['required', 'exists:barangays,id'],
            'street'         => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'mobile_number' => ['required', 'string', 'regex:/^9\d{9}$/'], // Match PH 9xxxxxxxxx
            'agree_terms'   => 'accepted',
        ]);

        DB::beginTransaction();

        try {
            // Generate a clean, structured temporary password (e.g., Firstname + Last initial + @ + 3 numbers)
            // Example: JohnD@482
            $firstNameClean = Str::studly($validated['first_name']);
            $lastInitial    = Str::upper(substr($validated['last_name'], 0, 1));
            $randomNum      = rand(100, 999);
            $plainPassword  = "{$firstNameClean}{$lastInitial}@{$randomNum}";
            // Create user if doesn't exist, or update details if existing
            $user = User::firstOrCreate(
                ['email' => $validated['email']],
                [
                    'first_name'    => $validated['first_name'],
                    'last_name'     => $validated['last_name'],
                    'name'     => $validated['first_name'] . ' ' . $validated['last_name'],
                    'sex'           => $validated['sex'],
                    'position'      => $validated['position'],
                    'department_id' => $validated['department_id'],
                    'office_id'     => $validated['office_id'],
                    'province_id'   => $validated['province_id'],
                    'city_id'       => $validated['city_id'],
                    'barangay_id'   => $validated['barangay_id'],
                    'street'        => $validated['street'],
                    'username'        => $validated['email'], // Use email as username for simplicity
                    'status'        => 'active',
                    'mobile_number' => '+63' . $validated['mobile_number'],
                    'password'      => Hash::make($plainPassword), // Secure placeholder password
                ]
            );
            // Assign default role safely
            $userRole = Role::where('name', 'User')->first();
            if ($userRole) {
                $user->assignRole($userRole);
            }


            // Attach participant to event if not already joined
            if (!$event->participants()->where('user_id', $user->id)->exists()) {
                $event->participants()->attach($user->id);
            }

            DB::commit();

            // 3. Notify Super Admins
            $admins = User::role('Super Admin')->get();
            $payload = [
                'title'      => 'New Event Pre-Registration!',
                'message'    => "Account application submitted by {$user->name} ({$user->email}).",
                'icon'       => 'fas fa-user-clock text-success',
                'action_url' => route('users.index'),
            ];

            foreach ($admins as $admin) {
                $admin->notify(new SystemAlertNotification($payload));
            }

            // Triggers Laravel's Email Verification notification
            Mail::to($user->email)->send(new PreRegistrationConfirmation($user, $event, $plainPassword));

            // 1. Send Laravel's standard Email Verification notification
            event(new Registered($user));

            Auth::login($user);


            if ($request->wantsJson()) {
                return response()->json([
                    'message'  => 'Pre-registration successful! Check your email for your temporary login password.',
                    'redirect' => route('dashboard'),
                ]);
            }

            return redirect()->route('dashboard')->with('success', 'Pre-registration successful! Check your email for your temporary login password.');
        } catch (\Exception $e) {
            DB::rollBack();

            // Log the actual error to storage/logs/laravel.log
            \Illuminate\Support\Facades\Log::error('Pre-registration failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'An error occurred: ' . $e->getMessage() // TEMPORARY: for debugging
                ], 500);
            }

            return back()->withInput()->withErrors([
                'error' => 'An error occurred: ' . $e->getMessage() // TEMPORARY: reveals exact issue on screen
            ]);
        }
    }

    /**
     * Helper to process participant attach
     */
    private function processUserJoin(Event $event, User $user)
    {
        if ($event->isJoinedBy($user->id)) {
            return redirect()->route('events.index')
                ->with('info', "You are already a participant of '{$event->title}'.");
        }

        if ($event->capacity && $event->participants()->count() >= $event->capacity) {
            return redirect()->route('events.index')
                ->with('error', 'Unable to join: This event is full.');
        }

        $event->participants()->attach($user->id);

        return redirect()->route('events.index')
            ->with('success', "Welcome! You are now registered as a participant for '{$event->title}'.");
    }




    // User: Join or Leave Event (Toggle)
    public function toggleJoin($id)
    {
        $event = Event::findOrFail($id);
        $user = auth()->user();

        if ($event->isJoinedBy($user->id)) {
            // Leave Event
            $event->participants()->detach($user->id);
            $isJoined = false;
            $message = 'You have left the event.';
        } else {
            // Check Capacity
            if ($event->capacity && $event->participants()->count() >= $event->capacity) {
                return response()->json([
                    'success' => false,
                    'message' => 'This event is already full.'
                ], 422);
            }

            // Join Event
            $event->participants()->attach($user->id);
            $isJoined = true;
            $message = 'You are now a participant!';
        }

        return response()->json([
            'success'     => true,
            'message'     => $message,
            'isJoined'    => $isJoined,
            'total_count' => $event->participants()->count()
        ]);
    }

    // Admin: Show Participants
    public function participants($id)
    {
        $event = Event::with('participants')->findOrFail($id);
        return response()->json([
            'title'        => $event->title,
            'participants' => $event->participants
        ]);
    }

    public function destroy(Event $event)
    {
        // Optional: Authorize the action via policy or role check
        // $this->authorize('delete', $event);

        try {
            $event->delete();

            return response()->json([
                'success' => true,
                'message' => 'Event deleted successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete event.'
            ], 500);
        }
    }
}
