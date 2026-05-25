<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FeedbackController extends Controller
{
    /**
     * Menampilkan semua feedback.
     */
    public function index(Request $request): View
    {
        $query = Feedback::with('user')->latest();

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $feedbacks = $query->paginate(15)->withQueryString();

        return view('admin.feedbacks.index', compact('feedbacks'));
    }

    /**
     * Membalas feedback dan mengubah status menjadi replied.
     */
    public function reply(Request $request, Feedback $feedback): RedirectResponse
    {
        $validated = $request->validate([
            'admin_reply' => 'required|string|min:5|max:2000',
        ]);

        $feedback->update([
            'admin_reply' => $validated['admin_reply'],
            'status'      => 'replied',
        ]);

        return redirect()->back()->with('success', 'Balasan berhasil dikirim.');
    }

    // Optional: Mark as read (bisa dibuatkan route sendiri)
    public function markAsRead(Feedback $feedback): RedirectResponse
    {
        $feedback->update(['status' => 'read']);
        return redirect()->back()->with('success', 'Feedback ditandai sudah dibaca.');
    }
}