@extends('layouts.app')

@section('title', 'Home — Librova')
@section('content')

{{-- HERO --}}
<section class="hero">
  <div class="hero-inner">
    <div>
      <h1 class="fade-up">
        Dunia tanpa batas<br>dimulai dari <span class="hero-accent">satu halaman.</span>
      </h1>
      <p class="hero-desc fade-up">
        Jelajahi ribuan e‑book pilihan dari berbagai kategori. Baca, nilai, dan bagikan pengalaman membacamu bersama komunitas Librova.
      </p>
      <form action="{{ route('books.index') }}" method="GET" class="hero-search fade-up">
        <input type="text" name="search" placeholder="Cari judul, penulis, atau ISBN…">
        <button type="submit">
          <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
          Cari Buku
        </button>
      </form>
      <div class="hero-stats fade-up">
        <div>
          <div class="hero-stat-num">{{ \App\Models\Book::count() ?? '0' }}</div>
          <div class="hero-stat-label">Koleksi E-Book</div>
        </div>
        <div>
          <div class="hero-stat-num">{{ \App\Models\User::where('role','user')->count() ?? '0' }}</div>
          <div class="hero-stat-label">Pembaca Aktif</div>
        </div>
        <div>
          <div class="hero-stat-num">{{ \App\Models\Category::count() ?? '0' }}</div>
          <div class="hero-stat-label">Kategori</div>
        </div>
      </div>
    </div>

    {{-- Visual Book Stack (tetap) --}}
    <div class="hero-visual">
      <div class="book-glow"></div>
      <div class="book-stack">
        <div class="book-card-3d b3"><div class="b-line"></div><div class="b-title">Clean Code</div><div class="b-auth">Robert C. Martin</div></div>
        <div class="book-card-3d b2"><div class="b-line"></div><div class="b-title">Sapiens</div><div class="b-auth">Yuval Noah Harari</div></div>
        <div class="book-card-3d b1"><div class="b-line"></div><div class="b-title">Atomic Habits</div><div class="b-auth">James Clear</div></div>
      </div>
      <div class="hero-badge b-a">
        <div class="hb-label">Rating Tertinggi</div>
        <div class="hb-val">4.9 <span class="stars-sm">★★★★★</span></div>
      </div>
      <div class="hero-badge b-b">
        <div class="hb-label">Dibaca Hari Ini</div>
        <div class="hb-val">3,241 pembaca</div>
      </div>
    </div>
  </div>
</section>

{{-- FEATURED BOOKS --}}
@if($featuredBooks->count())
<section class="section">
  <div class="container">
    <div class="section-head">
      <h2 class="section-title">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="inline-block mr-2">
          <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
        </svg>
        Pilihan Unggulan
      </h2>
      <a href="{{ route('books.index') }}" class="section-eye">Lihat Semua →</a>
    </div>
    <div class="books-grid">
      @foreach($featuredBooks as $book)
        <a href="{{ route('books.show', $book->slug) }}" class="bk-card fade-up">
          <div class="bk-cover" style="background: linear-gradient(145deg, {{ $book->cover_color ?? '#2C5F2E' }}, {{ $book->cover_color_dark ?? '#1d4220' }})">
            @if($book->is_featured)
              <span class="bk-rank">★</span>
            @endif
            <div class="bk-cover-title">{{ Str::limit($book->title, 20) }}</div>
          </div>
          <div class="bk-body">
            <span class="bk-cat">{{ $book->category->name ?? '-' }}</span>
            <div class="bk-title">{{ $book->title }}</div>
            <div class="bk-author">{{ $book->author }}</div>
            <div class="bk-rating">
              <span class="stars">@for($i=1; $i<=5; $i++){{ $i <= round($book->averageRating()) ? '★' : '☆' }}@endfor</span>
              <span class="rating-num">{{ $book->averageRating() }} ({{ $book->ratingsCount() }})</span>
            </div>
          </div>
        </a>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- POPULAR + TRENDING --}}
<section class="section section-alt">
  <div class="container">
    <div class="popular-layout">
      {{-- Popular List --}}
      <div>
        <div class="section-head">
          <h2 class="section-title">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="inline-block mr-2">
              <path d="M15.172 9.828a4 4 0 11-5.657-5.657 4 4 0 015.657 5.657z"/>
              <path d="M12 5v.01M12 12v.01M12 19v.01M5.636 5.636v.01M18.364 18.364v.01"/>
            </svg>
            Terpopuler
          </h2>
        </div>
        <div class="popular-list">
          @foreach($popularBooks->take(5) as $index => $book)
            <a href="{{ route('books.show', $book->slug) }}" class="pop-item">
              <div class="pop-num">{{ str_pad($index+1, 2, '0', STR_PAD_LEFT) }}</div>
              <div class="pop-thumb" style="background: linear-gradient(145deg, #2C5F2E, #1d4220);"></div>
              <div class="pop-info">
                <div class="pop-cat">{{ $book->category->name ?? '' }}</div>
                <div class="pop-title">{{ $book->title }}</div>
                <div class="pop-author">{{ $book->author }}</div>
                <div class="pop-meta">
                  <span class="stars-sm">★★★★★</span>
                  <span class="pop-views">{{ number_format($book->view_count) }} dibaca</span>
                  @if($book->is_featured)
                    <span class="pop-badge">Trending</span>
                  @endif
                </div>
              </div>
            </a>
          @endforeach
        </div>
      </div>

      {{-- Trending + New --}}
      <div>
        <div class="section-head">
          <h2 class="section-title">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="inline-block mr-2">
              <path d="M4 20h16M6 14l2-6 3 4 4-8 3 8"/>
            </svg>
            Trending Minggu Ini
          </h2>
        </div>
        <div class="trending-pills">
          <div class="tr-pill active">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
            Semua
          </div>
          <div class="tr-pill">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 7l-8 5-4-3L4 17M4 7h16"/></svg>
            Bisnis
          </div>
          <div class="tr-pill">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 2l2.4 7.2h7.6l-6 4.8 2.4 7.2-6-4.8-6 4.8 2.4-7.2-6-4.8h7.6z"/></svg>
            Self-Dev
          </div>
          <div class="tr-pill">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
            Teknologi
          </div>
        </div>
        <div class="books-grid" style="grid-template-columns:repeat(2,1fr);gap:14px">
          @foreach($trendingBooks->take(4) as $book)
            <a href="{{ route('books.show', $book->slug) }}" class="bk-card">
              <div class="bk-cover" style="background:linear-gradient(145deg,#3a6b5c,#1d4035);aspect-ratio:1.5/2">
                @if($book->created_at->diffInDays(now()) < 7)
                  <div class="bk-new">Baru</div>
                @endif
                <div class="bk-cover-title">{{ Str::limit($book->title, 15) }}</div>
              </div>
              <div class="bk-body">
                <span class="bk-cat">{{ $book->category->name ?? '' }}</span>
                <div class="bk-title">{{ $book->title }}</div>
                <div class="bk-author">{{ $book->author }}</div>
                <div class="bk-rating"><span class="stars">★★★★☆</span><span class="rating-num">{{ $book->averageRating() }}</span></div>
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
<section class="section">
  <div class="container">
    <div class="section-head">
      <h2 class="section-title">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="inline-block mr-2">
          <path d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
        </svg>
        Jelajahi Kategori
      </h2>
      <a href="{{ route('categories.index') }}" class="section-eye">Semua Kategori →</a>
    </div>
    <div class="cat-grid">
      @foreach($categories as $cat)
        <a href="{{ route('categories.show', $cat->slug) }}" class="cat-card">
          <div class="cat-icon" style="background:#E8F5E9">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
          </div>
          <div class="cat-name">{{ $cat->name }}</div>
          <div class="cat-count">{{ $cat->books_count ?? $cat->books()->count() }} buku</div>
        </a>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- NEW ARRIVALS --}}
@if($newArrivals->count())
<section class="section section-alt">
  <div class="container">
    <div class="section-head">
      <h2 class="section-title">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="inline-block mr-2">
          <circle cx="12" cy="12" r="10"/>
          <path d="M12 8v4l3 3"/>
        </svg>
        Buku Terbaru
      </h2>
      <a href="{{ route('books.index', ['sort' => 'newest']) }}" class="section-eye">Lihat Semua →</a>
    </div>
    <div class="scroll-row">
      @foreach($newArrivals as $book)
        <a href="{{ route('books.show', $book->slug) }}" class="bk-card">
          <div class="bk-cover" style="background:linear-gradient(145deg,#1a5c4a,#0d3a2d)">
            <div class="bk-new">Baru</div>
            <div class="bk-cover-title">{{ Str::limit($book->title, 20) }}</div>
          </div>
          <div class="bk-body">
            <span class="bk-cat">{{ $book->category->name ?? '' }}</span>
            <div class="bk-title">{{ $book->title }}</div>
            <div class="bk-author">{{ $book->author }}</div>
            <div class="bk-rating"><span class="stars">★★★★☆</span><span class="rating-num">{{ $book->averageRating() }}</span></div>
          </div>
        </a>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- FEEDBACK SECTION --}}
<div class="feedback-section">
  <div class="feedback-inner">
    <h2>Punya masukan untuk kami?</h2>
    <p>Kami terus berkembang. Ceritakan pengalamanmu atau buku apa yang ingin kamu temukan di Librova.</p>
    <form action="{{ route('feedback.store') }}" method="POST" class="feedback-form">
      @csrf
      <input class="fb-input" type="text" name="subject" placeholder="Subjek masukan…" required>
      <textarea class="fb-input" name="message" rows="4" placeholder="Tulis masukan, saran, atau permintaan koleksi buku…" required></textarea>
      <button type="submit" class="fb-btn">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
        Kirim Masukan
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
        // AJAX filter bisa ditambahkan di sini
      });
    });
  });
</script>
@endpush