<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use App\Models\Message;
use App\Models\ProjectSubmission;
use App\Models\SubmissionFile;
use App\Models\ProgressHistory;
use App\Services\EscrowService;
use App\Services\NotificationService;
use App\Services\ProfileCompletionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProjectSubmissionController extends Controller
{
    /**
     * Kategori file beserta aturan validasinya.
     */
    private const FILE_CATEGORIES = [
        'images' => [
            'category' => 'image',
            'mimes' => 'jpg,jpeg,png,webp',
            'max' => 10240, // 10 MB
        ],
        'videos' => [
            'category' => 'video',
            'mimes' => 'mp4,mov,avi,mkv',
            'max' => 102400, // 100 MB
        ],
        'documents' => [
            'category' => 'document',
            'mimes' => 'pdf,doc,docx',
            'max' => 20480, // 20 MB
        ],
        'archives' => [
            'category' => 'archive',
            'mimes' => 'zip,rar,7z',
            'max' => 102400, // 100 MB
        ],
    ];

    public function store(Request $request, Workspace $workspace): RedirectResponse
    {
        // Hanya freelancer yang bisa upload
        if ((int) $workspace->freelancer_id !== (int) Auth::id()) {
            abort(403, 'Hanya freelancer yang dapat mengirim hasil pekerjaan.');
        }

        // Cek kelengkapan profil freelancer
        $completionService = app(ProfileCompletionService::class);
        if (!$completionService->isComplete(Auth::user())) {
            return redirect()
                ->route('freelancer.profile')
                ->with('error', 'Profil Anda belum lengkap. Silakan lengkapi minimal 80% profil terlebih dahulu agar dapat mengirim hasil pekerjaan.');
        }

// Jika sudah ada submission yang diterima, freelancer tidak boleh upload lagi
        if ($workspace->submissions()->where('status', 'accepted')->exists()) {
            return redirect()
                ->route('freelancer.workspaces.show', $workspace)
                ->with('error', 'Hasil pekerjaan sudah diterima. Tidak dapat mengirim submission baru.');
        }

        // Jika masih ada submission yang pending (menunggu review), freelancer tidak boleh upload lagi
        if ($workspace->submissions()->where('status', 'pending')->exists()) {
            return redirect()
                ->route('freelancer.workspaces.show', $workspace)
                ->with('error', 'Menunggu perusahaan meninjau hasil pekerjaan Anda. Tidak dapat mengirim submission baru.');
        }

        // 1. Validasi title dulu
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        // 2. Validasi bahwa minimal ada 1 file dari salah satu kategori
        $hasAnyFile = false;
        foreach (array_keys(self::FILE_CATEGORIES) as $inputName) {
            if ($request->hasFile($inputName) && count($request->file($inputName)) > 0) {
                $hasAnyFile = true;
                break;
            }
        }

        if (!$hasAnyFile) {
            return redirect()
                ->route('freelancer.workspaces.show', $workspace)
                ->with('error', 'Minimal 1 file harus diunggah.');
        }

        // 3. Validasi per kategori
        $validationRules = [];
        $validationMessages = [];

        foreach (self::FILE_CATEGORIES as $inputName => $config) {
            $validationRules[$inputName] = ['nullable', 'array'];
            $validationRules[$inputName . '.*'] = [
                'file',
                'mimes:' . $config['mimes'],
                'max:' . $config['max'],
            ];

            $label = match ($config['category']) {
                'image' => 'Gambar',
                'video' => 'Video',
                'document' => 'Dokumen',
                'archive' => 'Arsip',
            };

            $validationMessages[$inputName . '.*.mimes'] = 'Format file ' . $label . ' tidak diizinkan. Gunakan: ' . str_replace(',', ', ', $config['mimes']) . '.';
            $validationMessages[$inputName . '.*.max'] = 'Ukuran file ' . $label . ' maksimal ' . ($config['max'] / 1024) . ' MB.';
        }

        $request->validate($validationRules, $validationMessages);

        // Buat submission terlebih dahulu
        $submission = ProjectSubmission::create([
            'workspace_id' => $workspace->id,
            'submitted_by' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        // Upload masing-masing file per kategori dan simpan ke tabel submission_files
        $fileCount = 0;
        foreach (self::FILE_CATEGORIES as $inputName => $config) {
            if (!$request->hasFile($inputName)) {
                continue;
            }

            foreach ($request->file($inputName) as $file) {
                $originalName = $file->getClientOriginalName();
                $fileName = time() . '_' . uniqid() . '_' . $originalName;
                $filePath = $file->storeAs('submissions', $fileName, 'public');

                SubmissionFile::create([
                    'submission_id' => $submission->id,
                    'file_name' => $originalName,
                    'file_path' => $filePath,
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'category' => $config['category'],
                ]);

                $fileCount++;
            }
        }

        // System message ke chat workspace
        Message::create([
            'workspace_id' => $workspace->id,
            'sender_id' => Auth::id(),
            'message' => 'Freelancer telah mengirim hasil pekerjaan.',
            'type' => 'system',
        ]);

        // Notifikasi ke company: hasil pekerjaan telah diupload
        NotificationService::sendTo(
            user: $workspace->company_id,
            type: 'submission.uploaded',
            title: 'Hasil Pekerjaan Diupload',
            message: Auth::user()->name . ' telah mengirim hasil pekerjaan untuk proyek "' . ($workspace->project->project_name ?? '') . '".',
            redirect: route('company.workspaces.show', $workspace),
            senderId: Auth::id(),
            workspaceId: $workspace->id,
            projectId: $workspace->project_id,
        );

        return redirect()
            ->route('freelancer.workspaces.show', $workspace)
            ->with('success', 'Hasil pekerjaan (' . $fileCount . ' file) berhasil dikirim.');
    }

    public function accept(Request $request, Workspace $workspace, ProjectSubmission $submission): RedirectResponse
    {
        // Hanya company yang bisa accept
        if ((int) $workspace->company_id !== (int) Auth::id()) {
            abort(403, 'Hanya perusahaan yang dapat menerima hasil pekerjaan.');
        }

        // Pastikan submission milik workspace ini
        if ((int) $submission->workspace_id !== (int) $workspace->id) {
            abort(404);
        }

        // Pastikan status masih pending
        if ($submission->status !== 'pending') {
            return redirect()
                ->route('company.workspaces.show', $workspace)
                ->with('error', 'Status submission sudah berubah.');
        }

        $request->validate([
            'company_note' => ['nullable', 'string', 'max:2000'],
        ]);

        DB::beginTransaction();

        try {
            // Update submission
            $submission->update([
                'status' => 'accepted',
                'company_note' => $request->company_note,
                'reviewed_at' => now(),
            ]);

            // Update workspace status menjadi Selesai
            $workspace->update(['status' => 'Selesai']);

            // RELEASE dana tertahan (escrow) — idempotent, hanya sekali.
            // Dana held/disputed menjadi released + ledger escrow_released + fee_earned.
            $payment = $workspace->payment;
            if ($payment && $payment->status === 'paid' && $payment->isFundsHeld()) {
                app(EscrowService::class)->release(
                    payment: $payment,
                    report: null,
                    description: 'Dana proyek dirilis ke freelancer karena proyek selesai/disetujui.',
                    createdBy: Auth::id(),
                );

                // Refresh agar funds_status/released_amount terbaru dipakai di pesan.
                $payment->refresh();
            }

            // Buat Progress History penyelesaian (100%)
            ProgressHistory::create([
                'workspace_id' => $workspace->id,
                'stage' => 'Selesai',
                'progress' => 100,
                'description' => 'Hasil pekerjaan telah disetujui oleh perusahaan. Proyek dinyatakan selesai.',
                'updated_by' => Auth::id(),
            ]);

            // System message
            $releaseText = '';
            if ($payment && $payment->funds_status === \App\Models\Payment::FUNDS_RELEASED) {
                $releaseText = ' Dana sebesar Rp ' . number_format((float) $payment->released_amount, 0, ',', '.') . ' telah dirilis ke pendapatan freelancer.';
            }

            $messageText = 'Perusahaan telah menerima hasil pekerjaan. Proyek dinyatakan selesai.' . $releaseText;
            if ($request->filled('company_note')) {
                $messageText .= "\n\nCatatan:\n" . $request->company_note;
            }

            Message::create([
                'workspace_id' => $workspace->id,
                'sender_id' => Auth::id(),
                'message' => $messageText,
                'type' => 'system',
            ]);

            // Notifikasi ke freelancer: hasil pekerjaan diterima & proyek selesai + dana dirilis
            NotificationService::sendTo(
                user: $workspace->freelancer_id,
                type: 'submission.accepted',
                title: 'Hasil Pekerjaan Diterima - Proyek Selesai',
                message: 'Selamat! Hasil pekerjaan Anda untuk proyek "' . ($workspace->project->project_name ?? '') . '" telah disetujui oleh perusahaan. Proyek telah selesai.' . $releaseText,
                redirect: route('freelancer.workspaces.show', $workspace),
                senderId: Auth::id(),
                workspaceId: $workspace->id,
                projectId: $workspace->project_id,
            );

            DB::commit();

            return redirect()
                ->route('company.workspaces.show', $workspace)
                ->with('success', 'Hasil pekerjaan telah diterima. Proyek berhasil diselesaikan!' . $releaseText);

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->route('company.workspaces.show', $workspace)
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function requestRevision(Request $request, Workspace $workspace, ProjectSubmission $submission): RedirectResponse
    {
        // Hanya company yang bisa minta revisi
        if ((int) $workspace->company_id !== (int) Auth::id()) {
            abort(403, 'Hanya perusahaan yang dapat meminta revisi.');
        }

        // Pastikan submission milik workspace ini
        if ((int) $submission->workspace_id !== (int) $workspace->id) {
            abort(404);
        }

        // Pastikan status masih pending
        if ($submission->status !== 'pending') {
            return redirect()
                ->route('company.workspaces.show', $workspace)
                ->with('error', 'Status submission sudah berubah.');
        }

        $request->validate([
            'company_note' => ['nullable', 'string', 'max:2000'],
        ]);

        // Update submission
        $submission->update([
            'status' => 'revision',
            'company_note' => $request->company_note,
            'reviewed_at' => now(),
        ]);

        // Update workspace status jadi Menunggu Revisi
        $workspace->update(['status' => 'Menunggu Revisi']);

        // System message
        $messageText = 'Perusahaan meminta revisi terhadap hasil pekerjaan.';
        if ($request->filled('company_note')) {
            $messageText .= "\n\nCatatan:\n" . $request->company_note;
        }

        Message::create([
            'workspace_id' => $workspace->id,
            'sender_id' => Auth::id(),
            'message' => $messageText,
            'type' => 'system',
        ]);

        // Notifikasi ke freelancer: revisi diminta
        NotificationService::sendTo(
            user: $workspace->freelancer_id,
            type: 'submission.revision_requested',
            title: 'Permintaan Revisi',
            message: 'Perusahaan meminta revisi terhadap hasil pekerjaan untuk proyek "' . ($workspace->project->project_name ?? '') . '".'
                . ($request->filled('company_note') ? "\n\nCatatan: " . $request->company_note : ''),
            redirect: route('freelancer.workspaces.show', $workspace),
            senderId: Auth::id(),
            workspaceId: $workspace->id,
            projectId: $workspace->project_id,
        );

        return redirect()
            ->route('company.workspaces.show', $workspace)
            ->with('success', 'Permintaan revisi telah dikirim.');
    }
}
