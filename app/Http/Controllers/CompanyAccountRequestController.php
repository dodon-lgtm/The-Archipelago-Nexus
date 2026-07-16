<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyAccountRequestStoreRequest;
use App\Models\CompanyAccountRequest;
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

        CompanyAccountRequest::create([
            ...$validated,
            'request_status' => 'menunggu',
        ]);

        return redirect()
            ->route('company-account-requests.create')
            ->with('success', 'Permintaan akun perusahaan berhasil dikirim. Silakan menunggu persetujuan dari admin.');
    }
}

