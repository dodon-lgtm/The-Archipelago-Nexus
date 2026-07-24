<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ClientProfile; // <--- Pastikan use model ini ada
use App\Models\CompanyProfile;

class ProfilController extends Controller
{
    public function profile()
    {
        // DIPERBAIKI: Mengubah ClientProfil menjadi ClientProfile
        $profile = CompanyProfile::firstOrCreate(
            [
                'user_id' => Auth::id()
            ]
        );

        return view(
            'company.profil',
            compact('profile')
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

        return redirect()
                ->route('company.profile')
                ->with(
                    'success',
                    'Profil berhasil diperbarui.'
                );
    }
}