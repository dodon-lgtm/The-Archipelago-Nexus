<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Workspace;
use App\Models\Message;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    /**
     * Show the payment upload form (or redirect if not applicable).
     */
    public function showUploadForm(Workspace $workspace)
    {
        // Hanya company yang bisa akses
        if ((int) $workspace->company_id !== (int) Auth::id()) {
            abort(403, 'Hanya perusahaan yang dapat mengakses halaman ini.');
        }

        // Pastikan status workspace adalah Menunggu Pembayaran
        if ($workspace->status !== 'Menunggu Pembayaran') {
            return redirect()
                ->route('company.workspaces.show', $workspace)
                ->with('error', 'Workspace tidak dalam status Menunggu Pembayaran.');
        }

        $payment = $workspace->payment;

        if (!$payment) {
            return redirect()
                ->route('company.workspaces.show', $workspace)
                ->with('error', 'Data pembayaran tidak ditemukan.');
        }

        return view('company.payments.upload', compact('workspace', 'payment'));
    }

    /**
     * Upload payment proof.
     */
    public function uploadProof(Request $request, Workspace $workspace): RedirectResponse
    {
        // Hanya company yang bisa upload
        if ((int) $workspace->company_id !== (int) Auth::id()) {
            abort(403, 'Hanya perusahaan yang dapat mengupload bukti pembayaran.');
        }

        // Pastikan workspace dalam status Menunggu Pembayaran
        if ($workspace->status !== 'Menunggu Pembayaran') {
            return redirect()
                ->route('company.workspaces.show', $workspace)
                ->with('error', 'Workspace tidak dalam status Menunggu Pembayaran.');
        }

        $payment = $workspace->payment;

        if (!$payment) {
            return redirect()
                ->route('company.workspaces.show', $workspace)
                ->with('error', 'Data pembayaran tidak ditemukan.');
        }

        // Jika payment sudah paid, tidak bisa upload lagi
        if ($payment->status === 'paid') {
            return redirect()
                ->route('company.workspaces.show', $workspace)
                ->with('error', 'Pembayaran sudah diverifikasi.');
        }

        $request->validate([
            'payment_method' => ['required', 'string', 'in:Transfer Bank,QRIS,E-Wallet'],
            'payment_proof' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:10240'], // 10 MB
            'company_note' => ['nullable', 'string', 'max:2000'],
        ], [
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
            'payment_proof.required' => 'Bukti pembayaran wajib diupload.',
            'payment_proof.mimes' => 'Bukti pembayaran harus berupa file: jpg, jpeg, png, atau pdf.',
            'payment_proof.max' => 'Ukuran file maksimal 10 MB.',
        ]);

        // Hapus file bukti pembayaran lama jika ada (untuk re-upload)
        if ($payment->payment_proof) {
            Storage::disk('public')->delete($payment->payment_proof);
        }

        // Upload file bukti pembayaran
        $filePath = $request->file('payment_proof')
            ->store('payment-proofs', 'public');

        // Update payment
        $payment->update([
            'payment_method' => $request->payment_method,
            'payment_proof' => $filePath,
            'company_note' => $request->company_note,
            'status' => 'waiting_verification',
        ]);

        // Update workspace status
        $workspace->update(['status' => 'Menunggu Verifikasi Admin']);

        // System message
        Message::create([
            'workspace_id' => $workspace->id,
            'sender_id' => Auth::id(),
            'message' => 'Perusahaan telah mengirim bukti pembayaran dan menunggu verifikasi admin.',
            'type' => 'system',
        ]);

// Notifikasi untuk semua admin (pembayaran perlu diverifikasi)
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            NotificationService::sendTo(
                user: $admin->id,
                type: 'payment.waiting',
                title: 'Pembayaran Perlu Diverifikasi',
                message: 'Perusahaan "' . (Auth::user()->companyProfile->company_name ?? Auth::user()->name) . '" telah mengirim bukti pembayaran untuk proyek "' . ($workspace->project->project_name ?? '') . '". Silakan lakukan verifikasi.',
                redirect: route('admin.payments.show', $payment),
                senderId: Auth::id(),
                paymentId: $payment->id,
                workspaceId: $workspace->id,
                projectId: $workspace->project_id,
            );
        }

        return redirect()
            ->route('company.workspaces.show', $workspace)
            ->with('success', 'Bukti pembayaran berhasil dikirim. Menunggu verifikasi admin.');
    }
}

