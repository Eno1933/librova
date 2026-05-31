<div x-data="{ loading: true }"
     x-init="
         window.addEventListener('load', () => {
             loading = false;
         });
         // Fallback: jika window.load terlalu lama, sembunyikan setelah 3 detik
         setTimeout(() => { loading = false; }, 3000);
     "
     x-show="loading"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-400"
     x-transition:leave-start="opacity-100 scale-100"
     x-transition:leave-end="opacity-0 scale-95"
     style="position: fixed; inset: 0; background: var(--bg); z-index: 9999; display: flex; align-items: center; justify-content: center;">
    
    <div style="text-align: center; display: flex; flex-direction: column; align-items: center; gap: 24px;">
        {{-- Animasi Spinner Modern & Simetris --}}
        <div class="loader-container">
            <div class="loader-ring outer"></div>
            <div class="loader-ring inner"></div>
            <div class="loader-dot"></div>
        </div>

        {{-- Teks --}}
        <div>
            <div style="font-family: 'Playfair Display', serif; font-size: 1.6rem; font-weight: 700; color: var(--tx); letter-spacing: 0.5px;">
                Librova
            </div>
            <div style="font-size: 0.75rem; font-weight: 600; color: var(--tx3); margin-top: 6px; text-transform: uppercase; letter-spacing: 2px; animation: pulse-text 2s infinite;">
                Memuat...
            </div>
        </div>
    </div>

    <style>
        .loader-container {
            position: relative;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .loader-ring {
            position: absolute;
            border-radius: 50%;
            border: 2px solid transparent;
        }
        
        /* Cincin Luar */
        .loader-ring.outer {
            width: 100%;
            height: 100%;
            border-top-color: var(--primary);
            border-bottom-color: var(--primary);
            opacity: 0.8;
            animation: spin-smooth 1.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) infinite;
        }
        
        /* Cincin Dalam */
        .loader-ring.inner {
            width: 65%;
            height: 65%;
            border-left-color: var(--primary);
            border-right-color: var(--primary);
            opacity: 0.5;
            animation: spin-reverse 1.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) infinite;
        }

        /* Titik Tengah */
        .loader-dot {
            width: 8px;
            height: 8px;
            background-color: var(--primary);
            border-radius: 50%;
            animation: pulse-dot 1.5s ease-in-out infinite;
        }

        /* Keyframes Animasi */
        @keyframes spin-smooth {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        @keyframes spin-reverse {
            0% { transform: rotate(360deg); }
            100% { transform: rotate(-360deg); }
        }

        @keyframes pulse-dot {
            0%, 100% { transform: scale(0.8); opacity: 0.5; }
            50% { transform: scale(1.3); opacity: 1; }
        }

        @keyframes pulse-text {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }
    </style>
</div>