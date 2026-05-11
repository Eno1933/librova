@extends('layouts.app')

@section('title', 'Riwayat Baca — Librova')

@section('content')
<div class="container" style="padding: 2rem 0;">
    <h2 style="font-family:'Playfair Display',serif; font-size:2rem; color:var(--tx); margin-bottom:1.5rem;">
        🕒 Riwayat Baca
    </h2>
    @if($history->count())
        <div class="popular-list">
            @foreach($history as $item)
                <a href="{{ route('books.show', $item->book->slug) }}" class="pop-item">
                    <div class="pop-thumb" style="background: linear-gradient(145deg, {{ $item->book->cover_color ?? '#2C5F2E' }}, {{ $item->book->cover_color_dark ?? '#1d4220' }});"></div>
                    <div class="pop-info">
                        <div class="pop-title">{{ $item->book->title }}</div>
                        <div class="pop-author">{{ $item->book->author }}</div>
                        <div class="pop-views">Dibaca {{ $item->viewed_at->diffForHumans() }}</div>
                    </div>
                </a>
            @endforeach
        </div>
        {{ $history->links() }}
    @else
        <p style="color:var(--tx3); text-align:center; padding:3rem;">Belum ada riwayat membaca.</p>
    @endif
</div>
<x-mobile-bottom-nav active="profile" />
@endsection