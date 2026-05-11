@extends('layouts.app')

@section('title', 'Koleksi Buku — Librova')

@push('styles')
<style>
/* ═══════════════════════════════════════════
   BOOKS INDEX — PAGE STYLES (ICONS: Bootstrap Icons)
═══════════════════════════════════════════ */

.books-page { padding: 0 0 100px; }

/* ── Hero Banner ── */
.books-hero {
    position: relative; overflow: hidden;
    padding: 40px 0 44px;
    border-bottom: 1px solid var(--border);
}
.books-hero::before {
    content: ''; position: absolute; inset: 0;
    background:
        radial-gradient(ellipse 60% 90% at 100% 40%, rgba(44,95,46,.07)   0%, transparent 60%),
        radial-gradient(ellipse 35% 60% at 0%   70%, rgba(201,168,76,.06) 0%, transparent 55%);
    pointer-events: none;
}
[data-theme="dark"] .books-hero::before {
    background:
        radial-gradient(ellipse 60% 90% at 100% 40%, rgba(74,222,128,.05)  0%, transparent 60%),
        radial-gradient(ellipse 35% 60% at 0%   70%, rgba(251,191,36,.04)  0%, transparent 55%);
}
.books-hero::after {
    content: 'B';
    position: absolute; right: -8px; top: 50%; transform: translateY(-50%);
    font-family: 'Playfair Display', serif;
    font-size: 190px; font-weight: 700; line-height: 1;
    color: var(--primary); opacity: .03;
    pointer-events: none; user-select: none;
}
.books-hero-inner {
    max-width: 1200px; margin: 0 auto; padding: 0 24px;
    display: flex; align-items: flex-end; justify-content: space-between;
    gap: 20px; flex-wrap: wrap;
}

/* eyebrow */
.books-eyebrow {
    display: inline-flex; align-items: center; gap: 8px;
    font-size: .75rem; font-weight: 600; letter-spacing: .08em;
    text-transform: uppercase; color: var(--tx3); margin-bottom: 10px;
}
.eyebrow-line { width: 20px; height: 1.5px; background: var(--primary); border-radius: 1px; }

.books-hero h1 {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.9rem, 3.8vw, 2.7rem);
    font-weight: 700; line-height: 1.15;
    letter-spacing: -.025em; color: var(--tx); margin-bottom: 10px;
}
.books-hero h1 em { font-style: italic; color: var(--primary); }
.books-hero-desc { font-size: .9rem; color: var(--tx2); max-width: 450px; line-height: 1.7; }

/* stat chips */
.books-stat-row { display: flex; gap: 6px; margin-top: 20px; flex-wrap: wrap; }
.books-chip {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 6px 14px; border-radius: 100px;
    background: var(--surface); border: 1px solid var(--border);
    font-size: .78rem; font-weight: 600; color: var(--tx2);
    transition: border-color .2s, color .2s;
}
.books-chip.primary {
    background: rgba(44,95,46,.07); border-color: rgba(44,95,46,.2); color: var(--primary);
}
[data-theme="dark"] .books-chip.primary { background: rgba(74,222,128,.07); border-color: rgba(74,222,128,.2); }
.chip-dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }

/* search + sort on the right */
.books-hero-right { flex-shrink: 0; width: 320px; }
@media(max-width: 640px) { .books-hero-right { width: 100%; } }

.hero-search-box {
    display: flex; border-radius: 12px;
    border: 1.5px solid var(--border); background: var(--surface);
    box-shadow: 0 2px 10px rgba(0,0,0,.04);
    transition: border-color .2s, box-shadow .2s; margin-bottom: 10px;
}
.hero-search-box:focus-within {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(44,95,46,.09);
}
[data-theme="dark"] .hero-search-box:focus-within { box-shadow: 0 0 0 3px rgba(74,222,128,.09); }
.hs-icon { display: flex; align-items: center; padding: 0 12px; color: var(--tx3); flex-shrink: 0; }
.hero-search-box input {
    flex: 1; padding: 11px 6px; border: none; background: transparent;
    font-family: inherit; font-size: .85rem; color: var(--tx); min-width: 0;
}
.hero-search-box input::placeholder { color: var(--tx3); }
.hero-search-box input:focus { outline: none; }
.hs-btn {
    margin: 5px; padding: 0 16px; border-radius: 8px;
    background: var(--primary); color: #fff; border: none; cursor: pointer;
    font-family: inherit; font-size: .8rem; font-weight: 600;
    display: flex; align-items: center; gap: 5px;
    transition: background .2s; white-space: nowrap;
}
[data-theme="dark"] .hs-btn { color: var(--bg); }
.hs-btn:hover { background: var(--primary-h); }

/* sort row */
.sort-row { display: flex; gap: 8px; align-items: center; }
.sort-select {
    flex: 1; padding: 8px 28px 8px 12px; border-radius: 8px;
    border: 1.5px solid var(--border); background: var(--surface);
    font-family: inherit; font-size: .8rem; color: var(--tx2);
    appearance: none; cursor: pointer;
    background-image: url("data:image/svg+xml,%3Csvg width='12' height='8' viewBox='0 0 12 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%239A9282' stroke-width='1.5' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 10px center;
    transition: border-color .2s;
}
.sort-select:focus { outline: none; border-color: var(--primary); }

/* ── Sticky Filter Bar ── */
.filter-bar {
    position: sticky; top: var(--nav-h, 68px); z-index: 40;
    background: rgba(250,247,242,0.93);
    backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px);
    border-bottom: 1px solid var(--border);
    transition: background .3s;
}
[data-theme="dark"] .filter-bar { background: rgba(20,20,16,0.94); }
.filter-bar-inner {
    max-width: 1200px; margin: 0 auto; padding: 0 24px;
    display: flex; align-items: center;
    overflow-x: auto; scrollbar-width: none;
}
.filter-bar-inner::-webkit-scrollbar { display: none; }
.filter-tab {
    padding: 13px 18px; border-bottom: 2px solid transparent;
    font-size: .82rem; font-weight: 500; color: var(--tx3);
    cursor: pointer; white-space: nowrap;
    transition: color .18s, border-color .18s;
    background: none; border-top: none; border-left: none; border-right: none;
    font-family: inherit; display: flex; align-items: center; gap: 6px;
    text-decoration: none;
}
.filter-tab:hover { color: var(--tx); }
.filter-tab.active { color: var(--primary); border-bottom-color: var(--primary); font-weight: 600; }
.filter-divider { width: 1px; height: 20px; background: var(--border); flex-shrink: 0; margin: 0 4px; }

/* active filter chips */
.active-filters {
    max-width: 1200px; margin: 0 auto; padding: 12px 24px;
    display: flex; align-items: center; gap: 8px; flex-wrap: wrap;
}
.active-chip {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 4px 12px; border-radius: 100px;
    background: rgba(44,95,46,.08); border: 1px solid rgba(44,95,46,.2);
    font-size: .75rem; font-weight: 600; color: var(--primary);
}
[data-theme="dark"] .active-chip { background: rgba(74,222,128,.08); border-color: rgba(74,222,128,.2); }
.active-chip a {
    font-size: 14px; color: var(--primary); opacity: .7; text-decoration: none;
    line-height: 1; transition: opacity .15s;
}
.active-chip a:hover { opacity: 1; }

/* ── Main Body ── */
.books-body { max-width: 1200px; margin: 0 auto; padding: 32px 24px; }

/* result row */
.result-row {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 22px; gap: 12px; flex-wrap: wrap;
}
.result-label {
    font-family: 'Playfair Display', serif;
    font-size: 1.1rem; font-weight: 700; color: var(--tx);
    display: flex; align-items: center; gap: 8px;
}
.result-count { font-size: .78rem; color: var(--tx3); font-weight: 500; margin-top: 2px; }

/* view toggle */
.view-toggle {
    display: flex; gap: 0; border: 1.5px solid var(--border); border-radius: 8px; overflow: hidden;
}
.view-btn {
    width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;
    background: none; border: none; cursor: pointer; color: var(--tx3);
    transition: background .18s, color .18s; font-size: 1rem;
}
.view-btn.active { background: var(--primary); color: #fff; }
[data-theme="dark"] .view-btn.active { color: var(--bg); }
.view-btn:hover:not(.active) { background: var(--surface2); color: var(--tx); }

/* ── Book Grid ── */
.bk-grid-index {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(158px, 1fr));
    gap: 18px;
}

/* Card */
.bk-card-idx {
    background: var(--surface); border: 1px solid var(--border);
    border-radius: 14px; overflow: hidden;
    text-decoration: none; display: block;
    transition: transform .28s cubic-bezier(.34,1.56,.64,1), box-shadow .28s, border-color .2s;
    animation: bkFadeUp .5s both;
}
.bk-card-idx:hover { transform: translateY(-7px); box-shadow: 0 14px 40px var(--shadow); border-color: var(--border2); }
@for($d = 1; $d <= 12; $d++)
.bk-card-idx:nth-child({{ $d }}) { animation-delay: {{ ($d * 0.04) }}s; }
@endfor
@keyframes bkFadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

/* Cover */
.bk-cover-idx {
    aspect-ratio: 2/3; position: relative; overflow: hidden;
    display: flex; flex-direction: column; justify-content: flex-end; padding: 14px;
}
.bk-cover-idx::before {
    content: ''; position: absolute; top: 0; left: -60%; width: 40%; height: 100%;
    background: linear-gradient(105deg, transparent 0%, rgba(255,255,255,.07) 50%, transparent 100%);
    transition: left .6s ease; pointer-events: none;
}
.bk-card-idx:hover .bk-cover-idx::before { left: 130%; }
.bk-cover-idx::after {
    content: ''; position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,.62) 0%, rgba(0,0,0,.04) 55%, transparent 100%);
}
.bk-badge-idx {
    position: absolute; top: 10px; z-index: 2;
    font-size: .62rem; font-weight: 700;
    padding: 3px 9px; border-radius: 5px; letter-spacing: .03em;
    display: flex; align-items: center; gap: 3px;
}
.bk-badge-idx.featured { left: 10px; background: var(--gold); color: #000; }
.bk-badge-idx.new-tag  { right: 10px; background: rgba(0,0,0,.5); color: #fff; backdrop-filter: blur(4px); }
.bk-cover-title-idx {
    position: relative; z-index: 1;
    font-family: 'Playfair Display', serif;
    color: rgba(255,255,255,.93); font-size: .7rem; font-weight: 600; line-height: 1.3;
}

/* Body */
.bk-body-idx { padding: 12px 13px 13px; }
.bk-cat-idx {
    font-size: .62rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em;
    color: var(--primary); background: rgba(44,95,46,.08);
    padding: 2px 8px; border-radius: 4px; display: inline-block; margin-bottom: 7px;
}
[data-theme="dark"] .bk-cat-idx { background: rgba(74,222,128,.1); }
.bk-title-idx { font-size: .85rem; font-weight: 600; color: var(--tx); line-height: 1.3; margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.bk-author-idx { font-size: .74rem; color: var(--tx3); margin-bottom: 9px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.bk-footer-idx { display: flex; align-items: center; justify-content: space-between; gap: 4px; }
.bk-stars-idx { color: var(--gold); font-size: 11px; display: flex; gap: 1px; }
.bk-rating-txt { font-size: .7rem; color: var(--tx3); font-weight: 500; }
.bk-views { font-size: .65rem; color: var(--tx3); display: flex; align-items: center; gap: 3px; }
.bk-views i { font-size: 0.75rem; }

/* ── Empty State ── */
.bk-empty {
    text-align: center; padding: 5rem 1rem;
    max-width: 340px; margin: 0 auto;
    animation: bkFadeUp .5s both;
}
.bk-empty-icon {
    width: 72px; height: 72px; border-radius: 20px;
    background: var(--surface); border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 18px; font-size: 28px;
    box-shadow: 0 4px 20px rgba(0,0,0,.05);
}
.bk-empty-title { font-family: 'Playfair Display', serif; font-size: 1.2rem; font-weight: 700; color: var(--tx); margin-bottom: 8px; }
.bk-empty-desc { font-size: .87rem; color: var(--tx3); line-height: 1.6; margin-bottom: 20px; }
.bk-empty-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 10px 20px; border-radius: 100px;
    background: var(--primary); color: #fff;
    font-family: inherit; font-size: .85rem; font-weight: 600;
    text-decoration: none; transition: background .2s, transform .15s;
}
[data-theme="dark"] .bk-empty-btn { color: var(--bg); }
.bk-empty-btn:hover { background: var(--primary-h); transform: translateY(-2px); }

/* ── Pagination ── */
.bk-pagination { display: flex; justify-content: center; margin-top: 40px; }
.bk-pagination nav { display: flex; gap: 6px; align-items: center; }
.bk-pagination .pagination { display: flex; gap: 6px; list-style: none; align-items: center; }
.bk-pagination .page-item .page-link {
    display: flex; align-items: center; justify-content: center;
    min-width: 36px; height: 36px; padding: 0 10px; border-radius: 8px;
    font-size: .85rem; font-weight: 500; color: var(--tx2);
    background: var(--surface); border: 1px solid var(--border);
    text-decoration: none; transition: all .18s; gap: 5px;
}
.bk-pagination .page-item .page-link:hover { border-color: var(--primary); color: var(--primary); }
.bk-pagination .page-item.active .page-link {
    background: var(--primary); color: #fff; border-color: var(--primary); font-weight: 600;
}
[data-theme="dark"] .bk-pagination .page-item.active .page-link { color: var(--bg); }
.bk-pagination .page-item.disabled .page-link { opacity: .4; cursor: not-allowed; }

/* ── Responsive ── */
@media(max-width: 768px) {
    .bk-grid-index { grid-template-columns: repeat(auto-fill, minmax(138px, 1fr)); gap: 14px; }
}
@media(max-width: 480px) {
    .books-hero { padding: 26px 0 30px; }
    .bk-grid-index { grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 11px; }
}
</style>
@endpush

@section('content')
<div class="books-page">

    {{-- ── Hero Banner ── --}}
    <div class="books-hero">
        <div class="books-hero-inner">
            <div>
                <h1>
                    @if(request('search'))
                        Hasil untuk <em>"{{ request('search') }}"</em>
                    @else
                        Semua <em>koleksi buku</em><br>ada di sini
                    @endif
                </h1>
                <p class="books-hero-desc">
                    Cari, filter, dan temukan e-book sempurna dari ribuan judul pilihan yang dikurasi untuk kamu.
                </p>
                <div class="books-stat-row">
                    <div class="books-chip primary">
                        <i class="bi bi-book-fill chip-dot" style="background:var(--primary); font-size:0.7rem; width:auto; height:auto; border-radius:0; display:inline; margin-right:2px"></i>
                        {{ number_format($books->total()) }}+ Buku
                    </div>
                    <div class="books-chip">
                        <i class="bi bi-folder-fill chip-dot" style="background:var(--gold); color:var(--gold); font-size:0.7rem; width:auto; height:auto; border-radius:0; display:inline; margin-right:2px"></i>
                        {{ $categories->count() }} Kategori
                    </div>
                    <div class="books-chip">
                        <i class="bi bi-arrow-repeat chip-dot" style="background:var(--tx3); color:var(--tx3); font-size:0.7rem; width:auto; height:auto; border-radius:0; display:inline; margin-right:2px"></i>
                        Diperbarui harian
                    </div>
                </div>
            </div>

            {{-- Search + Sort --}}
            <div class="books-hero-right">
                <form action="{{ route('books.index') }}" method="GET">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    @if(request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif
                    <div class="hero-search-box">
                        <span class="hs-icon">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search"
                               placeholder="Judul, penulis, atau ISBN…"
                               value="{{ request('search') }}" autocomplete="off">
                        @if(request('search'))
                        <a href="{{ route('books.index', array_filter(['category' => request('category'), 'sort' => request('sort')])) }}"
                           style="display:flex;align-items:center;padding:0 10px;color:var(--tx3);font-size:18px;transition:color .15s">
                           <i class="bi bi-x"></i>
                        </a>
                        @endif
                        <button type="submit" class="hs-btn">
                            <i class="bi bi-search"></i>
                            Cari
                        </button>
                    </div>
                </form>

                <div class="sort-row">
                    <form action="{{ route('books.index') }}" method="GET" style="flex:1;display:flex">
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        <select name="sort" class="sort-select" onchange="this.form.submit()">
                            <option value="">Urutkan: Relevansi</option>
                            <option value="rating"    {{ request('sort') == 'rating'    ? 'selected' : '' }}>Rating Tertinggi</option>
                            <option value="popular"   {{ request('sort') == 'popular'   ? 'selected' : '' }}>Terpopuler</option>
                            <option value="newest"    {{ request('sort') == 'newest'    ? 'selected' : '' }}>Terbaru</option>
                            <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>Judul A–Z</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Sticky Filter Bar ── --}}
    <div class="filter-bar">
        <div class="filter-bar-inner">
            <a href="{{ route('books.index', array_filter(['search' => request('search'), 'sort' => request('sort')])) }}"
               class="filter-tab {{ !request('category') && !in_array(request('sort'), ['popular','newest']) ? 'active' : '' }}">
                <i class="bi bi-collection"></i> Semua
            </a>
            <a href="{{ route('books.index', array_filter(['search' => request('search'), 'sort' => 'popular'])) }}"
               class="filter-tab {{ request('sort') == 'popular' ? 'active' : '' }}">
                <i class="bi bi-fire"></i> Terpopuler
            </a>
            <a href="{{ route('books.index', array_filter(['search' => request('search'), 'sort' => 'newest'])) }}"
               class="filter-tab {{ request('sort') == 'newest' ? 'active' : '' }}">
                <i class="bi bi-clock-fill"></i> Terbaru
            </a>

            <div class="filter-divider"></div>

            @foreach($categories as $cat)
            <a href="{{ route('books.index', array_filter(['category' => $cat->slug, 'search' => request('search'), 'sort' => request('sort')])) }}"
               class="filter-tab {{ request('category') == $cat->slug ? 'active' : '' }}">
                <i class="bi bi-folder"></i> {{ $cat->name }}
            </a>
            @endforeach
        </div>
    </div>

    {{-- active filter indicator --}}
    @if(request('search') || request('category') || request('sort'))
    <div class="active-filters">
        @if(request('search'))
        <div class="active-chip">
            <i class="bi bi-search"></i> "{{ request('search') }}"
            <a href="{{ route('books.index', array_filter(['category' => request('category'), 'sort' => request('sort')])) }}" aria-label="Remove search filter"><i class="bi bi-x"></i></a>
        </div>
        @endif
        @if(request('category'))
        <div class="active-chip">
            <i class="bi bi-folder-fill"></i> {{ $categories->where('slug', request('category'))->first()?->name }}
            <a href="{{ route('books.index', array_filter(['search' => request('search'), 'sort' => request('sort')])) }}" aria-label="Remove category filter"><i class="bi bi-x"></i></a>
        </div>
        @endif
        @if(request('sort'))
        <div class="active-chip">
            <i class="bi bi-sort-down"></i> {{ ['popular'=>'Terpopuler','newest'=>'Terbaru','rating'=>'Rating Tertinggi','title_asc'=>'Judul A–Z'][request('sort')] ?? request('sort') }}
            <a href="{{ route('books.index', array_filter(['search' => request('search'), 'category' => request('category')])) }}" aria-label="Remove sort filter"><i class="bi bi-x"></i></a>
        </div>
        @endif
        <a href="{{ route('books.index') }}" style="font-size:.75rem;font-weight:600;color:var(--tx3);padding:4px 8px;border-radius:4px;transition:color .15s;text-decoration:none">
            <i class="bi bi-x-circle"></i> Hapus semua
        </a>
    </div>
    @endif

    {{-- ── Main Body ── --}}
    <div class="books-body">

        <div class="result-row">
            <div>
                <div class="result-label">
                    @if(request('search'))
                        <i class="bi bi-search" style="color:var(--primary); font-size:1.2rem"></i> Hasil Pencarian
                    @elseif(request('category'))
                        <i class="bi bi-folder-fill" style="color:var(--primary); font-size:1.2rem"></i> {{ $categories->where('slug', request('category'))->first()?->name }}
                    @else
                        <i class="bi bi-star-fill" style="color:var(--gold); font-size:1.2rem"></i> Koleksi Buku
                    @endif
                </div>
                <div class="result-count">{{ number_format($books->total()) }} buku ditemukan</div>
            </div>
            <div style="display:flex;align-items:center;gap:8px">
                <div class="view-toggle">
                    <button class="view-btn active" id="gridViewBtn" onclick="setView('grid')" title="Grid">
                        <i class="bi bi-grid-fill"></i>
                    </button>
                    <button class="view-btn" id="listViewBtn" onclick="setView('list')" title="List">
                        <i class="bi bi-list"></i>
                    </button>
                </div>
            </div>
        </div>

        @if($books->count())
        {{-- Book Grid --}}
        <div class="bk-grid-index" id="bookGrid">
            @foreach($books as $book)
            <a href="{{ route('books.show', $book->slug) }}" class="bk-card-idx">
                <div class="bk-cover-idx"
                     style="background: linear-gradient(145deg, {{ $book->cover_color ?? '#2C5F2E' }}, {{ $book->cover_color_dark ?? '#1d4220' }})">
                    @if($book->is_featured)
                    <span class="bk-badge-idx featured">
                        <i class="bi bi-star-fill" style="font-size:0.65rem"></i> Unggulan
                    </span>
                    @elseif($book->created_at->isAfter(now()->subDays(30)))
                    <span class="bk-badge-idx new-tag">
                        <i class="bi bi-bell-fill" style="font-size:0.65rem"></i> Baru
                    </span>
                    @endif
                    <div class="bk-cover-title-idx">{{ Str::limit($book->title, 22) }}</div>
                </div>
                <div class="bk-body-idx">
                    <span class="bk-cat-idx">{{ $book->category->name ?? '—' }}</span>
                    <div class="bk-title-idx">{{ $book->title }}</div>
                    <div class="bk-author-idx">{{ $book->author }}</div>
                    <div class="bk-footer-idx">
                        <div style="display:flex;align-items:center;gap:4px">
                            <span class="bk-stars-idx">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi {{ $i <= round($book->averageRating()) ? 'bi-star-fill' : 'bi-star' }}"></i>
                                @endfor
                            </span>
                            <span class="bk-rating-txt">{{ number_format($book->averageRating(), 1) }}</span>
                        </div>
                        <span class="bk-views">
                            <i class="bi bi-eye"></i>
                            {{ number_format($book->view_count ?? 0) }}
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="bk-pagination">
            {{ $books->appends(request()->query())->links() }}
        </div>

        @else
        {{-- Empty State --}}
        <div class="bk-empty">
            <div class="bk-empty-icon">
                <i class="bi {{ request('search') ? 'bi-search' : 'bi-inbox' }}" style="font-size:2rem; color:var(--tx3)"></i>
            </div>
            <div class="bk-empty-title">
                @if(request('search'))
                    Tidak ada hasil untuk<br>"{{ request('search') }}"
                @else
                    Belum ada buku di sini
                @endif
            </div>
            <p class="bk-empty-desc">
                @if(request('search'))
                    Coba kata kunci lain atau hapus filter yang aktif.
                @else
                    Koleksi akan segera diperbarui. Coba kategori lain.
                @endif
            </p>
            <a href="{{ route('books.index') }}" class="bk-empty-btn">
                <i class="bi bi-arrow-left"></i>
                Lihat Semua Buku
            </a>
        </div>
        @endif

    </div>{{-- /books-body --}}
</div>{{-- /books-page --}}

{{-- Bottom navbar hanya untuk user login --}}
@auth
    <x-mobile-bottom-nav active="books" />
@endauth
@endsection

@push('scripts')
<script>
// View toggle: grid ↔ list
function setView(v) {
    const grid = document.getElementById('bookGrid');
    const gb   = document.getElementById('gridViewBtn');
    const lb   = document.getElementById('listViewBtn');
    if (!grid) return;
    if (v === 'list') {
        grid.style.gridTemplateColumns = '1fr';
        grid.style.gap = '10px';
        lb.classList.add('active'); gb.classList.remove('active');
    } else {
        grid.style.gridTemplateColumns = '';
        grid.style.gap = '';
        gb.classList.add('active'); lb.classList.remove('active');
    }
    localStorage.setItem('librova-book-view', v);
}

// Restore last view preference
document.addEventListener('DOMContentLoaded', () => {
    const saved = localStorage.getItem('librova-book-view');
    if (saved === 'list') setView('list');

    // Scroll active filter tab into view
    const activeTab = document.querySelector('.filter-tab.active');
    if (activeTab) activeTab.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
});
</script>
@endpush