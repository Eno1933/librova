<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Halaman profil utama.
     */
    public function index(Request $request): View
    {
        $user = $request->user()->loadCount(['bookmarks', 'reviews', 'ratings']);
        $recentBooks = $request->user()
            ->bookmarks()
            ->with('book.category')
            ->latest()
            ->limit(4)
            ->get();
        $stats = [
            'bookmarks' => $user->bookmarks_count,
            'reviews'   => $user->reviews_count,
            'ratings'   => $user->ratings_count,
            'read'      => \App\Models\BookView::where('user_id', $user->id)->count(),
        ];

        return view('profile.index', compact('user', 'recentBooks', 'stats'));
    }

    /**
     * Update profil (nama & avatar).
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'   => ['required', 'string', 'max:255'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $user = $request->user();

        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Ganti password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Password::min(8)],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password berhasil diubah.');
    }

    /**
     * Halaman bookmark.
     */
    public function bookmarks(Request $request): View
    {
        $books = $request->user()
            ->bookmarks()
            ->with('book.category')
            ->latest()
            ->paginate(12);

        return view('profile.bookmarks', compact('books'));
    }

    /**
     * Halaman riwayat baca.
     */
    public function history(Request $request): View
    {
        $history = \App\Models\BookView::where('user_id', $request->user()->id)
            ->with('book.category')
            ->latest('viewed_at')
            ->paginate(20);

        return view('profile.history', compact('history'));
    }
}