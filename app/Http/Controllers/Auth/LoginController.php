<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Memproses login.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        // 1. User tidak ditemukan
        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'Email atau password salah.',
            ]);
        }

        // 2. User adalah akun Google (tidak punya password)
        if (!$user->password) {
            throw ValidationException::withMessages([
                'email' => 'Akun ini terdaftar menggunakan Google. Silakan masuk dengan tombol Google.',
            ]);
        }

        // 3. Password salah (gunakan Auth::validate untuk hash check)
        if (!Auth::validate($credentials)) {
            throw ValidationException::withMessages([
                'email' => 'Email atau password salah.',
            ]);
        }

        // 4. Email belum diverifikasi
        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice')
                ->with('warning', 'Silakan verifikasi email terlebih dahulu. Email verifikasi telah dikirim ke ' . $user->email);
        }

        // 5. Login sukses
        $remember = $request->boolean('remember');

        Auth::login($user, $remember);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'))
            ->with('success', 'Selamat datang kembali, ' . $user->name . '!');
    }

    /**
     * Logout.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'Anda telah keluar.');
    }
}
