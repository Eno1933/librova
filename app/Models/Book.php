<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'author', 'isbn', 'description', 'cover_image',
        'file_path', 'category_id', 'language', 'published_year', 'total_pages',
        'is_downloadable', 'is_featured', 'view_count', 'status', 'created_by',
    ];

    protected static function booted(): void
    {
        static::creating(function (Book $book) {
            if (empty($book->slug)) {
                $book->slug = Str::slug($book->title);
            }
        });
    }

    public function category() { return $this->belongsTo(Category::class); }
    public function ratings() { return $this->hasMany(Rating::class); }
    public function reviews() { return $this->hasMany(Review::class); }
    public function approvedReviews() { return $this->reviews()->where('status', 'approved'); }
    public function bookmarks() { return $this->hasMany(Bookmark::class); }
    public function views() { return $this->hasMany(BookView::class); }

    public function averageRating(): float
    {
        return round($this->ratings()->avg('score') ?? 0, 1);
    }

    public function ratingsCount(): int
    {
        return $this->ratings()->count();
    }
}