@extends('layouts.admin')

@section('title', 'Feedback — Admin Librova')
@section('header-title', 'Feedback')

@push('styles')
<style>
    .feedback-toolbar {
        display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 24px;
        align-items: center; justify-content: space-between;
    }
    .filter-select {
        padding: 9px 14px; border-radius: 10px; border: 1.5px solid var(--border);
        background: var(--surface); font-family: inherit; font-size: .85rem; color: var(--tx2);
        cursor: pointer; min-width: 160px;
    }
    .feedback-table {
        width: 100%; border-collapse: collapse; background: var(--surface);
        border: 1px solid var(--border); border-radius: 16px; overflow: hidden;
    }
    .feedback-table thead { background: var(--surface2); }
    .feedback-table th {
        padding: 14px 16px; font-size: .72rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: .06em; color: var(--tx3); text-align: left;
    }
    .feedback-table td {
        padding: 14px 16px; font-size: .88rem; color: var(--tx2); border-bottom: 1px solid var(--border);
        vertical-align: middle;
    }
    .feedback-table tr:last-child td { border-bottom: none; }
    .feedback-table tbody tr:hover { background: var(--surface2); }
    .user-mini {
        display: flex; align-items: center; gap: 8px;
    }
    .user-av-sm {
        width: 30px; height: 30px; border-radius: 50%;
        background: var(--surface2); display: flex; align-items: center; justify-content: center;
        font-size: .75rem; font-weight: 700; color: var(--primary); flex-shrink: 0;
    }
    .status-badge {
        display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px;
        border-radius: 100px; font-size: .72rem; font-weight: 600;
    }
    .status-new { background: rgba(234,179,8,.15); color: #b45309; }
    .status-read { background: rgba(99,102,241,.15); color: #4f46e5; }
    .status-replied { background: rgba(34,197,94,.12); color: #16a34a; }
    [data-theme="dark"] .status-new { background: rgba(251,191,36,.15); color: #fbbf24; }
    [data-theme="dark"] .status-read { background: rgba(165,180,252,.15); color: #a5b4fc; }
    [data-theme="dark"] .status-replied { background: rgba(74,222,128,.12); color: #4ADE80; }

    .message-cell { max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .action-btns { display: flex; gap: 6px; }
    .btn-icon {
        width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center;
        justify-content: center; border: 1px solid var(--border); background: var(--surface);
        color: var(--tx2); font-size: .9rem; cursor: pointer; transition: all .15s;
    }
    .btn-icon:hover { background: var(--surface2); color: var(--tx); border-color: var(--border2); }
    .btn-icon.reply { color: #6366f1; border-color: rgba(99,102,241,.2); }
    .btn-icon.reply:hover { background: #EEF2FF; }
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

    /* Modal reply */
    .reply-modal {
        position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 60;
        display: flex; align-items: center; justify-content: center;
        backdrop-filter: blur(4px);
    }
    .reply-card {
        background: var(--surface); border: 1px solid var(--border); border-radius: 16px;
        padding: 24px; width: 90%; max-width: 500px; box-shadow: 0 12px 40px var(--shadow);
    }
    .reply-card h3 { font-family: 'Playfair Display', serif; font-size: 1.2rem; margin-bottom: 16px; }
    .reply-card .btn-submit {
        margin-top: 12px; padding: 10px 20px; border-radius: 100px;
        background: var(--primary); color: #fff; border: none; cursor: pointer;
        font-weight: 600; font-size: .85rem;
    }
    [data-theme="dark"] .reply-card .btn-submit { color: var(--bg); }
</style>
@endpush

@section('content')
<div style="padding: 28px 28px 40px;">

    @if(session('success'))
        <div class="alert alert-success" style="padding:12px 16px; border-radius:10px; margin-bottom:20px; background:#E8F5E9; color:#1a4a1c; border:1px solid #A5D6A7; font-size:.88rem;">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        </div>
    @endif

    <div class="feedback-toolbar">
        <form action="{{ route('admin.feedbacks.index') }}" method="GET">
            <select name="status" class="filter-select" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>Baru</option>
                <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Sudah Dibaca</option>
                <option value="replied" {{ request('status') == 'replied' ? 'selected' : '' }}>Dibalas</option>
            </select>
        </form>
        <span style="font-size:.8rem;color:var(--tx3)">{{ $feedbacks->total() }} feedback</span>
    </div>

    <table class="feedback-table">
        <thead>
            <tr>
                <th>Pengguna</th>
                <th>Subjek</th>
                <th>Pesan</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($feedbacks as $feedback)
            <tr>
                <td>
                    <div class="user-mini">
                        <div class="user-av-sm">{{ $feedback->user ? strtoupper(substr($feedback->user->name, 0, 1)) : '👤' }}</div>
                        <span>{{ $feedback->user ? $feedback->user->name : 'Guest' }}</span>
                    </div>
                </td>
                <td style="font-weight:600;color:var(--tx)">{{ $feedback->subject }}</td>
                <td class="message-cell" title="{{ $feedback->message }}">{{ $feedback->message }}</td>
                <td>
                    @if($feedback->status === 'new')
                        <span class="status-badge status-new"><i class="bi bi-envelope-fill" style="font-size:.65rem;"></i> Baru</span>
                    @elseif($feedback->status === 'read')
                        <span class="status-badge status-read"><i class="bi bi-envelope-open-fill" style="font-size:.65rem;"></i> Dibaca</span>
                    @else
                        <span class="status-badge status-replied"><i class="bi bi-reply-fill" style="font-size:.65rem;"></i> Dibalas</span>
                    @endif
                </td>
                <td style="font-size:.82rem;color:var(--tx3)">{{ $feedback->created_at->translatedFormat('d M Y, H:i') }}</td>
                <td>
                    <div class="action-btns">
                        @if($feedback->status !== 'replied')
                        <button class="btn-icon reply" @click="openReply({{ $feedback->id }})" title="Balas">
                            <i class="bi bi-reply"></i>
                        </button>
                        @endif
                        @if($feedback->status === 'new')
                        <form action="{{ route('admin.feedbacks.read', $feedback->id) }}" method="POST" style="display:inline">
                            @csrf @method('PATCH')
                            <button class="btn-icon" title="Tandai sudah dibaca">
                                <i class="bi bi-check2"></i>
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
                    Belum ada feedback.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination-wrap">
        {{ $feedbacks->links() }}
    </div>

    {{-- Modal Balas (Alpine) --}}
    <div x-data="{ open: false, feedbackId: null }" x-show="open" x-cloak class="reply-modal" @click.self="open = false">
        <div class="reply-card">
            <h3>Balas Feedback</h3>
            <form :action="`/admin/feedbacks/${feedbackId}/reply`" method="POST">
                @csrf
                <div class="f-group">
                    <label class="f-label">Balasan Admin</label>
                    <textarea name="admin_reply" rows="4" class="f-input" style="padding-left:14px" required></textarea>
                </div>
                <button type="submit" class="btn-submit">Kirim Balasan</button>
            </form>
            <button @click="open = false" style="margin-left:8px; background:none; border:1px solid var(--border); padding:8px 16px; border-radius:100px; cursor:pointer; margin-top:12px;">Tutup</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('replyModal', () => ({
            open: false,
            feedbackId: null,
            openReply(id) {
                this.feedbackId = id;
                this.open = true;
            }
        }));
    });
</script>
@endpush