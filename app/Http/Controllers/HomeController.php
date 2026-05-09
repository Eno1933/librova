<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Tampilkan homepage untuk guest, atau redirect ke dashboard jika sudah login.
     */
    public function index(): View|RedirectResponse
    {
        // Jika user sudah login, langsung arahkan ke dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        // Data untuk tampilan guest
        $featuredBooks = Book::where('is_featured', true)
            ->where('status', 'active')
            ->with('category')
            ->limit(6)
            ->get();

        $popularBooks = Book::where('status', 'active')
            ->orderByDesc('view_count')
            ->with('category')
            ->limit(8)
            ->get();

        $trendingBooks = Book::where('status', 'active')
            ->where('created_at', '>=', now()->subDays(7))
            ->with('category')
            ->withAvg('ratings', 'score')
            ->orderByDesc('view_count')
            ->limit(6)
            ->get();

        $newArrivals = Book::where('status', 'active')
            ->latest()
            ->with('category')
            ->limit(6)
            ->get();

        $categories = Category::whereNull('parent_id')
            ->with('children')
            ->limit(8)
            ->get();

        return view('home', compact(
            'featuredBooks', 'popularBooks', 'trendingBooks',
            'newArrivals', 'categories'
        ));
    }
}