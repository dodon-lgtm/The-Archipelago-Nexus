<?php

use Illuminate\Support\Facades\Route;

// ─── ALL CONTROLLER IMPORTS ───────────────────
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\CompanyAccountRequestController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\WorkspaceController;
use App\Http\Controllers\Admin\CompanyAccountRequestAdminController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\PenawaranController as AdminPenawaranController;
use App\Http\Controllers\Admin\HasilPekerjaanController as AdminHasilPekerjaanController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Company\ProjectController as CompanyProjectController;
use App\Http\Controllers\Freelancer\DashboardController as FreelancerDashboardController;
use App\Http\Controllers\Freelancer\ProjectBrowseController;
use App\Http\Controllers\Freelancer\ProjectProposalController;
use App\Http\Controllers\Freelancer\DashboardController;
use App\Http\Controllers\Freelancer\SavedProjectController;



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


Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

Route::get('/freelancer/dashboard', [DashboardController::class, 'index'])
    ->name('freelancer.dashboard');

Route::get('/freelancer/projects', [ProjectBrowseController::class, 'index'])
    ->name('freelancer.projects.index');

Route::get('/freelancer/proyek', [ProjectBrowseController::class, 'index'])
    ->name('freelancer.proyek');

Route::get('/freelancer/projects/{project}', [ProjectBrowseController::class, 'show'])
    ->name('freelancer.projects.show');


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
        Route::get('/dashboard', [FreelancerDashboardController::class, 'index'])->name('dashboard');

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
        Route::get('/projects', [CompanyProjectController::class, 'index'])->name('projects.index');
        Route::get('/projects/create', [CompanyProjectController::class, 'create'])->name('projects.create');
        Route::post('/projects', [CompanyProjectController::class, 'store'])->name('projects.store');
        Route::get('/projects/{project}', [CompanyProjectController::class, 'show'])->name('projects.show');
        Route::get('/projects/{project}/edit', [CompanyProjectController::class, 'edit'])->name('projects.edit');
        Route::put('/projects/{project}', [CompanyProjectController::class, 'update'])->name('projects.update');
        Route::delete('/projects/{project}', [CompanyProjectController::class, 'destroy'])->name('projects.destroy');

        // Select freelancer
        Route::post('/projects/{project}/penawaran/{penawaran}/select', [CompanyProjectController::class, 'selectFreelancer'])
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

        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Users
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
        Route::post('/users/{user}/update-role', [AdminUserController::class, 'updateRole'])->name('users.update-role');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

        // Categories
        Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
        Route::put('/categories/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

        // Projects
        Route::get('/projects', [AdminProjectController::class, 'index'])->name('projects.index');
        Route::get('/projects/{project}', [AdminProjectController::class, 'show'])->name('projects.show');
        Route::delete('/projects/{project}', [AdminProjectController::class, 'destroy'])->name('projects.destroy');

        // Penawarans
        Route::get('/penawarans', [AdminPenawaranController::class, 'index'])->name('penawarans.index');
        Route::get('/penawarans/{penawaran}', [AdminPenawaranController::class, 'show'])->name('penawarans.show');

        // Hasil Pekerjaan (Workspaces)
        Route::get('/hasil-pekerjaan', [AdminHasilPekerjaanController::class, 'index'])->name('hasil-pekerjaan.index');
        Route::get('/hasil-pekerjaan/{workspace}', [AdminHasilPekerjaanController::class, 'show'])->name('hasil-pekerjaan.show');

        // Reports
        Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/{report}', [AdminReportController::class, 'show'])->name('reports.show');
        Route::post('/reports/{report}/update-status', [AdminReportController::class, 'updateStatus'])->name('reports.update-status');

        // Company Account Requests (existing - preserved)
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
