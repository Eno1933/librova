<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Bookmark;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    /**
     * Toggle bookmark untuk sebuah buku.
     */
    public function toggle(Book $book): RedirectResponse|JsonResponse
    {
        $user = auth()->user();

        $added = Bookmark::toggle($user->id, $book->id);

        $message = $added
            ? 'Buku berhasil disimpan ke bookmark.'
            : 'Buku dihapus dari bookmark.';

        // Jika permintaan AJAX / JSON, kirim respons JSON
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'bookmarked' => $added,
                'message'    => $message,
            ]);
        }

        // Fallback untuk submit form biasa
        return redirect()->back()->with('success', $message);
    }
}