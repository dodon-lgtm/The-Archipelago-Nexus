<?php

use Illuminate\Support\Facades\Route;

// ─── GENERAL CONTROLLERS ─────────────────────────
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\WorkspaceController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProjectSubmissionController; // Di-import dari branch incoming

// ─── ADMIN CONTROLLERS ───────────────────────────
use App\Http\Controllers\Admin\CompanyAccountRequestAdminController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\PenawaranController as AdminPenawaranController;
use App\Http\Controllers\Admin\HasilPekerjaanController as AdminHasilPekerjaanController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;

// ─── COMPANY CONTROLLERS ─────────────────────────
use App\Http\Controllers\Company\ProjectController as CompanyProjectController;
use App\Http\Controllers\Company\ProfilController as CompanyProfilController;
use App\Http\Controllers\Company\PaymentController as CompanyPaymentController;
use App\Http\Controllers\Company\ReportController as CompanyReportController;

// ─── FREELANCER CONTROLLERS ──────────────────────
use App\Http\Controllers\Freelancer\PendapatanController as FreelancerPendapatanController;
use App\Http\Controllers\Freelancer\ReportController as FreelancerReportController;

// ─── FREELANCER CONTROLLERS ──────────────────────
use App\Http\Controllers\Freelancer\DashboardController as FreelancerDashboardController;
use App\Http\Controllers\Freelancer\ProjectBrowseController;
use App\Http\Controllers\Freelancer\ProjectProposalController;
use App\Http\Controllers\Freelancer\SavedProjectController;
use App\Http\Controllers\Freelancer\ProjectOfferController;
use App\Http\Controllers\Freelancer\ProfilController as FreelancerProfilController;
use App\Http\Controllers\review\ReviewController;
use App\Http\Controllers\ReportController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/register', [RegisterController::class, 'showRegister'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// ──────────────────────────────────────────────
// LANDING PAGE
// ──────────────────────────────────────────────
Route::get('/', function () {
    $recentProjects = \App\Models\Project::with(['category', 'owner'])
        ->where('status', 'Open')
        ->latest()
        ->take(6)
        ->get();

    $categories = \App\Models\Category::orderBy('name')->get();

    $totalProjects     = \App\Models\Project::count();
    $totalFreelancers  = \App\Models\User::where('role', 'freelancer')->count();
    $totalCompanies    = \App\Models\User::where('role', 'company')->count();
    $totalProjectsCompleted = \App\Models\Project::where('status', 'Closed')->count();

    return view('landingpage', compact(
        'recentProjects',
        'categories',
        'totalProjects',
        'totalFreelancers',
        'totalCompanies',
        'totalProjectsCompleted'
    ));
})->name('landing');


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

        // Submissions
        Route::post('/workspaces/{workspace}/submissions', [ProjectSubmissionController::class, 'store'])
            ->name('workspaces.submissions.store');

        // Profile
        Route::get('/profile', [FreelancerProfilController::class, 'profile'])
            ->name('profile');
        Route::get('/profile/edit', [FreelancerProfilController::class, 'editProfile'])
            ->name('profile.edit');
        Route::post('/profile/update', [FreelancerProfilController::class, 'updateProfile'])
            ->name('profile.update');

        // Pendapatan
        Route::get('/pendapatan', [FreelancerPendapatanController::class, 'index'])
            ->name('pendapatan.index');

        // Reports
        Route::get('/reports', [FreelancerReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/create', [FreelancerReportController::class, 'create'])->name('reports.create');
        Route::post('/reports', [FreelancerReportController::class, 'store'])->name('reports.store');
        Route::get('/reports/{report}', [FreelancerReportController::class, 'show'])->name('reports.show');
    });

// ──────────────────────────────────────────────
// COMPANY ROUTES (auth + ensureCompanyAdminOrAbort)
// ──────────────────────────────────────────────
Route::middleware(['auth', 'ensureCompanyAdminOrAbort'])->prefix('company')->name('company.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', function () {
            $userId = Auth::id();

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
        Route::get('/client/project/{project}/review', [ReviewController::class, 'create'])->name('client.review.create');
        Route::post('/client/project/{project}/review', [ReviewController::class, 'store'])->name('client.review.store');
        
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
        // Profile Freelancer (Read-only untuk Company)
        Route::get('/freelancer-profile/{id}', [FreelancerProfilController::class, 'profile'])->name('freelancer.profile');


// Submissions
        Route::post('/workspaces/{workspace}/submissions/{submission}/accept', [ProjectSubmissionController::class, 'accept'])
            ->name('workspaces.submissions.accept');
        Route::post('/workspaces/{workspace}/submissions/{submission}/revision', [ProjectSubmissionController::class, 'requestRevision'])
            ->name('workspaces.submissions.revision');

        // Payment
Route::post('/workspaces/{workspace}/payment/upload', [CompanyPaymentController::class, 'uploadProof'])
            ->name('payments.upload');

// Profile
        Route::get('/profile', [CompanyProfilController::class, 'profile'])
            ->name('profile');
        Route::get('/profile/edit', [CompanyProfilController::class, 'editProfile'])
            ->name('profile.edit');
        Route::post('/profile/update', [CompanyProfilController::class, 'updateProfile'])
            ->name('profile.update');

        // Reports
        Route::get('/reports', [CompanyReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/create', [CompanyReportController::class, 'create'])->name('reports.create');
        Route::post('/reports', [CompanyReportController::class, 'store'])->name('reports.store');
        Route::get('/reports/{report}', [CompanyReportController::class, 'show'])->name('reports.show');
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

        // Company Account Requests
        Route::get('/company-account-requests', [CompanyAccountRequestAdminController::class, 'index'])
            ->name('company-account-requests.index');
        Route::get('/company-account-requests/{request}', [CompanyAccountRequestAdminController::class, 'show'])
            ->name('company-account-requests.show');
        Route::post('/company-account-requests/{companyRequest}/approve', [CompanyAccountRequestAdminController::class, 'approve'])
            ->name('company-account-requests.approve');
        Route::post('/company-account-requests/{companyRequest}/reject', [CompanyAccountRequestAdminController::class, 'reject'])
            ->name('company-account-requests.reject');

        // Payments
        Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
        Route::get('/payments/{payment}', [AdminPaymentController::class, 'show'])->name('payments.show');
        Route::post('/payments/{payment}/verify', [AdminPaymentController::class, 'verify'])->name('payments.verify');
        Route::post('/payments/{payment}/reject', [AdminPaymentController::class, 'reject'])->name('payments.reject');
    });

// ──────────────────────────────────────────────
// REPORTS (auth only - for any authenticated user)
// ──────────────────────────────────────────────
Route::middleware('auth')->prefix('reports')->name('reports.')->group(function () {
    Route::get('/create', [ReportController::class, 'create'])->name('create');
    Route::post('/', [ReportController::class, 'store'])->name('store');
});

// ──────────────────────────────────────────────
// NOTIFICATIONS (auth only - for any authenticated user)
// ──────────────────────────────────────────────
Route::middleware('auth')->prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', [NotificationController::class, 'index'])->name('index');
    Route::post('/{notification}/read', [NotificationController::class, 'markRead'])->name('mark-read');
    Route::post('/mark-all-read', [NotificationController::class, 'markAllRead'])->name('mark-all-read');
});
