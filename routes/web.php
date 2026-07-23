<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyAccountRequestController;
use App\Http\Controllers\Admin\CompanyAccountRequestAdminController;
use App\Http\Controllers\Company\ProjectController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Freelancer\ProjectBrowseController;
use App\Http\Controllers\Freelancer\ProjectProposalController;
use App\Http\Controllers\Freelancer\DashboardController;



Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

use App\Http\Controllers\RegisterController;
use App\Models\Penawaran;

Route::get('/register', [RegisterController::class, 'showRegister'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);




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

Route::middleware(['auth', 'ensureCompanyAdminOrAbort'])->prefix('company')->name('company.')
    ->group(function () {

        Route::get('/dashboard', function () {
            $userId = auth()->id();

            $totalProjects = \App\Models\Project::where('user_id', $userId)->count();
            $activeProjects = \App\Models\Project::where('user_id', $userId)->where('status', 'Open')->count();
            $recentProjects = \App\Models\Project::where('user_id', $userId)->latest()->take(5)->get();

            // Total freelancer yang sedang bekerja (penawaran Diterima dari project milik company)
            $activeFreelancers = \App\Models\Penawaran::whereHas('project', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })->where('status', 'Diterima')->count();

            // Total pengeluaran dari penawaran yang diterima
            $totalSpending = \App\Models\Penawaran::whereHas('project', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })->where('status', 'Diterima')->sum('harga_penawaran');

            // Proposal masuk (penawaran terbaru di project milik company)
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

        Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
        Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
        Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');

        Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
        Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
        Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
        Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');

        Route::post('/projects/{project}/penawaran/{penawaran}/select', [ProjectController::class, 'selectFreelancer'])
            ->name('projects.penawaran.select');
    });
  // Penawaran & Saved Projects
            Route::middleware('auth')->prefix('freelancer')->name('freelancer.')->group(function () {

    Route::get('/projects/{project}/penawaran', [ProjectBrowseController::class, 'create'])
        ->name('penawaran.create');

    Route::post('/projects/{project}/penawaran', [ProjectBrowseController::class, 'store'])
        ->name('penawaran.store');

    Route::get('/lamaran', [\App\Http\Controllers\Freelancer\ProjectOfferController::class, 'index'])
        ->name('lamaran');

    // Saved Projects
    Route::get('/simpan', [\App\Http\Controllers\Freelancer\SavedProjectController::class, 'index'])
        ->name('saved-projects.index');

    Route::post('/projects/{project}/simpan', [\App\Http\Controllers\Freelancer\SavedProjectController::class, 'store'])
        ->name('saved-projects.store');

    Route::delete('/projects/{project}/simpan', [\App\Http\Controllers\Freelancer\SavedProjectController::class, 'destroy'])
        ->name('saved-projects.destroy');

});

Route::prefix('admin')
    ->name('admin.')
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

// Notifikasi (hanya untuk authenticated users, terutama company)
use App\Http\Controllers\NotificationController;
Route::middleware('auth')->prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', [NotificationController::class, 'index'])->name('index');
    Route::post('/{notification}/read', [NotificationController::class, 'markRead'])->name('mark-read');
    Route::post('/mark-all-read', [NotificationController::class, 'markAllRead'])->name('mark-all-read');
});
