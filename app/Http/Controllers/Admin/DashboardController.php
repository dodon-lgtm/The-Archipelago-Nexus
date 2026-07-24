<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Project;
use App\Models\Penawaran;
use App\Models\Workspace;
use App\Models\Report;
use App\Models\CompanyAccountRequest;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalUsers = User::count();
        $totalFreelancers = User::where('role', 'freelancer')->count();
        $totalCompanies = User::where('role', 'company')->count();
        $totalProjects = Project::count();
        $totalPenawarans = Penawaran::count();
        $totalCompletedProjects = Workspace::where('status', 'Selesai')->count();
        $totalReports = Report::count();
        $pendingCompanyRequests = CompanyAccountRequest::where('request_status', 'menunggu')->count();

        // Recent projects
        $recentProjects = Project::with('owner')
            ->latest()
            ->take(5)
            ->get();

        // Recent users
        $recentUsers = User::latest()
            ->take(5)
            ->get();

        // Recent company account requests
        $recentRequests = CompanyAccountRequest::where('request_status', 'menunggu')
            ->latest()
            ->take(5)
            ->get();

        // Recent penawarans
        $recentPenawarans = Penawaran::with(['freelancer', 'project'])
            ->latest()
            ->take(5)
            ->get();

        // Recent reports
        $recentReports = Report::with('reporter')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalFreelancers',
            'totalCompanies',
            'totalProjects',
            'totalPenawarans',
            'totalCompletedProjects',
            'totalReports',
            'pendingCompanyRequests',
            'recentProjects',
            'recentUsers',
            'recentRequests',
            'recentPenawarans',
            'recentReports'
        ));
    }
}

