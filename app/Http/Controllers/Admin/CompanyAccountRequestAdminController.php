<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CompanyAccountRequestApproveRequest;
use App\Http\Requests\Admin\CompanyAccountRequestRejectRequest;
use App\Models\CompanyAccountRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CompanyAccountRequestAdminController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status', 'menunggu');

        $allowed = ['menunggu', 'disetujui', 'ditolak'];
        if (!in_array($status, $allowed, true)) {
            $status = 'menunggu';
        }

        $companyRequests = CompanyAccountRequest::query()
            ->where('request_status', $status)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.company-account-requests.index', [
            'status' => $status,
            'companyRequests' => $companyRequests,
        ]);
    }

    public function show(CompanyAccountRequest $request): View
    {
        return view('admin.company-account-requests.show', [
            'companyRequest' => $request,
        ]);
    }

    public function approve(CompanyAccountRequestApproveRequest $request, CompanyAccountRequest $companyRequest): RedirectResponse
    {
        $admin = $request->user();

        // Admin approve: ubah request_status menjadi disetujui dan ubah role user menjadi company.


        if ($companyRequest->request_status !== 'menunggu') {

            return back()->with('error', 'Permintaan ini tidak dapat disetujui lagi.');
        }

        $companyRequest->request_status = 'disetujui';

        $companyRequest->reviewed_by = $admin->id;
        $companyRequest->note = $request->input('note');
        $companyRequest->save();

        // Ubah role user yang email-nya sama dari freelancer -> company
        $user = User::query()->where('email', $companyRequest->company_email)->first();
        if ($user) {
            $user->role = 'company';
            $user->save();
        }

        return redirect()
            ->route('admin.company-account-requests.index')
            ->with('success', 'Permintaan akun perusahaan disetujui.');

    }

    public function reject(CompanyAccountRequestRejectRequest $request, CompanyAccountRequest $companyRequest): RedirectResponse
    {
        $admin = $request->user();

        if ($companyRequest->request_status !== 'menunggu') {
            return back()->with('error', 'Permintaan ini tidak dapat ditolak lagi.');
        }

        $companyRequest->request_status = 'ditolak';
        $companyRequest->reviewed_by = $admin->id;
        $companyRequest->note = $request->input('note');
        $companyRequest->save();

        // Jika user masih dalam proses register perusahaan (role awal freelancer), hapus user-nya.
        $user = User::query()->where('email', $companyRequest->company_email)->first();
        if ($user && $user->role === 'freelancer') {
            $user->delete();
        }

        return redirect()
            ->route('admin.company-account-requests.index')
            ->with('success', 'Permintaan akun perusahaan telah ditolak.');

    }
}

