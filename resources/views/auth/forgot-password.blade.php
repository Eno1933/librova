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
    <title>Lupa Password — Librova</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .forgot-container {
            display: flex;
            min-height: 100vh;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: var(--bg);
            position: relative;
        }
        .forgot-card {
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
        .icon-lock {
            width: 64px;
            height: 64px;
            margin: 0 auto 1.5rem;
            background: var(--surface2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .icon-lock svg {
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
            width: 100%;
            justify-content: center;
        }
        .btn-primary:hover { background: var(--primary-h); }
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
        .input-group {
            margin-bottom: 1.2rem;
            text-align: left;
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
    </style>
</head>
<body>
    <a href="{{ route('home') }}" class="back-home">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Beranda
    </a>

    <div class="forgot-container" x-data="{ cooldown: 0, timer: null }">
        <div class="forgot-card">
            <div class="icon-lock">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>

            <h2 style="font-family: 'Playfair Display', serif; font-size: 1.8rem; margin-bottom: 1rem; color: var(--tx);">
                Lupa Password?
            </h2>
            <p style="color: var(--tx2); font-size: 0.95rem; margin-bottom: 1.5rem; line-height: 1.6;">
                Masukkan email Anda dan kami akan mengirimkan link untuk mereset password.
            </p>

            @if (session('success'))
                <div class="message-box">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div style="background:#FCE4EC; color:#C62828; padding:0.75rem; border-radius:12px; margin-bottom:1.5rem; font-size:0.9rem; text-align:left;">
                    <ul style="margin:0; padding-left:1.2rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" @submit="
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
                <div class="input-group">
                    <label for="email">Alamat Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus placeholder="nama@email.com">
                </div>

                <button type="submit" class="btn btn-primary" :disabled="cooldown > 0">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span x-show="cooldown === 0">Kirim Link Reset</span>
                    <span x-show="cooldown > 0" x-text="'Kirim Ulang (' + cooldown + ')'"></span>
                </button>
            </form>

            <p style="margin-top: 1.5rem; font-size: 0.9rem; color: var(--tx2);">
                <a href="{{ route('login') }}" style="color: var(--primary); font-weight: 600;">← Kembali ke login</a>
            </p>
        </div>
    </div>
</body>
</html>