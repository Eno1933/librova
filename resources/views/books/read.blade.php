@extends('layouts.app')

@section('title', 'Baca: ' . $book->title)

@push('styles')
<style>
    .reader-wrap {
        display: flex;
        flex-direction: column;
        min-height: calc(100vh - var(--nav-h, 68px));
        background: var(--bg);
    }

    /* ── Toolbar ── */
    .reader-toolbar {
        position: sticky;
        top: var(--nav-h, 68px);
        z-index: 30;
        background: var(--surface);
        border-bottom: 1px solid var(--border);
        padding: 10px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
    }

    .reader-book-info h3 {
        font-family: 'Playfair Display', serif;
        font-size: .95rem;
        color: var(--tx);
        margin-bottom: 1px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 300px;
    }
    .reader-book-info span {
        font-size: .76rem;
        color: var(--tx3);
    }

    /* Controls */
    .reader-controls {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .ctrl-btn {
        width: 34px; height: 34px;
        border-radius: 8px;
        border: 1.5px solid var(--border);
        background: var(--surface);
        color: var(--tx2);
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        font-size: .95rem;
        transition: background .15s, border-color .15s, color .15s;
    }
    .ctrl-btn:hover:not(:disabled) {
        background: var(--surface2);
        border-color: var(--primary);
        color: var(--primary);
    }
    .ctrl-btn:disabled { opacity: .4; cursor: not-allowed; }

    .ctrl-sep {
        width: 1px; height: 22px;
        background: var(--border);
        margin: 0 2px;
    }

    .page-info {
        font-size: .82rem;
        color: var(--tx2);
        display: flex; align-items: center; gap: 5px;
        white-space: nowrap;
    }
    .page-jump {
        width: 52px;
        padding: 5px 6px;
        border-radius: 7px;
        border: 1.5px solid var(--border);
        background: var(--bg);
        color: var(--tx);
        font-family: inherit;
        font-size: .82rem;
        text-align: center;
        transition: border-color .2s;
    }
    .page-jump:focus { outline: none; border-color: var(--primary); }

    .zoom-label {
        font-size: .78rem;
        color: var(--tx2);
        min-width: 46px;
        text-align: center;
        font-weight: 500;
    }

    /* Back link */
    .reader-back {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: .8rem; font-weight: 500; color: var(--tx3);
        padding: 6px 12px; border-radius: 8px;
        border: 1.5px solid var(--border);
        background: var(--surface);
        text-decoration: none;
        transition: color .15s, border-color .15s;
        white-space: nowrap;
    }
    .reader-back:hover { color: var(--primary); border-color: var(--primary); }

    /* ── Canvas ── */
    .reader-stage {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        padding: 28px 20px;
        background: rgba(0,0,0,0.04);
        overflow-y: auto;
    }
    [data-theme="dark"] .reader-stage { background: rgba(0,0,0,0.2); }

    #pdf-canvas {
        max-width: 100%;
        height: auto;
        box-shadow: 0 8px 40px rgba(0,0,0,0.18);
        border-radius: 5px;
        display: block;
    }

    /* ── Loading / Error states ── */
    .reader-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 14px;
        padding: 60px 20px;
        color: var(--tx3);
        font-size: .9rem;
        width: 100%;
    }
    .reader-state-icon { font-size: 2.5rem; opacity: .5; }
    .reader-state-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.1rem; color: var(--tx2); font-weight: 700;
    }
    .reader-spinner {
        width: 40px; height: 40px;
        border: 3px solid var(--border);
        border-top-color: var(--primary);
        border-radius: 50%;
        animation: spin .8s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
</style>
@endpush

@section('content')
<div class="reader-wrap" id="readerApp">

    {{-- ── Toolbar ── --}}
    <div class="reader-toolbar">
        {{-- Back + info --}}
        <div style="display:flex;align-items:center;gap:14px;min-width:0">
            <a href="{{ route('books.show', $book->slug) }}" class="reader-back">
                <i class="bi bi-arrow-left"></i>
                <span class="d-none d-sm-inline">Kembali</span>
            </a>
            <div class="reader-book-info">
                <h3>{{ $book->title }}</h3>
                <span>{{ $book->author }}</span>
            </div>
        </div>

        {{-- Controls --}}
        <div class="reader-controls">
            {{-- Zoom --}}
            <button class="ctrl-btn" id="btnZoomOut" title="Zoom Out">
                <i class="bi bi-zoom-out"></i>
            </button>
            <span class="zoom-label" id="zoomLabel">120%</span>
            <button class="ctrl-btn" id="btnZoomIn" title="Zoom In">
                <i class="bi bi-zoom-in"></i>
            </button>

            <div class="ctrl-sep"></div>

            {{-- Navigation --}}
            <button class="ctrl-btn" id="btnPrev" disabled title="Halaman Sebelumnya">
                <i class="bi bi-chevron-left"></i>
            </button>
            <div class="page-info">
                <input type="number" id="pageInput" class="page-jump" value="1" min="1">
                <span>/ <span id="totalPages">—</span></span>
            </div>
            <button class="ctrl-btn" id="btnNext" disabled title="Halaman Berikutnya">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>
    </div>

    {{-- ── Stage ── --}}
    <div class="reader-stage" id="readerStage">
        {{-- Loading state (shown initially) --}}
        <div class="reader-state" id="stateLoading">
            <div class="reader-spinner"></div>
            <div class="reader-state-title">Memuat PDF…</div>
            <div>Mohon tunggu sebentar</div>
        </div>

        {{-- Error state (hidden) --}}
        <div class="reader-state" id="stateError" style="display:none">
            <div class="reader-state-icon">📄</div>
            <div class="reader-state-title">Gagal memuat PDF</div>
            <div id="stateErrorMsg">Terjadi kesalahan saat memuat file.</div>
            <a href="{{ route('books.show', $book->slug) }}"
               style="margin-top:8px;padding:9px 20px;border-radius:100px;background:var(--primary);color:#fff;font-size:.85rem;font-weight:600;text-decoration:none">
                Kembali ke Detail Buku
            </a>
        </div>

        {{-- Canvas (hidden until loaded) --}}
        <canvas id="pdf-canvas" style="display:none"></canvas>
    </div>
</div>
@endsection

@push('scripts')
{{--
    ✅ FIX: Muat PDF.js HANYA dari CDN.
    Jika pdfjs-dist juga ada di package.json / app.js, ini akan konflik.
    Solusi: hapus pdfjs-dist dari npm, atau pastikan app.js tidak mengimpornya.
    Gunakan versi yang sama persis antara pdf.min.js dan pdf.worker.min.js.
--}}
<script>
// ✅ FIX UTAMA: Blok import PDF.js dari Vite/app.js jika ada,
// supaya hanya CDN yang dipakai. Jalankan SEBELUM memuat pdf.min.js.
if (typeof window.pdfjsLib !== 'undefined') {
    // Sudah dimuat (kemungkinan dari app.js), hapus supaya kita ganti dengan CDN
    delete window.pdfjsLib;
}
</script>

{{-- Muat PDF.js dari CDN — versi harus sama persis antara kedua file --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>

<script>
(function () {
    'use strict';

    // ── Konfigurasi ──────────────────────────────────────────
    const PDF_URL    = @json(route('books.file', $book));
    const WORKER_URL = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js';

    // ✅ Set worker SEBELUM getDocument dipanggil
    pdfjsLib.GlobalWorkerOptions.workerSrc = WORKER_URL;

    // ── State ────────────────────────────────────────────────
    let pdfDoc      = null;
    let currentPage = 1;
    let totalPages  = 0;
    let scale       = 1.2;
    let rendering   = false;

    // ── DOM refs ─────────────────────────────────────────────
    const canvas       = document.getElementById('pdf-canvas');
    const ctx          = canvas.getContext('2d');
    const btnPrev      = document.getElementById('btnPrev');
    const btnNext      = document.getElementById('btnNext');
    const btnZoomIn    = document.getElementById('btnZoomIn');
    const btnZoomOut   = document.getElementById('btnZoomOut');
    const pageInput    = document.getElementById('pageInput');
    const totalPagesEl = document.getElementById('totalPages');
    const zoomLabel    = document.getElementById('zoomLabel');
    const stateLoading = document.getElementById('stateLoading');
    const stateError   = document.getElementById('stateError');
    const stateErrorMsg= document.getElementById('stateErrorMsg');

    // ── Helpers ──────────────────────────────────────────────
    function showCanvas() {
        stateLoading.style.display = 'none';
        stateError.style.display   = 'none';
        canvas.style.display       = 'block';
    }

    function showError(msg) {
        stateLoading.style.display  = 'none';
        stateError.style.display    = 'flex';
        canvas.style.display        = 'none';
        stateErrorMsg.textContent   = msg;
    }

    function updateControls() {
        btnPrev.disabled  = currentPage <= 1;
        btnNext.disabled  = currentPage >= totalPages;
        pageInput.value   = currentPage;
        pageInput.max     = totalPages;
        zoomLabel.textContent = Math.round(scale * 100) + '%';
        totalPagesEl.textContent = totalPages;
    }

    // ── Render ───────────────────────────────────────────────
    async function renderPage(pageNum) {
        if (rendering || !pdfDoc) return;
        rendering = true;

        try {
            const page     = await pdfDoc.getPage(pageNum);
            const viewport = page.getViewport({ scale });

            canvas.width  = viewport.width;
            canvas.height = viewport.height;

            await page.render({ canvasContext: ctx, viewport }).promise;
            showCanvas();
        } catch (err) {
            console.error('Render error:', err);
            showError('Gagal merender halaman ' + pageNum + '. ' + err.message);
        } finally {
            rendering = false;
        }
    }

    // ── Init ─────────────────────────────────────────────────
    async function init() {
        try {
            const loadingTask = pdfjsLib.getDocument({
                url: PDF_URL,
                // ✅ Cegah PDF.js memuat worker dari sumber lain
                disableAutoFetch: false,
                disableStream:    false,
            });

            pdfDoc     = await loadingTask.promise;
            totalPages = pdfDoc.numPages;

            updateControls();
            await renderPage(currentPage);
        } catch (err) {
            console.error('PDF load error:', err);
            let msg = 'Gagal memuat file PDF.';
            if (err.name === 'PasswordException') {
                msg = 'File PDF ini dilindungi kata sandi.';
            } else if (err.message?.includes('Missing PDF')) {
                msg = 'File PDF tidak ditemukan.';
            } else if (err.status === 401 || err.status === 403) {
                msg = 'Akses ditolak. Silakan login ulang.';
            }
            showError(msg);
        }
    }

    // ── Event listeners ──────────────────────────────────────
    btnPrev.addEventListener('click', async () => {
        if (currentPage > 1) {
            currentPage--;
            updateControls();
            await renderPage(currentPage);
        }
    });

    btnNext.addEventListener('click', async () => {
        if (currentPage < totalPages) {
            currentPage++;
            updateControls();
            await renderPage(currentPage);
        }
    });

    btnZoomIn.addEventListener('click', async () => {
        scale = Math.min(scale + 0.25, 3.0);
        updateControls();
        await renderPage(currentPage);
    });

    btnZoomOut.addEventListener('click', async () => {
        scale = Math.max(scale - 0.25, 0.5);
        updateControls();
        await renderPage(currentPage);
    });

    pageInput.addEventListener('keyup', async (e) => {
        if (e.key !== 'Enter') return;
        const p = parseInt(pageInput.value);
        if (p >= 1 && p <= totalPages) {
            currentPage = p;
            updateControls();
            await renderPage(currentPage);
        } else {
            pageInput.value = currentPage;
        }
    });

    pageInput.addEventListener('blur', async () => {
        const p = parseInt(pageInput.value);
        if (p >= 1 && p <= totalPages && p !== currentPage) {
            currentPage = p;
            updateControls();
            await renderPage(currentPage);
        } else {
            pageInput.value = currentPage;
        }
    });

    // ── Keyboard shortcuts ───────────────────────────────────
    document.addEventListener('keydown', async (e) => {
        if (['INPUT', 'TEXTAREA'].includes(document.activeElement?.tagName)) return;
        if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
            if (currentPage < totalPages) { currentPage++; updateControls(); await renderPage(currentPage); }
        } else if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
            if (currentPage > 1) { currentPage--; updateControls(); await renderPage(currentPage); }
        } else if (e.key === '+' || e.key === '=') {
            scale = Math.min(scale + 0.25, 3.0); updateControls(); await renderPage(currentPage);
        } else if (e.key === '-') {
            scale = Math.max(scale - 0.25, 0.5); updateControls(); await renderPage(currentPage);
        }
    });

    // ── Start ────────────────────────────────────────────────
    init();
})();
</script>
@endpush