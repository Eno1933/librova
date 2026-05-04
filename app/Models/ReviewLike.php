<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewLike extends Model
{
    use HasFactory;

    /**
     * Hanya created_at yang digunakan, tanpa updated_at.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'review_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Review
    public function review()
    {
        return $this->belongsTo(Review::class);
    }

    /**
     * Toggle like/unlike. Return true jika like ditambahkan, false jika dihapus.
     */
    public static function toggle(int $userId, int $reviewId): bool
    {
        $like = static::where('user_id', $userId)
            ->where('review_id', $reviewId)
            ->first();

        if ($like) {
            $like->delete();
            // Kurangi likes_count pada review
            Review::where('id', $reviewId)->decrement('likes_count');
            return false;
        }

        static::create([
            'user_id' => $userId,
            'review_id' => $reviewId,
            'created_at' => now(),
        ]);

        Review::where('id', $reviewId)->increment('likes_count');
        return true;
    }

    /**
     * Cek apakah user sudah menyukai review ini.
     */
    public static function isLiked(int $userId, int $reviewId): bool
    {
        return static::where('user_id', $userId)
            ->where('review_id', $reviewId)
            ->exists();
    }
}