<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookView extends Model
{
    use HasFactory;

    // Tabel ini tidak menggunakan kolom created_at/updated_at bawaan Laravel
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'book_id',
        'viewed_at',
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
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

    // Mencatat view (atau update timestamp)
    public static function recordView(int $userId, int $bookId): void
    {
        static::create([
            'user_id' => $userId,
            'book_id' => $bookId,
            'viewed_at' => now(),
        ]);
    }
}