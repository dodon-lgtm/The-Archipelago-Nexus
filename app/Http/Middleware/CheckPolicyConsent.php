<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class CheckPolicyConsent
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only check for authenticated users
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // Skip check for admin users - they manage policies, shouldn't be blocked
        if ($user->role === 'admin') {
            return $next($request);
        }

        // Skip check for certain routes (login, logout, register, policy pages, consent pages)
        $exceptRoutes = [
            'login',
            'logout',
            'register',
            'kebijakan-privasi',
            'syarat-ketentuan',
            'kebijakan-penggunaan',
            'password.request',
            'password.email',
            'password.verify',
            'password.verify.submit',
            'password.resend',
            'password.reset',
            'password.reset.submit',
            'login.google',
        ];

        if (in_array(Route::currentRouteName(), $exceptRoutes)) {
            return $next($request);
        }

        // Skip for AJAX/API requests
        if ($request->ajax() || $request->wantsJson()) {
            return $next($request);
        }

        // Check if user has pending required policies
        $pendingPolicies = $user->getPendingRequiredPolicies();

        if ($pendingPolicies->isNotEmpty()) {
            // Redirect to consent page with list of policies to accept
            return Redirect::route('consent.required')
                ->with('pending_policies', $pendingPolicies);
        }

        return $next($request);
    }
}
