<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BookController extends Controller
{
    public function index(Request $request): View
    {
        $query = Book::query()->where('status', 'active')->with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('language')) {
            $query->where('language', $request->language);
        }

        $sort = $request->get('sort', 'newest');
        match ($sort) {
            'popular' => $query->orderByDesc('view_count'),
            'rating'  => $query->withAvg('ratings', 'score')->orderByDesc('ratings_avg_score'),
            default   => $query->latest(),
        };

        $books      = $query->paginate(12)->withQueryString();
        $categories = Category::whereNull('parent_id')->with('children')->get();

        return view('books.index', compact('books', 'categories'));
    }

    public function show(string $slug): View
    {
        $book = Book::where('slug', $slug)
            ->where('status', 'active')
            ->with(['category', 'approvedReviews.user'])
            ->firstOrFail();

        // Tracking view
        if (auth()->check()) {
            $book->views()->create(['user_id' => auth()->id()]);
        }
        $book->increment('view_count');

        // Rating user saat ini
        $userRating = null;
        if (auth()->check()) {
            $userRating = $book->ratings()->where('user_id', auth()->id())->first();
        }

        // Distribusi rating (pastikan semua 1-5 ada)
        $ratingDistribution = $book->ratings()
            ->selectRaw('score, COUNT(*) as count')
            ->groupBy('score')
            ->pluck('count', 'score')
            ->toArray();

        // Isi missing key 1-5 dengan 0
        for ($i = 1; $i <= 5; $i++) {
            $ratingDistribution[$i] = $ratingDistribution[$i] ?? 0;
        }

        // Buku terkait
        $relatedBooks = Book::where('category_id', $book->category_id)
            ->where('id', '!=', $book->id)
            ->where('status', 'active')
            ->limit(6)
            ->get();

        return view('books.show', compact('book', 'userRating', 'ratingDistribution', 'relatedBooks'));
    }
    

    /**
     * Melayani file PDF dari storage private.
     * Hanya user yang sudah login yang boleh mengakses.
     */
    public function serveFile(Book $book): BinaryFileResponse
    {
        // ✅ FIX: Pastikan hanya user login yang bisa akses file PDF
        if (! auth()->check()) {
            abort(403, 'Silakan login untuk membaca buku ini.');
        }

        if (! $book->file_path || ! Storage::disk('private')->exists($book->file_path)) {
            abort(404, 'File buku tidak ditemukan.');
        }

        $path = Storage::disk('private')->path($book->file_path);

        return response()->file($path, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $book->title . '.pdf"',
            // Cegah browser cache file PDF sensitif
            'Cache-Control'       => 'no-store, no-cache, must-revalidate',
            'Pragma'              => 'no-cache',
        ]);
    }

    public function read(string $slug): View
    {
        // ✅ Pastikan user login untuk membaca
        if (! auth()->check()) {
            return redirect()->route('login')
                ->with('message', 'Silakan login untuk membaca buku.');
        }

        $book = Book::where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        if (! $book->file_path) {
            abort(404, 'File buku tidak tersedia.');
        }

        return view('books.read', compact('book'));
    }
}
