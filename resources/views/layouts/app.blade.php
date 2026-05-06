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
    <title>@yield('title', 'Librova') — Read More. Discover More.</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-inner">
            <a href="{{ route('home') }}" class="logo">
                <!-- Ikon buku SVG, bukan emoji -->
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
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-surface dark:bg-surface-dark border border-border rounded-xl shadow-xl py-1 z-50">
                        <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm">Profil</a>
                        <a href="{{ route('profile.history') }}" class="block px-4 py-2 text-sm">Riwayat</a>
                        @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm font-medium text-primary">Panel Admin</a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="w-full text-left px-4 py-2 text-sm text-red-600">Keluar</button>
                        </form>
                    </div>
                </div>
                @else
                <a href="{{ route('login') }}" class="btn-outline">Masuk</a>
                <a href="{{ route('register') }}" class="btn-primary">Daftar</a>
                @endauth

                <button class="theme-btn" @click="darkMode = !darkMode" aria-label="Toggle dark mode">
                    <svg x-show="!darkMode" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <svg x-show="darkMode" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <!-- Main content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <div class="footer-inner">
            <div class="footer-brand">
                <div class="logo" style="margin-bottom:12px">
                    <span class="logo-icon">
                        <!-- SVG logo footer -->
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                            <path d="M4 19.5A2.5 2.5 0 016.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"></path>
                        </svg> </span>
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