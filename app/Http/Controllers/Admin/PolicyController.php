<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PolicyUpdateRequest;
use App\Models\Policy;

class PolicyController extends Controller
{
    /**
     * Daftar dokumen kebijakan (Privasi + Penggunaan) dengan aksi kelola.
     */
    public function index()
    {
        $policies = Policy::orderBy('id')->get();

        return view('admin.policies.index', compact('policies'));
    }

    /**
     * Formulir edit sebuah dokumen kebijakan.
     */
    public function edit(Policy $policy)
    {
        return view('admin.policies.edit', compact('policy'));
    }

    /**
     * Simpan perubahan judul, isi, dan status aktif dokumen.
     */
    public function update(PolicyUpdateRequest $request, Policy $policy)
    {
        $policy->update($request->validated());

        return redirect()->route('admin.policies.index')
            ->with('success', 'Kebijakan berhasil diperbarui.');
    }
}
