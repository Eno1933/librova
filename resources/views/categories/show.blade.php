@extends('layouts.app')

@section('title', $category->name . ' — Librova')

@push('styles')
<style>
/* ═══════════════════════════════════════════
   CATEGORY SHOW PAGE
═══════════════════════════════════════════ */
.cat-show-page { padding: 0 0 100px; }

/* ── Hero ── */
.cat-hero {
    position: relative; overflow: hidden;
    padding: 40px 0 44px;
    border-bottom: 1px solid var(--border);
}
.cat-hero::before {
    content: ''; position: absolute; inset: 0;
    background:
        radial-gradient(ellipse 55% 80% at 90% 50%, rgba(201,168,76,.07) 0%, transparent 60%),
        radial-gradient(ellipse 30% 60% at 5%  60%, rgba(44,95,46,.06)   0%, transparent 55%);
    pointer-events: none;
}
[data-theme="dark"] .cat-hero::before {
    background:
        radial-gradient(ellipse 55% 80% at 90% 50%, rgba(251,191,36,.05) 0%, transparent 60%),
        radial-gradient(ellipse 30% 60% at 5%  60%, rgba(74,222,128,.04) 0%, transparent 55%);
}
/* large decorative emoji */
.cat-hero::after {
    content: attr(data-icon);
    position: absolute; right: 24px; top: 50%; transform: translateY(-50%);
    font-size: 140px; opacity: .04; pointer-events: none;
    user-select: none; line-height: 1;
}

.cat-hero-inner {
    max-width: 1200px; margin: 0 auto; padding: 0 24px;
}

/* Breadcrumb */
.cat-breadcrumb {
    display: flex; align-items: center; gap: 6px;
    font-size: .78rem; color: var(--tx3); margin-bottom: 20px;
}
.cat-breadcrumb a {
    color: var(--tx3); text-decoration: none; font-weight: 500;
    transition: color .15s;
}
.cat-breadcrumb a:hover { color: var(--primary); }
.cat-breadcrumb-sep { font-size: 10px; opacity: .5; }
.cat-breadcrumb-cur { color: var(--tx2); font-weight: 600; }

/* Hero content row */
.cat-hero-row {
    display: flex; align-items: flex-end;
    justify-content: space-between; gap: 24px; flex-wrap: wrap;
}

/* Icon + title */
.cat-hero-left { display: flex; align-items: center; gap: 20px; }
.cat-big-icon {
    width: 64px; height: 64px; border-radius: 18px;
    display: flex; align-items: center; justify-content: center;
    font-size: 28px; flex-shrink: 0;
    background: var(--cat-icon-bg, rgba(44,95,46,.1));
    box-shadow: 0 4px 20px var(--shadow);
    transition: transform .3s cubic-bezier(.34,1.56,.64,1);
}
.cat-big-icon:hover { transform: scale(1.08) rotate(-5deg); }
.cat-eyebrow {
    font-size: .72rem; font-weight: 700; letter-spacing: .08em;
    text-transform: uppercase; color: var(--tx3); margin-bottom: 6px;
    display: flex; align-items: center; gap: 6px;
}
.cat-eyebrow-line { width: 16px; height: 1.5px; background: var(--primary); border-radius: 1px; }
.cat-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.8rem, 3.5vw, 2.5rem);
    font-weight: 700; line-height: 1.15;
    letter-spacing: -.025em; color: var(--tx);
    margin-bottom: 4px;
}
.cat-desc { font-size: .88rem; color: var(--tx2); line-height: 1.65; max-width: 480px; margin-top: 6px; }

/* Right stats */
.cat-hero-stats { display: flex; gap: 6px; flex-shrink: 0; }
.cat-stat-chip {
    display: flex; flex-direction: column; align-items: center;
    padding: 12px 20px;
    background: var(--surface); border: 1px solid var(--border);
    border-radius: 12px; min-width: 80px;
    transition: border-color .2s, box-shadow .2s;
}
.cat-stat-chip:hover { border-color: var(--primary); box-shadow: 0 2px 16px var(--shadow); }
.cat-stat-num {
    font-family: 'Playfair Display', serif;
    font-size: 1.4rem; font-weight: 700; color: var(--tx); line-height: 1;
}
.cat-stat-lbl { font-size: .65rem; font-weight: 500; color: var(--tx3); margin-top: 3px; white-space: nowrap; }

/* Subcategory pills */
.cat-subpills { display: flex; gap: 7px; flex-wrap: wrap; margin-top: 16px; }
.cat-subpill {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 13px; border-radius: 100px;
    background: var(--surface); border: 1px solid var(--border);
    font-size: .75rem; font-weight: 500; color: var(--tx2);
    text-decoration: none;
    transition: border-color .2s, color .2s, background .2s;
}
.cat-subpill:hover { border-color: var(--primary); color: var(--primary); background: rgba(44,95,46,.05); }
[data-theme="dark"] .cat-subpill:hover { background: rgba(74,222,128,.06); }

/* ── Filter / Sort bar ── */
.cat-filter-bar {
    position: sticky; top: var(--nav-h, 68px); z-index: 40;
    background: rgba(250,247,242,0.93);
    backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px);
    border-bottom: 1px solid var(--border);
    transition: background .3s;
}
[data-theme="dark"] .cat-filter-bar { background: rgba(20,20,16,0.94); }
.cat-filter-inner {
    max-width: 1200px; margin: 0 auto; padding: 0 24px;
    display: flex; align-items: center; justify-content: space-between;
    height: 52px; gap: 14px;
}
.cat-result-info {
    font-size: .82rem; color: var(--tx3); font-weight: 500;
    white-space: nowrap;
}
.cat-result-info strong { color: var(--tx2); }
.cat-sort-wrap { display: flex; align-items: center; gap: 8px; }
.cat-sort {
    padding: 7px 28px 7px 12px; border-radius: 9px;
    border: 1.5px solid var(--border); background: var(--surface);
    font-family: inherit; font-size: .8rem; color: var(--tx2);
    appearance: none; cursor: pointer;
    background-image: url("data:image/svg+xml,%3Csvg width='12' height='8' viewBox='0 0 12 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%239A9282' stroke-width='1.5' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 10px center;
    transition: border-color .2s;
}
.cat-sort:focus { outline: none; border-color: var(--primary); }
.cat-view-toggle { display: flex; border: 1.5px solid var(--border); border-radius: 9px; overflow: hidden; }
.cat-vbtn {
    width: 34px; height: 34px; display: flex; align-items: center; justify-content: center;
    background: none; border: none; cursor: pointer; color: var(--tx3);
    font-size: .9rem; transition: background .15s, color .15s;
}
.cat-vbtn.active { background: var(--primary); color: #fff; }
[data-theme="dark"] .cat-vbtn.active { color: var(--bg); }
.cat-vbtn:hover:not(.active) { background: var(--surface2); color: var(--tx); }

/* ── Books body ── */
.cat-body { max-width: 1200px; margin: 0 auto; padding: 28px 24px; }

/* Grid */
.cat-books-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(156px, 1fr));
    gap: 18px;
}

/* Book card */
.cat-bk-card {
    background: var(--surface); border: 1px solid var(--border);
    border-radius: 14px; overflow: hidden;
    text-decoration: none; display: block;
    transition: transform .28s cubic-bezier(.34,1.56,.64,1), box-shadow .28s, border-color .2s;
    animation: catBkUp .5s cubic-bezier(.22,1,.36,1) both;
}
.cat-bk-card:hover { transform: translateY(-7px); box-shadow: 0 14px 40px var(--shadow); border-color: var(--border2); }
.cat-bk-card:nth-child(1){animation-delay:.04s} .cat-bk-card:nth-child(2){animation-delay:.08s}
.cat-bk-card:nth-child(3){animation-delay:.12s} .cat-bk-card:nth-child(4){animation-delay:.16s}
.cat-bk-card:nth-child(5){animation-delay:.20s} .cat-bk-card:nth-child(6){animation-delay:.24s}
.cat-bk-card:nth-child(7){animation-delay:.28s} .cat-bk-card:nth-child(8){animation-delay:.32s}
.cat-bk-card:nth-child(9){animation-delay:.36s} .cat-bk-card:nth-child(10){animation-delay:.40s}
.cat-bk-card:nth-child(11){animation-delay:.44s} .cat-bk-card:nth-child(12){animation-delay:.48s}
@keyframes catBkUp { from{opacity:0;transform:translateY(18px)} to{opacity:1;transform:translateY(0)} }

.cat-bk-cover {
    aspect-ratio: 2/3; position: relative; overflow: hidden;
    display: flex; flex-direction: column; justify-content: flex-end; padding: 14px;
}
.cat-bk-cover::before {
    content: ''; position: absolute; top: 0; left: -60%; width: 40%; height: 100%;
    background: linear-gradient(105deg, transparent 0%, rgba(255,255,255,.07) 50%, transparent 100%);
    transition: left .6s ease; pointer-events: none;
}
.cat-bk-card:hover .cat-bk-cover::before { left: 130%; }
.cat-bk-cover::after {
    content: ''; position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,.62) 0%, rgba(0,0,0,.04) 55%, transparent 100%);
}
.cat-bk-badge {
    position: absolute; top: 10px; left: 10px; z-index: 2;
    background: var(--gold); color: #000;
    font-size: .6rem; font-weight: 700; padding: 2px 8px; border-radius: 4px;
}
.cat-bk-cover-title {
    position: relative; z-index: 1;
    font-family: 'Playfair Display', serif;
    color: rgba(255,255,255,.93); font-size: .7rem; font-weight: 600; line-height: 1.3;
}
.cat-bk-body { padding: 12px 13px 13px; }
.cat-bk-cat {
    font-size: .62rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em;
    color: var(--primary); background: rgba(44,95,46,.08);
    padding: 2px 8px; border-radius: 4px; display: inline-block; margin-bottom: 6px;
}
[data-theme="dark"] .cat-bk-cat { background: rgba(74,222,128,.1); }
.cat-bk-title { font-size: .85rem; font-weight: 600; color: var(--tx); line-height: 1.3; margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.cat-bk-author { font-size: .74rem; color: var(--tx3); margin-bottom: 8px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.cat-bk-footer { display: flex; align-items: center; justify-content: space-between; gap: 4px; }
.cat-bk-stars  { color: var(--gold); font-size: 11px; letter-spacing: .5px; }
.cat-bk-rating { font-size: .7rem; color: var(--tx3); font-weight: 500; }
.cat-bk-views  { font-size: .65rem; color: var(--tx3); display: flex; align-items: center; gap: 3px; }

/* List view */
.cat-books-grid.list-view { grid-template-columns: 1fr; gap: 10px; }
.cat-books-grid.list-view .cat-bk-card { display: flex; flex-direction: row; border-radius: 12px; }
.cat-books-grid.list-view .cat-bk-cover { aspect-ratio: unset; width: 80px; min-height: 116px; flex-shrink: 0; border-radius: 0; }
.cat-books-grid.list-view .cat-bk-body { flex: 1; padding: 12px 16px; display: flex; flex-direction: column; justify-content: center; }
.cat-books-grid.list-view .cat-bk-cover::before { display: none; }
.cat-books-grid.list-view .cat-bk-title { white-space: normal; }

/* ── Empty State ── */
.cat-empty {
    text-align: center; padding: 64px 20px;
    max-width: 340px; margin: 0 auto;
    animation: catBkUp .5s cubic-bezier(.22,1,.36,1) both;
}
.cat-empty-icon {
    width: 80px; height: 80px; border-radius: 22px;
    background: var(--surface); border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 18px; font-size: 30px;
    box-shadow: 0 4px 20px rgba(0,0,0,.05);
}
.cat-empty-title { font-family: 'Playfair Display', serif; font-size: 1.2rem; font-weight: 700; color: var(--tx); margin-bottom: 8px; }
.cat-empty-desc  { font-size: .87rem; color: var(--tx3); line-height: 1.6; margin-bottom: 20px; }
.cat-empty-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 11px 24px; border-radius: 100px;
    background: var(--primary); color: #fff;
    font-family: inherit; font-size: .875rem; font-weight: 600;
    text-decoration: none; transition: background .2s, transform .15s;
    box-shadow: 0 3px 12px var(--shadow);
}
[data-theme="dark"] .cat-empty-btn { color: var(--bg); }
.cat-empty-btn:hover { background: var(--primary-h); transform: translateY(-1px); }

/* ── Pagination ── */
.cat-pagination { display: flex; justify-content: center; margin-top: 36px; }
.cat-pagination nav { display: flex; gap: 6px; align-items: center; }
.cat-pagination .pagination { display: flex; gap: 6px; list-style: none; align-items: center; }
.cat-pagination .page-item .page-link {
    display: flex; align-items: center; justify-content: center;
    min-width: 36px; height: 36px; padding: 0 10px; border-radius: 8px;
    font-size: .85rem; font-weight: 500; color: var(--tx2);
    background: var(--surface); border: 1px solid var(--border);
    text-decoration: none; transition: all .18s;
}
.cat-pagination .page-item .page-link:hover { border-color: var(--primary); color: var(--primary); }
.cat-pagination .page-item.active .page-link { background: var(--primary); color: #fff; border-color: var(--primary); font-weight: 600; }
[data-theme="dark"] .cat-pagination .page-item.active .page-link { color: var(--bg); }
.cat-pagination .page-item.disabled .page-link { opacity: .4; cursor: not-allowed; }

/* Responsive */
@media(max-width: 768px) { .cat-books-grid { grid-template-columns: repeat(auto-fill, minmax(135px,1fr)); gap: 12px; } .cat-hero-stats { display: none; } }
@media(max-width: 480px) { .cat-books-grid { grid-template-columns: repeat(auto-fill, minmax(118px,1fr)); gap: 10px; } .cat-hero { padding: 26px 0 30px; } }
</style>
@endpush

@section('content')
@php
    /* Map common category slugs to icon + bg color */
    $catIconMap = [
        'fiksi'        => ['icon' => '📖', 'bg' => 'rgba(139,58,58,.1)'],
        'sains'        => ['icon' => '🔬', 'bg' => 'rgba(26,92,122,.1)'],
        'bisnis'       => ['icon' => '💼', 'bg' => 'rgba(201,168,76,.12)'],
        'teknologi'    => ['icon' => '💻', 'bg' => 'rgba(26,58,92,.1)'],
        'psikologi'    => ['icon' => '🧠', 'bg' => 'rgba(91,45,142,.1)'],
        'self-dev'     => ['icon' => '🌱', 'bg' => 'rgba(44,95,46,.1)'],
        'sejarah'      => ['icon' => '🏛️',  'bg' => 'rgba(139,69,19,.1)'],
        'seni'         => ['icon' => '🎨', 'bg' => 'rgba(58,107,92,.1)'],
        'filsafat'     => ['icon' => '📐', 'bg' => 'rgba(91,45,142,.08)'],
        'pendidikan'   => ['icon' => '🎓', 'bg' => 'rgba(26,92,122,.08)'],
        'kesehatan'    => ['icon' => '❤️',  'bg' => 'rgba(239,68,68,.08)'],
    ];
    $ci = $catIconMap[Str::slug($category->name)] ?? ['icon' => $category->icon ?? '📚', 'bg' => 'rgba(44,95,46,.09)'];
@endphp

<div class="cat-show-page">

    {{-- ── Hero ── --}}
    <div class="cat-hero" data-icon="{{ $ci['icon'] }}">
        <div class="cat-hero-inner">

            {{-- Breadcrumb --}}
            <div class="cat-breadcrumb">
                <a href="{{ route('home') }}">
                    <i class="bi bi-house"></i>
                </a>
                <span class="cat-breadcrumb-sep"><i class="bi bi-chevron-right"></i></span>
                <a href="{{ route('categories.index') }}">Kategori</a>
                <span class="cat-breadcrumb-sep"><i class="bi bi-chevron-right"></i></span>
                <span class="cat-breadcrumb-cur">{{ $category->name }}</span>
            </div>

            {{-- Content row --}}
            <div class="cat-hero-row">
                <div>
                    <div class="cat-hero-left">
                        <div class="cat-big-icon" style="--cat-icon-bg:{{ $ci['bg'] }}">
                            {{ $ci['icon'] }}
                        </div>
                        <div>
                            <div class="cat-eyebrow">
                                <span class="cat-eyebrow-line"></span>
                                Kategori Buku
                            </div>
                            <h1 class="cat-title">{{ $category->name }}</h1>
                        </div>
                    </div>

                    @if($category->description)
                    <p class="cat-desc">{{ $category->description }}</p>
                    @endif

                    {{-- Subcategory pills --}}
                    @if($category->children->count())
                    <div class="cat-subpills">
                        <span style="font-size:.72rem;font-weight:600;color:var(--tx3);align-self:center">Sub:</span>
                        @foreach($category->children as $child)
                        <a href="{{ route('categories.show', $child->slug) }}" class="cat-subpill">
                            <i class="bi bi-folder2" style="font-size:.7rem"></i>
                            {{ $child->name }}
                        </a>
                        @endforeach
                    </div>
                    @endif
                </div>

                {{-- Stats --}}
                <div class="cat-hero-stats">
                    <div class="cat-stat-chip">
                        <span class="cat-stat-num">{{ number_format($books->total()) }}</span>
                        <span class="cat-stat-lbl">Buku</span>
                    </div>
                    @if($category->children->count())
                    <div class="cat-stat-chip">
                        <span class="cat-stat-num">{{ $category->children->count() }}</span>
                        <span class="cat-stat-lbl">Sub-Kategori</span>
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    {{-- ── Filter Bar ── --}}
    @if($books->total() > 0)
    <div class="cat-filter-bar">
        <div class="cat-filter-inner">
            <div class="cat-result-info">
                <strong>{{ number_format($books->total()) }}</strong> buku ditemukan
            </div>
            <div class="cat-sort-wrap">
                <form method="GET" action="{{ route('categories.show', $category->slug) }}">
                    <select name="sort" class="cat-sort" onchange="this.form.submit()">
                        <option value="newest"  {{ request('sort','newest')=='newest'  ? 'selected' : '' }}>Terbaru</option>
                        <option value="popular" {{ request('sort')=='popular' ? 'selected' : '' }}>Terpopuler</option>
                        <option value="rating"  {{ request('sort')=='rating'  ? 'selected' : '' }}>Rating Tertinggi</option>
                        <option value="title"   {{ request('sort')=='title'   ? 'selected' : '' }}>Judul A–Z</option>
                    </select>
                </form>
                <div class="cat-view-toggle">
                    <button class="cat-vbtn active" id="gridBtn" onclick="setView('grid')" title="Grid">
                        <i class="bi bi-grid-3x3-gap"></i>
                    </button>
                    <button class="cat-vbtn" id="listBtn" onclick="setView('list')" title="List">
                        <i class="bi bi-list-ul"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ── Body ── --}}
    <div class="cat-body">

        @if($books->count())

        <div class="cat-books-grid" id="catGrid">
            @foreach($books as $book)
            <a href="{{ route('books.show', $book->slug) }}" class="cat-bk-card">
                <div class="cat-bk-cover"
                     style="background:linear-gradient(145deg,{{ $book->cover_color ?? '#2C5F2E' }},{{ $book->cover_color_dark ?? '#1d4220' }})">
                    @if($book->cover_image)
                    <img src="{{ Storage::url($book->cover_image) }}" alt=""
                         style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover">
                    @endif
                    @if($book->is_featured)
                    <span class="cat-bk-badge">
                        <i class="bi bi-star-fill" style="font-size:.55rem"></i> Unggulan
                    </span>
                    @endif
                    <div class="cat-bk-cover-title">{{ Str::limit($book->title, 22) }}</div>
                </div>
                <div class="cat-bk-body">
                    <span class="cat-bk-cat">{{ $book->category->name ?? '—' }}</span>
                    <div class="cat-bk-title">{{ $book->title }}</div>
                    <div class="cat-bk-author">{{ $book->author }}</div>
                    <div class="cat-bk-footer">
                        <div style="display:flex;align-items:center;gap:4px">
                            <span class="cat-bk-stars">
                                @for($i=1;$i<=5;$i++){{ $i<=round($book->averageRating())?'★':'☆' }}@endfor
                            </span>
                            <span class="cat-bk-rating">{{ number_format($book->averageRating(),1) }}</span>
                        </div>
                        <span class="cat-bk-views">
                            <i class="bi bi-eye" style="font-size:9px"></i>
                            {{ number_format($book->view_count ?? 0) }}
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <div class="cat-pagination">
            {{ $books->appends(request()->query())->links() }}
        </div>

        @else

        {{-- Empty state --}}
        <div class="cat-empty">
            <div class="cat-empty-icon">{{ $ci['icon'] }}</div>
            <div class="cat-empty-title">Belum ada buku</div>
            <p class="cat-empty-desc">
                Kategori <strong>{{ $category->name }}</strong> masih kosong. Kunjungi kembali nanti atau jelajahi kategori lain.
            </p>
            <a href="{{ route('categories.index') }}" class="cat-empty-btn">
                <i class="bi bi-grid-1x2"></i>
                Lihat Kategori Lain
            </a>
        </div>

        @endif

    </div>{{-- /cat-body --}}
</div>{{-- /cat-show-page --}}

<x-mobile-bottom-nav active="categories" />
@endsection

@push('scripts')
<script>
function setView(v) {
    const grid = document.getElementById('catGrid');
    const gb   = document.getElementById('gridBtn');
    const lb   = document.getElementById('listBtn');
    if (!grid) return;

    if (v === 'list') {
        grid.classList.add('list-view');
        lb.classList.add('active'); gb.classList.remove('active');
    } else {
        grid.classList.remove('list-view');
        gb.classList.add('active'); lb.classList.remove('active');
    }
    localStorage.setItem('librova-cat-view', v);
}

document.addEventListener('DOMContentLoaded', () => {
    const saved = localStorage.getItem('librova-cat-view');
    if (saved === 'list') setView('list');
});
</script>
@endpush