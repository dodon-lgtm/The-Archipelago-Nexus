<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403);
        }

        $adminEmails = collect(explode(',', (string) env('ADMIN_EMAILS', '')))
            ->map(fn ($e) => Str::lower(trim((string) $e)))
            ->filter();

        if ($adminEmails->isEmpty()) {
            // Jika ADMIN_EMAILS belum diisi, batasi akses agar tidak ada user biasa yang bisa jadi admin.
            abort(403);
        }

        if (!$adminEmails->contains(Str::lower((string) $user->email))) {
            abort(403);
        }

        return $next($request);
    }
}

