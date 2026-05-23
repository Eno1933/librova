<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Bookmark;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    /**
     * Toggle bookmark untuk sebuah buku.
     */
    public function toggle(Book $book): RedirectResponse
    {
        $user = auth()->user();

        // Gunakan method toggle dari model Bookmark (menambah/menghapus)
        $added = Bookmark::toggle($user->id, $book->id);

        $message = $added
            ? 'Buku berhasil disimpan ke bookmark.'
            : 'Buku dihapus dari bookmark.';

        return redirect()->back()->with('success', $message);
    }
}