<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\View\View;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Tampilkan semua kategori (parent beserta children).
     */
    public function index(Request $request): View
    {
        $query = Category::whereNull('parent_id');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        $categories = $query->with(['children' => function ($q) {
            $q->orderBy('name');
        }])->orderBy('name')->get();

        // Statistik untuk hero
        $totalCategories = Category::whereNull('parent_id')->count();
        $totalSubCategories = Category::whereNotNull('parent_id')->count();
        $totalBooks = \App\Models\Book::where('status', 'active')->count();

        return view('categories.index', compact(
            'categories',
            'totalCategories',
            'totalSubCategories',
            'totalBooks'
        ));
    }

    /**
     * Tampilkan buku-buku dalam kategori tertentu.
     */
    public function show(string $slug): View
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $books = $category->books()
            ->where('status', 'active')
            ->with('category')
            ->latest()
            ->paginate(12);

        return view('categories.show', compact('category', 'books'));
    }
}
