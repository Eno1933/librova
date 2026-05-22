@extends('layouts.app')

@section('title', $book->title . ' — Librova')

@push('styles')
<style>
/* ═══════════════════════════════════════════
   BOOK DETAIL PAGE
═══════════════════════════════════════════ */

.bk-page { max-width: 1100px; margin: 0 auto; padding: 32px 20px 60px; }

/* ── Hero ── */
.bk-hero {
    display: grid;
    grid-template-columns: 200px 1fr;
    gap: 36px;
    margin-bottom: 32px;
    align-items: start;
}

/* Cover */
.bk-cover-wrap { position: relative; }
.bk-cover {
    width: 100%;
    aspect-ratio: 2/3;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 12px 40px rgba(0,0,0,.22);
    position: relative;
}
.bk-cover::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,.65) 0%, transparent 55%);
    z-index: 1;
}
.bk-cover-img {
    width: 100%; height: 100%;
    object-fit: cover; display: block;
}
/* Featured ribbon */
.bk-featured-ribbon {
    position: absolute;
    top: 14px; left: -4px; z-index: 2;
    background: var(--gold);
    color: #000;
    font-size: .62rem; font-weight: 700;
    padding: 4px 12px 4px 10px;
    border-radius: 0 4px 4px 0;
    box-shadow: 2px 2px 6px rgba(0,0,0,.15);
    display: flex; align-items: center; gap: 4px;
}
.bk-featured-ribbon::before {
    content: '';
    position: absolute;
    bottom: -4px; left: 0;
    border: 4px solid transparent;
    border-right-color: #7B5E00;
    border-top-color: #7B5E00;
}

/* Info column */
.bk-info { min-width: 0; }

.bk-cat-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(44,95,46,.08); color: var(--primary);
    padding: 5px 13px; border-radius: 100px;
    font-size: .72rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .06em;
    text-decoration: none; margin-bottom: 14px;
    border: 1px solid rgba(44,95,46,.15);
    transition: background .2s;
}
[data-theme="dark"] .bk-cat-badge { background: rgba(74,222,128,.09); border-color: rgba(74,222,128,.2); }
.bk-cat-badge:hover { background: rgba(44,95,46,.14); }

.bk-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.6rem, 3vw, 2.2rem);
    font-weight: 700; line-height: 1.18;
    letter-spacing: -.025em; color: var(--tx);
    margin-bottom: 8px;
}
.bk-author {
    font-size: .95rem; color: var(--tx2);
    margin-bottom: 18px;
}
.bk-author strong { color: var(--tx); font-weight: 600; }

/* Quick stats inline */
.bk-quick-stats {
    display: flex; align-items: center; gap: 16px;
    flex-wrap: wrap; margin-bottom: 18px;
}
.bk-qs-item {
    display: flex; align-items: center; gap: 5px;
    font-size: .82rem; color: var(--tx2); font-weight: 500;
}
.bk-qs-item i { font-size: .9rem; color: var(--tx3); }
.bk-qs-sep { width: 1px; height: 14px; background: var(--border); }

/* Meta list */
.bk-meta {
    display: flex; flex-wrap: wrap; gap: 8px;
    margin-bottom: 22px;
}
.bk-meta-chip {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 5px 12px;
    border-radius: 8px;
    background: var(--surface2); border: 1px solid var(--border);
    font-size: .78rem; color: var(--tx2);
}
.bk-meta-chip i { font-size: .85rem; color: var(--tx3); }

/* Actions */
.bk-actions { display: flex; gap: 10px; flex-wrap: wrap; }
.btn-read {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 11px 24px; border-radius: 100px;
    background: var(--primary); color: #fff;
    font-family: inherit; font-size: .875rem; font-weight: 600;
    text-decoration: none; border: none; cursor: pointer;
    transition: background .2s, transform .15s, box-shadow .2s;
    box-shadow: 0 3px 12px var(--shadow);
}
[data-theme="dark"] .btn-read { color: var(--bg); }
.btn-read:hover { background: var(--primary-h); transform: translateY(-1px); box-shadow: 0 6px 20px var(--shadow); }
.btn-bm {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 11px 22px; border-radius: 100px;
    background: var(--surface); color: var(--tx2);
    border: 1.5px solid var(--border);
    font-family: inherit; font-size: .875rem; font-weight: 500;
    cursor: pointer; transition: all .15s; text-decoration: none;
}
.btn-bm:hover { border-color: var(--primary); color: var(--primary); background: rgba(44,95,46,.04); }
.btn-bm.bookmarked { border-color: var(--primary); color: var(--primary); background: rgba(44,95,46,.07); font-weight: 600; }
[data-theme="dark"] .btn-bm.bookmarked { background: rgba(74,222,128,.08); }

/* ── Shared Card ── */
.detail-card {
    background: var(--surface); border: 1px solid var(--border);
    border-radius: 16px; overflow: hidden;
    margin-bottom: 20px;
    animation: dcFadeUp .5s cubic-bezier(.22,1,.36,1) both;
}
.detail-card:nth-child(1){ animation-delay: .05s }
.detail-card:nth-child(2){ animation-delay: .10s }
.detail-card:nth-child(3){ animation-delay: .15s }
.detail-card:nth-child(4){ animation-delay: .20s }
.detail-card:nth-child(5){ animation-delay: .25s }
@keyframes dcFadeUp { from{opacity:0;transform:translateY(14px)} to{opacity:1;transform:translateY(0)} }

.card-hd {
    display: flex; align-items: center; justify-content: space-between;
    padding: 18px 22px;
    border-bottom: 1px solid var(--border);
}
.card-hd-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.05rem; font-weight: 700; color: var(--tx);
    display: flex; align-items: center; gap: 9px;
}
.card-hd-icon {
    width: 32px; height: 32px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center; font-size: 15px;
}
.card-body { padding: 22px; }

/* ── Rating Card ── */
.rating-summary {
    display: flex; gap: 28px; align-items: center; flex-wrap: wrap;
    margin-bottom: 22px;
}
.rating-big-num {
    font-family: 'Playfair Display', serif;
    font-size: 3.8rem; font-weight: 700; color: var(--tx);
    line-height: 1;
}
.rating-stars-lg {
    color: var(--gold); font-size: 1.5rem; letter-spacing: 2px;
    display: block; margin: 6px 0;
}
.rating-count { font-size: .8rem; color: var(--tx3); font-weight: 500; }

/* Distribution bars */
.rating-dist { flex: 1; min-width: 200px; max-width: 340px; }
.dist-row {
    display: flex; align-items: center; gap: 8px;
    margin-bottom: 7px;
}
.dist-star { font-size: .78rem; color: var(--tx2); font-weight: 600; min-width: 30px; }
.dist-bar {
    flex: 1; height: 7px; border-radius: 4px;
    background: var(--surface2); overflow: hidden;
}
.dist-fill {
    height: 100%; border-radius: 4px;
    background: var(--gold);
    transition: width 1s cubic-bezier(.22,1,.36,1);
}
.dist-count { font-size: .72rem; color: var(--tx3); min-width: 28px; text-align: right; }

/* Interactive star input */
.star-input-wrap {
    padding-top: 18px; border-top: 1px solid var(--border);
}
.star-input-label {
    font-size: .78rem; font-weight: 600; color: var(--tx2);
    text-transform: uppercase; letter-spacing: .06em; margin-bottom: 10px;
}
.star-input {
    display: flex; align-items: center; gap: 4px;
}
.star-btn {
    background: none; border: none; cursor: pointer;
    font-size: 1.85rem; color: var(--border2);
    transition: color .12s, transform .15s cubic-bezier(.34,1.56,.64,1);
    padding: 0 2px; line-height: 1;
}
.star-btn:hover { transform: scale(1.25); }
.star-btn.lit { color: var(--gold); }
.star-rating-status {
    font-size: .82rem; color: var(--tx3); margin-left: 10px;
    font-weight: 500;
}

/* ── Description ── */
.desc-text {
    font-size: .92rem; color: var(--tx2); line-height: 1.75;
}
.desc-toggle {
    background: none; border: none; cursor: pointer;
    color: var(--primary); font-family: inherit;
    font-size: .82rem; font-weight: 600; margin-top: 10px;
    display: inline-flex; align-items: center; gap: 5px; padding: 0;
}

/* ── Reviews ── */
.review-item {
    padding: 16px 0;
    border-bottom: 1px solid var(--border);
}
.review-item:first-child { padding-top: 0; }
.review-item:last-child { border-bottom: none; padding-bottom: 0; }
.review-head { display: flex; align-items: center; gap: 10px; margin-bottom: 8px; }
.review-av {
    width: 36px; height: 36px; border-radius: 50%;
    background: rgba(44,95,46,.1);
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: .85rem; color: var(--primary);
    flex-shrink: 0;
}
[data-theme="dark"] .review-av { background: rgba(74,222,128,.1); }
.review-name { font-weight: 600; color: var(--tx); font-size: .88rem; }
.review-time { font-size: .73rem; color: var(--tx3); margin-left: auto; }
.review-body { font-size: .88rem; color: var(--tx2); line-height: 1.6; }
.review-empty { text-align: center; padding: 32px 0; color: var(--tx3); font-size: .88rem; }

/* Review form */
.review-form-wrap {
    margin-top: 18px; padding-top: 18px;
    border-top: 1px solid var(--border);
}
.review-form-label {
    font-size: .78rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .06em;
    color: var(--tx3); margin-bottom: 8px;
}
.review-textarea {
    width: 100%; padding: 12px 16px;
    border-radius: 10px; border: 1.5px solid var(--border);
    background: var(--bg); color: var(--tx);
    font-family: inherit; font-size: .88rem;
    resize: vertical; min-height: 90px;
    transition: border-color .2s, box-shadow .2s;
}
.review-textarea:focus {
    outline: none; border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(44,95,46,.09);
}
[data-theme="dark"] .review-textarea:focus { box-shadow: 0 0 0 3px rgba(74,222,128,.09); }
.review-textarea::placeholder { color: var(--tx3); }
.review-submit {
    margin-top: 10px; padding: 10px 22px;
    border-radius: 100px; background: var(--primary); color: #fff;
    font-family: inherit; font-size: .85rem; font-weight: 600;
    border: none; cursor: pointer;
    display: inline-flex; align-items: center; gap: 7px;
    transition: background .2s, transform .15s;
}
[data-theme="dark"] .review-submit { color: var(--bg); }
.review-submit:hover { background: var(--primary-h); transform: translateY(-1px); }

/* ── Related ── */
.related-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(148px, 1fr));
    gap: 14px;
}
.rel-card {
    background: var(--surface); border: 1px solid var(--border);
    border-radius: 12px; overflow: hidden;
    text-decoration: none; display: block;
    transition: transform .28s cubic-bezier(.34,1.56,.64,1), box-shadow .25s, border-color .2s;
}
.rel-card:hover { transform: translateY(-6px); box-shadow: 0 12px 36px var(--shadow); border-color: var(--border2); }
.rel-cover {
    aspect-ratio: 2/3; position: relative; overflow: hidden;
    display: flex; align-items: flex-end; padding: 12px;
}
.rel-cover::before {
    content: ''; position: absolute; top: 0; left: -60%; width: 40%; height: 100%;
    background: linear-gradient(105deg, transparent 0%, rgba(255,255,255,.07) 50%, transparent 100%);
    transition: left .6s ease;
}
.rel-card:hover .rel-cover::before { left: 130%; }
.rel-cover::after {
    content: ''; position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,.6) 0%, transparent 55%);
}
.rel-cover-title {
    position: relative; z-index: 1;
    font-family: 'Playfair Display', serif;
    color: rgba(255,255,255,.92); font-size: .68rem; font-weight: 600; line-height: 1.3;
}
.rel-body { padding: 10px 12px 12px; }
.rel-cat {
    font-size: .6rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em;
    color: var(--primary); background: rgba(44,95,46,.08);
    padding: 2px 7px; border-radius: 4px; display: inline-block; margin-bottom: 5px;
}
[data-theme="dark"] .rel-cat { background: rgba(74,222,128,.1); }
.rel-title { font-size: .82rem; font-weight: 600; color: var(--tx); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 2px; }
.rel-author { font-size: .72rem; color: var(--tx3); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

/* ── Responsive ── */
@media(max-width: 700px) {
    .bk-hero { grid-template-columns: 140px 1fr; gap: 20px; }
    .bk-title { font-size: 1.5rem; }
}
@media(max-width: 500px) {
    .bk-hero { grid-template-columns: 1fr; }
    .bk-cover-wrap { max-width: 180px; }
    .rating-summary { flex-direction: column; align-items: flex-start; gap: 16px; }
}
</style>
@endpush

@section('content')
<div class="bk-page">

{{-- ── HERO ── --}}
<div class="bk-hero">

    {{-- Cover --}}
    <div class="bk-cover-wrap">
        <div class="bk-cover"
             style="background: linear-gradient(145deg, {{ $book->cover_color ?? '#2C5F2E' }}, {{ $book->cover_color_dark ?? '#1d4220' }})">
            @if($book->cover_image)
            <img src="{{ Storage::url($book->cover_image) }}" alt="{{ $book->title }}" class="bk-cover-img">
            @endif
            @if($book->is_featured)
            <div class="bk-featured-ribbon"><i class="bi bi-star-fill"></i> Unggulan</div>
            @endif
        </div>
    </div>

    {{-- Info --}}
    <div class="bk-info">
        {{-- Category --}}
        <a href="{{ route('categories.show', $book->category->slug ?? '#') }}" class="bk-cat-badge">
            <i class="bi bi-folder2"></i>
            {{ $book->category->name ?? 'Tanpa Kategori' }}
        </a>

        <h1 class="bk-title">{{ $book->title }}</h1>
        <p class="bk-author">oleh <strong>{{ $book->author }}</strong></p>

        {{-- Quick stats --}}
        <div class="bk-quick-stats">
            <div class="bk-qs-item">
                <i class="bi bi-star-fill" style="color:var(--gold)"></i>
                <strong style="color:var(--tx)">{{ number_format($book->averageRating(), 1) }}</strong>
                <span style="color:var(--tx3)">({{ $book->ratingsCount() }} rating)</span>
            </div>
            <div class="bk-qs-sep"></div>
            <div class="bk-qs-item">
                <i class="bi bi-eye"></i>
                {{ number_format($book->view_count ?? 0) }} dibaca
            </div>
            <div class="bk-qs-sep"></div>
            <div class="bk-qs-item">
                <i class="bi bi-chat-dots"></i>
                {{ $book->approvedReviews->count() }} ulasan
            </div>
        </div>

        {{-- Meta chips --}}
        <div class="bk-meta">
            @if($book->isbn)
            <span class="bk-meta-chip"><i class="bi bi-upc"></i> {{ $book->isbn }}</span>
            @endif
            <span class="bk-meta-chip">
                <i class="bi bi-translate"></i>
                {{ $book->language ?? 'Indonesia' }}
            </span>
            @if($book->published_year)
            <span class="bk-meta-chip"><i class="bi bi-calendar3"></i> {{ $book->published_year }}</span>
            @endif
            @if($book->total_pages)
            <span class="bk-meta-chip"><i class="bi bi-book"></i> {{ number_format($book->total_pages) }} hal.</span>
            @endif
            @if($book->is_downloadable)
            <span class="bk-meta-chip" style="color:var(--primary);border-color:rgba(44,95,46,.2);background:rgba(44,95,46,.06)">
                <i class="bi bi-download"></i> Bisa Diunduh
            </span>
            @endif
        </div>

        {{-- Actions --}}
        <div class="bk-actions">
            @auth
            <a href="{{ route('books.read', $book->slug) }}" class="btn-read">
                <i class="bi bi-book-open"></i> Baca Sekarang
            </a>
            @else
            <a href="{{ route('login') }}" class="btn-read">
                <i class="bi bi-book-open"></i> Masuk untuk Membaca
            </a>
            @endauth

            @auth
            @php /* Hitung isBookmarked sekali */ $isBookmarked = auth()->user()->bookmarks()->where('book_id', $book->id)->exists(); @endphp
            <form action="{{ route('bookmarks.toggle', $book->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn-bm {{ $isBookmarked ? 'bookmarked' : '' }}">
                    <i class="bi {{ $isBookmarked ? 'bi-bookmark-fill' : 'bi-bookmark' }}"></i>
                    {{ $isBookmarked ? 'Tersimpan' : 'Bookmark' }}
                </button>
            </form>
            @endauth

            @if($book->is_downloadable && auth()->check())
            <a href="{{ route('books.download', $book->slug) }}" class="btn-bm">
                <i class="bi bi-cloud-download"></i> Unduh
            </a>
            @endif
        </div>
    </div>
</div>{{-- /bk-hero --}}


{{-- ── RATING CARD ── --}}
<div class="detail-card">
    <div class="card-hd">
        <div class="card-hd-title">
            <div class="card-hd-icon" style="background:rgba(201,168,76,.12)">
                <i class="bi bi-star-fill" style="color:var(--gold)"></i>
            </div>
            Rating & Penilaian
        </div>
        <span style="font-size:.75rem;color:var(--tx3)">Total {{ $book->ratingsCount() }} penilai</span>
    </div>
    <div class="card-body">

        {{-- Summary --}}
        <div class="rating-summary">
            <div style="text-align:center">
                <div class="rating-big-num">{{ number_format($book->averageRating(), 1) }}</div>
                <div class="rating-stars-lg">
                    @for($i = 1; $i <= 5; $i++)
                        {{ $i <= round($book->averageRating()) ? '★' : '☆' }}
                    @endfor
                </div>
                <div class="rating-count">dari 5 bintang</div>
            </div>

            {{-- Distribution bars --}}
            <div class="rating-dist">
                @php
                /* $ratingDistribution sudah dijamin punya key 1-5 dari controller */
                $maxRatingCount = max($ratingDistribution);
                $maxRatingCount = max($maxRatingCount, 1); /* cegah division by zero */
                @endphp
                @foreach(range(5, 1) as $star)
                @php $count = $ratingDistribution[$star]; @endphp
                <div class="dist-row">
                    <span class="dist-star">{{ $star }}<i class="bi bi-star-fill" style="color:var(--gold);font-size:.6rem;margin-left:2px"></i></span>
                    <div class="dist-bar">
                        <div class="dist-fill" style="width:{{ ($count / $maxRatingCount) * 100 }}%"></div>
                    </div>
                    <span class="dist-count">{{ $count }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Interactive star input --}}
        @auth
        <div class="star-input-wrap"
             x-data="{
                 current: {{ $userRating->score ?? 0 }},
                 hover:   0,
                 saving:  false,
                 status:  '{{ $userRating ? 'Kamu memberi ' . $userRating->score . ' bintang' : 'Belum dinilai' }}',
                 labels:  ['', 'Buruk', 'Kurang', 'Cukup', 'Bagus', 'Luar Biasa'],

                 async rate(score) {
                     if (this.saving) return;
                     this.saving  = true;
                     this.current = score;
                     this.status  = 'Menyimpan…';

                     try {
                         const res = await fetch('{{ route('books.rate', $book->id) }}', {
                             method: 'POST',
                             headers: {
                                 'Content-Type': 'application/json',
                                 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                             },
                             body: JSON.stringify({ score }),
                         });

                         if (!res.ok) throw new Error(res.statusText);
                         const data = await res.json();

                         this.status = 'Tersimpan ✓ · Rata-rata: ' + parseFloat(data.average).toFixed(1) + ' (' + data.count + ' penilai)';
                     } catch (e) {
                         this.status = 'Gagal menyimpan. Coba lagi.';
                         console.error(e);
                     } finally {
                         this.saving = false;
                     }
                 }
             }">
            <div class="star-input-label">Beri Penilaianmu</div>
            <div class="star-input">
                <template x-for="s in [1,2,3,4,5]" :key="s">
                    <button class="star-btn"
                            :class="{ lit: (hover || current) >= s }"
                            @click="rate(s)"
                            @mouseenter="hover = s"
                            @mouseleave="hover = 0"
                            :title="labels[s]"
                            :disabled="saving">
                        <i class="bi" :class="(hover || current) >= s ? 'bi-star-fill' : 'bi-star'"></i>
                    </button>
                </template>
                <span class="star-rating-status" x-text="hover ? labels[hover] : status"></span>
            </div>
        </div>
        @else
        <div class="star-input-wrap">
            <p style="font-size:.85rem;color:var(--tx3)">
                <a href="{{ route('login') }}" style="color:var(--primary);font-weight:600">Masuk</a> untuk memberi rating.
            </p>
        </div>
        @endauth

    </div>
</div>{{-- /rating card --}}


{{-- ── DESCRIPTION ── --}}
@if($book->description)
<div class="detail-card">
    <div class="card-hd">
        <div class="card-hd-title">
            <div class="card-hd-icon" style="background:rgba(99,102,241,.1)">
                <i class="bi bi-info-circle" style="color:#6366f1"></i>
            </div>
            Deskripsi Buku
        </div>
    </div>
    <div class="card-body">
        <div class="desc-text" id="descText"
             style="display:-webkit-box;-webkit-line-clamp:5;-webkit-box-orient:vertical;overflow:hidden"
             x-data="{ expanded: false }"
             :style="expanded ? 'display:block' : ''">
            {!! nl2br(e($book->description)) !!}
        </div>
        <button class="desc-toggle" x-data="{ expanded: false }"
                @click="expanded = !expanded; document.getElementById('descText').style.display = expanded ? 'block' : '-webkit-box'">
            <span x-text="expanded ? 'Tampilkan lebih sedikit' : 'Baca selengkapnya'"></span>
            <i class="bi" :class="expanded ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
        </button>
    </div>
</div>
@endif


{{-- ── REVIEWS ── --}}
<div class="detail-card">
    <div class="card-hd">
        <div class="card-hd-title">
            <div class="card-hd-icon" style="background:rgba(44,95,46,.1)">
                <i class="bi bi-chat-left-text" style="color:var(--primary)"></i>
            </div>
            Ulasan Pembaca
        </div>
        <span style="font-size:.75rem;color:var(--tx3)">{{ $book->approvedReviews->count() }} ulasan</span>
    </div>
    <div class="card-body">

        @forelse($book->approvedReviews as $review)
        <div class="review-item">
            <div class="review-head">
                <div class="review-av">{{ strtoupper(substr($review->user->name, 0, 1)) }}</div>
                <div>
                    <div class="review-name">{{ $review->user->name }}</div>
                </div>
                <span class="review-time">
                    <i class="bi bi-clock" style="font-size:10px"></i>
                    {{ $review->created_at->diffForHumans() }}
                </span>
            </div>
            <p class="review-body">{{ $review->content }}</p>
        </div>
        @empty
        <div class="review-empty">
            <div style="font-size:2rem;margin-bottom:8px">✍️</div>
            <div style="font-weight:600;color:var(--tx2);margin-bottom:4px">Belum ada ulasan</div>
            <div>Jadilah yang pertama mengulas buku ini!</div>
        </div>
        @endforelse

        {{-- Review form --}}
        @auth
        <div class="review-form-wrap">
            <div class="review-form-label">Tulis Ulasanmu</div>
            <form action="{{ route('reviews.store', $book) }}" method="POST">
                @csrf
                <textarea name="content" class="review-textarea"
                          placeholder="Bagikan pengalamanmu membaca buku ini…" required
                          maxlength="1000"></textarea>
                <button type="submit" class="review-submit">
                    <i class="bi bi-send"></i> Kirim Ulasan
                </button>
            </form>
        </div>
        @else
        <div style="margin-top:14px;padding-top:14px;border-top:1px solid var(--border)">
            <p style="font-size:.85rem;color:var(--tx3)">
                <a href="{{ route('login') }}" style="color:var(--primary);font-weight:600">Masuk</a>
                untuk menulis ulasan.
            </p>
        </div>
        @endauth

    </div>
</div>{{-- /reviews --}}


{{-- ── RELATED BOOKS ── --}}
@if($relatedBooks->count())
<div class="detail-card">
    <div class="card-hd">
        <div class="card-hd-title">
            <div class="card-hd-icon" style="background:rgba(245,158,11,.1)">
                <i class="bi bi-bookshelf" style="color:#f59e0b"></i>
            </div>
            Buku Terkait
        </div>
        <a href="{{ route('categories.show', $book->category->slug ?? '#') }}"
           style="font-size:.76rem;font-weight:600;color:var(--primary);display:flex;align-items:center;gap:4px;text-decoration:none">
            Lihat Semua <i class="bi bi-arrow-right" style="font-size:11px"></i>
        </a>
    </div>
    <div class="card-body" style="padding-top:16px">
        <div class="related-grid">
            @foreach($relatedBooks as $rel)
            <a href="{{ route('books.show', $rel->slug) }}" class="rel-card">
                <div class="rel-cover"
                     style="background: linear-gradient(145deg, {{ $rel->cover_color ?? '#2C5F2E' }}, {{ $rel->cover_color_dark ?? '#1d4220' }})">
                    @if($rel->cover_image)
                    <img src="{{ Storage::url($rel->cover_image) }}" alt=""
                         style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover">
                    @endif
                    <div class="rel-cover-title">{{ Str::limit($rel->title, 22) }}</div>
                </div>
                <div class="rel-body">
                    <span class="rel-cat">{{ $rel->category->name ?? '—' }}</span>
                    <div class="rel-title">{{ $rel->title }}</div>
                    <div class="rel-author">{{ $rel->author }}</div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endif

</div>{{-- /bk-page --}}

<x-mobile-bottom-nav active="books" />
@endsection