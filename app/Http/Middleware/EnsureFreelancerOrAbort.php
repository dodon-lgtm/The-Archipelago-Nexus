<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureFreelancerOrAbort
{
    /**
     * Handle an incoming request.
     * Only allows users with role = 'freelancer' to proceed.
     * All others (company, admin, guest) receive 403 Forbidden.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || $user->role !== 'freelancer') {
            abort(403, 'Akses hanya untuk freelancer.');
        }

        return $next($request);
    }
}

