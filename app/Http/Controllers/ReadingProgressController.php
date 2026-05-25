<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\ReadingProgress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReadingProgressController extends Controller
{
    /**
     * Menyimpan / memperbarui halaman terakhir yang dibaca.
     */
    public function store(Request $request, Book $book): JsonResponse
    {
        $validated = $request->validate([
            'current_page' => 'required|integer|min:1',
            'total_pages'  => 'nullable|integer|min:1',
        ]);

        $progress = ReadingProgress::updateOrCreate(
            ['user_id' => auth()->id(), 'book_id' => $book->id],
            [
                'current_page' => $validated['current_page'],
                'total_pages'  => $validated['total_pages'] ?? null,
                'last_read_at' => now(),
            ]
        );

        return response()->json(['success' => true]);
    }
}