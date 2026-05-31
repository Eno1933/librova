@extends('layouts.admin')

@section('title', 'Feedback — Admin Librova')
@section('header-title', 'Feedback')
@section('breadcrumb', 'Feedback')

@push('styles')
<style>
/* ═══════════════════════════════════════════
   ADMIN FEEDBACKS INDEX
═══════════════════════════════════════════ */
.af-page { padding: 28px 28px 48px; }

/* ── Page head ── */
.af-head {
    display: flex; align-items: flex-end; justify-content: space-between;
    gap: 16px; flex-wrap: wrap; margin-bottom: 24px;
}
.af-page-eyebrow {
    font-size: .7rem; font-weight: 700; letter-spacing: .08em;
    text-transform: uppercase; color: var(--tx3); margin-bottom: 5px;
    display: flex; align-items: center; gap: 6px;
}
.af-eyebrow-dot { width: 5px; height: 5px; border-radius: 50%; background: var(--primary); }
.af-page-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.6rem; font-weight: 700; letter-spacing: -.02em; color: var(--tx);
}

/* ── Quick stats ── */
.af-stats { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 24px; }
.af-stat {
    display: flex; align-items: center; gap: 8px;
    padding: 9px 16px; border-radius: 10px;
    background: var(--surface); border: 1px solid var(--border);
    transition: border-color .2s; cursor: default;
}
.af-stat:hover { border-color: var(--border2); }
.af-stat-icon {
    width: 28px; height: 28px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center; font-size: 13px;
}
.af-stat-val { font-family: 'Playfair Display', serif; font-size: 1.05rem; font-weight: 700; color: var(--tx); }
.af-stat-lbl { font-size: .7rem; color: var(--tx3); font-weight: 500; }

/* ── Toolbar ── */
.af-toolbar {
    display: flex; align-items: center; gap: 10px;
    flex-wrap: wrap; margin-bottom: 16px;
}
.af-select {
    padding: 10px 28px 10px 12px; border-radius: 10px;
    border: 1.5px solid var(--border); background: var(--surface);
    font-family: inherit; font-size: .83rem; color: var(--tx2);
    appearance: none; cursor: pointer; min-width: 160px;
    background-image: url("data:image/svg+xml,%3Csvg width='12' height='8' viewBox='0 0 12 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%239A9282' stroke-width='1.5' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 10px center;
    transition: border-color .2s;
}
.af-select:focus { outline: none; border-color: var(--primary); }

/* Active filter chip */
.af-active-chip {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 12px; border-radius: 100px;
    background: rgba(44,95,46,.08); border: 1px solid rgba(44,95,46,.2);
    font-size: .75rem; font-weight: 600; color: var(--primary);
}
[data-theme="dark"] .af-active-chip { background: rgba(74,222,128,.09); border-color: rgba(74,222,128,.2); }

/* Result info */
.af-result-info { font-size: .8rem; color: var(--tx3); margin-bottom: 14px; }
.af-result-info strong { color: var(--tx2); }

/* ── Table card ── */
.af-table-card {
    background: var(--surface); border: 1px solid var(--border);
    border-radius: 16px; overflow: hidden;
}
.af-table { width: 100%; border-collapse: collapse; }
.af-table thead tr { background: var(--surface2); }
.af-table th {
    padding: 12px 16px; font-size: .68rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .08em;
    color: var(--tx3); text-align: left; border-bottom: 1px solid var(--border);
    white-space: nowrap;
}
.af-table td {
    padding: 14px 16px; font-size: .86rem; color: var(--tx2);
    border-bottom: 1px solid var(--border); vertical-align: middle;
}
.af-table tbody tr:last-child td { border-bottom: none; }
.af-table tbody tr { transition: background .15s; }
.af-table tbody tr:hover td { background: rgba(250,247,242,.6); }
[data-theme="dark"] .af-table tbody tr:hover td { background: rgba(40,39,31,.7); }

/* Unread row highlight */
.af-table tbody tr.unread td { background: rgba(234,179,8,.03); }
.af-table tbody tr.unread:hover td { background: rgba(234,179,8,.06); }
[data-theme="dark"] .af-table tbody tr.unread td { background: rgba(251,191,36,.03); }

/* User mini */
.af-user { display: flex; align-items: center; gap: 9px; }
.af-avatar {
    width: 32px; height: 32px; border-radius: 50%; flex-shrink: 0;
    background: rgba(44,95,46,.1);
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: .78rem; color: var(--primary);
}
[data-theme="dark"] .af-avatar { background: rgba(74,222,128,.1); }
.af-user-name { font-weight: 600; color: var(--tx); font-size: .85rem; }
.af-user-guest { font-size: .82rem; color: var(--tx3); font-style: italic; }

/* Subject */
.af-subject { font-weight: 600; color: var(--tx); }

/* Message preview */
.af-msg {
    max-width: 240px; white-space: nowrap;
    overflow: hidden; text-overflow: ellipsis;
    font-size: .84rem; color: var(--tx3);
}

/* Status badges */
.af-status {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 4px 10px; border-radius: 100px; font-size: .7rem; font-weight: 700;
}
.af-status i { font-size: .6rem; }
.af-status-new     { background: rgba(234,179,8,.14); color: #b45309; }
.af-status-read    { background: rgba(99,102,241,.12); color: #4f46e5; }
.af-status-replied { background: rgba(34,197,94,.1);  color: #16a34a; }
[data-theme="dark"] .af-status-new     { background: rgba(251,191,36,.14); color: #fbbf24; }
[data-theme="dark"] .af-status-read    { background: rgba(165,180,252,.12); color: #a5b4fc; }
[data-theme="dark"] .af-status-replied { background: rgba(74,222,128,.12); color: #4ADE80; }

/* Action buttons */
.af-actions { display: flex; gap: 5px; align-items: center; }
.af-btn {
    width: 32px; height: 32px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    border: 1.5px solid var(--border); background: var(--surface);
    color: var(--tx2); font-size: .88rem; cursor: pointer;
    transition: all .15s; font-family: inherit;
}
.af-btn:hover       { background: var(--surface2); color: var(--tx); border-color: var(--border2); }
.af-btn.reply:hover { border-color: #6366f1; color: #6366f1; background: rgba(99,102,241,.07); }
.af-btn.read:hover  { border-color: var(--primary); color: var(--primary); background: rgba(44,95,46,.06); }
[data-theme="dark"] .af-btn.read:hover { background: rgba(74,222,128,.07); }

/* ── Empty state ── */
.af-empty { text-align: center; padding: 48px 20px; }
.af-empty-icon {
    width: 64px; height: 64px; border-radius: 18px;
    background: var(--surface2); border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    font-size: 26px; margin: 0 auto 14px;
}
.af-empty-title { font-family: 'Playfair Display', serif; font-size: 1.05rem; font-weight: 700; color: var(--tx2); margin-bottom: 6px; }

/* ── Pagination ── */
.af-pagination { margin-top: 20px; display: flex; justify-content: center; }
.af-pagination nav { display: flex; gap: 6px; align-items: center; }
.af-pagination .pagination { display: flex; gap: 6px; list-style: none; align-items: center; }
.af-pagination .page-item .page-link {
    display: flex; align-items: center; justify-content: center;
    min-width: 36px; height: 36px; padding: 0 10px; border-radius: 8px;
    font-size: .85rem; font-weight: 500; color: var(--tx2);
    background: var(--surface); border: 1px solid var(--border);
    text-decoration: none; transition: all .18s;
}
.af-pagination .page-item .page-link:hover { border-color: var(--primary); color: var(--primary); }
.af-pagination .page-item.active .page-link { background: var(--primary); color: #fff; border-color: var(--primary); font-weight: 600; }
[data-theme="dark"] .af-pagination .page-item.active .page-link { color: var(--bg); }
.af-pagination .page-item.disabled .page-link { opacity: .4; cursor: not-allowed; }

/* Alert */
.af-alert {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: .87rem;
    animation: afIn .3s both;
}
@keyframes afIn { from{opacity:0;transform:translateY(-6px)} to{opacity:1;transform:translateY(0)} }
.af-alert-success { background: #E8F5E9; color: #1a4a1c; border: 1px solid #A5D6A7; }
[data-theme="dark"] .af-alert-success { background: rgba(74,222,128,.09); color: #86EFAC; border-color: rgba(74,222,128,.2); }

/* ── Reply Modal ── */
.af-modal {
    position: fixed; inset: 0;
    background: rgba(0,0,0,.5); backdrop-filter: blur(4px);
    z-index: 60; display: flex; align-items: center; justify-content: center;
    padding: 20px;
}
.af-modal-card {
    background: var(--surface); border: 1px solid var(--border);
    border-radius: 18px; width: 100%; max-width: 480px;
    box-shadow: 0 20px 60px var(--shadow);
    animation: modalIn .25s cubic-bezier(.34,1.56,.64,1) both;
}
@keyframes modalIn { from{opacity:0;transform:scale(.94)} to{opacity:1;transform:scale(1)} }

.af-modal-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 18px 22px; border-bottom: 1px solid var(--border);
}
.af-modal-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.1rem; font-weight: 700; color: var(--tx);
    display: flex; align-items: center; gap: 8px;
}
.af-modal-title-icon {
    width: 30px; height: 30px; border-radius: 8px;
    background: rgba(99,102,241,.1); color: #6366f1;
    display: flex; align-items: center; justify-content: center; font-size: 14px;
}
.af-modal-close {
    width: 32px; height: 32px; border-radius: 8px;
    border: 1.5px solid var(--border); background: none;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; color: var(--tx3); font-size: .9rem;
    transition: background .15s, color .15s;
}
.af-modal-close:hover { background: var(--surface2); color: var(--tx); }

.af-modal-body { padding: 20px 22px; }

/* Modal feedback preview */
.af-fb-preview {
    background: var(--surface2); border: 1px solid var(--border);
    border-radius: 10px; padding: 12px 14px; margin-bottom: 16px;
}
.af-fb-preview-label {
    font-size: .68rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .07em; color: var(--tx3); margin-bottom: 5px;
}
.af-fb-preview-subject { font-weight: 600; color: var(--tx); font-size: .88rem; margin-bottom: 3px; }
.af-fb-preview-msg { font-size: .82rem; color: var(--tx2); line-height: 1.55; }

.af-f-label {
    display: block; margin-bottom: 6px;
    font-size: .75rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .05em; color: var(--tx2);
}
.af-textarea {
    width: 100%; padding: 11px 14px;
    border-radius: 10px; border: 1.5px solid var(--border);
    background: var(--bg); color: var(--tx);
    font-family: inherit; font-size: .88rem;
    resize: vertical; min-height: 110px;
    transition: border-color .2s, box-shadow .2s;
}
.af-textarea:focus {
    outline: none; border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99,102,241,.1);
}
.af-textarea::placeholder { color: var(--tx3); }

.af-modal-foot {
    padding: 14px 22px; border-top: 1px solid var(--border);
    display: flex; gap: 8px; justify-content: flex-end;
}
.af-modal-cancel {
    padding: 10px 18px; border-radius: 100px;
    border: 1.5px solid var(--border); background: none;
    font-family: inherit; font-size: .85rem; font-weight: 500; color: var(--tx2);
    cursor: pointer; transition: background .15s, border-color .15s;
}
.af-modal-cancel:hover { background: var(--surface2); border-color: var(--border2); }
.af-modal-send {
    padding: 10px 22px; border-radius: 100px;
    background: #6366f1; color: #fff;
    font-family: inherit; font-size: .85rem; font-weight: 600;
    border: none; cursor: pointer;
    display: flex; align-items: center; gap: 7px;
    transition: background .2s, transform .15s;
}
.af-modal-send:hover { background: #4f46e5; transform: translateY(-1px); }
</style>
@endpush

@section('content')
<div class="af-page"
     x-data="{
         open: false,
         feedbackId: null,
         subject: '',
         message: '',
         openReply(id, subject, message) {
             this.feedbackId = id;
             this.subject = subject;
             this.message = message;
             this.open = true;
         }
     }">

    {{-- Alert --}}
    @if(session('success'))
    <div class="af-alert af-alert-success" x-data x-init="setTimeout(()=>$el.remove(),4000)">
        <i class="bi bi-check-circle-fill"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    {{-- Page head --}}
    <div class="af-head">
        <div>
            <div class="af-page-eyebrow"><span class="af-eyebrow-dot"></span>Admin Panel</div>
            <div class="af-page-title">Feedback Pengguna</div>
        </div>
    </div>

    {{-- Quick stats --}}
    <div class="af-stats">
        <div class="af-stat">
            <div class="af-stat-icon" style="background:rgba(44,95,46,.1)">
                <i class="bi bi-envelope" style="color:var(--primary)"></i>
            </div>
            <div>
                <div class="af-stat-val">{{ $feedbacks->total() }}</div>
                <div class="af-stat-lbl">Total</div>
            </div>
        </div>
        <div class="af-stat">
            <div class="af-stat-icon" style="background:rgba(234,179,8,.1)">
                <i class="bi bi-envelope-fill" style="color:#b45309"></i>
            </div>
            <div>
                <div class="af-stat-val">{{ $feedbacks->where('status','new')->count() }}</div>
                <div class="af-stat-lbl">Baru</div>
            </div>
        </div>
        <div class="af-stat">
            <div class="af-stat-icon" style="background:rgba(99,102,241,.1)">
                <i class="bi bi-envelope-open" style="color:#6366f1"></i>
            </div>
            <div>
                <div class="af-stat-val">{{ $feedbacks->where('status','read')->count() }}</div>
                <div class="af-stat-lbl">Dibaca</div>
            </div>
        </div>
        <div class="af-stat">
            <div class="af-stat-icon" style="background:rgba(34,197,94,.1)">
                <i class="bi bi-reply-fill" style="color:#16a34a"></i>
            </div>
            <div>
                <div class="af-stat-val">{{ $feedbacks->where('status','replied')->count() }}</div>
                <div class="af-stat-lbl">Dibalas</div>
            </div>
        </div>
    </div>

    {{-- Toolbar --}}
    <form action="{{ route('admin.feedbacks.index') }}" method="GET">
        <div class="af-toolbar">
            <select name="status" class="af-select" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="new"     {{ request('status')=='new'     ? 'selected':'' }}>Baru</option>
                <option value="read"    {{ request('status')=='read'    ? 'selected':'' }}>Sudah Dibaca</option>
                <option value="replied" {{ request('status')=='replied' ? 'selected':'' }}>Dibalas</option>
            </select>

            @if(request('status'))
            <div class="af-active-chip">
                Status: {{ ['new'=>'Baru','read'=>'Dibaca','replied'=>'Dibalas'][request('status')] ?? request('status') }}
                <a href="{{ route('admin.feedbacks.index') }}" style="color:var(--primary);font-size:14px">×</a>
            </div>
            @endif
        </div>
    </form>

    {{-- Result info --}}
    <div class="af-result-info">
        Menampilkan <strong>{{ $feedbacks->count() }}</strong> dari <strong>{{ $feedbacks->total() }}</strong> feedback
    </div>

    {{-- Table --}}
    <div class="af-table-card">
        <table class="af-table">
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
                <tr class="{{ $feedback->status === 'new' ? 'unread' : '' }}">

                    {{-- User --}}
                    <td>
                        <div class="af-user">
                            <div class="af-avatar">
                                {{ $feedback->user ? strtoupper(substr($feedback->user->name, 0, 1)) : '?' }}
                            </div>
                            <div>
                                @if($feedback->user)
                                <div class="af-user-name">{{ $feedback->user->name }}</div>
                                <div style="font-size:.72rem;color:var(--tx3)">{{ $feedback->user->email }}</div>
                                @else
                                <div class="af-user-guest">Tamu</div>
                                @endif
                            </div>
                        </div>
                    </td>

                    {{-- Subject --}}
                    <td>
                        <div class="af-subject">{{ Str::limit($feedback->subject, 30) }}</div>
                    </td>

                    {{-- Message preview --}}
                    <td>
                        <div class="af-msg" title="{{ $feedback->message }}">
                            {{ $feedback->message }}
                        </div>
                    </td>

                    {{-- Status --}}
                    <td>
                        @if($feedback->status === 'new')
                        <span class="af-status af-status-new">
                            <i class="bi bi-envelope-fill"></i> Baru
                        </span>
                        @elseif($feedback->status === 'read')
                        <span class="af-status af-status-read">
                            <i class="bi bi-envelope-open-fill"></i> Dibaca
                        </span>
                        @else
                        <span class="af-status af-status-replied">
                            <i class="bi bi-reply-fill"></i> Dibalas
                        </span>
                        @endif
                    </td>

                    {{-- Date --}}
                    <td style="font-size:.8rem;color:var(--tx3);white-space:nowrap">
                        {{ $feedback->created_at->translatedFormat('d M Y') }}
                        <div style="font-size:.72rem;margin-top:1px">{{ $feedback->created_at->format('H:i') }}</div>
                    </td>

                    {{-- Actions --}}
                    <td>
                        <div class="af-actions">
                            {{-- Reply --}}
                            @if($feedback->status !== 'replied')
                            <button type="button"
                                    class="af-btn reply"
                                    title="Balas"
                                    @click="openReply({{ $feedback->id }}, '{{ addslashes($feedback->subject) }}', '{{ addslashes(Str::limit($feedback->message, 100)) }}')">
                                <i class="bi bi-reply"></i>
                            </button>
                            @endif

                            {{-- Mark as read --}}
                            @if($feedback->status === 'new')
                            <form action="{{ route('admin.feedbacks.read', $feedback->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="af-btn read" title="Tandai Dibaca">
                                    <i class="bi bi-check2-all"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="af-empty">
                            <div class="af-empty-icon">📭</div>
                            <div class="af-empty-title">
                                {{ request('status') ? 'Tidak ada feedback dengan status ini' : 'Belum ada feedback' }}
                            </div>
                            <div style="font-size:.84rem;color:var(--tx3);margin-bottom:10px">
                                {{ request('status') ? 'Coba pilih status lain.' : 'Feedback dari pengguna akan muncul di sini.' }}
                            </div>
                            @if(request('status'))
                            <a href="{{ route('admin.feedbacks.index') }}"
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
    <div class="af-pagination">
        {{ $feedbacks->links() }}
    </div>

    {{-- ── Reply Modal ── --}}
    <div class="af-modal" x-show="open" x-cloak @click.self="open = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

        <div class="af-modal-card" @click.stop>

            {{-- Header --}}
            <div class="af-modal-head">
                <div class="af-modal-title">
                    <div class="af-modal-title-icon">
                        <i class="bi bi-reply-fill"></i>
                    </div>
                    Balas Feedback
                </div>
                <button type="button" class="af-modal-close" @click="open = false">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            {{-- Body --}}
            <div class="af-modal-body">

                {{-- Original feedback preview --}}
                <div class="af-fb-preview">
                    <div class="af-fb-preview-label">Pesan Asli</div>
                    <div class="af-fb-preview-subject" x-text="subject"></div>
                    <div class="af-fb-preview-msg" x-text="message"></div>
                </div>

                {{-- Reply form --}}
                <form :action="`/admin/feedbacks/${feedbackId}/reply`" method="POST" id="replyForm">
                    @csrf
                    <label class="af-f-label">Balasan Admin</label>
                    <textarea name="admin_reply" class="af-textarea"
                              placeholder="Tulis balasan untuk pengguna…" required></textarea>
                </form>
            </div>

            {{-- Footer --}}
            <div class="af-modal-foot">
                <button type="button" class="af-modal-cancel" @click="open = false">
                    Batal
                </button>
                <button type="submit" form="replyForm" class="af-modal-send">
                    <i class="bi bi-send"></i> Kirim Balasan
                </button>
            </div>
        </div>
    </div>

</div>
@endsection