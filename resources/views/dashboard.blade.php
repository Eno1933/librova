@extends('layouts.app')

@section('title', 'Dashboard — Librova')

@push('styles')
<style>
/* ═══════════════════════════════════════════════
   DASHBOARD PAGE STYLES
═══════════════════════════════════════════════ */

/* ── Page layout ── */
.dash-page {
    padding: 0 0 32px;
    min-height: calc(100vh - var(--nav-h, 68px));
}

/* ── Welcome Banner ── */
.dash-banner {
    position: relative;
    overflow: hidden;
    padding: 32px 0 36px;
    margin-bottom: 32px;
    border-bottom: 1px solid var(--border);
}
.dash-banner::before {
    content: '';
    position: absolute; inset: 0;
    background:
        radial-gradient(ellipse 55% 80% at 90% 50%, rgba(201,168,76,0.07) 0%, transparent 65%),
        radial-gradient(ellipse 35% 60% at 5%  60%, rgba(44,95,46,0.06)   0%, transparent 60%);
    pointer-events: none;
}
[data-theme="dark"] .dash-banner::before {
    background:
        radial-gradient(ellipse 55% 80% at 90% 50%, rgba(251,191,36,0.05) 0%, transparent 65%),
        radial-gradient(ellipse 35% 60% at 5%  60%, rgba(74,222,128,0.04) 0%, transparent 60%);
}
.dash-banner-inner {
    max-width: 1200px; margin: 0 auto; padding: 0 24px;
    display: flex; align-items: center; justify-content: space-between; gap: 20px;
}
.dash-greeting {
    font-size: 0.8rem; font-weight: 600;
    text-transform: uppercase; letter-spacing: 0.07em;
    color: var(--tx3); margin-bottom: 6px;
    display: flex; align-items: center; gap: 8px;
}
.dash-greeting-dot {
    width: 6px; height: 6px; border-radius: 50%; background: var(--primary);
    animation: pulse-dot 2.4s ease-in-out infinite;
}
@keyframes pulse-dot {
    0%,100%{ opacity:1; transform:scale(1) }
    50%     { opacity:.4; transform:scale(0.7) }
}
.dash-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.5rem, 3.5vw, 2.1rem);
    font-weight: 700; line-height: 1.2;
    letter-spacing: -0.02em; color: var(--tx);
}
.dash-title em { font-style: italic; color: var(--primary); }
.dash-subtitle {
    font-size: 0.87rem; color: var(--tx2);
    margin-top: 6px; max-width: 420px; line-height: 1.6;
}

/* Banner quick stats */
.dash-stats {
    display: flex; gap: 6px; flex-shrink: 0;
}
.dash-stat {
    display: flex; flex-direction: column; align-items: center;
    padding: 12px 18px;
    background: var(--surface); border: 1px solid var(--border);
    border-radius: 12px; min-width: 80px;
    transition: border-color .2s, box-shadow .2s;
}
.dash-stat:hover { border-color: var(--primary); box-shadow: 0 2px 16px var(--shadow, rgba(44,95,46,.1)); }
.dash-stat-num {
    font-family: 'Playfair Display', serif;
    font-size: 1.35rem; font-weight: 700; color: var(--tx); line-height: 1;
}
.dash-stat-lbl { font-size: 0.65rem; font-weight: 500; color: var(--tx3); margin-top: 3px; white-space: nowrap; }

/* ── Search Bar ── */
.dash-search-wrap {
    max-width: 1200px; margin: 0 auto; padding: 0 24px 20px;
}
.dash-search {
    display: flex;
    border-radius: 12px;
    border: 1.5px solid var(--border);
    background: var(--surface);
    overflow: hidden;
    transition: border-color .2s, box-shadow .2s;
    box-shadow: 0 2px 12px rgba(0,0,0,0.04);
}
.dash-search:focus-within {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(44,95,46,0.09), 0 2px 16px rgba(0,0,0,0.06);
}
[data-theme="dark"] .dash-search:focus-within {
    box-shadow: 0 0 0 3px rgba(74,222,128,0.09);
}
.dash-search-icon {
    display: flex; align-items: center; padding: 0 14px 0 16px; color: var(--tx3); flex-shrink: 0;
}
.dash-search input {
    flex: 1; padding: 13px 8px;
    border: none; background: transparent;
    font-family: inherit; font-size: 0.92rem; color: var(--tx);
}
.dash-search input::placeholder { color: var(--tx3); }
.dash-search input:focus { outline: none; }
.dash-search-btn {
    margin: 6px; padding: 0 20px;
    background: var(--primary); color: #fff;
    border-radius: 8px; font-family: inherit;
    font-size: 0.85rem; font-weight: 600;
    display: flex; align-items: center; gap: 7px;
    transition: background .2s, transform .15s;
    white-space: nowrap; border: none; cursor: pointer;
}
[data-theme="dark"] .dash-search-btn { color: var(--bg, #141410); }
.dash-search-btn:hover { background: var(--primary-h, #1d4220); }
.dash-search-btn:active { transform: scale(0.96); }

/* ── Category Filter Pills ── */
.dash-cats-wrap {
    max-width: 1200px; margin: 0 auto; padding: 0 24px 28px;
}
.dash-cats-scroll {
    display: flex; gap: 8px;
    overflow-x: auto; padding-bottom: 4px;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
}
.dash-cats-scroll::-webkit-scrollbar { display: none; }
.dash-cat-pill {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 16px; border-radius: 100px;
    background: var(--surface); border: 1.5px solid var(--border);
    font-family: inherit; font-size: 0.8rem; font-weight: 500;
    color: var(--tx2); white-space: nowrap; flex-shrink: 0;
    cursor: pointer; text-decoration: none;
    transition: border-color .2s, color .2s, background .2s, transform .15s;
}
.dash-cat-pill:hover { border-color: var(--border2, #ccc5b5); color: var(--tx); transform: translateY(-1px); }
.dash-cat-pill.active {
    border-color: var(--primary); color: var(--primary);
    background: rgba(44,95,46,0.07);
    font-weight: 600;
}
[data-theme="dark"] .dash-cat-pill.active { background: rgba(74,222,128,0.08); }
.dash-cat-pill .cat-emoji { font-size: 14px; line-height: 1; }

/* ── Section header inside main grid ── */
.dash-section-head {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 20px;
    max-width: 1200px; margin-left: auto; margin-right: auto; padding: 0 24px;
}
.dash-section-head + .books-outer { padding-top: 0; }
.dash-section-label {
    font-family: 'Playfair Display', serif;
    font-size: 1.25rem; font-weight: 700; color: var(--tx);
    display: flex; align-items: center; gap: 8px;
}
.dash-section-meta {
    font-size: 0.78rem; color: var(--tx3); font-weight: 500;
}

/* ── Books Grid ── */
.books-outer {
    max-width: 1200px; margin: 0 auto; padding: 0 24px;
}
.books-grid-dash {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(155px, 1fr));
    gap: 18px;
}

/* ── Book Card ── */
.bk-card-d {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 14px; overflow: hidden;
    text-decoration: none; display: block;
    transition:
        transform .28s cubic-bezier(.34,1.56,.64,1),
        box-shadow .28s,
        border-color .2s;
    animation: fadeCardUp .5s both;
}
.bk-card-d:hover {
    transform: translateY(-7px);
    box-shadow: 0 14px 40px var(--shadow, rgba(44,95,46,.13));
    border-color: var(--border2, #ccc5b5);
}
.bk-card-d:nth-child(1)  { animation-delay: .04s }
.bk-card-d:nth-child(2)  { animation-delay: .08s }
.bk-card-d:nth-child(3)  { animation-delay: .12s }
.bk-card-d:nth-child(4)  { animation-delay: .16s }
.bk-card-d:nth-child(5)  { animation-delay: .20s }
.bk-card-d:nth-child(6)  { animation-delay: .24s }
.bk-card-d:nth-child(7)  { animation-delay: .28s }
.bk-card-d:nth-child(8)  { animation-delay: .32s }
.bk-card-d:nth-child(9)  { animation-delay: .36s }
.bk-card-d:nth-child(10) { animation-delay: .40s }
.bk-card-d:nth-child(11) { animation-delay: .44s }
.bk-card-d:nth-child(12) { animation-delay: .48s }
@keyframes fadeCardUp {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* Cover area */
.bk-cover-d {
    aspect-ratio: 2/3; position: relative; overflow: hidden;
    display: flex; flex-direction: column;
    justify-content: flex-end; padding: 14px;
}
/* subtle light sweep on cover */
.bk-cover-d::before {
    content: '';
    position: absolute; top: 0; left: -60%; width: 40%; height: 100%;
    background: linear-gradient(105deg, transparent 0%, rgba(255,255,255,0.07) 50%, transparent 100%);
    transition: left .6s ease;
    pointer-events: none;
}
.bk-card-d:hover .bk-cover-d::before { left: 130%; }
/* overlay */
.bk-cover-d::after {
    content: '';
    position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.62) 0%, rgba(0,0,0,0.05) 55%, transparent 100%);
}

/* Featured star badge */
.bk-star-badge {
    position: absolute; top: 10px; left: 10px; z-index: 2;
    width: 26px; height: 26px; border-radius: 50%;
    background: var(--gold, #C9A84C); color: #000;
    font-size: 11px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}
.bk-cover-title-d {
    position: relative; z-index: 1;
    font-family: 'Playfair Display', serif;
    color: rgba(255,255,255,0.93); font-size: 0.7rem; font-weight: 600; line-height: 1.3;
}

/* Card body */
.bk-body-d { padding: 12px 13px 13px; }
.bk-cat-d {
    font-size: 0.63rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.06em;
    color: var(--primary);
    background: rgba(44,95,46,0.08);
    padding: 2px 8px; border-radius: 4px;
    display: inline-block; margin-bottom: 7px;
}
[data-theme="dark"] .bk-cat-d { background: rgba(74,222,128,0.1); }
.bk-title-d {
    font-size: 0.85rem; font-weight: 600; color: var(--tx);
    line-height: 1.3; margin-bottom: 2px;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.bk-author-d {
    font-size: 0.74rem; color: var(--tx3);
    margin-bottom: 9px;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.bk-footer-d {
    display: flex; align-items: center; justify-content: space-between;
    gap: 4px;
}
.bk-stars { color: var(--gold, #C9A84C); font-size: 11px; letter-spacing: 0.5px; }
.bk-rating-txt { font-size: 0.7rem; color: var(--tx3); font-weight: 500; }
.bk-views {
    font-size: 0.65rem; color: var(--tx3);
    display: flex; align-items: center; gap: 3px;
}
.bk-views svg { width: 10px; height: 10px; }

/* ── Empty State ── */
.dash-empty {
    text-align: center; padding: 5rem 1rem;
    max-width: 360px; margin: 0 auto;
    animation: fadeCardUp .5s both;
}
.dash-empty-icon {
    width: 72px; height: 72px; border-radius: 20px;
    background: var(--surface); border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 20px; font-size: 28px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
}
.dash-empty-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.25rem; font-weight: 700; color: var(--tx); margin-bottom: 8px;
}
.dash-empty-desc { font-size: 0.87rem; color: var(--tx3); line-height: 1.6; margin-bottom: 20px; }
.dash-empty-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 10px 20px; border-radius: 100px;
    background: var(--primary); color: #fff;
    font-family: inherit; font-size: 0.85rem; font-weight: 600;
    text-decoration: none; border: none; cursor: pointer;
    transition: background .2s, transform .15s;
}
[data-theme="dark"] .dash-empty-btn { color: var(--bg, #141410); }
.dash-empty-btn:hover { background: var(--primary-h, #1d4220); transform: translateY(-2px); }

/* ── Pagination ── */
.dash-pagination {
    max-width: 1200px; margin: 32px auto 0; padding: 0 24px;
}
.dash-pagination nav {
    display: flex; justify-content: center;
}
/* Override default Laravel pagination for elegance */
.dash-pagination .pagination {
    display: flex; gap: 6px; list-style: none; align-items: center;
}
.dash-pagination .page-item .page-link {
    display: flex; align-items: center; justify-content: center;
    width: 36px; height: 36px; border-radius: 8px;
    font-size: 0.85rem; font-weight: 500; color: var(--tx2);
    background: var(--surface); border: 1px solid var(--border);
    text-decoration: none; transition: all .18s;
}
.dash-pagination .page-item .page-link:hover {
    border-color: var(--primary); color: var(--primary);
}
.dash-pagination .page-item.active .page-link {
    background: var(--primary); color: #fff; border-color: var(--primary);
    font-weight: 600;
}
[data-theme="dark"] .dash-pagination .page-item.active .page-link { color: var(--bg); }

/* ── Result count label ── */
.result-count {
    font-size: 0.8rem; color: var(--tx3); margin-bottom: 16px;
    padding: 0 24px;
    max-width: 1200px; margin-left: auto; margin-right: auto;
}
.result-count strong { color: var(--tx2); font-weight: 600; }

/* ── Responsive ── */
@media(max-width: 768px) {
    .dash-banner-inner { flex-direction: column; align-items: flex-start; gap: 16px; }
    .dash-stats { align-self: stretch; }
    .dash-stat { flex: 1; }
    .books-grid-dash { grid-template-columns: repeat(auto-fill, minmax(135px, 1fr)); gap: 14px; }
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

            {{-- Quick stats --}}
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
            {{-- Preserve category filter when searching --}}
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            <div class="dash-search">
                <span class="dash-search-icon">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </span>
                <input type="text" name="search"
                       placeholder="Cari judul, penulis, atau ISBN…"
                       value="{{ request('search') }}"
                       autocomplete="off">
                @if(request('search'))
                <a href="{{ route('dashboard', array_filter(['category' => request('category')])) }}"
                   style="display:flex;align-items:center;padding:0 12px;color:var(--tx3);text-decoration:none;font-size:18px;transition:color .15s"
                   title="Hapus pencarian">×</a>
                @endif
                <button type="submit" class="dash-search-btn">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Cari
                </button>
            </div>
        </form>
    </div>

    {{-- ── Category Pills ── --}}
    <div class="dash-cats-wrap">
        <div class="dash-cats-scroll">
            {{-- All --}}
            <a href="{{ route('dashboard', array_filter(['search' => request('search')])) }}"
               class="dash-cat-pill {{ !request('category') ? 'active' : '' }}">
                <span class="cat-emoji">📚</span> Semua
            </a>

            {{-- Dynamic categories --}}
            @foreach($categories as $cat)
            <a href="{{ route('dashboard', array_filter(['category' => $cat->slug, 'search' => request('search')])) }}"
               class="dash-cat-pill {{ request('category') == $cat->slug ? 'active' : '' }}">
                <span class="cat-emoji">{{ $cat->icon ?? '📖' }}</span>
                {{ $cat->name }}
            </a>
            @endforeach
        </div>
    </div>

    {{-- ── Section heading + result info ── --}}
    @if($books->count())
    <div style="max-width:1200px;margin:0 auto;padding:0 24px;margin-bottom:18px;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap">
        <div style="display:flex;align-items:center;gap:8px">
            <span style="font-family:'Playfair Display',serif;font-size:1.15rem;font-weight:700;color:var(--tx)">
                @if(request('search'))
                    🔍 Hasil Pencarian
                @elseif(request('category'))
                    📂 {{ $categories->where('slug', request('category'))->first()?->name ?? 'Kategori' }}
                @else
                    ✦ Semua Koleksi
                @endif
            </span>
        </div>
        <span style="font-size:0.78rem;color:var(--tx3);font-weight:500">
            {{ number_format($books->total()) }} buku ditemukan
        </span>
    </div>

    {{-- ── Book Grid ── --}}
    <div class="books-outer">
        <div class="books-grid-dash">
            @foreach($books as $book)
            <a href="{{ route('books.show', $book->slug) }}" class="bk-card-d">
                <div class="bk-cover-d"
                     style="background: linear-gradient(145deg, {{ $book->cover_color ?? '#2C5F2E' }}, {{ $book->cover_color_dark ?? '#1d4220' }})">
                    @if($book->is_featured)
                    <span class="bk-star-badge">★</span>
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
                                @for($i = 1; $i <= 5; $i++){{ $i <= round($book->averageRating()) ? '★' : '☆' }}@endfor
                            </span>
                            <span class="bk-rating-txt">{{ number_format($book->averageRating(), 1) }}</span>
                        </div>
                        <span class="bk-views">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            {{ number_format($book->view_count ?? 0) }}
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>

    {{-- ── Pagination ── --}}
    <div class="dash-pagination">
        {{ $books->appends(request()->query())->links() }}
    </div>

    @else
    {{-- ── Empty State ── --}}
    <div class="books-outer">
        <div class="dash-empty">
            <div class="dash-empty-icon">
                @if(request('search'))
                    🔍
                @else
                    📭
                @endif
            </div>
            <h3 class="dash-empty-title">
                @if(request('search'))
                    Tidak ada hasil untuk<br>"{{ request('search') }}"
                @else
                    Belum ada buku<br>di kategori ini
                @endif
            </h3>
            <p class="dash-empty-desc">
                @if(request('search'))
                    Coba kata kunci lain atau telusuri berdasarkan kategori.
                @else
                    Koleksi sedang diperbarui. Coba kategori lain sementara itu.
                @endif
            </p>
            <a href="{{ route('dashboard') }}" class="dash-empty-btn">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Lihat Semua Buku
            </a>
        </div>
    </div>
    @endif

</div>

<x-mobile-bottom-nav active="home" />
@endsection

@push('scripts')
<script>
    // Smooth active pill scroll into view on load
    document.addEventListener('DOMContentLoaded', () => {
        const active = document.querySelector('.dash-cat-pill.active');
        if (active) {
            active.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
        }
    });
</script>
@endpush