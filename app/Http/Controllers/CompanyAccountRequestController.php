<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyAccountRequestStoreRequest;
use App\Models\CompanyAccountRequest;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CompanyAccountRequestController extends Controller
{
    public function create(): View
    {
        return view('company-account-requests.create');
    }

    public function store(CompanyAccountRequestStoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $companyAccountRequest = CompanyAccountRequest::create([
            ...$validated,
            'request_status' => 'menunggu',
        ]);

        // Notifikasi ke semua admin: permintaan akun perusahaan baru
        User::where('role', 'admin')->chunk(100, function ($admins) use ($companyAccountRequest, $validated) {
            foreach ($admins as $admin) {
                NotificationService::sendTo(
                    user: $admin->id,
                    type: 'company_request.created',
                    title: 'Permintaan Akun Perusahaan Baru',
                    message: 'Terdapat permintaan akun perusahaan baru dari "' . ($validated['company_name'] ?? '') . '" yang menunggu verifikasi.',
                    redirect: route('admin.company-account-requests.show', $companyAccountRequest),
                    senderId: auth()->id(),
                    companyAccountRequestId: $companyAccountRequest->id,
                );
            }
        });

        return redirect()
            ->route('company-account-requests.create')
            ->with('success', 'Permintaan akun perusahaan berhasil dikirim. Silakan menunggu persetujuan dari admin.');
    }
}

