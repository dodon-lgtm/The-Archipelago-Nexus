<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// ─── GENERAL CONTROLLERS ─────────────────────────
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\WorkspaceController;
use App\Http\Controllers\ProjectSubmissionController;
use App\Http\Controllers\review\ReviewController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\Freelancer\ProjectController;
use App\Http\Controllers\NegotiationController;

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
use App\Http\Controllers\Admin\WithdrawalController as AdminWithdrawalController;
use App\Http\Controllers\Admin\WalletController as AdminWalletController;
use App\Http\Controllers\Admin\PolicyController as AdminPolicyController;
use App\Http\Controllers\Admin\FooterSettingController as AdminFooterSettingController;
use App\Http\Controllers\LegalPageController;
use App\Http\Controllers\Admin\ResolutionController as AdminResolutionController;

// ─── COMPANY CONTROLLERS ─────────────────────────
use App\Http\Controllers\Company\ProjectController as CompanyProjectController;
use App\Http\Controllers\Company\ProfilController as CompanyProfilController;
use App\Http\Controllers\Company\PaymentController as CompanyPaymentController;
use App\Http\Controllers\Company\ReportController as CompanyReportController;

// ─── FREELANCER CONTROLLERS ──────────────────────
use App\Http\Controllers\Freelancer\PendapatanController as FreelancerPendapatanController;
use App\Http\Controllers\Freelancer\ReportController as FreelancerReportController;
use App\Http\Controllers\Freelancer\DashboardController as FreelancerDashboardController;
use App\Http\Controllers\Freelancer\ProjectBrowseController;
use App\Http\Controllers\Freelancer\ProjectProposalController;
use App\Http\Controllers\Freelancer\SavedProjectController;
use App\Http\Controllers\Freelancer\ProjectOfferController;
use App\Http\Controllers\Freelancer\ProfilController as FreelancerProfilController;
use App\Http\Controllers\Freelancer\WithdrawalController as FreelancerWithdrawalController;

// ──────────────────────────────────────────────
// AUTH / GUEST
// ──────────────────────────────────────────────
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
Route::get('/register', [RegisterController::class, 'showRegister'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login/google', [AuthController::class, 'handleGoogleCallback'])->name('login.google');

// ──────────────────────────────────────────────
// FORGOT PASSWORD WITH OTP
// ──────────────────────────────────────────────
Route::get('/forgot-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showRequestForm'])->name('password.request');
Route::post('/forgot-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendOtp'])->name('password.email');
Route::get('/verify-otp', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showVerifyForm'])->name('password.verify');
Route::post('/verify-otp', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'verifyOtp'])->name('password.verify.submit');
Route::post('/resend-otp', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'resendOtp'])->name('password.resend');
Route::get('/reset-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'resetPassword'])->name('password.reset.submit');

// ──────────────────────────────────────────────
// PUSAT BANTUAN & PUBLIC
// ──────────────────────────────────────────────
Route::get('/kebijakan-privasi', [LegalPageController::class, 'privacyPolicy'])->name('kebijakan-privasi');
Route::get('/syarat-ketentuan', [LegalPageController::class, 'termsConditions'])->name('syarat-ketentuan');
Route::get('/pusat-bantuan', [\App\Http\Controllers\HelpCenterController::class, 'index'])->name('help.index');
Route::post('/pusat-bantuan/kontak', [\App\Http\Controllers\HelpCenterController::class, 'storeContact'])->name('help.contact');

// ──────────────────────────────────────────────
// LANDING PAGE
// ──────────────────────────────────────────────
Route::get('/', function () {
    $recentProjects = \App\Models\Project::with(['category', 'owner'])->whereIn('status', ['open', 'OPEN', 'Open'])->latest('created_at')->take(6)->get();
    $categories = \App\Models\Category::orderBy('name')->get();
    $totalProjects = \App\Models\Project::count();
    $totalFreelancers = \App\Models\User::where('role', 'freelancer')->count();
    $totalCompanies = \App\Models\User::where('role', 'company')->count();
    $totalProjectsCompleted = \App\Models\Workspace::where('status', 'Selesai')->count();

    return view('landingpage', compact('recentProjects', 'categories', 'totalProjects', 'totalFreelancers', 'totalCompanies', 'totalProjectsCompleted'));
})->name('landing');

// ──────────────────────────────────────────────
// PUBLIC PROJECT DETAIL
// ──────────────────────────────────────────────
Route::get('/proyek/{project}', [ProjectBrowseController::class, 'publicShow'])->name('projects.public.show');

// ──────────────────────────────────────────────
// FREELANCER ROUTES
// ──────────────────────────────────────────────
Route::middleware(['auth', 'ensureFreelancer'])->prefix('freelancer')->name('freelancer.')->group(function () {
    Route::get('/dashboard', [FreelancerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/projects', [ProjectBrowseController::class, 'index'])->name('projects.index');
    Route::get('/proyek', [ProjectBrowseController::class, 'index'])->name('proyek');
    Route::get('/projects/{project}', [ProjectBrowseController::class, 'show'])->name('projects.show');
    Route::get('/projects/{project}/penawaran', [ProjectBrowseController::class, 'create'])->name('penawaran.create');
    Route::post('/projects/{project}/penawaran', [ProjectBrowseController::class, 'store'])->name('penawaran.store');
    Route::get('/lamaran', [ProjectOfferController::class, 'index'])->name('lamaran');
    Route::delete('/penawaran/{penawaran}', [ProjectOfferController::class, 'destroy'])->name('penawaran.destroy');
    Route::get('/simpan', [SavedProjectController::class, 'index'])->name('saved-projects.index');
    Route::post('/projects/{project}/simpan', [SavedProjectController::class, 'store'])->name('saved-projects.store');
    Route::delete('/projects/{project}/simpan', [SavedProjectController::class, 'destroy'])->name('saved-projects.destroy');
    Route::get('/workspaces', [WorkspaceController::class, 'freelancerIndex'])->name('workspaces.index');
    Route::get('/workspaces/{workspace}', [WorkspaceController::class, 'show'])->name('workspaces.show');
    Route::post('/workspaces/{workspace}/message', [WorkspaceController::class, 'sendMessage'])->name('workspaces.message');
    Route::post('/workspaces/{workspace}/progress', [WorkspaceController::class, 'updateProgress'])->name('workspaces.progress');
    Route::post('/workspaces/{workspace}/submissions', [ProjectSubmissionController::class, 'store'])->name('workspaces.submissions.store');
    Route::get('/profile', [FreelancerProfilController::class, 'profile'])->name('profile');
    Route::get('/profile/edit', [FreelancerProfilController::class, 'editProfile'])->name('profile.edit');
    Route::post('/profile/update', [FreelancerProfilController::class, 'updateProfile'])->name('profile.update');
    Route::get('/pendapatan', [FreelancerPendapatanController::class, 'index'])->name('pendapatan.index');
    Route::post('/withdrawals', [FreelancerWithdrawalController::class, 'store'])->name('withdrawals.store');
    Route::get('/reports', [FreelancerReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/create', [FreelancerReportController::class, 'create'])->name('reports.create');
    Route::post('/reports', [FreelancerReportController::class, 'store'])->name('reports.store');
    Route::get('/reports/{report}', [FreelancerReportController::class, 'show'])->name('reports.show');
    Route::post('/reports/{report}/evidence', [FreelancerReportController::class, 'uploadEvidence'])->name('reports.evidence');
});

// ──────────────────────────────────────────────
// NEGOTIATIONS
// ──────────────────────────────────────────────
Route::middleware(['auth'])->prefix('negotiations')->name('negotiations.')->group(function () {
    Route::get('/{penawaran}', [NegotiationController::class, 'getMessages'])->name('messages');
    Route::post('/{penawaran}/send', [NegotiationController::class, 'sendMessage'])->name('send');
    Route::post('/{penawaran}/{negotiation}/accept', [NegotiationController::class, 'acceptNegotiation'])->name('accept');
    Route::post('/{penawaran}/{negotiation}/reject', [NegotiationController::class, 'rejectNegotiation'])->name('reject');
});

// ──────────────────────────────────────────────
// COMPANY ROUTES
// ──────────────────────────────────────────────
Route::middleware(['auth', 'ensureCompanyAdminOrAbort'])->prefix('company')->name('company.')->group(function () {
    Route::get('/dashboard', function () {
        $userId = Auth::id();
        $totalProjects = \App\Models\Project::where('user_id', $userId)->count();
        $activeProjects = \App\Models\Project::where('user_id', $userId)->where('status', 'open')->count();
        $recentProjects = \App\Models\Project::where('user_id', $userId)->latest()->take(5)->get();
        $activeFreelancers = \App\Models\Penawaran::whereHas('project', fn($q) => $q->where('user_id', $userId))->where('status', 'Diterima')->count();
        $totalSpending = (float) \App\Models\Payment::where('company_id', $userId)->where('status', 'paid')->sum('amount');
        $incomingProposals = \App\Models\Penawaran::whereHas('project', fn($q) => $q->where('user_id', $userId))->with(['project', 'freelancer'])->latest()->take(10)->get();

        return view('company.dashboard', compact('totalProjects', 'activeProjects', 'activeFreelancers', 'totalSpending', 'recentProjects', 'incomingProposals'));
    })->name('dashboard');

    Route::get('/projects', [CompanyProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/archive', [CompanyProjectController::class, 'archiveIndex'])->name('projects.archive');
    Route::get('/projects/create', [CompanyProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [CompanyProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/{project}', [CompanyProjectController::class, 'show'])->name('projects.show');
    Route::get('/projects/{project}/edit', [CompanyProjectController::class, 'edit'])->name('projects.edit');
    Route::put('/projects/{project}', [CompanyProjectController::class, 'update'])->name('projects.update');
    Route::post('/projects/{project}/close', [CompanyProjectController::class, 'close'])->name('projects.close');
    Route::post('/projects/{project}/archive', [CompanyProjectController::class, 'archive'])->name('projects.archive-project');
    Route::post('/projects/{project}/activate', [CompanyProjectController::class, 'activate'])->name('projects.activate');
    Route::post('/projects/{project}/deactivate', [CompanyProjectController::class, 'deactivate'])->name('projects.deactivate');
    Route::delete('/projects/{project}', [CompanyProjectController::class, 'destroy'])->name('projects.destroy');

    Route::get('/quota/payment/info', [CompanyPaymentController::class, 'quotaPaymentInfo'])->name('quota.payment.info');
    Route::get('/quota-payment/start', [CompanyPaymentController::class, 'startQuotaPayment'])->name('quota.payment.start');
    Route::get('/quota-payment/{payment}', [CompanyPaymentController::class, 'showQuotaGateway'])->name('quota.payment.show');
    Route::get('/quota-payment/{payment}/status', [CompanyPaymentController::class, 'quotaPaymentStatus'])->name('quota.payment.status');
    Route::post('/quota-payment/{payment}/midtrans', [CompanyPaymentController::class, 'createQuotaTransaction'])->name('quota.payment.midtrans');

    Route::get('/client/project/{project}/review', [ReviewController::class, 'create'])->name('client.review.create');
    Route::post('/client/project/{project}/review', [ReviewController::class, 'store'])->name('client.review.store');
    Route::post('/projects/{project}/penawaran/{penawaran}/select', [CompanyProjectController::class, 'selectFreelancer'])->name('projects.penawaran.select');

    Route::get('/workspaces', [WorkspaceController::class, 'companyIndex'])->name('workspaces.index');
    Route::get('/workspaces/{workspace}', [WorkspaceController::class, 'show'])->name('workspaces.show');
    Route::post('/workspaces/{workspace}/message', [WorkspaceController::class, 'sendMessage'])->name('workspaces.message');
    Route::post('/workspaces/{workspace}/progress', [WorkspaceController::class, 'updateProgress'])->name('workspaces.progress');
    Route::get('/freelancer-profile/{id}', [FreelancerProfilController::class, 'profile'])->name('freelancer.profile');

    Route::post('/workspaces/{workspace}/submissions/{submission}/accept', [ProjectSubmissionController::class, 'accept'])->name('workspaces.submissions.accept');
    Route::post('/workspaces/{workspace}/submissions/{submission}/revision', [ProjectSubmissionController::class, 'requestRevision'])->name('workspaces.submissions.revision');

    Route::get('/workspaces/{workspace}/payment/gateway', [CompanyPaymentController::class, 'showGateway'])->name('payments.gateway');
    Route::get('/workspaces/{workspace}/payment/upload', [CompanyPaymentController::class, 'showUploadForm'])->name('payments.upload-form');
    Route::post('/workspaces/{workspace}/payment/upload', [CompanyPaymentController::class, 'uploadProof'])->name('payments.upload');
    Route::post('/workspaces/{workspace}/payment/midtrans', [CompanyPaymentController::class, 'createMidtransTransaction'])->name('payments.midtrans');
    Route::post('/workspaces/{workspace}/payment/confirm', [CompanyPaymentController::class, 'confirmPayment'])->name('payments.confirm');

    Route::get('/profile', [CompanyProfilController::class, 'profile'])->name('profile');
    Route::get('/profile/edit', [CompanyProfilController::class, 'editProfile'])->name('profile.edit');
    Route::post('/profile/update', [CompanyProfilController::class, 'updateProfile'])->name('profile.update');

    Route::get('/reports', [CompanyReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/create', [CompanyReportController::class, 'create'])->name('reports.create');
    Route::post('/reports', [CompanyReportController::class, 'store'])->name('reports.store');
    Route::get('/reports/{report}', [CompanyReportController::class, 'show'])->name('reports.show');
    Route::post('/reports/{report}/evidence', [CompanyReportController::class, 'uploadEvidence'])->name('reports.evidence');
});

// ──────────────────────────────────────────────
// ADMIN ROUTES
// ──────────────────────────────────────────────
Route::middleware(['auth', 'ensureAdmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::post('/users/{user}/update-role', [AdminUserController::class, 'updateRole'])->name('users.update-role');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('/policies', [AdminPolicyController::class, 'index'])->name('policies.index');
    Route::get('/policies/{policy}/edit', [AdminPolicyController::class, 'edit'])->name('policies.edit');
    Route::put('/policies/{policy}', [AdminPolicyController::class, 'update'])->name('policies.update');

    Route::get('/footer-settings/edit', [AdminFooterSettingController::class, 'edit'])->name('footer-settings.edit');
    Route::put('/footer-settings', [AdminFooterSettingController::class, 'update'])->name('footer-settings.update');

    Route::get('/projects', [AdminProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/{project}', [AdminProjectController::class, 'show'])->name('projects.show');
    Route::delete('/projects/{project}', [AdminProjectController::class, 'destroy'])->name('projects.destroy');

    Route::get('/penawarans', [AdminPenawaranController::class, 'index'])->name('penawarans.index');
    Route::get('/penawarans/{penawaran}', [AdminPenawaranController::class, 'show'])->name('penawarans.show');

    Route::get('/hasil-pekerjaan', [AdminHasilPekerjaanController::class, 'index'])->name('hasil-pekerjaan.index');
    Route::get('/hasil-pekerjaan/{workspace}', [AdminHasilPekerjaanController::class, 'show'])->name('hasil-pekerjaan.show');

    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{report}', [AdminReportController::class, 'show'])->name('reports.show');
    Route::post('/reports/{report}/update-status', [AdminReportController::class, 'updateStatus'])->name('reports.update-status');
    Route::post('/reports/{report}/destroy-project', [AdminReportController::class, 'destroyProject'])->name('reports.destroy-project');
    Route::post('/reports/{report}/destroy-penawaran', [AdminReportController::class, 'destroyPenawaran'])->name('reports.destroy-penawaran');

    Route::get('/company-account-requests', [CompanyAccountRequestAdminController::class, 'index'])->name('company-account-requests.index');
    Route::get('/company-account-requests/{request}', [CompanyAccountRequestAdminController::class, 'show'])->name('company-account-requests.show');
    Route::post('/company-account-requests/{companyRequest}/approve', [CompanyAccountRequestAdminController::class, 'approve'])->name('company-account-requests.approve');
    Route::post('/company-account-requests/{companyRequest}/reject', [CompanyAccountRequestAdminController::class, 'reject'])->name('company-account-requests.reject');

    Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/export-pdf', [AdminPaymentController::class, 'exportPdfAll'])->name('payments.pdf.all');
    Route::get('/payments/{payment}/export-pdf', [AdminPaymentController::class, 'exportPdfSingle'])->name('payments.pdf.single');
    Route::get('/payments/{payment}', [AdminPaymentController::class, 'show'])->name('payments.show');
    Route::post('/payments/{payment}/verify', [AdminPaymentController::class, 'verify'])->name('payments.verify');
    Route::post('/payments/{payment}/reject', [AdminPaymentController::class, 'reject'])->name('payments.reject');

    Route::get('/withdrawals', [AdminWithdrawalController::class, 'index'])->name('withdrawals.index');
    Route::get('/withdrawals/{withdrawal}', [AdminWithdrawalController::class, 'show'])->name('withdrawals.show');
    Route::post('/withdrawals/{withdrawal}/process', [AdminWithdrawalController::class, 'process'])->name('withdrawals.process');
    Route::post('/withdrawals/{withdrawal}/approve', [AdminWithdrawalController::class, 'approve'])->name('withdrawals.approve');
    Route::post('/withdrawals/{withdrawal}/reject', [AdminWithdrawalController::class, 'reject'])->name('withdrawals.reject');

    Route::get('/wallet', [AdminWalletController::class, 'index'])->name('wallet.index');
    Route::post('/wallet/expense', [AdminWalletController::class, 'storeExpense'])->name('wallet.expense');
    Route::post('/wallet/withdraw', [AdminWalletController::class, 'withdraw'])->name('wallet.withdraw');
});

// ──────────────────────────────────────────────
// REPORTS (AUTH ONLY)
// ──────────────────────────────────────────────
Route::middleware('auth')->prefix('reports')->name('reports.')->group(function () {
    Route::get('/create', [ReportController::class, 'create'])->name('create');
    Route::post('/', [ReportController::class, 'store'])->name('store');
    Route::post('/{report}/evidence', [ReportController::class, 'uploadEvidence'])->name('evidence');
});

// ──────────────────────────────────────────────
// NOTIFICATIONS (AUTH ONLY)
// ──────────────────────────────────────────────
Route::middleware('auth')->prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', [NotificationController::class, 'index'])->name('index');
    Route::post('/{notification}/read', [NotificationController::class, 'markRead'])->name('mark-read');
    Route::post('/mark-all-read', [NotificationController::class, 'markAllRead'])->name('mark-all-read');
});

// ──────────────────────────────────────────────
// PASSWORD SETTINGS
// ──────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::post('/settings/password/verify', [PasswordController::class, 'verifyCurrentPassword'])->name('settings.password.verify');
    Route::post('/settings/password/update', [PasswordController::class, 'updatePassword'])->name('settings.password.update');
    Route::post('/password/update', [PasswordController::class, 'update'])->name('password.update');
});