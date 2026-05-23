@extends('layouts.admin')

@section('title', 'Moderasi Review — Admin Librova')
@section('header-title', 'Moderasi Review')

@push('styles')
<style>
    .review-toolbar {
        display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 24px;
        align-items: center; justify-content: space-between;
    }
    .filter-select {
        padding: 9px 14px; border-radius: 10px; border: 1.5px solid var(--border);
        background: var(--surface); font-family: inherit; font-size: .85rem; color: var(--tx2);
        cursor: pointer; min-width: 160px;
    }
    .review-table {
        width: 100%; border-collapse: collapse; background: var(--surface);
        border: 1px solid var(--border); border-radius: 16px; overflow: hidden;
    }
    .review-table thead { background: var(--surface2); }
    .review-table th {
        padding: 14px 16px; font-size: .72rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: .06em; color: var(--tx3); text-align: left;
    }
    .review-table td {
        padding: 14px 16px; font-size: .88rem; color: var(--tx2); border-bottom: 1px solid var(--border);
        vertical-align: middle;
    }
    .review-table tr:last-child td { border-bottom: none; }
    .review-table tbody tr:hover { background: var(--surface2); }
    .review-avatar-mini {
        width: 34px; height: 34px; border-radius: 50%;
        background: var(--surface2); display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: .8rem; color: var(--primary); flex-shrink: 0;
    }
    .status-badge {
        display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px;
        border-radius: 100px; font-size: .72rem; font-weight: 600;
    }
    .status-pending { background: rgba(234,179,8,.15); color: #b45309; }
    .status-approved { background: rgba(34,197,94,.12); color: #16a34a; }
    .status-rejected { background: rgba(239,68,68,.1); color: #ef4444; }
    [data-theme="dark"] .status-pending { background: rgba(251,191,36,.15); color: #fbbf24; }
    [data-theme="dark"] .status-approved { background: rgba(74,222,128,.12); color: #4ADE80; }
    [data-theme="dark"] .status-rejected { background: rgba(252,165,165,.12); color: #FCA5A5; }
    .action-btns { display: flex; gap: 6px; }
    .btn-icon {
        width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center;
        justify-content: center; border: 1px solid var(--border); background: var(--surface);
        color: var(--tx2); font-size: .9rem; cursor: pointer; transition: all .15s; text-decoration: none;
    }
    .btn-icon:hover { background: var(--surface2); color: var(--tx); border-color: var(--border2); }
    .btn-icon.approve { color: #16a34a; border-color: #A5D6A7; }
    .btn-icon.approve:hover { background: #F0FDF4; }
    .btn-icon.reject { color: #ef4444; border-color: #FECACA; }
    .btn-icon.reject:hover { background: #FEF2F2; }
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
    .review-content {
        max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
    }
    @media (max-width: 768px) {
        .review-content { max-width: 180px; }
    }
</style>
@endpush

@section('content')
<div style="padding: 28px 28px 40px;">

    @if(session('success'))
        <div class="alert alert-success" style="padding:12px 16px; border-radius:10px; margin-bottom:20px; background:#E8F5E9; color:#1a4a1c; border:1px solid #A5D6A7; font-size:.88rem;">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        </div>
    @endif

    <div class="review-toolbar">
        <form action="{{ route('admin.reviews.index') }}" method="GET">
            <select name="status" class="filter-select" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Moderasi</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </form>
        <span style="font-size:.8rem;color:var(--tx3);">{{ $reviews->total() }} review</span>
    </div>

    <table class="review-table">
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
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div class="review-avatar-mini">{{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}</div>
                        <div>
                            <div style="font-weight:600;color:var(--tx)">{{ $review->user->name ?? 'User tidak dikenal' }}</div>
                            <div style="font-size:.72rem;color:var(--tx3)">{{ $review->user->email ?? '-' }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <div style="font-weight:600;color:var(--tx)">{{ Str::limit($review->book->title ?? '-', 30) }}</div>
                    <div style="font-size:.72rem;color:var(--tx3)">{{ $review->book->author ?? '-' }}</div>
                </td>
                <td>
                    <div class="review-content" title="{{ $review->content }}">
                        {{ $review->content }}
                    </div>
                </td>
                <td>
                    @if($review->status === 'pending')
                        <span class="status-badge status-pending">
                            <i class="bi bi-hourglass-split" style="font-size:.65rem;"></i> Menunggu
                        </span>
                    @elseif($review->status === 'approved')
                        <span class="status-badge status-approved">
                            <i class="bi bi-check-circle-fill" style="font-size:.65rem;"></i> Disetujui
                        </span>
                    @else
                        <span class="status-badge status-rejected">
                            <i class="bi bi-x-circle-fill" style="font-size:.65rem;"></i> Ditolak
                        </span>
                    @endif
                </td>
                <td style="font-size:.82rem;color:var(--tx3)">{{ $review->created_at->translatedFormat('d M Y, H:i') }}</td>
                <td>
                    <div class="action-btns">
                        @if($review->status !== 'approved')
                        <form action="{{ route('admin.reviews.update-status', $review->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="approved">
                            <button type="submit" class="btn-icon approve" title="Setujui">
                                <i class="bi bi-check-lg"></i>
                            </button>
                        </form>
                        @endif
                        @if($review->status !== 'rejected')
                        <form action="{{ route('admin.reviews.update-status', $review->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="rejected">
                            <button type="submit" class="btn-icon reject" title="Tolak">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;padding:32px;color:var(--tx3);">
                    <i class="bi bi-inbox" style="font-size:2rem;display:block;margin-bottom:8px;"></i>
                    Belum ada review.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination-wrap">
        {{ $reviews->appends(request()->query())->links() }}
    </div>
</div>
@endsection