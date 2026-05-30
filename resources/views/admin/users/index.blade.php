@extends('layouts.admin')

@section('title', 'Manajemen User — Admin Librova')
@section('header-title', 'Manajemen User')
@section('breadcrumb', 'Manajemen User')

@push('styles')
<style>
/* ═══════════════════════════════════════════
   ADMIN USERS INDEX
═══════════════════════════════════════════ */
.au-page { padding: 28px 28px 48px; }

/* ── Page head ── */
.au-head {
    display: flex; align-items: flex-end; justify-content: space-between;
    gap: 16px; flex-wrap: wrap; margin-bottom: 24px;
}
.au-page-eyebrow {
    font-size: .7rem; font-weight: 700; letter-spacing: .08em;
    text-transform: uppercase; color: var(--tx3); margin-bottom: 5px;
    display: flex; align-items: center; gap: 6px;
}
.au-eyebrow-dot { width: 5px; height: 5px; border-radius: 50%; background: var(--primary); }
.au-page-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.6rem; font-weight: 700; letter-spacing: -.02em; color: var(--tx);
}

/* ── Quick stats ── */
.au-stats { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 24px; }
.au-stat {
    display: flex; align-items: center; gap: 8px;
    padding: 9px 16px; border-radius: 10px;
    background: var(--surface); border: 1px solid var(--border);
    font-size: .82rem; transition: border-color .2s;
}
.au-stat:hover { border-color: var(--border2); }
.au-stat-icon {
    width: 28px; height: 28px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center; font-size: 13px;
}
.au-stat-val { font-family: 'Playfair Display', serif; font-size: 1.05rem; font-weight: 700; color: var(--tx); }
.au-stat-lbl { font-size: .7rem; color: var(--tx3); font-weight: 500; }

/* ── Toolbar ── */
.au-toolbar {
    display: flex; align-items: center; gap: 10px;
    flex-wrap: wrap; margin-bottom: 16px;
}
.au-search {
    display: flex; border-radius: 10px;
    border: 1.5px solid var(--border); background: var(--surface);
    overflow: hidden; flex: 1; max-width: 340px;
    transition: border-color .2s, box-shadow .2s;
}
.au-search:focus-within {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(44,95,46,.09);
}
[data-theme="dark"] .au-search:focus-within { box-shadow: 0 0 0 3px rgba(74,222,128,.09); }
.au-search-icon { display: flex; align-items: center; padding: 0 11px; color: var(--tx3); flex-shrink: 0; }
.au-search input {
    flex: 1; padding: 10px 6px; border: none; background: transparent;
    font-family: inherit; font-size: .84rem; color: var(--tx); min-width: 0;
}
.au-search input::placeholder { color: var(--tx3); }
.au-search input:focus { outline: none; }
.au-search-btn {
    margin: 4px; padding: 0 14px; border-radius: 7px;
    background: var(--primary); color: #fff; border: none;
    cursor: pointer; font-family: inherit; font-size: .82rem; font-weight: 600;
    display: flex; align-items: center; gap: 5px; transition: background .2s;
}
[data-theme="dark"] .au-search-btn { color: var(--bg); }
.au-search-btn:hover { background: var(--primary-h); }

.au-select {
    padding: 10px 28px 10px 12px; border-radius: 10px;
    border: 1.5px solid var(--border); background: var(--surface);
    font-family: inherit; font-size: .83rem; color: var(--tx2);
    appearance: none; cursor: pointer; min-width: 130px;
    background-image: url("data:image/svg+xml,%3Csvg width='12' height='8' viewBox='0 0 12 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%239A9282' stroke-width='1.5' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 10px center;
    transition: border-color .2s;
}
.au-select:focus { outline: none; border-color: var(--primary); }

/* Result info */
.au-result-info { font-size: .8rem; color: var(--tx3); margin-bottom: 14px; }
.au-result-info strong { color: var(--tx2); }

/* ── Table card ── */
.au-table-card {
    background: var(--surface); border: 1px solid var(--border);
    border-radius: 16px; overflow: hidden;
}
.au-table { width: 100%; border-collapse: collapse; }
.au-table thead tr { background: var(--surface2); }
.au-table th {
    padding: 12px 16px; font-size: .68rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .08em;
    color: var(--tx3); text-align: left; border-bottom: 1px solid var(--border);
    white-space: nowrap;
}
.au-table td {
    padding: 14px 16px; font-size: .86rem; color: var(--tx2);
    border-bottom: 1px solid var(--border); vertical-align: middle;
}
.au-table tbody tr:last-child td { border-bottom: none; }
.au-table tbody tr { transition: background .15s; }
.au-table tbody tr:hover td { background: rgba(250,247,242,.6); }
[data-theme="dark"] .au-table tbody tr:hover td { background: rgba(40,39,31,.7); }

/* Avatar */
.au-avatar {
    width: 38px; height: 38px; border-radius: 50%; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: .88rem; color: var(--primary);
    background: rgba(44,95,46,.1); overflow: hidden;
    box-shadow: 0 2px 6px rgba(0,0,0,.08);
}
[data-theme="dark"] .au-avatar { background: rgba(74,222,128,.1); }
.au-avatar img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }
.au-user-name { font-weight: 600; color: var(--tx); font-size: .86rem; }
.au-user-email { font-size: .74rem; color: var(--tx3); margin-top: 1px; }

/* Role badges */
.au-role {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: .7rem; font-weight: 700;
    padding: 3px 9px; border-radius: 5px;
}
.au-role-admin { background: rgba(99,102,241,.1);  color: #6366f1; }
.au-role-user  { background: rgba(44,95,46,.08);   color: var(--primary); }
[data-theme="dark"] .au-role-admin { background: rgba(99,102,241,.15); }
[data-theme="dark"] .au-role-user  { background: rgba(74,222,128,.09); }

/* Status badges */
.au-status {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 4px 10px; border-radius: 100px; font-size: .7rem; font-weight: 700;
}
.au-status-active    { background: rgba(34,197,94,.1);  color: #16a34a; }
.au-status-suspended { background: rgba(239,68,68,.1);  color: #ef4444; }
[data-theme="dark"] .au-status-active    { background: rgba(74,222,128,.12); color: #4ADE80; }
[data-theme="dark"] .au-status-suspended { background: rgba(252,165,165,.12); color: #FCA5A5; }
.au-status i { font-size: .6rem; }

/* Verified badge */
.au-verified {
    display: inline-flex; align-items: center; gap: 3px;
    font-size: .65rem; font-weight: 600;
    padding: 2px 6px; border-radius: 4px;
    background: rgba(34,197,94,.08); color: #16a34a; margin-top: 3px;
}
[data-theme="dark"] .au-verified { background: rgba(74,222,128,.1); color: #4ADE80; }

/* Stats micro row */
.au-user-stats {
    display: flex; gap: 10px; align-items: center; margin-top: 3px;
}
.au-user-stat { font-size: .72rem; color: var(--tx3); display: flex; align-items: center; gap: 3px; }

/* Action buttons */
.au-actions { display: flex; gap: 5px; align-items: center; }
.au-btn {
    width: 32px; height: 32px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    border: 1.5px solid var(--border); background: var(--surface);
    color: var(--tx2); font-size: .88rem; cursor: pointer;
    text-decoration: none;
    transition: all .15s;
}
.au-btn:hover         { background: var(--surface2); color: var(--tx); border-color: var(--border2); }
.au-btn.view:hover    { border-color: #6366f1; color: #6366f1; background: rgba(99,102,241,.06); }
.au-btn.suspend:hover { border-color: #ef4444; color: #ef4444; background: rgba(239,68,68,.06); }
.au-btn.unsuspend:hover { border-color: #22c55e; color: #22c55e; background: rgba(34,197,94,.06); }

/* ── Empty state ── */
.au-empty { text-align: center; padding: 48px 20px; color: var(--tx3); }
.au-empty-icon {
    width: 64px; height: 64px; border-radius: 18px;
    background: var(--surface2); border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    font-size: 26px; margin: 0 auto 16px;
}
.au-empty-title { font-family: 'Playfair Display', serif; font-size: 1.1rem; font-weight: 700; color: var(--tx2); margin-bottom: 6px; }

/* ── Pagination ── */
.au-pagination { margin-top: 20px; display: flex; justify-content: center; }
.au-pagination nav { display: flex; gap: 6px; align-items: center; }
.au-pagination .pagination { display: flex; gap: 6px; list-style: none; align-items: center; }
.au-pagination .page-item .page-link {
    display: flex; align-items: center; justify-content: center;
    min-width: 36px; height: 36px; padding: 0 10px;
    border-radius: 8px; font-size: .85rem; font-weight: 500;
    color: var(--tx2); background: var(--surface);
    border: 1px solid var(--border); text-decoration: none; transition: all .18s;
}
.au-pagination .page-item .page-link:hover { border-color: var(--primary); color: var(--primary); }
.au-pagination .page-item.active .page-link { background: var(--primary); color: #fff; border-color: var(--primary); font-weight: 600; }
[data-theme="dark"] .au-pagination .page-item.active .page-link { color: var(--bg); }
.au-pagination .page-item.disabled .page-link { opacity: .4; cursor: not-allowed; }

/* Alert */
.au-alert {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: .87rem;
    animation: auFadeIn .3s both;
}
@keyframes auFadeIn { from{opacity:0;transform:translateY(-6px)} to{opacity:1;transform:translateY(0)} }
.au-alert-success { background: #E8F5E9; color: #1a4a1c; border: 1px solid #A5D6A7; }
.au-alert-error   { background: #FEF2F2; color: #B91C1C; border: 1px solid #FECACA; }
[data-theme="dark"] .au-alert-success { background: rgba(74,222,128,.09); color: #86EFAC; border-color: rgba(74,222,128,.2); }
[data-theme="dark"] .au-alert-error   { background: rgba(252,165,165,.09); color: #FCA5A5; border-color: rgba(252,165,165,.2); }
</style>
@endpush

@section('content')
<div class="au-page">

    {{-- Alerts --}}
    @if(session('success'))
    <div class="au-alert au-alert-success" x-data x-init="setTimeout(()=>$el.remove(),4000)">
        <i class="bi bi-check-circle-fill"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif
    @if(session('error'))
    <div class="au-alert au-alert-error">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    {{-- Page head --}}
    <div class="au-head">
        <div>
            <div class="au-page-eyebrow">
                <span class="au-eyebrow-dot"></span>
                Admin Panel
            </div>
            <div class="au-page-title">Manajemen User</div>
        </div>
    </div>

    {{-- Quick stats --}}
    <div class="au-stats">
        <div class="au-stat">
            <div class="au-stat-icon" style="background:rgba(99,102,241,.1)">
                <i class="bi bi-people" style="color:#6366f1"></i>
            </div>
            <div>
                <div class="au-stat-val">{{ number_format($users->total()) }}</div>
                <div class="au-stat-lbl">Total User</div>
            </div>
        </div>
        <div class="au-stat">
            <div class="au-stat-icon" style="background:rgba(34,197,94,.1)">
                <i class="bi bi-person-check" style="color:#16a34a"></i>
            </div>
            <div>
                <div class="au-stat-val">{{ $users->whereNull('suspended_at')->count() }}</div>
                <div class="au-stat-lbl">Aktif</div>
            </div>
        </div>
        <div class="au-stat">
            <div class="au-stat-icon" style="background:rgba(239,68,68,.08)">
                <i class="bi bi-person-x" style="color:#ef4444"></i>
            </div>
            <div>
                <div class="au-stat-val">{{ $users->whereNotNull('suspended_at')->count() }}</div>
                <div class="au-stat-lbl">Dinonaktifkan</div>
            </div>
        </div>
        <div class="au-stat">
            <div class="au-stat-icon" style="background:rgba(44,95,46,.1)">
                <i class="bi bi-shield-check" style="color:var(--primary)"></i>
            </div>
            <div>
                <div class="au-stat-val">{{ $users->where('role','admin')->count() }}</div>
                <div class="au-stat-lbl">Admin</div>
            </div>
        </div>
    </div>

    {{-- Toolbar --}}
    <form action="{{ route('admin.users.index') }}" method="GET" id="filterForm">
        <div class="au-toolbar">

            {{-- Search --}}
            <div class="au-search">
                <span class="au-search-icon"><i class="bi bi-search" style="font-size:.85rem"></i></span>
                <input type="text" name="search"
                       placeholder="Cari nama atau email…"
                       value="{{ request('search') }}"
                       autocomplete="off">
                @if(request('search'))
                <a href="{{ route('admin.users.index', array_filter(['role'=>request('role'),'status'=>request('status')])) }}"
                   style="display:flex;align-items:center;padding:0 10px;color:var(--tx3);font-size:16px;transition:color .15s">×</a>
                @endif
                <button type="submit" class="au-search-btn">
                    <i class="bi bi-search"></i> Cari
                </button>
            </div>

            {{-- Role filter --}}
            <select name="role" class="au-select" onchange="this.form.submit()">
                <option value="">Semua Role</option>
                <option value="user"  {{ request('role')=='user'  ? 'selected' : '' }}>User</option>
                <option value="admin" {{ request('role')=='admin' ? 'selected' : '' }}>Admin</option>
            </select>

            {{-- Status filter --}}
            <select name="status" class="au-select" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="active"    {{ request('status')=='active'    ? 'selected' : '' }}>Aktif</option>
                <option value="suspended" {{ request('status')=='suspended' ? 'selected' : '' }}>Dinonaktifkan</option>
            </select>
        </div>
    </form>

    {{-- Result info --}}
    <div class="au-result-info">
        Menampilkan <strong>{{ $users->count() }}</strong> dari <strong>{{ number_format($users->total()) }}</strong> pengguna
    </div>

    {{-- Table --}}
    <div class="au-table-card">
        <table class="au-table">
            <thead>
                <tr>
                    <th style="width:38%">Pengguna</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Aktivitas</th>
                    <th>Bergabung</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    {{-- User info --}}
                    <td>
                        <div style="display:flex;align-items:center;gap:11px">
                            <div class="au-avatar">
                                @if($user->avatar)
                                <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}">
                                @else
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                @endif
                            </div>
                            <div style="min-width:0">
                                <div class="au-user-name">{{ $user->name }}</div>
                                <div class="au-user-email">{{ $user->email }}</div>
                                @if($user->email_verified_at)
                                <div class="au-verified">
                                    <i class="bi bi-patch-check-fill" style="font-size:.6rem"></i> Terverifikasi
                                </div>
                                @endif
                            </div>
                        </div>
                    </td>

                    {{-- Role --}}
                    <td>
                        <span class="au-role {{ $user->role === 'admin' ? 'au-role-admin' : 'au-role-user' }}">
                            <i class="bi {{ $user->role === 'admin' ? 'bi-shield-fill' : 'bi-person-fill' }}" style="font-size:.6rem"></i>
                            {{ $user->role === 'admin' ? 'Admin' : 'User' }}
                        </span>
                    </td>

                    {{-- Status --}}
                    <td>
                        <span class="au-status {{ $user->suspended_at ? 'au-status-suspended' : 'au-status-active' }}">
                            <i class="bi {{ $user->suspended_at ? 'bi-x-circle-fill' : 'bi-check-circle-fill' }}"></i>
                            {{ $user->suspended_at ? 'Nonaktif' : 'Aktif' }}
                        </span>
                    </td>

                    {{-- Activity micro stats --}}
                    <td>
                        <div class="au-user-stats">
                            <span class="au-user-stat">
                                <i class="bi bi-bookmark"></i>
                                {{ $user->bookmarks()->count() }}
                            </span>
                            <span class="au-user-stat">
                                <i class="bi bi-chat-dots"></i>
                                {{ $user->reviews()->count() }}
                            </span>
                            <span class="au-user-stat">
                                <i class="bi bi-star"></i>
                                {{ $user->ratings()->count() }}
                            </span>
                        </div>
                    </td>

                    {{-- Joined --}}
                    <td style="font-size:.8rem;color:var(--tx3);white-space:nowrap">
                        {{ $user->created_at->translatedFormat('d M Y') }}
                    </td>

                    {{-- Actions --}}
                    <td>
                        <div class="au-actions">
                            {{-- View profile --}}
                            <a href="{{ route('admin.users.show', $user->id) }}"
                               class="au-btn view" title="Lihat Detail">
                                <i class="bi bi-eye"></i>
                            </a>

                            {{-- Toggle suspend --}}
                            <form action="{{ route('admin.users.toggle-suspend', $user->id) }}" method="POST"
                                  onsubmit="return confirm('{{ $user->suspended_at ? 'Aktifkan kembali akun ' . $user->name . '?' : 'Nonaktifkan akun ' . $user->name . '?' }}')">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        class="au-btn {{ $user->suspended_at ? 'unsuspend' : 'suspend' }}"
                                        title="{{ $user->suspended_at ? 'Aktifkan Kembali' : 'Nonaktifkan' }}">
                                    <i class="bi {{ $user->suspended_at ? 'bi-unlock' : 'bi-lock' }}"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="au-empty">
                            <div class="au-empty-icon">👥</div>
                            <div class="au-empty-title">
                                @if(request()->anyFilled(['search','role','status']))
                                    Tidak ada hasil
                                @else
                                    Belum ada pengguna
                                @endif
                            </div>
                            <div style="font-size:.84rem;margin-bottom:12px">
                                @if(request()->anyFilled(['search','role','status']))
                                    Tidak ada pengguna yang cocok dengan filter yang dipilih.
                                @else
                                    Pengguna yang mendaftar akan muncul di sini.
                                @endif
                            </div>
                            @if(request()->anyFilled(['search','role','status']))
                            <a href="{{ route('admin.users.index') }}"
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
    <div class="au-pagination">
        {{ $users->appends(request()->query())->links() }}
    </div>

</div>
@endsection