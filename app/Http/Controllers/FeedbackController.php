<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use App\Models\Feedback;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FeedbackController extends Controller
{
    /**
     * Menampilkan halaman feedback (form + riwayat).
     */
    public function show(): View
    {
        $feedbacks = Feedback::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('feedback.show', compact('feedbacks'));
    }

    /**
     * Menyimpan feedback. Bisa dilakukan oleh guest maupun user login.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10|max:2000',
        ]);

        $feedback = Feedback::create([
            'user_id' => auth()->check() ? auth()->id() : null,
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'status'  => 'new',
        ]);

        // Buat notifikasi untuk admin
        AdminNotification::create([
            'type'        => 'new_feedback',
            'message'     => 'Feedback baru: ' . $feedback->subject,
            'related_url' => route('admin.feedbacks.index'),
        ]);

        return redirect()->back()->with('success', 'Terima kasih! Masukan kamu telah dikirim.');
    }
}