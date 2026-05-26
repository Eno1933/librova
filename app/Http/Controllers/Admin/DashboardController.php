<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Feedback;
use App\Models\Rating;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    /**
     * Ekspor data dashboard ke CSV.
     */
    public function export(): StreamedResponse   // ← ubah type-hint
    {
        $totalBooks   = Book::count();
        $totalUsers   = User::where('role', 'user')->count();
        $totalRatings = Rating::count();
        $totalReviews = Review::count();
        $newFeedbacks = Feedback::where('status', 'new')->count();

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="librova-dashboard-' . now()->format('Ymd-His') . '.csv"',
        ];

        $callback = function () use ($totalBooks, $totalUsers, $totalRatings, $totalReviews, $newFeedbacks) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");   // BOM UTF-8

            fputcsv($file, ['Statistik', 'Nilai']);
            fputcsv($file, ['Total Buku', $totalBooks]);
            fputcsv($file, ['Pengguna Terdaftar', $totalUsers]);
            fputcsv($file, ['Total Rating', $totalRatings]);
            fputcsv($file, ['Total Review', $totalReviews]);
            fputcsv($file, ['Feedback Baru', $newFeedbacks]);
            fputcsv($file, ['Tanggal Ekspor', now()->translatedFormat('d F Y H:i')]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
