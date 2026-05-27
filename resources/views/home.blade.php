@extends('layouts.app')

@section('title', 'Home — Librova')

@push('styles')
<style>
    /* ─── ADDITIONAL HOMEPAGE STYLES (merging referensi) ─── */
    .hp-section { padding: 72px 0; }
    .hp-section-alt { background: var(--surface2); border-top: 1px solid var(--border); border-bottom: 1px solid var(--border); }
    .hp-container { max-width: 1200px; margin: 0 auto; padding: 0 24px; }
    .hp-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 30px; }
    .hp-title { font-family: 'Playfair Display', serif; font-size: 1.7rem; font-weight: 700; letter-spacing: -0.02em; color: var(--tx); display: flex; align-items: center; gap: 10px; }
    .hp-title .title-icon { width: 38px; height: 38px; border-radius: 11px; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; }
    .hp-see-all { display: inline-flex; align-items: center; gap: 5px; font-size: 0.82rem; font-weight: 600; color: var(--primary); padding: 7px 16px; border-radius: 100px; border: 1.5px solid currentColor; transition: background 0.2s; text-decoration: none; white-space: nowrap; }
    .hp-see-all:hover { background: rgba(44,95,46,0.07); }

    /* Hero enhancements (some overrides) */
    .hero { padding: 84px 0 80px; }
    .hero-inner { gap: 56px; }
    .hero-tag { display: inline-flex; align-items: center; gap: 8px; background: var(--gold-light); border-radius: 100px; padding: 6px 14px; margin-bottom: 22px; font-size: 0.72rem; font-weight: 700; color: var(--gold-dim); letter-spacing: 0.07em; text-transform: uppercase; }
    [data-theme="dark"] .hero-tag { color: var(--gold); }
    .hero-tag-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--gold); }
    .hero-search-bar { display: flex; border-radius: 14px; overflow: hidden; border: 1.5px solid var(--border); background: var(--surface); box-shadow: 0 4px 24px var(--shadow); max-width: 520px; transition: border-color 0.2s, box-shadow 0.2s; }
    .hero-search-bar:focus-within { border-color: var(--primary); box-shadow: 0 8px 32px var(--shadow); }
    .hero-search-bar input { flex: 1; padding: 14px 18px; border: none; background: transparent; font-family: inherit; font-size: 0.95rem; color: var(--tx); }
    .hero-search-bar input::placeholder { color: var(--tx3); }
    .hero-search-bar input:focus { outline: none; }
    .hero-search-bar button { padding: 10px 22px; margin: 5px; border: none; cursor: pointer; background: var(--primary); color: #fff; border-radius: 10px; font-family: inherit; font-size: 0.875rem; font-weight: 600; display: flex; align-items: center; gap: 7px; transition: background 0.2s; }
    [data-theme="dark"] .hero-search-bar button { color: var(--bg); }
    .hero-search-bar button:hover { background: var(--primary-h); }

    /* Book cards - new style */
    .bk-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(155px, 1fr)); gap: 18px; }
    .bk-card-hp { background: var(--surface); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; text-decoration: none; display: block; transition: transform 0.28s cubic-bezier(.34,1.56,.64,1), box-shadow 0.28s, border-color 0.2s; animation: bkUp 0.5s both; }
    .bk-card-hp:hover { transform: translateY(-7px); box-shadow: 0 14px 40px var(--shadow); border-color: var(--border2); }
    .bk-card-hp:nth-child(1){ animation-delay: 0.04s; } .bk-card-hp:nth-child(2){ animation-delay: 0.08s; } .bk-card-hp:nth-child(3){ animation-delay: 0.12s; } .bk-card-hp:nth-child(4){ animation-delay: 0.16s; } .bk-card-hp:nth-child(5){ animation-delay: 0.20s; } .bk-card-hp:nth-child(6){ animation-delay: 0.24s; }
    @keyframes bkUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .bk-cover-hp { aspect-ratio: 2/3; position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: flex-end; padding: 14px; }
    .bk-cover-hp::before { content: ''; position: absolute; top: 0; left: -60%; width: 40%; height: 100%; background: linear-gradient(105deg, transparent 0%, rgba(255,255,255,0.07) 50%, transparent 100%); transition: left 0.6s ease; pointer-events: none; }
    .bk-card-hp:hover .bk-cover-hp::before { left: 130%; }
    .bk-cover-hp::after { content: ''; position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.62) 0%, rgba(0,0,0,0.04) 55%, transparent 100%); }
    .bk-badge-hp { position: absolute; top: 10px; left: 10px; z-index: 2; background: var(--gold); color: #000; font-size: 0.6rem; font-weight: 700; padding: 2px 8px; border-radius: 4px; display: flex; align-items: center; gap: 2px; }
    .bk-new-hp { position: absolute; top: 10px; right: 10px; z-index: 2; background: rgba(0,0,0,0.5); color: #fff; backdrop-filter: blur(4px); font-size: 0.6rem; font-weight: 700; padding: 2px 8px; border-radius: 4px; display: flex; align-items: center; gap: 2px; }
    .bk-cover-title-hp { position: relative; z-index: 1; font-family: 'Playfair Display', serif; color: rgba(255,255,255,0.93); font-size: 0.7rem; font-weight: 600; line-height: 1.3; }
    .bk-body-hp { padding: 12px 13px 13px; }
    .bk-cat-hp { font-size: 0.62rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: var(--primary); background: rgba(44,95,46,0.08); padding: 2px 8px; border-radius: 4px; display: inline-block; margin-bottom: 6px; }
    [data-theme="dark"] .bk-cat-hp { background: rgba(74,222,128,0.1); }
    .bk-title-hp { font-size: 0.85rem; font-weight: 600; color: var(--tx); line-height: 1.3; margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .bk-author-hp { font-size: 0.74rem; color: var(--tx3); margin-bottom: 8px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .bk-rating-row { display: flex; align-items: center; justify-content: space-between; gap: 4px; }
    .bk-stars-hp { color: var(--gold); font-size: 11px; letter-spacing: 0.5px; display: flex; gap: 1px; }
    .bk-rating-num { font-size: 0.7rem; color: var(--tx3); font-weight: 500; }
    .bk-views-hp { font-size: 0.65rem; color: var(--tx3); display: flex; align-items: center; gap: 3px; }

    /* Popular & Trending */
    .popular-layout { display: grid; grid-template-columns: 1fr 1fr; gap: 44px; align-items: start; }
    .pop-list { display: flex; flex-direction: column; gap: 14px; }
    .pop-item { display: flex; align-items: center; gap: 16px; background: var(--surface); border: 1px solid var(--border); border-radius: 14px; padding: 14px 16px; text-decoration: none; color: inherit; transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s; }
    .pop-item:hover { transform: translateX(4px); box-shadow: 0 4px 24px var(--shadow); border-color: var(--border2); }
    .pop-num { font-family: 'Playfair Display', serif; font-size: 1.8rem; font-weight: 700; min-width: 40px; text-align: center; color: var(--border2); line-height: 1; }
    .pop-item:nth-child(1) .pop-num { color: var(--gold); } .pop-item:nth-child(2) .pop-num { color: var(--tx3); } .pop-item:nth-child(3) .pop-num { color: #8B7355; }
    .pop-thumb { width: 52px; height: 76px; border-radius: 6px; flex-shrink: 0; overflow: hidden; background-size: cover; background-position: center; }
    .pop-cat { font-size: 0.65rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: var(--primary); margin-bottom: 3px; }
    .pop-title { font-family: 'Playfair Display', serif; font-size: 0.95rem; font-weight: 600; color: var(--tx); line-height: 1.25; margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .pop-author { font-size: 0.74rem; color: var(--tx3); margin-bottom: 5px; }
    .pop-meta { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
    .pop-views { font-size: 0.7rem; color: var(--tx3); display: flex; align-items: center; gap: 3px; }
    .pop-badge { font-size: 0.62rem; font-weight: 600; padding: 2px 8px; border-radius: 4px; background: rgba(44,95,46,0.09); color: var(--primary); display: flex; align-items: center; gap: 2px; }
    [data-theme="dark"] .pop-badge { background: rgba(74,222,128,0.1); }
    .trend-pills { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 18px; }
    .tr-pill { padding: 7px 14px; border-radius: 100px; background: var(--surface); border: 1.5px solid var(--border); font-size: 0.78rem; font-weight: 500; color: var(--tx2); display: flex; align-items: center; gap: 6px; cursor: pointer; transition: border-color 0.2s, color 0.2s, background 0.2s; user-select: none; }
    .tr-pill:hover, .tr-pill.active { border-color: var(--primary); color: var(--primary); background: rgba(44,95,46,0.06); }
    [data-theme="dark"] .tr-pill:hover, [data-theme="dark"] .tr-pill.active { background: rgba(74,222,128,0.07); }

    /* Categories */
    .cat-grid-hp { display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 14px; }
    .cat-card-hp { background: var(--surface); border: 1px solid var(--border); border-radius: 14px; padding: 20px 14px; display: flex; flex-direction: column; align-items: center; text-align: center; gap: 10px; cursor: pointer; text-decoration: none; transition: transform 0.28s cubic-bezier(.34,1.56,.64,1), box-shadow 0.2s, border-color 0.2s; }
    .cat-card-hp:hover { transform: translateY(-5px); box-shadow: 0 8px 30px var(--shadow); border-color: var(--primary); background: rgba(44,95,46,0.02); }
    [data-theme="dark"] .cat-card-hp:hover { background: rgba(74,222,128,0.02); }
    .cat-icon-hp { width: 50px; height: 50px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 22px; transition: transform 0.28s cubic-bezier(.34,1.56,.64,1); }
    .cat-card-hp:hover .cat-icon-hp { transform: scale(1.12) rotate(-5deg); }
    .cat-name-hp { font-size: 0.82rem; font-weight: 600; color: var(--tx); }
    .cat-count-hp { font-size: 0.7rem; color: var(--tx3); display: flex; align-items: center; gap: 3px; }

    /* Scroll row */
    .scroll-row-hp { display: flex; gap: 16px; overflow-x: auto; padding-bottom: 8px; scroll-snap-type: x mandatory; -webkit-overflow-scrolling: touch; scrollbar-width: none; }
    .scroll-row-hp::-webkit-scrollbar { display: none; }
    .scroll-row-hp .bk-card-hp { min-width: 152px; flex-shrink: 0; scroll-snap-align: start; }

    /* Feedback */
    .fb-eyebrow { display: inline-flex; align-items: center; gap: 6px; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.07em; text-transform: uppercase; color: var(--tx3); margin-bottom: 14px; }
    .feedback-inner h2 { font-family: 'Playfair Display', serif; font-size: 2rem; font-weight: 700; margin-bottom: 10px; color: var(--tx); }
    .feedback-inner p { color: var(--tx2); margin-bottom: 30px; font-size: 0.95rem; line-height: 1.7; }
    .feedback-form { display: flex; flex-direction: column; gap: 12px; text-align: left; }
    .fb-input { padding: 13px 18px; border-radius: 10px; border: 1.5px solid var(--border); background: var(--surface); font-family: inherit; font-size: 0.9rem; color: var(--tx); transition: border-color 0.2s, box-shadow 0.2s; resize: none; width: 100%; }
    .fb-input::placeholder { color: var(--tx3); }
    .fb-input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(44,95,46,0.09); }
    [data-theme="dark"] .fb-input:focus { box-shadow: 0 0 0 3px rgba(74,222,128,0.09); }
    .fb-btn { padding: 13px 28px; border-radius: 10px; align-self: flex-end; background: var(--primary); color: #fff; font-family: inherit; font-size: 0.9rem; font-weight: 600; border: none; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: background 0.2s, transform 0.15s, box-shadow 0.2s; box-shadow: 0 4px 16px var(--shadow); }
    [data-theme="dark"] .fb-btn { color: var(--bg); }
    .fb-btn:hover { background: var(--primary-h); transform: translateY(-2px); }

    /* Responsive */
    @media (max-width: 960px) {
        .popular-layout { grid-template-columns: 1fr; gap: 32px; }
        .hero-inner { grid-template-columns: 1fr; }
        .hero-visual { display: none; }
    }
    @media (max-width: 640px) {
        .hero { padding: 52px 0 56px; }
        .hero h1 { font-size: 2.2rem; }
        .bk-grid { grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 12px; }
        .cat-grid-hp { grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 10px; }
        .hp-section { padding: 48px 0; }
        .hp-title { font-size: 1.35rem; }
    }
</style>
@endpush

@section('content')

{{-- HERO --}}
<section class="hero">
  <div class="hero-inner">
    <div>
      <h1 class="fade-up">Dunia tanpa batas<br>dimulai dari <span class="hero-accent">satu halaman.</span></h1>
      <p class="hero-desc fade-up">Jelajahi ribuan e-book pilihan dari berbagai kategori. Baca, nilai, dan bagikan pengalaman membacamu bersama komunitas Librova.</p>
      <form action="{{ route('books.index') }}" method="GET" class="hero-search-bar fade-up">
        <input type="text" name="search" placeholder="Cari judul, penulis, atau ISBN…">
        <button type="submit"><i class="bi bi-search"></i> Cari Buku</button>
      </form>
      <div class="hero-stats fade-up">
        <div><div class="hero-stat-num">{{ \App\Models\Book::count() ?? '0' }}</div><div class="hero-stat-label">Koleksi E-Book</div></div>
        <div><div class="hero-stat-num">{{ \App\Models\User::where('role','user')->count() ?? '0' }}</div><div class="hero-stat-label">Pembaca Aktif</div></div>
        <div><div class="hero-stat-num">{{ \App\Models\Category::count() ?? '0' }}</div><div class="hero-stat-label">Kategori</div></div>
      </div>
    </div>

    <div class="hero-visual">
      <div class="book-glow"></div>
      <div class="book-stack">
        <div class="book-card-3d b3"><div class="b-line"></div><div class="b-title">Clean Code</div><div class="b-auth">Robert C. Martin</div></div>
        <div class="book-card-3d b2"><div class="b-line"></div><div class="b-title">Sapiens</div><div class="b-auth">Yuval Noah Harari</div></div>
        <div class="book-card-3d b1"><div class="b-line"></div><div class="b-title">Atomic Habits</div><div class="b-auth">James Clear</div></div>
      </div>
      <div class="hero-badge b-a"><div class="hb-label">Rating Tertinggi</div><div class="hb-val">4.9 <span class="stars-sm"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></span></div></div>
      <div class="hero-badge b-b"><div class="hb-label">Dibaca Hari Ini</div><div class="hb-val">{{ number_format($readToday) }} pembaca</div></div>
    </div>
  </div>
</section>

{{-- FEATURED BOOKS --}}
@if($featuredBooks->count())
<section class="hp-section">
  <div class="hp-container">
    <div class="hp-head">
      <h2 class="hp-title">
        <span class="title-icon" style="background:rgba(201,168,76,.12)"><i class="bi bi-star-fill" style="color:var(--gold)"></i></span>
        Pilihan Unggulan
      </h2>
      <a href="{{ route('books.index') }}" class="hp-see-all">Lihat Semua <i class="bi bi-arrow-right"></i></a>
    </div>
    <div class="bk-grid">
      @foreach($featuredBooks as $book)
        <a href="{{ route('books.show', $book->slug) }}" class="bk-card-hp">
          <div class="bk-cover-hp" style="@if($book->cover_image) background-image: url('{{ Storage::url($book->cover_image) }}'); background-size: cover; background-position: center; @else background: linear-gradient(145deg, {{ $book->cover_color ?? '#2C5F2E' }}, {{ $book->cover_color_dark ?? '#1d4220' }}); @endif">
            @if($book->is_featured)<span class="bk-badge-hp"><i class="bi bi-star-fill"></i> Unggulan</span>@endif
            @if($book->created_at->isAfter(now()->subDays(30)))<span class="bk-new-hp"><i class="bi bi-bell-fill"></i> Baru</span>@endif
            <div class="bk-cover-title-hp">{{ Str::limit($book->title, 22) }}</div>
          </div>
          <div class="bk-body-hp">
            <span class="bk-cat-hp">{{ $book->category->name ?? '-' }}</span>
            <div class="bk-title-hp">{{ $book->title }}</div>
            <div class="bk-author-hp">{{ $book->author }}</div>
            <div class="bk-rating-row">
              <div style="display:flex;align-items:center;gap:4px">
                <span class="bk-stars-hp">
                  @for($i=1; $i<=5; $i++)<i class="bi {{ $i <= round($book->averageRating()) ? 'bi-star-fill' : 'bi-star' }}"></i>@endfor
                </span>
                <span class="bk-rating-num">{{ number_format($book->averageRating(),1) }}</span>
              </div>
              <span class="bk-views-hp"><i class="bi bi-eye" style="font-size:10px"></i> {{ number_format($book->view_count) }}</span>
            </div>
          </div>
        </a>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- POPULAR + TRENDING --}}
<section class="hp-section hp-section-alt">
  <div class="hp-container">
    <div class="popular-layout">
      <div>
        <div class="hp-head">
          <h2 class="hp-title">
            <span class="title-icon" style="background:rgba(239,68,68,.1)"><i class="bi bi-fire" style="color:#ef4444"></i></span>
            Terpopuler
          </h2>
        </div>
        <div class="pop-list">
          @foreach($popularBooks->take(5) as $index => $book)
            <a href="{{ route('books.show', $book->slug) }}" class="pop-item">
              <div class="pop-num">{{ str_pad($index+1,2,'0',STR_PAD_LEFT) }}</div>
              <div class="pop-thumb" style="@if($book->cover_image) background-image: url('{{ Storage::url($book->cover_image) }}'); @else background: linear-gradient(145deg, {{ $book->cover_color ?? '#2C5F2E' }}, {{ $book->cover_color_dark ?? '#1d4220' }}); @endif"></div>
              <div style="min-width:0;flex:1">
                <div class="pop-cat">{{ $book->category->name ?? '' }}</div>
                <div class="pop-title">{{ $book->title }}</div>
                <div class="pop-author">{{ $book->author }}</div>
                <div class="pop-meta">
                  <span class="stars-sm"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></span>
                  <span class="pop-views"><i class="bi bi-eye" style="font-size:10px"></i> {{ number_format($book->view_count) }} dibaca</span>
                  @if($book->is_featured)<span class="pop-badge"><i class="bi bi-graph-up-arrow" style="font-size:9px"></i> Trending</span>@endif
                </div>
              </div>
            </a>
          @endforeach
        </div>
      </div>

      <div>
        <div class="hp-head">
          <h2 class="hp-title">
            <span class="title-icon" style="background:rgba(99,102,241,.1)"><i class="bi bi-graph-up-arrow" style="color:#6366f1"></i></span>
            Trending Minggu Ini
          </h2>
        </div>
        <div class="trend-pills">
          <span class="tr-pill active" onclick="setTrend(this)"><i class="bi bi-grid"></i> Semua</span>
          <span class="tr-pill" onclick="setTrend(this)"><i class="bi bi-briefcase"></i> Bisnis</span>
          <span class="tr-pill" onclick="setTrend(this)"><i class="bi bi-lightning-charge"></i> Self-Dev</span>
          <span class="tr-pill" onclick="setTrend(this)"><i class="bi bi-cpu"></i> Teknologi</span>
        </div>
        <div class="bk-grid" style="grid-template-columns:repeat(2,1fr);gap:14px">
          @foreach($trendingBooks->take(4) as $book)
            <a href="{{ route('books.show', $book->slug) }}" class="bk-card-hp">
              <div class="bk-cover-hp" style="@if($book->cover_image) background-image: url('{{ Storage::url($book->cover_image) }}'); background-size: cover; background-position: center; @else background: linear-gradient(145deg, {{ $book->cover_color ?? '#3a6b5c' }}, {{ $book->cover_color_dark ?? '#1d4035' }}); @endif aspect-ratio:1.6/2;">
                @if($book->created_at->diffInDays(now()) < 7)<span class="bk-new-hp"><i class="bi bi-bell-fill"></i> Baru</span>@endif
                <div class="bk-cover-title-hp">{{ Str::limit($book->title,15) }}</div>
              </div>
              <div class="bk-body-hp">
                <span class="bk-cat-hp">{{ $book->category->name ?? '' }}</span>
                <div class="bk-title-hp">{{ $book->title }}</div>
                <div class="bk-author-hp">{{ $book->author }}</div>
                <div class="bk-rating-row">
                  <span class="bk-stars-hp">@for($i=1; $i<=5; $i++)<i class="bi {{ $i <= round($book->averageRating()) ? 'bi-star-fill' : 'bi-star' }}"></i>@endfor</span>
                  <span class="bk-rating-num">{{ number_format($book->averageRating(),1) }}</span>
                </div>
              </div>
            </a>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>

{{-- CATEGORIES --}}
@if($categories->count())
<section class="hp-section hp-section-alt">
  <div class="hp-container">
    <div class="hp-head">
      <h2 class="hp-title">
        <span class="title-icon" style="background:rgba(245,158,11,.1)"><i class="bi bi-grid-1x2" style="color:#f59e0b"></i></span>
        Jelajahi Kategori
      </h2>
      <a href="{{ route('categories.index') }}" class="hp-see-all">Semua Kategori <i class="bi bi-arrow-right"></i></a>
    </div>
    <div class="cat-grid-hp">
      @foreach($categories as $cat)
        <a href="{{ route('categories.show', $cat->slug) }}" class="cat-card-hp">
          <div class="cat-icon-hp" style="background:{{ $cat->icon_bg ?? 'rgba(44,95,46,.1)' }}">
            <i class="bi bi-book" style="color:{{ $cat->icon_color ?? 'var(--primary)' }};font-size:22px"></i>
          </div>
          <div class="cat-name-hp">{{ $cat->name }}</div>
          <div class="cat-count-hp"><i class="bi bi-book" style="font-size:10px"></i> {{ $cat->books_count ?? $cat->books()->count() }} buku</div>
        </a>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- NEW ARRIVALS --}}
@if($newArrivals->count())
<section class="hp-section">
  <div class="hp-container">
    <div class="hp-head">
      <h2 class="hp-title">
        <span class="title-icon" style="background:rgba(16,185,129,.1)"><i class="bi bi-clock-history" style="color:#10b981"></i></span>
        Buku Terbaru
      </h2>
      <a href="{{ route('books.index', ['sort' => 'newest']) }}" class="hp-see-all">Lihat Semua <i class="bi bi-arrow-right"></i></a>
    </div>
    <div class="scroll-row-hp">
      @foreach($newArrivals as $book)
        <a href="{{ route('books.show', $book->slug) }}" class="bk-card-hp">
          <div class="bk-cover-hp" style="@if($book->cover_image) background-image: url('{{ Storage::url($book->cover_image) }}'); background-size: cover; background-position: center; @else background: linear-gradient(145deg, {{ $book->cover_color ?? '#1a5c4a' }}, {{ $book->cover_color_dark ?? '#0d3a2d' }}); @endif">
            <span class="bk-new-hp"><i class="bi bi-bell-fill"></i> Baru</span>
            <div class="bk-cover-title-hp">{{ Str::limit($book->title,20) }}</div>
          </div>
          <div class="bk-body-hp">
            <span class="bk-cat-hp">{{ $book->category->name ?? '' }}</span>
            <div class="bk-title-hp">{{ $book->title }}</div>
            <div class="bk-author-hp">{{ $book->author }}</div>
            <div class="bk-rating-row">
              <span class="bk-stars-hp">@for($i=1; $i<=5; $i++)<i class="bi {{ $i <= round($book->averageRating()) ? 'bi-star-fill' : 'bi-star' }}"></i>@endfor</span>
              <span class="bk-rating-num">{{ number_format($book->averageRating(),1) }}</span>
            </div>
          </div>
        </a>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- FEEDBACK SECTION --}}
<div class="feedback-section" x-data="feedbackForm()">
  <div class="feedback-inner">
    <div class="fb-eyebrow"><i class="bi bi-chat-heart"></i> Suaramu penting untuk kami</div>
    <h2>Punya masukan<br>untuk Librova?</h2>
    <p>Kami terus berkembang. Ceritakan pengalamanmu atau buku apa yang ingin kamu temukan di Librova.</p>

    {{-- Notifikasi Sukses --}}
    <div x-show="successMessage" x-transition
         style="background: #E8F5E9; color: #1a4a1c; padding: 12px 16px; border-radius: 10px; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
        <i class="bi bi-check-circle-fill"></i>
        <span x-text="successMessage"></span>
    </div>

    {{-- Notifikasi Error --}}
    <div x-show="errorMessage" x-transition
         style="background: #FEF2F2; color: #B91C1C; padding: 12px 16px; border-radius: 10px; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
        <i class="bi bi-exclamation-circle-fill"></i>
        <span x-text="errorMessage"></span>
    </div>

    <form @submit.prevent="submitForm" class="feedback-form">
        @csrf
        <input class="fb-input" type="text" name="subject" x-model="form.subject" placeholder="Subjek masukan…" required>
        <textarea class="fb-input" name="message" x-model="form.message" rows="4" placeholder="Tulis masukan, saran, atau permintaan koleksi buku…" required></textarea>
        <button type="submit" class="fb-btn" :disabled="loading">
            <i class="bi bi-send"></i>
            <span x-text="loading ? 'Mengirim…' : 'Kirim Masukan'"></span>
        </button>
    </form>
  </div>
</div>

<div class="divider"></div>

@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.tr-pill').forEach(pill => {
      pill.addEventListener('click', function () {
        document.querySelectorAll('.tr-pill').forEach(p => p.classList.remove('active'));
        this.classList.add('active');
      });
    });
  });
  function setTrend(el) {
    document.querySelectorAll('.tr-pill').forEach(p => p.classList.remove('active'));
    el.classList.add('active');
  }

  // ✅ Form Feedback dengan Alpine.js
  function feedbackForm() {
      return {
          form: { subject: '', message: '' },
          loading: false,
          successMessage: '',
          errorMessage: '',
          async submitForm() {
              this.loading = true;
              this.successMessage = '';
              this.errorMessage = '';
              try {
                  const response = await fetch('{{ route('feedback.store') }}', {
                      method: 'POST',
                      headers: {
                          'Content-Type': 'application/json',
                          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                          'Accept': 'application/json',
                      },
                      body: JSON.stringify(this.form),
                  });
                  const data = await response.json();
                  if (!response.ok) {
                      if (data.errors) {
                          const firstError = Object.values(data.errors)[0][0];
                          this.errorMessage = firstError || 'Validasi gagal.';
                      } else {
                          this.errorMessage = data.message || 'Terjadi kesalahan.';
                      }
                      return;
                  }
                  this.successMessage = data.message || 'Terima kasih! Masukan kamu telah dikirim.';
                  this.form.subject = '';
                  this.form.message = '';
              } catch (err) {
                  this.errorMessage = 'Gagal mengirim feedback. Silakan coba lagi.';
              } finally {
                  this.loading = false;
              }
          }
      };
  }
</script>
@endpush