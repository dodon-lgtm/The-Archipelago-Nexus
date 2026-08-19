<?php

namespace App\Http\Middleware;

use App\Models\Workspace;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureWorkspacePaid
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $workspace = $request->route('workspace');

        if (!$workspace) {
            return $next($request);
        }

        if (!$workspace instanceof Workspace) {
            $workspace = Workspace::find($workspace);
        }

        if (!$workspace) {
            return $next($request);
        }

        // Admin has unrestricted access for auditing and management
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        // Check if current route is an allowed payment route
        $routeName = $request->route()?->getName();
        $allowedRoutes = [
            'company.payments.gateway',
            'company.payments.midtrans',
            'company.payments.upload-form',
            'company.payments.upload',
        ];

        if (in_array($routeName, $allowedRoutes)) {
            return $next($request);
        }

        // Check payment status as single source of truth
        $payment = $workspace->payment;
        $isPaid = $payment && $payment->status === 'paid' && $workspace->status !== 'Menunggu Pembayaran';

        if (!$isPaid) {
            $user = Auth::user();

            // If Company: redirect to payment gateway
            if ($user && ((int) $workspace->company_id === (int) $user->id || $user->role === 'company')) {
                return redirect()
                    ->route('company.payments.gateway', $workspace)
                    ->with('error', 'Silakan selesaikan pembayaran terlebih dahulu untuk mengakses workspace dan memulai proyek.');
            }

            // If Freelancer: block access and redirect with clear message
            if ($user && ((int) $workspace->freelancer_id === (int) $user->id || $user->role === 'freelancer')) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Proyek ini masih menunggu pembayaran dari perusahaan. Anda belum dapat melakukan aktivitas pada workspace.'
                    ], 403);
                }

                return redirect()
                    ->route('freelancer.workspaces.index')
                    ->with('error', 'Proyek ini masih menunggu pembayaran dari perusahaan. Anda belum dapat mengakses workspace sebelum pembayaran diselesaikan.');
            }

            // Default fallback
            abort(403, 'Workspace ini belum menyelesaikan tahap pembayaran.');
        }

        return $next($request);
    }
}
