<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Book
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    // Mencegah duplikat bookmark (sudah di-handle unique constraint di DB)
    public static function toggle(int $userId, int $bookId): bool
    {
        $bookmark = static::where('user_id', $userId)
            ->where('book_id', $bookId)
            ->first();

        if ($bookmark) {
            $bookmark->delete();
            return false; // artinya bookmark dihapus
        }

        static::create([
            'user_id' => $userId,
            'book_id' => $bookId,
        ]);

        return true; // artinya bookmark ditambahkan
    }

    public static function isBookmarked(int $userId, int $bookId): bool
    {
        return static::where('user_id', $userId)
            ->where('book_id', $bookId)
            ->exists();
    }
}