<!DOCTYPE html>
<html lang="id" class="no-flash">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Librova') — Read More. Discover More.</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    {{-- Tema langsung diterapkan & tampilkan halaman --}}
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

        /* Toggle menu mobile */
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            color: var(--tx2);
            padding: 8px;
            border-radius: 8px;
            transition: background 0.2s;
            margin-left: auto;
        }
        .menu-toggle:hover { background: var(--surface2); }
        .mobile-menu {
            position: absolute;
            top: var(--nav-h);
            left: 0;
            right: 0;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            box-shadow: 0 4px 12px var(--shadow);
            padding: 16px 24px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            z-index: 99;
        }
        @media (max-width: 900px) {
            .menu-toggle { display: flex; align-items: center; justify-content: center; }
            .nav-links, .nav-search { display: none; }
        }

        /* Perbaikan ikon tema: sembunyikan yang tidak sesuai sejak awal */
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
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                        <path d="M4 19.5A2.5 2.5 0 016.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"></path>
                    </svg>
                </span>
                Librova
            </a>

            <div class="nav-search">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
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
                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 12 12">
                            <path d="M3 4.5l3 3 3-3" />
                        </svg>
                    </button>
                    {{-- Dropdown user yang dipercantik --}}
                    <div x-show="open" x-cloak @click.away="open = false" class="absolute right-0 mt-2 w-56 bg-surface dark:bg-surface-dark border border-border rounded-xl shadow-xl py-2 z-50" style="background: var(--surface); border: 1px solid var(--border);">
                        <a href="{{ route('profile') }}" class="dropdown-item">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Profil Saya
                        </a>
                        <a href="{{ route('profile.history') }}" class="dropdown-item">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Riwayat Baca
                        </a>
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="dropdown-item" style="color: var(--primary);">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Panel Admin
                            </a>
                        @endif
                        <hr style="border-color: var(--border); margin: 4px 0;">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item" style="color: #B91C1C;">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
                @else
                <a href="{{ route('login') }}" class="btn-outline">Masuk</a>
                <a href="{{ route('register') }}" class="btn-primary">Daftar</a>
                @endauth

                {{-- Tombol tema --}}
                <button class="theme-btn" @click="toggleTheme" aria-label="Toggle dark mode">
                    <svg class="icon-moon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <svg class="icon-sun" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </button>
            </div>

            <!-- Tombol hamburger mobile -->
            <button class="menu-toggle" @click="mobileMenuOpen = !mobileMenuOpen" aria-label="Toggle menu">
                <svg x-show="!mobileMenuOpen" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-cloak x-show="mobileMenuOpen" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-cloak class="mobile-menu" 
             x-transition:enter="transition ease-out duration-200" 
             x-transition:enter-start="opacity-0 -translate-y-2" 
             x-transition:enter-end="opacity-100 translate-y-0" 
             x-transition:leave="transition ease-in duration-150" 
             x-transition:leave-start="opacity-100 translate-y-0" 
             x-transition:leave-end="opacity-0 -translate-y-2"
             @click.away="mobileMenuOpen = false">
            <div style="display: flex; position: relative; max-width: 100%;">
                <svg style="position: absolute; left: 13px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: var(--tx3);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <form action="{{ route('books.index') }}" method="GET" style="width: 100%;">
                    <input type="text" name="search" placeholder="Cari buku..." style="width: 100%; padding: 9px 16px 9px 40px; border-radius: 100px; border: 1.5px solid var(--border); background: var(--surface2); color: var(--tx); font-family: inherit; font-size: 0.875rem;">
                </form>
            </div>

            <a href="{{ route('books.index') }}" class="nav-link">Buku</a>
            <a href="{{ route('categories.index') }}" class="nav-link">Kategori</a>
            @auth
                <a href="{{ route('profile.bookmarks') }}" class="nav-link">Bookmark</a>
                <a href="{{ route('profile') }}" class="nav-link">Profil</a>
                <a href="{{ route('profile.history') }}" class="nav-link">Riwayat</a>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="nav-link" style="color: var(--primary);">Panel Admin</a>
                @endif
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn-outline" style="width: 100%;">Keluar</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn-outline" style="display: block; text-align: center;">Masuk</a>
                <a href="{{ route('register') }}" class="btn-primary" style="display: block; text-align: center;">Daftar</a>
            @endauth

            <hr style="border-color: var(--border); margin: 8px 0;">
            <button @click="toggleTheme" style="display: flex; align-items: center; gap: 8px; background: none; border: none; cursor: pointer; color: var(--tx2); padding: 8px 0; font-size: 0.9rem;">
                <svg x-show="!darkMode" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
                <svg x-show="darkMode" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span x-text="darkMode ? 'Mode Terang' : 'Mode Gelap'"></span>
            </button>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer>
        <div class="footer-inner">
            <div class="footer-brand">
                <div class="logo" style="margin-bottom:12px">
                    <span class="logo-icon" style="background:#FAF7F2;color:#2C5F2E">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 19.5A2.5 2.5 0 016.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"></path>
                        </svg>
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