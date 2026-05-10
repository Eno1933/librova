@props(['active' => 'home'])

{{-- ▸ Bottom Navbar Styles (scoped) --}}
<style>
.bnav {
    position: fixed;
    bottom: 0; left: 0; right: 0;
    z-index: 50;
    padding-bottom: env(safe-area-inset-bottom);
    border-top: 1px solid var(--border);
    backdrop-filter: blur(20px) saturate(180%);
    -webkit-backdrop-filter: blur(20px) saturate(180%);
    box-shadow: 0 -4px 24px rgba(0,0,0,0.07);
    transition: background 0.3s;
}
.bnav-inner {
    display: flex;
    justify-content: space-around;
    align-items: center;
    height: 62px;
    padding: 0 4px;
    width: 100%;
    max-width: 480px;
    margin: 0 auto;
}

/* Each tab item */
.bnav-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    flex: 1;
    height: 100%;
    text-decoration: none;
    position: relative;
    color: var(--tx3);
    transition: color 0.2s;
    -webkit-tap-highlight-color: transparent;
    gap: 0;
    cursor: pointer;
    border: none;
    background: none;
    font-family: inherit;
    padding: 0;
}
.bnav-item:active .bnav-pill {
    transform: scale(0.92);
}

/* Pill bubble behind icon + label */
.bnav-pill {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 3px;
    padding: 6px 14px 5px;
    border-radius: 100px;
    transition:
        background 0.25s cubic-bezier(.34,1.56,.64,1),
        transform 0.2s cubic-bezier(.34,1.56,.64,1),
        padding 0.25s;
    will-change: transform;
}
.bnav-item.is-active .bnav-pill {
    background: rgba(44, 95, 46, 0.10);
    padding: 6px 16px 5px;
    transform: translateY(-1px);
}
[data-theme="dark"] .bnav-item.is-active .bnav-pill {
    background: rgba(74, 222, 128, 0.10);
}

/* Icon wrapper */
.bnav-icon {
    width: 22px;
    height: 22px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.25s cubic-bezier(.34,1.56,.64,1);
    flex-shrink: 0;
}
.bnav-item.is-active .bnav-icon {
    transform: scale(1.08);
}

/* Label */
.bnav-label {
    font-size: 0.6rem;
    font-weight: 500;
    line-height: 1;
    white-space: nowrap;
    letter-spacing: 0.01em;
    transition: font-weight 0.15s, opacity 0.2s;
    opacity: 0.7;
}
.bnav-item.is-active .bnav-label {
    font-weight: 600;
    opacity: 1;
}

/* Active dot indicator at top of navbar */
.bnav-dot {
    position: absolute;
    top: -1px;
    left: 50%;
    transform: translateX(-50%) scaleX(0);
    width: 20px;
    height: 2.5px;
    border-radius: 0 0 3px 3px;
    background: var(--primary);
    transition: transform 0.3s cubic-bezier(.34,1.56,.64,1), opacity 0.2s;
    opacity: 0;
}
.bnav-item.is-active .bnav-dot {
    transform: translateX(-50%) scaleX(1);
    opacity: 1;
}

/* Color states */
.bnav-item { color: var(--tx3); }
.bnav-item.is-active { color: var(--primary); }

/* Search modal backdrop */
.bnav-modal-backdrop {
    position: fixed; inset: 0;
    background: rgba(0,0,0,0.45);
    z-index: 60;
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
}
.bnav-modal-card {
    position: absolute;
    top: 5rem; left: 1rem; right: 1rem;
    background: var(--surface);
    border-radius: 16px;
    box-shadow: 0 20px 48px rgba(0,0,0,0.18);
    padding: 1.25rem;
    border: 1px solid var(--border);
}
.bnav-modal-form {
    display: flex;
    gap: 10px;
}
.bnav-modal-input-wrap {
    flex: 1;
    position: relative;
}
.bnav-modal-input-wrap svg {
    position: absolute;
    left: 12px; top: 50%;
    transform: translateY(-50%);
    width: 18px; height: 18px;
    color: var(--tx3);
    pointer-events: none;
}
.bnav-modal-input {
    width: 100%;
    padding: 11px 14px 11px 38px;
    border-radius: 10px;
    border: 1.5px solid var(--border);
    background: var(--bg);
    color: var(--tx);
    font-family: inherit;
    font-size: 0.9rem;
    outline: none;
    transition: border-color .2s, box-shadow .2s;
}
.bnav-modal-input:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(44,95,46,0.1);
}
[data-theme="dark"] .bnav-modal-input:focus {
    box-shadow: 0 0 0 3px rgba(74,222,128,0.1);
}
.bnav-modal-submit {
    background: var(--primary);
    color: white;
    border: none;
    padding: 11px 16px;
    border-radius: 10px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background .2s, transform .15s;
    flex-shrink: 0;
}
[data-theme="dark"] .bnav-modal-submit { color: var(--bg); }
.bnav-modal-submit:hover { background: var(--primary-h); }
.bnav-modal-submit:active { transform: scale(0.95); }
.bnav-modal-cancel {
    margin-top: 10px;
    width: 100%;
    padding: 8px;
    text-align: center;
    font-size: 0.82rem;
    color: var(--tx3);
    background: none;
    border: none;
    cursor: pointer;
    font-family: inherit;
    transition: color .15s;
}
.bnav-modal-cancel:hover { color: var(--tx); }

/* Mobile spacer */
.bnav-spacer { display: block; height: 80px; }
@media(min-width: 640px) { .bnav, .bnav-spacer { display: none; } }

/* Alpine cloak */
[x-cloak] { display: none !important; }
</style>

<nav class="bnav"
     x-data="{
         darkMode: document.documentElement.getAttribute('data-theme') === 'dark',
         activeTab: '{{ $active }}',
         searchOpen: false,
         init() {
             const observer = new MutationObserver(() => {
                 this.darkMode = document.documentElement.getAttribute('data-theme') === 'dark';
             });
             observer.observe(document.documentElement, {
                 attributes: true,
                 attributeFilter: ['data-theme']
             });
         }
     }"
     :style="{ background: darkMode ? 'rgba(30,30,25,0.94)' : 'rgba(250,247,242,0.92)' }">

    <div class="bnav-inner">

        {{-- Home --}}
        <a href="{{ route('dashboard') }}"
           class="bnav-item"
           :class="{ 'is-active': activeTab === 'home' }"
           @click="activeTab = 'home'">
            <div class="bnav-dot"></div>
            <div class="bnav-pill">
                <div class="bnav-icon">
                    <svg width="22" height="22" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                    </svg>
                </div>
                <span class="bnav-label">Home</span>
            </div>
        </a>

        {{-- Buku --}}
        <a href="{{ route('books.index') }}"
           class="bnav-item"
           :class="{ 'is-active': activeTab === 'books' }"
           @click="activeTab = 'books'">
            <div class="bnav-dot"></div>
            <div class="bnav-pill">
                <div class="bnav-icon">
                    <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <span class="bnav-label">Buku</span>
            </div>
        </a>

        {{-- Kategori --}}
        <a href="{{ route('categories.index') }}"
           class="bnav-item"
           :class="{ 'is-active': activeTab === 'categories' }"
           @click="activeTab = 'categories'">
            <div class="bnav-dot"></div>
            <div class="bnav-pill">
                <div class="bnav-icon">
                    <svg width="22" height="22" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                    </svg>
                </div>
                <span class="bnav-label">Kategori</span>
            </div>
        </a>

        {{-- Bookmark --}}
        <a href="{{ route('profile.bookmarks') }}"
           class="bnav-item"
           :class="{ 'is-active': activeTab === 'bookmarks' }"
           @click="activeTab = 'bookmarks'">
            <div class="bnav-dot"></div>
            <div class="bnav-pill">
                <div class="bnav-icon">
                    <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                    </svg>
                </div>
                <span class="bnav-label">Bookmark</span>
            </div>
        </a>

        {{-- Profil --}}
        <a href="{{ route('profile') }}"
           class="bnav-item"
           :class="{ 'is-active': activeTab === 'profile' }"
           @click="activeTab = 'profile'">
            <div class="bnav-dot"></div>
            <div class="bnav-pill">
                <div class="bnav-icon">
                    <svg width="22" height="22" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="bnav-label">Profil</span>
            </div>
        </a>

    </div>

    {{-- Search Modal (mobile) --}}
    <div x-show="searchOpen" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="bnav-modal-backdrop"
         @click.self="searchOpen = false">

        <div class="bnav-modal-card"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             @click.stop>

            <form action="{{ route('books.index') }}" method="GET" class="bnav-modal-form">
                <div class="bnav-modal-input-wrap">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="search"
                           placeholder="Cari judul atau penulis…"
                           class="bnav-modal-input"
                           autofocus>
                </div>
                <button type="submit" class="bnav-modal-submit">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>
            </form>

            <button @click="searchOpen = false" class="bnav-modal-cancel">
                Tutup
            </button>
        </div>
    </div>

</nav>

{{-- Spacer untuk mobile bottom nav --}}
<span class="bnav-spacer" aria-hidden="true"></span>