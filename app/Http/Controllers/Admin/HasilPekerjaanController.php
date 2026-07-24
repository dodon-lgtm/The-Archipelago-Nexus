<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HasilPekerjaanController extends Controller
{
    public function index(Request $request): View
    {
        $query = Workspace::with([
            'project',
            'project.owner',
            'project.category',
            'company',
            'freelancer',
            'latestProgress',
        ]);

        // Filter by status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Search by project name, company name, or freelancer name
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('project', function ($sub) use ($search) {
                    $sub->where('project_name', 'like', "%{$search}%");
                })
                ->orWhereHas('company', function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('freelancer', function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%");
                });
            });
        }

        $workspaces = $query->latest()->paginate(15)->withQueryString();

        return view('admin.hasil-pekerjaan.index', compact('workspaces'));
    }

    public function show(Workspace $workspace): View
    {
        $workspace->load([
            'project',
            'project.owner',
            'project.category',
            'company',
            'freelancer',
            'progressHistories.updater',
            'messages.sender',
        ]);

        return view('admin.hasil-pekerjaan.show', compact('workspace'));
    }
}

