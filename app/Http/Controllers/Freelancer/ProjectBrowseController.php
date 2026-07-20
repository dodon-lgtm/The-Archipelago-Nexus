<?php

namespace App\Http\Controllers\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectBrowseController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $categoryId = $request->query('category_id');

        $query = Project::query()
            ->with('category')
            ->latest();

        if ($search) {
            $query->where('project_name', 'like', '%' . $search . '%');
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $projects = $query->paginate(10)->withQueryString();
        $categories = Category::query()->orderBy('name')->get();

        return view('freelancer.projects.index', [
            'projects' => $projects,
            'categories' => $categories,
            'search' => $search,
            'categoryId' => $categoryId,
        ]);
    }

    public function show(Project $project): View
    {
        $project->load('category', 'owner');

        return view('freelancer.projects.show', compact('project'));
    }
    
}

