<?php
namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function store(Request $request, Book $book): JsonResponse
    {
        $request->validate([
            'score' => 'required|integer|min:1|max:5',
        ]);

        $rating = $book->ratings()->updateOrCreate(
            ['user_id' => auth()->id()],
            ['score' => $request->score]
        );

        return response()->json([
            'success' => true,
            'average' => $book->averageRating(),
            'count' => $book->ratingsCount(),
            'userScore' => $rating->score,
        ]);
    }

    public function getUserRating(Book $book): JsonResponse
    {
        $rating = $book->ratings()->where('user_id', auth()->id())->first();

        return response()->json([
            'score' => $rating ? $rating->score : 0,
        ]);
    }
}