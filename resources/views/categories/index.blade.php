@extends('layouts.app')

@section('title', 'Kategori — Librova')

@section('content')
<div class="container" style="padding: 2rem 0;">
    <div class="section-head">
        <h2 class="section-title">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 0.5rem;">
                <path d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
            </svg>
            Jelajahi Kategori
        </h2>
    </div>

    <div class="cat-grid">
        @forelse ($categories as $category)
            <a href="{{ route('categories.show', $category->slug) }}" class="cat-card">
                <div class="cat-icon">
                    {{-- Ikon buku sebagai default, bisa disesuaikan --}}
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div class="cat-name">{{ $category->name }}</div>
                <div class="cat-count">
                    {{ $category->children->count() }} subkategori
                </div>
            </a>
        @empty
            <p style="color: var(--tx3);">Belum ada kategori.</p>
        @endforelse
    </div>
</div>
@endsection