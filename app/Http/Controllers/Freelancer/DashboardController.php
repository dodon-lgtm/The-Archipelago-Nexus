<?php

namespace App\Http\Controllers\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $projects = Project::latest()
            ->take(3)
            ->get();

        return view('freelancer.dashboard', compact('projects'));
    }
}