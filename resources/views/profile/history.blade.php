@extends('layouts.app')

@section('title', 'Riwayat Baca — Librova')

@push('styles')
<style>
/* ═══════════════════════════════════════════
   READING HISTORY PAGE
═══════════════════════════════════════════ */
.hist-page { padding: 0 0 100px; }

/* ── Hero ── */
.hist-hero {
    position: relative; overflow: hidden;
    padding: 38px 0 40px;
    border-bottom: 1px solid var(--border);
}
.hist-hero::before {
    content: ''; position: absolute; inset: 0;
    background:
        radial-gradient(ellipse 50% 80% at 90% 50%, rgba(44,95,46,.07) 0%, transparent 60%),
        radial-gradient(ellipse 30% 60% at 5%  60%, rgba(201,168,76,.05) 0%, transparent 55%);
    pointer-events: none;
}
[data-theme="dark"] .hist-hero::before {
    background:
        radial-gradient(ellipse 50% 80% at 90% 50%, rgba(74,222,128,.05) 0%, transparent 60%),
        radial-gradient(ellipse 30% 60% at 5%  60%, rgba(251,191,36,.04) 0%, transparent 55%);
}
.hist-hero::after {
    content: '📖';
    position: absolute; right: 24px; top: 50%; transform: translateY(-50%);
    font-size: 130px; opacity: .04; pointer-events: none; user-select: none; line-height: 1;
}
.hist-hero-inner {
    max-width: 860px; margin: 0 auto; padding: 0 24px;
    display: flex; align-items: flex-end; justify-content: space-between; gap: 20px; flex-wrap: wrap;
}
.hist-eyebrow {
    display: inline-flex; align-items: center; gap: 8px;
    font-size: .72rem; font-weight: 700; letter-spacing: .08em;
    text-transform: uppercase; color: var(--tx3); margin-bottom: 10px;
}
.hist-eyebrow-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--primary); animation: pulseDot 2.5s ease-in-out infinite; }
@keyframes pulseDot { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.4;transform:scale(.7)} }
.hist-hero h1 {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.7rem, 3.5vw, 2.4rem);
    font-weight: 700; line-height: 1.15; letter-spacing: -.025em; color: var(--tx); margin-bottom: 6px;
}
.hist-hero h1 em { font-style: italic; color: var(--primary); }
.hist-hero-desc { font-size: .88rem; color: var(--tx2); line-height: 1.65; }
.hist-count-chip {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 18px; border-radius: 100px;
    background: var(--surface); border: 1px solid var(--border);
    font-size: .82rem; font-weight: 600; color: var(--tx2);
    white-space: nowrap; flex-shrink: 0; align-self: flex-end;
}
.hist-count-chip i { color: var(--primary); }

/* ── Body ── */
.hist-body { max-width: 860px; margin: 0 auto; padding: 28px 24px; }

.hist-toolbar {
    display: flex; align-items: center; justify-content: space-between;
    gap: 10px; margin-bottom: 22px; flex-wrap: wrap;
}
.hist-toolbar-left {
    font-family: 'Playfair Display', serif;
    font-size: 1.1rem; font-weight: 700; color: var(--tx);
}
.hist-sort {
    padding: 8px 28px 8px 12px; border-radius: 9px;
    border: 1.5px solid var(--border); background: var(--surface);
    font-family: inherit; font-size: .8rem; color: var(--tx2);
    appearance: none; cursor: pointer;
    background-image: url("data:image/svg+xml,%3Csvg width='12' height='8' viewBox='0 0 12 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%239A9282' stroke-width='1.5' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 10px center;
    transition: border-color .2s;
}
.hist-sort:focus { outline: none; border-color: var(--primary); }

/* ── History Card ── */
.hist-list { display: flex; flex-direction: column; gap: 12px; }
.hist-card {
    display: flex; align-items: center; gap: 18px;
    background: var(--surface); border: 1px solid var(--border);
    border-radius: 16px; padding: 16px 18px;
    text-decoration: none; color: inherit;
    transition: transform .22s cubic-bezier(.34,1.56,.64,1), box-shadow .22s, border-color .2s;
    animation: histCardIn .5s cubic-bezier(.22,1,.36,1) both;
    position: relative; overflow: hidden;
}
.hist-card:nth-child(1){animation-delay:.04s}.hist-card:nth-child(2){animation-delay:.08s}
.hist-card:nth-child(3){animation-delay:.12s}.hist-card:nth-child(4){animation-delay:.16s}
.hist-card:nth-child(5){animation-delay:.20s}.hist-card:nth-child(6){animation-delay:.24s}
@keyframes histCardIn { from{opacity:0;transform:translateY(14px)} to{opacity:1;transform:translateY(0)} }
.hist-card:hover {
    transform: translateX(5px);
    box-shadow: 0 8px 32px var(--shadow);
    border-color: var(--primary);
}
.hist-card::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 2.5px;
    background: linear-gradient(to right, var(--primary), transparent);
    transform: scaleX(0); transform-origin: left;
    transition: transform .4s ease;
}
.hist-card:hover::before { transform: scaleX(1); }

/* Cover */
.hist-cover {
    width: 62px; height: 90px; border-radius: 9px; flex-shrink: 0;
    box-shadow: 3px 4px 14px rgba(0,0,0,.18);
    position: relative; overflow: hidden;
    background-size: cover; background-position: center;
}
.hist-cover::after {
    content: ''; position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,.4) 0%, transparent 55%);
}

/* Info */
.hist-info { flex: 1; min-width: 0; }
.hist-cat {
    font-size: .62rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em;
    color: var(--primary); background: rgba(44,95,46,.08);
    padding: 1px 7px; border-radius: 4px; display: inline-block; margin-bottom: 5px;
}
[data-theme="dark"] .hist-cat { background: rgba(74,222,128,.1); }
.hist-title {
    font-family: 'Playfair Display', serif;
    font-size: 1rem; font-weight: 700; color: var(--tx);
    line-height: 1.25; margin-bottom: 3px;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.hist-author { font-size: .78rem; color: var(--tx3); margin-bottom: 10px; }

/* Progress */
.hist-progress-row { display: flex; align-items: center; gap: 8px; margin-bottom: 8px; }
.hist-prog-bar {
    flex: 1; height: 5px; border-radius: 3px;
    background: var(--surface2); overflow: hidden;
}
.hist-prog-fill {
    height: 100%; border-radius: 3px;
    background: linear-gradient(to right, var(--primary), color-mix(in srgb, var(--primary) 70%, var(--gold)));
    transition: width .8s cubic-bezier(.22,1,.36,1);
}
.hist-prog-label {
    font-size: .7rem; color: var(--tx3); font-weight: 600; white-space: nowrap; min-width: 44px; text-align: right;
}

/* Meta chips */
.hist-meta { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
.hist-meta-item {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: .72rem; color: var(--tx3); font-weight: 500;
}
.hist-meta-item i { font-size: .72rem; }

/* Continue button */
.hist-continue {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 18px; border-radius: 100px;
    background: var(--primary); color: #fff;
    font-family: inherit; font-size: .8rem; font-weight: 600;
    text-decoration: none; white-space: nowrap; flex-shrink: 0;
    border: none; cursor: pointer;
    transition: background .2s, transform .15s, box-shadow .2s;
    box-shadow: 0 2px 10px var(--shadow);
}
[data-theme="dark"] .hist-continue { color: var(--bg); }
.hist-continue:hover { background: var(--primary-h); transform: translateY(-1px); box-shadow: 0 4px 16px var(--shadow); }

/* Completed badge */
.hist-done-badge {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: .72rem; font-weight: 700; padding: 6px 12px; border-radius: 100px;
    background: rgba(34,197,94,.1); color: #16a34a; flex-shrink: 0;
}
[data-theme="dark"] .hist-done-badge { background: rgba(74,222,128,.12); color: #4ADE80; }

/* ── Empty State ── */
.hist-empty {
    text-align: center; padding: 64px 20px;
    max-width: 340px; margin: 0 auto;
    animation: histCardIn .5s cubic-bezier(.22,1,.36,1) both;
}
.hist-empty-icon {
    width: 80px; height: 80px; border-radius: 22px;
    background: var(--surface); border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 18px; font-size: 32px;
    box-shadow: 0 4px 20px rgba(0,0,0,.05);
}
.hist-empty-title { font-family: 'Playfair Display', serif; font-size: 1.25rem; font-weight: 700; color: var(--tx); margin-bottom: 8px; }
.hist-empty-desc { font-size: .87rem; color: var(--tx3); line-height: 1.6; margin-bottom: 22px; }
.hist-empty-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 11px 24px; border-radius: 100px;
    background: var(--primary); color: #fff;
    font-family: inherit; font-size: .875rem; font-weight: 600;
    text-decoration: none; transition: background .2s, transform .15s;
    box-shadow: 0 3px 12px var(--shadow);
}
[data-theme="dark"] .hist-empty-btn { color: var(--bg); }
.hist-empty-btn:hover { background: var(--primary-h); transform: translateY(-1px); }

/* ── Pagination ── */
.hist-pagination { display: flex; justify-content: center; margin-top: 32px; }
.hist-pagination nav { display: flex; gap: 6px; align-items: center; }
.hist-pagination .pagination { display: flex; gap: 6px; list-style: none; align-items: center; }
.hist-pagination .page-item .page-link {
    display: flex; align-items: center; justify-content: center;
    min-width: 36px; height: 36px; padding: 0 10px; border-radius: 8px;
    font-size: .85rem; font-weight: 500; color: var(--tx2);
    background: var(--surface); border: 1px solid var(--border);
    text-decoration: none; transition: all .18s;
}
.hist-pagination .page-item .page-link:hover { border-color: var(--primary); color: var(--primary); }
.hist-pagination .page-item.active .page-link { background: var(--primary); color: #fff; border-color: var(--primary); font-weight: 600; }
[data-theme="dark"] .hist-pagination .page-item.active .page-link { color: var(--bg); }
.hist-pagination .page-item.disabled .page-link { opacity: .4; cursor: not-allowed; }

/* Responsive */
@media(max-width: 600px) {
    .hist-card { flex-wrap: wrap; gap: 12px; }
    .hist-continue, .hist-done-badge { width: 100%; justify-content: center; }
    .hist-hero { padding: 26px 0 30px; }
}
</style>
@endpush

@section('content')
<div class="hist-page">

    {{-- ── Hero Banner ── --}}
    <div class="hist-hero">
        <div class="hist-hero-inner">
            <div>
                <div class="hist-eyebrow">
                    <span class="hist-eyebrow-dot"></span>
                    Aktivitas Membaca
                </div>
                <h1>Riwayat <em>Bacaan</em></h1>
                <p class="hist-hero-desc">
                    Pantau perjalanan membacamu dan lanjutkan dari halaman terakhir.
                </p>
            </div>
            @if($history->count())
            <div class="hist-count-chip">
                <i class="bi bi-clock-history"></i>
                {{ $history->total() }} buku unik
            </div>
            @endif
        </div>
    </div>

    {{-- ── Body ── --}}
    <div class="hist-body">

        @if($history->count())

        {{-- Toolbar --}}
        <div class="hist-toolbar">
            <div class="hist-toolbar-left">✦ Semua Riwayat</div>
            <form method="GET" action="{{ route('profile.history') }}">
                <select name="sort" class="hist-sort" onchange="this.form.submit()">
                    <option value="newest" {{ request('sort','newest')=='newest' ? 'selected':'' }}>Terbaru Dilihat</option>
                    <option value="oldest" {{ request('sort')=='oldest' ? 'selected':'' }}>Terlama Dilihat</option>
                    <option value="title"  {{ request('sort')=='title'  ? 'selected':'' }}>Judul A–Z</option>
                </select>
            </form>
        </div>

        {{-- List --}}
        <div class="hist-list">
            @foreach($history as $item)
            @php
                $book       = $item->book;
                $totalPages = $book->total_pages ?? 0;
            @endphp
            <div class="hist-card"
                 data-book-id="{{ $book->id }}"
                 data-book-slug="{{ $book->slug }}"
                 data-total-pages="{{ $totalPages }}">

                {{-- Cover --}}
                <div class="hist-cover"
                     style="@if($book->cover_image) background-image:url('{{ Storage::url($book->cover_image) }}'); @else background:linear-gradient(145deg,{{ $book->cover_color ?? '#2C5F2E' }},{{ $book->cover_color_dark ?? '#1d4220' }}); @endif">
                </div>

                {{-- Info --}}
                <div class="hist-info">
                    @if($book->category)
                    <span class="hist-cat">{{ $book->category->name }}</span>
                    @endif
                    <div class="hist-title">{{ $book->title }}</div>
                    <div class="hist-author">{{ $book->author }}</div>

                    {{-- Progress bar --}}
                    <div class="hist-progress-row">
                        <div class="hist-prog-bar">
                            <div class="hist-prog-fill" id="prog-fill-{{ $book->id }}" style="width:0%"></div>
                        </div>
                        <span class="hist-prog-label" id="prog-label-{{ $book->id }}">—</span>
                    </div>

                    {{-- Meta --}}
                    <div class="hist-meta">
                        <span class="hist-meta-item">
                            <i class="bi bi-clock"></i>
                            {{ $item->viewed_at->diffForHumans() }}
                        </span>
                        @if($totalPages > 0)
                        <span class="hist-meta-item">
                            <i class="bi bi-book"></i>
                            {{ number_format($totalPages) }} halaman
                        </span>
                        @endif
                        <span class="hist-meta-item">
                            <i class="bi bi-eye"></i>
                            {{ number_format($book->view_count ?? 0) }} dibaca
                        </span>
                    </div>
                </div>

                {{-- Lanjutkan --}}
                <a href="{{ route('books.read', $book->slug) }}"
                   class="hist-continue" id="hist-btn-{{ $book->id }}">
                    <i class="bi bi-play-fill"></i>
                    Lanjutkan
                </a>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="hist-pagination">
            {{ $history->appends(request()->query())->links() }}
        </div>

        @else

        {{-- Empty State --}}
        <div class="hist-empty">
            <div class="hist-empty-icon">📖</div>
            <div class="hist-empty-title">Belum ada riwayat baca</div>
            <p class="hist-empty-desc">
                Mulailah membaca buku favoritmu dan riwayat bacaan akan muncul di sini.
            </p>
            <a href="{{ route('books.index') }}" class="hist-empty-btn">
                <i class="bi bi-search"></i>
                Jelajahi Buku
            </a>
        </div>

        @endif

    </div>{{-- /hist-body --}}
</div>{{-- /hist-page --}}

<x-mobile-bottom-nav active="profile" />
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.hist-card').forEach(card => {
        const bookId     = card.dataset.bookId;
        const totalPages = parseInt(card.dataset.totalPages) || 0;
        const lastPage   = parseInt(localStorage.getItem('librova_last_page_' + bookId)) || 0;

        const fill  = document.getElementById('prog-fill-'  + bookId);
        const label = document.getElementById('prog-label-' + bookId);
        const btn   = document.getElementById('hist-btn-'   + bookId);

        if (lastPage > 0) {
            if (totalPages > 0) {
                const pct = Math.min(Math.round((lastPage / totalPages) * 100), 100);
                if (fill) fill.style.width = pct + '%';
                if (label) {
                    label.textContent = pct >= 98 ? '100% ✅' : pct + '%';
                    if (pct >= 98) label.style.color = '#22c55e';
                }
                if (pct >= 98 && btn) {
                    btn.innerHTML = '<i class="bi bi-arrow-repeat"></i> Baca Lagi';
                    btn.style.background = 'var(--surface2)';
                    btn.style.color = 'var(--tx2)';
                    btn.style.border = '1.5px solid var(--border)';
                    btn.style.boxShadow = 'none';
                }
            } else {
                if (fill) fill.style.width = '25%';
                if (label) label.textContent = 'Hlm ' + lastPage;
            }
            if (btn && lastPage > 1) {
                const url = new URL(btn.href);
                url.searchParams.set('page', lastPage);
                btn.href = url.toString();
            }
        } else {
            if (fill) fill.style.width = '0%';
            if (label) label.textContent = 'Belum dibuka';
        }
    });
});
</script>
@endpush