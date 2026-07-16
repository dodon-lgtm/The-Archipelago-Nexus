<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyAccountRequestController;
use App\Http\Controllers\Admin\CompanyAccountRequestAdminController;
use App\Http\Controllers\Company\ProjectController;
use App\Http\Controllers\AuthController;


Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

use App\Http\Controllers\RegisterController;

Route::get('/register', [RegisterController::class, 'showRegister'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);




Route::get('/', function () {
    return view('welcome');
})->name('landing');

Route::get('/company-account-requests/create', [CompanyAccountRequestController::class, 'create'])
    ->name('company-account-requests.create');


Route::post('/company-account-requests', [CompanyAccountRequestController::class, 'store'])
    ->name('company-account-requests.store');

Route::middleware(['auth', 'ensureCompanyAdminOrAbort'])->prefix('company')->name('company.')
    ->group(function () {


        Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
        Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
        Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');

        Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
        Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
        Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
        Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
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





