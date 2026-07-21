<?php

namespace App\Http\Controllers\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\Penawaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProjectOfferController extends Controller
{
    /**
     * Display a listing of the freelancer's project offers/lamaran.
     */
    public function index(Request $request): View
    {
        $lamaran = Penawaran::with([
            'project',
            'project.category',
            'project.owner',
        ])
            ->where('freelancer_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('freelancer.lamaran', compact('lamaran'));
    }
}

