<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CompanyProfile;
use App\Models\Project;

class ProfilController extends Controller
{
    public function profile()
    {
        $userId = Auth::id();

        $profile = CompanyProfile::firstOrCreate(
            [
                'user_id' => $userId
            ]
        );

        // 1. Total Project yang pernah dibuat
        $totalProjects = Project::where('user_id', $userId)->count();
        
        // 2. Project Selesai (Mencakup berbagai variasi nama status di database)
        $completedProjects = Project::where('user_id', $userId)
                                    ->whereIn('status', ['completed', 'selesai', 'done', 'finished', 'closed']) 
                                    ->count();

        $paymentRate = '100%'; 
        $successRate = $totalProjects > 0 ? round(($completedProjects / $totalProjects) * 100) . '%' : '0%';   

        return view(
            'company.profil',
            compact('profile', 'totalProjects', 'completedProjects', 'paymentRate', 'successRate')
        );
    }

    public function editProfile()
    {
        $profile = CompanyProfile::firstOrCreate(
            [
                'user_id' => Auth::id()
            ]
        );

        return view(
            'company.edit_profil',
            compact('profile')
        );
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'company_logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'company_name' => 'nullable|string|max:255',
            'industry'     => 'nullable|string|max:255',
            'description'  => 'nullable|string',
            'website'      => 'nullable|url',
            'location'     => 'nullable|string|max:255',
            'phone'        => 'nullable|string|max:20',
        ]);

        $profile = CompanyProfile::firstOrCreate(
            [
                'user_id' => Auth::id()
            ]
        );

        if($request->hasFile('company_logo'))
        {
            $logo = $request
                    ->file('company_logo')
                    ->store('company','public');

            $profile->company_logo = $logo;
        }

$profile->company_name = $request->company_name;
        $profile->industry     = $request->industry;
        $profile->description  = $request->description;
        $profile->website      = $request->website;
        $profile->location     = $request->location;
        $profile->phone        = $request->phone;

        $profile->save();

        // Sync phone to User model so ProfileCompletionService can read it
        if ($request->phone) {
            Auth::user()->update(['phone' => $request->phone]);
        }

        return redirect()
                ->route('company.profile')
                ->with(
                    'success',
                    'Profil berhasil diperbarui.'
                );
    }
}