<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Illuminate\Pagination\LengthAwarePaginator;

class ProfileController extends Controller
{
    /**
     * Halaman profil utama.
     */
    public function index(Request $request): View
    {
        $user = $request->user()->loadCount(['bookmarks', 'reviews', 'ratings']);

        // Bookmark terbaru untuk ditampilkan di profil
        $recentBooks = $request->user()
            ->bookmarks()
            ->with('book.category')
            ->latest()
            ->limit(4)
            ->get();

        // Statistik: jumlah buku unik yang sudah dibaca
        $uniqueReadBooks = \App\Models\BookView::where('user_id', $user->id)
            ->distinct('book_id')
            ->count('book_id');

        $stats = [
            'bookmarks' => $user->bookmarks_count,
            'reviews'   => $user->reviews_count,
            'ratings'   => $user->ratings_count,
            'read'      => $uniqueReadBooks,   // buku unik yang sudah dibaca
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
        $allViews = \App\Models\BookView::where('user_id', $request->user()->id)
            ->with('book.category')
            ->latest('viewed_at')
            ->get()
            ->unique('book_id');   // hanya satu entri per buku (yang terbaru)

        $page    = $request->get('page', 1);
        $perPage = 10;
        $items   = $allViews->forPage($page, $perPage);
        $history = new LengthAwarePaginator(
            $items,
            $allViews->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('profile.history', compact('history'));
    }
}