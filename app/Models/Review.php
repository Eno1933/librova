<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'content',
        'status',
        'likes_count',
    ];

    protected $casts = [
        'status' => 'string',      // pending, approved, rejected
        'likes_count' => 'integer',
    ];

    // Relasi ke User (penulis review)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Book (buku yang direview)
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    // Relasi ke ReviewLike (jika model ReviewLike dibuat)
    public function likes()
    {
        return $this->hasMany(ReviewLike::class);
    }

    // Cek apakah user tertentu sudah menyukai review ini
    public function isLikedBy(?User $user): bool
    {
        if (!$user) {
            return false;
        }
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    // Scope untuk review yang sudah disetujui
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    // Scope untuk review yang masih menunggu moderasi
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}