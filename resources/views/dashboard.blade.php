@extends('layouts.app')

@section('title', 'Dashboard — Librova')

@push('styles')
<style>
    /* ═══════════════════════════════════════════════
       DASHBOARD PAGE STYLES (ICONS: Bootstrap Icons)
    ═══════════════════════════════════════════════ */

    .dash-page { padding: 0 0 32px; min-height: calc(100vh - var(--nav-h, 68px)); }

    /* ── Welcome Banner ── */
    .dash-banner {
        position: relative; overflow: hidden; padding: 32px 0 36px;
        margin-bottom: 32px; border-bottom: 1px solid var(--border);
    }
    .dash-banner::before {
        content: ''; position: absolute; inset: 0;
        background: radial-gradient(ellipse 55% 80% at 90% 50%, rgba(201,168,76,0.07) 0%, transparent 65%),
                    radial-gradient(ellipse 35% 60% at 5% 60%, rgba(44,95,46,0.06) 0%, transparent 60%);
        pointer-events: none;
    }
    [data-theme="dark"] .dash-banner::before {
        background: radial-gradient(ellipse 55% 80% at 90% 50%, rgba(251,191,36,0.05) 0%, transparent 65%),
                    radial-gradient(ellipse 35% 60% at 5% 60%, rgba(74,222,128,0.04) 0%, transparent 60%);
    }
    .dash-banner-inner {
        max-width: 1200px; margin: 0 auto; padding: 0 24px;
        display: flex; align-items: center; justify-content: space-between; gap: 20px;
    }
    .dash-greeting {
        font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.07em;
        color: var(--tx3); margin-bottom: 6px; display: flex; align-items: center; gap: 8px;
    }
    .dash-title {
        font-family: 'Playfair Display', serif; font-size: clamp(1.5rem, 3.5vw, 2.1rem);
        font-weight: 700; line-height: 1.2; letter-spacing: -0.02em; color: var(--tx);
    }
    .dash-title em { font-style: italic; color: var(--primary); }
    .dash-subtitle { font-size: 0.87rem; color: var(--tx2); margin-top: 6px; max-width: 420px; line-height: 1.6; }
    .dash-stats { display: flex; gap: 6px; flex-shrink: 0; }
    .dash-stat {
        display: flex; flex-direction: column; align-items: center; padding: 12px 18px;
        background: var(--surface); border: 1px solid var(--border); border-radius: 12px;
        min-width: 80px; transition: border-color .2s, box-shadow .2s;
    }
    .dash-stat:hover { border-color: var(--primary); box-shadow: 0 2px 16px var(--shadow); }
    .dash-stat-num { font-family: 'Playfair Display', serif; font-size: 1.35rem; font-weight: 700; color: var(--tx); line-height: 1; }
    .dash-stat-lbl { font-size: 0.65rem; font-weight: 500; color: var(--tx3); margin-top: 3px; white-space: nowrap; }

    /* ── Search Bar ── */
    .dash-search-wrap { max-width: 1200px; margin: 0 auto; padding: 0 24px 20px; }
    .dash-search {
        display: flex; border-radius: 12px; border: 1.5px solid var(--border);
        background: var(--surface); overflow: hidden; transition: border-color .2s, box-shadow .2s;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    }
    .dash-search:focus-within {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(44,95,46,0.09), 0 2px 16px rgba(0,0,0,0.06);
    }
    [data-theme="dark"] .dash-search:focus-within { box-shadow: 0 0 0 3px rgba(74,222,128,0.09); }
    .dash-search-icon { display: flex; align-items: center; padding: 0 14px 0 16px; color: var(--tx3); flex-shrink: 0; }
    .dash-search input {
        flex: 1; padding: 13px 8px; border: none; background: transparent;
        font-family: inherit; font-size: 0.92rem; color: var(--tx);
    }
    .dash-search input::placeholder { color: var(--tx3); }
    .dash-search input:focus { outline: none; }
    .dash-search-btn {
        margin: 6px; padding: 0 20px; background: var(--primary); color: #fff;
        border-radius: 8px; font-family: inherit; font-size: 0.85rem; font-weight: 600;
        display: flex; align-items: center; gap: 7px; transition: background .2s, transform .15s;
        white-space: nowrap; border: none; cursor: pointer;
    }
    [data-theme="dark"] .dash-search-btn { color: var(--bg); }
    .dash-search-btn:hover { background: var(--primary-h); }
    .dash-search-btn:active { transform: scale(0.96); }

    /* ── Category Filter Pills ── */
    .dash-cats-wrap { max-width: 1200px; margin: 0 auto; padding: 0 24px 28px; }
    .dash-cats-scroll {
        display: flex; gap: 8px; overflow-x: auto; padding-bottom: 4px;
        -webkit-overflow-scrolling: touch; scrollbar-width: none;
    }
    .dash-cats-scroll::-webkit-scrollbar { display: none; }
    .dash-cat-pill {
        display: inline-flex; align-items: center; gap: 6px; padding: 7px 16px;
        border-radius: 100px; background: var(--surface); border: 1.5px solid var(--border);
        font-family: inherit; font-size: 0.8rem; font-weight: 500; color: var(--tx2);
        white-space: nowrap; flex-shrink: 0; cursor: pointer; text-decoration: none;
        transition: border-color .2s, color .2s, background .2s, transform .15s;
    }
    .dash-cat-pill:hover { border-color: var(--border2); color: var(--tx); transform: translateY(-1px); }
    .dash-cat-pill.active {
        border-color: var(--primary); color: var(--primary);
        background: rgba(44,95,46,0.07); font-weight: 600;
    }
    [data-theme="dark"] .dash-cat-pill.active { background: rgba(74,222,128,0.08); }
    .cat-icon-pill { font-size: 1rem; }

    /* ── Continue Reading Section ── */
    .continue-section { max-width: 1200px; margin: 0 auto; padding: 0 24px 28px; }
    .continue-title {
        font-family: 'Playfair Display', serif; font-size: 1.25rem; font-weight: 700;
        color: var(--tx); margin-bottom: 16px; display: flex; align-items: center; gap: 8px;
    }
    .continue-grid {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 14px;
    }
    .continue-card {
        display: flex; align-items: center; gap: 14px;
        background: var(--surface); border: 1px solid var(--border); border-radius: 14px;
        padding: 14px 16px; text-decoration: none; color: inherit;
        transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
    }
    .continue-card:hover {
        transform: translateX(4px); box-shadow: 0 6px 24px var(--shadow); border-color: var(--primary);
    }
    .continue-cover {
        width: 48px; height: 68px; border-radius: 8px; flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.12); background-size: cover; background-position: center;
    }
    .continue-info { flex: 1; min-width: 0; }
    .continue-book-title {
        font-family: 'Playfair Display', serif; font-weight: 600; color: var(--tx);
        font-size: 0.9rem; line-height: 1.25; margin-bottom: 2px;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .continue-book-author { font-size: 0.75rem; color: var(--tx3); margin-bottom: 8px; }
    .continue-progress { display: flex; align-items: center; gap: 8px; }
    .continue-progress-bar {
        flex: 1; height: 5px; border-radius: 3px; background: var(--surface2); overflow: hidden;
    }
    .continue-progress-fill {
        height: 100%; border-radius: 3px; background: var(--primary); transition: width 0.4s ease;
    }
    .continue-progress-text { font-size: 0.72rem; color: var(--tx3); font-weight: 500; white-space: nowrap; }
    .continue-btn {
        display: inline-flex; align-items: center; gap: 4px; padding: 5px 12px;
        border-radius: 100px; background: var(--primary); color: #fff;
        font-size: 0.72rem; font-weight: 600; text-decoration: none; white-space: nowrap;
        transition: background 0.2s; flex-shrink: 0;
    }
    [data-theme="dark"] .continue-btn { color: var(--bg); }
    .continue-btn:hover { background: var(--primary-h); }
    .continue-empty {
        text-align: center; padding: 24px; color: var(--tx3); font-size: 0.9rem;
        background: var(--surface); border: 1px solid var(--border); border-radius: 14px;
    }

    /* ── Popular Books Section ── */
    .popular-section { max-width: 1200px; margin: 0 auto; padding: 0 24px 28px; }
    .popular-section-title {
        font-family: 'Playfair Display', serif; font-size: 1.25rem; font-weight: 700;
        color: var(--tx); margin-bottom: 16px; display: flex; align-items: center; gap: 8px;
    }
    .popular-list-dash { display: flex; flex-direction: column; gap: 10px; }
    .pop-item-dash {
        display: flex; align-items: center; gap: 14px;
        background: var(--surface); border: 1px solid var(--border); border-radius: 14px;
        padding: 12px 16px; text-decoration: none; color: inherit;
        transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
    }
    .pop-item-dash:hover {
        transform: translateX(4px); box-shadow: 0 4px 20px var(--shadow); border-color: var(--border2);
    }
    .pop-num-dash {
        font-family: 'Playfair Display', serif; font-size: 1.5rem; font-weight: 700;
        min-width: 32px; text-align: center; color: var(--border2); line-height: 1;
    }
    .pop-item-dash:nth-child(1) .pop-num-dash { color: var(--gold); }
    .pop-item-dash:nth-child(2) .pop-num-dash { color: var(--tx3); }
    .pop-item-dash:nth-child(3) .pop-num-dash { color: #8B7355; }
    .pop-thumb-dash {
        width: 44px; height: 62px; border-radius: 6px; flex-shrink: 0; overflow: hidden;
        background-size: cover; background-position: center;
    }
    .pop-info-dash { flex: 1; min-width: 0; }
    .pop-title-dash {
        font-family: 'Playfair Display', serif; font-size: 0.9rem; font-weight: 600;
        color: var(--tx); line-height: 1.25; margin-bottom: 2px;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .pop-author-dash { font-size: 0.74rem; color: var(--tx3); margin-bottom: 4px; }
    .pop-meta-dash { display: flex; align-items: center; gap: 8px; }
    .pop-views-dash { font-size: 0.7rem; color: var(--tx3); display: flex; align-items: center; gap: 3px; }
    .pop-stars-dash { color: var(--gold); font-size: 0.75rem; display: flex; gap: 1px; }

    /* ── All Books Grid ── */
    .books-outer { max-width: 1200px; margin: 0 auto; padding: 0 24px; }
    .books-grid-dash {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(155px, 1fr)); gap: 18px;
    }
    .bk-card-d {
        background: var(--surface); border: 1px solid var(--border); border-radius: 14px;
        overflow: hidden; text-decoration: none; display: block;
        transition: transform .28s cubic-bezier(.34,1.56,.64,1), box-shadow .28s, border-color .2s;
        animation: fadeCardUp .5s both;
    }
    .bk-card-d:hover { transform: translateY(-7px); box-shadow: 0 14px 40px var(--shadow); border-color: var(--border2); }
    .bk-card-d:nth-child(1) { animation-delay: .04s } .bk-card-d:nth-child(2) { animation-delay: .08s }
    .bk-card-d:nth-child(3) { animation-delay: .12s } .bk-card-d:nth-child(4) { animation-delay: .16s }
    .bk-card-d:nth-child(5) { animation-delay: .20s } .bk-card-d:nth-child(6) { animation-delay: .24s }
    .bk-card-d:nth-child(7) { animation-delay: .28s } .bk-card-d:nth-child(8) { animation-delay: .32s }
    .bk-card-d:nth-child(9) { animation-delay: .36s } .bk-card-d:nth-child(10) { animation-delay: .40s }
    .bk-card-d:nth-child(11) { animation-delay: .44s } .bk-card-d:nth-child(12) { animation-delay: .48s }
    @keyframes fadeCardUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .bk-cover-d {
        aspect-ratio: 2/3; position: relative; overflow: hidden;
        display: flex; flex-direction: column; justify-content: flex-end; padding: 14px;
    }
    .bk-cover-d::before {
        content: ''; position: absolute; top: 0; left: -60%; width: 40%; height: 100%;
        background: linear-gradient(105deg, transparent 0%, rgba(255,255,255,0.07) 50%, transparent 100%);
        transition: left .6s ease; pointer-events: none;
    }
    .bk-card-d:hover .bk-cover-d::before { left: 130%; }
    .bk-cover-d::after {
        content: ''; position: absolute; inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.62) 0%, rgba(0,0,0,0.05) 55%, transparent 100%);
    }
    .bk-star-badge {
        position: absolute; top: 10px; left: 10px; z-index: 2; width: 26px; height: 26px;
        border-radius: 50%; background: var(--gold); color: #000; font-size: 15px; font-weight: 700;
        display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }
    .bk-cover-title-d {
        position: relative; z-index: 1; font-family: 'Playfair Display', serif;
        color: rgba(255,255,255,0.93); font-size: 0.7rem; font-weight: 600; line-height: 1.3;
    }
    .bk-body-d { padding: 12px 13px 13px; }
    .bk-cat-d {
        font-size: 0.63rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em;
        color: var(--primary); background: rgba(44,95,46,0.08); padding: 2px 8px;
        border-radius: 4px; display: inline-block; margin-bottom: 7px;
    }
    [data-theme="dark"] .bk-cat-d { background: rgba(74,222,128,0.1); }
    .bk-title-d {
        font-size: 0.85rem; font-weight: 600; color: var(--tx); line-height: 1.3; margin-bottom: 2px;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .bk-author-d {
        font-size: 0.74rem; color: var(--tx3); margin-bottom: 9px;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .bk-footer-d { display: flex; align-items: center; justify-content: space-between; gap: 4px; }
    .bk-stars { color: var(--gold); font-size: 0.9rem; letter-spacing: -1px; }
    .bk-rating-txt { font-size: 0.7rem; color: var(--tx3); font-weight: 500; }
    .bk-views { font-size: 0.65rem; color: var(--tx3); display: flex; align-items: center; gap: 3px; }
    .bk-views i { font-size: 0.8rem; }

    /* ── Empty State ── */
    .dash-empty {
        text-align: center; padding: 5rem 1rem; max-width: 360px; margin: 0 auto;
        animation: fadeCardUp .5s both;
    }
    .dash-empty-icon {
        width: 72px; height: 72px; border-radius: 20px; background: var(--surface);
        border: 1px solid var(--border); display: flex; align-items: center; justify-content: center;
        margin: 0 auto 20px; font-size: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }
    .dash-empty-title { font-family: 'Playfair Display', serif; font-size: 1.25rem; font-weight: 700; color: var(--tx); margin-bottom: 8px; }
    .dash-empty-desc { font-size: 0.87rem; color: var(--tx3); line-height: 1.6; margin-bottom: 20px; }
    .dash-empty-btn {
        display: inline-flex; align-items: center; gap: 7px; padding: 10px 20px;
        border-radius: 100px; background: var(--primary); color: #fff;
        font-family: inherit; font-size: 0.85rem; font-weight: 600; text-decoration: none;
        border: none; cursor: pointer; transition: background .2s, transform .15s;
    }
    [data-theme="dark"] .dash-empty-btn { color: var(--bg); }
    .dash-empty-btn:hover { background: var(--primary-h); transform: translateY(-2px); }

    /* ── Section Divider ── */
    .section-divider { max-width: 1200px; margin: 0 auto; padding: 0 24px 24px; }
    .section-divider hr { border: none; border-top: 1px solid var(--border); }

    /* ── Pagination ── */
    .dash-pagination { max-width: 1200px; margin: 32px auto 0; padding: 0 24px; }
    .dash-pagination nav { display: flex; justify-content: center; }
    .dash-pagination .pagination { display: flex; gap: 6px; list-style: none; align-items: center; }
    .dash-pagination .page-item .page-link {
        display: flex; align-items: center; justify-content: center; width: 36px; height: 36px;
        border-radius: 8px; font-size: 0.85rem; font-weight: 500; color: var(--tx2);
        background: var(--surface); border: 1px solid var(--border); text-decoration: none; transition: all .18s;
    }
    .dash-pagination .page-item .page-link:hover { border-color: var(--primary); color: var(--primary); }
    .dash-pagination .page-item.active .page-link { background: var(--primary); color: #fff; border-color: var(--primary); font-weight: 600; }
    [data-theme="dark"] .dash-pagination .page-item.active .page-link { color: var(--bg); }

    @media(max-width: 768px) {
        .dash-banner-inner { flex-direction: column; align-items: flex-start; gap: 16px; }
        .dash-stats { align-self: stretch; }
        .dash-stat { flex: 1; }
        .books-grid-dash { grid-template-columns: repeat(auto-fill, minmax(135px, 1fr)); gap: 14px; }
        .continue-grid { grid-template-columns: 1fr; }
    }
    @media(max-width: 480px) {
        .dash-banner { padding: 22px 0 26px; }
        .books-grid-dash { grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 12px; }
        .dash-stats { display: none; }
    }
</style>
@endpush

@section('content')
<div class="dash-page">

    {{-- ── Banner ── --}}
    <div class="dash-banner">
        <div class="dash-banner-inner">
            <div>
                <div class="dash-greeting">
                    @auth Selamat {{ now()->hour < 12 ? 'pagi' : (now()->hour < 17 ? 'siang' : 'malam') }}, {{ explode(' ', auth()->user()->name)[0] }} @else Selamat datang di @endauth
                </div>
                <h1 class="dash-title">
                    @if(request('search'))
                        Hasil untuk <em>"{{ request('search') }}"</em>
                    @elseif(request('category'))
                        Kategori <em>{{ request('category') }}</em>
                    @else
                        Temukan <em>buku impianmu</em><br>hari ini
                    @endif
                </h1>
                <p class="dash-subtitle">
                    @if(request('search') || request('category'))
                        Menampilkan {{ $books->total() }} buku yang ditemukan.
                    @else
                        Jelajahi {{ number_format($books->total()) }}+ koleksi e-book pilihan dari ratusan penulis terbaik.
                    @endif
                </p>
            </div>
            <div class="dash-stats">
                <div class="dash-stat">
                    <span class="dash-stat-num">{{ $books->total() }}+</span>
                    <span class="dash-stat-lbl">Buku</span>
                </div>
                <div class="dash-stat">
                    <span class="dash-stat-num">{{ $categories->count() }}</span>
                    <span class="dash-stat-lbl">Kategori</span>
                </div>
                @auth
                <div class="dash-stat">
                    <span class="dash-stat-num">{{ auth()->user()->bookmarks()->count() }}</span>
                    <span class="dash-stat-lbl">Bookmark</span>
                </div>
                @endauth
            </div>
        </div>
    </div>

    {{-- ── Search Bar ── --}}
    <div class="dash-search-wrap">
        <form action="{{ route('dashboard') }}" method="GET">
            @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
            <div class="dash-search">
                <span class="dash-search-icon"><i class="bi bi-search" style="font-size: 1.1rem;"></i></span>
                <input type="text" name="search" placeholder="Cari judul, penulis, atau ISBN…" value="{{ request('search') }}" autocomplete="off">
                @if(request('search'))
                <a href="{{ route('dashboard', array_filter(['category' => request('category')])) }}"
                   style="display:flex;align-items:center;padding:0 12px;color:var(--tx3);text-decoration:none;font-size:1.2rem;transition:color .15s" title="Hapus pencarian">
                    <i class="bi bi-x"></i>
                </a>
                @endif
                <button type="submit" class="dash-search-btn"><i class="bi bi-search"></i> Cari</button>
            </div>
        </form>
    </div>

    {{-- ── Category Pills ── --}}
    <div class="dash-cats-wrap">
        <div class="dash-cats-scroll">
            <a href="{{ route('dashboard', array_filter(['search' => request('search')])) }}"
               class="dash-cat-pill {{ !request('category') ? 'active' : '' }}">
                <i class="bi bi-collection cat-icon-pill"></i> Semua
            </a>
            @foreach($categories as $cat)
            <a href="{{ route('dashboard', array_filter(['category' => $cat->slug, 'search' => request('search')])) }}"
               class="dash-cat-pill {{ request('category') == $cat->slug ? 'active' : '' }}">
                <i class="bi bi-folder cat-icon-pill"></i> {{ $cat->name }}
            </a>
            @endforeach
        </div>
    </div>

    {{-- ═══════════ LANJUTKAN MEMBACA ═══════════ --}}
    @auth
    @if($continueReading->count())
    <div class="continue-section">
        <h2 class="continue-title">
            <i class="bi bi-book-open" style="color:var(--primary)"></i> Lanjutkan Membaca
        </h2>
        <div class="continue-grid">
            @foreach($continueReading as $item)
            @php $book = $item->book; @endphp
            <div class="continue-card" data-book-id="{{ $book->id }}" data-book-slug="{{ $book->slug }}" data-total-pages="{{ $book->total_pages ?? 0 }}">
                <div class="continue-cover"
                     style="@if($book->cover_image) background-image: url('{{ Storage::url($book->cover_image) }}'); @else background: linear-gradient(145deg, {{ $book->cover_color ?? '#2C5F2E' }}, {{ $book->cover_color_dark ?? '#1d4220' }}); @endif"></div>
                <div class="continue-info">
                    <div class="continue-book-title">{{ $book->title }}</div>
                    <div class="continue-book-author">{{ $book->author }}</div>
                    <div class="continue-progress">
                        <div class="continue-progress-bar">
                            <div class="continue-progress-fill" id="continue-fill-{{ $book->id }}" style="width: 0%;"></div>
                        </div>
                        <span class="continue-progress-text" id="continue-text-{{ $book->id }}">0%</span>
                    </div>
                </div>
                <a href="{{ route('books.read', $book->slug) }}" class="continue-btn" id="continue-btn-{{ $book->id }}">
                    <i class="bi bi-play-fill"></i> Baca
                </a>
            </div>
            @endforeach
        </div>
    </div>
    <div class="section-divider"><hr></div>
    @endif
    @endauth

    {{-- ═══════════ BUKU POPULER ═══════════ --}}
    @if($popularBooks->count() && !request('search') && !request('category'))
    <div class="popular-section">
        <h2 class="popular-section-title">
            <i class="bi bi-fire" style="color:#ef4444"></i> Buku Terpopuler
        </h2>
        <div class="popular-list-dash">
            @foreach($popularBooks as $index => $book)
            <a href="{{ route('books.show', $book->slug) }}" class="pop-item-dash">
                <div class="pop-num-dash">{{ str_pad($index+1, 2, '0', STR_PAD_LEFT) }}</div>
                <div class="pop-thumb-dash"
                     style="@if($book->cover_image) background-image: url('{{ Storage::url($book->cover_image) }}'); @else background: linear-gradient(145deg, {{ $book->cover_color ?? '#2C5F2E' }}, {{ $book->cover_color_dark ?? '#1d4220' }}); @endif"></div>
                <div class="pop-info-dash">
                    <div class="pop-title-dash">{{ $book->title }}</div>
                    <div class="pop-author-dash">{{ $book->author }}</div>
                    <div class="pop-meta-dash">
                        <span class="pop-stars-dash">
                            @for($i=1; $i<=5; $i++)<i class="bi {{ $i <= round($book->averageRating()) ? 'bi-star-fill' : 'bi-star' }}"></i>@endfor
                        </span>
                        <span class="pop-views-dash"><i class="bi bi-eye" style="font-size:0.65rem"></i> {{ number_format($book->view_count) }}</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    <div class="section-divider"><hr></div>
    @endif

    {{-- ── Semua Koleksi ── --}}
    @if($books->count())
    <div style="max-width:1200px;margin:0 auto;padding:0 24px;margin-bottom:18px;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap">
        <div style="display:flex;align-items:center;gap:8px">
            <i class="bi {{ request('search') ? 'bi-search' : (request('category') ? 'bi-folder' : 'bi-star') }}" style="font-size:1.4rem;color:var(--primary)"></i>
            <span style="font-family:'Playfair Display',serif;font-size:1.15rem;font-weight:700;color:var(--tx)">
                @if(request('search')) Hasil Pencarian
                @elseif(request('category')) {{ $categories->where('slug', request('category'))->first()?->name ?? 'Kategori' }}
                @else Semua Koleksi
                @endif
            </span>
        </div>
        <span class="dash-section-meta">{{ number_format($books->total()) }} buku ditemukan</span>
    </div>

    <div class="books-outer">
        <div class="books-grid-dash">
            @foreach($books as $book)
            <a href="{{ route('books.show', $book->slug) }}" class="bk-card-d">
                <div class="bk-cover-d"
                     style="@if($book->cover_image) background-image: url('{{ Storage::url($book->cover_image) }}'); background-size: cover; background-position: center; @else background: linear-gradient(145deg, {{ $book->cover_color ?? '#2C5F2E' }}, {{ $book->cover_color_dark ?? '#1d4220' }}); @endif">
                    @if($book->is_featured)
                    <span class="bk-star-badge"><i class="bi bi-star-fill" style="font-size:0.8rem;"></i></span>
                    @endif
                    <div class="bk-cover-title-d">{{ Str::limit($book->title, 22) }}</div>
                </div>
                <div class="bk-body-d">
                    <span class="bk-cat-d">{{ $book->category->name ?? '—' }}</span>
                    <div class="bk-title-d">{{ $book->title }}</div>
                    <div class="bk-author-d">{{ $book->author }}</div>
                    <div class="bk-footer-d">
                        <div style="display:flex;align-items:center;gap:4px">
                            <span class="bk-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi {{ $i <= round($book->averageRating()) ? 'bi-star-fill' : 'bi-star' }}"></i>
                                @endfor
                            </span>
                            <span class="bk-rating-txt">{{ number_format($book->averageRating(), 1) }}</span>
                        </div>
                        <span class="bk-views"><i class="bi bi-eye"></i> {{ number_format($book->view_count ?? 0) }}</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    <div class="dash-pagination">{{ $books->appends(request()->query())->links() }}</div>

    @else
    <div class="books-outer">
        <div class="dash-empty">
            <div class="dash-empty-icon">
                <i class="bi {{ request('search') ? 'bi-search' : 'bi-inbox' }}" style="font-size:2rem;color:var(--tx3)"></i>
            </div>
            <h3 class="dash-empty-title">
                @if(request('search')) Tidak ada hasil untuk<br>"{{ request('search') }}"
                @else Belum ada buku<br>di kategori ini
                @endif
            </h3>
            <p class="dash-empty-desc">
                @if(request('search')) Coba kata kunci lain atau telusuri berdasarkan kategori.
                @else Koleksi sedang diperbarui. Coba kategori lain sementara itu.
                @endif
            </p>
            <a href="{{ route('dashboard') }}" class="dash-empty-btn"><i class="bi bi-arrow-left"></i> Lihat Semua Buku</a>
        </div>
    </div>
    @endif

</div>

<x-mobile-bottom-nav active="home" />
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const active = document.querySelector('.dash-cat-pill.active');
        if (active) active.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });

        // Update progress bar untuk "Lanjutkan Membaca"
        document.querySelectorAll('.continue-card').forEach(card => {
            const bookId = card.dataset.bookId;
            const bookSlug = card.dataset.bookSlug;
            const totalPages = parseInt(card.dataset.totalPages || 0);
            const lastPage = localStorage.getItem('librova_last_page_' + bookId);

            if (lastPage) {
                const page = parseInt(lastPage);
                const fill = document.getElementById('continue-fill-' + bookId);
                const text = document.getElementById('continue-text-' + bookId);
                const btn = document.getElementById('continue-btn-' + bookId);

                if (totalPages > 0) {
                    const percent = Math.min(Math.round((page / totalPages) * 100), 100);
                    if (fill) fill.style.width = percent + '%';
                    if (text) text.textContent = percent + '%';
                } else {
                    if (text) text.textContent = 'Hlm ' + page;
                    if (fill) fill.style.width = '20%';
                }

                // Update link "Baca" agar langsung ke halaman terakhir
                if (btn && page > 1) {
                    btn.href = btn.href + '?page=' + page;
                }
            }
        });
    });
</script>
@endpush