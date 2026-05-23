<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Feedback;
use App\Models\Rating;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalBooks    = Book::count();
        $totalUsers    = User::where('role', 'user')->count();
        $totalRatings  = Rating::count();
        $totalReviews  = Review::count();
        $pendingReviews = Review::where('status', 'pending')->count();
        $newFeedbacks  = Feedback::where('status', 'new')->count();

        $popularBooks = Book::where('status', 'active')
            ->orderByDesc('view_count')
            ->limit(5)
            ->get();

        $latestUsers = User::where('role', 'user')
            ->latest()
            ->limit(5)
            ->get();

        // Data untuk chart distribusi rating
        $ratingsDistribution = Rating::select('score', DB::raw('count(*) as total'))
            ->groupBy('score')
            ->orderBy('score')
            ->pluck('total', 'score')
            ->toArray();

        // Data buku per kategori (top 8)
        $booksPerCategory = Category::withCount('books')
            ->orderByDesc('books_count')
            ->limit(8)
            ->get()
            ->map(fn($cat) => [
                'name' => $cat->name,
                'count' => $cat->books_count,
            ]);

        // Data user registrasi per bulan (6 bulan terakhir)
        $userRegistrations = User::select(
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
            DB::raw('count(*) as total')
        )
            ->where('role', 'user')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(fn($item) => [
                'month' => $item->month,
                'total' => $item->total,
            ]);

        return view('admin.dashboard', compact(
            'totalBooks',
            'totalUsers',
            'totalRatings',
            'totalReviews',
            'pendingReviews',
            'newFeedbacks',
            'popularBooks',
            'latestUsers',
            'ratingsDistribution',
            'booksPerCategory',
            'userRegistrations'
        ));
    }

    public function reports(): View
    {
        $totalBooks    = Book::count();
        $totalUsers    = User::where('role', 'user')->count();
        $totalRatings  = Rating::count();
        $totalReviews  = Review::count();
        $newFeedbacks  = Feedback::where('status', 'new')->count();

        // Distribusi rating
        $ratingsDistribution = Rating::select('score', DB::raw('count(*) as total'))
            ->groupBy('score')
            ->orderBy('score')
            ->pluck('total', 'score')
            ->toArray();
        for ($i = 1; $i <= 5; $i++) {
            $ratingsDistribution[$i] = $ratingsDistribution[$i] ?? 0;
        }

        // Buku per kategori (top 8)
        $booksPerCategory = Category::withCount('books')
            ->orderByDesc('books_count')
            ->limit(8)
            ->get()
            ->map(fn($cat) => [
                'name' => $cat->name,
                'count' => $cat->books_count,
            ]);

        // User registrasi 6 bulan
        $userRegistrations = User::select(
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
            DB::raw('count(*) as total')
        )
            ->where('role', 'user')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(fn($item) => [
                'month' => $item->month,
                'total' => $item->total,
            ]);

        // Buku terpopuler
        $popularBooks = Book::where('status', 'active')
            ->orderByDesc('view_count')
            ->limit(5)
            ->get();

        // User terbaru
        $latestUsers = User::where('role', 'user')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.reports', compact(
            'totalBooks',
            'totalUsers',
            'totalRatings',
            'totalReviews',
            'newFeedbacks',
            'ratingsDistribution',
            'booksPerCategory',
            'userRegistrations',
            'popularBooks',
            'latestUsers'
        ));
    }
}
