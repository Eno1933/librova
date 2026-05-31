@extends('layouts.admin')

@section('title', 'Moderasi Review — Admin Librova')
@section('header-title', 'Moderasi Review')
@section('breadcrumb', 'Moderasi Review')

@push('styles')
<style>
/* ═══════════════════════════════════════════
   ADMIN REVIEWS MODERATION
═══════════════════════════════════════════ */
.ar-page { padding: 28px 28px 48px; }

/* ── Page head ── */
.ar-head {
    display: flex; align-items: flex-end; justify-content: space-between;
    gap: 16px; flex-wrap: wrap; margin-bottom: 24px;
}
.ar-page-eyebrow {
    font-size: .7rem; font-weight: 700; letter-spacing: .08em;
    text-transform: uppercase; color: var(--tx3); margin-bottom: 5px;
    display: flex; align-items: center; gap: 6px;
}
.ar-eyebrow-dot { width: 5px; height: 5px; border-radius: 50%; background: var(--primary); }
.ar-page-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.6rem; font-weight: 700; letter-spacing: -.02em; color: var(--tx);
}

/* ── Quick stats ── */
.ar-stats { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 24px; }
.ar-stat {
    display: flex; align-items: center; gap: 8px;
    padding: 9px 16px; border-radius: 10px;
    background: var(--surface); border: 1px solid var(--border);
    transition: border-color .2s; cursor: default;
}
.ar-stat:hover { border-color: var(--border2); }
.ar-stat-icon {
    width: 28px; height: 28px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center; font-size: 13px;
}
.ar-stat-val { font-family: 'Playfair Display', serif; font-size: 1.05rem; font-weight: 700; color: var(--tx); }
.ar-stat-lbl { font-size: .7rem; color: var(--tx3); font-weight: 500; }

/* ── Toolbar ── */
.ar-toolbar { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; margin-bottom: 16px; }
.ar-select {
    padding: 10px 28px 10px 12px; border-radius: 10px;
    border: 1.5px solid var(--border); background: var(--surface);
    font-family: inherit; font-size: .83rem; color: var(--tx2);
    appearance: none; cursor: pointer; min-width: 180px;
    background-image: url("data:image/svg+xml,%3Csvg width='12' height='8' viewBox='0 0 12 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%239A9282' stroke-width='1.5' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 10px center;
    transition: border-color .2s;
}
.ar-select:focus { outline: none; border-color: var(--primary); }

.ar-active-chip {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 12px; border-radius: 100px;
    background: rgba(44,95,46,.08); border: 1px solid rgba(44,95,46,.2);
    font-size: .75rem; font-weight: 600; color: var(--primary);
}
[data-theme="dark"] .ar-active-chip { background: rgba(74,222,128,.09); border-color: rgba(74,222,128,.2); }

/* Result info */
.ar-result-info { font-size: .8rem; color: var(--tx3); margin-bottom: 14px; }
.ar-result-info strong { color: var(--tx2); }

/* ── Table card ── */
.ar-table-card {
    background: var(--surface); border: 1px solid var(--border);
    border-radius: 16px; overflow: hidden;
}
.ar-table { width: 100%; border-collapse: collapse; }
.ar-table thead tr { background: var(--surface2); }
.ar-table th {
    padding: 12px 16px; font-size: .68rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .08em;
    color: var(--tx3); text-align: left; border-bottom: 1px solid var(--border);
    white-space: nowrap;
}
.ar-table td {
    padding: 14px 16px; font-size: .86rem; color: var(--tx2);
    border-bottom: 1px solid var(--border); vertical-align: middle;
}
.ar-table tbody tr:last-child td { border-bottom: none; }
.ar-table tbody tr { transition: background .15s; }
.ar-table tbody tr:hover td { background: rgba(250,247,242,.6); }
[data-theme="dark"] .ar-table tbody tr:hover td { background: rgba(40,39,31,.7); }

/* Pending row highlight */
.ar-table tbody tr.is-pending td { background: rgba(234,179,8,.025); }
.ar-table tbody tr.is-pending:hover td { background: rgba(234,179,8,.05); }
[data-theme="dark"] .ar-table tbody tr.is-pending td { background: rgba(251,191,36,.025); }

/* User cell */
.ar-user { display: flex; align-items: center; gap: 9px; }
.ar-avatar {
    width: 34px; height: 34px; border-radius: 50%; flex-shrink: 0;
    background: rgba(44,95,46,.1);
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: .8rem; color: var(--primary);
}
[data-theme="dark"] .ar-avatar { background: rgba(74,222,128,.1); }
.ar-user-name  { font-weight: 600; color: var(--tx); font-size: .85rem; }
.ar-user-email { font-size: .72rem; color: var(--tx3); margin-top: 1px; }

/* Book cell */
.ar-book-cover {
    width: 34px; height: 48px; border-radius: 5px; flex-shrink: 0;
    box-shadow: 1px 2px 6px rgba(0,0,0,.12);
    background-size: cover;
    background-position: center;
}
.ar-book-title  { font-weight: 600; color: var(--tx); font-size: .85rem; }
.ar-book-author { font-size: .72rem; color: var(--tx3); margin-top: 1px; }

/* Review content */
.ar-content {
    max-width: 260px; white-space: nowrap;
    overflow: hidden; text-overflow: ellipsis;
    font-size: .84rem; color: var(--tx2);
    cursor: default;
}

/* Status badges */
.ar-status {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 4px 10px; border-radius: 100px; font-size: .7rem; font-weight: 700;
}
.ar-status i { font-size: .6rem; }
.ar-status-pending  { background: rgba(234,179,8,.14); color: #b45309; }
.ar-status-approved { background: rgba(34,197,94,.1);  color: #16a34a; }
.ar-status-rejected { background: rgba(239,68,68,.1);  color: #ef4444; }
[data-theme="dark"] .ar-status-pending  { background: rgba(251,191,36,.14); color: #fbbf24; }
[data-theme="dark"] .ar-status-approved { background: rgba(74,222,128,.12); color: #4ADE80; }
[data-theme="dark"] .ar-status-rejected { background: rgba(252,165,165,.12); color: #FCA5A5; }

/* Action buttons */
.ar-actions { display: flex; gap: 5px; align-items: center; }
.ar-btn {
    height: 32px; border-radius: 8px;
    display: inline-flex; align-items: center; justify-content: center; gap: 5px;
    border: 1.5px solid var(--border); background: var(--surface);
    color: var(--tx2); font-size: .8rem; font-weight: 600; cursor: pointer;
    padding: 0 12px; white-space: nowrap;
    font-family: inherit; transition: all .15s;
}
.ar-btn:hover         { background: var(--surface2); color: var(--tx); border-color: var(--border2); }
.ar-btn.approve       { color: #16a34a; border-color: #A5D6A7; background: rgba(34,197,94,.05); }
.ar-btn.approve:hover { background: #F0FDF4; border-color: #22c55e; }
.ar-btn.reject        { color: #ef4444; border-color: #FECACA; background: rgba(239,68,68,.04); }
.ar-btn.reject:hover  { background: #FEF2F2; border-color: #ef4444; }

/* ── Empty state ── */
.ar-empty { text-align: center; padding: 48px 20px; }
.ar-empty-icon {
    width: 64px; height: 64px; border-radius: 18px;
    background: var(--surface2); border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    font-size: 26px; margin: 0 auto 14px;
}
.ar-empty-title { font-family: 'Playfair Display', serif; font-size: 1.05rem; font-weight: 700; color: var(--tx2); margin-bottom: 6px; }

/* ── Pagination ── */
.ar-pagination { margin-top: 20px; display: flex; justify-content: center; }
.ar-pagination nav { display: flex; gap: 6px; align-items: center; }
.ar-pagination .pagination { display: flex; gap: 6px; list-style: none; align-items: center; }
.ar-pagination .page-item .page-link {
    display: flex; align-items: center; justify-content: center;
    min-width: 36px; height: 36px; padding: 0 10px; border-radius: 8px;
    font-size: .85rem; font-weight: 500; color: var(--tx2);
    background: var(--surface); border: 1px solid var(--border);
    text-decoration: none; transition: all .18s;
}
.ar-pagination .page-item .page-link:hover { border-color: var(--primary); color: var(--primary); }
.ar-pagination .page-item.active .page-link { background: var(--primary); color: #fff; border-color: var(--primary); font-weight: 600; }
[data-theme="dark"] .ar-pagination .page-item.active .page-link { color: var(--bg); }
.ar-pagination .page-item.disabled .page-link { opacity: .4; cursor: not-allowed; }

/* Alert */
.ar-alert {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: .87rem;
    animation: arIn .3s both;
}
@keyframes arIn { from{opacity:0;transform:translateY(-6px)} to{opacity:1;transform:translateY(0)} }
.ar-alert-success { background: #E8F5E9; color: #1a4a1c; border: 1px solid #A5D6A7; }
[data-theme="dark"] .ar-alert-success { background: rgba(74,222,128,.09); color: #86EFAC; border-color: rgba(74,222,128,.2); }
</style>
@endpush

@section('content')
<div class="ar-page">

    {{-- Alert --}}
    @if(session('success'))
    <div class="ar-alert ar-alert-success" x-data x-init="setTimeout(()=>$el.remove(),4000)">
        <i class="bi bi-check-circle-fill"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    {{-- Page head --}}
    <div class="ar-head">
        <div>
            <div class="ar-page-eyebrow"><span class="ar-eyebrow-dot"></span>Admin Panel</div>
            <div class="ar-page-title">Moderasi Review</div>
        </div>
    </div>

    {{-- Quick stats --}}
    <div class="ar-stats">
        <div class="ar-stat">
            <div class="ar-stat-icon" style="background:rgba(44,95,46,.1)">
                <i class="bi bi-chat-left-text" style="color:var(--primary)"></i>
            </div>
            <div>
                <div class="ar-stat-val">{{ $reviews->total() }}</div>
                <div class="ar-stat-lbl">Total</div>
            </div>
        </div>
        <div class="ar-stat">
            <div class="ar-stat-icon" style="background:rgba(234,179,8,.1)">
                <i class="bi bi-hourglass-split" style="color:#b45309"></i>
            </div>
            <div>
                <div class="ar-stat-val">{{ $reviews->where('status','pending')->count() }}</div>
                <div class="ar-stat-lbl">Menunggu</div>
            </div>
        </div>
        <div class="ar-stat">
            <div class="ar-stat-icon" style="background:rgba(34,197,94,.1)">
                <i class="bi bi-check-circle" style="color:#16a34a"></i>
            </div>
            <div>
                <div class="ar-stat-val">{{ $reviews->where('status','approved')->count() }}</div>
                <div class="ar-stat-lbl">Disetujui</div>
            </div>
        </div>
        <div class="ar-stat">
            <div class="ar-stat-icon" style="background:rgba(239,68,68,.08)">
                <i class="bi bi-x-circle" style="color:#ef4444"></i>
            </div>
            <div>
                <div class="ar-stat-val">{{ $reviews->where('status','rejected')->count() }}</div>
                <div class="ar-stat-lbl">Ditolak</div>
            </div>
        </div>
    </div>

    {{-- Toolbar --}}
    <form action="{{ route('admin.reviews.index') }}" method="GET">
        <div class="ar-toolbar">
            <select name="status" class="ar-select" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="pending"  {{ request('status')=='pending'  ? 'selected':'' }}>⏳ Menunggu Moderasi</option>
                <option value="approved" {{ request('status')=='approved' ? 'selected':'' }}>✅ Disetujui</option>
                <option value="rejected" {{ request('status')=='rejected' ? 'selected':'' }}>❌ Ditolak</option>
            </select>

            @if(request('status'))
            <div class="ar-active-chip">
                Status: {{ ['pending'=>'Menunggu','approved'=>'Disetujui','rejected'=>'Ditolak'][request('status')] ?? request('status') }}
                <a href="{{ route('admin.reviews.index') }}" style="color:var(--primary);font-size:14px">×</a>
            </div>
            @endif
        </div>
    </form>

    {{-- Result info --}}
    <div class="ar-result-info">
        Menampilkan <strong>{{ $reviews->count() }}</strong> dari <strong>{{ $reviews->total() }}</strong> review
    </div>

    {{-- Table --}}
    <div class="ar-table-card">
        <table class="ar-table">
            <thead>
                <tr>
                    <th>Pengguna</th>
                    <th>Buku</th>
                    <th>Ulasan</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                <tr class="{{ $review->status === 'pending' ? 'is-pending' : '' }}">

                    {{-- User --}}
                    <td>
                        <div class="ar-user">
                            <div class="ar-avatar">
                                {{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}
                            </div>
                            <div>
                                <div class="ar-user-name">{{ $review->user->name ?? 'User tidak dikenal' }}</div>
                                <div class="ar-user-email">{{ $review->user->email ?? '—' }}</div>
                            </div>
                        </div>
                    </td>

                    {{-- Book --}}
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            <div class="ar-book-cover"
                                 style="@if($review->book->cover_image) background-image:url('{{ Storage::url($review->book->cover_image) }}'); background-size:cover; background-position:center; @else background:linear-gradient(145deg, {{ $review->book->cover_color ?? '#2C5F2E' }}, {{ $review->book->cover_color_dark ?? '#1d4220' }}); @endif">
                            </div>
                            <div style="min-width:0">
                                <div class="ar-book-title">{{ Str::limit($review->book->title ?? '—', 28) }}</div>
                                <div class="ar-book-author">{{ $review->book->author ?? '—' }}</div>
                            </div>
                        </div>
                    </td>

                    {{-- Review content --}}
                    <td>
                        <div class="ar-content" title="{{ $review->content }}">
                            {{ $review->content }}
                        </div>
                    </td>

                    {{-- Status --}}
                    <td>
                        @if($review->status === 'pending')
                        <span class="ar-status ar-status-pending">
                            <i class="bi bi-hourglass-split"></i> Menunggu
                        </span>
                        @elseif($review->status === 'approved')
                        <span class="ar-status ar-status-approved">
                            <i class="bi bi-check-circle-fill"></i> Disetujui
                        </span>
                        @else
                        <span class="ar-status ar-status-rejected">
                            <i class="bi bi-x-circle-fill"></i> Ditolak
                        </span>
                        @endif
                    </td>

                    {{-- Date --}}
                    <td style="font-size:.8rem;color:var(--tx3);white-space:nowrap">
                        {{ $review->created_at->translatedFormat('d M Y') }}
                        <div style="font-size:.72rem;margin-top:1px">{{ $review->created_at->format('H:i') }}</div>
                    </td>

                    {{-- Actions --}}
                    <td>
                        <div class="ar-actions">
                            @if($review->status !== 'approved')
                            <form action="{{ route('admin.reviews.update-status', $review->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="ar-btn approve" title="Setujui">
                                    <i class="bi bi-check-lg"></i> Setujui
                                </button>
                            </form>
                            @endif

                            @if($review->status !== 'rejected')
                            <form action="{{ route('admin.reviews.update-status', $review->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="ar-btn reject" title="Tolak">
                                    <i class="bi bi-x-lg"></i> Tolak
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="ar-empty">
                            <div class="ar-empty-icon">💬</div>
                            <div class="ar-empty-title">
                                {{ request('status') ? 'Tidak ada review dengan status ini' : 'Belum ada review' }}
                            </div>
                            <div style="font-size:.84rem;color:var(--tx3);margin-bottom:12px">
                                {{ request('status') ? 'Coba pilih status lain.' : 'Review dari pengguna akan muncul di sini.' }}
                            </div>
                            @if(request('status'))
                            <a href="{{ route('admin.reviews.index') }}"
                               style="display:inline-flex;align-items:center;gap:6px;padding:9px 18px;border-radius:100px;background:var(--surface2);border:1px solid var(--border);font-size:.82rem;font-weight:600;color:var(--tx2);text-decoration:none">
                                <i class="bi bi-x-circle"></i> Tampilkan Semua
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
    <div class="ar-pagination">
        {{ $reviews->appends(request()->query())->links() }}
    </div>

</div>
@endsection