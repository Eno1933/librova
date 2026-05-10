@extends('layouts.app')

@section('title', 'Kategori — Librova')

@push('styles')
<style>
    /* ═══════════════════════════════════════════
       CATEGORIES PAGE — LIBROVA STYLE
    ═══════════════════════════════════════════ */
    .cats-page {
        padding: 0 0 100px;
    }

    /* ── Hero Banner ── */
    .cats-hero {
        position: relative;
        overflow: hidden;
        padding: 44px 0 48px;
        border-bottom: 1px solid var(--border);
        margin-bottom: 0;
    }
    .cats-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse 50% 100% at 100% 50%, rgba(201, 168, 76, .07) 0%, transparent 60%),
            radial-gradient(ellipse 30% 70% at 0% 80%, rgba(44, 95, 46, .06) 0%, transparent 55%);
        pointer-events: none;
    }
    [data-theme="dark"] .cats-hero::before {
        background:
            radial-gradient(ellipse 50% 100% at 100% 50%, rgba(251, 191, 36, .05) 0%, transparent 60%),
            radial-gradient(ellipse 30% 70% at 0% 80%, rgba(74, 222, 128, .04) 0%, transparent 55%);
    }
    .cats-hero::after {
        content: 'K';
        position: absolute;
        right: -10px;
        top: 50%;
        transform: translateY(-50%);
        font-family: 'Playfair Display', serif;
        font-size: 200px;
        font-weight: 700;
        line-height: 1;
        color: var(--primary);
        opacity: .03;
        pointer-events: none;
        user-select: none;
    }
    .cats-hero-inner {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 24px;
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 24px;
        flex-wrap: wrap;
    }
    .cats-hero-left {}
    .cats-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: .75rem;
        font-weight: 600;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: var(--tx3);
        margin-bottom: 12px;
    }
    .cats-eyebrow-line {
        width: 20px;
        height: 1.5px;
        background: var(--primary);
        border-radius: 1px;
    }
    .cats-hero h1 {
        font-family: 'Playfair Display', serif;
        font-size: clamp(2rem, 4vw, 2.8rem);
        font-weight: 700;
        line-height: 1.15;
        letter-spacing: -.03em;
        color: var(--tx);
        margin-bottom: 10px;
    }
    .cats-hero h1 em {
        font-style: italic;
        color: var(--primary);
    }
    .cats-hero-desc {
        font-size: .9rem;
        color: var(--tx2);
        max-width: 460px;
        line-height: 1.7;
    }
    .cats-stat-row {
        display: flex;
        gap: 6px;
        align-items: center;
        margin-top: 20px;
        flex-wrap: wrap;
    }
    .cats-stat-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 14px;
        border-radius: 100px;
        background: var(--surface);
        border: 1px solid var(--border);
        font-size: .78rem;
        font-weight: 600;
        color: var(--tx2);
        transition: border-color .2s;
        text-decoration: none;
    }
    .cats-stat-chip:hover {
        border-color: var(--primary);
        color: var(--primary);
    }
    .cats-stat-chip .chip-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--gold);
        flex-shrink: 0;
    }
    .cats-stat-chip.chip-primary {
        background: rgba(44, 95, 46, .07);
        border-color: rgba(44, 95, 46, .2);
        color: var(--primary);
    }
    [data-theme="dark"] .cats-stat-chip.chip-primary {
        background: rgba(74, 222, 128, .07);
        border-color: rgba(74, 222, 128, .2);
    }

    /* ── Search di kanan ── */
    .cats-hero-search {
        flex-shrink: 0;
        width: 280px;
    }
    @media (max-width: 640px) {
        .cats-hero-search {
            width: 100%;
        }
    }
    .cats-search-box {
        display: flex;
        border-radius: 12px;
        border: 1.5px solid var(--border);
        background: var(--surface);
        transition: border-color .2s, box-shadow .2s;
        box-shadow: 0 2px 10px rgba(0, 0, 0, .04);
    }
    .cats-search-box:focus-within {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(44, 95, 46, .09);
    }
    [data-theme="dark"] .cats-search-box:focus-within {
        box-shadow: 0 0 0 3px rgba(74, 222, 128, .09);
    }
    .cats-search-icon {
        display: flex;
        align-items: center;
        padding: 0 12px;
        color: var(--tx3);
        flex-shrink: 0;
    }
    .cats-search-box input {
        flex: 1;
        padding: 11px 6px;
        border: none;
        background: transparent;
        font-family: inherit;
        font-size: .85rem;
        color: var(--tx);
        min-width: 0;
    }
    .cats-search-box input::placeholder {
        color: var(--tx3);
    }
    .cats-search-box input:focus {
        outline: none;
    }
    .cats-search-btn {
        margin: 5px;
        padding: 0 14px;
        border-radius: 8px;
        background: var(--primary);
        color: #fff;
        border: none;
        cursor: pointer;
        font-family: inherit;
        font-size: .8rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 5px;
        transition: background .2s;
        white-space: nowrap;
    }
    [data-theme="dark"] .cats-search-btn {
        color: var(--bg);
    }
    .cats-search-btn:hover {
        background: var(--primary-h);
    }

    /* ── Filter Tabs ── */
    .cats-tabs-wrap {
        position: sticky;
        top: var(--nav-h);
        z-index: 40;
        background: rgba(250, 247, 242, 0.92);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-bottom: 1px solid var(--border);
        padding: 0;
        transition: background .3s;
    }
    [data-theme="dark"] .cats-tabs-wrap {
        background: rgba(20, 20, 16, 0.92);
    }
    .cats-tabs-inner {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 24px;
        display: flex;
        align-items: center;
        gap: 0;
        overflow-x: auto;
        scrollbar-width: none;
    }
    .cats-tabs-inner::-webkit-scrollbar {
        display: none;
    }
    .cats-tab {
        padding: 14px 18px;
        border-bottom: 2px solid transparent;
        font-size: .82rem;
        font-weight: 500;
        color: var(--tx3);
        cursor: pointer;
        white-space: nowrap;
        transition: color .18s, border-color .18s;
        background: none;
        border-top: none;
        border-left: none;
        border-right: none;
        font-family: inherit;
        display: flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }
    .cats-tab:hover {
        color: var(--tx);
    }
    .cats-tab.active {
        color: var(--primary);
        border-bottom-color: var(--primary);
        font-weight: 600;
    }

    /* ── Body ── */
    .cats-body {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 24px;
    }
    .cats-section-label {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
    }
    .cats-section-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--tx);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .cats-count-badge {
        font-size: .7rem;
        font-weight: 600;
        padding: 2px 10px;
        border-radius: 100px;
        background: var(--gold-light);
        color: var(--gold-dim);
        letter-spacing: .02em;
    }
    [data-theme="dark"] .cats-count-badge {
        color: var(--gold);
    }

    /* ── Category Grid ── */
    .cats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 16px;
    }

    /* ── Category Card V2 ── */
    .cat-card-v2 {
        position: relative;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
        cursor: pointer;
        text-decoration: none;
        display: block;
        transition: transform .3s cubic-bezier(.34, 1.56, .64, 1), box-shadow .3s, border-color .25s;
        animation: catFadeUp .5s both;
    }
    .cat-card-v2:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 40px var(--shadow);
        border-color: var(--primary);
    }
    .cat-card-v2:nth-child(1) { animation-delay: .03s; }
    .cat-card-v2:nth-child(2) { animation-delay: .06s; }
    .cat-card-v2:nth-child(3) { animation-delay: .09s; }
    .cat-card-v2:nth-child(4) { animation-delay: .12s; }
    .cat-card-v2:nth-child(5) { animation-delay: .15s; }
    .cat-card-v2:nth-child(6) { animation-delay: .18s; }
    .cat-card-v2:nth-child(7) { animation-delay: .21s; }
    .cat-card-v2:nth-child(8) { animation-delay: .24s; }
    .cat-card-v2:nth-child(9) { animation-delay: .27s; }
    .cat-card-v2:nth-child(10) { animation-delay: .30s; }
    .cat-card-v2:nth-child(11) { animation-delay: .33s; }
    .cat-card-v2:nth-child(12) { animation-delay: .36s; }
    @keyframes catFadeUp {
        from { opacity: 0; transform: translateY(18px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .cat-card-v2::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--cat-accent, var(--primary));
        opacity: 0;
        transition: opacity .25s;
    }
    .cat-card-v2:hover::before {
        opacity: 1;
    }
    .cat-card-inner {
        padding: 22px 20px 18px;
    }
    .cat-icon-box {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 16px;
        background: var(--cat-bg, rgba(44, 95, 46, .08));
        transition: transform .3s cubic-bezier(.34, 1.56, .64, 1);
    }
    .cat-card-v2:hover .cat-icon-box {
        transform: scale(1.1) rotate(-5deg);
    }
    .cat-card-name {
        font-family: 'Playfair Display', serif;
        font-size: 1rem;
        font-weight: 700;
        color: var(--tx);
        margin-bottom: 4px;
        line-height: 1.25;
    }
    .cat-card-desc {
        font-size: .76rem;
        color: var(--tx3);
        line-height: 1.5;
        margin-bottom: 14px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .cat-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 12px;
        border-top: 1px solid var(--border);
    }
    .cat-book-count {
        font-size: .72rem;
        font-weight: 600;
        color: var(--tx2);
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .cat-book-count svg {
        width: 13px;
        height: 13px;
        color: var(--tx3);
    }
    .cat-arrow {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--tx3);
        flex-shrink: 0;
        transition: background .2s, border-color .2s, color .2s, transform .2s;
    }
    .cat-card-v2:hover .cat-arrow {
        background: var(--primary);
        border-color: var(--primary);
        color: #fff;
        transform: translateX(2px);
    }
    [data-theme="dark"] .cat-card-v2:hover .cat-arrow {
        color: var(--bg);
    }

    /* ── Empty State ── */
    .cats-empty {
        text-align: center;
        padding: 5rem 1rem;
        max-width: 320px;
        margin: 0 auto;
        animation: catFadeUp .5s both;
    }
    .cats-empty-icon {
        width: 72px;
        height: 72px;
        border-radius: 20px;
        background: var(--surface);
        border: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 18px;
        font-size: 28px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, .05);
    }
    .cats-empty-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--tx);
        margin-bottom: 8px;
    }
    .cats-empty-desc {
        font-size: .87rem;
        color: var(--tx3);
        line-height: 1.6;
        margin-bottom: 20px;
    }
    .cats-empty-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 10px 20px;
        border-radius: 100px;
        background: var(--primary);
        color: #fff;
        font-family: inherit;
        font-size: .85rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: background .2s, transform .15s;
        text-decoration: none;
    }
    [data-theme="dark"] .cats-empty-btn {
        color: var(--bg);
    }
    .cats-empty-btn:hover {
        background: var(--primary-h);
        transform: translateY(-2px);
    }

    /* ── Responsive ── */
    @media (max-width: 768px) {
        .cats-grid {
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 12px;
        }
        .cats-hero-search {
            width: 100%;
        }
    }
    @media (max-width: 480px) {
        .cats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
        .cats-hero {
            padding: 28px 0 32px;
        }
        .cat-card-inner {
            padding: 16px 14px 14px;
        }
        .cat-icon-box {
            width: 44px;
            height: 44px;
            font-size: 20px;
            margin-bottom: 12px;
        }
    }
</style>
@endpush

@section('content')
<div class="cats-page">

    {{-- ═══════════ HERO SECTION ═══════════ --}}
    <div class="cats-hero">
        <div class="cats-hero-inner">
            <div class="cats-hero-left">
                <h1>Temukan bacaan dari<br><em>kategori favoritmu</em></h1>
                <p class="cats-hero-desc">Pilih topik yang ingin kamu dalami. Setiap kategori dikurasi dengan koleksi e-book terbaik dari penulis ternama.</p>
                <div class="cats-stat-row">
                    <div class="cats-stat-chip chip-primary">
                        <span class="chip-dot" style="background:var(--primary)"></span>
                        {{ $totalCategories }} Kategori Utama
                    </div>
                    <div class="cats-stat-chip">
                        <span class="chip-dot"></span>
                        {{ number_format($totalBooks, 0, ',', '.') }}+ Buku
                    </div>
                    <div class="cats-stat-chip">
                        <span class="chip-dot" style="background:var(--tx3)"></span>
                        {{ $totalSubCategories }} Sub-kategori
                    </div>
                </div>
            </div>

            {{-- Search di kanan --}}
            <div class="cats-hero-search">
                <form action="{{ route('categories.index') }}" method="GET" class="cats-search-box">
                    <span class="cats-search-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input type="text" name="search" placeholder="Cari kategori…" value="{{ request('search') }}">
                    <button type="submit" class="cats-search-btn">
                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Cari
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- ═══════════ FILTER TABS ═══════════ --}}
    <div class="cats-tabs-wrap">
        <div class="cats-tabs-inner">
            <a href="{{ route('categories.index') }}" class="cats-tab {{ !request('search') ? 'active' : '' }}">Semua</a>
            @foreach($categories as $cat)
                <a href="{{ route('categories.index', ['search' => $cat->name]) }}" class="cats-tab {{ request('search') == $cat->name ? 'active' : '' }}">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- ═══════════ BODY ═══════════ --}}
    <div class="cats-body">
        <div class="cats-section-label">
            <div class="cats-section-title">
                ✦ {{ request('search') ? 'Hasil Pencarian' : 'Semua Kategori' }}
            </div>
            <span class="cats-count-badge">{{ $categories->count() }} kategori</span>
        </div>

        {{-- Grid Kategori --}}
        @if($categories->count())
            <div class="cats-grid">
                @foreach($categories as $category)
                    <a href="{{ route('categories.show', $category->slug) }}" class="cat-card-v2"
                       style="--cat-accent:{{ $category->accent_color ?? '#2C5F2E' }};">
                        <div class="cat-card-inner">
                            <div class="cat-icon-box" style="background:{{ $category->icon_bg ?? 'rgba(44,95,46,.1)' }}">
                                {{-- Ikon default buku --}}
                                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            <div class="cat-card-name">{{ $category->name }}</div>
                            <div class="cat-card-desc">{{ $category->description ?? 'Jelajahi koleksi ' . $category->name . ' terbaik.' }}</div>
                            <div class="cat-card-footer">
                                <div class="cat-book-count">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                    {{ $category->books_count ?? $category->books()->count() }} buku
                                </div>
                                <div class="cat-arrow">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            {{-- Empty State --}}
            <div class="cats-empty">
                <div class="cats-empty-icon">
                    <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                </div>
                <div class="cats-empty-title">Kategori tidak ditemukan</div>
                <p class="cats-empty-desc">Coba kata kunci lain atau telusuri semua kategori.</p>
                <a href="{{ route('categories.index') }}" class="cats-empty-btn">
                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Hapus Pencarian
                </a>
            </div>
        @endif
    </div>

</div>

{{-- Bottom navbar --}}
<x-mobile-bottom-nav active="categories" />
@endsection