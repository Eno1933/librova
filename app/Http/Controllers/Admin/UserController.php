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

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->whereNull('suspended_at');
            } elseif ($request->status === 'suspended') {
                $query->whereNotNull('suspended_at');
            }
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Menampilkan detail seorang pengguna.
     */
    public function show(User $user): View
    {
        $user->loadCount(['bookmarks', 'reviews', 'ratings']);

        // Bookmark terbaru
        $recentBookmarks = $user->bookmarks()
            ->with('book.category')
            ->latest()
            ->limit(6)
            ->get();

        // Riwayat baca terbaru (unik per buku)
        $recentHistory = \App\Models\BookView::where('user_id', $user->id)
            ->with('book.category')
            ->latest('viewed_at')
            ->limit(5)
            ->get()
            ->unique('book_id');

        return view('admin.users.show', compact('user', 'recentBookmarks', 'recentHistory'));
    }

    /**
     * Toggle suspend/aktifkan user.
     */
    public function toggleSuspend(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menonaktifkan akun sendiri.');
        }

        if ($user->suspended_at) {
            $user->update(['suspended_at' => null]);
            $message = 'Akun berhasil diaktifkan kembali.';
        } else {
            $user->update(['suspended_at' => now()]);
            $message = 'Akun berhasil dinonaktifkan.';
        }

        return redirect()->back()->with('success', $message);
    }
}