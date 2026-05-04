<?php
namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

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
            'rating' => $query->withAvg('ratings', 'score')->orderByDesc('ratings_avg_score'),
            default => $query->latest(),
        };

        $books = $query->paginate(12)->withQueryString();
        $categories = Category::whereNull('parent_id')->with('children')->get();

        return view('books.index', compact('books', 'categories'));
    }

    public function show(string $slug): View
    {
        $book = Book::where('slug', $slug)
            ->where('status', 'active')
            ->with(['category', 'approvedReviews.user'])
            ->firstOrFail();

        // Track view
        if (auth()->check()) {
            $book->views()->create(['user_id' => auth()->id()]);
        }
        $book->increment('view_count');

        $userRating = null;
        if (auth()->check()) {
            $userRating = $book->ratings()->where('user_id', auth()->id())->first();
        }

        $ratingDistribution = $book->ratings()
            ->selectRaw('score, COUNT(*) as count')
            ->groupBy('score')
            ->pluck('count', 'score')
            ->toArray();

        $relatedBooks = Book::where('category_id', $book->category_id)
            ->where('id', '!=', $book->id)
            ->where('status', 'active')
            ->limit(4)
            ->get();

        return view('books.show', compact('book', 'userRating', 'ratingDistribution', 'relatedBooks'));
    }

    public function read(string $slug): View
    {
        $book = Book::where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        return view('books.read', compact('book'));
    }
}