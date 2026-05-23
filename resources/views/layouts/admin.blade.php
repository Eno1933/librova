<!DOCTYPE html>
<html lang="id" class="no-flash">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — Librova</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @stack('styles')

    {{-- Flash prevention --}}
    <script>
        (function(){
            const s = localStorage.getItem('librova-theme');
            const p = window.matchMedia('(prefers-color-scheme: dark)').matches;
            document.documentElement.setAttribute('data-theme', s ?? (p ? 'dark' : 'light'));
            document.documentElement.classList.remove('no-flash');
        })();
    </script>

    <style>
    /* ── Flash guard ── */
    html.no-flash body { visibility: hidden; }
    [x-cloak] { display: none !important; }

    /* ════════════════════════════════════════
       ADMIN LAYOUT
    ════════════════════════════════════════ */

    /* ── Sidebar widths ── */
    :root { --sb: 256px; --sb-collapsed: 68px; --hdr: 62px; }

    /* ── Layout shell ── */
    .adm-wrap {
        display: flex;
        min-height: 100vh;
        background: var(--bg);
    }

    /* ════════════════════════════════════════
       SIDEBAR
    ════════════════════════════════════════ */
    .adm-sidebar {
        width: var(--sb);
        background: var(--surface);
        border-right: 1px solid var(--border);
        display: flex;
        flex-direction: column;
        position: fixed;
        top: 0; left: 0; bottom: 0;
        z-index: 200;
        transition: transform .28s cubic-bezier(.4,0,.2,1), width .28s cubic-bezier(.4,0,.2,1);
        overflow: hidden;
    }

    /* Brand row */
    .adm-brand {
        height: var(--hdr);
        padding: 0 18px;
        display: flex;
        align-items: center;
        gap: 10px;
        border-bottom: 1px solid var(--border);
        flex-shrink: 0;
        text-decoration: none;
    }
    .adm-brand-icon {
        width: 34px; height: 34px;
        background: var(--primary);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 16px; color: #fff; flex-shrink: 0;
        transition: transform .2s cubic-bezier(.34,1.56,.64,1);
    }
    [data-theme="dark"] .adm-brand-icon { color: var(--bg); }
    .adm-brand:hover .adm-brand-icon { transform: rotate(-6deg) scale(1.08); }
    .adm-brand-text {
        font-family: 'Playfair Display', serif;
        font-size: 1.15rem; font-weight: 700;
        color: var(--primary); white-space: nowrap;
        overflow: hidden; transition: opacity .2s, width .2s;
    }
    .adm-brand-badge {
        margin-left: auto;
        font-size: .6rem; font-weight: 700;
        padding: 2px 7px; border-radius: 4px;
        background: rgba(44,95,46,.1); color: var(--primary);
        text-transform: uppercase; letter-spacing: .06em;
        flex-shrink: 0;
    }
    [data-theme="dark"] .adm-brand-badge { background: rgba(74,222,128,.1); }

    /* Nav */
    .adm-nav {
        flex: 1;
        padding: 14px 10px;
        display: flex;
        flex-direction: column;
        gap: 2px;
        overflow-y: auto;
        scrollbar-width: none;
    }
    .adm-nav::-webkit-scrollbar { display: none; }

    /* Section label – FIXED visibility */
    .adm-nav-label {
        font-size: .68rem; font-weight: 600;
        text-transform: uppercase; letter-spacing: .12em;
        color: var(--tx2);
        padding: 12px 12px 6px;
        opacity: 0.85;
    }

    /* Nav link */
    .adm-link {
        display: flex; align-items: center; gap: 10px;
        padding: 9px 12px;
        border-radius: 10px;
        font-size: .84rem; font-weight: 500;
        color: var(--tx2);
        text-decoration: none;
        position: relative;
        transition: background .15s, color .15s;
        white-space: nowrap;
    }
    .adm-link:hover {
        background: var(--surface2);
        color: var(--tx);
    }
    .adm-link.active {
        background: rgba(44,95,46,.09);
        color: var(--primary);
        font-weight: 600;
    }
    [data-theme="dark"] .adm-link.active { background: rgba(74,222,128,.1); }

    /* left accent bar on active */
    .adm-link.active::before {
        content: '';
        position: absolute; left: 0; top: 20%; bottom: 20%;
        width: 3px; border-radius: 0 3px 3px 0;
        background: var(--primary);
    }

    .adm-link i {
        font-size: 1.15rem;
        width: 22px; text-align: center; flex-shrink: 0;
    }
    .adm-link-text { overflow: hidden; transition: opacity .15s; }

    /* Badge on nav */
    .adm-link-badge {
        margin-left: auto;
        min-width: 20px; height: 20px;
        padding: 0 6px;
        border-radius: 10px;
        background: var(--primary); color: #fff;
        font-size: .62rem; font-weight: 700;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    [data-theme="dark"] .adm-link-badge { color: var(--bg); }

    /* Divider */
    .adm-nav-divider {
        height: 1px;
        background: var(--border);
        margin: 6px 2px;
    }

    /* Footer */
    .adm-sidebar-footer {
        padding: 12px 10px;
        border-top: 1px solid var(--border);
        flex-shrink: 0;
    }
    .adm-back-link {
        display: flex; align-items: center; gap: 10px;
        padding: 9px 12px;
        border-radius: 10px;
        font-size: .82rem; font-weight: 500;
        color: var(--tx3);
        text-decoration: none;
        transition: background .15s, color .15s;
        white-space: nowrap;
    }
    .adm-back-link:hover { background: var(--surface2); color: var(--tx); }
    .adm-back-link i { font-size: 1.1rem; width: 22px; text-align: center; flex-shrink: 0; }

    /* ════════════════════════════════════════
       HEADER
    ════════════════════════════════════════ */
    .adm-main {
        flex: 1;
        margin-left: var(--sb);
        display: flex;
        flex-direction: column;
        min-width: 0;
        transition: margin-left .28s cubic-bezier(.4,0,.2,1);
    }

    .adm-header {
        height: var(--hdr);
        background: rgba(250,247,242,0.92);
        backdrop-filter: blur(14px) saturate(180%);
        -webkit-backdrop-filter: blur(14px) saturate(180%);
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 24px;
        position: sticky; top: 0; z-index: 50;
        transition: background .3s;
    }
    [data-theme="dark"] .adm-header { background: rgba(20,20,16,0.92); }

    /* Breadcrumb / page title */
    .adm-header-left { display: flex; align-items: center; gap: 14px; }
    .adm-toggle {
        display: none;
        width: 36px; height: 36px;
        border-radius: 9px;
        background: none; border: 1.5px solid var(--border);
        cursor: pointer; font-size: 1.2rem; color: var(--tx2);
        align-items: center; justify-content: center;
        transition: background .15s, border-color .15s;
    }
    .adm-toggle:hover { background: var(--surface2); border-color: var(--border2); }

    /* Breadcrumb */
    .adm-breadcrumb {
        display: flex; align-items: center; gap: 6px;
        font-size: .8rem; color: var(--tx3);
    }
    .adm-breadcrumb a { color: var(--tx3); text-decoration: none; transition: color .15s; }
    .adm-breadcrumb a:hover { color: var(--primary); }
    .adm-breadcrumb-sep { font-size: 10px; opacity: .5; }
    .adm-page-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.1rem; font-weight: 700;
        color: var(--tx); white-space: nowrap;
    }

    /* Header right */
    .adm-header-right { display: flex; align-items: center; gap: 8px; }

    /* Search mini */
    .adm-search {
        display: flex; align-items: center; gap: 8px;
        padding: 7px 14px;
        border-radius: 100px;
        border: 1.5px solid var(--border);
        background: var(--surface2);
        font-size: .82rem; color: var(--tx3);
        cursor: pointer;
        transition: border-color .2s, background .2s;
        white-space: nowrap;
    }
    .adm-search:hover { border-color: var(--border2); background: var(--surface); color: var(--tx); }

    /* Icon button */
    .adm-icon-btn {
        width: 36px; height: 36px;
        border-radius: 10px;
        border: 1.5px solid var(--border);
        background: var(--surface2);
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; font-size: .95rem; color: var(--tx2);
        transition: background .15s, border-color .15s, color .15s;
        position: relative;
    }
    .adm-icon-btn:hover { background: var(--surface); border-color: var(--border2); color: var(--tx); }
    .adm-icon-btn .notif-dot {
        position: absolute; top: 6px; right: 6px;
        width: 7px; height: 7px; border-radius: 50%;
        background: #ef4444; border: 1.5px solid var(--surface2);
    }

    /* User chip */
    .adm-user-chip {
        display: flex; align-items: center; gap: 8px;
        padding: 5px 12px 5px 5px;
        border-radius: 100px;
        border: 1.5px solid var(--border);
        background: var(--surface);
        cursor: pointer;
        transition: border-color .2s, background .2s;
        text-decoration: none;
    }
    .adm-user-chip:hover { border-color: var(--border2); background: var(--surface2); }
    .adm-user-avatar {
        width: 28px; height: 28px; border-radius: 50%;
        background: rgba(44,95,46,.12);
        display: flex; align-items: center; justify-content: center;
        font-size: .75rem; font-weight: 700; color: var(--primary);
        flex-shrink: 0; overflow: hidden;
    }
    [data-theme="dark"] .adm-user-avatar { background: rgba(74,222,128,.12); }
    .adm-user-name { font-size: .82rem; font-weight: 600; color: var(--tx); white-space: nowrap; }

    /* ════════════════════════════════════════
       CONTENT
    ════════════════════════════════════════ */
    .adm-content {
        padding: 28px 24px;
        flex: 1;
    }

    /* ════════════════════════════════════════
       MOBILE OVERLAY
    ════════════════════════════════════════ */
    .adm-overlay {
        display: none;
        position: fixed; inset: 0;
        background: rgba(0,0,0,.45);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        z-index: 190;
    }
    .adm-overlay.open { display: block; }

    /* ════════════════════════════════════════
       RESPONSIVE
    ════════════════════════════════════════ */
    @media(max-width: 960px) {
        .adm-sidebar { transform: translateX(-100%); }
        .adm-sidebar.open { transform: translateX(0); }
        .adm-main { margin-left: 0; }
        .adm-toggle { display: flex; }
        .adm-search { display: none; }
    }
    @media(max-width: 480px) {
        .adm-content { padding: 20px 16px; }
        .adm-user-name { display: none; }
        .adm-header { padding: 0 16px; }
    }
    </style>
</head>
<body>
<div class="adm-wrap"
     x-data="{
         open: false,
         dark: document.documentElement.getAttribute('data-theme') === 'dark',
         toggleTheme() {
             this.dark = !this.dark;
             const t = this.dark ? 'dark' : 'light';
             localStorage.setItem('librova-theme', t);
             document.documentElement.setAttribute('data-theme', t);
         },
         init() {
             const obs = new MutationObserver(() => {
                 this.dark = document.documentElement.getAttribute('data-theme') === 'dark';
             });
             obs.observe(document.documentElement, { attributes: true, attributeFilter: ['data-theme'] });
         }
     }">

    {{-- Mobile overlay --}}
    <div class="adm-overlay" :class="{ open }" @click="open = false" x-cloak></div>

    {{-- ══ SIDEBAR ══ --}}
    <aside class="adm-sidebar" :class="{ open }">

        {{-- Brand --}}
        <a href="{{ route('admin.dashboard') }}" class="adm-brand">
            <span class="adm-brand-icon"><i class="bi bi-book"></i></span>
            <span class="adm-brand-text">Librova</span>
            <span class="adm-brand-badge">Admin</span>
        </a>

        {{-- Nav --}}
        <nav class="adm-nav">

            {{-- Overview --}}
            <div class="adm-nav-label">Overview</div>
            <a href="{{ route('admin.dashboard') }}"
               class="adm-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span class="adm-link-text">Dashboard</span>
            </a>
            <a href="{{ route('admin.reports') }}"
               class="adm-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-line"></i>
                <span class="adm-link-text">Laporan & Statistik</span>
            </a>

            <div class="adm-nav-divider"></div>

            {{-- Konten --}}
            <div class="adm-nav-label">Konten</div>
            <a href="{{ route('admin.books.index') }}"
               class="adm-link {{ request()->routeIs('admin.books.*') ? 'active' : '' }}">
                <i class="bi bi-journal-richtext"></i>
                <span class="adm-link-text">Manajemen Buku</span>
            </a>
            <a href="{{ route('admin.categories.index') }}"
               class="adm-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i>
                <span class="adm-link-text">Kategori</span>
            </a>

            <div class="adm-nav-divider"></div>

            {{-- Komunitas --}}
            <div class="adm-nav-label">Komunitas</div>
            <a href="{{ route('admin.users.index') }}"
               class="adm-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i>
                <span class="adm-link-text">Manajemen User</span>
            </a>
            <a href="{{ route('admin.reviews.index') }}"
               class="adm-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                <i class="bi bi-chat-left-text"></i>
                <span class="adm-link-text">Moderasi Review</span>
                @php $pendingReviews = \App\Models\Review::where('status','pending')->count(); @endphp
                @if($pendingReviews > 0)
                <span class="adm-link-badge">{{ $pendingReviews }}</span>
                @endif
            </a>
            <a href="{{ route('admin.feedbacks.index') }}"
               class="adm-link {{ request()->routeIs('admin.feedbacks.*') ? 'active' : '' }}">
                <i class="bi bi-envelope-paper"></i>
                <span class="adm-link-text">Feedback</span>
                @php $newFeedbacks = \App\Models\Feedback::where('status','new')->count(); @endphp
                @if($newFeedbacks > 0)
                <span class="adm-link-badge">{{ $newFeedbacks }}</span>
                @endif
            </a>

            <div class="adm-nav-divider"></div>

            {{-- Sistem --}}
            <div class="adm-nav-label">Sistem</div>
            <a href="{{ route('admin.settings') }}"
               class="adm-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                <i class="bi bi-sliders"></i>
                <span class="adm-link-text">Pengaturan</span>
            </a>
        </nav>

        {{-- Footer --}}
        <div class="adm-sidebar-footer">
            <a href="{{ route('dashboard') }}" class="adm-back-link">
                <i class="bi bi-arrow-left-circle"></i>
                <span class="adm-link-text">Kembali ke User Area</span>
            </a>
        </div>
    </aside>

    {{-- ══ MAIN ══ --}}
    <div class="adm-main">

        {{-- Header --}}
        <header class="adm-header">
            <div class="adm-header-left">
                {{-- Mobile menu toggle --}}
                <button class="adm-toggle" @click="open = !open" aria-label="Toggle menu">
                    <i class="bi" :class="open ? 'bi-x-lg' : 'bi-list'"></i>
                </button>

                <div>
                    {{-- Breadcrumb --}}
                    <div class="adm-breadcrumb">
                        <a href="{{ route('admin.dashboard') }}">Admin</a>
                        @hasSection('breadcrumb')
                        <span class="adm-breadcrumb-sep"><i class="bi bi-chevron-right"></i></span>
                        @yield('breadcrumb')
                        @endif
                    </div>
                    {{-- Page title --}}
                    <div class="adm-page-title">@yield('header-title', 'Dashboard')</div>
                </div>
            </div>

            <div class="adm-header-right">
                {{-- Quick search --}}
                <div class="adm-search">
                    <i class="bi bi-search" style="font-size:.85rem"></i>
                    Cari…
                    <span style="font-size:.72rem;padding:1px 6px;border-radius:4px;background:var(--surface2);border:1px solid var(--border);color:var(--tx3);margin-left:4px">⌘K</span>
                </div>

                {{-- Notification bell --}}
                <button class="adm-icon-btn" title="Notifikasi">
                    <i class="bi bi-bell"></i>
                    @if(isset($pendingReviews) && $pendingReviews > 0)
                    <span class="notif-dot"></span>
                    @endif
                </button>

                {{-- Theme toggle --}}
                <button class="adm-icon-btn" @click="toggleTheme" title="Toggle tema">
                    <i class="bi" :class="dark ? 'bi-sun' : 'bi-moon-stars'"></i>
                </button>

                {{-- User chip --}}
                <div class="adm-user-chip" x-data="{ menuOpen: false }" @click="menuOpen = !menuOpen" style="position:relative">
                    <div class="adm-user-avatar">
                        @if(auth()->user()->avatar)
                            <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="" style="width:100%;height:100%;object-fit:cover">
                        @else
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        @endif
                    </div>
                    <span class="adm-user-name">{{ explode(' ', auth()->user()->name)[0] }}</span>
                    <i class="bi bi-chevron-down" style="font-size:.7rem;color:var(--tx3);margin-left:2px"></i>

                    {{-- Dropdown --}}
                    <div x-show="menuOpen" x-cloak @click.away="menuOpen = false"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         style="position:absolute;top:calc(100% + 8px);right:0;min-width:180px;background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:6px;box-shadow:0 8px 30px var(--shadow);z-index:60">
                        <a href="{{ route('profile') }}"
                           style="display:flex;align-items:center;gap:8px;padding:8px 12px;border-radius:8px;font-size:.82rem;color:var(--tx2);text-decoration:none;transition:background .15s">
                            <i class="bi bi-person"></i> Profil Saya
                        </a>
                        <a href="{{ route('admin.settings') }}"
                           style="display:flex;align-items:center;gap:8px;padding:8px 12px;border-radius:8px;font-size:.82rem;color:var(--tx2);text-decoration:none;transition:background .15s">
                            <i class="bi bi-sliders"></i> Pengaturan
                        </a>
                        <div style="height:1px;background:var(--border);margin:4px 0"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                    style="display:flex;align-items:center;gap:8px;width:100%;padding:8px 12px;border-radius:8px;font-size:.82rem;color:#ef4444;background:none;border:none;cursor:pointer;font-family:inherit;text-align:left;transition:background .15s">
                                <i class="bi bi-box-arrow-right"></i> Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- Content --}}
        <main class="adm-content">
            @if(session('success'))
            <div style="display:flex;align-items:flex-start;gap:10px;padding:12px 16px;border-radius:10px;background:#E8F5E9;border:1px solid #A5D6A7;color:#1a4a1c;font-size:.875rem;margin-bottom:20px;animation:fadeIn .4s both"
                 x-data x-init="setTimeout(()=>$el.remove(), 4000)">
                <i class="bi bi-check-circle-fill" style="font-size:1rem;flex-shrink:0;margin-top:1px"></i>
                <span>{{ session('success') }}</span>
            </div>
            @endif
            @if(session('error'))
            <div style="display:flex;align-items:flex-start;gap:10px;padding:12px 16px;border-radius:10px;background:#FEF2F2;border:1px solid #FECACA;color:#B91C1C;font-size:.875rem;margin-bottom:20px">
                <i class="bi bi-exclamation-circle-fill" style="font-size:1rem;flex-shrink:0;margin-top:1px"></i>
                <span>{{ session('error') }}</span>
            </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<style>
@keyframes fadeIn { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
/* hover state for dropdown items */
.adm-user-chip div a:hover,
.adm-user-chip div button:hover { background: var(--surface2) !important; }
</style>

@stack('scripts')
</body>
</html>