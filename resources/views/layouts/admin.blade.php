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

    <script>
        (function() {
            const s = localStorage.getItem('librova-theme');
            const p = window.matchMedia('(prefers-color-scheme: dark)').matches;
            document.documentElement.setAttribute('data-theme', s ?? (p ? 'dark' : 'light'));
            document.documentElement.classList.remove('no-flash');
        })();
    </script>

    <style>
        html.no-flash body { visibility: hidden; }
        [x-cloak] { display: none !important; }

        :root { --sb: 256px; --hdr: 62px; }

        .adm-wrap { display: flex; min-height: 100vh; background: var(--bg); }
        .adm-sidebar {
            width: var(--sb); background: var(--surface); border-right: 1px solid var(--border);
            display: flex; flex-direction: column; position: fixed; top: 0; left: 0; bottom: 0;
            z-index: 200; overflow: hidden;
            transition: transform .28s cubic-bezier(.4,0,.2,1);
        }
        .adm-brand {
            height: var(--hdr); padding: 0 18px; display: flex; align-items: center; gap: 10px;
            border-bottom: 1px solid var(--border); flex-shrink: 0; text-decoration: none;
        }
        .adm-brand-icon {
            width: 34px; height: 34px; background: var(--primary); border-radius: 10px;
            display: flex; align-items: center; justify-content: center; font-size: 16px; color: #fff;
            flex-shrink: 0; transition: transform .2s cubic-bezier(.34,1.56,.64,1);
        }
        [data-theme="dark"] .adm-brand-icon { color: var(--bg); }
        .adm-brand:hover .adm-brand-icon { transform: rotate(-6deg) scale(1.08); }
        .adm-brand-text {
            font-family: 'Playfair Display', serif; font-size: 1.15rem; font-weight: 700;
            color: var(--primary); white-space: nowrap; overflow: hidden; transition: opacity .2s, width .2s;
        }
        .adm-brand-badge {
            margin-left: auto; font-size: .6rem; font-weight: 700; padding: 2px 7px; border-radius: 4px;
            background: rgba(44,95,46,.1); color: var(--primary); text-transform: uppercase;
            letter-spacing: .06em; flex-shrink: 0;
        }
        [data-theme="dark"] .adm-brand-badge { background: rgba(74,222,128,.1); }

        .adm-nav {
            flex: 1; padding: 14px 10px; display: flex; flex-direction: column; gap: 2px;
            overflow-y: auto; scrollbar-width: none;
        }
        .adm-nav::-webkit-scrollbar { display: none; }
        .adm-nav-label {
            font-size: .68rem; font-weight: 600; text-transform: uppercase; letter-spacing: .12em;
            color: var(--tx2); padding: 12px 12px 6px; opacity: 0.85;
        }
        .adm-link {
            display: flex; align-items: center; gap: 10px; padding: 9px 12px; border-radius: 10px;
            font-size: .84rem; font-weight: 500; color: var(--tx2); text-decoration: none;
            position: relative; transition: background .15s, color .15s; white-space: nowrap;
        }
        .adm-link:hover { background: var(--surface2); color: var(--tx); }
        .adm-link.active { background: rgba(44,95,46,.09); color: var(--primary); font-weight: 600; }
        [data-theme="dark"] .adm-link.active { background: rgba(74,222,128,.1); }
        .adm-link.active::before {
            content: ''; position: absolute; left: 0; top: 20%; bottom: 20%;
            width: 3px; border-radius: 0 3px 3px 0; background: var(--primary);
        }
        .adm-link i { font-size: 1.15rem; width: 22px; text-align: center; flex-shrink: 0; }
        .adm-link-text { overflow: hidden; transition: opacity .15s; }
        .adm-link-badge {
            margin-left: auto; min-width: 20px; height: 20px; padding: 0 6px; border-radius: 10px;
            background: var(--primary); color: #fff; font-size: .62rem; font-weight: 700;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        [data-theme="dark"] .adm-link-badge { color: var(--bg); }
        .adm-nav-divider { height: 1px; background: var(--border); margin: 6px 2px; }

        .adm-sidebar-footer { padding: 12px 10px; border-top: 1px solid var(--border); flex-shrink: 0; }
        .adm-back-link {
            display: flex; align-items: center; gap: 10px; padding: 9px 12px; border-radius: 10px;
            font-size: .82rem; font-weight: 500; color: var(--tx3); text-decoration: none;
            transition: background .15s, color .15s; white-space: nowrap;
        }
        .adm-back-link:hover { background: var(--surface2); color: var(--tx); }
        .adm-back-link i { font-size: 1.1rem; width: 22px; text-align: center; flex-shrink: 0; }

        .adm-main {
            flex: 1; margin-left: var(--sb); display: flex; flex-direction: column; min-width: 0;
            transition: margin-left .28s cubic-bezier(.4,0,.2,1);
        }
        .adm-header {
            height: var(--hdr); background: rgba(250,247,242,0.92);
            backdrop-filter: blur(14px) saturate(180%); -webkit-backdrop-filter: blur(14px) saturate(180%);
            border-bottom: 1px solid var(--border); display: flex; align-items: center;
            justify-content: space-between; padding: 0 24px; position: sticky; top: 0; z-index: 50;
            transition: background .3s;
        }
        [data-theme="dark"] .adm-header { background: rgba(20,20,16,0.92); }

        .adm-header-left { display: flex; align-items: center; gap: 14px; }
        .adm-toggle {
            display: none; width: 36px; height: 36px; border-radius: 9px; background: none;
            border: 1.5px solid var(--border); cursor: pointer; font-size: 1.2rem; color: var(--tx2);
            align-items: center; justify-content: center; transition: background .15s, border-color .15s;
        }
        .adm-toggle:hover { background: var(--surface2); border-color: var(--border2); }

        .adm-breadcrumb { display: flex; align-items: center; gap: 6px; font-size: .8rem; color: var(--tx3); }
        .adm-breadcrumb a { color: var(--tx3); text-decoration: none; transition: color .15s; }
        .adm-breadcrumb a:hover { color: var(--primary); }
        .adm-breadcrumb-sep { font-size: 10px; opacity: .5; }
        .adm-page-title {
            font-family: 'Playfair Display', serif; font-size: 1.1rem; font-weight: 700;
            color: var(--tx); white-space: nowrap;
        }

        .adm-header-right { display: flex; align-items: center; gap: 8px; }

        /* Search bar global */
        .adm-search {
            display: flex; align-items: center; gap: 8px; padding: 7px 14px; border-radius: 100px;
            border: 1.5px solid var(--border); background: var(--surface2); font-size: .82rem;
            color: var(--tx3); cursor: pointer; transition: border-color .2s, background .2s; white-space: nowrap;
        }
        .adm-search:hover { border-color: var(--border2); background: var(--surface); color: var(--tx); }
        .adm-search input {
            border: none; background: transparent; font-family: inherit; font-size: .82rem;
            color: var(--tx2); outline: none; width: 100px; transition: width .2s;
        }
        .adm-search input:focus { width: 200px; }

        .adm-icon-btn {
            width: 36px; height: 36px; border-radius: 10px; border: 1.5px solid var(--border);
            background: var(--surface2); display: flex; align-items: center; justify-content: center;
            cursor: pointer; font-size: .95rem; color: var(--tx2);
            transition: background .15s, border-color .15s, color .15s; position: relative;
        }
        .adm-icon-btn:hover { background: var(--surface); border-color: var(--border2); color: var(--tx); }
        .adm-icon-btn .notif-dot {
            position: absolute; top: 6px; right: 6px; width: 7px; height: 7px;
            border-radius: 50%; background: #ef4444; border: 1.5px solid var(--surface2);
        }

        .adm-user-chip {
            display: flex; align-items: center; gap: 8px; padding: 5px 12px 5px 5px;
            border-radius: 100px; border: 1.5px solid var(--border); background: var(--surface);
            cursor: pointer; transition: border-color .2s, background .2s; text-decoration: none;
        }
        .adm-user-chip:hover { border-color: var(--border2); background: var(--surface2); }
        .adm-user-avatar {
            width: 28px; height: 28px; border-radius: 50%; background: rgba(44,95,46,.12);
            display: flex; align-items: center; justify-content: center; font-size: .75rem;
            font-weight: 700; color: var(--primary); flex-shrink: 0; overflow: hidden;
        }
        [data-theme="dark"] .adm-user-avatar { background: rgba(74,222,128,.12); }
        .adm-user-name { font-size: .82rem; font-weight: 600; color: var(--tx); white-space: nowrap; }

        .adm-content { padding: 28px 24px; flex: 1; }

        .adm-overlay {
            display: none; position: fixed; inset: 0; background: rgba(0,0,0,.45);
            backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px); z-index: 190;
        }
        .adm-overlay.open { display: block; }

        /* ── Scrollbar invisible utility ── */
        .no-scrollbar {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        .no-scrollbar::-webkit-scrollbar { display: none; }

        /* ── Modal logout ── */
        .adm-modal-overlay {
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.55);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }
        .adm-modal-card { animation: modalPop 0.2s ease; }
        @keyframes modalPop { from { transform: scale(0.95); opacity: 0; } to { transform: scale(1); opacity: 1; } }

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
     <x-loader />
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
        <a href="{{ route('admin.dashboard') }}" class="adm-brand">
            <span class="adm-brand-icon"><i class="bi bi-book"></i></span>
            <span class="adm-brand-text">Librova</span>
            <span class="adm-brand-badge">Admin</span>
        </a>
        <nav class="adm-nav">
            <div class="adm-nav-label">Overview</div>
            <a href="{{ route('admin.dashboard') }}"
               class="adm-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i><span class="adm-link-text">Dashboard</span>
            </a>
            <div class="adm-nav-divider"></div>
            <div class="adm-nav-label">Konten</div>
            <a href="{{ route('admin.books.index') }}"
               class="adm-link {{ request()->routeIs('admin.books.*') ? 'active' : '' }}">
                <i class="bi bi-journal-richtext"></i><span class="adm-link-text">Manajemen Buku</span>
            </a>
            <a href="{{ route('admin.categories.index') }}"
               class="adm-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i><span class="adm-link-text">Kategori</span>
            </a>
            <div class="adm-nav-divider"></div>
            <div class="adm-nav-label">Komunitas</div>
            <a href="{{ route('admin.users.index') }}"
               class="adm-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i><span class="adm-link-text">Manajemen User</span>
            </a>
            <a href="{{ route('admin.reviews.index') }}"
               class="adm-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                <i class="bi bi-chat-left-text"></i><span class="adm-link-text">Moderasi Review</span>
                @php $pendingReviews = \App\Models\Review::where('status','pending')->count(); @endphp
                @if($pendingReviews > 0)<span class="adm-link-badge">{{ $pendingReviews }}</span>@endif
            </a>
            <a href="{{ route('admin.feedbacks.index') }}"
               class="adm-link {{ request()->routeIs('admin.feedbacks.*') ? 'active' : '' }}">
                <i class="bi bi-envelope-paper"></i><span class="adm-link-text">Feedback</span>
                @php $newFeedbacks = \App\Models\Feedback::where('status','new')->count(); @endphp
                @if($newFeedbacks > 0)<span class="adm-link-badge">{{ $newFeedbacks }}</span>@endif
            </a>
            <div class="adm-nav-divider"></div>
            <div class="adm-nav-label">Sistem</div>
            <a href="{{ route('admin.settings') }}"
               class="adm-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                <i class="bi bi-sliders"></i><span class="adm-link-text">Pengaturan</span>
            </a>
        </nav>
        <div class="adm-sidebar-footer">
            <a href="{{ route('dashboard') }}" class="adm-back-link">
                <i class="bi bi-arrow-left-circle"></i><span class="adm-link-text">Kembali ke User Area</span>
            </a>
        </div>
    </aside>

    {{-- ══ MAIN ══ --}}
    <div class="adm-main">
        <header class="adm-header">
            <div class="adm-header-left">
                <button class="adm-toggle" @click="open = !open" aria-label="Toggle menu">
                    <i class="bi" :class="open ? 'bi-x-lg' : 'bi-list'"></i>
                </button>
                <div>
                    <div class="adm-breadcrumb">
                        <a href="{{ route('admin.dashboard') }}">Admin</a>
                        @hasSection('breadcrumb')
                        <span class="adm-breadcrumb-sep"><i class="bi bi-chevron-right"></i></span>
                        @yield('breadcrumb')
                        @endif
                    </div>
                    <div class="adm-page-title">@yield('header-title', 'Dashboard')</div>
                </div>
            </div>

            {{-- HEADER RIGHT --}}
            <div class="adm-header-right"
                 x-data="{ menuOpen: false, showLogoutModal: false }">

                {{-- 🔍 SEARCH BAR GLOBAL --}}
                <form id="globalSearchForm" method="GET" action="{{ route('admin.books.index') }}" style="margin:0;">
                    <div class="adm-search" onclick="document.getElementById('globalSearchInput').focus()">
                        <i class="bi bi-search" style="font-size:.85rem"></i>
                        <input id="globalSearchInput" type="text" name="search" placeholder="Cari…" 
                               autocomplete="off">
                        <span style="font-size:.72rem;padding:1px 6px;border-radius:4px;background:var(--surface2);border:1px solid var(--border);color:var(--tx3);margin-left:4px">⌘K</span>
                    </div>
                </form>

                {{-- Notifikasi --}}
                <div class="adm-icon-btn" style="position:relative" x-data="{ notifOpen: false }" @click="notifOpen = !notifOpen">
                    <i class="bi bi-bell"></i>
                    @php $unreadCount = \App\Models\AdminNotification::unread()->count(); @endphp
                    @if($unreadCount > 0)<span class="notif-dot" style="top:6px; right:6px;"></span>@endif
                    <div x-show="notifOpen" x-cloak @click.away="notifOpen = false"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="no-scrollbar"
                         style="position:absolute; top:calc(100% + 8px); right:0; width:320px; background:var(--surface); border:1px solid var(--border); border-radius:14px; box-shadow:0 10px 40px rgba(0,0,0,.15); z-index:70; padding:8px 0; max-height:360px; overflow-y:auto;">
                        <div style="padding:10px 16px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between;">
                            <span style="font-weight:700; font-size:.82rem; color:var(--tx)">Notifikasi</span>
                            @if($unreadCount > 0)
                            <span style="font-size:.72rem; background:var(--primary); color:#fff; padding:2px 10px; border-radius:100px;">{{ $unreadCount }} baru</span>
                            @endif
                        </div>
                        @php $recentNotifications = \App\Models\AdminNotification::recent()->get(); @endphp
                        @forelse($recentNotifications as $notif)
                        <a href="{{ route('admin.notifications.read', $notif->id) }}"
                           style="display:flex; align-items:center; gap:10px; padding:10px 16px; text-decoration:none; color:inherit; transition:background .15s; border-bottom:1px solid var(--border);">
                            <div style="width:8px; height:8px; border-radius:50%; background:{{ $notif->read_at ? 'var(--tx3)' : 'var(--primary)' }}; flex-shrink:0; opacity:{{ $notif->read_at ? '.4' : '1' }}"></div>
                            <div style="flex:1; min-width:0;">
                                <div style="font-size:.82rem; color:var(--tx); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $notif->message }}</div>
                                <div style="font-size:.7rem; color:var(--tx3)">{{ $notif->created_at->diffForHumans() }}</div>
                            </div>
                            <i class="bi bi-arrow-right" style="font-size:.7rem; color:var(--tx3)"></i>
                        </a>
                        @empty
                        <div style="padding:20px 16px; text-align:center; font-size:.82rem; color:var(--tx3);">
                            <i class="bi bi-bell-slash" style="font-size:1.2rem; display:block; margin-bottom:4px;"></i> Tidak ada notifikasi
                        </div>
                        @endforelse
                        <a href="{{ route('admin.notifications.index') }}"
                           style="display:block; padding:10px 16px; text-align:center; font-size:.8rem; font-weight:600; color:var(--primary); text-decoration:none; border-top:1px solid var(--border);">
                            Lihat Semua Notifikasi
                        </a>
                    </div>
                </div>

                {{-- Theme toggle --}}
                <button class="adm-icon-btn" @click="toggleTheme" title="Toggle tema">
                    <i class="bi" :class="dark ? 'bi-sun' : 'bi-moon-stars'"></i>
                </button>

                {{-- User chip (dropdown only) --}}
                <div class="adm-user-chip" style="position:relative">
                    <div @click="menuOpen = !menuOpen" style="display:flex; align-items:center; gap:8px; cursor:pointer; flex:1;">
                        <div class="adm-user-avatar">
                            @if(auth()->user()->avatar)
                            <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="" style="width:100%;height:100%;object-fit:cover">
                            @else
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            @endif
                        </div>
                        <span class="adm-user-name">{{ explode(' ', auth()->user()->name)[0] }}</span>
                        <i class="bi bi-chevron-down" style="font-size:.7rem;color:var(--tx3);margin-left:2px"></i>
                    </div>

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
                        <button type="button"
                                @click="menuOpen = false; showLogoutModal = true"
                                style="display:flex;align-items:center;gap:8px;width:100%;padding:8px 12px;border-radius:8px;font-size:.82rem;color:#ef4444;background:none;border:none;cursor:pointer;font-family:inherit;text-align:left;transition:background .15s">
                            <i class="bi bi-box-arrow-right"></i> Keluar
                        </button>
                    </div>
                </div>

                {{-- MODAL LOGOUT --}}
                <template x-teleport="body">
                    <div x-show="showLogoutModal" x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         class="adm-modal-overlay"
                         @click.self="showLogoutModal = false">
                        <div class="adm-modal-card" style="background:var(--surface); border-radius:16px; padding:28px 24px; max-width:380px; width:90%; box-shadow:0 20px 60px rgba(0,0,0,0.25); text-align:center;">
                            <i class="bi bi-box-arrow-right" style="font-size:2.5rem; color:#ef4444;"></i>
                            <h3 style="font-family:'Playfair Display',serif; font-size:1.2rem; color:var(--tx); margin-top:10px;">Konfirmasi Logout</h3>
                            <p style="color:var(--tx2); font-size:.9rem; margin-bottom:20px;">Apakah Anda yakin ingin keluar dari akun?</p>
                            <div style="display:flex; gap:10px; justify-content:center;">
                                <button @click="showLogoutModal = false"
                                        style="padding:10px 20px; border-radius:100px; border:1.5px solid var(--border); background:var(--surface); color:var(--tx2); font-weight:600; cursor:pointer; transition:background .2s;"
                                        class="btn-cancel">Batal</button>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            style="padding:10px 20px; border-radius:100px; background:#ef4444; color:#fff; border:none; font-weight:600; cursor:pointer; transition:background .2s;"
                                            class="btn-logout">Ya, Keluar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </template>
            </div>{{-- /adm-header-right --}}
        </header>

        <main class="adm-content">
            @if(session('success'))
            <div style="display:flex;align-items:flex-start;gap:10px;padding:12px 16px;border-radius:10px;background:#E8F5E9;border:1px solid #A5D6A7;color:#1a4a1c;font-size:.875rem;margin-bottom:20px;animation:fadeIn .4s both"
                 x-data x-init="setTimeout(()=>$el.remove(), 4000)">
                <i class="bi bi-check-circle-fill" style="font-size:1rem;flex-shrink:0;margin-top:1px"></i>
                <span>{{ session('success') }}</span>
            </div>
            @endif
            @yield('content')
        </main>
    </div>
</div>

@push('scripts')
<script>
    // Global search: arahkan ke halaman yang sesuai
    (function() {
        const path = window.location.pathname;
        const form = document.getElementById('globalSearchForm');
        if (!form) return;

        if (path.includes('/admin/books')) {
            form.action = '{{ route('admin.books.index') }}';
        } else if (path.includes('/admin/users')) {
            form.action = '{{ route('admin.users.index') }}';
        } else if (path.includes('/admin/categories')) {
            form.action = '{{ route('admin.categories.index') }}';
        } else if (path.includes('/admin/reviews')) {
            form.action = '{{ route('admin.reviews.index') }}';
        } else if (path.includes('/admin/feedbacks')) {
            form.action = '{{ route('admin.feedbacks.index') }}';
        } else {
            form.action = '{{ route('admin.books.index') }}';
        }
    })();
</script>
@endpush

@stack('scripts')
</body>
</html>