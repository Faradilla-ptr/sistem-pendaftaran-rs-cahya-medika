@extends('layouts.app')
@section('title', 'Dashboard Admin - RS Cahya Medika')
@section('page-title', 'Dashboard Admin')

@section('content')

<!-- WELCOME -->
<div style="background:linear-gradient(135deg,#0c4a6e,#0891b2);border-radius:18px;padding:24px 28px;color:white;margin-bottom:24px;display:flex;align-items:center;gap:20px">
    <div style="font-size:48px;flex-shrink:0">🏥</div>
    <div>
        <div style="font-size:20px;font-weight:800;margin-bottom:4px">RS Cahya Medika Bondowoso</div>
        <div style="font-size:13px;opacity:0.75">Panel Administrasi · {{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</div>
        <div style="display:flex;gap:12px;margin-top:10px">
            <span style="background:rgba(255,255,255,0.15);padding:4px 12px;border-radius:8px;font-size:11px;font-weight:600">Rumah Sakit Swasta</span>
            <span style="background:rgba(255,255,255,0.15);padding:4px 12px;border-radius:8px;font-size:11px;font-weight:600">Non-BPJS</span>
            <span style="background:rgba(6,182,212,0.3);border:1px solid rgba(6,182,212,0.5);padding:4px 12px;border-radius:8px;font-size:11px;font-weight:600">🔗 SatuSehat Connected</span>
        </div>
    </div>
</div>

<!-- STATS ROW -->
<div class="grid grid-4" style="margin-bottom:24px">
    <div class="stat-card">
        <div class="stat-icon" style="background:#e0f2fe">👥</div>
        <div>
            <div class="stat-value">{{ number_format($stats['total_pasien']) }}</div>
            <div class="stat-label">Total Pasien</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fef3c7">📋</div>
        <div>
            <div class="stat-value">{{ $stats['total_pendaftaran_hari_ini'] }}</div>
            <div class="stat-label">Pendaftaran Hari Ini</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fee2e2">⏳</div>
        <div>
            <div class="stat-value">{{ $stats['pendaftaran_menunggu'] }}</div>
            <div class="stat-label">Menunggu Dilayani</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#d1fae5">✅</div>
        <div>
            <div class="stat-value">{{ $stats['pendaftaran_selesai_hari_ini'] }}</div>
            <div class="stat-label">Selesai Hari Ini</div>
        </div>
    </div>
</div>

<!-- CHART FILTER -->
<div class="card" style="margin-bottom:16px">
    <div class="card-body" style="padding:14px 20px">
        <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap">
            <span style="font-size:13px;font-weight:700;color:#0c4a6e">
                <i class="fas fa-filter" style="margin-right:6px;color:#0891b2"></i>Filter Chart:
            </span>
            <select id="chartBulan" class="form-select" style="width:140px;padding:7px 10px;font-size:13px" onchange="loadChartData()">
                @foreach(range(1,12) as $b)
                    <option value="{{ $b }}" {{ now()->month == $b ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($b)->locale('id')->monthName }}
                    </option>
                @endforeach
            </select>
            <select id="chartTahun" class="form-select" style="width:100px;padding:7px 10px;font-size:13px" onchange="loadChartData()">
                @foreach(range(date('Y'), date('Y')-4) as $t)
                    <option value="{{ $t }}" {{ date('Y') == $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>
            <span id="chartPeriodeLabel" style="font-size:12px;color:#64748b;font-weight:600">
                {{ now()->locale('id')->isoFormat('MMMM YYYY') }}
            </span>
            <div id="chartLoading" style="display:none;align-items:center;gap:6px;font-size:12px;color:#0891b2">
                <i class="fas fa-spinner fa-spin"></i> Memuat...
            </div>
        </div>
    </div>
</div>

<!-- CHARTS ROW -->
<div class="grid grid-2" style="gap:24px;margin-bottom:24px">
    <!-- LINE CHART: Kunjungan Harian -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">📈 Kunjungan Harian</div>
            <div style="display:flex;align-items:center;gap:10px">
                <div style="display:flex;align-items:center;gap:5px;font-size:11px;color:#0891b2">
                    <div style="width:12px;height:3px;background:#0891b2;border-radius:2px"></div> Total
                </div>
                <div style="display:flex;align-items:center;gap:5px;font-size:11px;color:#059669">
                    <div style="width:12px;height:3px;background:#059669;border-radius:2px;border-top:1px dashed #059669"></div> Selesai
                </div>
            </div>
        </div>
        <div class="card-body" style="padding:16px 20px">
            <div style="position:relative;height:260px">
                <canvas id="lineChart"></canvas>
            </div>
        </div>
    </div>

    <!-- BAR CHART: Kunjungan per Poli -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">📊 Kunjungan per Poli</div>
            <div id="barNoData" style="display:none;font-size:12px;color:#94a3b8">Belum ada data</div>
        </div>
        <div class="card-body" style="padding:16px 20px">
            <div style="position:relative;height:260px">
                <canvas id="barChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- ANTRIAN HARI INI + SIDE PANEL -->
<div class="grid grid-2" style="gap:24px;margin-bottom:20px">
    <!-- ANTRIAN HARI INI -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">📋 Antrian Hari Ini</div>
            <a href="{{ route('admin.pendaftaran.index') }}" class="btn btn-outline btn-sm">Lihat Semua</a>
        </div>
        <div class="card-body" style="padding:0">
            @forelse($pendaftaran_hari_ini as $p)
            <div style="padding:14px 20px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:12px">
                <div style="width:36px;height:36px;background:{{ $p->status === 'menunggu' ? '#fef3c7' : ($p->status === 'dipanggil' ? '#e0f2fe' : '#d1fae5') }};border-radius:10px;display:flex;align-items:center;justify-content:center;font-weight:900;font-size:14px;color:{{ $p->status === 'menunggu' ? '#92400e' : ($p->status === 'dipanggil' ? '#0c4a6e' : '#065f46') }};flex-shrink:0">
                    {{ $p->no_antrian }}
                </div>
                <div style="flex:1;min-width:0">
                    <div style="font-weight:700;font-size:13px;color:#0c4a6e;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                        {{ $p->pasien->nama_lengkap ?? '-' }}
                    </div>
                    <div style="font-size:11px;color:#94a3b8">
                        {{ $p->poli->nama ?? '-' }} · {{ $p->jam_kunjungan }}
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:6px">
                    <span class="badge badge-{{ $p->status_color }}" style="font-size:10px">{{ $p->status_label }}</span>
                    <form action="{{ route('admin.pendaftaran.status', $p->id) }}" method="POST">
                        @csrf @method('PATCH')
                        @if($p->status === 'menunggu')
                            <input type="hidden" name="status" value="dipanggil">
                            <button type="submit" class="btn btn-sm" style="padding:4px 10px;background:#e0f2fe;color:#0c4a6e;border:none;border-radius:6px;font-size:11px;font-weight:600;cursor:pointer">Panggil</button>
                        @elseif($p->status === 'dipanggil')
                            <input type="hidden" name="status" value="selesai">
                            <button type="submit" class="btn btn-sm" style="padding:4px 10px;background:#d1fae5;color:#065f46;border:none;border-radius:6px;font-size:11px;font-weight:600;cursor:pointer">Selesai</button>
                        @endif
                    </form>
                </div>
            </div>
            @empty
            <div style="padding:40px;text-align:center;color:#94a3b8">
                <div style="font-size:36px;margin-bottom:10px">📭</div>
                <div>Belum ada pendaftaran hari ini</div>
            </div>
            @endforelse
        </div>
    </div>

    <!-- RIGHT COLUMN -->
    <div style="display:flex;flex-direction:column;gap:20px">

        <!-- SATUSEHAT STATUS -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">🔗 Status SatuSehat</div>
                <a href="{{ route('admin.satusehat.status') }}" class="btn btn-outline btn-sm">Detail</a>
            </div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px">
                    <div style="text-align:center;padding:14px;background:#d1fae5;border-radius:12px">
                        <div style="font-size:22px;font-weight:900;color:#065f46">{{ $satusehat_status['success'] }}</div>
                        <div style="font-size:10px;color:#059669;font-weight:600;margin-top:4px">✅ Berhasil</div>
                    </div>
                    <div style="text-align:center;padding:14px;background:#fef3c7;border-radius:12px">
                        <div style="font-size:22px;font-weight:900;color:#92400e">{{ $satusehat_status['pending'] }}</div>
                        <div style="font-size:10px;color:#d97706;font-weight:600;margin-top:4px">⏳ Pending</div>
                    </div>
                    <div style="text-align:center;padding:14px;background:#fee2e2;border-radius:12px">
                        <div style="font-size:22px;font-weight:900;color:#991b1b">{{ $satusehat_status['failed'] }}</div>
                        <div style="font-size:10px;color:#dc2626;font-weight:600;margin-top:4px">❌ Gagal</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- QUICK STATS -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">📊 Statistik Bulan Ini</div>
            </div>
            <div class="card-body" style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                <div style="text-align:center;padding:14px;background:#f8fafc;border-radius:12px">
                    <div style="font-size:24px;font-weight:900;color:#0c4a6e">{{ $stats['total_pendaftaran_bulan_ini'] }}</div>
                    <div style="font-size:11px;color:#64748b;margin-top:4px">Total Kunjungan</div>
                </div>
                <div style="text-align:center;padding:14px;background:#f8fafc;border-radius:12px">
                    <div style="font-size:24px;font-weight:900;color:#0c4a6e">{{ $stats['total_dokter'] }}</div>
                    <div style="font-size:11px;color:#64748b;margin-top:4px">Dokter Aktif</div>
                </div>
            </div>
        </div>

        <!-- QUICK LINKS -->
        <div class="card">
            <div class="card-header"><div class="card-title">⚡ Aksi Cepat</div></div>
            <div class="card-body" style="padding:14px;display:grid;grid-template-columns:1fr 1fr;gap:8px">
                <a href="{{ route('admin.pendaftaran.index') }}" class="btn btn-outline" style="justify-content:center;font-size:12px">
                    <i class="fas fa-list"></i> Semua Pendaftaran
                </a>
                <a href="{{ route('admin.pasien.index') }}" class="btn btn-outline" style="justify-content:center;font-size:12px">
                    <i class="fas fa-users"></i> Data Pasien
                </a>
                <a href="{{ route('admin.dokter.create') }}" class="btn btn-outline" style="justify-content:center;font-size:12px">
                    <i class="fas fa-user-md"></i> Tambah Dokter
                </a>
                <a href="{{ route('admin.laporan') }}" class="btn btn-outline" style="justify-content:center;font-size:12px">
                    <i class="fas fa-chart-bar"></i> Laporan
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const BAR_COLORS = [
    'rgba(8,145,178,0.75)','rgba(5,150,105,0.75)','rgba(220,38,38,0.75)',
    'rgba(217,119,6,0.75)','rgba(124,58,237,0.75)','rgba(219,39,119,0.75)',
    'rgba(2,132,199,0.75)','rgba(225,29,72,0.75)',
];

const chartOpts = (type) => ({
    responsive: true,
    maintainAspectRatio: false,
    interaction: { mode: 'index', intersect: false },
    plugins: {
        legend: {
            display: type === 'line',
            labels: { font: { size: 12, family: 'Plus Jakarta Sans' }, boxWidth: 16, padding: 16 }
        },
        tooltip: { bodyFont: { size: 12 }, titleFont: { size: 12, weight: '700' }, padding: 10 }
    },
    scales: {
        x: {
            ticks: { font: { size: 11 }, maxRotation: type === 'bar' ? 30 : 0 },
            grid: { color: 'rgba(0,0,0,0.04)' }
        },
        y: {
            beginAtZero: true,
            ticks: {
                stepSize: 1,
                precision: 0,
                font: { size: 11 },
                callback: (v) => Number.isInteger(v) ? v : null
            },
            grid: { color: 'rgba(0,0,0,0.05)' }
        }
    }
});

// ── Inisialisasi chart kosong ─────────────────────────────
const lineChart = new Chart(document.getElementById('lineChart').getContext('2d'), {
    type: 'line',
    data: {
        labels: [],
        datasets: [
            {
                label: 'Total Kunjungan',
                data: [],
                borderColor: '#0891b2',
                backgroundColor: 'rgba(8,145,178,0.08)',
                borderWidth: 2.5,
                pointBackgroundColor: '#0891b2',
                pointRadius: 4,
                pointHoverRadius: 7,
                fill: true,
                tension: 0.3,
            },
            {
                label: 'Selesai',
                data: [],
                borderColor: '#059669',
                backgroundColor: 'rgba(5,150,105,0.06)',
                borderWidth: 2,
                borderDash: [5, 3],
                pointBackgroundColor: '#059669',
                pointRadius: 3,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.3,
            }
        ]
    },
    options: chartOpts('line')
});

const barChart = new Chart(document.getElementById('barChart').getContext('2d'), {
    type: 'bar',
    data: { labels: [], datasets: [{ label: 'Kunjungan', data: [], backgroundColor: [], borderRadius: 7, borderSkipped: false }] },
    options: chartOpts('bar')
});

// ── Load data via AJAX ────────────────────────────────────
function loadChartData() {
    const bulan  = document.getElementById('chartBulan').value;
    const tahun  = document.getElementById('chartTahun').value;
    const loader = document.getElementById('chartLoading');
    loader.style.display = 'flex';

    fetch(`{{ route('admin.api.chart') }}?bulan=${bulan}&tahun=${tahun}`)
        .then(r => r.json())
        .then(d => {
            // Update label periode
            document.getElementById('chartPeriodeLabel').textContent = d.namaBulan;

            // Update line chart
            lineChart.data.labels            = d.line.labels;
            lineChart.data.datasets[0].data  = d.line.total;
            lineChart.data.datasets[1].data  = d.line.selesai;
            lineChart.update('active');

            // Update bar chart
            const hasData = d.bar.data.some(v => v > 0);
            document.getElementById('barNoData').style.display = hasData ? 'none' : 'block';
            barChart.data.labels                        = d.bar.labels;
            barChart.data.datasets[0].data              = d.bar.data;
            barChart.data.datasets[0].backgroundColor   = BAR_COLORS.slice(0, d.bar.labels.length);
            barChart.update('active');
        })
        .catch(e => console.error('Chart load error', e))
        .finally(() => { loader.style.display = 'none'; });
}

// ── Load otomatis saat halaman pertama kali dibuka ────────
loadChartData();
</script>
@endpush
