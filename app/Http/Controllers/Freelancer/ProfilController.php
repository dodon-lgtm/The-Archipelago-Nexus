<?php

namespace App\Http\Controllers\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\FreelancerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfilController extends Controller
{
    public function profile($id = null)
{
    // Jika $id ada (artinya company/orang lain yang lihat), ambil user berdasarkan $id.
    // Jika $id kosong, ambil user yang sedang login (milik freelancer itu sendiri).
    if ($id) {
        $user = \App\Models\User::findOrFail($id);
    } else {
        $user = Auth::user();
    }

    // Ambil profil berdasarkan user_id dari user tersebut
    $profile = FreelancerProfile::firstOrCreate(
        ['user_id' => $user->id],
        [
            'bio' => '',
            'location' => '',
            'skills' => '',
            'portfolio_link' => '',
        ]
    );

    // Ambil ulasan yang diterima freelancer tersebut
    $reviews = $user->reviewsReceived()->with('client')->latest()->get();
    
    // Hitung rata-rata rating dan total ulasan
    $averageRating = $reviews->avg('rating');
    $totalReview = $reviews->count();

    // Tentukan apakah ini mode "hanya lihat" (view-only)
    // True jika yang login adalah company / bukan pemilik akun profil ini
    $isViewOnly = Auth::id() !== $user->id;

    return view('freelancer.profil', compact(
        'profile', 
        'user', 
        'reviews', 
        'averageRating', 
        'totalReview',
        'isViewOnly'
    ));
}

    public function dashboard()
    {
        // $categories = Category::all();
        // $projects = Project::latest()->take(8)->get();
        // return view('welcome', compact('categories', 'projects'));
    }

    // public function index()
    // {
    //     $freelancers = User::where('role', 'freelancer')->get();
    //     return view('freelancer.index', compact('freelancers'));
    // }
    
    public function editProfile()
    {
        $profile = FreelancerProfile::firstOrCreate(
            ['user_id' => Auth::id()]
        );

        return view('freelancer.edit_profile', compact('profile'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'bio' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'skills' => 'nullable|string',
            'experience' => 'nullable|string',
            'portfolio_link' => 'nullable|url',
            'cv' => 'nullable|mimes:pdf|max:2048',
        ]);

        $profile = FreelancerProfile::firstOrCreate([
            'user_id' => Auth::id()
        ]);

        // Upload Foto
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo')->store('profile', 'public');
            $profile->photo = $photo;
        }

        // Upload CV
        if ($request->hasFile('cv')) {
            $cv = $request->file('cv')->store('cv', 'public');
            $profile->cv = $cv;
        }

        $profile->bio = $request->bio;
        $profile->location = $request->location;
        $profile->skills = $request->skills;
        $profile->experience = $request->experience;
        $profile->portfolio_link = $request->portfolio_link;

        $profile->save();

        return redirect()
            ->route('freelancer.profile')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}