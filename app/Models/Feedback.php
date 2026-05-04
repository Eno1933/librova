<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subject',
        'message',
        'status',
        'admin_reply',
    ];

    protected $casts = [
        'status' => 'string',      // new, read, replied
    ];

    // Relasi ke User (pengirim feedback, bisa null untuk guest)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope: feedback baru
    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    // Scope: feedback yang sudah dibalas
    public function scopeReplied($query)
    {
        return $query->where('status', 'replied');
    }

    // Cek apakah sudah dibalas
    public function isReplied(): bool
    {
        return $this->status === 'replied';
    }
}