<?php

namespace App\Http\Controllers;

use App\Models\FooterSetting;
use App\Models\Policy;

class LegalPageController extends Controller
{
    /**
     * Halaman publik Kebijakan Privasi.
     */
    public function privacyPolicy()
    {
        $setting = FooterSetting::getSettings();
        $policy = Policy::where('key', Policy::KEY_PRIVACY)->active()->first();

        return view('pages.privacy-policy', compact('setting', 'policy'));
    }

    /**
     * Halaman publik Syarat & Ketentuan.
     */
    public function termsConditions()
    {
        $setting = FooterSetting::getSettings();
        $policy = Policy::where('key', Policy::KEY_TERMS)->active()->first();

        return view('pages.terms-conditions', compact('setting', 'policy'));
    }

    /**
     * Halaman publik Kebijakan Penggunaan Platform.
     */
    public function usagePolicy()
    {
        $setting = FooterSetting::getSettings();
        $policy = Policy::where('key', Policy::KEY_USAGE)->active()->first();

        return view('pages.usage-policy', compact('setting', 'policy'));
    }
}