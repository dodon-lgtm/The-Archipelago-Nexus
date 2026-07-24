<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penawaran;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PenawaranController extends Controller
{
    public function index(Request $request): View
    {
        $query = Penawaran::with([
            'freelancer',
            'project.owner',
            'project.category',
        ]);

        // Search by freelancer name, project name, or company name
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('freelancer', function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('project', function ($sub) use ($search) {
                    $sub->where('project_name', 'like', "%{$search}%");
                })
                ->orWhereHas('project.owner', function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%");
                });
            });
        }

        // Filter by status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $penawarans = $query->latest()->paginate(15)->withQueryString();

        return view('admin.penawarans.index', compact('penawarans'));
    }

    public function show(Penawaran $penawaran): View
    {
        $penawaran->load([
            'freelancer',
            'project.owner',
            'project.category',
            'project.workspace',
        ]);

        return view('admin.penawarans.show', compact('penawaran'));
    }
}

