<!DOCTYPE html>
<html lang="id" class="no-flash">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Librova') — Read More. Discover More.</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Bootstrap Icons CDN --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @stack('styles')

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
        html.no-flash body { visibility: hidden; }
        .menu-toggle { display: none; background: none; border: none; cursor: pointer; color: var(--tx2); padding: 8px; border-radius: 8px; transition: background 0.2s; margin-left: auto; }
        .menu-toggle:hover { background: var(--surface2); }
        .mobile-menu { position: absolute; top: var(--nav-h); left: 0; right: 0; background: var(--surface); border-bottom: 1px solid var(--border); box-shadow: 0 4px 12px var(--shadow); padding: 16px 24px; display: flex; flex-direction: column; gap: 12px; z-index: 99; }
        @media (max-width: 900px) { .menu-toggle { display: flex; align-items: center; justify-content: center; } .nav-links, .nav-search { display: none; } }
        html[data-theme='light'] .theme-btn .icon-moon { display: none; }
        html[data-theme='dark'] .theme-btn .icon-sun { display: none; }
    </style>
</head>

<body>
    <nav class="navbar" x-data="{ 
        mobileMenuOpen: false,
        darkMode: localStorage.getItem('librova-theme') === 'dark',
        toggleTheme() {
            this.darkMode = !this.darkMode;
            const theme = this.darkMode ? 'dark' : 'light';
            localStorage.setItem('librova-theme', theme);
            document.documentElement.setAttribute('data-theme', theme);
        }
    }">
        <div class="nav-inner">
            <a href="{{ route('home') }}" class="logo">
                <span class="logo-icon">
                    <i class="bi bi-book" style="font-size: 1.2rem; color: white;"></i>
                </span>
                Librova
            </a>

            <div class="nav-search">
                <i class="bi bi-search" style="position: absolute; left: 13px; top: 50%; transform: translateY(-50%); color: var(--tx3); pointer-events: none;"></i>
                <form action="{{ route('books.index') }}" method="GET">
                    <input type="text" name="search" placeholder="Cari buku, penulis, ISBN…" value="{{ request('search') }}">
                </form>
            </div>

            <div class="nav-links">
                <a href="{{ route('books.index') }}" class="nav-link">Buku</a>
                <a href="{{ route('categories.index') }}" class="nav-link">Kategori</a>
                @auth
                <a href="{{ route('profile.bookmarks') }}" class="nav-link">Bookmark</a>
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="nav-link flex items-center gap-1">
                        {{ auth()->user()->name }}
                        <i class="bi bi-chevron-down" style="font-size: 0.75rem;"></i>
                    </button>
                    <div x-show="open" x-cloak @click.away="open = false" class="absolute right-0 mt-2 w-56 bg-surface dark:bg-surface-dark border border-border rounded-xl shadow-xl py-2 z-50" style="background: var(--surface); border: 1px solid var(--border);">
                        <a href="{{ route('dashboard') }}" class="dropdown-item"><i class="bi bi-speedometer2"></i> Dashboard</a>
                        <a href="{{ route('profile') }}" class="dropdown-item"><i class="bi bi-person"></i> Profil Saya</a>
                        <a href="{{ route('profile.history') }}" class="dropdown-item"><i class="bi bi-clock-history"></i> Riwayat Baca</a>
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="dropdown-item" style="color: var(--primary);"><i class="bi bi-shield-lock"></i> Panel Admin</a>
                        @endif
                        <hr style="border-color: var(--border); margin: 4px 0;">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item w-full text-left" style="color: #B91C1C;"><i class="bi bi-box-arrow-right"></i> Keluar</button>
                        </form>
                    </div>
                </div>
                @else
                <a href="{{ route('login') }}" class="btn-outline">Masuk</a>
                <a href="{{ route('register') }}" class="btn-primary">Daftar</a>
                @endauth

                <button class="theme-btn" @click="toggleTheme" aria-label="Toggle dark mode">
                    <i class="bi bi-moon icon-moon" style="font-size: 1.1rem;"></i>
                    <i class="bi bi-sun icon-sun" style="font-size: 1.1rem;"></i>
                </button>
            </div>

            <button class="menu-toggle" @click="mobileMenuOpen = !mobileMenuOpen" aria-label="Toggle menu">
                <i class="bi bi-list" x-show="!mobileMenuOpen" style="font-size: 1.5rem;"></i>
                <i class="bi bi-x" x-cloak x-show="mobileMenuOpen" style="font-size: 1.5rem;"></i>
            </button>
        </div>

        <div x-show="mobileMenuOpen" x-cloak class="mobile-menu" 
             x-transition:enter="transition ease-out duration-200" 
             x-transition:enter-start="opacity-0 -translate-y-2" 
             x-transition:enter-end="opacity-100 translate-y-0" 
             x-transition:leave="transition ease-in duration-150" 
             x-transition:leave-start="opacity-100 translate-y-0" 
             x-transition:leave-end="opacity-0 -translate-y-2"
             @click.away="mobileMenuOpen = false">
            <div style="display: flex; position: relative; max-width: 100%;">
                <i class="bi bi-search" style="position: absolute; left: 13px; top: 50%; transform: translateY(-50%); color: var(--tx3); pointer-events: none;"></i>
                <form action="{{ route('books.index') }}" method="GET" style="width: 100%;">
                    <input type="text" name="search" placeholder="Cari buku..." style="width: 100%; padding: 9px 16px 9px 40px; border-radius: 100px; border: 1.5px solid var(--border); background: var(--surface2); color: var(--tx); font-family: inherit; font-size: 0.875rem;">
                </form>
            </div>

            <a href="{{ route('books.index') }}" class="nav-link">Buku</a>
            <a href="{{ route('categories.index') }}" class="nav-link">Kategori</a>
            @auth
                <a href="{{ route('profile.bookmarks') }}" class="nav-link"></i>Bookmark</a>
                <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
                <a href="{{ route('profile') }}" class="nav-link">Profil</a>
                <a href="{{ route('profile.history') }}" class="nav-link">Riwayat</a>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="nav-link" style="color: var(--primary);"><i class="bi bi-shield-lock"></i> Panel Admin</a>
                @endif
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn-outline" style="width: 100%;"><i class="bi bi-box-arrow-right"></i> Keluar</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn-outline" style="display: block; text-align: center;">Masuk</a>
                <a href="{{ route('register') }}" class="btn-primary" style="display: block; text-align: center;">Daftar</a>
            @endauth

            <hr style="border-color: var(--border); margin: 8px 0;">
            <button @click="toggleTheme" style="display: flex; align-items: center; gap: 8px; background: none; border: none; cursor: pointer; color: var(--tx2); padding: 8px 0; font-size: 0.9rem;">
                <i class="bi" :class="darkMode ? 'bi-sun' : 'bi-moon'"></i>
                <span x-text="darkMode ? 'Mode Terang' : 'Mode Gelap'"></span>
            </button>
        </div>
    </nav>

    <main>@yield('content')</main>

    <footer>
        <div class="footer-inner">
            <div class="footer-brand">
                <div class="logo" style="margin-bottom:12px">
                    <span class="logo-icon" style="background:#FAF7F2;color:#2C5F2E">
                        <i class="bi bi-book" style="font-size: 1.2rem;"></i>
                    </span>
                    Librova
                </div>
                <p>Perpustakaan digital untuk semua. Temukan buku yang mengubah cara pandangmu tentang dunia.</p>
            </div>
            <div class="footer-col">
                <h4>Navigasi</h4>
                <a href="{{ route('home') }}">Beranda</a>
                <a href="{{ route('books.index') }}">Koleksi Buku</a>
                <a href="{{ route('categories.index') }}">Kategori</a>
                <a href="{{ route('books.index', ['sort' => 'popular']) }}">Terpopuler</a>
                <a href="{{ route('books.index', ['sort' => 'newest']) }}">Terbaru</a>
            </div>
            <div class="footer-col">
                <h4>Akun</h4>
                @auth
                <a href="{{ route('profile') }}">Profil</a>
                <a href="{{ route('profile.bookmarks') }}">Bookmark</a>
                <a href="{{ route('profile.history') }}">Riwayat Baca</a>
                <form action="{{ route('logout') }}" method="POST">@csrf<button type="submit" class="text-left text-sm">Keluar</button></form>
                @else
                <a href="{{ route('login') }}">Masuk</a>
                <a href="{{ route('register') }}">Daftar</a>
                @endauth
            </div>
        </div>
        <div class="footer-bottom">
            <span>© {{ date('Y') }} Librova. All rights reserved.</span>
            <span>Read More. Discover More.</span>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>