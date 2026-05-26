<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Halaman semua notifikasi.
     */
    public function index(): View
    {
        $notifications = AdminNotification::latest()->paginate(20);
        return view('admin.notifications.index', compact('notifications'));
    }

    /**
     * Tandai satu notifikasi dibaca, lalu redirect ke URL terkait.
     */
    public function markAsRead(AdminNotification $notification): RedirectResponse
    {
        $notification->markAsRead();
        return redirect($notification->related_url ?? route('admin.dashboard'));
    }

    /**
     * Tandai semua belum dibaca menjadi sudah dibaca.
     */
    public function markAllAsRead(): RedirectResponse
    {
        AdminNotification::unread()->update(['read_at' => now()]);
        return back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }
}