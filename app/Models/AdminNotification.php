<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    protected $fillable = ['type', 'message', 'related_url', 'read_at'];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    /** Tandai sebagai sudah dibaca */
    public function markAsRead(): void
    {
        if (!$this->read_at) {
            $this->update(['read_at' => now()]);
        }
    }

    /** Scope: notifikasi belum dibaca */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /** Scope: 5 notifikasi terbaru */
    public function scopeRecent($query)
    {
        return $query->latest()->take(5);
    }
}