<?php
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::whereNull('parent_id')->with('children')->get();
        return view('categories.index', compact('categories'));
    }

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