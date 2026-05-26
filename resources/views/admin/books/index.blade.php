@extends('layouts.admin')

@section('title', 'Manajemen Buku — Admin Librova')
@section('header-title', 'Manajemen Buku')
@section('breadcrumb', 'Manajemen Buku')

@push('styles')
<style>
/* ═══════════════════════════════════════════
   ADMIN BOOKS INDEX
═══════════════════════════════════════════ */

.ab-page { padding: 28px 28px 48px; }

/* ── Page head ── */
.ab-head {
    display: flex; align-items: flex-end; justify-content: space-between;
    gap: 16px; flex-wrap: wrap; margin-bottom: 24px;
}
.ab-head-left {}
.ab-page-eyebrow {
    font-size: .7rem; font-weight: 700; letter-spacing: .08em;
    text-transform: uppercase; color: var(--tx3); margin-bottom: 5px;
    display: flex; align-items: center; gap: 6px;
}
.ab-page-eyebrow-dot { width: 5px; height: 5px; border-radius: 50%; background: var(--primary); }
.ab-page-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.6rem; font-weight: 700; letter-spacing: -.02em; color: var(--tx);
}
.ab-create-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 10px 22px; border-radius: 100px;
    background: var(--primary); color: #fff;
    font-family: inherit; font-size: .85rem; font-weight: 600;
    text-decoration: none; white-space: nowrap;
    transition: background .2s, transform .15s, box-shadow .2s;
    box-shadow: 0 3px 12px var(--shadow);
}
[data-theme="dark"] .ab-create-btn { color: var(--bg); }
.ab-create-btn:hover { background: var(--primary-h); transform: translateY(-1px); box-shadow: 0 6px 20px var(--shadow); }

/* ── Quick stat chips ── */
.ab-stats { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 24px; }
.ab-stat {
    display: flex; align-items: center; gap: 8px;
    padding: 9px 16px; border-radius: 10px;
    background: var(--surface); border: 1px solid var(--border);
    font-size: .82rem; transition: border-color .2s;
}
.ab-stat:hover { border-color: var(--border2); }
.ab-stat-icon {
    width: 28px; height: 28px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center; font-size: 13px;
}
.ab-stat-val { font-family: 'Playfair Display', serif; font-size: 1.05rem; font-weight: 700; color: var(--tx); }
.ab-stat-lbl { font-size: .7rem; color: var(--tx3); font-weight: 500; }

/* ── Toolbar ── */
.ab-toolbar {
    display: flex; align-items: center; gap: 10px;
    flex-wrap: wrap; margin-bottom: 18px;
}
.ab-search {
    display: flex; border-radius: 10px;
    border: 1.5px solid var(--border); background: var(--surface);
    overflow: hidden; flex: 1; max-width: 340px;
    transition: border-color .2s, box-shadow .2s;
}
.ab-search:focus-within {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(44,95,46,.09);
}
[data-theme="dark"] .ab-search:focus-within { box-shadow: 0 0 0 3px rgba(74,222,128,.09); }
.ab-search-icon { display: flex; align-items: center; padding: 0 11px; color: var(--tx3); flex-shrink: 0; }
.ab-search input {
    flex: 1; padding: 10px 6px; border: none; background: transparent;
    font-family: inherit; font-size: .84rem; color: var(--tx); min-width: 0;
}
.ab-search input::placeholder { color: var(--tx3); }
.ab-search input:focus { outline: none; }
.ab-search-btn {
    margin: 4px; padding: 0 14px; border-radius: 7px;
    background: var(--primary); color: #fff; border: none;
    cursor: pointer; font-family: inherit; font-size: .82rem; font-weight: 600;
    display: flex; align-items: center; gap: 5px;
    transition: background .2s;
}
[data-theme="dark"] .ab-search-btn { color: var(--bg); }
.ab-search-btn:hover { background: var(--primary-h); }

.ab-select {
    padding: 10px 28px 10px 12px; border-radius: 10px;
    border: 1.5px solid var(--border); background: var(--surface);
    font-family: inherit; font-size: .83rem; color: var(--tx2);
    appearance: none; cursor: pointer; min-width: 140px;
    background-image: url("data:image/svg+xml,%3Csvg width='12' height='8' viewBox='0 0 12 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%239A9282' stroke-width='1.5' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 10px center;
    transition: border-color .2s;
}
.ab-select:focus { outline: none; border-color: var(--primary); }

/* Active filter chip */
.ab-active-chip {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 10px; border-radius: 100px;
    background: rgba(44,95,46,.09); border: 1px solid rgba(44,95,46,.2);
    font-size: .75rem; font-weight: 600; color: var(--primary);
}
[data-theme="dark"] .ab-active-chip { background: rgba(74,222,128,.09); border-color: rgba(74,222,128,.2); }
.ab-active-chip a { color: var(--primary); font-size: 14px; }

/* Result label */
.ab-result-info {
    font-size: .8rem; color: var(--tx3); margin-bottom: 14px;
}
.ab-result-info strong { color: var(--tx2); }

/* ── Table card ── */
.ab-table-card {
    background: var(--surface); border: 1px solid var(--border);
    border-radius: 16px; overflow: hidden;
}
.ab-table {
    width: 100%; border-collapse: collapse;
}
.ab-table thead tr { background: var(--surface2); }
.ab-table th {
    padding: 12px 16px;
    font-size: .68rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .08em;
    color: var(--tx3); text-align: left;
    border-bottom: 1px solid var(--border);
    white-space: nowrap;
}
.ab-table th.sortable { cursor: pointer; user-select: none; }
.ab-table th.sortable:hover { color: var(--tx2); }
.ab-table td {
    padding: 13px 16px;
    font-size: .86rem; color: var(--tx2);
    border-bottom: 1px solid var(--border);
    vertical-align: middle;
}
.ab-table tbody tr:last-child td { border-bottom: none; }
.ab-table tbody tr { transition: background .15s; }
.ab-table tbody tr:hover td { background: rgba(250,247,242,.6); }
[data-theme="dark"] .ab-table tbody tr:hover td { background: rgba(40,39,31,.7); }

/* Book thumb */
.ab-cover {
    width: 38px; height: 56px; border-radius: 6px; flex-shrink: 0;
    box-shadow: 2px 2px 8px rgba(0,0,0,.12); overflow: hidden;
    object-fit: cover;
}
.ab-book-name { font-weight: 600; color: var(--tx); font-size: .86rem; max-width: 220px; }
.ab-book-meta { font-size: .74rem; color: var(--tx3); margin-top: 1px; }

/* Category badge */
.ab-cat {
    display: inline-block;
    font-size: .65rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .05em;
    color: var(--primary); background: rgba(44,95,46,.08);
    padding: 2px 8px; border-radius: 4px;
}
[data-theme="dark"] .ab-cat { background: rgba(74,222,128,.1); }

/* Status badges */
.ab-status {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 4px 10px; border-radius: 100px;
    font-size: .7rem; font-weight: 700;
}
.ab-status-active   { background: rgba(34,197,94,.1);  color: #16a34a; }
.ab-status-inactive { background: rgba(239,68,68,.1);  color: #ef4444; }
[data-theme="dark"] .ab-status-active   { background: rgba(74,222,128,.12); color: #4ADE80; }
[data-theme="dark"] .ab-status-inactive { background: rgba(252,165,165,.12); color: #FCA5A5; }
.ab-status i { font-size: .6rem; }

/* Featured badge */
.ab-featured {
    display: inline-flex; align-items: center; gap: 3px;
    font-size: .65rem; font-weight: 700;
    padding: 2px 7px; border-radius: 4px;
    background: rgba(201,168,76,.12); color: var(--gold);
    margin-top: 3px;
}

/* Views / Rating */
.ab-views  { font-size: .82rem; color: var(--tx2); display: flex; align-items: center; gap: 4px; }
.ab-rating { display: flex; align-items: center; gap: 4px; }
.ab-stars  { color: var(--gold); font-size: 11px; letter-spacing: .5px; }
.ab-rnum   { font-size: .8rem; color: var(--tx2); font-weight: 500; }

/* Action buttons */
.ab-actions { display: flex; gap: 5px; align-items: center; }
.ab-btn {
    width: 32px; height: 32px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    border: 1.5px solid var(--border); background: var(--surface);
    color: var(--tx2); font-size: .88rem; cursor: pointer;
    text-decoration: none;
    transition: all .15s;
}
.ab-btn:hover { background: var(--surface2); color: var(--tx); border-color: var(--border2); }
.ab-btn.view:hover  { border-color: #6366f1; color: #6366f1; background: rgba(99,102,241,.06); }
.ab-btn.edit:hover  { border-color: #f59e0b; color: #f59e0b; background: rgba(245,158,11,.06); }
.ab-btn.del:hover   { border-color: #ef4444; color: #ef4444; background: rgba(239,68,68,.06); }

/* ── Empty state ── */
.ab-empty {
    text-align: center; padding: 52px 20px; color: var(--tx3);
}
.ab-empty-icon {
    width: 64px; height: 64px; border-radius: 18px;
    background: var(--surface2); border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    font-size: 26px; margin: 0 auto 16px;
}
.ab-empty-title { font-family: 'Playfair Display', serif; font-size: 1.1rem; font-weight: 700; color: var(--tx2); margin-bottom: 6px; }

/* ── Pagination ── */
.ab-pagination { margin-top: 20px; display: flex; justify-content: center; }
.ab-pagination nav { display: flex; gap: 6px; align-items: center; }
.ab-pagination .pagination { display: flex; gap: 6px; list-style: none; align-items: center; }
.ab-pagination .page-item .page-link {
    display: flex; align-items: center; justify-content: center;
    min-width: 36px; height: 36px; padding: 0 10px;
    border-radius: 8px; font-size: .85rem; font-weight: 500;
    color: var(--tx2); background: var(--surface);
    border: 1px solid var(--border); text-decoration: none; transition: all .18s;
}
.ab-pagination .page-item .page-link:hover { border-color: var(--primary); color: var(--primary); }
.ab-pagination .page-item.active .page-link { background: var(--primary); color: #fff; border-color: var(--primary); font-weight: 600; }
[data-theme="dark"] .ab-pagination .page-item.active .page-link { color: var(--bg); }
.ab-pagination .page-item.disabled .page-link { opacity: .4; cursor: not-allowed; }

/* Alert */
.ab-alert {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: .87rem;
    animation: abFadeIn .3s both;
}
@keyframes abFadeIn { from{opacity:0;transform:translateY(-6px)} to{opacity:1;transform:translateY(0)} }
.ab-alert-success { background: #E8F5E9; color: #1a4a1c; border: 1px solid #A5D6A7; }
[data-theme="dark"] .ab-alert-success { background: rgba(74,222,128,.09); color: #86EFAC; border-color: rgba(74,222,128,.2); }
</style>
@endpush

@section('content')
<div class="ab-page">

    {{-- Alert --}}
    @if(session('success'))
    <div class="ab-alert ab-alert-success" x-data x-init="setTimeout(()=>$el.remove(),4000)">
        <i class="bi bi-check-circle-fill"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    {{-- Page head --}}
    <div class="ab-head">
        <div class="ab-head-left">
            <div class="ab-page-eyebrow">
                <span class="ab-page-eyebrow-dot"></span>
                Admin Panel
            </div>
            <div class="ab-page-title">Manajemen Buku</div>
        </div>
        <a href="{{ route('admin.books.create') }}" class="ab-create-btn">
            <i class="bi bi-plus-lg"></i>
            Tambah Buku
        </a>
    </div>

    {{-- Quick stats --}}
    <div class="ab-stats">
        <div class="ab-stat">
            <div class="ab-stat-icon" style="background:rgba(44,95,46,.1)">
                <i class="bi bi-journal-richtext" style="color:var(--primary)"></i>
            </div>
            <div>
                <div class="ab-stat-val">{{ number_format($books->total()) }}</div>
                <div class="ab-stat-lbl">Total Buku</div>
            </div>
        </div>
        <div class="ab-stat">
            <div class="ab-stat-icon" style="background:rgba(34,197,94,.1)">
                <i class="bi bi-check-circle" style="color:#16a34a"></i>
            </div>
            <div>
                <div class="ab-stat-val">{{ $books->where('status','active')->count() }}</div>
                <div class="ab-stat-lbl">Aktif</div>
            </div>
        </div>
        <div class="ab-stat">
            <div class="ab-stat-icon" style="background:rgba(201,168,76,.1)">
                <i class="bi bi-star-fill" style="color:var(--gold)"></i>
            </div>
            <div>
                <div class="ab-stat-val">{{ $books->where('is_featured',true)->count() }}</div>
                <div class="ab-stat-lbl">Unggulan</div>
            </div>
        </div>
        <div class="ab-stat">
            <div class="ab-stat-icon" style="background:rgba(99,102,241,.1)">
                <i class="bi bi-eye" style="color:#6366f1"></i>
            </div>
            <div>
                <div class="ab-stat-val">{{ number_format($books->sum('view_count')) }}</div>
                <div class="ab-stat-lbl">Total Views</div>
            </div>
        </div>
    </div>

    {{-- Toolbar --}}
    <form action="{{ route('admin.books.index') }}" method="GET" id="filterForm">
        <div class="ab-toolbar">

            {{-- Search --}}
            <div class="ab-search">
                <span class="ab-search-icon"><i class="bi bi-search" style="font-size:.85rem"></i></span>
                <input type="text" name="search"
                       placeholder="Cari judul, penulis, ISBN…"
                       value="{{ request('search') }}"
                       autocomplete="off">
                @if(request('search'))
                <a href="{{ route('admin.books.index', array_filter(['category'=>request('category'),'sort'=>request('sort')])) }}"
                   style="display:flex;align-items:center;padding:0 10px;color:var(--tx3);font-size:16px;transition:color .15s">×</a>
                @endif
                <button type="submit" class="ab-search-btn">
                    <i class="bi bi-search"></i> Cari
                </button>
            </div>

            {{-- Category filter --}}
            <select name="category" class="ab-select" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
                @endforeach
            </select>

            {{-- Status filter --}}
            <select name="status" class="ab-select" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="active"   {{ request('status')=='active'   ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ request('status')=='inactive' ? 'selected' : '' }}>Nonaktif</option>
            </select>

            {{-- Sort --}}
            <select name="sort" class="ab-select" onchange="this.form.submit()">
                <option value="newest"  {{ request('sort','newest')=='newest'  ? 'selected' : '' }}>Terbaru</option>
                <option value="popular" {{ request('sort')=='popular' ? 'selected' : '' }}>Terpopuler</option>
                <option value="rating"  {{ request('sort')=='rating'  ? 'selected' : '' }}>Rating</option>
                <option value="title"   {{ request('sort')=='title'   ? 'selected' : '' }}>Judul A–Z</option>
            </select>
        </div>

        {{-- Active filters --}}
        @if(request('search') || request('category') || request('status'))
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:14px;flex-wrap:wrap">
            <span style="font-size:.75rem;color:var(--tx3)">Filter aktif:</span>
            @if(request('search'))
            <span class="ab-active-chip">
                Cari: "{{ request('search') }}"
                <a href="{{ route('admin.books.index', array_filter(['category'=>request('category'),'sort'=>request('sort'),'status'=>request('status')])) }}">×</a>
            </span>
            @endif
            @if(request('category'))
            <span class="ab-active-chip">
                Kategori: {{ $categories->where('slug', request('category'))->first()?->name }}
                <a href="{{ route('admin.books.index', array_filter(['search'=>request('search'),'sort'=>request('sort'),'status'=>request('status')])) }}">×</a>
            </span>
            @endif
            @if(request('status'))
            <span class="ab-active-chip">
                Status: {{ request('status') == 'active' ? 'Aktif' : 'Nonaktif' }}
                <a href="{{ route('admin.books.index', array_filter(['search'=>request('search'),'category'=>request('category'),'sort'=>request('sort')])) }}">×</a>
            </span>
            @endif
            <a href="{{ route('admin.books.index') }}" style="font-size:.75rem;color:var(--tx3);font-weight:600">Hapus semua</a>
        </div>
        @endif
    </form>

    {{-- Result info --}}
    <div class="ab-result-info">
        Menampilkan <strong>{{ $books->count() }}</strong> dari <strong>{{ number_format($books->total()) }}</strong> buku
    </div>

    {{-- Table --}}
    <div class="ab-table-card">
        <table class="ab-table">
            <thead>
                <tr>
                    <th style="width:40%">Buku</th>
                    <th>Kategori</th>
                    <th>Status</th>
                    <th class="sortable">
                        <a href="{{ route('admin.books.index', array_merge(request()->query(), ['sort'=>'popular'])) }}"
                           style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:4px">
                            Views
                            @if(request('sort')=='popular') <i class="bi bi-caret-down-fill" style="font-size:.6rem"></i> @endif
                        </a>
                    </th>
                    <th class="sortable">
                        <a href="{{ route('admin.books.index', array_merge(request()->query(), ['sort'=>'rating'])) }}"
                           style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:4px">
                            Rating
                            @if(request('sort')=='rating') <i class="bi bi-caret-down-fill" style="font-size:.6rem"></i> @endif
                        </a>
                    </th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($books as $book)
                <tr>
                    {{-- Book info --}}
                    <td>
                        <div style="display:flex;align-items:center;gap:12px">
                            @if($book->cover_image)
                            <img src="{{ Storage::url($book->cover_image) }}" class="ab-cover" alt="">
                            @else
                            <div class="ab-cover" style="background:linear-gradient(135deg,{{ $book->cover_color ?? '#2C5F2E' }},{{ $book->cover_color_dark ?? '#1d4220' }});flex-shrink:0"></div>
                            @endif
                            <div style="min-width:0">
                                <div class="ab-book-name">{{ Str::limit($book->title, 32) }}</div>
                                <div class="ab-book-meta">{{ $book->author }}</div>
                                @if($book->is_featured)
                                <div class="ab-featured">
                                    <i class="bi bi-star-fill" style="font-size:.55rem"></i> Unggulan
                                </div>
                                @endif
                            </div>
                        </div>
                    </td>

                    {{-- Category --}}
                    <td>
                        <span class="ab-cat">{{ $book->category->name ?? '—' }}</span>
                    </td>

                    {{-- Status --}}
                    <td>
                        <form action="{{ route('admin.books.toggleStatus', $book->id) }}" method="POST" style="display:inline">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    class="ab-status {{ $book->status === 'active' ? 'ab-status-active' : 'ab-status-inactive' }}"
                                    style="background:none;border:none;cursor:pointer;font-family:inherit"
                                    title="Klik untuk toggle status">
                                <i class="bi {{ $book->status === 'active' ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }}"></i>
                                {{ $book->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                            </button>
                        </form>
                    </td>

                    {{-- Views --}}
                    <td>
                        <div class="ab-views">
                            <i class="bi bi-eye" style="color:var(--tx3);font-size:.8rem"></i>
                            {{ number_format($book->view_count ?? 0) }}
                        </div>
                    </td>

                    {{-- Rating --}}
                    <td>
                        <div class="ab-rating">
                            <span class="ab-stars">★</span>
                            <span class="ab-rnum">{{ number_format($book->averageRating(), 1) }}</span>
                            <span style="font-size:.7rem;color:var(--tx3)">({{ $book->ratingsCount() }})</span>
                        </div>
                    </td>

                    {{-- Actions --}}
                    <td>
                        <div class="ab-actions">
                            <a href="{{ route('books.show', $book->slug) }}"
                               class="ab-btn view" title="Lihat" target="_blank">
                                <i class="bi bi-box-arrow-up-right"></i>
                            </a>
                            <a href="{{ route('admin.books.edit', $book->id) }}"
                               class="ab-btn edit" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.books.destroy', $book->id) }}" method="POST"
                                  onsubmit="return confirm('Hapus buku «{{ addslashes($book->title) }}»? Tindakan ini tidak bisa dibatalkan.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="ab-btn del" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="ab-empty">
                            <div class="ab-empty-icon">📭</div>
                            <div class="ab-empty-title">Belum ada buku</div>
                            <div style="font-size:.84rem;margin-bottom:16px">
                                @if(request()->anyFilled(['search','category','status']))
                                    Tidak ada hasil untuk filter yang dipilih.
                                @else
                                    Mulai tambahkan buku pertama ke koleksi.
                                @endif
                            </div>
                            @if(request()->anyFilled(['search','category','status']))
                            <a href="{{ route('admin.books.index') }}"
                               style="display:inline-flex;align-items:center;gap:6px;padding:9px 18px;border-radius:100px;background:var(--surface2);border:1px solid var(--border);font-size:.82rem;font-weight:600;color:var(--tx2);text-decoration:none">
                                <i class="bi bi-x-circle"></i> Hapus Filter
                            </a>
                            @else
                            <a href="{{ route('admin.books.create') }}"
                               style="display:inline-flex;align-items:center;gap:7px;padding:10px 22px;border-radius:100px;background:var(--primary);color:#fff;font-size:.85rem;font-weight:600;text-decoration:none">
                                <i class="bi bi-plus-lg"></i> Tambah Buku Pertama
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="ab-pagination">
        {{ $books->appends(request()->query())->links() }}
    </div>

</div>
@endsection