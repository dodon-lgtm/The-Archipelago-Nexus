<?php

namespace App\Http\Middleware;

use App\Services\ProfileCompletionService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Admin tidak perlu pengecekan profil
        if ($user->role === 'admin') {
            return $next($request);
        }

        $completionService = app(ProfileCompletionService::class);

        if (!$completionService->isComplete($user)) {
            $missingFields = $completionService->getMissingMandatoryFields($user);
            $fieldsList = implode(', ', $missingFields);

            $message = 'Profil Anda belum lengkap. Silakan lengkapi minimal 80% profil terlebih dahulu agar dapat menggunakan fitur ini.';
            
            if (!empty($missingFields)) {
                $message .= ' Field yang masih kurang: ' . $fieldsList . '.';
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], 403);
            }

            return redirect()
                ->route($user->role === 'freelancer' ? 'freelancer.profile' : 'company.profile')
                ->with('error', $message);
        }

        return $next($request);
    }
}
