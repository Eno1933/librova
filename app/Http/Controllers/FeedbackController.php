<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use App\Models\Feedback;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;   // ← pastikan ada

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
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        try {
            $validated = $request->validate([
                'subject' => 'required|string|max:255',
                'message' => 'required|string|min:10|max:2000',
            ]);
        } catch (ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'errors'  => $e->errors(),
                    'message' => 'Validasi gagal. Periksa kembali isian Anda.',
                ], 422);
            }
            throw $e;
        }

        $feedback = Feedback::create([
            'user_id' => auth()->check() ? auth()->id() : null,
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'status'  => 'new',
        ]);

        AdminNotification::create([
            'type'        => 'new_feedback',
            'message'     => 'Feedback baru: ' . $feedback->subject,
            'related_url' => route('admin.feedbacks.index'),
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['message' => 'Terima kasih! Masukan kamu telah dikirim.']);
        }

        return redirect()->back()->with('success', 'Terima kasih! Masukan kamu telah dikirim.');
    }
}