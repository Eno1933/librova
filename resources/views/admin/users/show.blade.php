@extends('layouts.admin')

@section('title', 'Detail User — Admin Librova')
@section('header-title', 'Detail User')

@push('styles')
<style>
    .user-show-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }
    @media (max-width: 900px) {
        .user-show-grid { grid-template-columns: 1fr; }
    }

    .user-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
        animation: cardUp 0.4s ease both;
    }
    .user-card-header {
        padding: 18px 20px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .user-card-title {
        font-family: 'Playfair Display', serif;
        font-size: 1rem;
        font-weight: 700;
        color: var(--tx);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .user-card-body {
        padding: 20px;
    }

    .profile-section {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 24px;
    }
    .profile-avatar {
        width: 80px; height: 80px;
        border-radius: 50%;
        background: var(--surface2);
        display: flex; align-items: center; justify-content: center;
        font-size: 2rem; font-weight: 700; color: var(--primary);
        overflow: hidden; flex-shrink: 0;
    }
    .profile-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .profile-name {
        font-family: 'Playfair Display', serif;
        font-size: 1.4rem; font-weight: 700; color: var(--tx);
    }
    .profile-email { font-size: 0.9rem; color: var(--tx3); margin-bottom: 4px; }
    .profile-meta { font-size: 0.8rem; color: var(--tx3); display: flex; flex-wrap: wrap; gap: 12px; }

    .stat-mini-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
        margin-bottom: 20px;
    }
    .stat-mini {
        background: var(--surface2);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 14px;
        text-align: center;
    }
    .stat-mini-val {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem; font-weight: 700; color: var(--tx);
    }
    .stat-mini-lbl { font-size: 0.72rem; color: var(--tx3); margin-top: 2px; font-weight: 500; }

    .mini-book-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 10px;
    }
    .mini-book {
        background: var(--surface2);
        border: 1px solid var(--border);
        border-radius: 10px;
        overflow: hidden;
        text-decoration: none;
        transition: transform 0.2s;
        display: block;
    }
    .mini-book:hover { transform: translateY(-3px); }
    .mini-cover {
        aspect-ratio: 2/3;
        background-size: cover;
        background-position: center;
        position: relative;
    }
    .mini-cover::after {
        content: '';
        position: absolute; inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.5) 0%, transparent 50%);
    }
    .mini-cover-title {
        position: absolute; bottom: 4px; left: 6px; right: 6px;
        font-size: 0.6rem; font-weight: 600; color: #fff; z-index: 1;
        font-family: 'Playfair Display', serif;
    }

    .history-list {
        display: flex; flex-direction: column; gap: 8px;
    }
    .history-item {
        display: flex; align-items: center; gap: 12px;
        padding: 10px 12px;
        background: var(--surface2);
        border: 1px solid var(--border);
        border-radius: 10px;
        text-decoration: none;
        color: inherit;
        transition: background 0.15s;
    }
    .history-item:hover { background: var(--surface); }
    .history-cover {
        width: 36px; height: 50px; border-radius: 6px;
        background-size: cover; background-position: center; flex-shrink: 0;
    }
    .history-info { flex: 1; min-width: 0; }
    .history-title {
        font-weight: 600; font-size: 0.85rem; color: var(--tx);
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .history-author { font-size: 0.72rem; color: var(--tx3); }
    .history-time { font-size: 0.7rem; color: var(--tx3); white-space: nowrap; }

    .btn-suspend {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 18px; border-radius: 100px;
        font-weight: 600; font-size: 0.82rem; border: none; cursor: pointer;
        transition: all 0.2s;
    }
    .btn-suspend.suspend { background: #ef4444; color: #fff; }
    .btn-suspend.unsuspend { background: #22c55e; color: #fff; }
    .btn-suspend:hover { opacity: 0.9; transform: translateY(-1px); }

    .alert {
        padding: 12px 16px; border-radius: 10px; margin-bottom: 20px;
        font-size: 0.88rem; display: flex; align-items: center; gap: 8px;
    }
    .alert-success { background: #E8F5E9; color: #1a4a1c; border: 1px solid #A5D6A7; }
    .alert-error { background: #FEF2F2; color: #B91C1C; border: 1px solid #FECACA; }
</style>
@endpush

@section('content')
<div style="padding: 28px 28px 40px; max-width: 900px; margin: 0 auto;">

    <div style="display:flex; align-items:center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
        <div style="display:flex; align-items:center; gap:12px;">
            <a href="{{ route('admin.users.index') }}" class="au-btn view" style="text-decoration: none;">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h2 style="font-family:'Playfair Display',serif; font-size:1.5rem; color:var(--tx); margin:0;">Detail User</h2>
        </div>
        @if($user->id !== auth()->id())
        <form action="{{ route('admin.users.toggle-suspend', $user->id) }}" method="POST"
              onsubmit="return confirm('{{ $user->suspended_at ? 'Aktifkan kembali akun ini?' : 'Nonaktifkan akun ini?' }}')">
            @csrf @method('PATCH')
            <button type="submit" class="btn-suspend {{ $user->suspended_at ? 'unsuspend' : 'suspend' }}">
                <i class="bi {{ $user->suspended_at ? 'bi-unlock' : 'bi-lock' }}"></i>
                {{ $user->suspended_at ? 'Aktifkan Kembali' : 'Nonaktifkan Akun' }}
            </button>
        </form>
        @endif
    </div>

    @if(session('success'))
    <div class="alert alert-success"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert alert-error"><i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}</div>
    @endif

    {{-- Profile --}}
    <div class="user-card" style="margin-bottom:20px;">
        <div class="user-card-header">
            <div class="user-card-title"><i class="bi bi-person"></i> Profil</div>
        </div>
        <div class="user-card-body">
            <div class="profile-section">
                <div class="profile-avatar">
                    @if($user->avatar)
                    <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}">
                    @else
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                    @endif
                </div>
                <div>
                    <div class="profile-name">{{ $user->name }}</div>
                    <div class="profile-email">{{ $user->email }}</div>
                    <div class="profile-meta">
                        <span><i class="bi bi-calendar3"></i> Bergabung {{ $user->created_at->translatedFormat('d F Y') }}</span>
                        <span><i class="bi bi-shield"></i> {{ $user->role === 'admin' ? 'Admin' : 'User' }}</span>
                        @if($user->email_verified_at)
                        <span><i class="bi bi-patch-check-fill"></i> Terverifikasi</span>
                        @else
                        <span><i class="bi bi-patch-exclamation"></i> Belum verifikasi</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="stat-mini-grid">
                <div class="stat-mini">
                    <div class="stat-mini-val">{{ $user->bookmarks_count }}</div>
                    <div class="stat-mini-lbl">Bookmark</div>
                </div>
                <div class="stat-mini">
                    <div class="stat-mini-val">{{ \App\Models\BookView::where('user_id', $user->id)->distinct('book_id')->count() }}</div>
                    <div class="stat-mini-lbl">Buku Dibaca</div>
                </div>
                <div class="stat-mini">
                    <div class="stat-mini-val">{{ $user->reviews_count }}</div>
                    <div class="stat-mini-lbl">Review</div>
                </div>
                <div class="stat-mini">
                    <div class="stat-mini-val">{{ $user->ratings_count }}</div>
                    <div class="stat-mini-lbl">Rating</div>
                </div>
            </div>
        </div>
    </div>

    <div class="user-show-grid">
        {{-- Bookmark Terbaru --}}
        <div class="user-card">
            <div class="user-card-header">
                <div class="user-card-title"><i class="bi bi-bookmark-star"></i> Bookmark Terbaru</div>
                <a href="{{ route('admin.books.index') }}" style="font-size:0.78rem;color:var(--primary);text-decoration:none;">Lihat Semua</a>
            </div>
            <div class="user-card-body">
                @if($recentBookmarks->count())
                <div class="mini-book-list">
                    @foreach($recentBookmarks as $bm)
                    <a href="{{ route('books.show', $bm->book->slug) }}" class="mini-book">
                        <div class="mini-cover" style="@if($bm->book->cover_image) background-image:url('{{ Storage::url($bm->book->cover_image) }}'); @else background:linear-gradient(135deg,{{ $bm->book->cover_color ?? '#2C5F2E' }},{{ $bm->book->cover_color_dark ?? '#1d4220' }}); @endif">
                            <div class="mini-cover-title">{{ Str::limit($bm->book->title, 12) }}</div>
                        </div>
                    </a>
                    @endforeach
                </div>
                @else
                <p style="color:var(--tx3);font-size:0.85rem;">Belum ada bookmark.</p>
                @endif
            </div>
        </div>

        {{-- Riwayat Baca --}}
        <div class="user-card">
            <div class="user-card-header">
                <div class="user-card-title"><i class="bi bi-clock-history"></i> Riwayat Baca Terbaru</div>
            </div>
            <div class="user-card-body">
                @if($recentHistory->count())
                <div class="history-list">
                    @foreach($recentHistory as $item)
                    <a href="{{ route('books.show', $item->book->slug) }}" class="history-item">
                        <div class="history-cover" style="@if($item->book->cover_image) background-image:url('{{ Storage::url($item->book->cover_image) }}'); @else background:linear-gradient(135deg,{{ $item->book->cover_color ?? '#2C5F2E' }},{{ $item->book->cover_color_dark ?? '#1d4220' }}); @endif"></div>
                        <div class="history-info">
                            <div class="history-title">{{ $item->book->title }}</div>
                            <div class="history-author">{{ $item->book->author }}</div>
                        </div>
                        <div class="history-time">{{ $item->viewed_at->diffForHumans() }}</div>
                    </a>
                    @endforeach
                </div>
                @else
                <p style="color:var(--tx3);font-size:0.85rem;">Belum ada riwayat membaca.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection