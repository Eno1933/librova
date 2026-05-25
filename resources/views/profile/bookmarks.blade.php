@extends('layouts.app')

@section('title', 'Bookmark — Librova')

@push('styles')
<style>
/* ═══════════════════════════════════════════
   BOOKMARK PAGE
═══════════════════════════════════════════ */

.bm-page { padding: 0 0 100px; }

/* ── Hero Banner ── */
.bm-hero {
    position: relative; overflow: hidden;
    padding: 38px 0 40px;
    border-bottom: 1px solid var(--border);
    margin-bottom: 0;
}
.bm-hero::before {
    content: ''; position: absolute; inset: 0;
    background:
        radial-gradient(ellipse 50% 80% at 90% 50%, rgba(201,168,76,.07) 0%, transparent 60%),
        radial-gradient(ellipse 30% 60% at 5%  60%, rgba(44,95,46,.06)   0%, transparent 55%);
    pointer-events: none;
}
[data-theme="dark"] .bm-hero::before {
    background:
        radial-gradient(ellipse 50% 80% at 90% 50%, rgba(251,191,36,.05) 0%, transparent 60%),
        radial-gradient(ellipse 30% 60% at 5%  60%, rgba(74,222,128,.04) 0%, transparent 55%);
}
.bm-hero::after {
    content: '🔖';
    position: absolute; right: 24px; top: 50%; transform: translateY(-50%);
    font-size: 120px; opacity: .04; pointer-events: none; user-select: none;
    line-height: 1;
}
.bm-hero-inner {
    max-width: 1200px; margin: 0 auto; padding: 0 24px;
    display: flex; align-items: flex-end; justify-content: space-between;
    gap: 20px; flex-wrap: wrap;
}
.bm-eyebrow {
    display: inline-flex; align-items: center; gap: 8px;
    font-size: .72rem; font-weight: 700; letter-spacing: .08em;
    text-transform: uppercase; color: var(--tx3); margin-bottom: 10px;
}
.bm-eyebrow-line { width: 18px; height: 1.5px; background: var(--gold); border-radius: 1px; }
.bm-hero h1 {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.8rem, 3.5vw, 2.5rem);
    font-weight: 700; line-height: 1.15; letter-spacing: -.025em;
    color: var(--tx); margin-bottom: 8px;
}
.bm-hero h1 em { font-style: italic; color: var(--primary); }
.bm-hero-desc { font-size: .88rem; color: var(--tx2); line-height: 1.65; }

/* count chip */
.bm-count-chip {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 8px 18px; border-radius: 100px;
    background: var(--surface); border: 1px solid var(--border);
    font-size: .82rem; font-weight: 600; color: var(--tx2);
    white-space: nowrap; flex-shrink: 0; align-self: flex-end;
}
.bm-count-chip i { color: var(--gold); }

/* ── Main Body ── */
.bm-body { max-width: 1200px; margin: 0 auto; padding: 32px 24px; }

/* Sort / filter row */
.bm-toolbar {
    display: flex; align-items: center; justify-content: space-between;
    gap: 12px; margin-bottom: 24px; flex-wrap: wrap;
}
.bm-toolbar-left {
    font-family: 'Playfair Display', serif;
    font-size: 1.1rem; font-weight: 700; color: var(--tx);
    display: flex; align-items: center; gap: 8px;
}
.bm-toolbar-right { display: flex; align-items: center; gap: 8px; }
.bm-sort {
    padding: 8px 28px 8px 12px; border-radius: 9px;
    border: 1.5px solid var(--border); background: var(--surface);
    font-family: inherit; font-size: .8rem; color: var(--tx2);
    appearance: none; cursor: pointer;
    background-image: url("data:image/svg+xml,%3Csvg width='12' height='8' viewBox='0 0 12 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%239A9282' stroke-width='1.5' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 10px center;
    transition: border-color .2s;
}
.bm-sort:focus { outline: none; border-color: var(--primary); }
.bm-view-toggle { display: flex; border: 1.5px solid var(--border); border-radius: 9px; overflow: hidden; }
.bm-vbtn {
    width: 34px; height: 34px; display: flex; align-items: center; justify-content: center;
    background: none; border: none; cursor: pointer; color: var(--tx3);
    font-size: .9rem; transition: background .15s, color .15s;
}
.bm-vbtn.active { background: var(--primary); color: #fff; }
[data-theme="dark"] .bm-vbtn.active { color: var(--bg); }
.bm-vbtn:hover:not(.active) { background: var(--surface2); color: var(--tx); }

/* ── Book Grid ── */
.bm-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(155px, 1fr));
    gap: 18px;
}

/* ── Book Card ── */
.bm-card {
    background: var(--surface); border: 1px solid var(--border);
    border-radius: 14px; overflow: hidden;
    text-decoration: none; display: block;
    position: relative;
    transition: transform .28s cubic-bezier(.34,1.56,.64,1), box-shadow .28s, border-color .2s;
    animation: bmUp .5s cubic-bezier(.22,1,.36,1) both;
}
.bm-card:hover { transform: translateY(-7px); box-shadow: 0 14px 40px var(--shadow); border-color: var(--border2); }
.bm-card:nth-child(1){animation-delay:.04s} .bm-card:nth-child(2){animation-delay:.08s}
.bm-card:nth-child(3){animation-delay:.12s} .bm-card:nth-child(4){animation-delay:.16s}
.bm-card:nth-child(5){animation-delay:.20s} .bm-card:nth-child(6){animation-delay:.24s}
.bm-card:nth-child(7){animation-delay:.28s} .bm-card:nth-child(8){animation-delay:.32s}
.bm-card:nth-child(9){animation-delay:.36s} .bm-card:nth-child(10){animation-delay:.40s}
.bm-card:nth-child(11){animation-delay:.44s} .bm-card:nth-child(12){animation-delay:.48s}
@keyframes bmUp { from{opacity:0;transform:translateY(18px)} to{opacity:1;transform:translateY(0)} }

/* Cover */
.bm-cover {
    aspect-ratio: 2/3; position: relative; overflow: hidden;
    display: flex; flex-direction: column; justify-content: flex-end; padding: 14px;
}
.bm-cover::before {
    content: ''; position: absolute; top: 0; left: -60%; width: 40%; height: 100%;
    background: linear-gradient(105deg, transparent 0%, rgba(255,255,255,.07) 50%, transparent 100%);
    transition: left .6s ease; pointer-events: none;
}
.bm-card:hover .bm-cover::before { left: 130%; }
.bm-cover::after {
    content: ''; position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,.62) 0%, rgba(0,0,0,.04) 55%, transparent 100%);
}
.bm-cover-title {
    position: relative; z-index: 1;
    font-family: 'Playfair Display', serif;
    color: rgba(255,255,255,.93); font-size: .7rem; font-weight: 600; line-height: 1.3;
}

/* Bookmark icon badge (top right) */
.bm-icon-badge {
    position: absolute; top: 10px; right: 10px; z-index: 2;
    width: 28px; height: 28px; border-radius: 50%;
    background: rgba(0,0,0,.4); backdrop-filter: blur(4px);
    display: flex; align-items: center; justify-content: center;
    color: var(--gold); font-size: 13px;
}

/* Remove btn */
.bm-remove-btn {
    position: absolute; top: 10px; left: 10px; z-index: 3;
    width: 26px; height: 26px; border-radius: 50%;
    background: rgba(239,68,68,.85); color: #fff;
    display: none; align-items: center; justify-content: center;
    font-size: 11px; cursor: pointer; border: none;
    transition: background .15s, transform .15s;
}
.bm-card:hover .bm-remove-btn { display: flex; }
.bm-remove-btn:hover { background: #dc2626; transform: scale(1.1); }

/* Body */
.bm-card-body { padding: 12px 13px 13px; }
.bm-cat {
    font-size: .62rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em;
    color: var(--primary); background: rgba(44,95,46,.08);
    padding: 2px 8px; border-radius: 4px; display: inline-block; margin-bottom: 6px;
}
[data-theme="dark"] .bm-cat { background: rgba(74,222,128,.1); }
.bm-title { font-size: .85rem; font-weight: 600; color: var(--tx); line-height: 1.3; margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.bm-author { font-size: .74rem; color: var(--tx3); margin-bottom: 8px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.bm-footer { display: flex; align-items: center; justify-content: space-between; gap: 4px; }
.bm-stars { color: var(--gold); font-size: 11px; letter-spacing: .5px; }
.bm-rating-num { font-size: .7rem; color: var(--tx3); font-weight: 500; }
.bm-saved-date { font-size: .65rem; color: var(--tx3); display: flex; align-items: center; gap: 3px; }

/* ── List view ── */
.bm-grid.list-view { grid-template-columns: 1fr; gap: 10px; }
.bm-grid.list-view .bm-card { display: flex; flex-direction: row; border-radius: 12px; }
.bm-grid.list-view .bm-cover { aspect-ratio: unset; width: 80px; min-height: 116px; flex-shrink: 0; border-radius: 0; }
.bm-grid.list-view .bm-card-body { flex: 1; padding: 12px 14px; display: flex; flex-direction: column; justify-content: center; }
.bm-grid.list-view .bm-cover::before { display: none; }
.bm-grid.list-view .bm-title { white-space: normal; }
.bm-grid.list-view .bm-remove-btn { display: none !important; }
.bm-list-actions {
    display: none; margin-left: auto; padding: 0 14px;
    align-items: center;
}
.bm-grid.list-view .bm-list-actions { display: flex; }
.bm-remove-list {
    width: 32px; height: 32px; border-radius: 8px;
    border: 1.5px solid #FECACA; background: #FEF2F2;
    color: #ef4444; display: flex; align-items: center; justify-content: center;
    cursor: pointer; font-size: .85rem; transition: background .15s;
}
[data-theme="dark"] .bm-remove-list { background: rgba(252,165,165,.08); border-color: rgba(252,165,165,.2); color: #FCA5A5; }
.bm-remove-list:hover { background: #FEE2E2; }
[data-theme="dark"] .bm-remove-list:hover { background: rgba(252,165,165,.15); }

/* ── Empty State ── */
.bm-empty {
    text-align: center; padding: 64px 20px;
    max-width: 340px; margin: 0 auto;
    animation: bmUp .5s cubic-bezier(.22,1,.36,1) both;
}
.bm-empty-icon {
    width: 80px; height: 80px; border-radius: 22px;
    background: var(--surface); border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 20px; font-size: 32px;
    box-shadow: 0 4px 20px rgba(0,0,0,.05);
}
.bm-empty-title { font-family: 'Playfair Display', serif; font-size: 1.25rem; font-weight: 700; color: var(--tx); margin-bottom: 8px; }
.bm-empty-desc { font-size: .87rem; color: var(--tx3); line-height: 1.6; margin-bottom: 22px; }
.bm-empty-cta {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 11px 24px; border-radius: 100px;
    background: var(--primary); color: #fff;
    font-family: inherit; font-size: .875rem; font-weight: 600;
    text-decoration: none; transition: background .2s, transform .15s;
    box-shadow: 0 3px 12px var(--shadow);
}
[data-theme="dark"] .bm-empty-cta { color: var(--bg); }
.bm-empty-cta:hover { background: var(--primary-h); transform: translateY(-1px); }

/* ── Pagination ── */
.bm-pagination { display: flex; justify-content: center; margin-top: 36px; }
.bm-pagination nav { display: flex; gap: 6px; align-items: center; }
.bm-pagination .pagination { display: flex; gap: 6px; list-style: none; align-items: center; }
.bm-pagination .page-item .page-link {
    display: flex; align-items: center; justify-content: center;
    min-width: 36px; height: 36px; padding: 0 10px;
    border-radius: 8px; font-size: .85rem; font-weight: 500;
    color: var(--tx2); background: var(--surface);
    border: 1px solid var(--border); text-decoration: none; transition: all .18s;
}
.bm-pagination .page-item .page-link:hover { border-color: var(--primary); color: var(--primary); }
.bm-pagination .page-item.active .page-link { background: var(--primary); color: #fff; border-color: var(--primary); font-weight: 600; }
[data-theme="dark"] .bm-pagination .page-item.active .page-link { color: var(--bg); }
.bm-pagination .page-item.disabled .page-link { opacity: .4; cursor: not-allowed; }

/* ── Responsive ── */
@media(max-width: 768px) { .bm-grid { grid-template-columns: repeat(auto-fill, minmax(135px, 1fr)); gap: 12px; } }
@media(max-width: 480px) { .bm-grid { grid-template-columns: repeat(auto-fill, minmax(118px, 1fr)); gap: 10px; } .bm-hero { padding: 26px 0 30px; } }
</style>
@endpush

@section('content')
<div class="bm-page">

    {{-- ── Hero Banner ── --}}
    <div class="bm-hero">
        <div class="bm-hero-inner">
            <div>
                <div class="bm-eyebrow">
                    Perpustakaan Pribadi
                </div>
                <h1>Buku <em>Tersimpan</em></h1>
                <p class="bm-hero-desc">
                    Koleksi buku yang kamu simpan untuk dibaca kapan saja dan di mana saja.
                </p>
            </div>

            <div class="bm-count-chip">
                <i class="bi bi-bookmark-fill"></i>
                {{ $books->total() }} buku tersimpan
            </div>
        </div>
    </div>

    {{-- ── Body ── --}}
    <div class="bm-body">

        @if($books->count())

        {{-- Toolbar --}}
        <div class="bm-toolbar">
            <div class="bm-toolbar-left">
                ✦ Semua Bookmark
            </div>
            <div class="bm-toolbar-right">
                {{-- Sort --}}
                <form method="GET" action="{{ route('profile.bookmarks') }}">
                    <select name="sort" class="bm-sort" onchange="this.form.submit()">
                        <option value="newest"   {{ request('sort','newest')=='newest'   ? 'selected' : '' }}>Terbaru Disimpan</option>
                        <option value="oldest"   {{ request('sort')=='oldest'   ? 'selected' : '' }}>Terlama Disimpan</option>
                        <option value="title"    {{ request('sort')=='title'    ? 'selected' : '' }}>Judul A–Z</option>
                        <option value="popular"  {{ request('sort')=='popular'  ? 'selected' : '' }}>Terpopuler</option>
                    </select>
                </form>

                {{-- View toggle --}}
                <div class="bm-view-toggle">
                    <button class="bm-vbtn active" id="gridBtn" onclick="setView('grid')" title="Grid">
                        <i class="bi bi-grid-3x3-gap"></i>
                    </button>
                    <button class="bm-vbtn" id="listBtn" onclick="setView('list')" title="List">
                        <i class="bi bi-list-ul"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- Grid --}}
        <div class="bm-grid" id="bmGrid">
            @foreach($books as $bm)
            <div class="bm-card">
                {{-- Remove button (grid hover) --}}
                <form action="{{ route('bookmarks.toggle', $bm->book->id) }}" method="POST" style="display:contents">
                    @csrf
                    <button type="submit" class="bm-remove-btn" title="Hapus bookmark" onclick="event.preventDefault();event.stopPropagation();this.closest('form').submit()">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </form>

                <a href="{{ route('books.show', $bm->book->slug) }}" style="display:contents">
                    {{-- Cover --}}
                    <div class="bm-cover"
                         style="background:linear-gradient(145deg,{{ $bm->book->cover_color ?? '#2C5F2E' }},{{ $bm->book->cover_color_dark ?? '#1d4220' }})">
                        @if($bm->book->cover_image)
                        <img src="{{ Storage::url($bm->book->cover_image) }}" alt=""
                             style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover">
                        @endif
                        <div class="bm-icon-badge"><i class="bi bi-bookmark-fill"></i></div>
                        <div class="bm-cover-title">{{ Str::limit($bm->book->title, 22) }}</div>
                    </div>

                    {{-- Body --}}
                    <div class="bm-card-body">
                        <span class="bm-cat">{{ $bm->book->category->name ?? '—' }}</span>
                        <div class="bm-title">{{ $bm->book->title }}</div>
                        <div class="bm-author">{{ $bm->book->author }}</div>
                        <div class="bm-footer">
                            <div style="display:flex;align-items:center;gap:4px">
                                <span class="bm-stars">
                                    @for($i=1;$i<=5;$i++){{ $i<=round($bm->book->averageRating())?'★':'☆' }}@endfor
                                </span>
                                <span class="bm-rating-num">{{ number_format($bm->book->averageRating(),1) }}</span>
                            </div>
                            <span class="bm-saved-date">
                                <i class="bi bi-clock" style="font-size:9px"></i>
                                {{ $bm->created_at->diffForHumans(null, true) }}
                            </span>
                        </div>
                    </div>
                </a>

                {{-- Remove button (list view) --}}
                <div class="bm-list-actions">
                    <form action="{{ route('bookmarks.toggle', $bm->book->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="bm-remove-list" title="Hapus bookmark">
                            <i class="bi bi-bookmark-x"></i>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="bm-pagination">
            {{ $books->appends(request()->query())->links() }}
        </div>

        @else
        {{-- ── Empty State ── --}}
        <div class="bm-empty">
            <div class="bm-empty-icon">🔖</div>
            <div class="bm-empty-title">Belum ada buku tersimpan</div>
            <p class="bm-empty-desc">
                Kamu belum menyimpan buku apapun. Temukan buku favoritmu dan simpan untuk dibaca nanti.
            </p>
            <a href="{{ route('books.index') }}" class="bm-empty-cta">
                <i class="bi bi-search"></i>
                Jelajahi Koleksi Buku
            </a>
        </div>
        @endif

    </div>{{-- /bm-body --}}
</div>{{-- /bm-page --}}

<x-mobile-bottom-nav active="bookmarks" />
@endsection

@push('scripts')
<script>
// View toggle
function setView(v) {
    const grid  = document.getElementById('bmGrid');
    const gBtn  = document.getElementById('gridBtn');
    const lBtn  = document.getElementById('listBtn');
    if (!grid) return;

    if (v === 'list') {
        grid.classList.add('list-view');
        lBtn.classList.add('active');
        gBtn.classList.remove('active');
    } else {
        grid.classList.remove('list-view');
        gBtn.classList.add('active');
        lBtn.classList.remove('active');
    }
    localStorage.setItem('librova-bm-view', v);
}

// Restore last view preference
document.addEventListener('DOMContentLoaded', () => {
    const saved = localStorage.getItem('librova-bm-view');
    if (saved === 'list') setView('list');
});
</script>
@endpush