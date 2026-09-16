@extends('layouts.app')
@section('title', 'Dashboard Pendaftaran - RS Cahya Medika')
@section('page-title', 'Dashboard Pendaftaran &amp; Loket')

@section('content')

{{-- HERO BANNER --}}
<div style="background:linear-gradient(135deg,#1d4ed8 0%,#0891b2 100%);border-radius:14px;padding:22px 26px;color:white;margin-bottom:22px;display:flex;align-items:center;justify-content:space-between;gap:20px">
    <div style="display:flex;align-items:center;gap:18px">
        <div style="width:52px;height:52px;background:rgba(255,255,255,0.15);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0">📋</div>
        <div>
            <div style="font-size:17px;font-weight:800;margin-bottom:3px">Panel Pendaftaran &amp; Antrean Loket</div>
            <div style="font-size:12px;opacity:0.75">RS Cahya Medika Bondowoso &middot; {{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</div>
            <div style="display:flex;gap:8px;margin-top:10px">
                <span style="background:rgba(255,255,255,0.15);padding:3px 11px;border-radius:6px;font-size:11px;font-weight:600"><i class="fas fa-user-shield" style="margin-right:5px"></i>Otoritas Super Admin Pendaftaran</span>
                <span style="background:rgba(255,255,255,0.15);padding:3px 11px;border-radius:6px;font-size:11px;font-weight:600"><i class="fas fa-desktop" style="margin-right:5px"></i>Loket Front Office</span>
            </div>
        </div>
    </div>
    <div style="text-align:right;flex-shrink:0">
        <div style="font-size:28px;font-weight:900;line-height:1">{{ now()->format('H:i') }}</div>
        <div style="font-size:11px;opacity:0.65;margin-top:3px">Waktu Lokal</div>
    </div>
</div>

{{-- STATS ROW --}}
<div class="grid grid-4" style="margin-bottom:22px">
    <div class="stat-card">
        <div class="stat-icon" style="background:#e0f2fe"><i class="fas fa-users" style="color:#0369a1"></i></div>
        <div>
            <div class="stat-value">{{ number_format($stats['total_pasien']) }}</div>
            <div class="stat-label">Total Pasien Terdaftar</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fef3c7"><i class="fas fa-clipboard-list" style="color:#d97706"></i></div>
        <div>
            <div class="stat-value">{{ $stats['total_pendaftaran_hari_ini'] }}</div>
            <div class="stat-label">Pendaftaran Hari Ini</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fce7f3"><i class="fas fa-user-clock" style="color:#be185d"></i></div>
        <div>
            <div class="stat-value">{{ $stats['pendaftaran_menunggu'] }}</div>
            <div class="stat-label">Menunggu Antrean</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#dcfce7"><i class="fas fa-circle-check" style="color:#16a34a"></i></div>
        <div>
            <div class="stat-value">{{ $stats['pendaftaran_selesai_hari_ini'] }}</div>
            <div class="stat-label">Pemeriksaan Selesai</div>
        </div>
    </div>
</div>

{{-- CHART FILTER --}}
<div class="card" style="margin-bottom:16px">
    <div class="card-body" style="padding:12px 18px">
        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
            <span style="font-size:12px;font-weight:700;color:#374151"><i class="fas fa-filter" style="margin-right:5px;color:#6b7280"></i>Filter Chart:</span>
            <select id="chartBulan" class="form-select" style="width:130px;padding:6px 10px;font-size:12px" onchange="loadChartData()">
                @foreach(range(1,12) as $b)
                    <option value="{{ $b }}" {{ now()->month == $b ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($b)->locale('id')->monthName }}
                    </option>
                @endforeach
            </select>
            <select id="chartTahun" class="form-select" style="width:90px;padding:6px 10px;font-size:12px" onchange="loadChartData()">
                @foreach(range(date('Y'), date('Y')-4) as $t)
                    <option value="{{ $t }}" {{ date('Y') == $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>
            <span id="chartPeriodeLabel" style="font-size:12px;color:#6b7280;font-weight:600">{{ now()->locale('id')->isoFormat('MMMM YYYY') }}</span>
            <div id="chartLoading" style="display:none;align-items:center;gap:5px;font-size:12px;color:#0891b2">
                <i class="fas fa-spinner fa-spin"></i> Memuat...
            </div>
        </div>
    </div>
</div>

{{-- CHARTS --}}
<div class="grid grid-2" style="gap:16px;margin-bottom:20px">
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-chart-line" style="color:#0891b2"></i> Kunjungan Harian</div>
            <div style="display:flex;gap:12px">
                <span style="display:flex;align-items:center;gap:4px;font-size:11px;color:#0891b2"><span style="width:12px;height:2px;background:#0891b2;display:inline-block;border-radius:2px"></span> Total</span>
                <span style="display:flex;align-items:center;gap:4px;font-size:11px;color:#16a34a"><span style="width:12px;height:2px;background:#16a34a;display:inline-block;border-radius:2px;border-top:2px dashed #16a34a"></span> Selesai</span>
            </div>
        </div>
        <div class="card-body" style="padding:14px 18px">
            <div style="position:relative;height:220px"><canvas id="lineChart"></canvas></div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-chart-bar" style="color:#1d4ed8"></i> Kunjungan per Poliklinik</div>
            <div id="barNoData" style="display:none;font-size:12px;color:#9ca3af">Belum ada data</div>
        </div>
        <div class="card-body" style="padding:14px 18px">
            <div style="position:relative;height:220px"><canvas id="barChart"></canvas></div>
        </div>
    </div>
</div>

{{-- ANTREAN + STATUS PANEL --}}
<div class="grid grid-2" style="gap:16px;margin-bottom:20px">
    {{-- ANTREAN HARI INI --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-list-ol" style="color:#1d4ed8"></i> Antrean Pendaftaran Hari Ini</div>
            <a href="{{ route('admin.pendaftaran.index') }}" class="btn btn-outline btn-sm">Lihat Semua</a>
        </div>
        <div style="overflow:hidden">
            {{-- Table header --}}
            <div style="display:grid;grid-template-columns:50px 1fr 1fr 90px 80px;padding:9px 16px;background:#f9fafb;border-bottom:1px solid #f3f4f6;font-size:10px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.5px">
                <div>No</div><div>Pasien</div><div>Poli / Tujuan</div><div>Status</div><div>Aksi</div>
            </div>
            @forelse($pendaftaran_hari_ini as $p)
            <div style="display:grid;grid-template-columns:50px 1fr 1fr 90px 80px;padding:11px 16px;border-bottom:1px solid #f9fafb;align-items:center;font-size:13px">
                <div>
                    <span style="display:inline-flex;width:28px;height:28px;background:#1e293b;color:white;border-radius:7px;align-items:center;justify-content:center;font-weight:700;font-size:12px">#{{ $p->no_antrian }}</span>
                </div>
                <div>
                    <div style="font-weight:600;color:#111827;font-size:13px">{{ $p->pasien->nama_lengkap ?? '-' }}</div>
                    <div style="font-size:11px;color:#9ca3af">No. RM {{ $p->pasien->no_rm ?? '-' }}</div>
                </div>
                <div>
                    <div style="font-size:12px;color:#374151;font-weight:500">{{ $p->poli->nama ?? '-' }}</div>
                    <div style="font-size:11px;color:#9ca3af">{{ $p->jam_kunjungan }}</div>
                </div>
                <div>
                    <span class="badge badge-{{ $p->status === 'selesai' ? 'success' : ($p->status === 'dipanggil' ? 'info' : 'warning') }}">
                        {{ ucfirst($p->status) }}
                    </span>
                </div>
                <div>
                    <form action="{{ route('admin.pendaftaran.status', $p->id) }}" method="POST">
                        @csrf @method('PATCH')
                        @if($p->status === 'menunggu')
                            <input type="hidden" name="status" value="dipanggil">
                            <button type="submit" class="btn btn-sm" style="background:#dbeafe;color:#1e40af;border:none;cursor:pointer;font-family:inherit">Panggil</button>
                        @elseif($p->status === 'dipanggil')
                            <input type="hidden" name="status" value="selesai">
                            <button type="submit" class="btn btn-sm" style="background:#d1fae5;color:#065f46;border:none;cursor:pointer;font-family:inherit">Selesai</button>
                        @else
                            <span style="font-size:11px;color:#9ca3af">—</span>
                        @endif
                    </form>
                </div>
            </div>
            @empty
            <div style="padding:36px;text-align:center;color:#9ca3af">
                <i class="fas fa-inbox" style="font-size:28px;margin-bottom:8px;display:block;color:#d1d5db"></i>
                <div style="font-size:13px">Belum ada pendaftaran hari ini</div>
            </div>
            @endforelse
        </div>
    </div>

    {{-- STATUS PELAYANAN LOKET --}}
    <div style="display:flex;flex-direction:column;gap:14px">
        {{-- SatuSehat --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fas fa-link" style="color:#0891b2"></i> Status SatuSehat</div>
                <a href="{{ route('admin.satusehat.status') }}" class="btn btn-outline btn-sm">Detail</a>
            </div>
            <div class="card-body" style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px">
                <div style="text-align:center;padding:12px;background:#f0fdf4;border-radius:10px">
                    <div style="font-size:20px;font-weight:800;color:#166534">{{ $satusehat_status['success'] }}</div>
                    <div style="font-size:10px;color:#16a34a;font-weight:600;margin-top:3px">✅ Berhasil</div>
                </div>
                <div style="text-align:center;padding:12px;background:#fffbeb;border-radius:10px">
                    <div style="font-size:20px;font-weight:800;color:#92400e">{{ $satusehat_status['pending'] }}</div>
                    <div style="font-size:10px;color:#d97706;font-weight:600;margin-top:3px">⏳ Pending</div>
                </div>
                <div style="text-align:center;padding:12px;background:#fef2f2;border-radius:10px">
                    <div style="font-size:20px;font-weight:800;color:#991b1b">{{ $satusehat_status['failed'] }}</div>
                    <div style="font-size:10px;color:#dc2626;font-weight:600;margin-top:3px">❌ Gagal</div>
                </div>
            </div>
        </div>

        {{-- Status Pelayanan Loket per Poli --}}
        <div class="card" style="flex:1">
            <div class="card-header">
                <div class="card-title"><i class="fas fa-hospital" style="color:#16a34a"></i> Status Pelayanan Loket</div>
            </div>
            @php
                $poliAll = \App\Models\Poli::where('is_active', true)->withCount(['pendaftaran' => function($q){ $q->whereDate('tanggal_kunjungan', today()); }])->get();
            @endphp
            <div>
                @forelse($poliAll as $pl)
                <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 18px;border-bottom:1px solid #f9fafb;font-size:13px">
                    <span style="color:#374151;font-weight:500">{{ $pl->nama }}</span>
                    <span style="background:#dcfce7;color:#166534;padding:3px 10px;border-radius:6px;font-size:11px;font-weight:600">
                        Buka (07:30 – 14:00)
                    </span>
                </div>
                @empty
                <div style="padding:20px;text-align:center;color:#9ca3af;font-size:13px">Tidak ada poli aktif</div>
                @endforelse
            </div>
        </div>

        {{-- Statistik bulan ini --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fas fa-chart-pie" style="color:#7c3aed"></i> Statistik Bulan Ini</div>
            </div>
            <div class="card-body" style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                <div style="text-align:center;padding:12px;background:#f9fafb;border-radius:10px">
                    <div style="font-size:20px;font-weight:800;color:#1e293b">{{ $stats['total_pendaftaran_bulan_ini'] }}</div>
                    <div style="font-size:11px;color:#6b7280;margin-top:3px">Total Kunjungan</div>
                </div>
                <div style="text-align:center;padding:12px;background:#f9fafb;border-radius:10px">
                    <div style="font-size:20px;font-weight:800;color:#1e293b">{{ $stats['total_dokter'] }}</div>
                    <div style="font-size:11px;color:#6b7280;margin-top:3px">Dokter Aktif</div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const BAR_COLORS = ['rgba(29,78,216,0.7)','rgba(8,145,178,0.7)','rgba(22,163,74,0.7)','rgba(217,119,6,0.7)','rgba(124,58,237,0.7)','rgba(219,39,119,0.7)','rgba(2,132,199,0.7)','rgba(225,29,72,0.7)'];
const chartOpts = (type) => ({ responsive:true, maintainAspectRatio:false, interaction:{mode:'index',intersect:false}, plugins:{ legend:{display:type==='line',labels:{font:{size:11,family:'Inter'},boxWidth:14,padding:12}}, tooltip:{bodyFont:{size:12},titleFont:{size:12,weight:'700'},padding:10} }, scales:{ x:{ticks:{font:{size:11},maxRotation:type==='bar'?30:0},grid:{color:'rgba(0,0,0,0.04)'}}, y:{beginAtZero:true,ticks:{stepSize:1,precision:0,font:{size:11},callback:v=>Number.isInteger(v)?v:null},grid:{color:'rgba(0,0,0,0.05)'}} } });
const lineChart = new Chart(document.getElementById('lineChart').getContext('2d'), { type:'line', data:{ labels:[], datasets:[{ label:'Total Kunjungan', data:[], borderColor:'#0891b2', backgroundColor:'rgba(8,145,178,0.07)', borderWidth:2.5, pointBackgroundColor:'#0891b2', pointRadius:3, fill:true, tension:0.35 },{ label:'Selesai', data:[], borderColor:'#16a34a', backgroundColor:'rgba(22,163,74,0.05)', borderWidth:2, borderDash:[5,3], pointBackgroundColor:'#16a34a', pointRadius:3, fill:true, tension:0.35 }] }, options:chartOpts('line') });
const barChart = new Chart(document.getElementById('barChart').getContext('2d'), { type:'bar', data:{ labels:[], datasets:[{ label:'Kunjungan', data:[], backgroundColor:[], borderRadius:6, borderSkipped:false }] }, options:chartOpts('bar') });
function loadChartData() {
    const bulan = document.getElementById('chartBulan').value;
    const tahun = document.getElementById('chartTahun').value;
    document.getElementById('chartLoading').style.display = 'flex';
    fetch(`{{ route('admin.api.chart') }}?bulan=${bulan}&tahun=${tahun}`)
        .then(r=>r.json()).then(d=>{
            document.getElementById('chartPeriodeLabel').textContent = d.namaBulan;
            lineChart.data.labels = d.line.labels; lineChart.data.datasets[0].data = d.line.total; lineChart.data.datasets[1].data = d.line.selesai; lineChart.update('active');
            const hasData = d.bar.data.some(v=>v>0);
            document.getElementById('barNoData').style.display = hasData?'none':'block';
            barChart.data.labels = d.bar.labels; barChart.data.datasets[0].data = d.bar.data; barChart.data.datasets[0].backgroundColor = BAR_COLORS.slice(0,d.bar.labels.length); barChart.update('active');
        }).catch(e=>console.error(e)).finally(()=>{document.getElementById('chartLoading').style.display='none';});
}
loadChartData();
</script>
@endpush
