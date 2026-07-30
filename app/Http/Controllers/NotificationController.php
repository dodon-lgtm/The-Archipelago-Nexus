<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Fetch notifications for the current user (JSON).
     * Menampilkan maksimal 10 notifikasi terbaru.
     */
    public function index()
    {
        $notifications = Notification::with(['sender', 'penawaran.project', 'penawaran.freelancer'])
            ->where('user_id', Auth::id())
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($notification) {
                // Ambil redirect URL dari kolom data (JSON)
                $data = $notification->data;
                $redirectUrl = is_array($data) && isset($data['redirect']) ? $data['redirect'] : null;

                return [
                    'id'          => $notification->id,
                    'user_id'     => $notification->user_id,
                    'sender_id'   => $notification->sender_id,
                    'type'        => $notification->type,
                    'title'       => $notification->title,
                    'message'     => $notification->message,
                    'is_read'     => $notification->is_read,
                    'created_at'  => $notification->created_at,
                    'data'        => $data, // Kirim seluruh data JSON termasuk redirect URL
                    'penawaran'   => $notification->penawaran ? [
                        'id'         => $notification->penawaran->id,
                        'project_id' => $notification->penawaran->project_id,
                    ] : null,
                ];
            });

        $unreadCount = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count'  => $unreadCount,
        ]);
    }

    /**
     * Mark a single notification as read and return a redirect URL.
     */
    public function markRead(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        // Tandai sebagai sudah dibaca dengan timestamp
        $notification->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        // Ambil redirect URL dari kolom data (JSON)
        $data = $notification->data;
        $redirectUrl = is_array($data) && isset($data['redirect']) ? $data['redirect'] : null;

        // Fallback jika tidak ada redirect URL di data:
        if (!$redirectUrl) {
            $user = Auth::user();

            // Notifikasi terkait penawaran (offer.*)
            if ($notification->penawaran) {
                $projectId = $notification->penawaran->project_id;
                $redirectUrl = match ($user->role) {
                    'freelancer' => route('freelancer.projects.show', $projectId),
                    'company'    => route('company.projects.show', $projectId),
                    default      => url('/'),
                };
            }
            // Notifikasi terkait workspace
            elseif ($notification->workspace_id) {
                $redirectUrl = match ($user->role) {
                    'freelancer' => route('freelancer.workspaces.show', $notification->workspace_id),
                    'company'    => route('company.workspaces.show', $notification->workspace_id),
                    default      => url('/'),
                };
            }
            // Notifikasi terkait payment untuk admin
            elseif ($notification->payment_id && $user->role === 'admin') {
                $redirectUrl = route('admin.payments.show', $notification->payment_id);
            }
            // Notifikasi terkait company account request untuk admin
            elseif ($notification->company_account_request_id && $user->role === 'admin') {
                $redirectUrl = route('admin.company-account-requests.show', $notification->company_account_request_id);
            }
        }

        return response()->json([
            'redirect_url' => $redirectUrl ?? url('/'),
        ]);
    }

    /**
     * Mark all notifications as read for the current user.
     */
    public function markAllRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json(['success' => true]);
    }
}

