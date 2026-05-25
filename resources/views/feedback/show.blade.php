@extends('layouts.app')

@section('title', 'Feedback — Librova')

@push('styles')
<style>
    .feedback-page {
        max-width: 780px;
        margin: 0 auto;
        padding: 2rem 1rem 4rem;
    }

    .feedback-header {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 2rem;
    }
    .feedback-header-icon {
        width: 52px; height: 52px;
        border-radius: 14px;
        background: rgba(99,102,241,0.1);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.6rem; color: #6366f1;
        flex-shrink: 0;
    }
    .feedback-header h2 {
        font-family: 'Playfair Display', serif;
        font-size: 1.8rem; font-weight: 700; color: var(--tx);
        margin: 0;
    }
    .feedback-header p {
        font-size: 0.88rem; color: var(--tx3); margin: 4px 0 0;
    }

    /* Alert */
    .alert {
        padding: 12px 16px; border-radius: 10px; margin-bottom: 20px;
        font-size: 0.88rem; display: flex; align-items: flex-start; gap: 10px;
    }
    .alert-success { background: #E8F5E9; color: #1a4a1c; border: 1px solid #A5D6A7; }
    [data-theme="dark"] .alert-success { background: rgba(74,222,128,.09); color: #86EFAC; border-color: rgba(74,222,128,.2); }
    .alert-error { background: #FEF2F2; color: #B91C1C; border: 1px solid #FECACA; }
    [data-theme="dark"] .alert-error { background: rgba(252,165,165,.09); color: #FCA5A5; border-color: rgba(252,165,165,.2); }

    /* Form Card */
    .form-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 28px;
    }
    .form-card-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.15rem; font-weight: 700; color: var(--tx);
        margin-bottom: 18px;
        display: flex; align-items: center; gap: 8px;
    }
    .form-group {
        margin-bottom: 16px;
    }
    .form-label {
        display: block;
        font-size: 0.78rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.05em;
        color: var(--tx2); margin-bottom: 6px;
    }
    .form-input, .form-textarea {
        width: 100%; padding: 11px 14px;
        border-radius: 10px; border: 1.5px solid var(--border);
        background: var(--bg); color: var(--tx);
        font-family: inherit; font-size: 0.9rem;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-input:focus, .form-textarea:focus {
        outline: none; border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(44,95,46,.09);
    }
    [data-theme="dark"] .form-input:focus, [data-theme="dark"] .form-textarea:focus {
        box-shadow: 0 0 0 3px rgba(74,222,128,.09);
    }
    .form-textarea { resize: vertical; min-height: 120px; }
    .form-input::placeholder, .form-textarea::placeholder { color: var(--tx3); }

    .btn-submit {
        padding: 11px 24px; border-radius: 100px;
        background: var(--primary); color: #fff;
        font-family: inherit; font-size: 0.9rem; font-weight: 600;
        border: none; cursor: pointer;
        display: inline-flex; align-items: center; gap: 8px;
        transition: background 0.2s, transform 0.15s;
    }
    [data-theme="dark"] .btn-submit { color: var(--bg); }
    .btn-submit:hover { background: var(--primary-h); transform: translateY(-1px); }

    /* History Card */
    .history-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 16px;
    }
    .history-card-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 16px 20px;
        border-bottom: 1px solid var(--border);
    }
    .history-card-title {
        font-family: 'Playfair Display', serif;
        font-size: 1rem; font-weight: 700; color: var(--tx);
    }
    .history-card-body {
        padding: 16px 20px;
        font-size: 0.9rem; color: var(--tx2); line-height: 1.65;
    }
    .history-card-footer {
        padding: 12px 20px;
        border-top: 1px solid var(--border);
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 8px;
        font-size: 0.78rem; color: var(--tx3);
    }

    .status-badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 4px 10px; border-radius: 100px;
        font-size: 0.72rem; font-weight: 600;
    }
    .status-new { background: rgba(234,179,8,.15); color: #b45309; }
    .status-read { background: rgba(99,102,241,.1); color: #4f46e5; }
    .status-replied { background: rgba(34,197,94,.12); color: #16a34a; }
    [data-theme="dark"] .status-new { background: rgba(251,191,36,.15); color: #fbbf24; }
    [data-theme="dark"] .status-read { background: rgba(99,102,241,.2); color: #a5b4fc; }
    [data-theme="dark"] .status-replied { background: rgba(74,222,128,.12); color: #4ADE80; }

    .admin-reply {
        background: var(--surface2);
        border-radius: 10px;
        padding: 14px 18px;
        margin-top: 12px;
        font-size: 0.88rem; color: var(--tx2); line-height: 1.6;
    }
    .admin-reply-label {
        font-size: 0.72rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.05em;
        color: var(--primary); margin-bottom: 4px;
    }

    .empty-state {
        text-align: center; padding: 3rem 1rem; color: var(--tx3);
    }
    .empty-state i { font-size: 2.5rem; display: block; margin-bottom: 12px; opacity: 0.5; }
    .empty-state h3 { font-family: 'Playfair Display', serif; color: var(--tx2); margin-bottom: 8px; }
</style>
@endpush

@section('content')
<div class="feedback-page">

    {{-- Header --}}
    <div class="feedback-header">
        <div class="feedback-header-icon">
            <i class="bi bi-chat-heart"></i>
        </div>
        <div>
            <h2>Feedback</h2>
            <p>Sampaikan masukan, saran, atau permintaan koleksi buku.</p>
        </div>
    </div>

    {{-- Alert --}}
    @if(session('success'))
    <div class="alert alert-success">
        <i class="bi bi-check-circle-fill" style="font-size:1rem;flex-shrink:0;margin-top:1px"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-error">
        <i class="bi bi-exclamation-circle-fill" style="font-size:1rem;flex-shrink:0;margin-top:1px"></i>
        <ul style="margin:0;padding-left:1.2rem">
            @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Form Card --}}
    <div class="form-card">
        <div class="form-card-title">
            <i class="bi bi-pencil-square" style="color:var(--primary)"></i> Kirim Feedback
        </div>
        <form action="{{ route('feedback.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label" for="subject">Subjek</label>
                <input class="form-input" type="text" name="subject" id="subject"
                       value="{{ old('subject') }}" required
                       placeholder="Contoh: Permintaan koleksi buku">
            </div>
            <div class="form-group">
                <label class="form-label" for="message">Pesan</label>
                <textarea class="form-textarea" name="message" id="message" required
                          placeholder="Tulis masukan, saran, atau permintaan koleksi buku…">{{ old('message') }}</textarea>
            </div>
            <button type="submit" class="btn-submit">
                <i class="bi bi-send"></i> Kirim Masukan
            </button>
        </form>
    </div>

    {{-- Riwayat Feedback --}}
    @if($feedbacks->count())
    <div style="margin-top:32px;">
        <h3 style="font-family:'Playfair Display',serif; font-size:1.3rem; color:var(--tx); margin-bottom:18px;">
            <i class="bi bi-clock-history" style="color:var(--primary)"></i> Riwayat Feedback
        </h3>
        @foreach($feedbacks as $fb)
        <div class="history-card">
            <div class="history-card-header">
                <div class="history-card-title">{{ $fb->subject }}</div>
                @if($fb->status === 'new')
                    <span class="status-badge status-new">
                        <i class="bi bi-hourglass-split" style="font-size:.65rem"></i> Menunggu
                    </span>
                @elseif($fb->status === 'read')
                    <span class="status-badge status-read">
                        <i class="bi bi-check-circle" style="font-size:.65rem"></i> Dibaca
                    </span>
                @elseif($fb->status === 'replied')
                    <span class="status-badge status-replied">
                        <i class="bi bi-check-circle-fill" style="font-size:.65rem"></i> Dibalas
                    </span>
                @endif
            </div>
            <div class="history-card-body">
                {{ $fb->message }}
                @if($fb->admin_reply)
                <div class="admin-reply">
                    <div class="admin-reply-label"><i class="bi bi-shield-check"></i> Balasan Admin</div>
                    {{ $fb->admin_reply }}
                </div>
                @endif
            </div>
            <div class="history-card-footer">
                <span><i class="bi bi-clock"></i> {{ $fb->created_at->translatedFormat('d M Y, H:i') }}</span>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="empty-state" style="margin-top:32px;">
        <i class="bi bi-inbox"></i>
        <h3>Belum ada feedback</h3>
        <p>Kamu belum mengirim feedback. Gunakan form di atas untuk mengirim masukan.</p>
    </div>
    @endif

</div>

<x-mobile-bottom-nav active="profile" />
@endsection