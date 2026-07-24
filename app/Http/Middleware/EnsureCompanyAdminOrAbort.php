<?php

namespace App\Http\Middleware;

use App\Models\CompanyAccountRequest;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class EnsureCompanyAdminOrAbort
{
    /**
     * Handle an incoming request.
     * Only allows users with role = 'company' AND approved account request to proceed.
     * All other roles (freelancer, admin, guest) receive 403 Forbidden.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'Silakan login terlebih dahulu.');
        }

        // Strict check: hanya role 'company' yang diizinkan
        if (Str::lower((string) $user->role) !== 'company') {
            abort(403, 'Akses hanya untuk perusahaan.');
        }

        // Pastikan perusahaan sudah disetujui admin
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

