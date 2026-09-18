@php
    $isRM = request()->is('rekam-medis*');
    $r    = $isRM ? 'rekam_medis.' : (request()->is('pendaftaran*') ? 'pendaftaran.' : 'admin.');
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

{{-- QUICK LOOKUP & SCAN QR CODE FOR LOKET --}}
@if(!$isRM)
<div class="card" style="margin-bottom:22px;border:1.5px solid var(--palette-soft);background:#f0f7fc;border-radius:14px;box-shadow:0 4px 14px rgba(13,59,102,0.06)">
    <div class="card-body" style="padding:18px 22px">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap">
            <div style="display:flex;align-items:center;gap:14px">
                <div style="width:46px;height:46px;background:linear-gradient(135deg, var(--palette-dark), var(--palette-deep));color:white;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0;box-shadow:0 2px 8px rgba(13,59,102,0.25)">
                    <i class="fas fa-qrcode"></i>
                </div>
                <div>
                    <div style="font-size:15px;font-weight:800;color:var(--palette-dark)">Verifikasi Tiket &amp; Check-In Pasien Loket</div>
                    <div style="font-size:12px;color:#475569;margin-top:2px">Arahkan kamera HP / webcam ke QR Code Tiket Pasien atau ketik Kode Booking / NIK.</div>
                </div>
            </div>
            <div style="display:flex;gap:8px;flex:1;max-width:520px">
                <input type="text" id="lookupInputCode" class="form-control" placeholder="📷 Scan QR / Ketik Kode Booking / NIK..." style="border:1.5px solid var(--palette-medium);font-family:monospace;font-size:13px;font-weight:700" autofocus onkeypress="if(event.key==='Enter') executeBookingLookup()">
                <button type="button" class="btn" style="background:linear-gradient(135deg, var(--palette-dark), var(--palette-deep));color:white;font-weight:700;padding:8px 16px;white-space:nowrap;border-radius:9px" onclick="openCameraScanner()">
                    <i class="fas fa-camera me-1"></i> Kamera
                </button>
                <button type="button" class="btn" style="background:var(--palette-medium);color:white;font-weight:700;padding:8px 16px;white-space:nowrap;border-radius:9px" onclick="executeBookingLookup()">
                    <i class="fas fa-search me-1"></i> Cek Data
                </button>
            </div>
        </div>
        <div id="lookupResultArea" style="display:none;margin-top:16px"></div>
    </div>
</div>

<!-- LIVE CAMERA SCANNER MODAL -->
<div id="cameraModal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(15,23,42,0.75);backdrop-filter:blur(6px);z-index:9999;align-items:center;justify-content:center;padding:20px">
    <div style="background:white;border-radius:20px;max-width:480px;width:100%;overflow:hidden;box-shadow:0 25px 50px -12px rgba(0,0,0,0.25);border:1px solid #e2e8f0;animation:fadeIn 0.2s">
        <div style="padding:16px 20px;background:linear-gradient(135deg, var(--palette-dark), var(--palette-deep));color:white;display:flex;align-items:center;justify-content:space-between">
            <div style="font-weight:800;font-size:15px;display:flex;align-items:center;gap:8px">
                <i class="fas fa-camera"></i> Live Scanner QR Code Tiket
            </div>
            <button type="button" onclick="stopCameraScanner()" style="background:rgba(255,255,255,0.2);border:none;color:white;width:30px;height:30px;border-radius:50%;cursor:pointer;font-size:14px;display:flex;align-items:center;justify-content:center">&times;</button>
        </div>
        <div style="padding:20px;text-align:center">
            <div id="reader" style="width:100%;border-radius:12px;overflow:hidden;background:#000;min-height:250px"></div>
            <div style="font-size:12.5px;color:#64748b;margin-top:14px">
                <i class="fas fa-info-circle me-1" style="color:var(--palette-medium)"></i> Arahkan kamera ke QR Code pada HP atau Tiket Fisik Pasien.
            </div>
        </div>
        <div style="padding:12px 20px;background:#f8fafc;border-top:1px solid #e2e8f0;text-align:right">
            <button type="button" onclick="stopCameraScanner()" class="btn btn-outline btn-sm">Tutup Kamera</button>
        </div>
    </div>
</div>

<!-- FLOATING MODAL OVERLAY: FORM VERIFIKASI DATA PASIEN & CHECK-IN -->
<div id="verificationModal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(15,23,42,0.7);backdrop-filter:blur(5px);z-index:9998;align-items:center;justify-content:center;padding:20px">
    <div style="background:white;border-radius:20px;max-width:960px;width:100%;max-height:92vh;display:flex;flex-direction:column;box-shadow:0 25px 50px -12px rgba(15,23,42,0.35);border:1px solid #e2e8f0;margin:auto">
        <!-- MODAL HEADER -->
        <div style="padding:18px 24px;background:linear-gradient(135deg, var(--palette-dark), var(--palette-deep));color:white;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid rgba(255,255,255,0.1);flex-shrink:0">
            <div>
                <div style="font-weight:800;font-size:16px;display:flex;align-items:center;gap:10px">
                    <i class="fas fa-id-card" style="color:var(--palette-soft)"></i> Form Verifikasi Identitas Pasien &amp; Check-In Loket
                </div>
                <div style="font-size:12px;opacity:0.85;margin-top:2px" id="vModalSubHeader">Pemeriksaan kelengkapan berkas &amp; data pendaftaran pasien</div>
            </div>
            <button type="button" onclick="closeVerificationModal()" style="background:rgba(255,255,255,0.18);border:none;color:white;width:32px;height:32px;border-radius:50%;cursor:pointer;font-size:16px;display:flex;align-items:center;justify-content:center;transition:all 0.2s">&times;</button>
        </div>

        <!-- MODAL BODY: SCROLLABLE FORM -->
        <div style="padding:24px;overflow-y:auto;flex:1;background:#f8fafc" id="vModalBody">
            <!-- Form populated by JS -->
        </div>
    </div>
</div>
@endif

{{-- STATS ROW --}}
<div class="grid grid-4" style="margin-bottom:22px">
    <div class="stat-card" style="border:1px solid #e2e8f0;box-shadow:0 1px 3px rgba(0,0,0,0.03);border-radius:12px">
        <div class="stat-icon" style="background:#f1f5f9;color:#334155;border:1px solid #e2e8f0"><i class="fas fa-clipboard-list"></i></div>
        <div>
            <div class="stat-value" style="color:#0f172a;font-size:22px">{{ number_format($stats['total_pendaftaran_bulan_ini']) }}</div>
            <div class="stat-label">Total Kunjungan (Bulan Ini)</div>
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

@if(!$isRM)
{{-- ANTREAN PENDAFTARAN HARI INI --}}
<div class="card" style="margin-bottom:20px;border:1px solid #e2e8f0;box-shadow:0 1px 3px rgba(0,0,0,0.03);border-radius:12px">
    <div class="card-header" style="border-bottom:1px solid #f1f5f9;background:#ffffff">
        <div class="card-title" style="font-size:14px;color:#0f172a"><i class="fas fa-list-ol" style="color:#2563eb;margin-right:6px"></i> Antrean Pendaftaran Hari Ini</div>
        <a href="{{ route($r . 'pendaftaran.index') }}" class="btn btn-outline btn-sm" style="border-color:#cbd5e1;color:#475569">Lihat Semua Antrean</a>
    </div>
    <div style="overflow-x:auto">
        {{-- Table header --}}
        <div style="display:grid;grid-template-columns:110px 1fr 1fr 110px 100px;padding:10px 18px;background:#f8fafc;border-bottom:1px solid #e2e8f0;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.5px">
            <div>No. Antrean</div><div>Pasien</div><div>Poli / Dokter</div><div>Status</div><div>Aksi</div>
        </div>
        @forelse($pendaftaran_hari_ini as $p)
        <div style="display:grid;grid-template-columns:110px 1fr 1fr 110px 100px;padding:12px 18px;border-bottom:1px solid #f1f5f9;align-items:center;font-size:13px">
            <div>
                @if($p->no_antrian > 0)
                    <span style="display:inline-flex;align-items:center;gap:4px;padding:4px 9px;background:#0f172a;color:white;border-radius:6px;font-weight:800;font-size:12px">
                        <i class="fas fa-ticket" style="font-size:10px;color:#38bdf8"></i>#{{ $p->no_antrian }}
                    </span>
                @else
                    <span style="display:inline-flex;align-items:center;gap:4px;padding:4px 9px;background:#f1f5f9;color:#64748b;border:1px solid #cbd5e1;border-radius:6px;font-weight:600;font-size:11px">
                        <i class="fas fa-clock" style="font-size:10px;color:#0284c7"></i> Online
                    </span>
                @endif
            </div>
            <div>
                <div style="font-weight:700;color:#0f172a;font-size:13.5px">{{ $p->pasien->nama_lengkap ?? '-' }}</div>
                <div style="font-size:11px;color:#0891b2;font-weight:600">No. RM: {{ $p->pasien->no_rm ?? '-' }}</div>
            </div>
            <div>
                <div style="font-size:12.5px;color:#334155;font-weight:600">{{ $p->poli->nama ?? '-' }}</div>
                <div style="font-size:11px;color:#64748b">{{ $p->dokter->nama_lengkap ?? '-' }} &middot; {{ $p->jam_kunjungan }}</div>
            </div>
            <div>
                <span class="badge badge-{{ $p->status === 'selesai' ? 'success' : ($p->status === 'dipanggil' ? 'info' : 'warning') }}" style="padding:4px 9px;font-size:11px">
                    {{ ucfirst(str_replace('_', ' ', $p->status)) }}
                </span>
            </div>
            <div>
                <form action="{{ route($r . 'pendaftaran.status', $p->id) }}" method="POST">
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
            <div style="font-size:13px">Belum ada antrean pendaftaran hari ini</div>
        </div>
        @endforelse
    </div>
</div>
@endif

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
const BAR_COLORS = ['rgba(37,99,235,0.75)','rgba(2,132,199,0.75)','rgba(22,163,74,0.75)','rgba(217,119,6,0.75)','rgba(124,58,237,0.75)','rgba(219,39,119,0.75)','rgba(14,165,233,0.75)','rgba(225,29,72,0.75)'];
const chartOpts = (type) => ({ responsive:true, maintainAspectRatio:false, interaction:{mode:'index',intersect:false}, plugins:{ legend:{display:type==='line',labels:{font:{size:11,family:'Inter'},boxWidth:12,padding:12}}, tooltip:{bodyFont:{size:12},titleFont:{size:12,weight:'700'},padding:10} }, scales:{ x:{ticks:{display:type!=='bar',font:{size:11},maxRotation:type==='bar'?30:0},grid:{color:'rgba(0,0,0,0.03)'}}, y:{beginAtZero:true,ticks:{stepSize:1,precision:0,font:{size:11},callback:v=>Number.isInteger(v)?v:null},grid:{color:'rgba(0,0,0,0.04)'}} } });
const lineChart = new Chart(document.getElementById('lineChart').getContext('2d'), { type:'line', data:{ labels:[], datasets:[{ label:'Total Kunjungan', data:[], borderColor:'#0284c7', backgroundColor:'rgba(2,132,199,0.06)', borderWidth:2.5, pointBackgroundColor:'#0284c7', pointRadius:3, fill:true, tension:0.35 },{ label:'Selesai', data:[], borderColor:'#16a34a', backgroundColor:'rgba(22,163,74,0.04)', borderWidth:2, borderDash:[4,3], pointBackgroundColor:'#16a34a', pointRadius:3, fill:true, tension:0.35 }] }, options:chartOpts('line') });
const barChart = new Chart(document.getElementById('barChart').getContext('2d'), { type:'bar', data:{ labels:[], datasets:[{ label:'Kunjungan', data:[], backgroundColor:[], borderRadius:6, borderSkipped:false }] }, options:chartOpts('bar') });
function loadChartData() {
    const bulan = document.getElementById('chartBulan').value;
    const tahun = document.getElementById('chartTahun').value;
    document.getElementById('chartLoading').style.display = 'flex';
    fetch(`{{ route($r . 'api.chart') }}?bulan=${bulan}&tahun=${tahun}`)
        .then(r=>r.json()).then(d=>{
            document.getElementById('chartPeriodeLabel').textContent = d.namaBulan;
            lineChart.data.labels = d.line.labels; lineChart.data.datasets[0].data = d.line.total; lineChart.data.datasets[1].data = d.line.selesai; lineChart.update('active');
            const hasData = d.bar.data.some(v=>v>0);
            document.getElementById('barNoData').style.display = hasData?'none':'block';
            barChart.data.labels = d.bar.labels; barChart.data.datasets[0].data = d.bar.data; barChart.data.datasets[0].backgroundColor = BAR_COLORS.slice(0,d.bar.labels.length); barChart.update('active');
        }).catch(e=>console.error(e)).finally(()=>{document.getElementById('chartLoading').style.display='none';});
}
loadChartData();

// CAMERA QR SCANNER LOGIC
let html5QrCodeScannerInstance = null;

function openCameraScanner() {
    const modal = document.getElementById('cameraModal');
    modal.style.display = 'flex';
    if (!html5QrCodeScannerInstance) {
        html5QrCodeScannerInstance = new Html5Qrcode("reader");
    }
    html5QrCodeScannerInstance.start(
        { facingMode: "environment" },
        { fps: 10, qrbox: { width: 250, height: 250 } },
        (decodedText, decodedResult) => {
            document.getElementById('lookupInputCode').value = decodedText;
            stopCameraScanner();
            executeBookingLookup();
        },
        (errorMessage) => { }
    ).catch(err => {
        Swal.fire('Kamera Tidak Dapat Dibuka', 'Izinkan akses kamera di browser Anda untuk melakukan scan QR.', 'error');
        stopCameraScanner();
    });
}

function stopCameraScanner() {
    const modal = document.getElementById('cameraModal');
    if (html5QrCodeScannerInstance && html5QrCodeScannerInstance.isScanning) {
        html5QrCodeScannerInstance.stop().then(() => {
            modal.style.display = 'none';
        }).catch(err => console.error(err));
    } else {
        modal.style.display = 'none';
    }
}

function openVerificationModal() {
    document.getElementById('verificationModal').style.display = 'flex';
}

function closeVerificationModal() {
    document.getElementById('verificationModal').style.display = 'none';
}

function executeBookingLookup() {
    const codeInput = document.getElementById('lookupInputCode');
    const code = codeInput.value.trim();
    if (!code) {
        Swal.fire('Perhatian', 'Arahkan kamera QR ke tiket pasien atau ketik Kode Booking / NIK.', 'warning');
        return;
    }
    
    // Show spinner in Swal loading
    Swal.fire({
        title: 'Memverifikasi QR Code...',
        text: 'Mengambil formulir identitas & data pendaftaran pasien',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });

    const lookupUrl = `{{ route($r . 'api.lookup-booking') }}`;
    fetch(`${lookupUrl}?code=${encodeURIComponent(code)}`)
        .then(r => r.json())
        .then(res => {
            Swal.close();
            if (res.success && res.data) {
                const d = res.data;
                const p = d.pasien || {};
                const poli = d.poli || {};
                const dr = d.dokter || {};
                
                const rolePrefix = '{{ request()->is("admin*") ? "/admin" : "/pendaftaran" }}';
                const checkinUrl = `${rolePrefix}/pendaftaran/${d.id}/checkin`;
                const printUrl = `${rolePrefix}/pendaftaran/${d.id}/cetak-formulir`;
                
                let printBtn = `<a href="${printUrl}" target="_blank" class="btn" style="font-weight:700;font-size:12.5px;border:1.5px solid var(--palette-medium);color:var(--palette-dark);padding:8px 16px;border-radius:8px;background:white"><i class="fas fa-print me-1"></i> Cetak Tiket</a>`;

                // Evaluasi Kelengkapan Isian Form Pasien
                const missingFields = [];
                if (!p.nik || p.nik.length !== 16) missingFields.push('NIK (Harus 16 Digit)');
                if (!p.nama_lengkap) missingFields.push('Nama Lengkap Pasien');
                if (!p.tempat_lahir || !p.tanggal_lahir) missingFields.push('Tempat / Tanggal Lahir');
                if (!p.jenis_kelamin) missingFields.push('Jenis Kelamin');
                if (!p.no_hp) missingFields.push('Nomor HP');
                if (!p.alamat) missingFields.push('Alamat KTP');
                if (!p.kecamatan) missingFields.push('Kecamatan');
                if (!p.kelurahan) missingFields.push('Kelurahan / Desa');
                if (!p.nama_ibu) missingFields.push('Nama Ibu Kandung');

                const isAllComplete = missingFields.length === 0;

                const statusBanner = isAllComplete ? `
                    <div style="background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:12px;padding:14px 18px;margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px">
                        <div style="display:flex;align-items:center;gap:12px">
                            <i class="fas fa-circle-check" style="font-size:24px;color:#16a34a"></i>
                            <div>
                                <div style="font-size:14px;font-weight:900;color:#166534">DATA UMUM PASIEN 100% LENGKAP</div>
                                <div style="font-size:12px;color:#15803d">Seluruh identitas utama, KTP, alamat, dan berkas pendaftaran telah terisi lengkap.</div>
                            </div>
                        </div>
                        <span class="badge" style="background:#16a34a;color:white;font-size:11.5px;padding:5px 12px;border-radius:99px;font-weight:800">READY TO CHECK-IN</span>
                    </div>
                ` : `
                    <div style="background:#fffbeb;border:1.5px solid #fde68a;border-radius:12px;padding:14px 18px;margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px">
                        <div style="display:flex;align-items:center;gap:12px">
                            <i class="fas fa-triangle-exclamation" style="font-size:24px;color:#d97706"></i>
                            <div>
                                <div style="font-size:14px;font-weight:900;color:#92400e">ADA ${missingFields.length} FIELD DATA PASIEN BELUM LENGKAP</div>
                                <div style="font-size:12px;color:#b45309">Field yang belum terisi / kurang: <strong>${missingFields.join(', ')}</strong></div>
                            </div>
                        </div>
                        <span class="badge" style="background:#d97706;color:white;font-size:11.5px;padding:5px 12px;border-radius:99px;font-weight:800">PERIKSA FORMULIR</span>
                    </div>
                `;

                document.getElementById('vModalSubHeader').innerHTML = `Kode Booking: <strong>${d.kode_booking}</strong> &middot; No. RM: <strong>${p.no_rm || '-'}</strong>`;

                const formContent = `
                    <div id="modalCompletenessBanner">
                        ${statusBanner}
                    </div>

                    <!-- FORM SECTION 1: DATA UMUM PASIEN (EDITABLE BY LOKET STAFF) -->
                    <div style="background:white;border:1px solid #e2e8f0;border-radius:14px;padding:20px;margin-bottom:18px;box-shadow:0 2px 6px rgba(0,0,0,0.02)">
                        <div style="font-size:13px;font-weight:800;color:var(--palette-dark);margin-bottom:6px;display:flex;align-items:center;justify-content:space-between">
                            <div style="display:flex;align-items:center;gap:8px">
                                <i class="fas fa-user-pen" style="color:var(--palette-medium)"></i> DATA UMUM PASIEN &amp; IDENTITAS KTP
                            </div>
                            <span style="font-size:11px;background:#eff6ff;color:#2563eb;padding:3px 10px;border-radius:6px;font-weight:600;border:1px solid #bfdbfe"><i class="fas fa-pen me-1"></i> Dapat Diedit Petugas</span>
                        </div>
                        <div style="font-size:11.5px;color:#64748b;margin-bottom:14px;border-bottom:1px solid #f1f5f9;padding-bottom:10px">
                            Periksa &amp; perbaiki data pasien jika terdapat kesalahan sebelum melakukan check-in.
                        </div>
                        <div style="display:grid;grid-template-columns:repeat(3, 1fr);gap:14px">
                            <div>
                                <label style="font-size:11px;font-weight:700;color:#64748b">No. KTP / SIM / NIK <span style="color:#dc2626">*</span></label>
                                <input type="text" name="pasien_nik" class="form-control" value="${p.nik || ''}" oninput="recheckModalDataCompleteness()" style="${!p.nik || p.nik.length!==16 ? 'background:#fffbe6;border-color:#f59e0b;font-weight:800' : ''};padding:8px 12px;font-size:13px;font-family:monospace" placeholder="16 Digit NIK">
                            </div>
                            <div>
                                <label style="font-size:11px;font-weight:700;color:#64748b">Nama Lengkap Pasien <span style="color:#dc2626">*</span></label>
                                <input type="text" name="pasien_nama_lengkap" class="form-control" value="${p.nama_lengkap || ''}" oninput="recheckModalDataCompleteness()" style="${!p.nama_lengkap ? 'background:#fffbe6;border-color:#f59e0b;font-weight:800' : ''};padding:8px 12px;font-size:13px">
                            </div>
                            <div>
                                <label style="font-size:11px;font-weight:700;color:#64748b">No. Rekam Medis (RM)</label>
                                <input type="text" readonly class="form-control" value="${p.no_rm || ''}" style="background:#e0f2fe;border-color:#bae6fd;color:#0369a1;font-weight:800;padding:8px 12px;font-size:13px;font-family:monospace">
                            </div>

                            <div>
                                <label style="font-size:11px;font-weight:700;color:#64748b">Tempat Lahir <span style="color:#dc2626">*</span></label>
                                <input type="text" name="pasien_tempat_lahir" class="form-control" value="${p.tempat_lahir || ''}" oninput="recheckModalDataCompleteness()" style="${!p.tempat_lahir ? 'background:#fffbe6;border-color:#f59e0b' : ''};padding:8px 12px;font-size:12.5px">
                            </div>
                            <div>
                                <label style="font-size:11px;font-weight:700;color:#64748b">Tanggal Lahir <span style="color:#dc2626">*</span></label>
                                <input type="date" name="pasien_tanggal_lahir" class="form-control" value="${p.tanggal_lahir || ''}" onchange="recheckModalDataCompleteness()" style="${!p.tanggal_lahir ? 'background:#fffbe6;border-color:#f59e0b' : ''};padding:8px 12px;font-size:12.5px">
                            </div>
                            <div>
                                <label style="font-size:11px;font-weight:700;color:#64748b">Jenis Kelamin <span style="color:#dc2626">*</span></label>
                                <select name="pasien_jenis_kelamin" class="form-select" onchange="recheckModalDataCompleteness()" style="padding:8px 12px;font-size:12.5px">
                                    <option value="L" ${p.jenis_kelamin === 'L' ? 'selected' : ''}>Laki-laki</option>
                                    <option value="P" ${p.jenis_kelamin === 'P' ? 'selected' : ''}>Perempuan</option>
                                </select>
                            </div>

                            <div>
                                <label style="font-size:11px;font-weight:700;color:#64748b">Pekerjaan Pasien</label>
                                <input type="text" name="pasien_pekerjaan" class="form-control" value="${p.pekerjaan || ''}" style="padding:8px 12px;font-size:12.5px" placeholder="Pekerjaan">
                            </div>
                            <div>
                                <label style="font-size:11px;font-weight:700;color:#64748b">Agama</label>
                                <select name="pasien_agama" class="form-select" style="padding:8px 12px;font-size:12.5px">
                                    <option value="Islam" ${p.agama === 'Islam' ? 'selected' : ''}>Islam</option>
                                    <option value="Kristen" ${p.agama === 'Kristen' ? 'selected' : ''}>Kristen</option>
                                    <option value="Katolik" ${p.agama === 'Katolik' ? 'selected' : ''}>Katolik</option>
                                    <option value="Hindu" ${p.agama === 'Hindu' ? 'selected' : ''}>Hindu</option>
                                    <option value="Buddha" ${p.agama === 'Buddha' ? 'selected' : ''}>Buddha</option>
                                    <option value="Khonghucu" ${p.agama === 'Khonghucu' ? 'selected' : ''}>Khonghucu</option>
                                </select>
                            </div>
                            <div>
                                <label style="font-size:11px;font-weight:700;color:#64748b">Pendidikan Terakhir</label>
                                <input type="text" name="pasien_pendidikan" class="form-control" value="${p.pendidikan || ''}" style="padding:8px 12px;font-size:12.5px" placeholder="SD / SMP / SMA / S1">
                            </div>

                            <div>
                                <label style="font-size:11px;font-weight:700;color:#64748b">Status Perkawinan</label>
                                <select name="pasien_status_pernikahan" class="form-select" style="padding:8px 12px;font-size:12.5px">
                                    <option value="Belum Kawin" ${p.status_pernikahan === 'Belum Kawin' ? 'selected' : ''}>Belum Kawin</option>
                                    <option value="Kawin" ${p.status_pernikahan === 'Kawin' ? 'selected' : ''}>Kawin</option>
                                    <option value="Cerai Hidup" ${p.status_pernikahan === 'Cerai Hidup' ? 'selected' : ''}>Cerai Hidup</option>
                                    <option value="Cerai Mati" ${p.status_pernikahan === 'Cerai Mati' ? 'selected' : ''}>Cerai Mati</option>
                                </select>
                            </div>
                            <div>
                                <label style="font-size:11px;font-weight:700;color:#64748b">Warga Negara</label>
                                <input type="text" name="pasien_warga_negara" class="form-control" value="${p.warga_negara || 'WNI'}" style="padding:8px 12px;font-size:12.5px">
                            </div>
                            <div>
                                <label style="font-size:11px;font-weight:700;color:#64748b">Golongan Darah</label>
                                <select name="pasien_golongan_darah" class="form-select" style="padding:8px 12px;font-size:12.5px">
                                    <option value="A" ${p.golongan_darah === 'A' ? 'selected' : ''}>A</option>
                                    <option value="B" ${p.golongan_darah === 'B' ? 'selected' : ''}>B</option>
                                    <option value="AB" ${p.golongan_darah === 'AB' ? 'selected' : ''}>AB</option>
                                    <option value="O" ${p.golongan_darah === 'O' ? 'selected' : ''}>O</option>
                                    <option value="Tidak Tahu" ${!p.golongan_darah || p.golongan_darah === 'Tidak Tahu' ? 'selected' : ''}>Tidak Tahu</option>
                                </select>
                            </div>

                            <div>
                                <label style="font-size:11px;font-weight:700;color:#64748b">Nama Ibu Kandung <span style="color:#dc2626">*</span></label>
                                <input type="text" name="pasien_nama_ibu" class="form-control" value="${p.nama_ibu || ''}" oninput="recheckModalDataCompleteness()" style="${!p.nama_ibu ? 'background:#fffbe6;border-color:#f59e0b' : ''};padding:8px 12px;font-size:12.5px" placeholder="Nama Ibu Kandung">
                            </div>
                            <div>
                                <label style="font-size:11px;font-weight:700;color:#64748b">Nama Ayah Kandung</label>
                                <input type="text" name="pasien_nama_ayah" class="form-control" value="${p.nama_ayah || ''}" style="padding:8px 12px;font-size:12.5px" placeholder="Nama Ayah Kandung">
                            </div>
                            <div>
                                <label style="font-size:11px;font-weight:700;color:#64748b">No. HP / Telepon <span style="color:#dc2626">*</span></label>
                                <input type="text" name="pasien_no_hp" class="form-control" value="${p.no_hp || ''}" oninput="recheckModalDataCompleteness()" style="${!p.no_hp ? 'background:#fffbe6;border-color:#f59e0b' : ''};padding:8px 12px;font-size:12.5px" placeholder="08xxxxxxxxxx">
                            </div>
                        </div>

                        <!-- ALAMAT LENGKAP KTP -->
                        <div style="margin-top:14px">
                            <label style="font-size:11px;font-weight:700;color:#64748b">Alamat Lengkap KTP (Jalan / Dusun / RT / RW) <span style="color:#dc2626">*</span></label>
                            <textarea name="pasien_alamat" class="form-control" oninput="recheckModalDataCompleteness()" style="${!p.alamat ? 'background:#fffbe6;border-color:#f59e0b' : ''};padding:8px 12px;font-size:12.5px;min-height:50px" placeholder="Alamat KTP Pasien">${p.alamat || ''}</textarea>
                        </div>
                        <div style="display:grid;grid-template-columns:repeat(4, 1fr);gap:10px;margin-top:10px">
                            <div>
                                <label style="font-size:11px;font-weight:700;color:#64748b">Provinsi</label>
                                <input type="text" name="pasien_provinsi" class="form-control" value="${p.provinsi || 'Jawa Timur'}" style="padding:6px 10px;font-size:12px">
                            </div>
                            <div>
                                <label style="font-size:11px;font-weight:700;color:#64748b">Kabupaten / Kota</label>
                                <input type="text" name="pasien_kabupaten" class="form-control" value="${p.kabupaten || ''}" style="padding:6px 10px;font-size:12px">
                            </div>
                            <div>
                                <label style="font-size:11px;font-weight:700;color:#64748b">Kecamatan <span style="color:#dc2626">*</span></label>
                                <input type="text" name="pasien_kecamatan" class="form-control" value="${p.kecamatan || ''}" oninput="recheckModalDataCompleteness()" style="${!p.kecamatan ? 'background:#fffbe6;border-color:#f59e0b' : ''};padding:6px 10px;font-size:12px">
                            </div>
                            <div>
                                <label style="font-size:11px;font-weight:700;color:#64748b">Desa / Kelurahan <span style="color:#dc2626">*</span></label>
                                <input type="text" name="pasien_kelurahan" class="form-control" value="${p.kelurahan || ''}" oninput="recheckModalDataCompleteness()" style="${!p.kelurahan ? 'background:#fffbe6;border-color:#f59e0b' : ''};padding:6px 10px;font-size:12px">
                            </div>
                        </div>
                    </div>

                    <!-- FORM SECTION 2: DATA PENDAFTARAN & TUJUAN BEROBAT -->
                    <div style="background:white;border:1px solid #e2e8f0;border-radius:14px;padding:20px;margin-bottom:18px;box-shadow:0 2px 6px rgba(0,0,0,0.02)">
                        <div style="font-size:13px;font-weight:800;color:var(--palette-dark);margin-bottom:14px;display:flex;align-items:center;gap:8px;border-bottom:1px solid #f1f5f9;padding-bottom:10px">
                            <i class="fas fa-clinic-medical" style="color:var(--palette-medium)"></i> TUJUAN BEROBAT &amp; POLIKLINIK
                        </div>
                        <div style="display:grid;grid-template-columns:repeat(3, 1fr);gap:14px">
                            <div>
                                <label style="font-size:11px;font-weight:700;color:#64748b">Poliklinik Tujuan</label>
                                <input type="text" readonly class="form-control" value="${poli.nama || '-'}" style="background:#e0f2fe;border-color:#bae6fd;color:var(--palette-dark);font-weight:800;padding:8px 12px;font-size:13px">
                            </div>
                            <div>
                                <label style="font-size:11px;font-weight:700;color:#64748b">Dokter Spesialis</label>
                                <input type="text" readonly class="form-control" value="${dr.nama_lengkap || '-'}" style="background:#f8fafc;padding:8px 12px;font-size:13px;font-weight:700">
                            </div>
                            <div>
                                <label style="font-size:11px;font-weight:700;color:#64748b">Tanggal &amp; Jam Kunjungan</label>
                                <input type="text" readonly class="form-control" value="${d.tanggal_kunjungan || '-'} &middot; ${d.jam_kunjungan || '-'} WIB" style="background:#f8fafc;padding:8px 12px;font-size:12.5px;font-weight:600">
                            </div>
                        </div>
                        <div style="margin-top:10px">
                            <label style="font-size:11px;font-weight:700;color:#64748b">Keluhan Utama Pasien</label>
                            <input type="text" readonly class="form-control" value="${d.keluhan || '-'}" style="background:#f8fafc;padding:8px 12px;font-size:12.5px;font-style:italic">
                        </div>
                    </div>

                    <!-- FORM SECTION 3: CHECKIN & INPUT TANDA VITAL -->
                    <div style="background:white;border:1.5px solid var(--palette-soft);padding:20px;border-radius:14px;box-shadow:0 4px 14px rgba(13,59,102,0.06)">
                        <div style="font-size:14px;font-weight:800;color:var(--palette-dark);margin-bottom:12px;display:flex;align-items:center;gap:8px">
                            <i class="fas fa-heart-pulse" style="color:#dc2626"></i> PEMERIKSAAN TANDA VITAL PASIEN (DISARANKAN SAAT LOKET)
                        </div>
                        <div style="display:grid;grid-template-columns:repeat(4, 1fr);gap:12px;margin-bottom:16px">
                            <div>
                                <label style="font-size:11px;font-weight:700;color:#475569">Tekanan Darah</label>
                                <input type="text" name="tekanan_darah" class="form-control" placeholder="120/80 mmHg" value="${d.tekanan_darah || ''}" style="padding:8px 12px;font-size:12.5px">
                            </div>
                            <div>
                                <label style="font-size:11px;font-weight:700;color:#475569">Suhu (&deg;C)</label>
                                <input type="text" name="suhu" class="form-control" placeholder="36.5" value="${d.suhu || ''}" style="padding:8px 12px;font-size:12.5px">
                            </div>
                            <div>
                                <label style="font-size:11px;font-weight:700;color:#475569">Nadi (x/mnt)</label>
                                <input type="text" name="nadi" class="form-control" placeholder="80" value="${d.nadi || ''}" style="padding:8px 12px;font-size:12.5px">
                            </div>
                            <div>
                                <label style="font-size:11px;font-weight:700;color:#475569">Berat Badan (kg)</label>
                                <input type="text" name="berat_badan" class="form-control" placeholder="60" value="${d.berat_badan || ''}" style="padding:8px 12px;font-size:12.5px">
                            </div>
                        </div>

                        <div style="display:flex;align-items:center;justify-content:space-between;gap:14px;padding-top:14px;border-top:1px solid #f1f5f9;flex-wrap:wrap">
                            <div style="font-size:12.5px;color:#059669;font-weight:700;display:flex;align-items:center;gap:6px">
                                <i class="fas fa-shield-check" style="font-size:16px"></i> Deposit Rp 200.000 Terbaca &amp; Data Pasien Telah Diperiksa Petugas Loket
                            </div>
                            <div style="display:flex;gap:10px">
                                <button type="button" onclick="closeVerificationModal()" class="btn btn-outline">Batal</button>
                                ${printBtn}
                                ${d.is_checkin ? `
                                    <button type="submit" class="btn" style="background:linear-gradient(135deg, var(--palette-dark), var(--palette-deep));color:white;font-weight:800;font-size:14px;padding:12px 24px;border-radius:99px">
                                        <i class="fas fa-save me-1"></i> SIMPAN PERUBAHAN DATA
                                    </button>
                                ` : `
                                    <button type="submit" class="btn" style="background:linear-gradient(135deg, #16a34a 0%, #059669 100%);color:white;font-weight:800;font-size:14px;padding:12px 24px;border-radius:99px;box-shadow:0 4px 14px rgba(22,163,74,0.3)">
                                        <i class="fas fa-check-circle me-1"></i> VERIFIKASI &amp; TERBITKAN ANTREAN (CHECK-IN)
                                    </button>
                                `}
                            </div>
                        </div>
                    </div>
                `;

                if (d.is_checkin) {
                    document.getElementById('vModalBody').innerHTML = `
                        <div style="background:#f0fdf4;border:1.5px solid #bbf7d0;padding:18px 22px;border-radius:14px;margin-bottom:18px;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap">
                            <div style="display:flex;align-items:center;gap:14px">
                                <i class="fas fa-circle-check" style="font-size:32px;color:#16a34a"></i>
                                <div>
                                    <div style="font-size:16px;font-weight:900;color:#166534">PASIEN SUDAH CHECK-IN LOKET</div>
                                    <div style="font-size:13px;color:#15803d;margin-top:2px">Nomor Antrean Resmi Pasien: <strong style="font-size:18px;color:#0f172a">#${d.no_antrian}</strong> &middot; Waktu: ${d.waktu_checkin || '-'}</div>
                                </div>
                            </div>
                        </div>
                        <form action="${checkinUrl}" method="POST">
                            @csrf
                            ${formContent}
                        </form>
                    `;
                } else {
                    document.getElementById('vModalBody').innerHTML = `
                        <form action="${checkinUrl}" method="POST">
                            @csrf
                            ${formContent}
                        </form>
                    `;
                }

                openVerificationModal();
            } else {
                Swal.fire('Data Tidak Ditemukan', res.message || 'QR Code / Pendaftaran tidak ditemukan.', 'error');
            }
        })
        .catch(e => {
            Swal.fire('Terjadi Kesalahan', 'Gagal terhubung ke server lookup.', 'error');
        });
}

function recheckModalDataCompleteness() {
    const nik = document.querySelector('input[name="pasien_nik"]')?.value.trim() || '';
    const nama = document.querySelector('input[name="pasien_nama_lengkap"]')?.value.trim() || '';
    const tempat = document.querySelector('input[name="pasien_tempat_lahir"]')?.value.trim() || '';
    const tgl = document.querySelector('input[name="pasien_tanggal_lahir"]')?.value.trim() || '';
    const jk = document.querySelector('select[name="pasien_jenis_kelamin"]')?.value.trim() || '';
    const hp = document.querySelector('input[name="pasien_no_hp"]')?.value.trim() || '';
    const alamat = document.querySelector('textarea[name="pasien_alamat"]')?.value.trim() || '';
    const kec = document.querySelector('input[name="pasien_kecamatan"]')?.value.trim() || '';
    const kel = document.querySelector('input[name="pasien_kelurahan"]')?.value.trim() || '';
    const ibu = document.querySelector('input[name="pasien_nama_ibu"]')?.value.trim() || '';

    const missingFields = [];
    if (!nik || nik.length !== 16) missingFields.push('NIK (Harus 16 Digit)');
    if (!nama) missingFields.push('Nama Lengkap Pasien');
    if (!tempat || !tgl) missingFields.push('Tempat / Tanggal Lahir');
    if (!jk) missingFields.push('Jenis Kelamin');
    if (!hp) missingFields.push('Nomor HP');
    if (!alamat) missingFields.push('Alamat KTP');
    if (!kec) missingFields.push('Kecamatan');
    if (!kel) missingFields.push('Kelurahan / Desa');
    if (!ibu) missingFields.push('Nama Ibu Kandung');

    const bannerContainer = document.getElementById('modalCompletenessBanner');
    if (bannerContainer) {
        if (missingFields.length === 0) {
            bannerContainer.innerHTML = `
                <div style="background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:12px;padding:14px 18px;margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px">
                    <div style="display:flex;align-items:center;gap:12px">
                        <i class="fas fa-circle-check" style="font-size:24px;color:#16a34a"></i>
                        <div>
                            <div style="font-size:14px;font-weight:900;color:#166534">DATA UMUM PASIEN 100% LENGKAP</div>
                            <div style="font-size:12px;color:#15803d">Seluruh identitas utama, KTP, alamat, dan berkas pendaftaran telah terisi lengkap.</div>
                        </div>
                    </div>
                    <span class="badge" style="background:#16a34a;color:white;font-size:11.5px;padding:5px 12px;border-radius:99px;font-weight:800">READY TO CHECK-IN</span>
                </div>`;
        } else {
            bannerContainer.innerHTML = `
                <div style="background:#fffbeb;border:1.5px solid #fde68a;border-radius:12px;padding:14px 18px;margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px">
                    <div style="display:flex;align-items:center;gap:12px">
                        <i class="fas fa-triangle-exclamation" style="font-size:24px;color:#d97706"></i>
                        <div>
                            <div style="font-size:14px;font-weight:900;color:#92400e">ADA ${missingFields.length} FIELD DATA PASIEN BELUM LENGKAP</div>
                            <div style="font-size:12px;color:#b45309">Field yang belum terisi / kurang: <strong>${missingFields.join(', ')}</strong></div>
                        </div>
                    </div>
                    <span class="badge" style="background:#d97706;color:white;font-size:11.5px;padding:5px 12px;border-radius:99px;font-weight:800">PERIKSA FORMULIR</span>
                </div>`;
        }
    }
}
</script>
@endpush
