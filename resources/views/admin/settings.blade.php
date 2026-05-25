@extends('layouts.admin')

@section('title', 'Pengaturan — Admin Librova')
@section('header-title', 'Pengaturan Sistem')

@push('styles')
<style>
    .settings-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 24px;
        max-width: 700px;
        animation: cardUp 0.5s ease both;
    }
    .settings-card:last-child { margin-bottom: 0; }
    .card-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.15rem; font-weight: 700;
        color: var(--tx); margin-bottom: 18px;
        display: flex; align-items: center; gap: 8px;
    }
    .form-group { margin-bottom: 18px; }
    .form-label {
        display: block; font-size: .78rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .05em;
        color: var(--tx2); margin-bottom: 6px;
    }
    .form-input, .form-textarea {
        width: 100%; padding: 10px 14px;
        border-radius: 10px; border: 1.5px solid var(--border);
        background: var(--bg); color: var(--tx);
        font-family: inherit; font-size: .9rem;
        transition: border-color .2s, box-shadow .2s;
    }
    .form-input:focus, .form-textarea:focus {
        outline: none; border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(44,95,46,.09);
    }
    [data-theme="dark"] .form-input:focus, [data-theme="dark"] .form-textarea:focus {
        box-shadow: 0 0 0 3px rgba(74,222,128,.09);
    }
    .form-textarea { resize: vertical; min-height: 80px; }
    .form-hint { font-size: .75rem; color: var(--tx3); margin-top: 4px; }
    .file-preview {
        width: 100px; height: 100px; object-fit: contain;
        border: 1px solid var(--border); border-radius: 8px; margin-bottom: 8px;
    }
    .btn-save {
        padding: 10px 24px; border-radius: 100px;
        background: var(--primary); color: #fff;
        font-family: inherit; font-size: .88rem; font-weight: 600;
        border: none; cursor: pointer;
        display: inline-flex; align-items: center; gap: 8px;
        transition: background .2s, transform .15s;
    }
    [data-theme="dark"] .btn-save { color: var(--bg); }
    .btn-save:hover { background: var(--primary-h); transform: translateY(-1px); }
    .alert {
        padding: 12px 16px; border-radius: 10px; margin-bottom: 20px;
        font-size: .88rem; display: flex; align-items: flex-start; gap: 10px;
    }
    .alert-success { background: #E8F5E9; color: #1a4a1c; border: 1px solid #A5D6A7; }
    [data-theme="dark"] .alert-success { background: rgba(74,222,128,.09); color: #86EFAC; border-color: rgba(74,222,128,.2); }
</style>
@endpush

@section('content')
<div style="padding: 28px 28px 40px; max-width: 760px; margin: 0 auto;">

    @if(session('success'))
    <div class="alert alert-success">
        <i class="bi bi-check-circle-fill" style="font-size:1rem;flex-shrink:0;margin-top:1px"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- General --}}
        <div class="settings-card">
            <div class="card-title">
                <i class="bi bi-sliders" style="color:var(--primary)"></i> Umum
            </div>
            <div class="form-group">
                <label class="form-label">Nama Situs</label>
                <input class="form-input" type="text" name="site_name"
                       value="{{ old('site_name', $settings['site_name'] ?? 'Librova') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi Situs</label>
                <textarea class="form-textarea" name="site_description" rows="2">{{ old('site_description', $settings['site_description'] ?? '') }}</textarea>
                <div class="form-hint">Deskripsi singkat untuk footer atau meta tag.</div>
            </div>
        </div>

        {{-- Branding --}}
        <div class="settings-card">
            <div class="card-title">
                <i class="bi bi-image" style="color:#6366f1"></i> Branding
            </div>
            <div class="form-group">
                <label class="form-label">Logo (disarankan 200×60 px)</label>
                @if(!empty($settings['logo_url']))
                    <img src="{{ $settings['logo_url'] }}" class="file-preview" alt="Logo saat ini">
                    <div style="margin-bottom:6px;">
                        <label style="font-size:.82rem;color:var(--tx2);display:flex;align-items:center;gap:6px">
                            <input type="checkbox" name="remove_logo" value="1"> Hapus logo saat ini
                        </label>
                    </div>
                @endif
                <input class="form-input" type="file" name="logo" accept="image/*" style="padding:9px;">
                <div class="form-hint">Format PNG, JPG, atau SVG. Maks 1MB.</div>
            </div>
            <div class="form-group">
                <label class="form-label">Favicon (disarankan 32×32 px)</label>
                @if(!empty($settings['favicon_url']))
                    <img src="{{ $settings['favicon_url'] }}" class="file-preview" alt="Favicon saat ini" style="width:48px;height:48px;">
                    <div style="margin-bottom:6px;">
                        <label style="font-size:.82rem;color:var(--tx2);display:flex;align-items:center;gap:6px">
                            <input type="checkbox" name="remove_favicon" value="1"> Hapus favicon saat ini
                        </label>
                    </div>
                @endif
                <input class="form-input" type="file" name="favicon" accept="image/png,image/x-icon,image/vnd.microsoft.icon" style="padding:9px;">
                <div class="form-hint">Format PNG atau ICO. Maks 512KB.</div>
            </div>
        </div>

        {{-- Submit --}}
        <div style="display:flex;justify-content:flex-end;">
            <button type="submit" class="btn-save">
                <i class="bi bi-check-lg"></i> Simpan Pengaturan
            </button>
        </div>
    </form>

</div>
@endsection