<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Message;
use App\Models\Payment;
use App\Models\ProjectSubmission;
use App\Models\ProgressHistory;
use App\Models\Workspace;
use App\Services\EscrowService;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ResolutionController extends Controller
{
    protected $escrowService;
    protected $notificationService;

    public function __construct(EscrowService $escrowService, NotificationService $notificationService)
    {
        $this->escrowService = $escrowService;
        $this->notificationService = $notificationService;
    }

    public function show(Workspace $workspace): View
    {
        $workspace->load([
            'project',
            'project.owner',
            'project.category',
            'company',
            'freelancer',
            'payment',
            'messages' => function ($q) {
                $q->with('sender')->latest();
            },
            'progressHistories' => function ($q) {
                $q->latest();
            },
            'submissions' => function ($q) {
                $q->with(['submitter', 'files'])->latest();
            },
        ]);

        $payment = $workspace->payment;

        // Compute last activity
        $lastMessage = $workspace->messages->first();
        $latestProgress = $workspace->latestProgress;
        $lastProgressUpdate = $latestProgress ? $latestProgress->created_at : null;

        return view('admin.resolution.show', compact(
            'workspace',
            'payment',
            'lastMessage',
            'lastProgressUpdate',
            'submissions'
        ));
    }

    public function sendMessage(Request $request, Workspace $workspace): RedirectResponse
    {
        $request->validate([
            'recipient' => 'required|in:company,freelancer,both',
            'message' => 'required|string|max:1000',
        ]);

        $message = $request->input('message');
        $recipient = $request->input('recipient');

        // Determine recipient user ID(s)
        $receiverIds = [];
        $receiverRoles = [];

        if ($recipient === 'company' || $recipient === 'both') {
            $receiverIds[] = (int) $workspace->company_id;
            $receiverRoles[] = 'company';
        }
        if ($recipient === 'freelancer' || $recipient === 'both') {
            $receiverIds[] = (int) $workspace->freelancer_id;
            $receiverRoles[] = 'freelancer';
        }

        // Create message(s)
        foreach ($receiverIds as $index => $receiverId) {
            Message::create([
                'workspace_id' => $workspace->id,
                'sender_id' => Auth::id(),
                'message' => $message,
                'type' => 'admin',
            ]);

            // Send notification to recipient
            $role = $receiverRoles[$index];
            $redirectRoute = $role === 'company'
                ? route('company.workspaces.show', $workspace)
                : route('freelancer.workspaces.show', $workspace);

            $this->notificationService->sendTo(
                user: $receiverId,
                type: 'admin.message',
                title: 'Pesan Admin Baru',
                message: $message,
                redirect: $redirectRoute,
                senderId: Auth::id(),
                workspaceId: $workspace->id,
                projectId: $workspace->project_id,
            );
        }

        return redirect()
            ->route('admin.workspace.resolution', $workspace)
            ->with('success', 'Pesan berhasil dikirim kepada penerima.');
    }

    public function requestCompanyResponse(Request $request, Workspace $workspace): RedirectResponse
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'deadline' => 'nullable|string|max:255',
        ]);

        // Create message to company
        Message::create([
            'workspace_id' => $workspace->id,
            'sender_id' => Auth::id(),
            'message' => $request->input('message'),
            'type' => 'admin',
        ]);

        // Send notification to company
        $this->notificationService->sendTo(
            user: (int) $workspace->company_id,
            type: 'admin.request_company_response',
            title: 'Permintaan Respons Company',
            message: $request->input('message'),
            redirect: route('admin.workspace.resolution', $workspace),
            senderId: Auth::id(),
            workspaceId: $workspace->id,
            projectId: $workspace->project_id,
        );

        return redirect()
            ->route('admin.workspace.resolution', $workspace)
            ->with('success', 'Permintaan respons terkirim ke Company.');
    }

    public function requestFreelancerResponse(Request $request, Workspace $workspace): RedirectResponse
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'deadline' => 'nullable|string|max:255',
        ]);

        // Create message to freelancer
        Message::create([
            'workspace_id' => $workspace->id,
            'sender_id' => Auth::id(),
            'message' => $request->input('message'),
            'type' => 'admin',
        ]);

        // Send notification to freelancer
        $this->notificationService->sendTo(
            user: (int) $workspace->freelancer_id,
            type: 'admin.request_freelancer_response',
            title: 'Permintaan Respons Freelancer',
            message: $request->input('message'),
            redirect: route('admin.workspace.resolution', $workspace),
            senderId: Auth::id(),
            workspaceId: $workspace->id,
            projectId: $workspace->project_id,
        );

        return redirect()
            ->route('admin.workspace.resolution', $workspace)
            ->with('success', 'Permintaan respons terkirim ke Freelancer.');
    }

    public function startReview(Request $request, Workspace $workspace): RedirectResponse
    {
        // We'll use the payment's funds_status and workspace status to determine next steps

        // Create initial admin message in workspace
        Message::create([
            'workspace_id' => $workspace->id,
            'sender_id' => Auth::id(),
            'message' => 'Admin memulai peninjauan resolusi untuk workspace ini. Silakan periksa bukti dan memberikan respons.',
            'type' => 'admin',
        ]);

        // Send notification to both parties
        $this->notificationService->sendTo(
            user: (int) $workspace->company_id,
            type: 'admin.review_started',
            title: 'Peninjauan Resolusi Dimulai',
            message: 'Admin memulai peninjauan resolusi untuk workspace proyek "' . ($workspace->project->project_name ?? '') . '".',
            redirect: route('admin.workspace.resolution', $workspace),
            senderId: Auth::id(),
            workspaceId: $workspace->id,
            projectId: $workspace->project_id,
        );

        $this->notificationService->sendTo(
            user: (int) $workspace->freelancer_id,
            type: 'admin.review_started',
            title: 'Peninjauan Resolusi Dimulai',
            message: 'Admin memulai peninjauan resolusi untuk workspace proyek "' . ($workspace->project->project_name ?? '') . '".',
            redirect: route('admin.workspace.resolution', $workspace),
            senderId: Auth::id(),
            workspaceId: $workspace->id,
            projectId: $workspace->project_id,
        );

        return redirect()
            ->route('admin.workspace.resolution', $workspace)
            ->with('success', 'Peninjauan resolusi berhasil dimulai.');
    }

    public function decide(Request $request, Workspace $workspace, ResolutionRequest $requestValidated): RedirectResponse
    {
        $request = $requestValidated->validated();

        $action = $request->input('action');
        $reason = $request->input('reason');
        $payment = $workspace->payment;

        if (!$payment) {
            return redirect()
                ->route('admin.workspace.resolution', $workspace)
                ->with('error', 'Tidak ada pembayaran terkait pada workspace ini.');
        }

        // Use DB transaction for atomicity
        try {
            DB::transaction(function () use ($request, $workspace, $payment, $action, $reason) {
                // Prevent double action: if funds already released/refunded
                if ($payment->isFundsResolved()) {
                    throw new \RuntimeException('Dana sudah terselesaikan, tidak dapat melakukan action lagi.');
                }

                $adminId = Auth::id();

                if ($action === 'release_to_freelancer') {
                    // Release funds to freelancer using existing EscrowService
                    $this->escrowService->release($payment, null, null, $adminId);

                    // Update workspace status to selesai/diterima
                    $workspace->update(['status' => 'Selesai']);

                    // Create system message with resolution decision
                    Message::create([
                        'workspace_id' => $workspace->id,
                        'sender_id' => $adminId,
                        'message' => 'Admin telah memutuskan merilis dana escrow ke Freelancer.',
                        'type' => 'system',
                    ]);

                    // Notify freelancer
                    $this->notificationService->sendTo(
                        user: (int) $workspace->freelancer_id,
                        type: 'funds.released',
                        title: 'Dana Diresminkan',
                        message: 'Admin telah menyelesaikan review workspace dan memutuskan dana escrow diberikan kepada Anda. Alasan: ' . $reason,
                        redirect: route('freelancer.workspaces.show', $workspace),
                        senderId: $adminId,
                        workspaceId: $workspace->id,
                        projectId: $workspace->project_id,
                    );

                    // Notify company
                    $this->notificationService->sendTo(
                        user: (int) $workspace->company_id,
                        type: 'funds.released',
                        title: 'Dana Diresminkan',
                        message: 'Admin telah menyelesaikan review workspace dan menetapkan penyelesaian dana ke Freelancer. Alasan: ' . $reason,
                        redirect: route('company.workspaces.show', $workspace),
                        senderId: $adminId,
                        workspaceId: $workspace->id,
                        projectId: $workspace->project_id,
                    );
                } elseif ($action === 'refund_to_company') {
                    // Refund funds to company using existing EscrowService
                    $this->escrowService->refund($payment, null, null, $adminId);

                    // Update workspace status
                    $workspace->update(['status' => 'Selesai']);

                    // Create system message with resolution decision
                    Message::create([
                        'workspace_id' => $workspace->id,
                        'sender_id' => $adminId,
                        'message' => 'Admin telah memutuskan dana dikembalikan ke Company.',
                        'type' => 'system',
                    ]);

                    // Notify company
                    $this->notificationService->sendTo(
                        user: (int) $workspace->company_id,
                        type: 'funds.refunded',
                        title: 'Dana Direfund',
                        message: 'Admin telah menyelesaikan review workspace dan memutuskan dana dikembalikan kepada Company. Alasan: ' . $reason,
                        redirect: route('company.workspaces.show', $workspace),
                        senderId: $adminId,
                        workspaceId: $workspace->id,
                        projectId: $workspace->project_id,
                    );

                    // Notify freelancer
                    $this->notificationService->sendTo(
                        user: (int) $workspace->freelancer_id,
                        type: 'funds.refunded',
                        title: 'Dana Direfund',
                        message: 'Admin telah menyelesaikan review workspace dan memutuskan dana dikembalikan kepada Company. Alasan: ' . $reason,
                        redirect: route('freelancer.workspaces.show', $workspace),
                        senderId: $adminId,
                        workspaceId: $workspace->id,
                        projectId: $workspace->project_id,
                    );
                }
            });
        } catch (\RuntimeException $e) {
            return redirect()
                ->route('admin.workspace.resolution', $workspace)
                ->with('error', $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Gagal keputusan resolusi workspace #' . $workspace->id . ': ' . $e->getMessage());

            return redirect()
                ->route('admin.workspace.resolution', $workspace)
                ->with('error', 'Terjadi kesalahan saat memproses keputusan.');
        }

        return redirect()
            ->route('admin.workspace.resolution', $workspace)
            ->with('success', 'Keputusan dana berhasil diproses dan tercatat di ledger.');
    }
}