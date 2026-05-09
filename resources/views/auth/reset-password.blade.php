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
    <title>Reset Password — Librova</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .reset-container {
            display: flex;
            min-height: 100vh;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: var(--bg);
            position: relative;
        }
        .reset-card {
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
        .icon-key {
            width: 64px;
            height: 64px;
            margin: 0 auto 1.5rem;
            background: var(--surface2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .icon-key svg {
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
            width: 100%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        .btn-primary:hover { background: var(--primary-h); }
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

    <div class="reset-container">
        <div class="reset-card">
            <div class="icon-key">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
            </div>

            <h2 style="font-family: 'Playfair Display', serif; font-size: 1.8rem; margin-bottom: 1rem; color: var(--tx);">
                Reset Password
            </h2>
            <p style="color: var(--tx2); font-size: 0.95rem; margin-bottom: 1.5rem; line-height: 1.6;">
                Masukkan password baru untuk akun Anda.
            </p>

            @if ($errors->any())
                <div style="background:#FCE4EC; color:#C62828; padding:0.75rem; border-radius:12px; margin-bottom:1.5rem; font-size:0.9rem; text-align:left;">
                    <ul style="margin:0; padding-left:1.2rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email ?? old('email') }}">

                <div class="input-group">
                    <label for="password">Password Baru</label>
                    <input type="password" name="password" id="password" required placeholder="Minimal 8 karakter">
                </div>

                <div class="input-group">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="Ulangi password">
                </div>

                <button type="submit" class="btn btn-primary">Reset Password</button>
            </form>

            <p style="margin-top: 1.5rem; font-size: 0.9rem; color: var(--tx2);">
                <a href="{{ route('login') }}" style="color: var(--primary); font-weight: 600;">← Kembali ke login</a>
            </p>
        </div>
    </div>
</body>
</html>