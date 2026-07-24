<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query();

        // Search by name or email
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user): View
    {
        $user->loadCount([
            'penawarans',
            'savedProjects',
        ]);

        // For company users
        $projectsCount = \App\Models\Project::where('user_id', $user->id)->count();

        // For freelancer users
        $acceptedOffers = \App\Models\Penawaran::where('freelancer_id', $user->id)
            ->where('status', 'Diterima')
            ->count();

        return view('admin.users.show', compact(
            'user',
            'projectsCount',
            'acceptedOffers'
        ));
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'role' => 'required|in:admin,company,freelancer',
        ]);

        // Prevent changing own role
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat mengubah role Anda sendiri.');
        }

        $user->update(['role' => $request->role]);

        return back()->with('success', "Role pengguna {$user->name} berhasil diubah menjadi {$request->role}.");
    }

    public function destroy(User $user): RedirectResponse
    {
        // Prevent deleting self
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Prevent deleting other admins
        if ($user->role === 'admin') {
            return back()->with('error', 'Tidak dapat menghapus akun admin lain.');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', "Pengguna {$user->name} berhasil dihapus.");
    }
}

