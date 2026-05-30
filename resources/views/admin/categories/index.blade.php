@extends('layouts.admin')

@section('title', 'Manajemen Kategori — Admin Librova')
@section('header-title', 'Manajemen Kategori')
@section('breadcrumb', 'Manajemen Kategori')

@push('styles')
<style>
/* ═══════════════════════════════════════════
   ADMIN CATEGORIES INDEX
═══════════════════════════════════════════ */
.ac-page { padding: 28px 28px 48px; }

/* ── Page head ── */
.ac-head {
    display: flex; align-items: flex-end; justify-content: space-between;
    gap: 16px; flex-wrap: wrap; margin-bottom: 24px;
}
.ac-page-eyebrow {
    font-size: .7rem; font-weight: 700; letter-spacing: .08em;
    text-transform: uppercase; color: var(--tx3); margin-bottom: 5px;
    display: flex; align-items: center; gap: 6px;
}
.ac-eyebrow-dot { width: 5px; height: 5px; border-radius: 50%; background: var(--primary); }
.ac-page-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.6rem; font-weight: 700; letter-spacing: -.02em; color: var(--tx);
}
.ac-create-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 10px 22px; border-radius: 100px;
    background: var(--primary); color: #fff;
    font-family: inherit; font-size: .85rem; font-weight: 600;
    text-decoration: none; white-space: nowrap;
    transition: background .2s, transform .15s, box-shadow .2s;
    box-shadow: 0 3px 12px var(--shadow);
}
[data-theme="dark"] .ac-create-btn { color: var(--bg); }
.ac-create-btn:hover { background: var(--primary-h); transform: translateY(-1px); box-shadow: 0 6px 20px var(--shadow); }

/* ── Quick stats ── */
.ac-stats { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 24px; }
.ac-stat {
    display: flex; align-items: center; gap: 8px;
    padding: 9px 16px; border-radius: 10px;
    background: var(--surface); border: 1px solid var(--border);
    font-size: .82rem; transition: border-color .2s;
}
.ac-stat:hover { border-color: var(--border2); }
.ac-stat-icon {
    width: 28px; height: 28px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center; font-size: 13px;
}
.ac-stat-val { font-family: 'Playfair Display', serif; font-size: 1.05rem; font-weight: 700; color: var(--tx); }
.ac-stat-lbl { font-size: .7rem; color: var(--tx3); font-weight: 500; }

/* ── Toolbar ── */
.ac-toolbar { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; margin-bottom: 16px; }
.ac-search {
    display: flex; border-radius: 10px;
    border: 1.5px solid var(--border); background: var(--surface);
    overflow: hidden; flex: 1; max-width: 340px;
    transition: border-color .2s, box-shadow .2s;
}
.ac-search:focus-within {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(44,95,46,.09);
}
[data-theme="dark"] .ac-search:focus-within { box-shadow: 0 0 0 3px rgba(74,222,128,.09); }
.ac-search-icon { display: flex; align-items: center; padding: 0 11px; color: var(--tx3); flex-shrink: 0; }
.ac-search input {
    flex: 1; padding: 10px 6px; border: none; background: transparent;
    font-family: inherit; font-size: .84rem; color: var(--tx); min-width: 0;
}
.ac-search input::placeholder { color: var(--tx3); }
.ac-search input:focus { outline: none; }
.ac-search-btn {
    margin: 4px; padding: 0 14px; border-radius: 7px;
    background: var(--primary); color: #fff; border: none;
    cursor: pointer; font-family: inherit; font-size: .82rem; font-weight: 600;
    display: flex; align-items: center; gap: 5px; transition: background .2s;
}
[data-theme="dark"] .ac-search-btn { color: var(--bg); }
.ac-search-btn:hover { background: var(--primary-h); }
.ac-select {
    padding: 10px 28px 10px 12px; border-radius: 10px;
    border: 1.5px solid var(--border); background: var(--surface);
    font-family: inherit; font-size: .83rem; color: var(--tx2);
    appearance: none; cursor: pointer; min-width: 150px;
    background-image: url("data:image/svg+xml,%3Csvg width='12' height='8' viewBox='0 0 12 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%239A9282' stroke-width='1.5' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 10px center;
    transition: border-color .2s;
}
.ac-select:focus { outline: none; border-color: var(--primary); }

/* Result info */
.ac-result-info { font-size: .8rem; color: var(--tx3); margin-bottom: 14px; }
.ac-result-info strong { color: var(--tx2); }

/* ── Two-column layout ── */
.ac-layout { display: grid; grid-template-columns: 1fr 300px; gap: 20px; align-items: start; }

/* ── Table card ── */
.ac-table-card {
    background: var(--surface); border: 1px solid var(--border);
    border-radius: 16px; overflow: hidden;
}
.ac-table { width: 100%; border-collapse: collapse; }
.ac-table thead tr { background: var(--surface2); }
.ac-table th {
    padding: 12px 16px; font-size: .68rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .08em;
    color: var(--tx3); text-align: left; border-bottom: 1px solid var(--border);
    white-space: nowrap;
}
.ac-table td {
    padding: 13px 16px; font-size: .86rem; color: var(--tx2);
    border-bottom: 1px solid var(--border); vertical-align: middle;
}
.ac-table tbody tr:last-child td { border-bottom: none; }
.ac-table tbody tr { transition: background .15s; }
.ac-table tbody tr:hover td { background: rgba(250,247,242,.6); }
[data-theme="dark"] .ac-table tbody tr:hover td { background: rgba(40,39,31,.7); }

/* Category icon + name */
.ac-cat-cell { display: flex; align-items: center; gap: 10px; }
.ac-icon-box {
    width: 38px; height: 38px; border-radius: 11px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px;
    background: rgba(44,95,46,.08);
    transition: transform .25s cubic-bezier(.34,1.56,.64,1);
}
.ac-table tbody tr:hover .ac-icon-box { transform: scale(1.1) rotate(-5deg); }
[data-theme="dark"] .ac-icon-box { background: rgba(74,222,128,.08); }
.ac-cat-name { font-weight: 600; color: var(--tx); font-size: .88rem; }
.ac-cat-desc { font-size: .74rem; color: var(--tx3); margin-top: 1px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px; }

/* Slug */
.ac-slug {
    font-family: 'Courier New', monospace;
    font-size: .74rem; color: var(--tx3);
    background: var(--surface2); padding: 2px 8px; border-radius: 5px;
    border: 1px solid var(--border);
}

/* Parent badge */
.ac-parent-badge {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: .7rem; font-weight: 600; padding: 3px 9px; border-radius: 5px;
    background: rgba(99,102,241,.09); color: #6366f1;
}
[data-theme="dark"] .ac-parent-badge { background: rgba(99,102,241,.14); }

/* Book count */
.ac-book-count {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: .82rem; font-weight: 600; color: var(--tx2);
}
.ac-book-count i { font-size: .8rem; color: var(--tx3); }

/* Sub-count */
.ac-sub-count { font-size: .72rem; color: var(--tx3); margin-top: 1px; }

/* Action buttons */
.ac-actions { display: flex; gap: 5px; align-items: center; }
.ac-btn {
    width: 32px; height: 32px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    border: 1.5px solid var(--border); background: var(--surface);
    color: var(--tx2); font-size: .88rem; cursor: pointer;
    text-decoration: none; transition: all .15s;
}
.ac-btn:hover      { background: var(--surface2); color: var(--tx); border-color: var(--border2); }
.ac-btn.view:hover { border-color: #6366f1; color: #6366f1; background: rgba(99,102,241,.06); }
.ac-btn.edit:hover { border-color: #f59e0b; color: #f59e0b; background: rgba(245,158,11,.06); }
.ac-btn.del:hover  { border-color: #ef4444; color: #ef4444; background: rgba(239,68,68,.06); }

/* ── Quick Add Panel (right column) ── */
.ac-quick-panel {
    background: var(--surface); border: 1px solid var(--border);
    border-radius: 16px; overflow: hidden;
    position: sticky; top: calc(var(--nav-h, 68px) + 16px);
}
.ac-qp-head {
    display: flex; align-items: center; gap: 9px;
    padding: 16px 20px; border-bottom: 1px solid var(--border);
}
.ac-qp-title {
    font-family: 'Playfair Display', serif;
    font-size: 1rem; font-weight: 700; color: var(--tx);
}
.ac-qp-icon {
    width: 30px; height: 30px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center; font-size: 14px;
    background: rgba(44,95,46,.09); color: var(--primary);
}
.ac-qp-body { padding: 18px 20px; }
.ac-f-label {
    display: block; margin-bottom: 6px;
    font-size: .75rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .05em; color: var(--tx2);
}
.ac-f-input {
    width: 100%; padding: 10px 13px; border-radius: 9px;
    border: 1.5px solid var(--border); background: var(--bg);
    font-family: inherit; font-size: .87rem; color: var(--tx);
    margin-bottom: 13px;
    transition: border-color .2s, box-shadow .2s;
}
.ac-f-input:focus {
    outline: none; border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(44,95,46,.09);
}
[data-theme="dark"] .ac-f-input:focus { box-shadow: 0 0 0 3px rgba(74,222,128,.09); }
.ac-f-input::placeholder { color: var(--tx3); }
.ac-icon-picker {
    display: grid; grid-template-columns: repeat(6, 1fr); gap: 5px; margin-bottom: 13px;
}
.ac-icon-opt {
    width: 36px; height: 36px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center; font-size: 16px;
    background: var(--surface2); border: 1.5px solid transparent;
    cursor: pointer; transition: border-color .15s, background .15s;
}
.ac-icon-opt:hover, .ac-icon-opt.selected {
    border-color: var(--primary); background: rgba(44,95,46,.07);
}
[data-theme="dark"] .ac-icon-opt:hover, [data-theme="dark"] .ac-icon-opt.selected {
    background: rgba(74,222,128,.08);
}
.ac-submit-btn {
    width: 100%; padding: 11px; border-radius: 10px;
    background: var(--primary); color: #fff;
    font-family: inherit; font-size: .87rem; font-weight: 600;
    border: none; cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 7px;
    transition: background .2s, transform .15s;
}
[data-theme="dark"] .ac-submit-btn { color: var(--bg); }
.ac-submit-btn:hover { background: var(--primary-h); transform: translateY(-1px); }

/* ── Empty state ── */
.ac-empty { text-align: center; padding: 48px 20px; }
.ac-empty-icon {
    width: 64px; height: 64px; border-radius: 18px;
    background: var(--surface2); border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    font-size: 26px; margin: 0 auto 14px;
}
.ac-empty-title { font-family: 'Playfair Display', serif; font-size: 1.05rem; font-weight: 700; color: var(--tx2); margin-bottom: 6px; }

/* ── Pagination ── */
.ac-pagination { margin-top: 18px; display: flex; justify-content: center; }
.ac-pagination nav { display: flex; gap: 6px; align-items: center; }
.ac-pagination .pagination { display: flex; gap: 6px; list-style: none; align-items: center; }
.ac-pagination .page-item .page-link {
    display: flex; align-items: center; justify-content: center;
    min-width: 36px; height: 36px; padding: 0 10px; border-radius: 8px;
    font-size: .85rem; font-weight: 500; color: var(--tx2);
    background: var(--surface); border: 1px solid var(--border);
    text-decoration: none; transition: all .18s;
}
.ac-pagination .page-item .page-link:hover { border-color: var(--primary); color: var(--primary); }
.ac-pagination .page-item.active .page-link { background: var(--primary); color: #fff; border-color: var(--primary); font-weight: 600; }
[data-theme="dark"] .ac-pagination .page-item.active .page-link { color: var(--bg); }
.ac-pagination .page-item.disabled .page-link { opacity: .4; cursor: not-allowed; }

/* Alert */
.ac-alert {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: .87rem;
    animation: acIn .3s both;
}
@keyframes acIn { from{opacity:0;transform:translateY(-6px)} to{opacity:1;transform:translateY(0)} }
.ac-alert-success { background: #E8F5E9; color: #1a4a1c; border: 1px solid #A5D6A7; }
.ac-alert-error   { background: #FEF2F2; color: #B91C1C; border: 1px solid #FECACA; }
[data-theme="dark"] .ac-alert-success { background: rgba(74,222,128,.09); color: #86EFAC; border-color: rgba(74,222,128,.2); }
[data-theme="dark"] .ac-alert-error   { background: rgba(252,165,165,.09); color: #FCA5A5; border-color: rgba(252,165,165,.2); }

/* ═══════════════════════════════════════════
   RESPONSIVE
═══════════════════════════════════════════ */
@media(max-width: 1024px) {
    .ac-layout {
        grid-template-columns: 1fr;          /* tabel & panel jadi satu kolom */
    }
    .ac-quick-panel {
        position: static;                    /* tidak sticky lagi */
        margin-top: 16px;
    }
    /* Sembunyikan kolom Slug dan Induk di layar sedang */
    .ac-table th:nth-child(2),
    .ac-table td:nth-child(2),
    .ac-table th:nth-child(3),
    .ac-table td:nth-child(3) {
        display: none;
    }
}

@media(max-width: 640px) {
    .ac-page {
        padding: 20px 16px 40px;
    }
    .ac-head {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }
    .ac-create-btn {
        width: 100%;
        justify-content: center;
    }
    .ac-toolbar {
        flex-direction: column;
        align-items: stretch;
    }
    .ac-search {
        max-width: 100%;
    }
    .ac-select {
        width: 100%;
    }
    /* Sembunyikan kolom Buku dan Sub di layar kecil */
    .ac-table th:nth-child(4),
    .ac-table td:nth-child(4),
    .ac-table th:nth-child(5),
    .ac-table td:nth-child(5) {
        display: none;
    }
    .ac-icon-picker {
        grid-template-columns: repeat(4, 1fr); /* ikon lebih besar */
    }
    .ac-quick-panel {
        margin-top: 16px;
    }
}
</style>
@endpush

@section('content')
<div class="ac-page">

    {{-- Alerts --}}
    @if(session('success'))
    <div class="ac-alert ac-alert-success" x-data x-init="setTimeout(()=>$el.remove(),4000)">
        <i class="bi bi-check-circle-fill"></i><span>{{ session('success') }}</span>
    </div>
    @endif
    @if(session('error'))
    <div class="ac-alert ac-alert-error">
        <i class="bi bi-exclamation-triangle-fill"></i><span>{{ session('error') }}</span>
    </div>
    @endif

    {{-- Page head --}}
    <div class="ac-head">
        <div>
            <div class="ac-page-eyebrow"><span class="ac-eyebrow-dot"></span>Admin Panel</div>
            <div class="ac-page-title">Manajemen Kategori</div>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="ac-create-btn">
            <i class="bi bi-plus-lg"></i> Tambah Kategori
        </a>
    </div>

    {{-- Quick stats --}}
    <div class="ac-stats">
        <div class="ac-stat">
            <div class="ac-stat-icon" style="background:rgba(44,95,46,.1)">
                <i class="bi bi-grid-1x2" style="color:var(--primary)"></i>
            </div>
            <div>
                <div class="ac-stat-val">{{ $categories->total() }}</div>
                <div class="ac-stat-lbl">Total Kategori</div>
            </div>
        </div>
        <div class="ac-stat">
            <div class="ac-stat-icon" style="background:rgba(99,102,241,.1)">
                <i class="bi bi-diagram-3" style="color:#6366f1"></i>
            </div>
            <div>
                <div class="ac-stat-val">{{ $categories->where('parent_id', null)->count() }}</div>
                <div class="ac-stat-lbl">Kategori Utama</div>
            </div>
        </div>
        <div class="ac-stat">
            <div class="ac-stat-icon" style="background:rgba(245,158,11,.1)">
                <i class="bi bi-collection" style="color:#f59e0b"></i>
            </div>
            <div>
                <div class="ac-stat-val">{{ number_format($categories->sum('books_count')) }}</div>
                <div class="ac-stat-lbl">Total Buku</div>
            </div>
        </div>
    </div>

    {{-- Toolbar --}}
    <form action="{{ route('admin.categories.index') }}" method="GET" id="filterForm">
        <div class="ac-toolbar">
            <div class="ac-search">
                <span class="ac-search-icon"><i class="bi bi-search" style="font-size:.85rem"></i></span>
                <input type="text" name="search"
                       placeholder="Cari nama kategori…"
                       value="{{ request('search') }}"
                       autocomplete="off">
                @if(request('search'))
                <a href="{{ route('admin.categories.index') }}"
                   style="display:flex;align-items:center;padding:0 10px;color:var(--tx3);font-size:16px;transition:color .15s">×</a>
                @endif
                <button type="submit" class="ac-search-btn">
                    <i class="bi bi-search"></i> Cari
                </button>
            </div>
            <select name="type" class="ac-select" onchange="this.form.submit()">
                <option value="">Semua Tipe</option>
                <option value="parent" {{ request('type')=='parent' ? 'selected':'' }}>Kategori Utama</option>
                <option value="child"  {{ request('type')=='child'  ? 'selected':'' }}>Sub-Kategori</option>
            </select>
            <select name="sort" class="ac-select" onchange="this.form.submit()">
                <option value="newest" {{ request('sort','newest')=='newest' ? 'selected':'' }}>Terbaru</option>
                <option value="name"   {{ request('sort')=='name'   ? 'selected':'' }}>Nama A–Z</option>
                <option value="books"  {{ request('sort')=='books'  ? 'selected':'' }}>Terbanyak Buku</option>
            </select>
        </div>
    </form>

    {{-- Result info --}}
    <div class="ac-result-info">
        Menampilkan <strong>{{ $categories->count() }}</strong> dari <strong>{{ $categories->total() }}</strong> kategori
    </div>

    {{-- Two-column layout: table + quick-add panel --}}
    <div class="ac-layout">

        {{-- Table --}}
        <div>
            <div class="ac-table-card">
                <table class="ac-table">
                    <thead>
                        <tr>
                            <th style="width:36%">Kategori</th>
                            <th>Slug</th>
                            <th>Induk</th>
                            <th>Buku</th>
                            <th>Sub</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                        <tr>
                            {{-- Category name + icon --}}
                            <td>
                                <div class="ac-cat-cell">
                                    <div class="ac-icon-box">{{ $category->icon ?? '📂' }}</div>
                                    <div style="min-width:0">
                                        <div class="ac-cat-name">{{ $category->name }}</div>
                                        @if($category->description)
                                        <div class="ac-cat-desc">{{ $category->description }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Slug --}}
                            <td><span class="ac-slug">{{ $category->slug }}</span></td>

                            {{-- Parent --}}
                            <td>
                                @if($category->parent)
                                <span class="ac-parent-badge">
                                    <i class="bi bi-diagram-2" style="font-size:.6rem"></i>
                                    {{ $category->parent->name }}
                                </span>
                                @else
                                <span style="color:var(--tx3);font-size:.8rem;font-style:italic">Utama</span>
                                @endif
                            </td>

                            {{-- Book count --}}
                            <td>
                                <span class="ac-book-count">
                                    <i class="bi bi-journal-richtext"></i>
                                    {{ number_format($category->books_count ?? 0) }}
                                </span>
                            </td>

                            {{-- Sub-category count --}}
                            <td>
                                @if($category->children_count > 0)
                                <span style="font-size:.78rem;color:var(--tx2);font-weight:600">
                                    {{ $category->children_count }}
                                </span>
                                @else
                                <span style="color:var(--tx3);font-size:.78rem">—</span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td>
                                <div class="ac-actions">
                                    <a href="{{ route('categories.show', $category->slug) }}"
                                       class="ac-btn view" title="Lihat di frontend" target="_blank">
                                        <i class="bi bi-box-arrow-up-right"></i>
                                    </a>
                                    <a href="{{ route('admin.categories.edit', $category->id) }}"
                                       class="ac-btn edit" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST"
                                          onsubmit="return confirm('Hapus kategori «{{ addslashes($category->name) }}»? Semua buku di kategori ini akan kehilangan kategorinya.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="ac-btn del" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="ac-empty">
                                    <div class="ac-empty-icon">📂</div>
                                    <div class="ac-empty-title">
                                        {{ request('search') ? 'Tidak ada hasil' : 'Belum ada kategori' }}
                                    </div>
                                    <div style="font-size:.84rem;color:var(--tx3);margin-bottom:12px">
                                        {{ request('search') ? 'Coba kata kunci lain.' : 'Tambahkan kategori pertama menggunakan form di samping.' }}
                                    </div>
                                    @if(request('search'))
                                    <a href="{{ route('admin.categories.index') }}"
                                       style="display:inline-flex;align-items:center;gap:6px;padding:9px 18px;border-radius:100px;background:var(--surface2);border:1px solid var(--border);font-size:.82rem;font-weight:600;color:var(--tx2);text-decoration:none">
                                        <i class="bi bi-x-circle"></i> Hapus Filter
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
            <div class="ac-pagination">
                {{ $categories->appends(request()->query())->links() }}
            </div>
        </div>

        {{-- Quick Add Panel --}}
        <div class="ac-quick-panel">
            <div class="ac-qp-head">
                <div class="ac-qp-icon"><i class="bi bi-plus-lg"></i></div>
                <div class="ac-qp-title">Tambah Cepat</div>
            </div>
            <div class="ac-qp-body">
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf

                    {{-- Name --}}
                    <label class="ac-f-label">Nama Kategori</label>
                    <input type="text" name="name" class="ac-f-input"
                           placeholder="mis. Fiksi Ilmiah"
                           value="{{ old('name') }}" required>

                    {{-- Icon picker --}}
                    <label class="ac-f-label">Ikon</label>
                    <div class="ac-icon-picker" id="iconPicker">
                        @foreach(['📖','🔬','💼','💻','🧠','🌱','🏛️','🎨','📐','❤️','🎓','📚','🌍','🎭','💡','🏆','🔥','⚡'] as $em)
                        <div class="ac-icon-opt {{ old('icon') == $em ? 'selected' : '' }}"
                             data-icon="{{ $em }}"
                             onclick="selectIcon(this, '{{ $em }}')">{{ $em }}</div>
                        @endforeach
                    </div>
                    <input type="hidden" name="icon" id="iconInput" value="{{ old('icon', '📂') }}">

                    {{-- Parent --}}
                    <label class="ac-f-label">Kategori Induk (opsional)</label>
                    <select name="parent_id" class="ac-f-input" style="padding:10px 13px">
                        <option value="">— Kategori Utama —</option>
                        @foreach($allParents ?? $categories->where('parent_id', null) as $parent)
                        <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                            {{ $parent->icon ?? '📂' }} {{ $parent->name }}
                        </option>
                        @endforeach
                    </select>

                    {{-- Description --}}
                    <label class="ac-f-label">Deskripsi (opsional)</label>
                    <textarea name="description" class="ac-f-input" rows="2"
                              placeholder="Deskripsi singkat kategori…"
                              style="resize:vertical;min-height:60px">{{ old('description') }}</textarea>

                    <button type="submit" class="ac-submit-btn">
                        <i class="bi bi-plus-lg"></i> Tambah Kategori
                    </button>
                </form>

                {{-- Divider --}}
                <div style="height:1px;background:var(--border);margin:18px 0"></div>

                {{-- Quick link to full form --}}
                <a href="{{ route('admin.categories.create') }}"
                   style="display:flex;align-items:center;justify-content:center;gap:6px;width:100%;padding:10px;border-radius:10px;border:1.5px dashed var(--border);font-size:.82rem;font-weight:500;color:var(--tx3);text-decoration:none;transition:border-color .2s,color .2s">
                    <i class="bi bi-arrows-fullscreen"></i>
                    Form Lengkap
                </a>
            </div>
        </div>

    </div>{{-- /ac-layout --}}
</div>
@endsection

@push('scripts')
<script>
function selectIcon(el, icon) {
    document.querySelectorAll('.ac-icon-opt').forEach(o => o.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('iconInput').value = icon;
}
</script>
@endpush