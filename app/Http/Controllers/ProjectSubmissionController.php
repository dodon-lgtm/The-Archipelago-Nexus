<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use App\Models\Message;
use App\Models\ProjectSubmission;
use App\Models\SubmissionFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectSubmissionController extends Controller
{
    /**
     * Allowed file extensions for submission uploads.
     */
    private const ALLOWED_EXTENSIONS = [
        // Images
        'png', 'jpg', 'jpeg', 'webp', 'gif',
        // Videos
        'mp4', 'mov', 'avi', 'mkv',
        // Documents
        'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt',
        // Database / Archives
        'sql', 'zip', 'rar', '7z',
        // Source Code / Others
        'json', 'xml', 'fig', 'apk',
    ];

    /**
     * Maximum total upload size in bytes (100 MB).
     */
    private const MAX_TOTAL_SIZE = 100 * 1024 * 1024;

    public function store(Request $request, Workspace $workspace): RedirectResponse
    {
        // Hanya freelancer yang bisa upload
        if ((int) $workspace->freelancer_id !== (int) auth()->id()) {
            abort(403, 'Hanya freelancer yang dapat mengirim hasil pekerjaan.');
        }

        // Jika sudah ada submission yang diterima, freelancer tidak boleh upload lagi
        if ($workspace->submissions()->where('status', 'accepted')->exists()) {
            return redirect()
                ->route('freelancer.workspaces.show', $workspace)
                ->with('error', 'Hasil pekerjaan sudah diterima. Tidak dapat mengirim submission baru.');
        }

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'files' => ['required', 'array', 'min:1'],
            'files.*' => [
                'required',
                'file',
                'mimes:' . implode(',', self::ALLOWED_EXTENSIONS),
            ],
        ], [
            'files.required' => 'Minimal 1 file harus diunggah.',
            'files.min' => 'Minimal 1 file harus diunggah.',
            'files.*.mimes' => 'Tipe file tidak diizinkan. Gunakan: ' . implode(', ', self::ALLOWED_EXTENSIONS) . '.',
            'title.required' => 'Judul pekerjaan wajib diisi.',
        ]);

        // Validasi total ukuran seluruh file maksimal 100 MB
        $totalSize = 0;
        foreach ($request->file('files') as $file) {
            $totalSize += $file->getSize();
        }
        if ($totalSize > self::MAX_TOTAL_SIZE) {
            return redirect()
                ->route('freelancer.workspaces.show', $workspace)
                ->with('error', 'Total ukuran seluruh file maksimal 100 MB.');
        }

        // Buat submission terlebih dahulu
        $submission = ProjectSubmission::create([
            'workspace_id' => $workspace->id,
            'submitted_by' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        // Upload masing-masing file dan simpan ke tabel submission_files
        foreach ($request->file('files') as $file) {
            $fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('submissions', $fileName, 'public');

            SubmissionFile::create([
                'submission_id' => $submission->id,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $filePath,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
            ]);
        }

        // System message ke chat workspace
        Message::create([
            'workspace_id' => $workspace->id,
            'sender_id' => auth()->id(),
            'message' => 'Freelancer telah mengirim hasil pekerjaan.',
            'type' => 'system',
        ]);

        return redirect()
            ->route('freelancer.workspaces.show', $workspace)
            ->with('success', 'Hasil pekerjaan berhasil dikirim.');
    }

    public function accept(Request $request, Workspace $workspace, ProjectSubmission $submission): RedirectResponse
    {
        // Hanya company yang bisa accept
        if ((int) $workspace->company_id !== (int) auth()->id()) {
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

        // Update submission
        $submission->update([
            'status' => 'accepted',
            'company_note' => $request->company_note,
            'reviewed_at' => now(),
        ]);

        // Update workspace status jadi Selesai
        $workspace->update(['status' => 'Selesai']);

        // System message
        $messageText = 'Perusahaan telah menerima hasil pekerjaan.';
        if ($request->filled('company_note')) {
            $messageText .= "\n\nCatatan:\n" . $request->company_note;
        }

        Message::create([
            'workspace_id' => $workspace->id,
            'sender_id' => auth()->id(),
            'message' => $messageText,
            'type' => 'system',
        ]);

        return redirect()
            ->route('company.workspaces.show', $workspace)
            ->with('success', 'Hasil pekerjaan telah diterima.');
    }

    public function requestRevision(Request $request, Workspace $workspace, ProjectSubmission $submission): RedirectResponse
    {
        // Hanya company yang bisa minta revisi
        if ((int) $workspace->company_id !== (int) auth()->id()) {
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
            'sender_id' => auth()->id(),
            'message' => $messageText,
            'type' => 'system',
        ]);

        return redirect()
            ->route('company.workspaces.show', $workspace)
            ->with('success', 'Permintaan revisi telah dikirim.');
    }
}

