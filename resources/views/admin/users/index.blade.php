@extends('layouts.admin')

@section('title', 'Manajemen User — Admin Librova')
@section('header-title', 'Manajemen User')

@push('styles')
<style>
    .user-toolbar {
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
    .filter-select {
        padding: 9px 14px; border-radius: 10px; border: 1.5px solid var(--border);
        background: var(--surface); font-family: inherit; font-size: .85rem; color: var(--tx2);
        cursor: pointer; min-width: 140px;
    }
    .user-table {
        width: 100%; border-collapse: collapse; background: var(--surface);
        border: 1px solid var(--border); border-radius: 16px; overflow: hidden;
    }
    .user-table thead { background: var(--surface2); }
    .user-table th {
        padding: 14px 16px; font-size: .72rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: .06em; color: var(--tx3); text-align: left;
    }
    .user-table td {
        padding: 14px 16px; font-size: .88rem; color: var(--tx2); border-bottom: 1px solid var(--border);
        vertical-align: middle;
    }
    .user-table tr:last-child td { border-bottom: none; }
    .user-table tbody tr:hover { background: var(--surface2); }
    .user-avatar-mini {
        width: 36px; height: 36px; border-radius: 50%;
        background: var(--surface2); display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: .85rem; color: var(--primary); flex-shrink: 0;
    }
    .status-badge {
        display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px;
        border-radius: 100px; font-size: .72rem; font-weight: 600;
    }
    .status-active { background: rgba(34,197,94,.12); color: #16a34a; }
    .status-suspended { background: rgba(239,68,68,.1); color: #ef4444; }
    [data-theme="dark"] .status-active { background: rgba(74,222,128,.12); color: #4ADE80; }
    [data-theme="dark"] .status-suspended { background: rgba(252,165,165,.12); color: #FCA5A5; }
    .role-badge {
        font-size: .7rem; font-weight: 600; padding: 2px 8px; border-radius: 4px;
        background: rgba(99,102,241,.1); color: #6366f1;
    }
    [data-theme="dark"] .role-badge { background: rgba(99,102,241,.15); }
    .action-btns { display: flex; gap: 6px; }
    .btn-icon {
        width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center;
        justify-content: center; border: 1px solid var(--border); background: var(--surface);
        color: var(--tx2); font-size: .9rem; cursor: pointer; transition: all .15s;
    }
    .btn-icon:hover { background: var(--surface2); color: var(--tx); border-color: var(--border2); }
    .btn-icon.suspend { color: #ef4444; border-color: #FECACA; }
    .btn-icon.suspend:hover { background: #FEF2F2; }
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
</style>
@endpush

@section('content')
<div style="padding: 28px 28px 40px;">

    @if(session('success'))
        <div class="alert alert-success" style="padding:12px 16px; border-radius:10px; margin-bottom:20px; background:#E8F5E9; color:#1a4a1c; border:1px solid #A5D6A7; font-size:.88rem;">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-error" style="padding:12px 16px; border-radius:10px; margin-bottom:20px; background:#FEF2F2; color:#B91C1C; border:1px solid #FECACA; font-size:.88rem;">
            <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
        </div>
    @endif

    <div class="user-toolbar">
        <form action="{{ route('admin.users.index') }}" method="GET" style="display:flex;gap:12px;flex:1;flex-wrap:wrap;">
            <div class="search-box">
                <input type="text" name="search" placeholder="Cari nama atau email…" value="{{ request('search') }}">
                <button type="submit"><i class="bi bi-search"></i></button>
            </div>
            <select name="role" class="filter-select" onchange="this.form.submit()">
                <option value="">Semua Role</option>
                <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </form>
    </div>

    <table class="user-table">
        <thead>
            <tr>
                <th>Pengguna</th>
                <th>Role</th>
                <th>Status</th>
                <th>Bergabung</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div class="user-avatar-mini">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                        <div>
                            <div style="font-weight:600;color:var(--tx)">{{ $user->name }}</div>
                            <div style="font-size:.76rem;color:var(--tx3)">{{ $user->email }}</div>
                        </div>
                    </div>
                </td>
                <td><span class="role-badge">{{ $user->role === 'admin' ? 'Admin' : 'User' }}</span></td>
                <td>
                    @if($user->suspended_at)
                        <span class="status-badge status-suspended">
                            <i class="bi bi-x-circle-fill" style="font-size:.65rem;"></i> Dinonaktifkan
                        </span>
                    @else
                        <span class="status-badge status-active">
                            <i class="bi bi-check-circle-fill" style="font-size:.65rem;"></i> Aktif
                        </span>
                    @endif
                </td>
                <td style="font-size:.82rem;color:var(--tx3)">{{ $user->created_at->translatedFormat('d M Y') }}</td>
                <td>
                    <form action="{{ route('admin.users.toggle-suspend', $user->id) }}" method="POST" onsubmit="return confirm('{{ $user->suspended_at ? 'Aktifkan kembali akun ini?' : 'Nonaktifkan akun ini?' }}')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn-icon {{ $user->suspended_at ? '' : 'suspend' }}" title="{{ $user->suspended_at ? 'Aktifkan' : 'Nonaktifkan' }}">
                            <i class="bi {{ $user->suspended_at ? 'bi-unlock' : 'bi-lock' }}"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center;padding:32px;color:var(--tx3);">
                    <i class="bi bi-inbox" style="font-size:2rem;display:block;margin-bottom:8px;"></i>
                    Belum ada pengguna.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination-wrap">
        {{ $users->links() }}
    </div>
</div>
@endsection