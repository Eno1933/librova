@extends('layouts.admin')

@section('title', 'Tambah Buku — Admin Librova')
@section('header-title', 'Tambah Buku Baru')

@push('styles')
<style>
    .form-card {
        background: var(--surface); border: 1px solid var(--border); border-radius: 20px;
        padding: 28px; max-width: 800px; margin: 0 auto;
    }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    @media (max-width: 600px) { .form-grid { grid-template-columns: 1fr; } }
    .form-group { margin-bottom: 20px; }
    .form-label {
        display: block; font-size: .78rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: .05em; color: var(--tx2); margin-bottom: 6px;
    }
    .form-input, .form-textarea, .form-select {
        width: 100%; padding: 11px 14px; border-radius: 10px; border: 1.5px solid var(--border);
        background: var(--bg); color: var(--tx); font-family: inherit; font-size: .9rem;
        transition: border-color .2s, box-shadow .2s;
    }
    .form-input:focus, .form-textarea:focus, .form-select:focus {
        outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(44,95,46,.09);
    }
    [data-theme="dark"] .form-input:focus, [data-theme="dark"] .form-textarea:focus, [data-theme="dark"] .form-select:focus {
        box-shadow: 0 0 0 3px rgba(74,222,128,.09);
    }
    .form-textarea { resize: vertical; min-height: 100px; }
    .form-select { cursor: pointer; }
    .form-check { display: flex; align-items: center; gap: 10px; }
    .form-check input { width: 18px; height: 18px; accent-color: var(--primary); }
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
<div style="padding: 28px 28px 40px; max-width: 860px; margin: 0 auto;">
    <div class="form-card">
        <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Judul Buku</label>
                    <input class="form-input" type="text" name="title" value="{{ old('title') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Penulis</label>
                    <input class="form-input" type="text" name="author" value="{{ old('author') }}" required>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">ISBN</label>
                    <input class="form-input" type="text" name="isbn" value="{{ old('isbn') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <select class="form-select" name="category_id">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea class="form-textarea" name="description" rows="4">{{ old('description') }}</textarea>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Bahasa</label>
                    <input class="form-input" type="text" name="language" value="{{ old('language', 'Indonesia') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Tahun Terbit</label>
                    <input class="form-input" type="number" name="published_year" value="{{ old('published_year') }}">
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Jumlah Halaman</label>
                    <input class="form-input" type="number" name="total_pages" value="{{ old('total_pages') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Cover Buku (opsional, max 2MB)</label>
                    <input class="form-input" type="file" name="cover_image" accept="image/*" style="padding:9px;">
                </div>
                <div class="form-group">
                    <label class="form-label">File PDF (opsional, max 50MB)</label>
                    <input class="form-input" type="file" name="file_path" accept=".pdf" style="padding:9px;">
                </div>
            </div>

            <div style="display:flex;gap:20px;flex-wrap:wrap;margin-bottom:24px;">
                <label class="form-check">
                    <input type="checkbox" name="is_downloadable" value="1" {{ old('is_downloadable') ? 'checked' : '' }}>
                    <span style="font-size:.88rem;font-weight:500;color:var(--tx2)">Bisa diunduh</span>
                </label>
                <label class="form-check">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                    <span style="font-size:.88rem;font-weight:500;color:var(--tx2)">Featured (unggulan)</span>
                </label>
            </div>

            @if($errors->any())
            <div class="alert" style="background:#FEF2F2;color:#B91C1C;margin-bottom:20px;">
                <ul style="margin:0;padding-left:18px;">
                    @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                </ul>
            </div>
            @endif

            <div style="display:flex;gap:12px;justify-content:flex-end;">
                <a href="{{ route('admin.books.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Batal</a>
                <button type="submit" class="btn-save"><i class="bi bi-check-lg"></i> Simpan Buku</button>
            </div>
        </form>
    </div>
</div>
@endsection