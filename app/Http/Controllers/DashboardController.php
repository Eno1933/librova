<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $query = Book::with('category')->where('status', 'active');

        // Filter berdasarkan kategori (slug)
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $books = $query->latest()->paginate(12)->withQueryString();

        // Ambil semua kategori untuk filter
        $categories = Category::whereNull('parent_id')->with('children')->get();

        // ✅ Buku populer (5 teratas berdasarkan view_count)
        $popularBooks = Book::where('status', 'active')
            ->orderByDesc('view_count')
            ->with('category')
            ->limit(5)
            ->get();

        // ✅ Buku yang sedang/sudah dibaca user (5 terakhir dari book_views)
        $continueReading = collect();
        if (auth()->check()) {
            $continueReading = \App\Models\BookView::where('user_id', auth()->id())
                ->with('book.category')
                ->latest('viewed_at')
                ->take(5)
                ->get()
                ->unique('book_id'); // hindari duplikat buku yang sama
        }

        return view('dashboard', compact('books', 'categories', 'popularBooks', 'continueReading'));
    }
}