<!DOCTYPE html>
<html lang="id"
      x-data="{
          dark: localStorage.getItem('librova-theme') === 'dark',
          showPw: false,
          init() {
              this.$watch('dark', val => {
                  const t = val ? 'dark' : 'light';
                  localStorage.setItem('librova-theme', t);
                  document.documentElement.setAttribute('data-theme', t);
              });
          }
      }"
      :data-theme="dark ? 'dark' : 'light'">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk — Librova</title>

    {{-- Flash prevention --}}
    <script>
        (function(){
            const s = localStorage.getItem('librova-theme');
            const p = window.matchMedia('(prefers-color-scheme: dark)').matches;
            document.documentElement.setAttribute('data-theme', s ?? (p ? 'dark' : 'light'));
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
    /* ─────────────────────────────────────
       LOGIN PAGE STYLES
    ───────────────────────────────────── */

    /* Layout */
    .auth-shell {
        display: grid;
        grid-template-columns: 1fr 1fr;
        min-height: 100vh;
    }
    @media(max-width: 860px) { .auth-shell { grid-template-columns: 1fr; } }

    /* ── Left panel ── */
    .auth-left {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 32px 24px;
        position: relative;
        overflow-y: auto;
        min-height: 100vh;
    }

    /* Top bar */
    .auth-topbar {
        position: absolute; top: 24px; left: 0; right: 0;
        padding: 0 24px;
        display: flex; align-items: center; justify-content: space-between;
        z-index: 10;
    }
    .auth-back {
        display: inline-flex; align-items: center; gap: 7px;
        font-size: .82rem; font-weight: 500; color: var(--tx2);
        padding: 7px 14px; border-radius: 100px;
        border: 1.5px solid var(--border); background: var(--surface);
        text-decoration: none;
        transition: color .15s, border-color .15s;
    }
    .auth-back:hover { color: var(--primary); border-color: var(--primary); }
    .auth-theme-btn {
        width: 38px; height: 38px; border-radius: 50%;
        border: 1.5px solid var(--border); background: var(--surface);
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; font-size: 15px;
        transition: background .2s, border-color .2s;
        color: var(--tx2);
    }
    .auth-theme-btn:hover { background: var(--surface2); border-color: var(--border2); }

    /* Card */
    .auth-card { width: 100%; max-width: 400px; padding: 20px 0; }

    /* Logo */
    .auth-logo {
        display: inline-flex; align-items: center; gap: 9px;
        font-family: 'Playfair Display', serif;
        font-size: 1.55rem; font-weight: 700; color: var(--primary);
        text-decoration: none; margin-bottom: 28px;
    }
    .auth-logo-box {
        width: 36px; height: 36px; border-radius: 10px;
        background: var(--primary);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        transition: transform .2s cubic-bezier(.34,1.56,.64,1);
    }
    .auth-logo:hover .auth-logo-box { transform: rotate(-6deg) scale(1.08); }

    /* Heading */
    .auth-heading {
        font-family: 'Playfair Display', serif;
        font-size: 1.85rem; font-weight: 700;
        letter-spacing: -.025em; color: var(--tx);
        margin-bottom: 6px; line-height: 1.2;
    }
    .auth-heading em { font-style: italic; color: var(--primary); }
    .auth-sub { font-size: .9rem; color: var(--tx2); margin-bottom: 28px; line-height: 1.6; }

    /* Alerts */
    .auth-alert {
        display: flex; align-items: flex-start; gap: 10px;
        padding: 11px 14px; border-radius: 10px;
        font-size: .84rem; line-height: 1.5; margin-bottom: 16px;
    }
    .auth-alert i { flex-shrink: 0; font-size: 1rem; margin-top: 1px; }
    .alert-success { background: #E8F5E9; color: #1a4a1c; border: 1px solid #A5D6A7; }
    .alert-warn    { background: #FFF8E1; color: #7B5800; border: 1px solid #FFD54F; }
    .alert-error   { background: #FEF2F2; color: #B91C1C; border: 1px solid #FECACA; }
    [data-theme="dark"] .alert-success { background: rgba(74,222,128,.09);  color: #86EFAC; border-color: rgba(74,222,128,.2); }
    [data-theme="dark"] .alert-warn    { background: rgba(251,191,36,.09);  color: #FBBF24; border-color: rgba(251,191,36,.2); }
    [data-theme="dark"] .alert-error   { background: rgba(252,165,165,.09); color: #FCA5A5; border-color: rgba(252,165,165,.2); }

    /* Form fields */
    .f-group { margin-bottom: 14px; }
    .f-label {
        display: block; margin-bottom: 6px;
        font-size: .78rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .05em;
        color: var(--tx2);
    }
    .f-input-wrap { position: relative; }
    .f-input-wrap > i {
        position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
        font-size: 1rem; color: var(--tx3); pointer-events: none;
        transition: color .2s;
    }
    .f-input {
        width: 100%; padding: 12px 14px 12px 40px;
        border-radius: 11px; border: 1.5px solid var(--border);
        background: var(--surface); color: var(--tx);
        font-family: inherit; font-size: .9rem;
        transition: border-color .2s, box-shadow .2s, background .3s;
    }
    .f-input::placeholder { color: var(--tx3); }
    .f-input:focus {
        outline: none; border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(44,95,46,.1); background: var(--surface);
    }
    [data-theme="dark"] .f-input:focus { box-shadow: 0 0 0 3px rgba(74,222,128,.1); }
    .f-input-wrap:focus-within > i { color: var(--primary); }

    /* Password toggle */
    .pw-toggle {
        position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
        background: none; border: none; cursor: pointer;
        color: var(--tx3); font-size: 1rem;
        display: flex; align-items: center; padding: 4px;
        transition: color .15s;
    }
    .pw-toggle:hover { color: var(--tx); }

    /* Remember + forgot */
    .auth-remember {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 18px;
    }
    .auth-remember label {
        display: flex; align-items: center; gap: 7px;
        font-size: .83rem; color: var(--tx2); cursor: pointer;
        user-select: none;
    }
    .auth-remember input[type="checkbox"] {
        width: 16px; height: 16px; accent-color: var(--primary); cursor: pointer;
    }
    .auth-forgot { font-size: .83rem; font-weight: 600; color: var(--primary); }
    .auth-forgot:hover { text-decoration: underline; }

    /* Submit */
    .auth-submit {
        width: 100%; padding: 13px; border-radius: 11px;
        background: var(--primary); color: #fff;
        font-family: inherit; font-size: .9rem; font-weight: 700;
        border: none; cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        transition: background .2s, transform .15s, box-shadow .2s;
        box-shadow: 0 4px 16px var(--shadow);
    }
    [data-theme="dark"] .auth-submit { color: var(--bg); }
    .auth-submit:hover { background: var(--primary-h); transform: translateY(-2px); box-shadow: 0 8px 24px var(--shadow); }
    .auth-submit:active { transform: translateY(0); }

    /* Divider */
    .auth-divider {
        display: flex; align-items: center; gap: 12px;
        margin: 20px 0; color: var(--tx3); font-size: .8rem; font-weight: 500;
    }
    .auth-divider::before, .auth-divider::after {
        content: ''; flex: 1; height: 1px; background: var(--border);
    }

    /* Google button */
    .auth-google {
        width: 100%; padding: 12px 14px; border-radius: 11px;
        border: 1.5px solid var(--border); background: var(--surface);
        color: var(--tx); font-family: inherit; font-size: .88rem; font-weight: 500;
        display: flex; align-items: center; justify-content: center; gap: 10px;
        cursor: pointer; text-decoration: none;
        transition: background .2s, border-color .2s, transform .15s;
    }
    .auth-google:hover { background: var(--surface2); border-color: var(--border2); transform: translateY(-1px); }

    /* Footer */
    .auth-footer { text-align: center; margin-top: 22px; font-size: .85rem; color: var(--tx2); }
    .auth-footer a { color: var(--primary); font-weight: 700; }
    .auth-footer a:hover { text-decoration: underline; }

    /* Trust badges */
    .auth-trust {
        display: flex; align-items: center; justify-content: center; gap: 16px;
        margin-top: 22px; flex-wrap: wrap;
    }
    .trust-item { display: flex; align-items: center; gap: 5px; font-size: .72rem; color: var(--tx3); font-weight: 500; }
    .trust-item i { font-size: .8rem; color: var(--primary); opacity: .7; }

    /* ── Right panel ── */
    .auth-right {
        position: relative; overflow: hidden;
        background: linear-gradient(150deg, #1d4220 0%, #2C5F2E 45%, #3a7a3d 100%);
        display: flex; align-items: center; justify-content: center;
        padding: 48px;
    }
    @media(max-width: 860px) { .auth-right { display: none; } }
    .auth-right::before {
        content: ''; position: absolute; inset: 0;
        background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,.12) 1px, transparent 0);
        background-size: 28px 28px; pointer-events: none;
    }
    .auth-right::after {
        content: ''; position: absolute;
        width: 500px; height: 500px; border-radius: 50%;
        background: radial-gradient(circle, rgba(201,168,76,.18) 0%, transparent 70%);
        top: 50%; left: 50%; transform: translate(-50%,-50%);
        pointer-events: none;
        animation: blobPulse 5s ease-in-out infinite;
    }
    @keyframes blobPulse {
        0%,100% { transform: translate(-50%,-50%) scale(1); opacity: .7; }
        50%      { transform: translate(-50%,-50%) scale(1.15); opacity: 1; }
    }
    .auth-right-inner {
        position: relative; z-index: 1; text-align: center; color: #fff; max-width: 380px;
    }
    .auth-right-logo {
        font-family: 'Playfair Display', serif; font-size: 2rem; font-weight: 700; color: #fff;
        display: flex; align-items: center; justify-content: center; gap: 10px; margin-bottom: 36px;
    }
    .auth-right-logo-box {
        width: 40px; height: 40px; border-radius: 11px;
        background: rgba(255,255,255,.18); backdrop-filter: blur(8px);
        display: flex; align-items: center; justify-content: center; font-size: 18px;
    }
    /* 3D books */
    .book-stack-wrap { position: relative; width: 240px; height: 280px; margin: 0 auto 36px; }
    .book-3d {
        position: absolute; border-radius: 6px 10px 10px 6px;
        display: flex; flex-direction: column; justify-content: flex-end; padding: 14px;
        box-shadow: 4px 8px 24px rgba(0,0,0,.25), inset -4px 0 8px rgba(0,0,0,.12);
        transition: transform .4s cubic-bezier(.34,1.56,.64,1);
    }
    .book-3d::before {
        content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 16px;
        background: rgba(0,0,0,.14); border-radius: 6px 0 0 6px;
    }
    .book-3d:hover { transform: translateY(-10px) rotate(-1.5deg) scale(1.03) !important; }
    .bk-a { width: 145px; height: 210px; top: 50px; left: 10px; z-index: 3; background: linear-gradient(140deg,#3a7a3d,#1d4220); transform: rotate(-5deg); }
    .bk-b { width: 132px; height: 193px; top: 65px; left: 72px; z-index: 2; background: linear-gradient(140deg,#7B5400,#4a3200); transform: rotate(3deg); }
    .bk-c { width: 126px; height: 178px; top: 80px; left: 128px; z-index: 1; background: linear-gradient(140deg,#1a3a5c,#0d2140); transform: rotate(7deg); }
    .bk-title-3d { position: relative; z-index: 1; font-family: 'Playfair Display',serif; color: rgba(255,255,255,.9); font-size: .68rem; font-weight: 600; line-height: 1.3; }
    .bk-auth-3d  { color: rgba(255,255,255,.5); font-size: .58rem; margin-top: 2px; position: relative; z-index: 1; }
    .bk-line     { width: 24px; height: 1.5px; background: rgba(255,255,255,.3); margin-bottom: 7px; border-radius: 1px; position: relative; z-index: 1; }
    /* Floating badges */
    .float-badge {
        position: absolute; z-index: 4;
        background: rgba(255,255,255,.12); backdrop-filter: blur(12px);
        border: 1px solid rgba(255,255,255,.2); border-radius: 10px;
        padding: 8px 12px;
    }
    .float-badge.ba { top: 14px; right: 0; animation: floatBadge 3s ease-in-out infinite; }
    .float-badge.bb { bottom: 28px; left: 0; animation: floatBadge 3s ease-in-out 1.6s infinite; }
    @keyframes floatBadge { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-7px)} }
    .fb-label { font-size: .62rem; color: rgba(255,255,255,.6); font-weight: 500; }
    .fb-val   { font-size: .82rem; font-weight: 700; color: #fff; margin-top: 2px; }
    .fb-stars { color: var(--gold); font-size: 11px; letter-spacing: 1px; }
    /* Right text */
    .right-headline { font-family: 'Playfair Display',serif; font-size: 1.9rem; font-weight: 700; line-height: 1.2; margin-bottom: 12px; }
    .right-desc     { font-size: .9rem; opacity: .8; line-height: 1.65; }
    .right-stats    { display: flex; justify-content: center; gap: 24px; margin-top: 28px; }
    .right-stat-num { font-family: 'Playfair Display',serif; font-size: 1.5rem; font-weight: 700; color: #fff; line-height: 1; margin-bottom: 3px; }
    .right-stat-lbl { font-size: .7rem; color: rgba(255,255,255,.6); font-weight: 500; }
    .right-stat-sep { width: 1px; background: rgba(255,255,255,.2); }
    </style>
</head>
<body style="background:var(--bg); margin:0; font-family:'Plus Jakarta Sans',sans-serif;">

<div class="auth-shell">

    {{-- ── LEFT: FORM ── --}}
    <div class="auth-left">

        {{-- Top bar --}}
        <div class="auth-topbar">
            <a href="{{ route('home') }}" class="auth-back">
                <i class="bi bi-arrow-left"></i> Beranda
            </a>
            <button class="auth-theme-btn" @click="dark = !dark" :title="dark ? 'Light mode' : 'Dark mode'">
                <i class="bi" :class="dark ? 'bi-sun' : 'bi-moon-stars'"></i>
            </button>
        </div>

        {{-- Form card --}}
        <div class="auth-card">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="auth-logo">
                <span class="auth-logo-box">
                    <i class="bi bi-book" style="color:#fff;font-size:17px"></i>
                </span>
                Librova
            </a>

            {{-- Heading --}}
            <h1 class="auth-heading">Selamat <em>datang</em> kembali</h1>
            <p class="auth-sub">Masuk untuk melanjutkan perjalanan membacamu bersama Librova.</p>

            {{-- Alerts --}}
            @if(session('status'))
            <div class="auth-alert alert-success">
                <i class="bi bi-check-circle-fill"></i>
                <span>{{ session('status') }}</span>
            </div>
            @endif

            @if(session('warning'))
            <div class="auth-alert alert-warn">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span>{{ session('warning') }}</span>
            </div>
            @endif

            @if($errors->any())
            <div class="auth-alert alert-error">
                <i class="bi bi-exclamation-circle-fill"></i>
                <ul style="margin:0;padding-left:1.1rem">
                    @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Email --}}
                <div class="f-group">
                    <label class="f-label" for="email">Alamat Email</label>
                    <div class="f-input-wrap">
                        <i class="bi bi-envelope"></i>
                        <input class="f-input" type="email" name="email" id="email"
                               value="{{ old('email') }}" required autofocus
                               placeholder="nama@email.com" autocomplete="email">
                    </div>
                </div>

                {{-- Password --}}
                <div class="f-group">
                    <label class="f-label" for="password">Kata Sandi</label>
                    <div class="f-input-wrap">
                        <i class="bi bi-lock"></i>
                        <input class="f-input" :type="showPw ? 'text' : 'password'"
                               name="password" id="password" required
                               placeholder="••••••••" autocomplete="current-password"
                               style="padding-right:42px">
                        <button type="button" class="pw-toggle" @click="showPw = !showPw" :title="showPw ? 'Sembunyikan' : 'Tampilkan'">
                            <i class="bi" :class="showPw ? 'bi-eye-slash' : 'bi-eye'"></i>
                        </button>
                    </div>
                </div>

                {{-- Remember + forgot --}}
                <div class="auth-remember">
                    <label>
                        <input type="checkbox" name="remember" id="remember">
                        Ingat saya
                    </label>
                    <a href="{{ route('password.request') }}" class="auth-forgot">Lupa kata sandi?</a>
                </div>

                {{-- Submit --}}
                <button type="submit" class="auth-submit">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Masuk
                </button>
            </form>

            {{-- Divider --}}
            <div class="auth-divider">atau masuk dengan</div>

            {{-- Google --}}
            <a href="{{ route('google.login') }}" class="auth-google">
                <svg width="20" height="20" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                Lanjutkan dengan Google
            </a>

            {{-- Footer --}}
            <p class="auth-footer">
                Belum punya akun?
                <a href="{{ route('register') }}">Daftar sekarang</a>
            </p>

            {{-- Trust badges --}}
            <div class="auth-trust">
                <div class="trust-item"><i class="bi bi-shield-lock-fill"></i> SSL Terenkripsi</div>
                <div class="trust-item"><i class="bi bi-lock-fill"></i> Data Aman</div>
                <div class="trust-item"><i class="bi bi-eye-slash-fill"></i> Privasi Terjaga</div>
            </div>

        </div>
    </div>

    {{-- ── RIGHT: VISUAL ── --}}
    <div class="auth-right">
        <div class="auth-right-inner">

            {{-- Logo --}}
            <div class="auth-right-logo">
                <span class="auth-right-logo-box">
                    <i class="bi bi-book" style="color:#fff"></i>
                </span>
                Librova
            </div>

            {{-- 3D Book Stack --}}
            <div class="book-stack-wrap">
                <div class="float-badge ba">
                    <div class="fb-label">Rating Tertinggi</div>
                    <div class="fb-val">4.9 <span class="fb-stars">★★★★★</span></div>
                </div>
                <div class="float-badge bb">
                    <div class="fb-label">Dibaca Hari Ini</div>
                    <div class="fb-val">{{ number_format(\App\Models\Book::sum('view_count')) }}</div>
                </div>

                <div class="book-3d bk-c">
                    <div class="bk-line"></div>
                    <div class="bk-title-3d">Clean Code</div>
                    <div class="bk-auth-3d">Robert C. Martin</div>
                </div>
                <div class="book-3d bk-b">
                    <div class="bk-line"></div>
                    <div class="bk-title-3d">Sapiens</div>
                    <div class="bk-auth-3d">Yuval Noah Harari</div>
                </div>
                <div class="book-3d bk-a">
                    <div class="bk-line"></div>
                    <div class="bk-title-3d">Atomic Habits</div>
                    <div class="bk-auth-3d">James Clear</div>
                </div>
            </div>

            {{-- Text --}}
            <div class="right-headline">Read More.<br>Discover More.</div>
            <p class="right-desc">
                Jelajahi ribuan e-book, beri rating, dan temukan wawasan baru dalam setiap halaman.
            </p>

            {{-- Live stats --}}
            <div class="right-stats">
                <div>
                    <div class="right-stat-num">{{ number_format(\App\Models\Book::count()) }}</div>
                    <div class="right-stat-lbl">Koleksi Buku</div>
                </div>
                <div class="right-stat-sep"></div>
                <div>
                    <div class="right-stat-num">{{ number_format(\App\Models\User::where('role','user')->count()) }}</div>
                    <div class="right-stat-lbl">Pembaca Aktif</div>
                </div>
                <div class="right-stat-sep"></div>
                <div>
                    <div class="right-stat-num">{{ \App\Models\Category::count() }}</div>
                    <div class="right-stat-lbl">Kategori</div>
                </div>
            </div>

        </div>
    </div>

</div>

</body>
</html>