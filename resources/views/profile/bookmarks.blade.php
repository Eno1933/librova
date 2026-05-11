@extends('layouts.app')

@section('title', 'Bookmark — Librova')

@section('content')
<div class="container" style="padding: 2rem 0;">
    <h2 style="font-family:'Playfair Display',serif; font-size:2rem; color:var(--tx); margin-bottom:1.5rem;">
        📖 Buku Tersimpan
    </h2>
    @if($books->count())
        <div class="books-grid">
            @foreach($books as $bm)
                <a href="{{ route('books.show', $bm->book->slug) }}" class="bk-card">
                    <div class="bk-cover" style="background: linear-gradient(145deg, {{ $bm->book->cover_color ?? '#2C5F2E' }}, {{ $bm->book->cover_color_dark ?? '#1d4220' }})">
                        <div class="bk-cover-title">{{ Str::limit($bm->book->title, 20) }}</div>
                    </div>
                    <div class="bk-body">
                        <span class="bk-cat">{{ $bm->book->category->name ?? '-' }}</span>
                        <div class="bk-title">{{ $bm->book->title }}</div>
                        <div class="bk-author">{{ $bm->book->author }}</div>
                    </div>
                </a>
            @endforeach
        </div>
        {{ $books->links() }}
    @else
        <p style="color:var(--tx3); text-align:center; padding:3rem;">Belum ada buku yang disimpan.</p>
    @endif
</div>
<x-mobile-bottom-nav active="bookmarks" />
@endsection