<?php

namespace App\Http\Controllers\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\Category;
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

        $query = Project::with('category')->latest();

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

        return view('freelancer.dashboard', compact(
            'projects',
            'categories',
            'search',
            'categoryId',
            'latestApplications',
            'lamaranCount',
            'savedCount'
        ));
    }
}