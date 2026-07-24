<?php

use Illuminate\Support\Facades\Route;

// ─── ALL CONTROLLER IMPORTS ───────────────────
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\CompanyAccountRequestController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\WorkspaceController;
use App\Http\Controllers\Admin\CompanyAccountRequestAdminController;
use App\Http\Controllers\Company\ProjectController;
use App\Http\Controllers\Freelancer\DashboardController;
use App\Http\Controllers\Freelancer\ProjectBrowseController;
use App\Http\Controllers\Freelancer\ProjectOfferController;
use App\Http\Controllers\Freelancer\SavedProjectController;

// ──────────────────────────────────────────────
// AUTH (login/register/logout) - PUBLIC
// ──────────────────────────────────────────────
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/register', [RegisterController::class, 'showRegister'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// ──────────────────────────────────────────────
// LANDING PAGE
// ──────────────────────────────────────────────
Route::get('/', function () {
    return view('landingpage');
})->name('landing');

// ──────────────────────────────────────────────
// COMPANY ACCOUNT REQUEST - PUBLIC
// ──────────────────────────────────────────────
Route::get('/company-account-requests/create', [CompanyAccountRequestController::class, 'create'])
    ->name('company-account-requests.create');

Route::post('/company-account-requests', [CompanyAccountRequestController::class, 'store'])
    ->name('company-account-requests.store');

// ──────────────────────────────────────────────
// FREELANCER ROUTES (auth + ensureFreelancer)
// ──────────────────────────────────────────────
Route::middleware(['auth', 'ensureFreelancer'])->prefix('freelancer')->name('freelancer.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Projects browsing
        Route::get('/projects', [ProjectBrowseController::class, 'index'])->name('projects.index');
        Route::get('/proyek', [ProjectBrowseController::class, 'index'])->name('proyek');
        Route::get('/projects/{project}', [ProjectBrowseController::class, 'show'])->name('projects.show');

        // Penawaran (offer)
        Route::get('/projects/{project}/penawaran', [ProjectBrowseController::class, 'create'])
            ->name('penawaran.create');
        Route::post('/projects/{project}/penawaran', [ProjectBrowseController::class, 'store'])
            ->name('penawaran.store');

        // Lamaran list
        Route::get('/lamaran', [ProjectOfferController::class, 'index'])->name('lamaran');

        // Saved Projects
        Route::get('/simpan', [SavedProjectController::class, 'index'])->name('saved-projects.index');
        Route::post('/projects/{project}/simpan', [SavedProjectController::class, 'store'])
            ->name('saved-projects.store');
        Route::delete('/projects/{project}/simpan', [SavedProjectController::class, 'destroy'])
            ->name('saved-projects.destroy');

        // Workspace
        Route::get('/workspaces', [WorkspaceController::class, 'freelancerIndex'])
            ->name('workspaces.index');
        Route::get('/workspaces/{workspace}', [WorkspaceController::class, 'show'])
            ->name('workspaces.show');
        Route::post('/workspaces/{workspace}/message', [WorkspaceController::class, 'sendMessage'])
            ->name('workspaces.message');
        Route::post('/workspaces/{workspace}/progress', [WorkspaceController::class, 'updateProgress'])
            ->name('workspaces.progress');
    });

// ──────────────────────────────────────────────
// COMPANY ROUTES (auth + ensureCompanyAdminOrAbort)
// ──────────────────────────────────────────────
Route::middleware(['auth', 'ensureCompanyAdminOrAbort'])->prefix('company')->name('company.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', function () {
            $userId = auth()->id();

            $totalProjects = \App\Models\Project::where('user_id', $userId)->count();
            $activeProjects = \App\Models\Project::where('user_id', $userId)->where('status', 'Open')->count();
            $recentProjects = \App\Models\Project::where('user_id', $userId)->latest()->take(5)->get();

            $activeFreelancers = \App\Models\Penawaran::whereHas('project', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })->where('status', 'Diterima')->count();

            $totalSpending = \App\Models\Penawaran::whereHas('project', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })->where('status', 'Diterima')->sum('harga_penawaran');

            $incomingProposals = \App\Models\Penawaran::whereHas('project', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })->with(['project', 'freelancer'])->latest()->take(10)->get();

            return view('company.dashboard', compact(
                'totalProjects',
                'activeProjects',
                'activeFreelancers',
                'totalSpending',
                'recentProjects',
                'incomingProposals'
            ));
        })->name('dashboard');

        // Projects CRUD
        Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
        Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
        Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
        Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
        Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
        Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
        Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');

        // Select freelancer
        Route::post('/projects/{project}/penawaran/{penawaran}/select', [ProjectController::class, 'selectFreelancer'])
            ->name('projects.penawaran.select');

        // Workspace
        Route::get('/workspaces', [WorkspaceController::class, 'companyIndex'])
            ->name('workspaces.index');
        Route::get('/workspaces/{workspace}', [WorkspaceController::class, 'show'])
            ->name('workspaces.show');
        Route::post('/workspaces/{workspace}/message', [WorkspaceController::class, 'sendMessage'])
            ->name('workspaces.message');
        Route::post('/workspaces/{workspace}/complete', [WorkspaceController::class, 'complete'])
            ->name('workspaces.complete');
    });

// ──────────────────────────────────────────────
// ADMIN ROUTES (auth + ensureAdmin)
// ──────────────────────────────────────────────
Route::middleware(['auth', 'ensureAdmin'])->prefix('admin')->name('admin.')
    ->group(function () {

        Route::get('/dashboard', function () {
            return redirect()->route('admin.company-account-requests.index');
        })->name('dashboard');

        Route::get('/company-account-requests', [CompanyAccountRequestAdminController::class, 'index'])
            ->name('company-account-requests.index');

        Route::get('/company-account-requests/{request}', [CompanyAccountRequestAdminController::class, 'show'])
            ->name('company-account-requests.show');

        Route::post('/company-account-requests/{companyRequest}/approve', [CompanyAccountRequestAdminController::class, 'approve'])
            ->name('company-account-requests.approve');

        Route::post('/company-account-requests/{companyRequest}/reject', [CompanyAccountRequestAdminController::class, 'reject'])
            ->name('company-account-requests.reject');
    });

// ──────────────────────────────────────────────
// NOTIFICATIONS (auth only - for any authenticated user)
// ──────────────────────────────────────────────
Route::middleware('auth')->prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', [NotificationController::class, 'index'])->name('index');
    Route::post('/{notification}/read', [NotificationController::class, 'markRead'])->name('mark-read');
    Route::post('/mark-all-read', [NotificationController::class, 'markAllRead'])->name('mark-all-read');
});

