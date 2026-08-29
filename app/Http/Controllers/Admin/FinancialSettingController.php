<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FinancialSettingUpdateRequest;
use App\Models\FinancialSetting;

class FinancialSettingController extends Controller
{
    /**
     * Halaman Pengaturan Keuangan (Administrasi).
     */
    public function edit()
    {
        $setting = FinancialSetting::getSettings();

        return view('admin.financial-settings.edit', compact('setting'));
    }

    /**
     * Simpan perubahan pengaturan keuangan (single-row, firstOrNew).
     */
    public function update(FinancialSettingUpdateRequest $request)
    {
        $setting = FinancialSetting::query()->firstOrNew([]);
        
        $data = $request->validated();
        
        // Parse formatted price input (remove thousand separators)
        if (isset($data['paid_project_upload_price_raw'])) {
            $data['paid_project_upload_price'] = (float) str_replace('.', '', $data['paid_project_upload_price_raw']);
            unset($data['paid_project_upload_price_raw']);
        }
        
        $setting->fill($data);
        $setting->save();

        return redirect()
            ->route('admin.financial-settings.edit')
            ->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
