<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReadingProgress extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'book_id', 'current_page', 'total_pages', 'last_read_at'];

    protected $casts = [
        'last_read_at' => 'datetime',
        'current_page' => 'integer',
        'total_pages' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}