<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = Auth::user();

        if ($user->status !== 'active') {
            // If pending, regenerate session and redirect directly to the pending page

            // For suspended/inactive users, log them out and destroy the session
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($user->status === 'pending') {
                $request->session()->regenerate();

                return redirect()->route('registration.pending');
            }

            throw ValidationException::withMessages([
                'email' => 'This access account profile has been locked out or suspended.',
            ]);
        }
        $request->session()->regenerate();
        return redirect()->intended(url('/dashboard'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
