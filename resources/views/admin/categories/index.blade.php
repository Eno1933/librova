@extends('layouts.admin')

@section('title', 'Manajemen Kategori — Admin Librova')
@section('header-title', 'Manajemen Kategori')

@push('styles')
<style>
    .cat-toolbar {
        display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 24px;
        align-items: center; justify-content: space-between;
    }
    .search-box {
        display: flex; border-radius: 12px; border: 1.5px solid var(--border);
        background: var(--surface); overflow: hidden; transition: border-color .2s; flex: 1; max-width: 340px;
    }
    .search-box:focus-within { border-color: var(--primary); }
    .search-box input {
        flex: 1; padding: 10px 14px; border: none; background: transparent;
        font-family: inherit; font-size: .85rem; color: var(--tx);
    }
    .search-box input::placeholder { color: var(--tx3); }
    .search-box input:focus { outline: none; }
    .search-box button {
        margin: 4px; padding: 0 14px; background: var(--primary); color: #fff;
        border: none; border-radius: 8px; cursor: pointer; font-size: .85rem; font-weight: 600;
    }
    .btn-create {
        padding: 10px 20px; border-radius: 100px; background: var(--primary); color: #fff;
        font-weight: 600; font-size: .85rem; text-decoration: none; display: inline-flex;
        align-items: center; gap: 6px; transition: background .2s; white-space: nowrap;
    }
    [data-theme="dark"] .btn-create { color: var(--bg); }
    .btn-create:hover { background: var(--primary-h); }

    .cat-table {
        width: 100%; border-collapse: collapse; background: var(--surface);
        border: 1px solid var(--border); border-radius: 16px; overflow: hidden;
    }
    .cat-table thead { background: var(--surface2); }
    .cat-table th {
        padding: 14px 16px; font-size: .72rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: .06em; color: var(--tx3); text-align: left;
    }
    .cat-table td {
        padding: 14px 16px; font-size: .88rem; color: var(--tx2); border-bottom: 1px solid var(--border);
        vertical-align: middle;
    }
    .cat-table tr:last-child td { border-bottom: none; }
    .cat-table tbody tr:hover { background: var(--surface2); }
    .action-btns { display: flex; gap: 6px; }
    .btn-icon {
        width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center;
        justify-content: center; border: 1px solid var(--border); background: var(--surface);
        color: var(--tx2); font-size: .9rem; cursor: pointer; transition: all .15s; text-decoration: none;
    }
    .btn-icon:hover { background: var(--surface2); color: var(--tx); border-color: var(--border2); }
    .btn-icon.delete:hover { background: #FEF2F2; color: #B91C1C; border-color: #FECACA; }
    .pagination-wrap { margin-top: 24px; display: flex; justify-content: center; }
    .pagination-wrap nav { display: flex; gap: 6px; }
    .pagination-wrap .page-item .page-link {
        display: flex; align-items: center; justify-content: center;
        min-width: 36px; height: 36px; padding: 0 10px; border-radius: 8px;
        font-size: .85rem; font-weight: 500; color: var(--tx2);
        background: var(--surface); border: 1px solid var(--border); text-decoration: none;
    }
    .pagination-wrap .page-item.active .page-link {
        background: var(--primary); color: #fff; border-color: var(--primary); font-weight: 600;
    }
    [data-theme="dark"] .pagination-wrap .page-item.active .page-link { color: var(--bg); }
    .pagination-wrap .page-item.disabled .page-link { opacity: .4; cursor: not-allowed; }
    .alert {
        padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: .88rem;
    }
    .alert-success { background: #E8F5E9; color: #1a4a1c; border: 1px solid #A5D6A7; }
    [data-theme="dark"] .alert-success { background: rgba(74,222,128,.09); color: #86EFAC; border-color: rgba(74,222,128,.2); }
    .alert-error { background: #FEF2F2; color: #B91C1C; border: 1px solid #FECACA; }
    [data-theme="dark"] .alert-error { background: rgba(252,165,165,.09); color: #FCA5A5; border-color: rgba(252,165,165,.2); }
    .badge-parent {
        font-size: .7rem; font-weight: 600; padding: 2px 8px; border-radius: 4px;
        background: rgba(99,102,241,.1); color: #6366f1;
    }
    [data-theme="dark"] .badge-parent { background: rgba(99,102,241,.15); }
    .icon-preview {
        font-size: 1.2rem; margin-right: 6px; color: var(--tx2);
    }
</style>
@endpush

@section('content')
<div style="padding: 28px 28px 40px;">

    @if(session('success'))
        <div class="alert alert-success"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error"><i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}</div>
    @endif

    <div class="cat-toolbar">
        <form action="{{ route('admin.categories.index') }}" method="GET" style="display:flex;gap:12px;flex:1;flex-wrap:wrap;">
            <div class="search-box">
                <input type="text" name="search" placeholder="Cari kategori…" value="{{ request('search') }}">
                <button type="submit"><i class="bi bi-search"></i></button>
            </div>
        </form>
        <a href="{{ route('admin.categories.create') }}" class="btn-create">
            <i class="bi bi-plus-lg"></i> Tambah Kategori
        </a>
    </div>

    <table class="cat-table">
        <thead>
            <tr>
                <th>Kategori</th>
                <th>Slug</th>
                <th>Induk</th>
                <th>Buku</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:8px;">
                        @if($category->icon)
                            <span class="icon-preview">{{ $category->icon }}</span>
                        @endif
                        <span style="font-weight:600;color:var(--tx)">{{ $category->name }}</span>
                    </div>
                </td>
                <td style="font-size:.82rem;color:var(--tx3)">{{ $category->slug }}</td>
                <td>
                    @if($category->parent)
                        <span class="badge-parent">{{ $category->parent->name }}</span>
                    @else
                        <span style="color:var(--tx3);font-size:.82rem">—</span>
                    @endif
                </td>
                <td style="color:var(--tx2);font-weight:500">{{ number_format($category->books_count) }}</td>
                <td>
                    <div class="action-btns">
                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn-icon" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
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
                <td colspan="5" style="text-align:center;padding:32px;color:var(--tx3);">
                    <i class="bi bi-inbox" style="font-size:2rem;display:block;margin-bottom:8px;"></i>
                    Belum ada kategori.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination-wrap">
        {{ $categories->links() }}
    </div>
</div>
@endsection