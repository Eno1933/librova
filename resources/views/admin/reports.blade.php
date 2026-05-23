@extends('layouts.admin')

@section('title', 'Laporan & Statistik — Admin Librova')
@section('header-title', 'Laporan & Statistik')

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush

@push('styles')
<style>
    .rpt-content { padding: 28px 28px 40px; }

    .stat-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    @media (max-width: 1100px) { .stat-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 640px) { .stat-grid { grid-template-columns: 1fr 1fr; gap: 10px; } }

    .stat-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 20px;
        transition: transform 0.2s, box-shadow 0.2s;
        animation: cardUp 0.5s ease forwards;
        opacity: 0;
    }
    .stat-card:nth-child(1) { animation-delay: 0s; }
    .stat-card:nth-child(2) { animation-delay: 0.08s; }
    .stat-card:nth-child(3) { animation-delay: 0.16s; }
    .stat-card:nth-child(4) { animation-delay: 0.24s; }
    @keyframes cardUp {
        to { opacity: 1; transform: translateY(0); }
        from { opacity: 0; transform: translateY(16px); }
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px var(--shadow);
    }

    .stat-icon-box {
        width: 48px; height: 48px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem; flex-shrink: 0;
    }
    .stat-value {
        font-family: 'Playfair Display', serif;
        font-size: 2rem; font-weight: 700; color: var(--tx); line-height: 1.2;
    }
    .stat-label { font-size: 0.85rem; color: var(--tx2); font-weight: 500; }

    .charts-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 24px;
    }
    @media (max-width: 900px) { .charts-row { grid-template-columns: 1fr; } }

    .chart-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 20px;
        animation: cardUp 0.5s ease forwards;
        opacity: 0;
    }
    .chart-card:nth-child(1) { animation-delay: 0.1s; }
    .chart-card:nth-child(2) { animation-delay: 0.15s; }
    .chart-card:nth-child(3) { animation-delay: 0.2s; }
    .chart-card-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.1rem; font-weight: 700; color: var(--tx);
        margin-bottom: 16px; display: flex; align-items: center; gap: 8px;
    }
    .chart-wrap { position: relative; width: 100%; height: 280px; }
    .chart-wrap canvas { width: 100% !important; height: 100% !important; }

    .table-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
        animation: cardUp 0.5s ease forwards;
        opacity: 0;
        animation-delay: 0.25s;
    }
    .table-card-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 18px 20px; border-bottom: 1px solid var(--border);
    }
    .table-card-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.1rem; font-weight: 700; color: var(--tx);
        display: flex; align-items: center; gap: 8px;
    }
    .table-card-link {
        font-size: 0.8rem; font-weight: 600; color: var(--primary);
        text-decoration: none; display: flex; align-items: center; gap: 4px;
    }

    .rpt-table {
        width: 100%; border-collapse: collapse;
    }
    .rpt-table thead { background: var(--surface2); }
    .rpt-table th {
        padding: 12px 16px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.06em; color: var(--tx3); text-align: left;
    }
    .rpt-table td {
        padding: 12px 16px; font-size: 0.88rem; color: var(--tx2);
        border-bottom: 1px solid var(--border); vertical-align: middle;
    }
    .rpt-table tr:last-child td { border-bottom: none; }
    .rpt-table tbody tr:hover td { background: var(--surface2); }

    .book-cover-mini {
        width: 36px; height: 52px; border-radius: 6px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1); flex-shrink: 0;
    }
    .user-avatar-mini {
        width: 36px; height: 36px; border-radius: 50%;
        background: var(--surface2); display: flex; align-items: center;
        justify-content: center; font-weight: 700; font-size: 0.85rem;
        color: var(--primary); flex-shrink: 0;
    }
    .bottom-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        margin-top: 24px;
    }
    @media (max-width: 900px) { .bottom-grid { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
<div class="rpt-content">

    {{-- Stat Cards --}}
    <div class="stat-grid">
        <div class="stat-card">
            <div style="display:flex;align-items:center;gap:14px;">
                <div class="stat-icon-box" style="background:rgba(44,95,46,.1);color:var(--primary)"><i class="bi bi-book"></i></div>
                <div>
                    <div class="stat-value">{{ number_format($totalBooks) }}</div>
                    <div class="stat-label">Total Buku</div>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div style="display:flex;align-items:center;gap:14px;">
                <div class="stat-icon-box" style="background:rgba(99,102,241,.1);color:#6366f1"><i class="bi bi-people"></i></div>
                <div>
                    <div class="stat-value">{{ number_format($totalUsers) }}</div>
                    <div class="stat-label">Pengguna</div>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div style="display:flex;align-items:center;gap:14px;">
                <div class="stat-icon-box" style="background:rgba(245,158,11,.1);color:var(--gold)"><i class="bi bi-star"></i></div>
                <div>
                    <div class="stat-value">{{ number_format($totalRatings) }}</div>
                    <div class="stat-label">Total Rating</div>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div style="display:flex;align-items:center;gap:14px;">
                <div class="stat-icon-box" style="background:rgba(239,68,68,.1);color:#ef4444"><i class="bi bi-envelope"></i></div>
                <div>
                    <div class="stat-value">{{ $newFeedbacks }}</div>
                    <div class="stat-label">Feedback Baru</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Row 1 --}}
    <div class="charts-row">
        <div class="chart-card">
            <div class="chart-card-title">
                <i class="bi bi-bar-chart-fill" style="color:var(--gold)"></i> Distribusi Rating
            </div>
            <div class="chart-wrap"><canvas id="ratingChart"></canvas></div>
        </div>
        <div class="chart-card">
            <div class="chart-card-title">
                <i class="bi bi-pie-chart-fill" style="color:var(--primary)"></i> Buku per Kategori
            </div>
            <div class="chart-wrap"><canvas id="categoryChart"></canvas></div>
        </div>
    </div>

    {{-- User Registrations --}}
    <div class="chart-card" style="margin-bottom:24px;">
        <div class="chart-card-title">
            <i class="bi bi-graph-up-arrow" style="color:#6366f1"></i> Pendaftaran Pengguna (6 Bulan)
        </div>
        <div class="chart-wrap" style="height:250px"><canvas id="userChart"></canvas></div>
    </div>

    {{-- Bottom Tables --}}
    <div class="bottom-grid">
        <div class="table-card">
            <div class="table-card-header">
                <div class="table-card-title"><i class="bi bi-fire" style="color:#ef4444"></i> Buku Terpopuler</div>
                <a href="{{ route('admin.books.index') }}" class="table-card-link">Lihat Semua <i class="bi bi-arrow-right" style="font-size:10px"></i></a>
            </div>
            <table class="rpt-table">
                <thead><tr><th>Buku</th><th>Views</th><th>Rating</th></tr></thead>
                <tbody>
                    @forelse($popularBooks as $book)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div class="book-cover-mini" style="background:linear-gradient(135deg,{{ $book->cover_color ?? '#2C5F2E' }},{{ $book->cover_color_dark ?? '#1d4220' }})"></div>
                                <div>
                                    <div style="font-weight:600;color:var(--tx)">{{ Str::limit($book->title, 28) }}</div>
                                    <div style="font-size:.76rem;color:var(--tx3)">{{ $book->author }}</div>
                                </div>
                            </div>
                        </td>
                        <td><i class="bi bi-eye" style="font-size:10px;color:var(--tx3);margin-right:4px;"></i>{{ number_format($book->view_count) }}</td>
                        <td><i class="bi bi-star-fill" style="color:var(--gold)"></i> {{ number_format($book->averageRating(),1) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" style="text-align:center;padding:20px;color:var(--tx3)">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="table-card">
            <div class="table-card-header">
                <div class="table-card-title"><i class="bi bi-person-plus" style="color:#6366f1"></i> Pengguna Terbaru</div>
                <a href="{{ route('admin.users.index') }}" class="table-card-link">Lihat Semua <i class="bi bi-arrow-right" style="font-size:10px"></i></a>
            </div>
            <table class="rpt-table">
                <thead><tr><th>Pengguna</th><th>Bergabung</th></tr></thead>
                <tbody>
                    @forelse($latestUsers as $user)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div class="user-avatar-mini">{{ strtoupper(substr($user->name,0,1)) }}</div>
                                <div>
                                    <div style="font-weight:600;color:var(--tx)">{{ $user->name }}</div>
                                    <div style="font-size:.76rem;color:var(--tx3)">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td><i class="bi bi-clock" style="font-size:10px;color:var(--tx3);margin-right:4px;"></i>{{ $user->created_at->diffForHumans() }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="2" style="text-align:center;padding:20px;color:var(--tx3)">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const border = getComputedStyle(document.documentElement).getPropertyValue('--border').trim() || '#DDD8CC';
    const tx3 = getComputedStyle(document.documentElement).getPropertyValue('--tx3').trim() || '#9A9282';
    const surface = getComputedStyle(document.documentElement).getPropertyValue('--surface').trim() || '#FFFFFF';
    Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
    Chart.defaults.color = tx3;

    // Rating Distribution
    const ratingData = @json($ratingsDistribution);
    new Chart(document.getElementById('ratingChart'), {
        type: 'bar',
        data: {
            labels: ['1★','2★','3★','4★','5★'],
            datasets: [{
                data: [ratingData[1]??0, ratingData[2]??0, ratingData[3]??0, ratingData[4]??0, ratingData[5]??0],
                backgroundColor: ['#ef4444','#f97316','#eab308','#84cc16','#22c55e'],
                borderRadius: 8, borderWidth: 0,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: border }, ticks: { color: tx3 } },
                x: { grid: { display: false }, ticks: { color: tx3 } }
            }
        }
    });

    // Books per Category
    const catData = @json($booksPerCategory);
    new Chart(document.getElementById('categoryChart'), {
        type: 'doughnut',
        data: {
            labels: catData.map(c => c.name),
            datasets: [{
                data: catData.map(c => c.count),
                backgroundColor: ['#2C5F2E','#4ADE80','#6366f1','#f59e0b','#ef4444','#8B3A3A','#1a5c7a','#5b2d8e'],
                borderWidth: 3, borderColor: surface, hoverBorderWidth: 4,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false, cutout: '60%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: tx3, font: { size: 11 }, padding: 14, boxWidth: 10, usePointStyle: true }
                }
            }
        }
    });

    // User Registrations
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
                fill: true, tension: 0.4,
                pointBackgroundColor: '#6366f1', pointBorderColor: surface,
                pointBorderWidth: 2.5, pointRadius: 5, pointHoverRadius: 7, borderWidth: 2.5,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: border }, ticks: { color: tx3 } },
                x: { grid: { display: false }, ticks: { color: tx3 } }
            }
        }
    });
});
</script>
@endpush