<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FooterSettingUpdateRequest;
use App\Models\FooterSetting;

class FooterSettingController extends Controller
{
    /**
     * Formulir edit pengaturan footer.
     */
    public function edit()
    {
        $setting = FooterSetting::getSettings();

        return view('admin.footer-settings.edit', compact('setting'));
    }

    /**
     * Simpan perubahan pengaturan footer.
     */
    public function update(FooterSettingUpdateRequest $request)
    {
        $setting = FooterSetting::query()->firstOrNew([]);
        $setting->fill($request->validated());
        $setting->save();

        return redirect()->route('admin.footer-settings.edit')
            ->with('success', 'Pengaturan footer berhasil diperbarui.');
    }
}