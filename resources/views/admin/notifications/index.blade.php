@extends('layouts.admin')

@section('title', 'Notifikasi — Admin Librova')
@section('header-title', 'Notifikasi')

@push('styles')
<style>
    .notif-list { display: flex; flex-direction: column; gap: 8px; }
    .notif-item {
        display: flex; align-items: center; gap: 14px;
        padding: 16px 20px;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 14px;
        text-decoration: none;
        transition: background .15s, border-color .15s;
    }
    .notif-item:hover { background: var(--surface2); border-color: var(--border2); }
    .notif-icon {
        width: 42px; height: 42px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; flex-shrink: 0;
    }
    .notif-body { flex: 1; min-width: 0; }
    .notif-message { font-size: .9rem; color: var(--tx); line-height: 1.4; }
    .notif-time { font-size: .76rem; color: var(--tx3); margin-top: 3px; }
    .notif-dot {
        width: 8px; height: 8px; border-radius: 50%;
        background: var(--primary); flex-shrink: 0;
    }
    .notif-dot.read { background: var(--tx3); opacity: .5; }
    .notif-arrow { color: var(--tx3); font-size: .8rem; }
    .pagination-wrap { margin-top: 24px; display: flex; justify-content: center; }
    .pagination-wrap nav { display: flex; gap: 6px; }
    .pagination-wrap .page-item .page-link {
        display: flex; align-items: center; justify-content: center;
        min-width: 36px; height: 36px; border-radius: 8px;
        font-size: .85rem; font-weight: 500; color: var(--tx2);
        background: var(--surface); border: 1px solid var(--border); text-decoration: none;
    }
    .pagination-wrap .page-item.active .page-link { background: var(--primary); color: #fff; border-color: var(--primary); }
    [data-theme="dark"] .pagination-wrap .page-item.active .page-link { color: var(--bg); }
</style>
@endpush

@section('content')
<div style="padding: 28px 28px 40px; max-width: 700px; margin: 0 auto;">

    <div style="display:flex; align-items:center; justify-content: space-between; margin-bottom: 20px;">
        <h2 style="font-family:'Playfair Display',serif; font-size:1.5rem; color:var(--tx);">
            <i class="bi bi-bell"></i> Notifikasi
        </h2>
        <form action="{{ route('admin.notifications.markAllRead') }}" method="POST">
            @csrf
            <button type="submit" style="background:none; border:1.5px solid var(--border); border-radius:100px; padding:8px 16px; font-size:.82rem; color:var(--tx2); cursor:pointer;">
                <i class="bi bi-check-all"></i> Tandai semua sudah dibaca
            </button>
        </form>
    </div>

    @if($notifications->count())
        <div class="notif-list">
            @foreach($notifications as $notif)
                <a href="{{ route('admin.notifications.read', $notif->id) }}" class="notif-item">
                    <div class="notif-icon" style="background:
                        @if($notif->type === 'new_feedback') rgba(99,102,241,.1)
                        @elseif($notif->type === 'new_review') rgba(245,158,11,.1)
                        @elseif($notif->type === 'new_user') rgba(34,197,94,.1)
                        @else rgba(44,95,46,.1)
                        @endif">
                        <i class="bi
                            @if($notif->type === 'new_feedback') bi-envelope
                            @elseif($notif->type === 'new_review') bi-chat-dots
                            @elseif($notif->type === 'new_user') bi-person-plus
                            @else bi-bell
                            @endif"
                            style="color:
                            @if($notif->type === 'new_feedback') #6366f1
                            @elseif($notif->type === 'new_review') var(--gold)
                            @elseif($notif->type === 'new_user') #16a34a
                            @else var(--primary)
                            @endif"></i>
                    </div>
                    <div class="notif-body">
                        <div class="notif-message">{{ $notif->message }}</div>
                        <div class="notif-time">{{ $notif->created_at->diffForHumans() }}</div>
                    </div>
                    <div class="notif-dot {{ $notif->read_at ? 'read' : '' }}"></div>
                    <i class="bi bi-chevron-right notif-arrow"></i>
                </a>
            @endforeach
        </div>

        <div class="pagination-wrap">
            {{ $notifications->links() }}
        </div>
    @else
        <div style="text-align:center; padding: 3rem; color: var(--tx3);">
            <i class="bi bi-bell-slash" style="font-size:2rem; display:block; margin-bottom:12px;"></i>
            <p>Belum ada notifikasi.</p>
        </div>
    @endif

</div>
@endsection