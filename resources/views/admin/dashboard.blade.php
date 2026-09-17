@php
    $isRM = request()->is('rekam-medis*');
@endphp
@extends('layouts.app')
@section('title', $isRM ? 'Dashboard Rekam Medis - RS Cahya Medika' : 'Dashboard Pendaftaran - RS Cahya Medika')
@section('page-title', $isRM ? 'Dashboard Rekam Medis' : 'Dashboard Pendaftaran & Loket')

@section('content')

{{-- CLEAN HEADER BANNER --}}
<div style="background:white;border:1px solid #e2e8f0;border-radius:14px;padding:20px 24px;margin-bottom:22px;display:flex;align-items:center;justify-content:space-between;gap:20px;box-shadow:0 1px 3px rgba(0,0,0,0.03)">
    <div style="display:flex;align-items:center;gap:16px">
        <div style="width:48px;height:48px;background:#f8fafc;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;color:#0f172a;flex-shrink:0;border:1px solid #e2e8f0">
            <i class="fas fa-{{ $isRM ? 'file-medical' : 'clipboard-list' }}"></i>
        </div>
        <div>
            <div style="font-size:18px;font-weight:700;color:#0f172a;margin-bottom:2px">{{ $isRM ? 'Panel Rekam Medis & Riwayat Pasien' : 'Panel Pendaftaran & Antrean Loket' }}</div>
            <div style="font-size:12px;color:#64748b">RS Cahya Medika Bondowoso &middot; {{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</div>
            <div style="display:flex;gap:8px;margin-top:8px">
                @if($isRM)
                <span style="background:#f8fafc;color:#334155;padding:3px 10px;border-radius:6px;font-size:11px;font-weight:600;border:1px solid #e2e8f0"><i class="fas fa-user-shield" style="margin-right:5px;color:#0284c7"></i>Staf Rekam Medis</span>
                <span style="background:#f8fafc;color:#334155;padding:3px 10px;border-radius:6px;font-size:11px;font-weight:600;border:1px solid #e2e8f0"><i class="fas fa-database" style="margin-right:5px;color:#059669"></i>Unit Rekam Medis</span>
                @else
                <span style="background:#f8fafc;color:#334155;padding:3px 10px;border-radius:6px;font-size:11px;font-weight:600;border:1px solid #e2e8f0"><i class="fas fa-user-shield" style="margin-right:5px;color:#2563eb"></i>Super Admin Pendaftaran</span>
                <span style="background:#f8fafc;color:#334155;padding:3px 10px;border-radius:6px;font-size:11px;font-weight:600;border:1px solid #e2e8f0"><i class="fas fa-desktop" style="margin-right:5px;color:#0d9488"></i>Loket Front Office</span>
                @endif
            </div>
        </div>
    </div>
    <div style="text-align:right;flex-shrink:0;background:#f8fafc;padding:10px 18px;border-radius:10px;border:1px solid #e2e8f0">
        <div style="font-size:24px;font-weight:800;color:#0f172a;line-height:1;font-family:monospace">{{ now()->format('H:i') }}</div>
        <div style="font-size:11px;color:#64748b;margin-top:3px;font-weight:500">{{ $isRM ? 'Waktu Rekam Medis' : 'Waktu Loket' }}</div>
    </div>
</div>

{{-- QUICK LOOKUP & CHECK-IN CARD FOR LOKET --}}
@if(!$isRM)
<div class="card" style="margin-bottom:22px;border:1.5px solid #0284c7;background:#f0f9ff;border-radius:14px;box-shadow:0 4px 14px rgba(2,132,199,0.08)">
    <div class="card-body" style="padding:18px 22px">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap">
            <div style="display:flex;align-items:center;gap:12px">
                <div style="width:42px;height:42px;background:#0284c7;color:white;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px">
                    <i class="fas fa-qrcode"></i>
                </div>
                <div>
                    <div style="font-size:14px;font-weight:800;color:#0f172a">Ambil No. Antrean & Check-in Loket Pasien</div>
                    <div style="font-size:11.5px;color:#475569">Scan QR Barcode Tiket atau ketik Kode Booking / Nama / NIK Pasien.</div>
                </div>
            </div>
            <div style="display:flex;gap:8px;flex:1;max-width:440px">
                <input type="text" id="lookupInputCode" class="form-control" placeholder="Contoh: CM-20260917-0012 / Nama / NIK" style="border:1.5px solid #0284c7;font-family:monospace;font-size:13px" onkeypress="if(event.key==='Enter') executeBookingLookup()">
                <button type="button" class="btn" style="background:#0284c7;color:white;font-weight:700;padding:8px 16px;white-space:nowrap" onclick="executeBookingLookup()">
                    <i class="fas fa-search"></i> Cari / Check-in
                </button>
            </div>
        </div>
        <div id="lookupResultArea" style="display:none;margin-top:16px;padding:14px;background:white;border:1px solid #bae6fd;border-radius:10px"></div>
    </div>
</div>
@endif

{{-- STATS ROW --}}
<div class="grid grid-4" style="margin-bottom:22px">
    <div class="stat-card" style="border:1px solid #e2e8f0;box-shadow:0 1px 3px rgba(0,0,0,0.03);border-radius:12px">
        <div class="stat-icon" style="background:#f1f5f9;color:#334155;border:1px solid #e2e8f0"><i class="fas fa-users"></i></div>
        <div>
            <div class="stat-value" style="color:#0f172a;font-size:22px">{{ number_format($stats['total_pasien']) }}</div>
            <div class="stat-label">Total Pasien Terdaftar</div>
        </div>
    </div>
    <div class="stat-card" style="border:1px solid #e2e8f0;box-shadow:0 1px 3px rgba(0,0,0,0.03);border-radius:12px">
        <div class="stat-icon" style="background:#eff6ff;color:#2563eb;border:1px solid #dbeafe"><i class="fas fa-clipboard-user"></i></div>
        <div>
            <div class="stat-value" style="color:#0f172a;font-size:22px">{{ $stats['total_pendaftaran_hari_ini'] }}</div>
            <div class="stat-label">Pendaftaran Hari Ini</div>
        </div>
    </div>
    <div class="stat-card" style="border:1px solid #e2e8f0;box-shadow:0 1px 3px rgba(0,0,0,0.03);border-radius:12px">
        <div class="stat-icon" style="background:#fffbeb;color:#d97706;border:1px solid #fef3c7"><i class="fas fa-hourglass-half"></i></div>
        <div>
            <div class="stat-value" style="color:#0f172a;font-size:22px">{{ $stats['pendaftaran_menunggu'] }}</div>
            <div class="stat-label">Menunggu Antrean</div>
        </div>
    </div>
    <div class="stat-card" style="border:1px solid #e2e8f0;box-shadow:0 1px 3px rgba(0,0,0,0.03);border-radius:12px">
        <div class="stat-icon" style="background:#f0fdf4;color:#16a34a;border:1px solid #dcfce7"><i class="fas fa-circle-check"></i></div>
        <div>
            <div class="stat-value" style="color:#0f172a;font-size:22px">{{ $stats['pendaftaran_selesai_hari_ini'] }}</div>
            <div class="stat-label">Pemeriksaan Selesai</div>
        </div>
    </div>
</div>

{{-- CHART FILTER --}}
<div class="card" style="margin-bottom:16px;border:1px solid #e2e8f0;box-shadow:0 1px 3px rgba(0,0,0,0.03);border-radius:12px">
    <div class="card-body" style="padding:12px 18px">
        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
            <span style="font-size:12px;font-weight:700;color:#334155"><i class="fas fa-filter" style="margin-right:5px;color:#64748b"></i>Filter Chart:</span>
            <select id="chartBulan" class="form-select" style="width:130px;padding:6px 10px;font-size:12px;border:1px solid #cbd5e1" onchange="loadChartData()">
                @foreach(range(1,12) as $b)
                    <option value="{{ $b }}" {{ now()->month == $b ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($b)->locale('id')->monthName }}
                    </option>
                @endforeach
            </select>
            <select id="chartTahun" class="form-select" style="width:90px;padding:6px 10px;font-size:12px;border:1px solid #cbd5e1" onchange="loadChartData()">
                @foreach(range(date('Y'), date('Y')-4) as $t)
                    <option value="{{ $t }}" {{ date('Y') == $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>
            <span id="chartPeriodeLabel" style="font-size:12px;color:#64748b;font-weight:600">{{ now()->locale('id')->isoFormat('MMMM YYYY') }}</span>
            <div id="chartLoading" style="display:none;align-items:center;gap:5px;font-size:12px;color:#0284c7">
                <i class="fas fa-spinner fa-spin"></i> Memuat...
            </div>
        </div>
    </div>
</div>

{{-- CHARTS --}}
<div class="grid grid-2" style="gap:16px;margin-bottom:20px">
    <div class="card" style="border:1px solid #e2e8f0;box-shadow:0 1px 3px rgba(0,0,0,0.03);border-radius:12px">
        <div class="card-header" style="border-bottom:1px solid #f1f5f9;background:#ffffff">
            <div class="card-title" style="font-size:13.5px;color:#0f172a"><i class="fas fa-chart-line" style="color:#0284c7;margin-right:6px"></i> Kunjungan Harian</div>
            <div style="display:flex;gap:12px">
                <span style="display:flex;align-items:center;gap:4px;font-size:11px;color:#0284c7"><span style="width:10px;height:2px;background:#0284c7;display:inline-block;border-radius:2px"></span> Total</span>
                <span style="display:flex;align-items:center;gap:4px;font-size:11px;color:#16a34a"><span style="width:10px;height:2px;background:#16a34a;display:inline-block;border-radius:2px"></span> Selesai</span>
            </div>
        </div>
        <div class="card-body" style="padding:14px 18px">
            <div style="position:relative;height:220px"><canvas id="lineChart"></canvas></div>
        </div>
    </div>
    <div class="card" style="border:1px solid #e2e8f0;box-shadow:0 1px 3px rgba(0,0,0,0.03);border-radius:12px">
        <div class="card-header" style="border-bottom:1px solid #f1f5f9;background:#ffffff">
            <div class="card-title" style="font-size:13.5px;color:#0f172a"><i class="fas fa-chart-bar" style="color:#2563eb;margin-right:6px"></i> Kunjungan per Poliklinik</div>
            <div id="barNoData" style="display:none;font-size:12px;color:#94a3b8">Belum ada data</div>
        </div>
        <div class="card-body" style="padding:14px 18px">
            <div style="position:relative;height:220px"><canvas id="barChart"></canvas></div>
        </div>
    </div>
</div>

{{-- ANTREAN + STATUS PANEL --}}
<div class="grid grid-2" style="gap:16px;margin-bottom:20px">
    {{-- ANTREAN HARI INI --}}
    <div class="card" style="border:1px solid #e2e8f0;box-shadow:0 1px 3px rgba(0,0,0,0.03);border-radius:12px">
        <div class="card-header" style="border-bottom:1px solid #f1f5f9;background:#ffffff">
            <div class="card-title" style="font-size:13.5px;color:#0f172a"><i class="fas fa-list-ol" style="color:#2563eb;margin-right:6px"></i> Antrean Pendaftaran Hari Ini</div>
            <a href="{{ route('admin.pendaftaran.index') }}" class="btn btn-outline btn-sm" style="border-color:#cbd5e1;color:#475569">Lihat Semua</a>
        </div>
        <div style="overflow:hidden">
            {{-- Table header --}}
            <div style="display:grid;grid-template-columns:50px 1fr 1fr 90px 80px;padding:9px 16px;background:#f8fafc;border-bottom:1px solid #e2e8f0;font-size:10px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.5px">
                <div>No</div><div>Pasien</div><div>Poli / Tujuan</div><div>Status</div><div>Aksi</div>
            </div>
            @forelse($pendaftaran_hari_ini as $p)
            <div style="display:grid;grid-template-columns:50px 1fr 1fr 90px 80px;padding:11px 16px;border-bottom:1px solid #f1f5f9;align-items:center;font-size:13px">
                <div>
                    <span style="display:inline-flex;width:28px;height:28px;background:#0f172a;color:white;border-radius:7px;align-items:center;justify-content:center;font-weight:700;font-size:12px">#{{ $p->no_antrian }}</span>
                </div>
                <div>
                    <div style="font-weight:600;color:#0f172a;font-size:13px">{{ $p->pasien->nama_lengkap ?? '-' }}</div>
                    <div style="font-size:11px;color:#64748b">No. RM {{ $p->pasien->no_rm ?? '-' }}</div>
                </div>
                <div>
                    <div style="font-size:12px;color:#334155;font-weight:500">{{ $p->poli->nama ?? '-' }}</div>
                    <div style="font-size:11px;color:#64748b">{{ $p->jam_kunjungan }}</div>
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
                            <button type="submit" class="btn btn-sm" style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;cursor:pointer;font-family:inherit;font-weight:600">Panggil</button>
                        @elseif($p->status === 'dipanggil')
                            <input type="hidden" name="status" value="selesai">
                            <button type="submit" class="btn btn-sm" style="background:#f0fdf4;color:#15803d;border:1px solid #bbf7d0;cursor:pointer;font-family:inherit;font-weight:600">Selesai</button>
                        @else
                            <span style="font-size:11px;color:#94a3b8">—</span>
                        @endif
                    </form>
                </div>
            </div>
            @empty
            <div style="padding:36px;text-align:center;color:#94a3b8">
                <i class="fas fa-inbox" style="font-size:28px;margin-bottom:8px;display:block;color:#cbd5e1"></i>
                <div style="font-size:13px">Belum ada pendaftaran hari ini</div>
            </div>
            @endforelse
        </div>
    </div>

    {{-- STATUS PELAYANAN LOKET --}}
    <div style="display:flex;flex-direction:column;gap:14px">
        {{-- SatuSehat --}}
        <div class="card" style="border:1px solid #e2e8f0;box-shadow:0 1px 3px rgba(0,0,0,0.03);border-radius:12px">
            <div class="card-header" style="border-bottom:1px solid #f1f5f9;background:#ffffff">
                <div class="card-title" style="font-size:13.5px;color:#0f172a"><i class="fas fa-link" style="color:#0284c7;margin-right:6px"></i> Status SatuSehat</div>
                <a href="{{ route('admin.satusehat.status') }}" class="btn btn-outline btn-sm" style="border-color:#cbd5e1;color:#475569">Detail</a>
            </div>
            <div class="card-body" style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px">
                <div style="text-align:center;padding:12px;background:#f0fdf4;border-radius:10px;border:1px solid #dcfce7">
                    <div style="font-size:20px;font-weight:800;color:#166534">{{ $satusehat_status['success'] }}</div>
                    <div style="font-size:10.5px;color:#15803d;font-weight:600;margin-top:3px;display:flex;align-items:center;justify-content:center;gap:3px">
                        <i class="fas fa-circle-check"></i> Berhasil
                    </div>
                </div>
                <div style="text-align:center;padding:12px;background:#fffbeb;border-radius:10px;border:1px solid #fef3c7">
                    <div style="font-size:20px;font-weight:800;color:#92400e">{{ $satusehat_status['pending'] }}</div>
                    <div style="font-size:10.5px;color:#b45309;font-weight:600;margin-top:3px;display:flex;align-items:center;justify-content:center;gap:3px">
                        <i class="fas fa-clock"></i> Pending
                    </div>
                </div>
                <div style="text-align:center;padding:12px;background:#fef2f2;border-radius:10px;border:1px solid #fee2e2">
                    <div style="font-size:20px;font-weight:800;color:#991b1b">{{ $satusehat_status['failed'] }}</div>
                    <div style="font-size:10.5px;color:#b91c1c;font-weight:600;margin-top:3px;display:flex;align-items:center;justify-content:center;gap:3px">
                        <i class="fas fa-circle-xmark"></i> Gagal
                    </div>
                </div>
            </div>
        </div>

        {{-- Status Pelayanan Loket per Poli --}}
        <div class="card" style="flex:1;border:1px solid #e2e8f0;box-shadow:0 1px 3px rgba(0,0,0,0.03);border-radius:12px">
            <div class="card-header" style="border-bottom:1px solid #f1f5f9;background:#ffffff">
                <div class="card-title" style="font-size:13.5px;color:#0f172a"><i class="fas fa-hospital" style="color:#16a34a;margin-right:6px"></i> Status Pelayanan Loket</div>
            </div>
            @php
                $poliAll = \App\Models\Poli::where('is_active', true)->withCount(['pendaftaran' => function($q){ $q->whereDate('tanggal_kunjungan', today()); }])->get();
            @endphp
            <div>
                @forelse($poliAll as $pl)
                <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 18px;border-bottom:1px solid #f8fafc;font-size:13px">
                    <span style="color:#334155;font-weight:500">{{ $pl->nama }}</span>
                    <span style="background:#f0fdf4;color:#166534;padding:3px 10px;border-radius:6px;font-size:11px;font-weight:600;border:1px solid #dcfce7">
                        <i class="fas fa-door-open" style="margin-right:4px"></i>Buka (07:30 – 14:00)
                    </span>
                </div>
                @empty
                <div style="padding:20px;text-align:center;color:#94a3b8;font-size:13px">Tidak ada poli aktif</div>
                @endforelse
            </div>
        </div>

        {{-- Statistik bulan ini --}}
        <div class="card" style="border:1px solid #e2e8f0;box-shadow:0 1px 3px rgba(0,0,0,0.03);border-radius:12px">
            <div class="card-header" style="border-bottom:1px solid #f1f5f9;background:#ffffff">
                <div class="card-title" style="font-size:13.5px;color:#0f172a"><i class="fas fa-chart-pie" style="color:#7c3aed;margin-right:6px"></i> Statistik Bulan Ini</div>
            </div>
            <div class="card-body" style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                <div style="text-align:center;padding:12px;background:#f8fafc;border-radius:10px;border:1px solid #e2e8f0">
                    <div style="font-size:20px;font-weight:800;color:#0f172a">{{ $stats['total_pendaftaran_bulan_ini'] }}</div>
                    <div style="font-size:11px;color:#64748b;margin-top:3px">Total Kunjungan</div>
                </div>
                <div style="text-align:center;padding:12px;background:#f8fafc;border-radius:10px;border:1px solid #e2e8f0">
                    <div style="font-size:20px;font-weight:800;color:#0f172a">{{ $stats['total_dokter'] }}</div>
                    <div style="font-size:11px;color:#64748b;margin-top:3px">Dokter Aktif</div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const BAR_COLORS = ['rgba(37,99,235,0.75)','rgba(2,132,199,0.75)','rgba(22,163,74,0.75)','rgba(217,119,6,0.75)','rgba(124,58,237,0.75)','rgba(219,39,119,0.75)','rgba(14,165,233,0.75)','rgba(225,29,72,0.75)'];
const chartOpts = (type) => ({ responsive:true, maintainAspectRatio:false, interaction:{mode:'index',intersect:false}, plugins:{ legend:{display:type==='line',labels:{font:{size:11,family:'Inter'},boxWidth:12,padding:12}}, tooltip:{bodyFont:{size:12},titleFont:{size:12,weight:'700'},padding:10} }, scales:{ x:{ticks:{font:{size:11},maxRotation:type==='bar'?30:0},grid:{color:'rgba(0,0,0,0.03)'}}, y:{beginAtZero:true,ticks:{stepSize:1,precision:0,font:{size:11},callback:v=>Number.isInteger(v)?v:null},grid:{color:'rgba(0,0,0,0.04)'}} } });
const lineChart = new Chart(document.getElementById('lineChart').getContext('2d'), { type:'line', data:{ labels:[], datasets:[{ label:'Total Kunjungan', data:[], borderColor:'#0284c7', backgroundColor:'rgba(2,132,199,0.06)', borderWidth:2.5, pointBackgroundColor:'#0284c7', pointRadius:3, fill:true, tension:0.35 },{ label:'Selesai', data:[], borderColor:'#16a34a', backgroundColor:'rgba(22,163,74,0.04)', borderWidth:2, borderDash:[4,3], pointBackgroundColor:'#16a34a', pointRadius:3, fill:true, tension:0.35 }] }, options:chartOpts('line') });
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

function executeBookingLookup() {
    const code = document.getElementById('lookupInputCode').value.trim();
    if (!code) {
        Swal.fire('Perhatian', 'Masukkan kode booking, nama, atau NIK pasien.', 'warning');
        return;
    }
    const area = document.getElementById('lookupResultArea');
    area.style.display = 'block';
    area.innerHTML = '<div style="color:#0284c7;font-size:13px"><i class="fas fa-spinner fa-spin"></i> Mencari data booking pasien...</div>';

    fetch(`{{ route('pendaftaran.api.lookup-booking') }}?code=${encodeURIComponent(code)}`)
        .then(r => r.json())
        .then(res => {
            if (res.success && res.data) {
                const d = res.data;
                const p = d.pasien || {};
                const poli = d.poli || {};
                const dr = d.dokter || {};
                let checkinBtn = d.is_checkin ? `<span class="badge" style="background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;padding:6px 12px;border-radius:6px;font-weight:700">Sudah Check-in (Antrean #${d.no_antrian})</span>` : `<form action="/pendaftaran/pendaftaran/${d.id}/checkin" method="POST" style="display:inline">@csrf<button type="submit" class="btn" style="background:#059669;color:white;font-weight:700;font-size:12px;padding:8px 14px"><i class="fas fa-check-circle"></i> Check-in & Ambil No. Antrean (Deposit Rp 200rb)</button></form>`;
                let printBtn = `<a href="/pendaftaran/pendaftaran/${d.id}/cetak-formulir" target="_blank" class="btn" style="font-weight:700;font-size:12px;border:1px solid #0284c7;color:#0284c7;padding:8px 14px"><i class="fas fa-print"></i> Cetak Formulir Pendaftaran</a>`;

                area.innerHTML = `
                    <div style="display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap">
                        <div>
                            <div style="font-size:15px;font-weight:800;color:#0f172a">${p.nama_lengkap || '-'} (NIK: ${p.nik || '-'})</div>
                            <div style="font-size:12px;color:#64748b;margin-top:2px">Kode Booking: <strong>${d.kode_booking}</strong> &middot; Poli: <strong>${poli.nama || '-'}</strong> &middot; Dokter: <strong>${dr.nama_lengkap || '-'}</strong></div>
                            <div style="font-size:12px;color:#0369a1;margin-top:4px"><i class="fas fa-route"></i> Estimasi Jarak Tempuh Pasien: <strong>${d.jarak_km || '3.5 km'}</strong> (&plusmn; ${d.estimasi_menit || 12} Menit)</div>
                        </div>
                        <div style="display:flex;gap:8px;align-items:center">
                            ${checkinBtn}
                            ${printBtn}
                        </div>
                    </div>
                `;
            } else {
                area.innerHTML = `<div style="color:#dc2626;font-size:13px"><i class="fas fa-triangle-exclamation"></i> ${res.message || 'Pendaftaran tidak ditemukan.'}</div>`;
            }
        })
        .catch(e => {
            area.innerHTML = '<div style="color:#dc2626;font-size:13px"><i class="fas fa-exclamation-circle"></i> Gagal terhubung ke server.</div>';
        });
}
</script>
@endpush
