@extends('layouts.app')

@section('title', 'Baca: ' . $book->title)

@section('content')
<div x-data="pdfReader('{{ Storage::url($book->file_path) }}')" class="min-h-screen bg-gray-900">
    <!-- Toolbar -->
    <div class="bg-gray-800 text-white p-4 flex items-center justify-between sticky top-0 z-50">
        <div>
            <h2 class="text-lg font-semibold">{{ $book->title }}</h2>
            <p class="text-sm text-gray-400">{{ $book->author }}</p>
        </div>
        <div class="flex items-center gap-4">
            <button @click="zoomOut" class="btn-icon" title="Zoom Out">
                <svg>...</svg>
            </button>
            <span x-text="Math.round(scale * 100) + '%'" class="text-sm w-16 text-center"></span>
            <button @click="zoomIn" class="btn-icon" title="Zoom In">
                <svg>...</svg>
            </button>
            <span x-text="'Halaman ' + currentPage + ' / ' + totalPages" class="text-sm ml-4"></span>
            <button @click="prevPage" :disabled="currentPage <= 1" class="btn-nav">←</button>
            <button @click="nextPage" :disabled="currentPage >= totalPages" class="btn-nav">→</button>
            <input type="number" x-model="pageInput" @keyup.enter="goToPage"
                   class="w-16 px-2 py-1 bg-gray-700 rounded text-center text-sm"
                   min="1" :max="totalPages">
        </div>
    </div>
    <!-- Canvas -->
    <div class="flex justify-center py-8" id="pdf-container">
        <canvas id="pdf-canvas" class="shadow-2xl rounded"></canvas>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

    function pdfReader(url) {
        return {
            pdfDoc: null,
            currentPage: 1,
            totalPages: 0,
            scale: 1.2,
            pageInput: 1,

            async init() {
                this.pdfDoc = await pdfjsLib.getDocument(url).promise;
                this.totalPages = this.pdfDoc.numPages;
                this.renderPage();
            },

            async renderPage() {
                const page = await this.pdfDoc.getPage(this.currentPage);
                const canvas = document.getElementById('pdf-canvas');
                const ctx = canvas.getContext('2d');
                const viewport = page.getViewport({ scale: this.scale });
                canvas.width = viewport.width;
                canvas.height = viewport.height;
                await page.render({ canvasContext: ctx, viewport }).promise;
            },

            async nextPage() {
                if (this.currentPage < this.totalPages) {
                    this.currentPage++;
                    this.pageInput = this.currentPage;
                    await this.renderPage();
                }
            },

            async prevPage() {
                if (this.currentPage > 1) {
                    this.currentPage--;
                    this.pageInput = this.currentPage;
                    await this.renderPage();
                }
            },

            async goToPage() {
                const page = parseInt(this.pageInput);
                if (page >= 1 && page <= this.totalPages) {
                    this.currentPage = page;
                    await this.renderPage();
                }
                this.pageInput = this.currentPage;
            },

            async zoomIn() {
                this.scale = Math.min(this.scale + 0.2, 3.0);
                await this.renderPage();
            },

            async zoomOut() {
                this.scale = Math.max(this.scale - 0.2, 0.5);
                await this.renderPage();
            },

            // Panggil init() saat komponen dimuat
            async __xinit() {
                await this.init();
            }
        };
    }
</script>
@endpush