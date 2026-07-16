<?php

namespace App\Http\Middleware;

use App\Models\CompanyAccountRequest;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class EnsureCompanyAdminOrAbort
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403);
        }

        if (Str::lower((string) $user->role) !== 'company') {
            // Middleware ini hanya untuk user role company.
            // Admin/freelancer akan dilewatkan.
            return $next($request);
        }

        $companyRequest = CompanyAccountRequest::query()
            ->where('company_email', $user->email)
            ->orderByDesc('created_at')
            ->first();

        $allowed = $companyRequest && $companyRequest->request_status === 'disetujui';

        if (!$allowed) {
            $message = $companyRequest
                ? 'Akun perusahaan Anda belum disetujui admin.'
                : 'Akun perusahaan Anda tidak memiliki permintaan yang valid.';

            abort(403, $message);
        }

        return $next($request);
    }
}

