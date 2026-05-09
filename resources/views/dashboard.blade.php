@extends('layouts.app')

@section('title', 'Dashboard — Librova')

@section('content')
<div class="container" style="padding: 2rem 0;">

    {{-- Search + Filter Kategori --}}
    <div style="margin-bottom: 2rem;">
        <form action="{{ route('dashboard') }}" method="GET" class="hero-search" style="max-width: 100%; margin-bottom: 1.2rem;">
            <input type="text" name="search" placeholder="Cari buku di koleksi..." value="{{ request('search') }}">
            <button type="submit">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                Cari
            </button>
        </form>

        {{-- Kategori Scrollable Horizontal (scrollbar dihapus) --}}
        <div class="tr-pill-container" style="display: flex; gap: 8px; overflow-x: auto; padding-bottom: 6px; -webkit-overflow-scrolling: touch;">
            <a href="{{ route('dashboard') }}" class="tr-pill {{ !request('category') ? 'active' : '' }}" style="flex-shrink: 0; white-space: nowrap;">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <rect x="2" y="3" width="20" height="14" rx="2" />
                    <path d="M8 21h8M12 17v4" />
                </svg>
                Semua
            </a>
            @foreach($categories as $cat)
            <a href="{{ route('dashboard', ['category' => $cat->slug]) }}" class="tr-pill {{ request('category') == $cat->slug ? 'active' : '' }}" style="flex-shrink: 0; white-space: nowrap;">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M4 4h16v4H4zM4 10h16v4H4zM4 16h16v4H4z" />
                </svg>
                {{ $cat->name }}
            </a>
            @endforeach
        </div>
        <style>
            .tr-pill-container::-webkit-scrollbar { display: none; }
            .tr-pill-container { -ms-overflow-style: none; scrollbar-width: none; }
            .tr-pill {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 8px 16px;
                border-radius: 100px;
                background: var(--surface);
                border: 1px solid var(--border);
                font-size: 0.8rem;
                font-weight: 500;
                color: var(--tx2);
                cursor: pointer;
                transition: border-color 0.2s, color 0.2s, background 0.2s;
                text-decoration: none;
            }
            .tr-pill:hover,
            .tr-pill.active {
                border-color: var(--primary);
                color: var(--primary);
                background: rgba(44,95,46,0.06);
            }
            [data-theme="dark"] .tr-pill:hover,
            [data-theme="dark"] .tr-pill.active {
                background: rgba(74,222,128,0.07);
            }
        </style>
    </div>

    {{-- Daftar Buku --}}
    @if($books->count())
    <div class="books-grid">
        @foreach($books as $book)
        <a href="{{ route('books.show', $book->slug) }}" class="bk-card fade-up">
            <div class="bk-cover" style="background: linear-gradient(145deg, {{ $book->cover_color ?? '#2C5F2E' }}, {{ $book->cover_color_dark ?? '#1d4220' }})">
                @if($book->is_featured)
                <span class="bk-rank">★</span>
                @endif
                <div class="bk-cover-title">{{ Str::limit($book->title, 20) }}</div>
            </div>
            <div class="bk-body">
                <span class="bk-cat">{{ $book->category->name ?? '-' }}</span>
                <div class="bk-title">{{ $book->title }}</div>
                <div class="bk-author">{{ $book->author }}</div>
                <div class="bk-rating">
                    <span class="stars">@for($i=1; $i<=5; $i++){{ $i <= round($book->averageRating()) ? '★' : '☆' }}@endfor</span>
                    <span class="rating-num">{{ $book->averageRating() }}</span>
                </div>
            </div>
        </a>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div style="margin-top: 2rem;">
        {{ $books->links() }}
    </div>
    @else
    <div style="text-align: center; padding: 4rem 0; color: var(--tx3);">
        <svg width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin: 0 auto 1rem; opacity: 0.5;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
        </svg>
        <h3 style="font-family: 'Playfair Display', serif; margin-bottom: 0.5rem;">Tidak ada buku ditemukan</h3>
        <p>Coba kata kunci atau kategori lain.</p>
    </div>
    @endif
</div>

{{-- ========== MOBILE BOTTOM NAV ========== --}}
<nav class="bottom-nav"
     style="position: fixed; bottom: 0; left: 0; right: 0; z-index: 50; padding-bottom: env(safe-area-inset-bottom); border-top: 1px solid var(--border); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);"
     x-data="{ 
         darkMode: localStorage.getItem('librova-theme') === 'dark',
         active: 'home', 
         searchOpen: false,
         init() {
             // Pantau perubahan tema secara real‑time
             const observer = new MutationObserver(() => {
                 this.darkMode = localStorage.getItem('librova-theme') === 'dark';
             });
             observer.observe(document.documentElement, {
                 attributes: true,
                 attributeFilter: ['data-theme']
             });

             // Tentukan tab aktif berdasarkan URL
             const path = window.location.pathname;
             if (path.includes('dashboard')) this.active = 'home';
             else if (path.includes('categories')) this.active = 'categories';
             else if (path.includes('bookmarks')) this.active = 'bookmarks';
             else if (path.includes('profile')) this.active = 'profile';
             else this.active = 'home';
         }
     }"
     :style="{ background: darkMode ? 'rgba(30,30,25,0.94)' : 'rgba(250,247,242,0.92)' }">
    <div style="display: flex; justify-content: space-around; align-items: center; height: 64px; padding: 0 8px;">
        <!-- Home (Dashboard) -->
        <a href="{{ route('dashboard') }}" @click="active = 'home'"
           style="display: flex; flex-direction: column; align-items: center; justify-content: center; width: 64px; height: 100%; text-decoration: none;"
           :style="active === 'home' ? 'color: var(--primary)' : 'color: var(--tx3)'">
            <svg style="width: 24px; height: 24px; margin-bottom: 2px;" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
            </svg>
            <span style="font-size: 0.65rem; font-weight: 500;">Home</span>
        </a>

        <!-- Kategori -->
        <a href="{{ route('categories.index') }}" @click="active = 'categories'"
           style="display: flex; flex-direction: column; align-items: center; justify-content: center; width: 64px; height: 100%; text-decoration: none;"
           :style="active === 'categories' ? 'color: var(--primary)' : 'color: var(--tx3)'">
            <svg style="width: 24px; height: 24px; margin-bottom: 2px;" fill="currentColor" viewBox="0 0 20 20">
                <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
            </svg>
            <span style="font-size: 0.65rem; font-weight: 500;">Kategori</span>
        </a>

        <!-- Bookmark -->
        <a href="{{ route('profile.bookmarks') }}" @click="active = 'bookmarks'"
           style="display: flex; flex-direction: column; align-items: center; justify-content: center; width: 64px; height: 100%; text-decoration: none;"
           :style="active === 'bookmarks' ? 'color: var(--primary)' : 'color: var(--tx3)'">
            <svg style="width: 24px; height: 24px; margin-bottom: 2px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
            </svg>
            <span style="font-size: 0.65rem; font-weight: 500;">Bookmark</span>
        </a>

        <!-- Profil -->
        <a href="{{ route('profile') }}" @click="active = 'profile'"
           style="display: flex; flex-direction: column; align-items: center; justify-content: center; width: 64px; height: 100%; text-decoration: none;"
           :style="active === 'profile' ? 'color: var(--primary)' : 'color: var(--tx3)'">
            <svg style="width: 24px; height: 24px; margin-bottom: 2px;" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
            </svg>
            <span style="font-size: 0.65rem; font-weight: 500;">Profil</span>
        </a>
    </div>

    {{-- Search Modal (mobile) --}}
    <div x-show="searchOpen" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 50; backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"
         @click.self="searchOpen = false">
        <div style="position: absolute; top: 5rem; left: 1rem; right: 1rem; background: var(--surface); border-radius: 1rem; box-shadow: 0 10px 25px rgba(0,0,0,0.2); padding: 1.25rem;" @click.stop>
            <form action="{{ route('dashboard') }}" method="GET" style="display: flex; gap: 0.5rem;">
                <div style="flex: 1; position: relative;">
                    <svg style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); width: 1.25rem; height: 1.25rem; color: var(--tx3);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" placeholder="Cari judul atau penulis..."
                           style="width: 100%; padding: 0.75rem 1rem 0.75rem 2.5rem; border-radius: 0.75rem; border: 1px solid var(--border); background: var(--bg); color: var(--tx); outline: none;">
                </div>
                <button type="submit" style="background: var(--primary); color: white; border: none; padding: 0.75rem 1rem; border-radius: 0.75rem; cursor: pointer;">
                    <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </form>
            <button @click="searchOpen = false" style="margin-top: 0.75rem; width: 100%; padding: 0.5rem; text-align: center; font-size: 0.875rem; color: var(--tx3); background: none; border: none; cursor: pointer;">Tutup</button>
        </div>
    </div>
</nav>

{{-- Spacer untuk mobile bottom nav --}}
<div class="pb-20 sm:pb-0"></div>
@endsection

@push('scripts')
<script>
    // Additional script bisa ditambahkan di sini
</script>
@endpush