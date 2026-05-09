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
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk — Librova</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        (function() {
            const stored = localStorage.getItem('librova-theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            let theme = stored ? stored : (prefersDark ? 'dark' : 'light');
            document.documentElement.setAttribute('data-theme', theme);
            document.documentElement.classList.remove('no-flash');
        })();
    </script>
    
    <style>
        /* Tambahan kecil untuk layout login yang tidak ada di custom.css */
        .login-container {
            display: flex;
            min-height: 100vh;
        }
        .login-left {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
        }
        .login-right {
            flex: 1;
            background: linear-gradient(135deg, var(--primary), var(--primary-h));
            display: none;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }
        .login-right .pattern {
            position: absolute;
            inset: 0;
            opacity: 0.08;
            background-image: radial-gradient(circle, white 2px, transparent 2px);
            background-size: 30px 30px;
        }
        @media (min-width: 900px) {
            .login-right { display: flex; }
        }
        .back-home {
            position: absolute;
            top: 24px;
            left: 24px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--tx2);
            font-size: 0.9rem;
            font-weight: 500;
            text-decoration: none;
            transition: color 0.2s;
            z-index: 10;
        }
        .back-home:hover { color: var(--primary); }
        .back-home svg { width: 18px; height: 18px; }
        .theme-btn {
            position: absolute;
            top: 24px;
            right: 24px;
            z-index: 10;
        }
        .login-form {
            width: 100%;
            max-width: 420px;
        }
        .login-form h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            margin-bottom: 0.5rem;
            color: var(--tx);
        }
        .login-form p {
            color: var(--tx2);
            margin-bottom: 2rem;
        }
        .input-group {
            margin-bottom: 1.2rem;
        }
        .input-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--tx2);
            font-weight: 500;
            font-size: 0.9rem;
        }
        .input-group input {
            width: 100%;
            padding: 0.85rem 1rem;
            border-radius: 12px;
            border: 1.5px solid var(--border);
            background: var(--surface);
            color: var(--tx);
            font-family: inherit;
            font-size: 0.95rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .input-group input::placeholder { color: var(--tx3); }
        .input-group input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--shadow);
        }
        .btn-login {
            width: 100%;
            padding: 0.9rem;
            border-radius: 12px;
            background: var(--primary);
            color: white;
            font-weight: 600;
            font-size: 1rem;
            border: none;
            cursor: pointer;
            transition: background 0.2s, transform 0.15s;
            margin-top: 0.5rem;
        }
        .btn-login:hover { background: var(--primary-h); transform: translateY(-1px); }
        .btn-google {
            width: 100%;
            padding: 0.85rem;
            border-radius: 12px;
            border: 1.5px solid var(--border);
            background: var(--surface);
            color: var(--tx);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-weight: 500;
            font-size: 0.95rem;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 0.8rem;
            text-decoration: none;
        }
        .btn-google:hover { background: var(--surface2); }
        .divider-text {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 1.8rem 0;
            color: var(--tx3);
            font-size: 0.85rem;
        }
        .divider-text::before,
        .divider-text::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }
        .check-remember {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }
        .check-remember label {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--tx2);
            font-size: 0.9rem;
        }
        .check-remember a {
            color: var(--primary);
            font-size: 0.9rem;
        }
    </style>
</head>
<body style="background:var(--bg); margin:0; font-family:'Plus Jakarta Sans',sans-serif;">

<div class="login-container">
    <!-- Kiri: Form -->
    <div class="login-left">
        <!-- Tombol Kembali -->
        <a href="{{ route('home') }}" class="back-home">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>

        <!-- Tombol Dark Mode -->
        <button class="theme-btn" @click="darkMode = !darkMode" aria-label="Toggle dark mode" style="background:transparent; border:1.5px solid var(--border); border-radius:100px; width:38px; height:38px; display:flex; align-items:center; justify-content:center; cursor:pointer;">
            <svg x-show="!darkMode" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
            <svg x-show="darkMode" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
        </button>

        <div class="login-form">
            <!-- Logo -->
            <div style="text-align:center; margin-bottom:2rem;">
                <a href="{{ route('home') }}" style="display:inline-flex; align-items:center; gap:8px; font-family:'Playfair Display',serif; font-size:1.9rem; font-weight:700; color:var(--primary); text-decoration:none;">
                    <span class="logo-icon" style="width:36px; height:36px; background:var(--primary); border-radius:10px; display:flex; align-items:center; justify-content:center;">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
                    </span>
                    Librova
                </a>
                <h2>Selamat Datang Kembali</h2>
                <p>Masuk untuk melanjutkan membaca.</p>
            </div>

            <!-- Session Status -->
            @if(session('status'))
                <div style="background:var(--surface2); color:var(--tx); padding:1rem; border-radius:10px; margin-bottom:1rem; border:1px solid var(--border); font-size:0.9rem;">
                    {{ session('status') }}
                </div>
            @endif
            @if(session('warning'))
                <div style="background:#FFF3E0; color:#E65100; padding:1rem; border-radius:10px; margin-bottom:1rem; font-size:0.9rem;">
                    {{ session('warning') }}
                </div>
            @endif
            @if($errors->any())
                <div style="background:#FCE4EC; color:#C62828; padding:1rem; border-radius:10px; margin-bottom:1rem; font-size:0.9rem;">
                    <ul style="margin:0; padding-left:1.2rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="input-group">
                    <label for="email">Alamat Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus placeholder="nama@email.com">
                </div>

                <div class="input-group">
                    <label for="password">Kata Sandi</label>
                    <input type="password" name="password" id="password" required placeholder="••••••••">
                </div>

                <div class="check-remember">
                    <label>
                        <input type="checkbox" name="remember" id="remember">
                        Ingat saya
                    </label>
                    <a href="{{ route('password.request') }}">Lupa password?</a>
                </div>

                <button type="submit" class="btn-login">Masuk</button>
            </form>

            <!-- Divider -->
            <div class="divider-text">atau masuk dengan</div>

            <!-- Google Login -->
            <a href="{{ route('google.login') }}" class="btn-google">
                <svg width="20" height="20" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                Google
            </a>

            <p style="text-align:center; margin-top:2rem; color:var(--tx2);">
                Belum punya akun?
                <a href="{{ route('register') }}" style="color:var(--primary); font-weight:600;">Daftar sekarang</a>
            </p>
        </div>
    </div>

    <!-- Kanan: Ilustrasi -->
    <div class="login-right">
        <div class="pattern"></div>
        <div style="text-align:center; color:white; position:relative; z-index:1; max-width:80%;">
            <svg width="160" height="130" viewBox="0 0 200 160" fill="none" style="margin:0 auto 1.8rem;">
                <path d="M20 20 L100 40 L180 20 L160 140 L100 120 L40 140 Z" fill="rgba(255,255,255,0.2)" stroke="white" stroke-width="2"/>
                <path d="M60 40 Q100 60 140 40" stroke="white" stroke-width="2" fill="none"/>
                <path d="M70 60 Q100 80 130 60" stroke="white" stroke-width="2" fill="none"/>
                <path d="M80 80 Q100 100 120 80" stroke="white" stroke-width="2" fill="none"/>
            </svg>
            <h2 style="font-family:'Playfair Display',serif; font-size:2.5rem; margin-bottom:1rem; line-height:1.2;">Read More.<br>Discover More.</h2>
            <p style="font-size:1.1rem; opacity:0.9;">Jelajahi ribuan e‑book, beri rating, dan temukan dunia baru dalam setiap halaman.</p>
        </div>
    </div>
</div>

</body>
</html>