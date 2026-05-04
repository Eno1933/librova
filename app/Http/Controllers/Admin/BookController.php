<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BookController extends Controller
{
    public function index(): View
    {
        $books = Book::with('category')->latest()->paginate(10);
        return view('admin.books.index', compact('books'));
    }

    public function create(): View
    {
        $categories = Category::whereNull('parent_id')->with('children')->get();
        return view('admin.books.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:20|unique:books',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'file_path' => 'nullable|mimes:pdf|max:51200',
            'category_id' => 'nullable|exists:categories,id',
            'language' => 'required|string|max:50',
            'published_year' => 'nullable|integer|min:1000|max:' . date('Y'),
            'total_pages' => 'nullable|integer|min:1',
            'is_downloadable' => 'boolean',
            'is_featured' => 'boolean',
            'status' => 'required|in:active,inactive',
        ]);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        if ($request->hasFile('file_path')) {
            $bookFile = $request->file('file_path');
            $validated['file_path'] = $bookFile->store('books', 'private');
        }

        $validated['created_by'] = auth()->id();

        Book::create($validated);

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku berhasil ditambahkan.');
    }

    public function edit(Book $book): View
    {
        $categories = Category::whereNull('parent_id')->with('children')->get();
        return view('admin.books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:20|unique:books,isbn,' . $book->id,
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'file_path' => 'nullable|mimes:pdf|max:51200',
            'category_id' => 'nullable|exists:categories,id',
            'language' => 'required|string|max:50',
            'published_year' => 'nullable|integer|min:1000|max:' . date('Y'),
            'total_pages' => 'nullable|integer|min:1',
            'is_downloadable' => 'boolean',
            'is_featured' => 'boolean',
            'status' => 'required|in:active,inactive',
        ]);

        if ($request->hasFile('cover_image')) {
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        if ($request->hasFile('file_path')) {
            if ($book->file_path) {
                Storage::disk('local')->delete($book->file_path);
            }
            $validated['file_path'] = $request->file('file_path')->store('books');
        }

        $book->update($validated);

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroy(Book $book): RedirectResponse
    {
        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }
        if ($book->file_path) {
            Storage::disk('local')->delete($book->file_path);
        }
        $book->delete();

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku berhasil dihapus.');
    }
}