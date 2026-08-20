<?php

namespace App\Http\Controllers;

use App\Models\FooterSetting;

class LegalPageController extends Controller
{
    /**
     * Halaman publik Kebijakan Privasi.
     */
    public function privacyPolicy()
    {
        $setting = FooterSetting::getSettings();

        return view('pages.privacy-policy', compact('setting'));
    }

    /**
     * Halaman publik Syarat & Ketentuan.
     */
    public function termsConditions()
    {
        $setting = FooterSetting::getSettings();

        return view('pages.terms-conditions', compact('setting'));
    }
}