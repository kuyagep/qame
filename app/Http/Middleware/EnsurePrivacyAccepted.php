<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePrivacyAccepted
{
    /**
     * Handle an incoming request transaction.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If session indicator key is missing, forcefully reroute back to notice wall
        if (!session('privacy_accepted')) {
            return redirect()->route('privacy.notice');
        }

        return $next($request);
    }
}
