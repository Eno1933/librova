<!DOCTYPE html>
<html lang="id"
      x-data="{
          dark: localStorage.getItem('librova-theme') === 'dark',
          nightMode: false,
          toggleTheme() {
              this.dark = !this.dark;
              const t = this.dark ? 'dark' : 'light';
              localStorage.setItem('librova-theme', t);
              document.documentElement.setAttribute('data-theme', t);
          }
      }"
      :data-theme="dark ? 'dark' : 'light'">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Baca: {{ $book->title }} — Librova</title>

    <script>
        (function(){
            const s = localStorage.getItem('librova-theme');
            const p = window.matchMedia('(prefers-color-scheme: dark)').matches;
            document.documentElement.setAttribute('data-theme', s ?? (p ? 'dark' : 'light'));
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root { --nav-h: 0px; }
        body {
            margin: 0; font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg); color: var(--tx);
            -webkit-font-smoothing: antialiased; overflow-x: hidden;
            transition: background .3s, color .3s;
        }
        .reader-wrap { 
            display: flex; flex-direction: column; height: 100vh; background: var(--bg); 
            overflow: hidden; 
        }

        /* ── Toolbar (shared) ── */
        .reader-toolbar {
            display: flex; align-items: center; justify-content: space-between;
            padding: 12px 24px; min-height: 64px;
            background: var(--surface);
            flex-shrink: 0; flex-wrap: wrap; gap: 12px;
            position: relative; z-index: 10;
        }
        .toolbar-top {
            box-shadow: 0 4px 24px -6px rgba(0,0,0,0.06);
            border-bottom: 1px solid var(--border);
        }
        .toolbar-bottom {
            box-shadow: 0 -4px 24px -6px rgba(0,0,0,0.06);
            border-top: 1px solid var(--border);
        }
        [data-theme="dark"] .reader-toolbar { box-shadow: 0 4px 30px rgba(0,0,0,0.3); }

        /* Toolbar Sections */
        .toolbar-left { display: flex; align-items: center; gap: 16px; min-width: 0; flex: 1; }
        .toolbar-center { display: flex; align-items: center; justify-content: center; flex: 1; }
        .toolbar-right { display: flex; align-items: center; justify-content: flex-end; gap: 8px; flex-wrap: wrap; }

        .reader-book-info { display: flex; flex-direction: column; min-width: 0; }
        .reader-book-info h3 {
            font-family: 'Playfair Display', serif; font-size: 1.05rem; font-weight: 700;
            color: var(--tx); margin: 0; white-space: nowrap;
            overflow: hidden; text-overflow: ellipsis; max-width: 100%;
        }
        .reader-book-info span { font-size: .8rem; color: var(--tx3); line-height: 1.3; font-weight: 500; }

        /* Controls */
        .reader-controls { display: flex; align-items: center; gap: 8px; }
        .ctrl-btn {
            width: 38px; height: 38px; border-radius: 10px; /* Lebih membulat */
            border: 1.5px solid var(--border); background: var(--surface);
            color: var(--tx2); display: flex; align-items: center; justify-content: center;
            cursor: pointer; font-size: 1.05rem;
            transition: all .2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .ctrl-btn:hover:not(:disabled) {
            background: var(--surface2); border-color: var(--primary); color: var(--primary);
            transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .ctrl-btn:active:not(:disabled) { transform: translateY(0); }
        .ctrl-btn:disabled { opacity: .4; cursor: not-allowed; }
        .ctrl-sep { width: 1.5px; height: 22px; background: var(--border); margin: 0 6px; border-radius: 2px; }

        .page-info { font-size: .85rem; color: var(--tx2); display: flex; align-items: center; gap: 8px; white-space: nowrap; font-weight: 500; }
        .page-jump {
            width: 56px; padding: 6px 8px; border-radius: 8px;
            border: 1.5px solid var(--border); background: var(--bg);
            color: var(--tx); font-family: inherit; font-size: .85rem; text-align: center; font-weight: 600;
            transition: all .2s;
        }
        .page-jump:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(var(--primary-rgb, 0,0,0), 0.1); }
        .page-jump::-webkit-outer-spin-button, .page-jump::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
        .page-jump[type=number] { -moz-appearance: textfield; }

        .zoom-label { font-size: .8rem; color: var(--tx2); min-width: 48px; text-align: center; font-weight: 700; }

        /* Back link */
        .reader-back {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            font-size: .85rem; font-weight: 600; color: var(--tx2);
            padding: 8px 16px; border-radius: 10px;
            border: 1.5px solid var(--border); background: var(--surface);
            text-decoration: none; transition: all .2s; white-space: nowrap; cursor: pointer;
        }
        .reader-back:hover { 
            color: var(--primary); border-color: var(--primary); 
            background: var(--surface2); transform: translateX(-2px);
        }

        /* ── Stage ── */
        .reader-stage {
            flex: 1; display: flex; justify-content: center; align-items: flex-start;
            padding: 32px 20px; background: rgba(0,0,0,0.02); overflow: auto; transition: background .3s;
            position: relative; z-index: 1;
        }
        [data-theme="dark"] .reader-stage { background: rgba(0,0,0,0.25); }
        .reader-stage.night-mode { background: #F5E6C8; }
        [data-theme="dark"] .reader-stage.night-mode { background: #3D3522; }

        /* Custom Scrollbar */
        .reader-stage::-webkit-scrollbar { width: 12px; height: 12px; }
        .reader-stage::-webkit-scrollbar-track { background: transparent; }
        .reader-stage::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.15); border-radius: 12px; border: 3px solid transparent; background-clip: padding-box; }
        .reader-stage::-webkit-scrollbar-thumb:hover { background-color: rgba(0,0,0,0.3); }
        [data-theme="dark"] .reader-stage::-webkit-scrollbar-thumb { background-color: rgba(255,255,255,0.15); }
        [data-theme="dark"] .reader-stage::-webkit-scrollbar-thumb:hover { background-color: rgba(255,255,255,0.3); }

        #pdf-canvas {
            max-width: 100%; height: auto; 
            box-shadow: 0 20px 40px -8px rgba(0,0,0,0.15), 0 8px 24px -4px rgba(0,0,0,0.08);
            border-radius: 4px; display: block; transition: filter .3s, opacity .2s;
            margin: auto;
        }
        [data-theme="dark"] #pdf-canvas { box-shadow: 0 20px 40px -8px rgba(0,0,0,0.5); }
        .reader-stage.night-mode #pdf-canvas { filter: sepia(0.35) brightness(0.9) contrast(0.95); }

        /* ── Loading / Error ── */
        .reader-state {
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            gap: 16px; padding: 60px 20px; color: var(--tx3); font-size: .95rem; width: 100%; height: 100%;
        }
        .reader-state-icon { font-size: 3.5rem; opacity: .4; }
        .reader-state-title { font-family: 'Playfair Display', serif; font-size: 1.3rem; color: var(--tx); font-weight: 700; }
        .reader-spinner {
            width: 48px; height: 48px; border: 3.5px solid var(--border);
            border-top-color: var(--primary); border-radius: 50%; animation: spin .8s cubic-bezier(0.4, 0, 0.2, 1) infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Mobile Adjustments */
        @media (max-width: 640px) {
            .reader-toolbar { padding: 12px 16px; }
            .toolbar-left, .toolbar-right { flex: initial; width: 100%; justify-content: space-between; }
            .reader-book-info h3 { max-width: 180px; }
            .ctrl-sep { display: none; }
        }
    </style>
</head>
<body>

<div class="reader-wrap" id="readerApp">

    {{-- ═══════ TOOLBAR ATAS (Info + Aksi) ═══════ --}}
    <div class="reader-toolbar toolbar-top">
        <div class="toolbar-left">
            <a onclick="goBack()" class="reader-back" title="Kembali ke halaman sebelumnya">
                <i class="bi bi-arrow-left"></i>
                <span class="d-none d-sm-inline">Kembali</span>
            </a>
            <div class="reader-book-info">
                <h3>{{ $book->title }}</h3>
                <span>{{ $book->author }}</span>
            </div>
        </div>

        <div class="toolbar-right">
            <div class="reader-controls">
                {{-- Theme toggle --}}
                <button class="ctrl-btn" @click="toggleTheme" :title="dark ? 'Mode Terang' : 'Mode Gelap'">
                    <i class="bi" :class="dark ? 'bi-sun' : 'bi-moon-stars'"></i>
                </button>

                {{-- Mode baca malam --}}
                <button class="ctrl-btn" id="btnNightMode" title="Mode Baca Malam">
                    <i class="bi bi-moon-fill"></i>
                </button>

                <div class="ctrl-sep d-none d-sm-block"></div>

                {{-- Zoom --}}
                <button class="ctrl-btn" id="btnZoomOut" title="Perkecil">
                    <i class="bi bi-zoom-out"></i>
                </button>
                <span class="zoom-label" id="zoomLabel">120%</span>
                <button class="ctrl-btn" id="btnZoomIn" title="Perbesar">
                    <i class="bi bi-zoom-in"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- ═══════ STAGE ═══════ --}}
    <div class="reader-stage" id="readerStage">
        <div class="reader-state" id="stateLoading">
            <div class="reader-spinner"></div>
            <div class="reader-state-title">Memuat Dokumen…</div>
            <div>Mohon tunggu sebentar</div>
        </div>
        <div class="reader-state" id="stateError" style="display:none">
            <div class="reader-state-icon">📄</div>
            <div class="reader-state-title">Gagal memuat PDF</div>
            <div id="stateErrorMsg">Terjadi kesalahan saat memuat file.</div>
            <a onclick="goBack()" 
               style="margin-top:12px;padding:10px 24px;border-radius:100px;background:var(--primary);color:#fff;font-size:.9rem;font-weight:600;text-decoration:none;transition:opacity .2s;cursor:pointer;"
               onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
                Kembali
            </a>
        </div>
        <canvas id="pdf-canvas" style="display:none; opacity: 0;"></canvas>
    </div>

    {{-- ═══════ TOOLBAR BAWAH (Navigasi Halaman) ═══════ --}}
    <div class="reader-toolbar toolbar-bottom">
        <div class="toolbar-center">
            <div class="reader-controls">
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
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
<script>
    // Fungsi pintar untuk navigasi kembali
    function goBack() {
        // Cek jika ada riwayat halaman sebelumnya yang berasal dari web yang sama
        if (window.history.length > 1 && document.referrer.includes(window.location.host)) {
            window.history.back();
        } else {
            // Fallback: Jika dibuka di tab baru, kembali ke detail buku
            window.location.href = "{{ route('books.show', $book->slug) }}";
        }
    }

(function () {
    'use strict';

    const PDF_URL    = @json(route('books.file', $book));
    const WORKER_URL = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js';
    const BOOK_ID    = {{ $book->id }};

    pdfjsLib.GlobalWorkerOptions.workerSrc = WORKER_URL;

    let pdfDoc = null, currentPage = 1, totalPages = 0, scale = 1.2, rendering = false, nightMode = false;
    let renderQueue = null;

    const canvas = document.getElementById('pdf-canvas'), ctx = canvas.getContext('2d');
    const btnPrev = document.getElementById('btnPrev'), btnNext = document.getElementById('btnNext');
    const btnZoomIn = document.getElementById('btnZoomIn'), btnZoomOut = document.getElementById('btnZoomOut');
    const btnNightMode = document.getElementById('btnNightMode');
    const pageInput = document.getElementById('pageInput'), totalPagesEl = document.getElementById('totalPages');
    const zoomLabel = document.getElementById('zoomLabel');
    const stateLoading = document.getElementById('stateLoading'), stateError = document.getElementById('stateError');
    const stateErrorMsg = document.getElementById('stateErrorMsg'), readerStage = document.getElementById('readerStage');

    function showCanvas() { 
        stateLoading.style.display = 'none'; 
        stateError.style.display = 'none'; 
        canvas.style.display = 'block'; 
        // Efek fade-in saat render selesai
        setTimeout(() => canvas.style.opacity = '1', 50); 
    }
    
    function showError(msg) { 
        stateLoading.style.display = 'none'; 
        stateError.style.display = 'flex'; 
        canvas.style.display = 'none'; 
        stateErrorMsg.textContent = msg; 
    }

    function updateControls() {
        btnPrev.disabled = currentPage <= 1;
        btnNext.disabled = currentPage >= totalPages;
        pageInput.value = currentPage;
        pageInput.max = totalPages;
        zoomLabel.textContent = Math.round(scale * 100) + '%';
        totalPagesEl.textContent = totalPages;
    }

    function saveProgress() { localStorage.setItem('librova_last_page_' + BOOK_ID, currentPage); }
    function applyNightMode() { readerStage.classList.toggle('night-mode', nightMode); }

    async function renderPage(pageNum) {
        if (rendering) {
            renderQueue = pageNum;
            return;
        }
        rendering = true;
        canvas.style.opacity = '0.5'; // Sedikit transparan saat sedang memuat halaman baru
        
        try {
            const page = await pdfDoc.getPage(pageNum);
            const viewport = page.getViewport({ scale });
            canvas.width = viewport.width;
            canvas.height = viewport.height;
            await page.render({ canvasContext: ctx, viewport }).promise;
            showCanvas();
            saveProgress();
        } catch (err) {
            console.error('Render error:', err);
            showError('Gagal merender halaman ' + pageNum + '.');
        } finally { 
            rendering = false; 
            if (renderQueue !== null) {
                const nextRender = renderQueue;
                renderQueue = null;
                renderPage(nextRender);
            }
        }
    }

    async function init() {
        try {
            const loadingTask = pdfjsLib.getDocument({ url: PDF_URL });
            pdfDoc = await loadingTask.promise;
            totalPages = pdfDoc.numPages;
            const lastPage = localStorage.getItem('librova_last_page_' + BOOK_ID);
            if (lastPage) { const p = parseInt(lastPage); if (p >= 1 && p <= totalPages) currentPage = p; }
            updateControls();
            await renderPage(currentPage);
        } catch (err) {
            console.error('PDF load error:', err);
            let msg = 'Gagal memuat file PDF.';
            if (err.name === 'PasswordException') msg = 'File PDF ini dilindungi kata sandi.';
            else if (err.message?.includes('Missing PDF')) msg = 'File PDF tidak ditemukan.';
            else if (err.status === 401 || err.status === 403) msg = 'Akses ditolak. Silakan login ulang.';
            showError(msg);
        }
    }

    btnPrev.addEventListener('click', async () => { if (currentPage > 1) { currentPage--; updateControls(); await renderPage(currentPage); } });
    btnNext.addEventListener('click', async () => { if (currentPage < totalPages) { currentPage++; updateControls(); await renderPage(currentPage); } });
    btnZoomIn.addEventListener('click', async () => { scale = Math.min(scale + 0.25, 3.0); updateControls(); await renderPage(currentPage); });
    btnZoomOut.addEventListener('click', async () => { scale = Math.max(scale - 0.25, 0.5); updateControls(); await renderPage(currentPage); });
    btnNightMode.addEventListener('click', () => { nightMode = !nightMode; applyNightMode(); });
    
    pageInput.addEventListener('keyup', async (e) => {
        if (e.key !== 'Enter') return;
        const p = parseInt(pageInput.value);
        if (p >= 1 && p <= totalPages) { currentPage = p; updateControls(); await renderPage(currentPage); } else { pageInput.value = currentPage; }
    });
    
    pageInput.addEventListener('blur', async () => {
        const p = parseInt(pageInput.value);
        if (p >= 1 && p <= totalPages && p !== currentPage) { currentPage = p; updateControls(); await renderPage(currentPage); } else { pageInput.value = currentPage; }
    });
    
    document.addEventListener('keydown', async (e) => {
        if (['INPUT','TEXTAREA'].includes(document.activeElement?.tagName)) return;
        if (e.key === 'ArrowRight' || e.key === 'ArrowDown') { if (currentPage < totalPages) { currentPage++; updateControls(); await renderPage(currentPage); } }
        else if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') { if (currentPage > 1) { currentPage--; updateControls(); await renderPage(currentPage); } }
        else if (e.key === '+' || e.key === '=') { scale = Math.min(scale + 0.25, 3.0); updateControls(); await renderPage(currentPage); }
        else if (e.key === '-') { scale = Math.max(scale - 0.25, 0.5); updateControls(); await renderPage(currentPage); }
    });

    init();
})();
</script>
</body>
</html>