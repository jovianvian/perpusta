@extends('layouts.app')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-slate-800 dark:text-white">Dashboard</h1>
    <p class="text-slate-500 dark:text-slate-400">Selamat datang kembali, {{ session('name') }}! Berikut adalah aktivitas hari ini.</p>
</div>

@if(session('level') == 3)
<!-- ================== PEMINJAM DASHBOARD ================== -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Active Loans -->
    <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-lg border border-slate-200 dark:border-slate-700 flex items-center gap-4 transition-colors">
        <div class="w-12 h-12 rounded-full bg-amber-500/10 flex items-center justify-center text-amber-500 dark:text-amber-400">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <h3 class="text-slate-500 dark:text-slate-400 text-sm font-medium">Buku Dipinjam</h3>
            <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ $my_active_loans }}</p>
        </div>
    </div>

    <!-- Total History -->
    <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-lg border border-slate-200 dark:border-slate-700 flex items-center gap-4 transition-colors">
        <div class="w-12 h-12 rounded-full bg-indigo-500/10 flex items-center justify-center text-indigo-500 dark:text-indigo-400">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
        </div>
        <div>
            <h3 class="text-slate-500 dark:text-slate-400 text-sm font-medium">Total Riwayat</h3>
            <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ $my_total_loans }}</p>
        </div>
    </div>

    <!-- Returned -->
    <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-lg border border-slate-200 dark:border-slate-700 flex items-center gap-4 transition-colors">
        <div class="w-12 h-12 rounded-full bg-emerald-500/10 flex items-center justify-center text-emerald-500 dark:text-emerald-400">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <div>
            <h3 class="text-slate-500 dark:text-slate-400 text-sm font-medium">Dikembalikan</h3>
            <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ $my_returned_loans }}</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Personal Chart -->
    <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 p-6 transition-colors">
        <div class="mb-6">
            <h2 class="text-lg font-bold text-slate-800 dark:text-white">Statistik Peminjaman Saya</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400">Grafik aktivitas peminjaman Anda tahun ini.</p>
        </div>
        <div class="relative h-80">
            <canvas id="loansChart"></canvas>
        </div>
    </div>

    <!-- Personal Recent Activity -->
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 p-6 transition-colors">
        <h2 class="text-lg font-bold text-slate-800 dark:text-white mb-6">Aktivitas Terakhir</h2>
        <div class="space-y-6">
            @forelse($recent_activities as $activity)
            <div class="flex gap-4">
                <div class="w-10 h-10 rounded-full flex-shrink-0 flex items-center justify-center 
                    {{ $activity->status == 'dipinjam' ? 'bg-amber-500/10 text-amber-500 dark:text-amber-400' : 'bg-emerald-500/10 text-emerald-500 dark:text-emerald-400' }}">
                    @if($activity->status == 'dipinjam')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    @else
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    @endif
                </div>
                <div>
                    <p class="text-sm text-slate-800 dark:text-white">
                        {{ $activity->status == 'dipinjam' ? 'Meminjam' : 'Mengembalikan' }} 
                        <span class="text-indigo-500 dark:text-indigo-400">{{ $activity->book_title }}</span>
                    </p>
                    <p class="text-xs text-slate-500 mt-1">{{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}</p>
                </div>
            </div>
            @empty
            <p class="text-slate-500 italic text-sm">Belum ada aktivitas.</p>
            @endforelse
        </div>
        <div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-700 text-center">
            <a href="{{ url('/riwayat') }}" class="text-sm text-indigo-500 dark:text-indigo-400 hover:text-indigo-600 dark:hover:text-indigo-300 font-medium">Lihat Riwayat Lengkap &rarr;</a>
        </div>
    </div>
</div>

@else
<!-- ================== ADMIN / SUPER ADMIN DASHBOARD ================== -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Books -->
    <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-lg border border-slate-200 dark:border-slate-700 flex items-center gap-4 transition-colors">
        <div class="w-12 h-12 rounded-full bg-indigo-500/10 flex items-center justify-center text-indigo-500 dark:text-indigo-400">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
        </div>
        <div>
            <h3 class="text-slate-500 dark:text-slate-400 text-sm font-medium">Total Buku</h3>
            <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ $total_books }}</p>
        </div>
    </div>

    <!-- Active Loans -->
    <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-lg border border-slate-200 dark:border-slate-700 flex items-center gap-4 transition-colors">
        <div class="w-12 h-12 rounded-full bg-amber-500/10 flex items-center justify-center text-amber-500 dark:text-amber-400">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <h3 class="text-slate-500 dark:text-slate-400 text-sm font-medium">Peminjaman Aktif</h3>
            <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ $active_loans }}</p>
        </div>
    </div>

    <!-- Today's Loans -->
    <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-lg border border-slate-200 dark:border-slate-700 flex items-center gap-4 transition-colors">
        <div class="w-12 h-12 rounded-full bg-emerald-500/10 flex items-center justify-center text-emerald-500 dark:text-emerald-400">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        </div>
        <div>
            <h3 class="text-slate-500 dark:text-slate-400 text-sm font-medium">Dipinjam Hari Ini</h3>
            <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ $today_loans }}</p>
        </div>
    </div>

    <!-- Total Users -->
    <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-lg border border-slate-200 dark:border-slate-700 flex items-center gap-4 transition-colors">
        <div class="w-12 h-12 rounded-full bg-blue-500/10 flex items-center justify-center text-blue-500 dark:text-blue-400">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        </div>
        <div>
            <h3 class="text-slate-500 dark:text-slate-400 text-sm font-medium">Total Pengguna</h3>
            <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ $total_users }}</p>
        </div>
    </div>
</div>

<!-- Charts & Activity -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Main Chart -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Line Chart -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 p-6 transition-colors">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-bold text-slate-800 dark:text-white">Statistik Peminjaman (Global)</h2>
                <div class="flex gap-2">
                    <select id="chartType" class="bg-slate-100 dark:bg-slate-700 border-none text-slate-900 dark:text-white text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 p-2.5 transition-colors">
                        <option value="line">Line Chart</option>
                        <option value="bar">Bar Chart</option>
                        <option value="pie">Pie Chart</option>
                    </select>
                    <select id="chartFilter" class="bg-slate-100 dark:bg-slate-700 border-none text-slate-900 dark:text-white text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 p-2.5 transition-colors">
                        <option value="monthly">Bulanan (Tahun Ini)</option>
                        <option value="daily">Harian (Minggu Ini)</option>
                    </select>
                </div>
            </div>
            <div class="relative h-64">
                <canvas id="loansChart"></canvas>
            </div>
        </div>

        <!-- Bar Chart (Top 3 Books) -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 p-6 transition-colors">
            <h2 class="text-lg font-bold text-slate-800 dark:text-white mb-6">Top 3 Buku Terfavorit</h2>
            <div class="relative h-64">
                <canvas id="topBooksChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 p-6 transition-colors">
        <h2 class="text-lg font-bold text-slate-800 dark:text-white mb-6">Aktivitas Terbaru</h2>
        <div class="space-y-6">
            @forelse($recent_activities as $activity)
            <div class="flex gap-4">
                <div class="w-10 h-10 rounded-full flex-shrink-0 flex items-center justify-center 
                    {{ $activity->status == 'dipinjam' ? 'bg-amber-500/10 text-amber-500 dark:text-amber-400' : 'bg-emerald-500/10 text-emerald-500 dark:text-emerald-400' }}">
                    @if($activity->status == 'dipinjam')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    @else
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    @endif
                </div>
                <div>
                    <p class="text-sm text-slate-800 dark:text-white">
                        <span class="font-bold">{{ $activity->user_name }}</span> 
                        {{ $activity->status == 'dipinjam' ? 'meminjam' : 'mengembalikan' }} 
                        <span class="text-indigo-500 dark:text-indigo-400">{{ $activity->book_title }}</span>
                    </p>
                    <p class="text-xs text-slate-500 mt-1">{{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}</p>
                </div>
            </div>
            @empty
            <p class="text-slate-500 italic text-sm">Belum ada aktivitas.</p>
            @endforelse
        </div>
        <div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-700 text-center">
            <a href="{{ url('/peminjaman') }}" class="text-sm text-indigo-500 dark:text-indigo-400 hover:text-indigo-600 dark:hover:text-indigo-300 font-medium">Lihat Semua Transaksi &rarr;</a>
        </div>
    </div>
</div>
@endif

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('loansChart').getContext('2d');
    
    // Data passed from Controller
    const monthlyData = @json($chart_data); 
    const monthlyLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

    const dailyData = @json($daily_data ?? []); // Default empty array for member who doesn't have daily data
    const dailyLabels = @json($daily_labels ?? []);
    
    let currentChartType = 'line';
    let currentDataType = 'monthly';
    let loansChart = null;

    function renderChart() {
        if (loansChart) {
            loansChart.destroy();
        }

        const isPie = currentChartType === 'pie' || currentChartType === 'doughnut';
        const isBar = currentChartType === 'bar';
        
        let labels, data;
        if (currentDataType === 'daily' && dailyData.length > 0) {
            labels = dailyLabels;
            data = dailyData;
        } else {
            labels = monthlyLabels;
            data = monthlyData;
        }

        // Color Palette for Pie
        const pieColors = ['#6366f1', '#818cf8', '#a5b4fc', '#c7d2fe', '#e0e7ff', '#f43f5e', '#fb7185', '#fda4af', '#fcd34d', '#fde68a', '#34d399', '#6ee7b7'];

        loansChart = new Chart(ctx, {
            type: currentChartType,
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Peminjaman',
                    data: data,
                    borderColor: isPie ? '#fff' : '#6366f1',
                    backgroundColor: isPie ? pieColors : (isBar ? '#6366f1' : 'rgba(99, 102, 241, 0.1)'),
                    borderWidth: isPie ? 2 : 2,
                    tension: 0.4,
                    fill: !isPie && !isBar,
                    pointBackgroundColor: '#6366f1'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: isPie,
                        position: 'right'
                    }
                },
                scales: isPie ? {
                    x: { display: false },
                    y: { display: false }
                } : {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#334155' },
                        ticks: { color: '#94a3b8' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#94a3b8' }
                    }
                }
            }
        });
    }

    renderChart();

    const chartFilter = document.getElementById('chartFilter');
    if (chartFilter) {
        chartFilter.addEventListener('change', function(e) {
            currentDataType = e.target.value;
            renderChart();
        });
    }

    const chartType = document.getElementById('chartType');
    if (chartType) {
        chartType.addEventListener('change', function(e) {
            currentChartType = e.target.value;
            renderChart();
        });
    }

    // --- Top Books Bar Chart ---
    const ctxBar = document.getElementById('topBooksChart').getContext('2d');
    const topBookLabels = @json($top_book_labels ?? []);
    const topBookData = @json($top_book_data ?? []);

    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: topBookLabels,
            datasets: [{
                label: 'Total Peminjaman',
                data: topBookData,
                backgroundColor: [
                    'rgba(245, 158, 11, 0.8)', // Amber
                    'rgba(16, 185, 129, 0.8)', // Emerald
                    'rgba(99, 102, 241, 0.8)'  // Indigo
                ],
                borderColor: [
                    'rgba(245, 158, 11, 1)',
                    'rgba(16, 185, 129, 1)',
                    'rgba(99, 102, 241, 1)'
                ],
                borderWidth: 1,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#334155' },
                    ticks: { color: '#94a3b8', stepSize: 1 }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#94a3b8' }
                }
            }
        }
    });
</script>
@endsection