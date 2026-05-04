<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\User;
use App\Models\Rating;
use App\Models\Review;
use App\Models\Feedback;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalBooks = Book::count();
        $totalUsers = User::where('role', 'user')->count();
        $totalRatings = Rating::count();
        $totalReviews = Review::count();
        $pendingReviews = Review::where('status', 'pending')->count();
        $newFeedbacks = Feedback::where('status', 'new')->count();
        $popularBooks = Book::where('status', 'active')
            ->orderByDesc('view_count')
            ->limit(5)
            ->get();
        $latestUsers = User::where('role', 'user')->latest()->limit(5)->get();

        return view('admin.dashboard', compact(
            'totalBooks', 'totalUsers', 'totalRatings', 'totalReviews',
            'pendingReviews', 'newFeedbacks', 'popularBooks', 'latestUsers'
        ));
    }
}