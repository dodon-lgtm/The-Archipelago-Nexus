<?php

namespace App\Http\Controllers\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Penawaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProjectOfferController extends Controller
{
    /**
     * Display a listing of the freelancer's project offers/lamaran.
     */
    public function index(Request $request): View
    {
        $lamaran = Penawaran::with([
            'project',
            'project.category',
            'project.owner',
            'project.workspace',
        ])
            ->where('freelancer_id', Auth::id())
            ->latest()
            ->paginate(10);

        // Jumlah pesan negosiasi unread per lamaran (satu query agregat,
        // diambil dari tabel notifications yang sudah ada — bukan sistem baru).
        $penawaranIds = $lamaran->getCollection()->pluck('id');
        $negoUnread = Notification::query()
            ->where('user_id', Auth::id())
            ->where('type', 'negotiation.message')
            ->where('is_read', false)
            ->whereIn('penawaran_id', $penawaranIds)
            ->selectRaw('penawaran_id, COUNT(*) as total')
            ->groupBy('penawaran_id')
            ->pluck('total', 'penawaran_id');

        return view('freelancer.lamaran', compact('lamaran', 'negoUnread'));
    }

    /**
     * Membatalkan penawaran freelancer.
     *
     * Hanya penawaran berstatus "Menunggu" yang dapat dibatalkan.
     * Penawaran yang sudah "Diterima" / "Ditolak" tidak dapat dibatalkan
     * karena proses seleksi perusahaan sudah selesai.
     */
    public function destroy(Penawaran $penawaran): RedirectResponse
    {
        // Pastikan penawaran milik freelancer yang sedang login
        abort_unless((int) $penawaran->freelancer_id === (int) Auth::id(), 403);

        // Penawaran hanya bisa dibatalkan jika status masih "Menunggu"
        if ($penawaran->status !== 'Menunggu') {
            return redirect()
                ->route('freelancer.lamaran')
                ->with('error', 'Penawaran tidak dapat dibatalkan karena sudah diproses perusahaan.');
        }

        // Hapus file proposal dari storage (jika ada)
        if ($penawaran->proposal) {
            Storage::disk('public')->delete($penawaran->proposal);
        }

        // Hapus record penawaran
        $penawaran->delete();

        return redirect()
            ->route('freelancer.lamaran')
            ->with('success', 'Penawaran berhasil dibatalkan. Anda dapat mengirim penawaran baru pada proyek ini.');
    }
}

