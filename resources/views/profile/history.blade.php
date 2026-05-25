@extends('layouts.app')

@section('title', 'Riwayat Baca — Librova')

@push('styles')
<style>
    .history-page {
        max-width: 900px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }
    .history-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 2rem;
    }
    .history-header h2 {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        font-weight: 700;
        color: var(--tx);
        margin: 0;
    }

    .history-list {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .history-card {
        display: flex;
        align-items: center;
        gap: 18px;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 16px 20px;
        text-decoration: none;
        transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
        color: inherit;
    }
    .history-card:hover {
        transform: translateX(4px);
        box-shadow: 0 8px 28px var(--shadow);
        border-color: var(--primary);
    }

    .history-cover {
        width: 60px;
        height: 86px;
        border-radius: 8px;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        background-size: cover;
        background-position: center;
        position: relative;
        overflow: hidden;
    }

    .history-info {
        flex: 1;
        min-width: 0;
    }
    .history-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--tx);
        line-height: 1.25;
        margin-bottom: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .history-author {
        font-size: 0.82rem;
        color: var(--tx3);
        margin-bottom: 8px;
    }

    .history-progress {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 4px;
    }
    .progress-bar {
        flex: 1;
        height: 6px;
        border-radius: 4px;
        background: var(--surface2);
        overflow: hidden;
    }
    .progress-fill {
        height: 100%;
        border-radius: 4px;
        background: var(--primary);
        transition: width 0.4s ease;
    }
    .progress-text {
        font-size: 0.75rem;
        color: var(--tx3);
        font-weight: 500;
        white-space: nowrap;
    }

    .history-meta {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 0.78rem;
        color: var(--tx3);
        flex-wrap: wrap;
    }
    .history-meta i {
        font-size: 0.7rem;
        margin-right: 3px;
    }

    .history-continue {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 100px;
        background: var(--primary);
        color: #fff;
        font-size: 0.8rem;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
        transition: background 0.2s;
        flex-shrink: 0;
    }
    [data-theme="dark"] .history-continue { color: var(--bg); }
    .history-continue:hover { background: var(--primary-h); }

    .empty-state {
        text-align: center;
        padding: 4rem 1rem;
        color: var(--tx3);
    }
    .empty-state i {
        font-size: 3rem;
        display: block;
        margin-bottom: 12px;
        opacity: 0.5;
    }
    .empty-state h3 {
        font-family: 'Playfair Display', serif;
        color: var(--tx2);
        margin-bottom: 8px;
    }

    @media (max-width: 600px) {
        .history-card {
            flex-wrap: wrap;
            gap: 12px;
        }
        .history-continue {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="history-page">
    <div class="history-header">
        <h2>Riwayat Baca</h2>
        @if($history->count())
            <span style="font-size:0.85rem;color:var(--tx3);">{{ $history->total() }} buku</span>
        @endif
    </div>

    @if($history->count())
        <div class="history-list">
            @foreach($history as $item)
                @php
                    $book = $item->book;
                    // Ambil halaman terakhir dari localStorage (client-side, kita set via JS)
                    // Di sini kita hanya siapkan placeholder, nanti di-update oleh JS
                    $lastPage = 0; // akan diisi oleh JavaScript
                    $totalPages = $book->total_pages ?? 0;
                    $progressPercent = $totalPages > 0 ? 0 : 0;
                @endphp
                <div class="history-card" data-book-id="{{ $book->id }}" data-book-slug="{{ $book->slug }}">
                    {{-- Cover --}}
                    <div class="history-cover"
                         style="@if($book->cover_image) background-image: url('{{ Storage::url($book->cover_image) }}'); @else background: linear-gradient(145deg, {{ $book->cover_color ?? '#2C5F2E' }}, {{ $book->cover_color_dark ?? '#1d4220' }}); @endif">
                    </div>

                    {{-- Info --}}
                    <div class="history-info">
                        <div class="history-title">{{ $book->title }}</div>
                        <div class="history-author">{{ $book->author }}</div>

                        {{-- Progress Bar (akan diupdate JS) --}}
                        <div class="history-progress">
                            <div class="progress-bar">
                                <div class="progress-fill" id="progress-{{ $book->id }}" style="width: 0%;"></div>
                            </div>
                            <span class="progress-text" id="progress-text-{{ $book->id }}">0%</span>
                        </div>

                        {{-- Meta --}}
                        <div class="history-meta">
                            <span><i class="bi bi-clock"></i> {{ $item->viewed_at->diffForHumans() }}</span>
                            @if($totalPages > 0)
                                <span><i class="bi bi-book"></i> {{ number_format($totalPages) }} hlm</span>
                            @endif
                            <span><i class="bi bi-eye"></i> {{ number_format($book->view_count) }} dibaca</span>
                        </div>
                    </div>

                    {{-- Tombol Lanjutkan --}}
                    <a href="{{ route('books.read', $book->slug) }}" class="history-continue" id="continue-{{ $book->id }}">
                        <i class="bi bi-play-fill"></i> Lanjutkan
                    </a>
                </div>
            @endforeach
        </div>

        <div style="margin-top: 2rem;">
            {{ $history->links() }}
        </div>
    @else
        <div class="empty-state">
            <i class="bi bi-book"></i>
            <h3>Belum ada riwayat membaca</h3>
            <p>Mulailah membaca buku favoritmu!</p>
            <a href="{{ route('books.index') }}" style="display:inline-flex;align-items:center;gap:8px;margin-top:12px;padding:10px 20px;border-radius:100px;background:var(--primary);color:#fff;text-decoration:none;font-weight:600;font-size:0.9rem;">
                <i class="bi bi-search"></i> Jelajahi Buku
            </a>
        </div>
    @endif
</div>

<x-mobile-bottom-nav active="profile" />
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Untuk setiap kartu riwayat, ambil progress dari localStorage
    document.querySelectorAll('.history-card').forEach(card => {
        const bookId   = card.dataset.bookId;
        const bookSlug = card.dataset.bookSlug;
        const lastPage = localStorage.getItem('librova_last_page_' + bookId);

        if (lastPage) {
            const page = parseInt(lastPage);
            if (page > 0) {
                // Ambil total halaman dari elemen data atau dari server (kita set di atas)
                // Karena total halaman sudah di-render, kita bisa ambil dari progress-fill ID
                const totalPagesEl = card.querySelector('[data-total-pages]');
                // Kita tidak punya data-total-pages, kita bisa ambil dari teks "XX hlm" atau kita tambahkan data attribute
                // Alternatif: kita set data-total-pages di card
                // Mari kita tambahkan data-total-pages di card
                const totalPages = parseInt(card.dataset.totalPages || 0);

                const fill   = card.querySelector('.progress-fill');
                const text   = card.querySelector('.progress-text');
                const btn    = card.querySelector('.history-continue');

                if (totalPages > 0) {
                    const percent = Math.min(Math.round((page / totalPages) * 100), 100);
                    if (fill) {
                        fill.style.width = percent + '%';
                    }
                    if (text) {
                        text.textContent = percent + '% (hlm ' + page + ')';
                    }
                } else {
                    // Jika total halaman tidak diketahui, tetap tampilkan halaman terakhir
                    if (text) {
                        text.textContent = 'Hlm ' + page;
                    }
                    if (fill) {
                        fill.style.width = '20%'; // indikasi sudah membaca
                    }
                }

                // Update link "Lanjutkan" agar langsung ke halaman terakhir?
                // Bisa tambahkan query parameter ?page=... jika reader mendukung
                if (btn && page > 1) {
                    btn.href = btn.href + '?page=' + page;
                }
            }
        }
    });
});
</script>
@endpush