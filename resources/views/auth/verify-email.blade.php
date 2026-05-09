<!DOCTYPE html>
<html lang="id"
      x-data="{ darkMode: localStorage.getItem('librova-theme') === 'dark' }"
      x-init="
          $watch('darkMode', val => {
              localStorage.setItem('librova-theme', val ? 'dark' : 'light');
              document.documentElement.setAttribute('data-theme', val ? 'dark' : 'light');
          });
          if (darkMode) document.documentElement.setAttribute('data-theme', 'dark');
          else document.documentElement.setAttribute('data-theme', 'light');
      "
      x-bind:data-theme="darkMode ? 'dark' : 'light'">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email — Librova</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .verify-container {
            display: flex;
            min-height: 100vh;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: var(--bg);
        }
        .verify-card {
            width: 100%;
            max-width: 440px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 2.5rem 2rem;
            text-align: center;
            box-shadow: 0 4px 24px var(--shadow);
        }
        .back-home {
            position: absolute;
            top: 1.5rem;
            left: 1.5rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--tx2);
            font-size: 0.9rem;
            font-weight: 500;
            text-decoration: none;
            transition: color 0.2s;
        }
        .back-home:hover { color: var(--primary); }
        .icon-envelope {
            width: 64px;
            height: 64px;
            margin: 0 auto 1.5rem;
            background: var(--surface2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .icon-envelope svg {
            width: 32px;
            height: 32px;
            color: var(--primary);
        }
        .btn {
            padding: 12px 24px;
            border-radius: 100px;
            font-size: 0.95rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        .btn-primary:hover:not(:disabled) { background: var(--primary-h); }
        .btn-primary:disabled { opacity: 0.6; cursor: not-allowed; }
        .btn-outline {
            background: transparent;
            color: var(--primary);
            border: 1.5px solid var(--primary);
        }
        .btn-outline:hover { background: rgba(44,95,46,0.07); }
        .message-box {
            background: #E8F5E9;
            color: #2C5F2E;
            padding: 0.75rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }
        [data-theme="dark"] .message-box {
            background: rgba(74,222,128,0.1);
            color: #86EFAC;
        }
    </style>
</head>
<body style="position: relative;">

    {{-- Tombol kembali --}}
    <a href="{{ route('home') }}" class="back-home">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Beranda
    </a>

    <div class="verify-container">
        <div class="verify-card" x-data="{ cooldown: 0, timer: null }" x-init="() => {}">
            {{-- Ikon amplop --}}
            <div class="icon-envelope">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>

            <h2 style="font-family: 'Playfair Display', serif; font-size: 1.8rem; margin-bottom: 1rem; color: var(--tx);">
                Cek Email Anda
            </h2>
            <p style="color: var(--tx2); font-size: 0.95rem; margin-bottom: 1.5rem; line-height: 1.6;">
                Kami telah mengirimkan link verifikasi ke<br>
                <strong style="color: var(--tx);">{{ auth()->user()->email }}</strong>
            </p>

            @if (session('success'))
                <div class="message-box">
                    {{ session('success') }}
                </div>
            @endif

            <p style="color: var(--tx3); font-size: 0.85rem; margin-bottom: 2rem;">
                Belum menerima email? Periksa folder spam atau klik tombol di bawah untuk mengirim ulang.
            </p>

            <form method="POST" action="{{ route('verification.send') }}" @submit="
                cooldown = 30;
                if (timer) clearInterval(timer);
                timer = setInterval(() => {
                    cooldown--;
                    if (cooldown <= 0) {
                        clearInterval(timer);
                        timer = null;
                    }
                }, 1000);
            ">
                @csrf
                <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                    <button type="submit" class="btn btn-primary" :disabled="cooldown > 0">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span x-show="cooldown === 0">Kirim Ulang Email</span>
                        <span x-show="cooldown > 0" x-text="'Kirim Ulang (' + cooldown + ')'"></span>
                    </button>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline">Nanti Saja</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>