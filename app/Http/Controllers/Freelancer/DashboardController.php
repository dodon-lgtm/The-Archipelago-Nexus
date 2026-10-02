<?php

namespace App\Http\Controllers\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Project;
use App\Models\Penawaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->search;
        $categoryId = $request->category_id;

        // Pekerjaan Terbaru: HANYA proyek berstatus 'open' DAN belum memiliki freelancer terpilih.
        // - Closed / Archived otomatis terbuang oleh filter status di bawah.
        // - Freelancer "sudah dipilih" ditandai dengan adanya record Workspace di tabel
        //   `project_workspaces` (dibuat saat Company menekan Pilih/Terima di selectFreelancer).
        //   Proyek dengan workspace apa pun (aktif / menunggu bayar / selesai) tidak boleh
        //   lagi tampil sebagai pekerjaan yang tersedia; freelancer terpilih mengaksesnya
        //   lewat halaman Workspace, bukan lewat Pekerjaan Terbaru.
        $query = Project::with('category', 'owner')
            ->where('status', Project::STATUS_OPEN)
            ->whereDoesntHave('workspace')
            ->latest();

        if ($search) {
            $query->where('project_name', 'like', "%$search%");
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $projects = $query->paginate(10)->withQueryString();

        $categories = Category::orderBy('name')->get();

        $latestApplications = Penawaran::with('project')
            ->where('freelancer_id', Auth::id())
            ->latest()
            ->take(4)
            ->get();

        $lamaranCount = Penawaran::where('freelancer_id', Auth::id())->count();

        $savedCount = \App\Models\SavedProject::where('freelancer_id', Auth::id())->count();

        // Indikator "Pesan Baru": total pesan masuk (chat dari lawan bicara,
        // bukan pesan sistem) pada seluruh workspace milik freelancer yang
        // belum dibaca. Kolom is_read pada tabel `messages` diisi true saat
        // freelancer membuka room chat di halaman Workspace.
        $unreadMessagesCount = Message::unreadIncomingFor((int) Auth::id())->count();

        return view('freelancer.dashboard', compact(
            'projects',
            'categories',
            'search',
            'categoryId',
            'latestApplications',
            'lamaranCount',
            'savedCount',
            'unreadMessagesCount'
        ));
    }
}