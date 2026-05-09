@extends('layouts.app')

@section('title', $category->name . ' — Librova')

@section('content')
<div class="container" style="padding: 2rem 0;">
    {{-- Breadcrumb / Navigasi Kembali --}}
    <a href="{{ route('categories.index') }}" class="back-home" style="position: static; margin-bottom: 1.5rem; display: inline-flex; align-items: center; gap: 6px; color: var(--tx2); font-size: 0.9rem; font-weight: 500; text-decoration: none;">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Semua Kategori
    </a>

    <div class="section-head">
        <h2 class="section-title">
            {{-- Ikon buku --}}
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 0.5rem;">
                <path d="M4 19.5A2.5 2.5 0 016.5 17H20"/>
                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/>
            </svg>
            {{ $category->name }}
        </h2>
        <span style="font-size: 0.9rem; color: var(--tx3);">{{ $books->total() }} buku</span>
    </div>

    @if($category->description)
        <p style="color: var(--tx2); margin-top: -0.5rem; margin-bottom: 2rem;">
            {{ $category->description }}
        </p>
    @endif

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

        <div style="margin-top: 2rem;">
            {{ $books->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 4rem 0; color: var(--tx3);">
            <svg width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin: 0 auto 1rem; opacity: 0.5;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            <h3 style="font-family: 'Playfair Display', serif; margin-bottom: 0.5rem;">Belum ada buku</h3>
            <p>Kategori ini masih kosong. Kunjungi lagi nanti.</p>
        </div>
    @endif
</div>
@endsection