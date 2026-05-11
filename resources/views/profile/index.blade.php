@extends('layouts.app')

@section('title', 'Profil — Librova')

@push('styles')
<style>
    /* ═══════════════════════════════════════════
       PROFILE PAGE STYLES (TANPA HERO)
    ═══════════════════════════════════════════ */
    .profile-page { padding: 0 0 100px; }

    .profile-wrap {
        max-width: 900px;
        margin: 0 auto;
        padding: 40px 20px 0;
        position: relative;
        z-index: 10;
    }

    /* ── Avatar row ── */
    .avatar-row {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 24px;
        padding: 0 4px;
    }
    .avatar-ring {
        width: 96px;
        height: 96px;
        border-radius: 50%;
        border: 4px solid var(--surface);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        flex-shrink: 0;
        position: relative;
        background: var(--surface2);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .avatar-initials {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary);
    }
    .avatar-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .avatar-online {
        position: absolute;
        bottom: 4px;
        right: 4px;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: #22C55E;
        border: 2.5px solid var(--surface);
    }
    .user-name {
        font-family: 'Playfair Display', serif;
        font-size: 1.65rem;
        font-weight: 700;
        color: var(--tx);
        line-height: 1.2;
        margin-bottom: 3px;
    }
    .user-email {
        font-size: .85rem;
        color: var(--tx3);
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .verified-badge {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        font-size: .68rem;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 4px;
        background: rgba(44, 95, 46, .09);
        color: var(--primary);
    }
    [data-theme="dark"] .verified-badge {
        background: rgba(74, 222, 128, .1);
    }
    .user-since {
        font-size: .75rem;
        color: var(--tx3);
    }
    .avatar-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        padding-bottom: 8px;
    }
    .btn-edit-profile {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 9px 18px;
        border-radius: 100px;
        background: var(--primary);
        color: #fff;
        font-family: inherit;
        font-size: .82rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        box-shadow: 0 2px 10px var(--shadow);
    }
    [data-theme="dark"] .btn-edit-profile {
        color: var(--bg);
    }
    .btn-edit-profile:hover {
        background: var(--primary-h);
    }
    .btn-outline-sm {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 9px 16px;
        border-radius: 100px;
        background: var(--surface);
        color: var(--tx2);
        font-family: inherit;
        font-size: .82rem;
        font-weight: 500;
        border: 1.5px solid var(--border);
        cursor: pointer;
    }
    .btn-outline-sm:hover {
        border-color: var(--primary);
        color: var(--primary);
    }

    /* Stats Bar */
    .stats-bar {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 28px;
    }
    .stat-cell {
        padding: 18px 16px;
        text-align: center;
        position: relative;
        transition: background .2s;
    }
    .stat-cell:not(:last-child)::after {
        content: '';
        position: absolute;
        right: 0;
        top: 20%;
        bottom: 20%;
        width: 1px;
        background: var(--border);
    }
    .stat-cell:hover {
        background: var(--surface2);
    }
    .stat-num {
        font-family: 'Playfair Display', serif;
        font-size: 1.6rem;
        font-weight: 700;
        color: var(--tx);
        line-height: 1;
        margin-bottom: 4px;
    }
    .stat-label {
        font-size: .7rem;
        font-weight: 500;
        color: var(--tx3);
        text-transform: uppercase;
        letter-spacing: .05em;
    }
    .stat-icon {
        font-size: 1.2rem;
        margin-bottom: 4px;
        display: block;
    }

    /* Card */
    .profile-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 16px;
        animation: pFadeUp .5s both;
    }
    @keyframes pFadeUp {
        from {
            opacity: 0;
            transform: translateY(16px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 22px 0;
        margin-bottom: 18px;
    }
    .card-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--tx);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .card-title-icon {
        width: 32px;
        height: 32px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .card-see-all {
        font-size: .78rem;
        font-weight: 600;
        color: var(--primary);
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-decoration: none;
    }
    .card-see-all:hover {
        gap: 7px;
    }
    .card-body {
        padding: 0 22px 20px;
    }

    /* Form */
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }
    @media (max-width: 540px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }
    .form-group {
        margin-bottom: 14px;
    }
    .form-label {
        display: block;
        font-size: .78rem;
        font-weight: 600;
        color: var(--tx2);
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: .04em;
    }
    .form-input {
        width: 100%;
        padding: 11px 14px;
        border-radius: 10px;
        border: 1.5px solid var(--border);
        background: var(--bg);
        color: var(--tx);
        font-family: inherit;
        font-size: .9rem;
    }
    .form-input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(44, 95, 46, .09);
        background: var(--surface);
    }
    [data-theme="dark"] .form-input:focus {
        box-shadow: 0 0 0 3px rgba(74, 222, 128, .09);
    }
    .form-input::placeholder {
        color: var(--tx3);
    }
    .file-input-wrapper {
        position: relative;
        border: 1.5px dashed var(--border);
        border-radius: 10px;
        background: var(--bg);
        padding: 16px;
        text-align: center;
        cursor: pointer;
    }
    .file-input-wrapper input[type="file"] {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
        width: 100%;
        height: 100%;
    }
    .file-label {
        font-size: .82rem;
        color: var(--tx3);
    }

    /* Password strength */
    .pw-strength-bar {
        height: 3px;
        border-radius: 2px;
        margin-top: 6px;
        background: var(--border);
        overflow: hidden;
    }
    .pw-strength-fill {
        height: 100%;
        border-radius: 2px;
        width: 0%;
        transition: width .3s, background .3s;
    }
    .form-divider {
        height: 1px;
        background: var(--border);
        margin: 18px 0;
    }

    .btn-submit {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 24px;
        border-radius: 100px;
        background: var(--primary);
        color: #fff;
        font-family: inherit;
        font-size: .875rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        box-shadow: 0 2px 10px var(--shadow);
    }
    [data-theme="dark"] .btn-submit {
        color: var(--bg);
    }
    .btn-submit:hover {
        background: var(--primary-h);
    }
    .btn-danger {
        background: transparent;
        color: #B91C1C;
        border: 1.5px solid #FECACA;
        box-shadow: none;
    }
    [data-theme="dark"] .btn-danger {
        color: #FCA5A5;
        border-color: rgba(252, 165, 165, .3);
    }

    /* Alert */
    .alert {
        border-radius: 10px;
        padding: 12px 16px;
        margin-bottom: 20px;
        font-size: .88rem;
        display: flex;
        align-items: flex-start;
        gap: 10px;
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
    .alert-error {
        background: #FEF2F2;
        color: #B91C1C;
        border: 1px solid #FECACA;
    }
    [data-theme="dark"] .alert-error {
        background: rgba(252, 165, 165, .09);
        color: #FCA5A5;
        border-color: rgba(252, 165, 165, .2);
    }

    /* Mini grid */
    .mini-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
        gap: 10px;
    }
    .mini-bk {
        aspect-ratio: 2/3;
        border-radius: 8px;
        overflow: hidden;
        position: relative;
        cursor: pointer;
        transition: transform .25s cubic-bezier(.34, 1.56, .64, 1), box-shadow .2s;
    }
    .mini-bk:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px var(--shadow);
    }
    .mini-bk::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0, 0, 0, .5) 0%, transparent 50%);
    }
    .mini-bk-title {
        position: absolute;
        bottom: 5px;
        left: 6px;
        right: 6px;
        z-index: 1;
        font-family: 'Playfair Display', serif;
        font-size: .55rem;
        color: #fff;
        font-weight: 600;
        line-height: 1.3;
    }

    /* Danger zone */
    .danger-zone {
        border: 1px solid #FECACA;
        border-radius: 16px;
        background: var(--surface);
        overflow: hidden;
        margin-top: 24px;
    }
    [data-theme="dark"] .danger-zone {
        border-color: rgba(252, 165, 165, .2);
    }
    .danger-header {
        padding: 16px 22px;
        border-bottom: 1px solid #FECACA;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    [data-theme="dark"] .danger-header {
        border-color: rgba(252, 165, 165, .2);
    }
    .danger-title {
        font-family: 'Playfair Display', serif;
        font-size: .95rem;
        font-weight: 700;
        color: #B91C1C;
    }
    [data-theme="dark"] .danger-title {
        color: #FCA5A5;
    }
    .danger-body {
        padding: 16px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }
    .danger-desc {
        font-size: .82rem;
        color: var(--tx3);
        max-width: 400px;
        line-height: 1.5;
    }

    /* Responsive */
    @media (max-width: 600px) {
        .stats-bar {
            grid-template-columns: repeat(2, 1fr);
        }
        .stats-bar .stat-cell:nth-child(2)::after {
            display: none;
        }
        .avatar-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }
        .avatar-actions {
            padding-bottom: 0;
        }
    }
</style>
@endpush

@section('content')
<div class="profile-page">

    <div class="profile-wrap">

        {{-- Alert Sukses --}}
        @if(session('success'))
            <div class="alert alert-success" id="successAlert">
                <span class="alert-icon"><i class="bi bi-check-circle-fill"></i></span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <span class="alert-icon"><i class="bi bi-exclamation-triangle-fill"></i></span>
                <span>
                    @foreach($errors->all() as $error)
                        {{ $error }}@if(!$loop->last), @endif
                    @endforeach
                </span>
            </div>
        @endif

        {{-- Avatar Row --}}
        <div class="avatar-row">
            <div style="display:flex;align-items:flex-end;gap:16px">
                <div class="avatar-ring">
                    @if($user->avatar)
                        <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="avatar-img">
                    @else
                        <span class="avatar-initials">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    @endif
                    <span class="avatar-online"></span>
                </div>
                <div>
                    <div class="user-name">{{ $user->name }}</div>
                    <div class="user-email">
                        {{ $user->email }}
                        @if($user->hasVerifiedEmail())
                            <span class="verified-badge"><i class="bi bi-check-circle-fill"></i> Terverifikasi</span>
                        @endif
                    </div>
                    <div class="user-since">Bergabung sejak {{ $user->created_at->translatedFormat('F Y') }}</div>
                </div>
            </div>
            <div class="avatar-actions">
                <button class="btn-outline-sm" onclick="shareProfile()">
                    <i class="bi bi-share"></i> Bagikan
                </button>
                <button class="btn-edit-profile" onclick="document.getElementById('editCard').scrollIntoView({behavior:'smooth'})">
                    <i class="bi bi-pencil-square"></i> Edit Profil
                </button>
            </div>
        </div>

        {{-- Stats Bar --}}
        <div class="stats-bar">
            <div class="stat-cell">
                <span class="stat-icon"><i class="bi bi-bookmark-fill"></i></span>
                <div class="stat-num">{{ $stats['bookmarks'] }}</div>
                <div class="stat-label">Bookmark</div>
            </div>
            <div class="stat-cell">
                <span class="stat-icon"><i class="bi bi-book-fill"></i></span>
                <div class="stat-num">{{ $stats['read'] }}</div>
                <div class="stat-label">Dibaca</div>
            </div>
            <div class="stat-cell">
                <span class="stat-icon"><i class="bi bi-chat-dots-fill"></i></span>
                <div class="stat-num">{{ $stats['reviews'] }}</div>
                <div class="stat-label">Review</div>
            </div>
            <div class="stat-cell">
                <span class="stat-icon"><i class="bi bi-star-fill"></i></span>
                <div class="stat-num">{{ $stats['ratings'] }}</div>
                <div class="stat-label">Rating</div>
            </div>
        </div>

        {{-- Edit Profil --}}
        <div class="profile-card" id="editCard">
            <div class="card-header">
                <div class="card-title">
                    <div class="card-title-icon" style="background:rgba(44,95,46,.09)"><i class="bi bi-pencil-fill" style="color:var(--primary)"></i></div>
                    Edit Profil
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap</label>
                            <input class="form-input" type="text" name="name" value="{{ old('name', $user->name) }}" placeholder="Nama lengkap kamu" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input class="form-input" type="email" value="{{ $user->email }}" disabled style="opacity:.6;cursor:not-allowed">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Foto Profil</label>
                        <div class="file-input-wrapper">
                            <input type="file" name="avatar" accept="image/*">
                            <div class="file-label">
                                <i class="bi bi-camera-fill" style="font-size:1.2rem"></i> <strong>Klik untuk pilih foto</strong> atau drag & drop<br>
                                <span style="font-size:.75rem">PNG, JPG maksimal 2MB</span>
                            </div>
                        </div>
                    </div>

                    <div style="display:flex;gap:10px;margin-top:4px">
                        <button type="submit" class="btn-submit">
                            <i class="bi bi-check-lg"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Ganti Password --}}
        <div class="profile-card">
            <div class="card-header">
                <div class="card-title">
                    <div class="card-title-icon" style="background:rgba(26,92,122,.09)"><i class="bi bi-lock-fill" style="color:#1a5c7a"></i></div>
                    Ganti Password
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.password.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label class="form-label">Password Saat Ini</label>
                        <div style="position:relative">
                            <input class="form-input" type="password" name="current_password" id="currPw" placeholder="Masukkan password lama" required style="padding-right:42px">
                            <button type="button" onclick="togglePw('currPw',this)" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--tx3);cursor:pointer;font-size:1.2rem"><i class="bi bi-eye-fill"></i></button>
                        </div>
                    </div>
                    <div class="form-divider"></div>
                    <div class="form-row">
                        <div class="form-group" style="margin-bottom:0">
                            <label class="form-label">Password Baru</label>
                            <div style="position:relative">
                                <input class="form-input" type="password" name="password" id="newPw" placeholder="Min. 8 karakter" required oninput="updateStrength(this.value)" style="padding-right:42px">
                                <button type="button" onclick="togglePw('newPw',this)" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--tx3);cursor:pointer;font-size:1.2rem"><i class="bi bi-eye-fill"></i></button>
                            </div>
                            <div class="pw-strength-bar"><div class="pw-strength-fill" id="pwFill"></div></div>
                            <div id="pwHint" style="font-size:.7rem;color:var(--tx3);margin-top:4px">Kekuatan password</div>
                        </div>
                        <div class="form-group" style="margin-bottom:0">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <input class="form-input" type="password" name="password_confirmation" placeholder="Ulangi password baru" required>
                        </div>
                    </div>
                    <div style="margin-top:16px">
                        <button type="submit" class="btn-submit">
                            <i class="bi bi-shield-lock-fill"></i>
                            Ganti Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Bookmark Terbaru --}}
        @if($recentBooks->count())
        <div class="profile-card">
            <div class="card-header">
                <div class="card-title">
                    <div class="card-title-icon" style="background:rgba(201,168,76,.1)"><i class="bi bi-bookmark-star-fill" style="color:var(--gold)"></i></div>
                    Bookmark Terbaru
                </div>
                <a href="{{ route('profile.bookmarks') }}" class="card-see-all">
                    Lihat Semua
                    <i class="bi bi-arrow-right" style="font-size:0.7rem"></i>
                </a>
            </div>
            <div class="card-body">
                <div class="mini-grid">
                    @foreach($recentBooks as $bm)
                        <a href="{{ route('books.show', $bm->book->slug) }}" class="mini-bk" style="background:linear-gradient(145deg, {{ $bm->book->cover_color ?? '#2C5F2E' }}, {{ $bm->book->cover_color_dark ?? '#1d4220' }})">
                            <div class="mini-bk-title">{{ Str::limit($bm->book->title, 15) }}</div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- Danger Zone --}}
        <div class="danger-zone">
            <div class="danger-header">
                <span style="font-size:1.2rem;color:#B91C1C"><i class="bi bi-exclamation-triangle-fill"></i></span>
                <div class="danger-title">Zona Berbahaya</div>
            </div>
            <div class="danger-body">
                <div>
                    <div style="font-size:.88rem;font-weight:600;color:#B91C1C;margin-bottom:3px">Hapus Akun</div>
                    <p class="danger-desc">Tindakan ini tidak dapat dibatalkan. Semua data, bookmark, review, dan riwayat baca kamu akan dihapus secara permanen.</p>
                </div>
                <button class="btn-submit btn-danger" onclick="alert('Fitur ini belum tersedia.')">
                    <i class="bi bi-trash-fill"></i>
                    Hapus Akun Saya
                </button>
            </div>
        </div>

        <div style="height:40px"></div>
    </div>
</div>

<x-mobile-bottom-nav active="profile" />

@endsection

@push('scripts')
<script>
    function togglePw(id, btn) {
        const input = document.getElementById(id);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye-fill', 'bi-eye-slash-fill');
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye-slash-fill', 'bi-eye-fill');
        }
    }

    function updateStrength(val) {
        const fill = document.getElementById('pwFill');
        const hint = document.getElementById('pwHint');
        let score = 0;
        if (val.length >= 8) score++;
        if (/[A-Z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

        const levels = [
            { w:'0%',   c:'transparent', t:'Masukkan password' },
            { w:'25%',  c:'#ef4444',     t:'Lemah' },
            { w:'50%',  c:'#f97316',     t:'Cukup' },
            { w:'75%',  c:'#eab308',     t:'Kuat' },
            { w:'100%', c:'#22c55e',     t:'Sangat Kuat ✓' },
        ];
        const l = levels[score];
        fill.style.width = l.w;
        fill.style.background = l.c;
        hint.textContent = l.t;
        hint.style.color = l.c === 'transparent' ? 'var(--tx3)' : l.c;
    }

    setTimeout(() => {
        const a = document.getElementById('successAlert');
        if (a) { a.style.transition = 'opacity .4s'; a.style.opacity = '0'; setTimeout(() => a.remove(), 400); }
    }, 4000);

    function shareProfile() {
        const url = window.location.href;
        navigator.clipboard.writeText(url).then(() => {
            alert('Link profil disalin ke clipboard!');
        });
    }
</script>
@endpush