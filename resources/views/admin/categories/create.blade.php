@extends('layouts.admin')

@section('title', 'Tambah Kategori — Admin Librova')
@section('header-title', 'Tambah Kategori')

@push('styles')
<style>
    .form-card {
        background: var(--surface); border: 1px solid var(--border); border-radius: 20px;
        padding: 28px; max-width: 600px; margin: 0 auto;
    }
    .form-group { margin-bottom: 20px; }
    .form-label {
        display: block; font-size: .78rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: .05em; color: var(--tx2); margin-bottom: 6px;
    }
    .form-input, .form-select, .form-textarea {
        width: 100%; padding: 11px 14px; border-radius: 10px; border: 1.5px solid var(--border);
        background: var(--bg); color: var(--tx); font-family: inherit; font-size: .9rem;
        transition: border-color .2s, box-shadow .2s;
    }
    .form-input:focus, .form-select:focus, .form-textarea:focus {
        outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(44,95,46,.09);
    }
    [data-theme="dark"] .form-input:focus, [data-theme="dark"] .form-select:focus, [data-theme="dark"] .form-textarea:focus {
        box-shadow: 0 0 0 3px rgba(74,222,128,.09);
    }
    .form-textarea { resize: vertical; min-height: 100px; }
    .form-select { cursor: pointer; }
    .btn-save {
        padding: 12px 28px; border-radius: 100px; background: var(--primary); color: #fff;
        font-weight: 600; font-size: .9rem; border: none; cursor: pointer;
        display: inline-flex; align-items: center; gap: 8px; transition: background .2s;
    }
    [data-theme="dark"] .btn-save { color: var(--bg); }
    .btn-save:hover { background: var(--primary-h); }
    .btn-back {
        padding: 12px 24px; border-radius: 100px; border: 1.5px solid var(--border);
        background: var(--surface); color: var(--tx2); font-weight: 600; font-size: .9rem;
        text-decoration: none; display: inline-flex; align-items: center; gap: 8px;
    }
    .btn-back:hover { border-color: var(--primary); color: var(--primary); }
</style>
@endpush

@section('content')
<div style="padding: 28px 28px 40px; max-width: 640px; margin: 0 auto;">
    <div class="form-card">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label">Nama Kategori</label>
                <input class="form-input" type="text" name="name" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Induk Kategori (opsional)</label>
                <select class="form-select" name="parent_id">
                    <option value="">— Tanpa Induk —</option>
                    @foreach($parentCategories as $parent)
                        <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi (opsional)</label>
                <textarea class="form-textarea" name="description" rows="3">{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Ikon (emoji atau teks, opsional)</label>
                <input class="form-input" type="text" name="icon" value="{{ old('icon') }}" placeholder="Contoh: 📚">
            </div>

            @if($errors->any())
            <div class="alert" style="background:#FEF2F2;color:#B91C1C;margin-bottom:20px;">
                <ul style="margin:0;padding-left:18px;">
                    @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                </ul>
            </div>
            @endif

            <div style="display:flex;gap:12px;justify-content:flex-end;">
                <a href="{{ route('admin.categories.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Batal</a>
                <button type="submit" class="btn-save"><i class="bi bi-check-lg"></i> Simpan Kategori</button>
            </div>
        </form>
    </div>
</div>
@endsection