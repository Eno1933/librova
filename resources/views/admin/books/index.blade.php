@extends('layouts.admin')

@section('title', 'Manajemen Buku — Admin Librova')
@section('header-title', 'Manajemen Buku')

@push('styles')
<style>
    .books-toolbar {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 24px;
        align-items: center;
        justify-content: space-between;
    }

    .search-box {
        display: flex;
        border-radius: 12px;
        border: 1.5px solid var(--border);
        background: var(--surface);
        overflow: hidden;
        transition: border-color .2s;
        flex: 1;
        max-width: 340px;
    }

    .search-box:focus-within {
        border-color: var(--primary);
    }

    .search-box input {
        flex: 1;
        padding: 10px 14px;
        border: none;
        background: transparent;
        font-family: inherit;
        font-size: .85rem;
        color: var(--tx);
    }

    .search-box input::placeholder {
        color: var(--tx3);
    }

    .search-box input:focus {
        outline: none;
    }

    .search-box button {
        margin: 4px;
        padding: 0 14px;
        background: var(--primary);
        color: #fff;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: .85rem;
        font-weight: 600;
    }

    .filter-select {
        padding: 10px 14px;
        border-radius: 10px;
        border: 1.5px solid var(--border);
        background: var(--surface);
        font-family: inherit;
        font-size: .85rem;
        color: var(--tx2);
        cursor: pointer;
        min-width: 140px;
    }

    .btn-create {
        padding: 10px 20px;
        border-radius: 100px;
        background: var(--primary);
        color: #fff;
        font-weight: 600;
        font-size: .85rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: background .2s;
        white-space: nowrap;
    }

    [data-theme="dark"] .btn-create {
        color: var(--bg);
    }

    .btn-create:hover {
        background: var(--primary-h);
    }

    .books-table {
        width: 100%;
        border-collapse: collapse;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
    }

    .books-table thead {
        background: var(--surface2);
    }

    .books-table th {
        padding: 14px 16px;
        font-size: .72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: var(--tx3);
        text-align: left;
    }

    .books-table td {
        padding: 14px 16px;
        font-size: .88rem;
        color: var(--tx2);
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
    }

    .books-table tr:last-child td {
        border-bottom: none;
    }

    .books-table tbody tr:hover {
        background: var(--surface2);
    }

    .book-cover-mini {
        width: 36px;
        height: 52px;
        border-radius: 6px;
        background: var(--surface2);
        box-shadow: 0 2px 6px rgba(0, 0, 0, .08);
        flex-shrink: 0;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 100px;
        font-size: .72rem;
        font-weight: 600;
    }

    .status-active {
        background: rgba(34, 197, 94, .12);
        color: #16a34a;
    }

    .status-inactive {
        background: rgba(239, 68, 68, .1);
        color: #ef4444;
    }

    [data-theme="dark"] .status-active {
        background: rgba(74, 222, 128, .12);
        color: #4ADE80;
    }

    [data-theme="dark"] .status-inactive {
        background: rgba(252, 165, 165, .12);
        color: #FCA5A5;
    }

    .action-btns {
        display: flex;
        gap: 6px;
    }

    .btn-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--border);
        background: var(--surface);
        color: var(--tx2);
        font-size: .9rem;
        cursor: pointer;
        transition: all .15s;
    }

    .btn-icon:hover {
        background: var(--surface2);
        color: var(--tx);
        border-color: var(--border2);
    }

    .btn-icon.delete:hover {
        background: #FEF2F2;
        color: #B91C1C;
        border-color: #FECACA;
    }

    .pagination-wrap {
        margin-top: 24px;
        display: flex;
        justify-content: center;
    }

    .pagination-wrap nav {
        display: flex;
        gap: 6px;
    }

    .pagination-wrap .page-item .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
        height: 36px;
        padding: 0 10px;
        border-radius: 8px;
        font-size: .85rem;
        font-weight: 500;
        color: var(--tx2);
        background: var(--surface);
        border: 1px solid var(--border);
        text-decoration: none;
    }

    .pagination-wrap .page-item.active .page-link {
        background: var(--primary);
        color: #fff;
        border-color: var(--primary);
        font-weight: 600;
    }

    [data-theme="dark"] .pagination-wrap .page-item.active .page-link {
        color: var(--bg);
    }

    .pagination-wrap .page-item.disabled .page-link {
        opacity: .4;
        cursor: not-allowed;
    }

    .alert {
        padding: 12px 16px;
        border-radius: 10px;
        margin-bottom: 20px;
        font-size: .88rem;
    }

    .alert-success {
        background: #E8F5E9;
        color: #1a4a1c;
        border: 1px solid #A5D6A7;
    }

    [data-theme="dark"] .alert-success {
        background: rgba(74, 222, 128, .09);
        color: #86EFAC;
        border-color: rgba(74, 222, 128, .2);
    }
</style>
@endpush

@section('content')
<div style="padding: 28px 28px 40px;">

    @if(session('success'))
    <div class="alert alert-success"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
    @endif

    <div class="books-toolbar">
        <form action="{{ route('admin.books.index') }}" method="GET" style="display:flex;gap:12px;flex:1;flex-wrap:wrap;">
            <div class="search-box">
                <input type="text" name="search" placeholder="Cari judul, penulis, ISBN…" value="{{ request('search') }}">
                <button type="submit"><i class="bi bi-search"></i></button>
            </div>
            <select name="category" class="filter-select" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <select name="sort" class="filter-select" onchange="this.form.submit()">
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Terpopuler</option>
                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating</option>
                <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Judul A-Z</option>
            </select>
        </form>
        <a href="{{ route('admin.books.create') }}" class="btn-create">
            <i class="bi bi-plus-lg"></i> Tambah Buku
        </a>
    </div>

    <table class="books-table">
        <thead>
            <tr>
                <th>Buku</th>
                <th>Kategori</th>
                <th>Status</th>
                <th>Views</th>
                <th>Rating</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($books as $book)
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:12px;">
                        @if($book->cover_image)
                        <img src="{{ Storage::url($book->cover_image) }}" class="book-cover-mini" style="object-fit:cover;">
                        @else
                        <div class="book-cover-mini" style="background:linear-gradient(135deg, {{ $book->cover_color ?? '#2C5F2E' }}, {{ $book->cover_color_dark ?? '#1d4220' }})"></div>
                        @endif
                        <div>
                            <div style="font-weight:600;color:var(--tx);">{{ Str::limit($book->title, 30) }}</div>
                            <div style="font-size:.76rem;color:var(--tx3)">{{ $book->author }}</div>
                        </div>
                    </div>
                </td>
                <td><span class="tbl-cat" style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--primary);background:rgba(44,95,46,.08);padding:2px 8px;border-radius:4px;">{{ $book->category->name ?? '-' }}</span></td>
                <td>
                    <span class="status-badge {{ $book->status === 'active' ? 'status-active' : 'status-inactive' }}">
                        <i class="bi {{ $book->status === 'active' ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }}" style="font-size:.65rem;"></i>
                        {{ $book->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td><i class="bi bi-eye" style="font-size:.7rem;color:var(--tx3);margin-right:4px;"></i> {{ number_format($book->view_count) }}</td>
                <td>
                    <i class="bi bi-star-fill" style="color:var(--gold);font-size:.7rem;"></i>
                    {{ number_format($book->averageRating(), 1) }}
                </td>
                <td>
                    <div class="action-btns">
                        <a href="{{ route('admin.books.edit', $book->id) }}" class="btn-icon" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.books.destroy', $book->id) }}" method="POST" onsubmit="return confirm('Hapus buku ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-icon delete" title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;padding:32px;color:var(--tx3);">
                    <i class="bi bi-inbox" style="font-size:2rem;display:block;margin-bottom:8px;"></i>
                    Belum ada buku.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination-wrap">
        {{ $books->links() }}
    </div>
</div>
@endsection