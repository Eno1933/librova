<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Menampilkan daftar semua pengguna.
     */
    public function index(Request $request): View
    {
        $query = User::query();

        // Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Toggle suspend/aktifkan user.
     */
    public function toggleSuspend(User $user): RedirectResponse
    {
        // Admin tidak bisa menonaktifkan dirinya sendiri
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menonaktifkan akun sendiri.');
        }

        // Toggle status suspended (gunakan kolom `suspended_at` atau `is_suspended`)
        // Di sini kita asumsikan ada kolom `suspended_at` di tabel users.
        // Jika belum ada, tambahkan migration: $table->timestamp('suspended_at')->nullable();
        if ($user->suspended_at) {
            $user->update(['suspended_at' => null]);
            $message = 'Akun berhasil diaktifkan kembali.';
        } else {
            $user->update(['suspended_at' => now()]);
            $message = 'Akun berhasil dinonaktifkan.';
        }

        return redirect()->route('admin.users.index')->with('success', $message);
    }
}