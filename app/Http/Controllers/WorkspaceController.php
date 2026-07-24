<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use App\Models\Message;
use App\Models\ProgressHistory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WorkspaceController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Daftar workspace (freelancer).
     */
    public function freelancerIndex(): View
    {
        $workspaces = Workspace::with([
            'project',
            'company',
            'latestProgress',
        ])
            ->where('freelancer_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('workspace.freelancer-index', compact('workspaces'));
    }

    /**
     * Daftar workspace (company).
     */
    public function companyIndex(): View
    {
        $workspaces = Workspace::with([
            'project',
            'freelancer',
            'latestProgress',
        ])
            ->where('company_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('workspace.company-index', compact('workspaces'));
    }

    /**
     * Menampilkan detail workspace (chat + progress).
     */
    public function show(Workspace $workspace): View
    {
        $this->authorizeAccess($workspace);

        $workspace->load([
            'project',
            'company',
            'freelancer',
            'messages' => function ($q) {
                $q->with('sender')->oldest();
            },
            'progressHistories' => function ($q) {
                $q->latest();
            },
            'latestProgress',
        ]);

        $allStages = [
            'Dipilih', 'Analisis', 'Desain', 'Backend',
            'Frontend', 'Testing', 'Revisi', 'Selesai',
        ];

        $activeStage = $workspace->latestProgress?->stage;
        $activeStageIndex = $activeStage ? array_search($activeStage, $allStages) : 0;
        $latestProgress = $workspace->latestProgress;
        $progressValue = $latestProgress?->progress ?? 0;

        return view('workspace.show', compact(
            'workspace', 'allStages', 'activeStage',
            'activeStageIndex', 'latestProgress', 'progressValue'
        ));
    }

    /**
     * Kirim pesan chat.
     */
    public function sendMessage(Request $request, Workspace $workspace): RedirectResponse
    {
        $this->authorizeAccess($workspace);

        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        Message::create([
            'workspace_id' => $workspace->id,
            'sender_id' => auth()->id(),
            'message' => $request->message,
            'type' => 'user',
        ]);

        return redirect()
            ->route(
                auth()->user()->role === 'company' ? 'company.workspaces.show' : 'freelancer.workspaces.show',
                $workspace
            )
            ->with('success', 'Pesan berhasil dikirim.');
    }

    /**
     * Update progress (hanya freelancer).
     */
    public function updateProgress(Request $request, Workspace $workspace): RedirectResponse
    {
        // Hanya freelancer yang bisa update progress
        if ((int) $workspace->freelancer_id !== (int) auth()->id()) {
            abort(403, 'Hanya freelancer yang dapat mengupdate progress.');
        }

        $request->validate([
            'stage' => 'required|in:Dipilih,Analisis,Desain,Backend,Frontend,Testing,Revisi,Selesai',
            'progress' => 'required|integer|min:0|max:100',
            'description' => 'nullable|string|max:500',
        ]);

        $latestProgress = $workspace->latestProgress()->first();
        $currentProgress = $latestProgress?->progress ?? 0;
        $newProgress = (int) $request->progress;

        // Validasi: progress tidak boleh turun
        if ($newProgress < $currentProgress) {
            return redirect()
                ->route('freelancer.workspaces.show', $workspace)
                ->with('error', 'Progress tidak boleh turun dari ' . $currentProgress . '%.');
        }

        // Validasi: progress maksimal 100
        if ($newProgress > 100) {
            return redirect()
                ->route('freelancer.workspaces.show', $workspace)
                ->with('error', 'Progress maksimal 100%.');
        }

        ProgressHistory::create([
            'workspace_id' => $workspace->id,
            'stage' => $request->stage,
            'progress' => $newProgress,
            'description' => $request->description,
            'updated_by' => auth()->id(),
        ]);

        // Jika progress 100%, otomatis status workspace jadi "Menunggu Revisi"
        // (menunggu konfirmasi perusahaan)
        if ($newProgress === 100) {
            $workspace->update(['status' => 'Menunggu Revisi']);

            Message::create([
                'workspace_id' => $workspace->id,
                'sender_id' => auth()->id(),
                'message' => 'Freelander telah menyelesaikan pekerjaan dan menunggu konfirmasi perusahaan.',
                'type' => 'system',
            ]);
        }

        return redirect()
            ->route('freelancer.workspaces.show', $workspace)
            ->with('success', 'Progress berhasil diperbarui.');
    }

    /**
     * Konfirmasi pekerjaan selesai (hanya company).
     */
    public function complete(Workspace $workspace): RedirectResponse
    {
        // Hanya company yang bisa konfirmasi
        if ((int) $workspace->company_id !== (int) auth()->id()) {
            abort(403, 'Hanya perusahaan yang dapat mengkonfirmasi pekerjaan selesai.');
        }

        // Pastikan progress sudah 100%
        $latestProgress = $workspace->latestProgress()->first();
        if (!$latestProgress || $latestProgress->progress < 100) {
            return redirect()
                ->route('company.workspaces.show', $workspace)
                ->with('error', 'Progress belum 100%.');
        }

        // Ubah status workspace menjadi Selesai
        $workspace->update(['status' => 'Selesai']);

        // System message
        Message::create([
            'workspace_id' => $workspace->id,
            'sender_id' => auth()->id(),
            'message' => 'Pekerjaan telah dikonfirmasi selesai.',
            'type' => 'system',
        ]);

        return redirect()
            ->route('company.workspaces.show', $workspace)
            ->with('success', 'Pekerjaan telah dikonfirmasi selesai.');
    }

    /**
     * Otorisasi akses workspace.
     */
    private function authorizeAccess(Workspace $workspace): void
    {
        $user = auth()->user();
        $isCompany = (int) $workspace->company_id === (int) $user->id;
        $isFreelancer = (int) $workspace->freelancer_id === (int) $user->id;

        if (!$isCompany && !$isFreelancer) {
            abort(403, 'Anda tidak memiliki akses ke workspace ini.');
        }
    }
}

