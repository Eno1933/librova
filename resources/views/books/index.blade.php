@extends('layouts.app')

@section('title', 'Buku — Librova')

@section('content')
<div class="container" style="padding: 2rem 0;">

    {{-- Search Bar --}}
    <div style="margin-bottom: 2rem;">
        <form action="{{ route('books.index') }}" method="GET" class="hero-search" style="max-width: 100%;">
            <input type="text" name="search" placeholder="Cari judul, penulis, atau ISBN..." value="{{ request('search') }}">
            <button type="submit">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Cari
            </button>
        </form>
    </div>

    {{-- Header Koleksi yang Dipercantik --}}
    <div style="margin-bottom: 2rem;">
        <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
            {{-- Ikon dalam kotak hijau --}}
            <div style="
                background: var(--primary);
                border-radius: 16px;
                width: 56px;
                height: 56px;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 8px 20px var(--shadow);
            ">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                    <path d="M4 19.5A2.5 2.5 0 016.5 17H20"/>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/>
                </svg>
            </div>
            <div>
                <h2 class="section-title" style="margin-bottom: 0.2rem; font-size: 2rem;">Koleksi Buku</h2>
                <p style="color: var(--tx3); font-size: 0.9rem; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                    <span style="
                        background: var(--gold-light);
                        color: var(--gold-dim);
                        padding: 0.2rem 0.8rem;
                        border-radius: 100px;
                        font-weight: 600;
                        font-size: 0.8rem;
                    ">{{ $books->total() }} buku</span>
                    ditemukan di perpustakaan
                </p>
            </div>
        </div>
        {{-- Garis dekoratif --}}
        <div style="margin-top: 1.2rem; height: 2px; background: linear-gradient(to right, var(--primary), transparent); width: 120px; border-radius: 2px;"></div>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            <h3 style="font-family: 'Playfair Display', serif; margin-bottom: 0.5rem;">Tidak ada buku ditemukan</h3>
            <p>Coba kata kunci lain.</p>
        </div>
    @endif
</div>

{{-- Bottom navbar hanya untuk user login --}}
@auth
    <x-mobile-bottom-nav active="books" />
@endauth
@endsection