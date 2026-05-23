@extends('layouts.admin')

@section('title', 'Dashboard Admin — Librova')
@section('header-title', 'Dashboard')

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush

@push('styles')
<style>
    .dash-content {
        padding: 28px 28px 40px;
        flex: 1;
    }

    .dash-page-head {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        margin-bottom: 28px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .dash-page-eyebrow {
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: var(--tx3);
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .dash-page-eyebrow-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--primary);
    }

    .dash-page-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.7rem;
        font-weight: 700;
        letter-spacing: -.02em;
        color: var(--tx);
    }

    .dash-page-title em {
        font-style: italic;
        color: var(--primary);
    }

    .dash-date {
        display: flex;
        align-items: center;
        gap: 7px;
        font-size: .8rem;
        color: var(--tx3);
        font-weight: 500;
        padding: 7px 14px;
        border-radius: 100px;
        background: var(--surface);
        border: 1px solid var(--border);
        white-space: nowrap;
    }

    .quick-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .qa-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 100px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: .78rem;
        font-weight: 600;
        cursor: pointer;
        transition: all .18s;
        border: none;
        text-decoration: none;
    }

    .qa-btn-primary {
        background: var(--primary);
        color: #fff;
        box-shadow: 0 2px 8px var(--shadow);
    }

    [data-theme="dark"] .qa-btn-primary {
        color: var(--bg);
    }

    .qa-btn-primary:hover {
        background: var(--primary-h);
        transform: translateY(-1px);
    }

    .qa-btn-outline {
        background: var(--surface);
        color: var(--tx2);
        border: 1.5px solid var(--border);
    }

    .qa-btn-outline:hover {
        border-color: var(--primary);
        color: var(--primary);
        background: rgba(44, 95, 46, .04);
    }

    /* Stat Grid */
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    @media (max-width: 1100px) {
        .stat-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 640px) {
        .stat-grid {
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
    }

    .stat-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 20px;
        position: relative;
        overflow: hidden;
        transition: transform .25s cubic-bezier(.34, 1.56, .64, 1), box-shadow .25s, border-color .2s;
        animation: statUp .5s cubic-bezier(.22, 1, .36, 1) both;
    }

    .stat-card:nth-child(1) {
        animation-delay: .04s;
    }

    .stat-card:nth-child(2) {
        animation-delay: .09s;
    }

    .stat-card:nth-child(3) {
        animation-delay: .14s;
    }

    .stat-card:nth-child(4) {
        animation-delay: .19s;
    }

    @keyframes statUp {
        from {
            opacity: 0;
            transform: translateY(18px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 32px var(--shadow);
        border-color: var(--border2);
    }

    .stat-card::after {
        content: '';
        position: absolute;
        top: -30px;
        right: -30px;
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: var(--stat-glow, rgba(44, 95, 46, .05));
        pointer-events: none;
    }

    .stat-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 14px;
    }

    .stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
        transition: transform .25s cubic-bezier(.34, 1.56, .64, 1);
    }

    .stat-card:hover .stat-icon {
        transform: scale(1.12) rotate(4deg);
    }

    .stat-trend {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        font-size: .7rem;
        font-weight: 700;
        padding: 3px 9px;
        border-radius: 100px;
    }

    .trend-up {
        background: rgba(34, 197, 94, .1);
        color: #16a34a;
    }

    .trend-down {
        background: rgba(239, 68, 68, .1);
        color: #ef4444;
    }

    .trend-neutral {
        background: var(--surface2);
        color: var(--tx3);
    }

    [data-theme="dark"] .trend-up {
        background: rgba(74, 222, 128, .12);
        color: #4ADE80;
    }

    [data-theme="dark"] .trend-down {
        background: rgba(252, 165, 165, .12);
        color: #FCA5A5;
    }

    .stat-num {
        font-family: 'Playfair Display', serif;
        font-size: 2.1rem;
        font-weight: 700;
        color: var(--tx);
        line-height: 1;
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: .8rem;
        font-weight: 600;
        color: var(--tx2);
        margin-bottom: 3px;
    }

    .stat-sub {
        font-size: .72rem;
        color: var(--tx3);
    }

    .stat-bar {
        height: 3px;
        border-radius: 2px;
        background: var(--border);
        margin-top: 14px;
        overflow: hidden;
    }

    .stat-bar-fill {
        height: 100%;
        border-radius: 2px;
        background: var(--stat-color, var(--primary));
        transition: width 1s cubic-bezier(.22, 1, .36, 1);
    }

    /* Charts */
    .charts-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 16px;
    }

    @media (max-width: 900px) {
        .charts-row {
            grid-template-columns: 1fr;
        }
    }

    /* Section Card */
    .sec-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
        animation: statUp .5s cubic-bezier(.22, 1, .36, 1) both;
    }

    .sec-card:nth-child(1) {
        animation-delay: .22s;
    }

    .sec-card:nth-child(2) {
        animation-delay: .28s;
    }

    .sec-card:nth-child(3) {
        animation-delay: .32s;
    }

    .sec-card:nth-child(4) {
        animation-delay: .36s;
    }

    .sec-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 20px;
        border-bottom: 1px solid var(--border);
    }

    .sec-title {
        font-family: 'Playfair Display', serif;
        font-size: 1rem;
        font-weight: 700;
        color: var(--tx);
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .sec-title-icon {
        width: 32px;
        height: 32px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }

    .sec-link {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: .76rem;
        font-weight: 600;
        color: var(--primary);
        padding: 5px 12px;
        border-radius: 100px;
        border: 1.5px solid transparent;
        transition: background .15s, border-color .15s;
        text-decoration: none;
    }

    .sec-link:hover {
        background: rgba(44, 95, 46, .06);
        border-color: rgba(44, 95, 46, .15);
    }

    [data-theme="dark"] .sec-link:hover {
        background: rgba(74, 222, 128, .07);
        border-color: rgba(74, 222, 128, .2);
    }

    .chart-wrap {
        padding: 16px 20px 20px;
        position: relative;
    }

    .chart-wrap canvas {
        width: 100% !important;
    }

    .wide-card {
        margin-bottom: 16px;
    }

    /* Bottom */
    .bottom-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    @media (max-width: 900px) {
        .bottom-row {
            grid-template-columns: 1fr;
        }
    }

    /* Table */
    .adm-table {
        width: 100%;
        border-collapse: collapse;
    }

    .adm-table thead tr {
        background: var(--surface2);
    }

    .adm-table th {
        padding: 10px 16px;
        font-size: .68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .07em;
        color: var(--tx3);
        text-align: left;
        border-bottom: 1px solid var(--border);
    }

    .adm-table td {
        padding: 11px 16px;
        font-size: .84rem;
        color: var(--tx2);
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
    }

    .adm-table tbody tr:last-child td {
        border-bottom: none;
    }

    .adm-table tbody tr:hover td {
        background: rgba(250, 247, 242, .6);
    }

    [data-theme="dark"] .adm-table tbody tr:hover td {
        background: rgba(40, 39, 31, .7);
    }

    .tbl-cover {
        width: 36px;
        height: 52px;
        border-radius: 6px;
        flex-shrink: 0;
        box-shadow: 2px 2px 8px rgba(0, 0, 0, .12);
    }

    .tbl-book-name {
        font-weight: 600;
        color: var(--tx);
        font-size: .85rem;
    }

    .tbl-book-author {
        font-size: .73rem;
        color: var(--tx3);
        margin-top: 1px;
    }

    .tbl-cat {
        font-size: .65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: var(--primary);
        background: rgba(44, 95, 46, .08);
        padding: 2px 8px;
        border-radius: 4px;
    }

    [data-theme="dark"] .tbl-cat {
        background: rgba(74, 222, 128, .1);
    }

    .tbl-views {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: .78rem;
        color: var(--tx2);
        font-weight: 500;
    }

    /* User list */
    .user-list {
        padding: 6px 0;
    }

    .user-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 20px;
        transition: background .15s;
        border-bottom: 1px solid var(--border);
    }

    .user-row:last-child {
        border-bottom: none;
    }

    .user-row:hover {
        background: var(--surface2);
    }

    .user-av {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: rgba(44, 95, 46, .1);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .85rem;
        font-weight: 700;
        color: var(--primary);
        flex-shrink: 0;
    }

    [data-theme="dark"] .user-av {
        background: rgba(74, 222, 128, .1);
    }

    .user-name {
        font-size: .85rem;
        font-weight: 600;
        color: var(--tx);
    }

    .user-email {
        font-size: .73rem;
        color: var(--tx3);
        margin-top: 1px;
    }

    .user-time {
        margin-left: auto;
        font-size: .72rem;
        color: var(--tx3);
        display: flex;
        align-items: center;
        gap: 4px;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .verified-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #22c55e;
        flex-shrink: 0;
        box-shadow: 0 0 0 2px rgba(34, 197, 94, .2);
    }

    @media (max-width: 540px) {
        .dash-content {
            padding: 16px;
        }

        .stat-num {
            font-size: 1.65rem;
        }
    }
</style>
@endpush

@section('content')
<div class="dash-content">

    {{-- Page Head --}}
    <div class="dash-page-head">
        <div>
            <div class="dash-page-eyebrow"><span class="dash-page-eyebrow-dot"></span>Selamat datang kembali</div>
            <div class="dash-page-title">Halo, <em>{{ explode(' ', auth()->user()->name)[0] }}</em> 👋</div>
        </div>
        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
            <div class="dash-date"><i class="bi bi-calendar3"></i> {{ now()->translatedFormat('l, d F Y') }}</div>
            <div class="quick-actions">
                <a href="{{ route('admin.books.create') }}" class="qa-btn qa-btn-primary"><i class="bi bi-plus-lg"></i> Tambah Buku</a>
                <a href="{{ route('admin.reports') }}" class="qa-btn qa-btn-outline"><i class="bi bi-download"></i> Ekspor</a>
            </div>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="stat-grid">
        <div class="stat-card" style="--stat-glow:rgba(44,95,46,.06);--stat-color:var(--primary)">
            <div class="stat-top">
                <div class="stat-icon" style="background:rgba(44,95,46,.1);color:var(--primary)"><i class="bi bi-journal-richtext"></i></div>
                <span class="stat-trend trend-up"><i class="bi bi-arrow-up"></i> 12%</span>
            </div>
            <div class="stat-num">{{ number_format($totalBooks) }}</div>
            <div class="stat-label">Total Buku</div>
            <div class="stat-sub">{{ \App\Models\Book::whereMonth('created_at', now()->month)->count() }} buku ditambahkan bulan ini</div>
            <div class="stat-bar">
                <div class="stat-bar-fill" style="width:72%"></div>
            </div>
        </div>
        <div class="stat-card" style="--stat-glow:rgba(99,102,241,.06);--stat-color:#6366f1">
            <div class="stat-top">
                <div class="stat-icon" style="background:rgba(99,102,241,.1);color:#6366f1"><i class="bi bi-people"></i></div>
                <span class="stat-trend trend-up"><i class="bi bi-arrow-up"></i> 8%</span>
            </div>
            <div class="stat-num">{{ number_format($totalUsers) }}</div>
            <div class="stat-label">Pengguna Terdaftar</div>
            <div class="stat-sub">{{ \App\Models\User::whereRole('user')->whereMonth('created_at', now()->month)->count() }} user baru bulan ini</div>
            <div class="stat-bar">
                <div class="stat-bar-fill" style="width:85%;background:#6366f1"></div>
            </div>
        </div>
        <div class="stat-card" style="--stat-glow:rgba(245,158,11,.06);--stat-color:var(--gold)">
            <div class="stat-top">
                <div class="stat-icon" style="background:rgba(245,158,11,.1);color:var(--gold)"><i class="bi bi-star-fill"></i></div>
                <span class="stat-trend trend-up"><i class="bi bi-arrow-up"></i> 5%</span>
            </div>
            <div class="stat-num">{{ number_format($totalRatings) }}</div>
            <div class="stat-label">Total Rating</div>
            <div class="stat-sub">Dari {{ number_format($totalReviews) }} review tertulis</div>
            <div class="stat-bar">
                <div class="stat-bar-fill" style="width:60%;background:var(--gold)"></div>
            </div>
        </div>
        <div class="stat-card" style="--stat-glow:rgba(239,68,68,.06);--stat-color:#ef4444">
            <div class="stat-top">
                <div class="stat-icon" style="background:rgba(239,68,68,.08);color:#ef4444"><i class="bi bi-envelope-paper"></i></div>
                <span class="stat-trend trend-down"><i class="bi bi-arrow-down"></i> 2%</span>
            </div>
            <div class="stat-num">{{ $newFeedbacks }}</div>
            <div class="stat-label">Feedback Baru</div>
            <div class="stat-sub">Menunggu balasan admin</div>
            <div class="stat-bar">
                <div class="stat-bar-fill" style="width:28%;background:#ef4444"></div>
            </div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="charts-row">
        <div class="sec-card">
            <div class="sec-head">
                <div class="sec-title">
                    <div class="sec-title-icon" style="background:rgba(245,158,11,.1)"><i class="bi bi-bar-chart-fill" style="color:var(--gold)"></i></div>
                    Distribusi Rating
                </div>
                <span style="font-size:.72rem;color:var(--tx3)">Semua waktu</span>
            </div>
            <div class="chart-wrap"><canvas id="ratingChart" height="220"></canvas></div>
        </div>
        <div class="sec-card">
            <div class="sec-head">
                <div class="sec-title">
                    <div class="sec-title-icon" style="background:rgba(44,95,46,.1)"><i class="bi bi-pie-chart-fill" style="color:var(--primary)"></i></div>
                    Buku per Kategori
                </div>
                <span style="font-size:.72rem;color:var(--tx3)">{{ number_format($totalBooks) }} total</span>
            </div>
            <div class="chart-wrap"><canvas id="categoryChart" height="220"></canvas></div>
        </div>
    </div>

    {{-- User Registrations --}}
    <div class="sec-card wide-card">
        <div class="sec-head">
            <div class="sec-title">
                <div class="sec-title-icon" style="background:rgba(99,102,241,.1)"><i class="bi bi-person-plus-fill" style="color:#6366f1"></i></div>
                Pendaftaran Pengguna (6 Bulan Terakhir)
            </div>
            <div style="display:flex;align-items:center;gap:6px">
                <span style="width:10px;height:10px;border-radius:50%;background:#6366f1;display:inline-block"></span>
                <span style="font-size:.72rem;color:var(--tx3)">User baru per bulan</span>
            </div>
        </div>
        <div class="chart-wrap"><canvas id="userChart" height="200"></canvas></div>
    </div>

    {{-- Bottom Row --}}
    <div class="bottom-row">
        <div class="sec-card">
            <div class="sec-head">
                <div class="sec-title">
                    <div class="sec-title-icon" style="background:rgba(239,68,68,.08)"><i class="bi bi-fire" style="color:#ef4444"></i></div>
                    Buku Terpopuler
                </div>
                <a href="{{ route('admin.books.index') }}" class="sec-link">Lihat Semua <i class="bi bi-arrow-right" style="font-size:10px"></i></a>
            </div>
            <table class="adm-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Buku</th>
                        <th>Kategori</th>
                        <th>Views</th>
                        <th>Rating</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($popularBooks as $index => $book)
                    <tr>
                        <td style="color:{{ $index < 3 ? ($index == 0 ? 'var(--gold)' : ($index == 1 ? 'var(--tx3)' : '#8B7355')) : 'var(--tx3)' }};font-weight:700;font-size:.9rem">{{ str_pad($index+1,2,'0',STR_PAD_LEFT) }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px">
                                <div class="tbl-cover" style="
                            @if($book->cover_image) 
                                background-image: url('{{ Storage::url($book->cover_image) }}'); 
                                background-size: cover; 
                                background-position: center; 
                            @else 
                                background: linear-gradient(145deg, {{ $book->cover_color ?? '#2C5F2E' }}, {{ $book->cover_color_dark ?? '#1d4220' }}); 
                            @endif">
                                </div>
                                <div>
                                    <div class="tbl-book-name">{{ Str::limit($book->title, 28) }}</div>
                                    <div class="tbl-book-author">{{ $book->author }}</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="tbl-cat">{{ $book->category->name ?? '-' }}</span></td>
                        <td><span class="tbl-views"><i class="bi bi-eye"></i> {{ number_format($book->view_count) }}</span></td>
                        <td>
                            <span style="color:var(--gold);font-size:12px">@for($i=1; $i<=5; $i++)<i class="bi {{ $i <= round($book->averageRating()) ? 'bi-star-fill' : 'bi-star' }}"></i>@endfor</span>
                            <span style="font-size:.75rem;color:var(--tx3)">{{ number_format($book->averageRating(),1) }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center;color:var(--tx3);padding:24px;">Belum ada data buku.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="sec-card">
            <div class="sec-head">
                <div class="sec-title">
                    <div class="sec-title-icon" style="background:rgba(99,102,241,.09)"><i class="bi bi-person-check" style="color:#6366f1"></i></div>
                    Pengguna Terbaru
                </div>
                <a href="{{ route('admin.users.index') }}" class="sec-link">Lihat Semua <i class="bi bi-arrow-right" style="font-size:10px"></i></a>
            </div>
            <div class="user-list">
                @forelse($latestUsers as $user)
                <div class="user-row">
                    <div class="user-av">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                    <div>
                        <div class="user-name">{{ $user->name }}</div>
                        <div class="user-email">{{ $user->email }}</div>
                    </div>
                    @if($user->hasVerifiedEmail())<span class="verified-dot"></span>@endif
                    <div class="user-time"><i class="bi bi-clock" style="font-size:10px"></i> {{ $user->created_at->diffForHumans() }}</div>
                </div>
                @empty
                <div style="text-align:center;color:var(--tx3);padding:24px;">Belum ada pengguna.</div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const border = getComputedStyle(document.documentElement).getPropertyValue('--border').trim() || '#DDD8CC';
        const tx3 = getComputedStyle(document.documentElement).getPropertyValue('--tx3').trim() || '#9A9282';
        const surface = getComputedStyle(document.documentElement).getPropertyValue('--surface').trim() || '#FFFFFF';

        Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
        Chart.defaults.color = tx3;

        const ratingData = @json($ratingsDistribution);
        new Chart(document.getElementById('ratingChart'), {
            type: 'bar',
            data: {
                labels: ['1★', '2★', '3★', '4★', '5★'],
                datasets: [{
                    data: [ratingData[1] ?? 0, ratingData[2] ?? 0, ratingData[3] ?? 0, ratingData[4] ?? 0, ratingData[5] ?? 0],
                    backgroundColor: ['#ef4444', '#f97316', '#eab308', '#84cc16', '#22c55e'],
                    borderRadius: 8,
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: border
                        },
                        ticks: {
                            color: tx3,
                            font: {
                                size: 11
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: tx3,
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });

        const catData = @json($booksPerCategory);
        new Chart(document.getElementById('categoryChart'), {
            type: 'doughnut',
            data: {
                labels: catData.map(c => c.name),
                datasets: [{
                    data: catData.map(c => c.count),
                    backgroundColor: ['#2C5F2E', '#4ADE80', '#6366f1', '#f59e0b', '#ef4444', '#8B3A3A', '#1a5c7a', '#5b2d8e'],
                    borderWidth: 3,
                    borderColor: surface,
                    hoverBorderWidth: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: tx3,
                            font: {
                                size: 11
                            },
                            padding: 14,
                            boxWidth: 10,
                            usePointStyle: true
                        }
                    }
                }
            }
        });

        const userReg = @json($userRegistrations);
        new Chart(document.getElementById('userChart'), {
            type: 'line',
            data: {
                labels: userReg.map(u => u.month),
                datasets: [{
                    label: 'Pendaftar Baru',
                    data: userReg.map(u => u.total),
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99,102,241,0.08)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#6366f1',
                    pointBorderColor: surface,
                    pointBorderWidth: 2.5,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    borderWidth: 2.5,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: border
                        },
                        ticks: {
                            color: tx3,
                            font: {
                                size: 11
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: tx3,
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });

        // Animate stat bars
        document.querySelectorAll('.stat-bar-fill').forEach(el => {
            const w = el.style.width;
            el.style.width = '0%';
            setTimeout(() => {
                el.style.width = w;
            }, 300);
        });
    });
</script>
@endpush