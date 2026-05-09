<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationController extends Controller
{
    /**
     * Menampilkan halaman "Cek Email Anda".
     */
    public function notice(): View
    {
        return view('auth.verify-email');
    }

    /**
     * Verifikasi email dari link yang diklik.
     */
    public function verify(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Jika email sudah diverifikasi, langsung ke dashboard
        if ($user->hasVerifiedEmail()) {
            return redirect()->route('dashboard')
                ->with('success', 'Email Anda sudah diverifikasi sebelumnya.');
        }

        // Tandai email sebagai terverifikasi
        $user->markEmailAsVerified();

        // Login otomatis setelah verifikasi (sudah login, tapi kita pastikan session direfresh)
        auth()->login($user);

        return redirect()->route('dashboard')
            ->with('success', 'Email berhasil diverifikasi! Selamat datang di Librova.');
    }

    /**
     * Kirim ulang email verifikasi.
     */
    public function resend(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', 'Link verifikasi baru telah dikirim ke email Anda.');
    }
}