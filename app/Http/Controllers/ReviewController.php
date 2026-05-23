<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;                      // <-- pastikan ada ini

class ReviewController extends Controller
{
    /**
     * Menyimpan review baru dari user.
     */
    public function store(Request $request, Book $book): RedirectResponse
    {
        $validated = $request->validate([
            'content' => 'required|string|min:5|max:1000',
        ]);

        $book->reviews()->create([
            'user_id' => auth()->id(),
            'content' => $validated['content'],
            'status'  => 'pending',
        ]);

        return redirect()->back()
            ->with('success', 'Ulasan berhasil dikirim dan sedang menunggu moderasi.');
    }

    /**
     * Menampilkan semua review untuk moderasi (admin).
     */
    public function index(Request $request): View      // <-- type‑hint yang benar
    {
        $query = Review::with(['user', 'book']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reviews = $query->latest()->paginate(15)->withQueryString();

        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Mengubah status review (approve / reject) oleh admin.
     */
    public function updateStatus(Request $request, Review $review): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $review->update(['status' => $validated['status']]);

        $message = $validated['status'] === 'approved'
            ? 'Review telah disetujui dan akan ditampilkan.'
            : 'Review telah ditolak.';

        return redirect()->route('admin.reviews.index')
            ->with('success', $message);
    }
}