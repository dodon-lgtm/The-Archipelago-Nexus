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

        // ── KEUANGAN ─────────────────────────────────────────────
        // Data transaksi diambil SEMATA dari tabel `payments` (data sudah ada).
        // Tidak membuat transaksi baru dan tidak mengubah saldo apa pun.
        //   * Company    → Pengeluaran (payments di mana user adalah company_id)
        //   * Freelancer → Pemasukan  (payments di mana user adalah freelancer_id)
        $companyExpenses = collect();
        $companyExpensesTotal = 0;
        $freelancerIncomes = collect();
        $freelancerIncomesTotal = 0;
        $freelancerWithdrawals = collect();
        $freelancerWithdrawalsTotal = 0;
        $remainingBalance = 0;

        if ($user->role === 'company') {
            $companyExpenses = \App\Models\Payment::with(['workspace.project', 'freelancer'])
                ->where('company_id', $user->id)
                ->latest()
                ->get();

            $companyExpensesTotal = \App\Models\Payment::where('company_id', $user->id)
                ->where('status', 'paid')
                ->sum('amount');
        } elseif ($user->role === 'freelancer') {
            $freelancerIncomes = \App\Models\Payment::with(['workspace.project', 'company'])
                ->where('freelancer_id', $user->id)
                ->latest()
                ->get();

            // Hanya pembayaran berstatus 'paid' yang dihitung sebagai pendapatan.
            // Pembayaran Ditolak/gagal tidak masuk hitungan.
            $freelancerIncomesTotal = \App\Models\Payment::where('freelancer_id', $user->id)
                ->where('status', 'paid')
                ->sum('freelancer_receive');

            // Riwayat penarikan freelancer (semua status, untuk daftar).
            $freelancerWithdrawals = \App\Models\Withdrawal::where('user_id', $user->id)
                ->latest()
                ->get();

            // Hanya penarikan berstatus 'berhasil' yang mengurangi saldo.
            // Penarikan Ditolak/gagal tidak dihitung sebagai Total Ditarik.
            $freelancerWithdrawalsTotal = (float) \App\Models\Withdrawal::where('user_id', $user->id)
                ->where('status', \App\Models\Withdrawal::STATUS_BERHASIL)
                ->sum('amount');

            // Sisa saldo = Total Pendapatan - Total Ditarik.
            $remainingBalance = max(0.0, (float) $freelancerIncomesTotal - $freelancerWithdrawalsTotal);
        }

        return view('admin.users.show', compact(
            'user',
            'projectsCount',
            'acceptedOffers',
            'companyExpenses',
            'companyExpensesTotal',
            'freelancerIncomes',
            'freelancerIncomesTotal',
            'freelancerWithdrawals',
            'freelancerWithdrawalsTotal',
            'remainingBalance'
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

