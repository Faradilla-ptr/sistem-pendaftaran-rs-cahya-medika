@extends('layouts.app')
@section('title', 'Laporan - RS Cahya Medika')
@section('page-title', 'Laporan Kunjungan')

@section('content')

@php
    $r = request()->is('rekam-medis*') ? 'rekam_medis.' : (request()->is('pendaftaran*') ? 'pendaftaran.' : 'admin.');
@endphp

<!-- FILTER + EXPORT -->
<div class="card" style="margin-bottom:20px">
    <div class="card-body" style="padding:14px 18px">
        <form method="GET" action="{{ route($r . 'laporan') }}" id="filterForm">
            <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end">
                <div style="display:flex;flex-direction:column;gap:4px;flex:1;min-width:140px">
                    <label style="font-size:11px;font-weight:600;color:#6b7280">Bulan Kunjungan</label>
                    <select name="bulan" class="form-select" style="padding:6px 12px;font-size:12px;width:100%">
                        @foreach(range(1,12) as $b)
                            <option value="{{ $b }}" {{ $bulan == $b ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($b)->locale('id')->monthName }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div style="display:flex;flex-direction:column;gap:4px;flex:1;min-width:100px">
                    <label style="font-size:11px;font-weight:600;color:#6b7280">Tahun</label>
                    <select name="tahun" class="form-select" style="padding:6px 12px;font-size:12px;width:100%">
                        @foreach(range(date('Y'), date('Y')-4) as $t)
                            <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display:flex;gap:8px;align-items:center;align-self:flex-end">
                    <button type="submit" class="btn btn-primary btn-sm" style="padding:7px 16px"><i class="fas fa-filter"></i> Tampilkan</button>
                    <!-- Export Excel -->
                    <a href="{{ route($r . 'laporan.excel', ['bulan'=>$bulan,'tahun'=>$tahun]) }}"
                        class="btn btn-outline btn-sm" style="color:#059669;border-color:#059669;padding:7px 14px;font-weight:600">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>
                    <!-- Export PDF -->
                    <a href="{{ route($r . 'laporan.pdf', ['bulan'=>$bulan,'tahun'=>$tahun]) }}"
                        target="_blank" class="btn btn-outline btn-sm" style="color:#dc2626;border-color:#dc2626;padding:7px 14px;font-weight:600">
                        <i class="fas fa-file-pdf"></i> Export PDF
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- SUMMARY STATS -->
<div class="grid grid-4" style="margin-bottom:24px">
    <div class="stat-card">
        <div class="stat-icon" style="background:#e0f2fe">📋</div>
        <div>
            <div class="stat-value">{{ $data->count() }}</div>
            <div class="stat-label">Total Kunjungan</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#d1fae5">✅</div>
        <div>
            <div class="stat-value">{{ $byStatus['selesai'] ?? 0 }}</div>
            <div class="stat-label">Selesai</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fef3c7">⏳</div>
        <div>
            <div class="stat-value">{{ ($byStatus['menunggu'] ?? 0) + ($byStatus['dipanggil'] ?? 0) }}</div>
            <div class="stat-label">Menunggu / Dipanggil</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fee2e2">❌</div>
        <div>
            <div class="stat-value">{{ $byStatus['batal'] ?? 0 }}</div>
            <div class="stat-label">Dibatalkan</div>
        </div>
    </div>
</div>

<!-- SENSUS HARIAN RAWAT JALAN (PRD v2.0) -->
<div class="card" style="margin-bottom:24px;border-left:4px solid #0891b2">
    <div class="card-header" style="background:linear-gradient(135deg,#f8fafc,#f1f5f9)">
        <div>
            <div class="card-title" style="color:#0c4a6e;font-size:15px"><i class="fas fa-clipboard-list" style="color:#0891b2;margin-right:8px"></i> Sensus Harian Rawat Jalan — {{ $namabulan }}</div>
            <div style="font-size:11.5px;color:#64748b;margin-top:2px">Klasifikasi demografi wilayah, jenis kelamin, cara bayar, poli spesifik, dan rekap dokter spesialis sesuai standar pelaporan RS.</div>
        </div>
        <span class="badge" style="background:#0891b2;color:white;font-size:11px;padding:6px 12px;border-radius:8px">Standar PRD v2.0</span>
    </div>
    <div class="card-body">
        <div class="grid grid-4" style="gap:16px;margin-bottom:20px">
            <!-- 1. Wilayah -->
            <div style="background:#f0f9ff;border:1px solid #bae6fd;border-radius:12px;padding:14px">
                <div style="font-size:11px;font-weight:700;color:#0369a1;text-transform:uppercase;margin-bottom:8px"><i class="fas fa-map-marker-alt"></i> Demografi Wilayah</div>
                <div style="display:flex;justify-content:space-between;margin-bottom:6px">
                    <span style="font-size:12px;color:#334155">Dalam Kota (Bondowoso):</span>
                    <strong style="color:#0c4a6e">{{ $sensusWilayah['dalam_kota'] ?? 0 }}</strong>
                </div>
                <div style="display:flex;justify-content:space-between">
                    <span style="font-size:12px;color:#334155">Luar Kota:</span>
                    <strong style="color:#0891b2">{{ $sensusWilayah['luar_kota'] ?? 0 }}</strong>
                </div>
            </div>

            <!-- 2. Gender -->
            <div style="background:#fdf4ff;border:1px solid #f0abfc;border-radius:12px;padding:14px">
                <div style="font-size:11px;font-weight:700;color:#86198f;text-transform:uppercase;margin-bottom:8px"><i class="fas fa-venus-mars"></i> Jenis Kelamin</div>
                <div style="display:flex;justify-content:space-between;margin-bottom:6px">
                    <span style="font-size:12px;color:#334155">Laki-laki (L):</span>
                    <strong style="color:#0284c7">{{ $sensusGender['laki_laki'] ?? 0 }}</strong>
                </div>
                <div style="display:flex;justify-content:space-between">
                    <span style="font-size:12px;color:#334155">Perempuan (P):</span>
                    <strong style="color:#ec4899">{{ $sensusGender['perempuan'] ?? 0 }}</strong>
                </div>
            </div>

            <!-- 3. Cara Bayar -->
            <div style="background:#ecfdf5;border:1px solid #a7f3d0;border-radius:12px;padding:14px">
                <div style="font-size:11px;font-weight:700;color:#065f46;text-transform:uppercase;margin-bottom:8px"><i class="fas fa-wallet"></i> Cara Bayar</div>
                <div style="display:flex;justify-content:space-between;margin-bottom:4px">
                    <span style="font-size:12px;color:#334155">Umum / Mandiri:</span>
                    <strong style="color:#059669">{{ $sensusCaraBayar['umum'] ?? 0 }}</strong>
                </div>
                <div style="display:flex;justify-content:space-between;margin-bottom:4px">
                    <span style="font-size:12px;color:#334155">BPJS Kesehatan:</span>
                    <strong style="color:#0284c7">{{ $sensusCaraBayar['bpjs'] ?? 0 }}</strong>
                </div>
                <div style="display:flex;justify-content:space-between">
                    <span style="font-size:12px;color:#334155">Asuransi Lain:</span>
                    <strong style="color:#d97706">{{ $sensusCaraBayar['asuransi'] ?? 0 }}</strong>
                </div>
            </div>

            <!-- 4. Kasus Spesifik (Anak & Kandungan) -->
            <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:12px;padding:14px">
                <div style="font-size:11px;font-weight:700;color:#92400e;text-transform:uppercase;margin-bottom:8px"><i class="fas fa-stethoscope"></i> Poli Spesifik</div>
                <div style="font-size:11.5px;font-weight:700;color:#b45309;margin-bottom:2px">Poli Anak:</div>
                <div style="display:flex;justify-content:space-between;font-size:11px;margin-bottom:4px;color:#475569">
                    <span>Neonatus: <strong>{{ $sensusAnak['neonatus'] ?? 0 }}</strong></span>
                    <span>Anak: <strong>{{ $sensusAnak['anak'] ?? 0 }}</strong></span>
                </div>
                <div style="font-size:11.5px;font-weight:700;color:#b45309;margin-bottom:2px">Poli Kandungan:</div>
                <div style="display:flex;justify-content:space-between;font-size:11px;color:#475569">
                    <span>Obstetri: <strong>{{ $sensusObsgyn['obstetri'] ?? 0 }}</strong></span>
                    <span>Ginekologi: <strong>{{ $sensusObsgyn['ginekologi'] ?? 0 }}</strong></span>
                </div>
            </div>
        </div>

        <!-- REKAP PER DOKTER SPESIALIS TABLE -->
        <div style="margin-top:12px">
            <div style="font-size:12px;font-weight:700;color:#0c4a6e;margin-bottom:8px;text-transform:uppercase;letter-spacing:0.5px"><i class="fas fa-user-md" style="color:#0891b2"></i> Rekap Pelayanan Dokter Spesialis</div>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Dokter</th>
                            <th>Spesialisasi</th>
                            <th>Poliklinik</th>
                            <th>Pasien Baru</th>
                            <th>Kontrol</th>
                            <th>Total Pasien</th>
                            <th>Selesai Dilayani</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($byDokter as $dr)
                        <tr>
                            <td style="color:#94a3b8;font-size:11px">{{ $loop->iteration }}</td>
                            <td style="font-weight:700;color:#0c4a6e">{{ $dr['nama'] }}</td>
                            <td style="font-size:12px;color:#64748b">{{ $dr['spesialisasi'] }}</td>
                            <td style="font-size:12px">{{ $dr['poli'] }}</td>
                            <td style="font-size:12px;color:#0891b2;font-weight:600">{{ $dr['baru'] }}</td>
                            <td style="font-size:12px;color:#7c3aed;font-weight:600">{{ $dr['kontrol'] }}</td>
                            <td style="font-size:13px;font-weight:800;color:#0f172a">{{ $dr['total'] }}</td>
                            <td>
                                <span class="badge" style="background:#d1fae5;color:#065f46;font-size:11px">{{ $dr['selesai'] }} Pasien</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" style="text-align:center;color:#94a3b8;padding:16px">Belum ada data dokter bertugas</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- CHART HARIAN -->
<div class="card" style="margin-bottom:24px">
    <div class="card-header">
        <div class="card-title">📈 Kunjungan Harian — {{ $namabulan }}</div>
        <span style="font-size:12px;color:#94a3b8">Total: {{ $data->count() }} kunjungan</span>
    </div>
    <div class="card-body" style="padding:16px 20px">
        <canvas id="hariChart" height="80"></canvas>
    </div>
</div>

<div class="grid grid-2" style="gap:24px;margin-bottom:24px">

    <!-- REKAP PER POLI -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">🏥 Rekap Per Poli</div>
            <span style="font-size:12px;color:#94a3b8">{{ $namabulan }}</span>
        </div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Poli</th>
                        <th>Total</th>
                        <th>Baru</th>
                        <th>Kontrol</th>
                        <th>Selesai %</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($byPoli as $info)
                    @php
                        $pctSelesai = ($info['total'] > 0)
                            ? min(100, round($info['selesai'] / $info['total'] * 100))
                            : 0;
                    @endphp
                    <tr>
                        <td style="font-weight:600">{{ $info['nama'] }}</td>
                        <td><span style="font-weight:800;color:#0c4a6e">{{ $info['total'] }}</span></td>
                        <td style="font-size:12px;color:#0891b2">{{ $info['baru'] }}</td>
                        <td style="font-size:12px;color:#7c3aed">{{ $info['kontrol'] }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:8px">
                                <div style="flex:1;background:#e2e8f0;border-radius:4px;height:6px;min-width:50px">
                                    <div style="width:{{ $pctSelesai }}%;background:#059669;height:6px;border-radius:4px"></div>
                                </div>
                                <span style="font-size:11px;color:#64748b;width:36px;text-align:right">{{ $pctSelesai }}%</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="text-align:center;padding:24px;color:#94a3b8">Tidak ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- STATUS BREAKDOWN -->
    <div class="card">
        <div class="card-header"><div class="card-title">📊 Status Kunjungan</div></div>
        <div class="card-body">
            @php
                $totalData   = $data->count();
                $jmlSelesai  = (int) $byStatus->get('selesai',  0);
                $jmlMenunggu = (int) $byStatus->get('menunggu', 0);
                $jmlDipanggil= (int) $byStatus->get('dipanggil',0);
                $jmlBatal    = (int) $byStatus->get('batal',    0);
            @endphp
            @foreach([
                ['label'=>'Selesai',   'color'=>'#059669','count'=>$jmlSelesai],
                ['label'=>'Menunggu',  'color'=>'#d97706','count'=>$jmlMenunggu],
                ['label'=>'Dipanggil', 'color'=>'#0891b2','count'=>$jmlDipanggil],
                ['label'=>'Dibatalkan','color'=>'#dc2626','count'=>$jmlBatal],
            ] as $row)
            @php
                $pct = $totalData > 0 ? min(100, round($row['count'] / $totalData * 100)) : 0;
            @endphp
            <div style="margin-bottom:16px">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px">
                    <span style="font-size:13px;font-weight:600;color:#334155">{{ $row['label'] }}</span>
                    <span style="font-size:13px;font-weight:800;color:{{ $row['color'] }}">
                        {{ $row['count'] }} kunjungan &nbsp;<span style="font-size:12px;font-weight:600;opacity:0.8">({{ $pct }}%)</span>
                    </span>
                </div>
                <div style="background:#f1f5f9;border-radius:8px;height:10px;overflow:hidden">
                    <div style="width:{{ $pct }}%;background:{{ $row['color'] }};height:10px;border-radius:8px;transition:width 0.8s ease"></div>
                </div>
            </div>
            @endforeach

            <div style="margin-top:20px;padding:14px 16px;background:#f8fafc;border-radius:12px;border:1px solid #e2e8f0">
                <div style="font-size:11px;font-weight:700;color:#64748b;margin-bottom:10px;text-transform:uppercase;letter-spacing:0.5px">Jenis Kunjungan</div>
                @php
                    $jmlBaru    = $data->where('jenis_kunjungan','baru')->count();
                    $jmlKontrol = $data->where('jenis_kunjungan','kontrol')->count();
                    $pctBaru    = $totalData > 0 ? round($jmlBaru    / $totalData * 100) : 0;
                    $pctKontrol = $totalData > 0 ? round($jmlKontrol / $totalData * 100) : 0;
                @endphp
                <div style="display:flex;gap:12px">
                    <div style="flex:1;text-align:center;padding:10px;background:#e0f2fe;border-radius:10px">
                        <div style="font-size:22px;font-weight:900;color:#0891b2">{{ $jmlBaru }}</div>
                        <div style="font-size:11px;color:#0369a1;font-weight:600">Pasien Baru</div>
                        <div style="font-size:10px;color:#64748b;margin-top:2px">{{ $pctBaru }}% dari total</div>
                    </div>
                    <div style="flex:1;text-align:center;padding:10px;background:#ede9fe;border-radius:10px">
                        <div style="font-size:22px;font-weight:900;color:#7c3aed">{{ $jmlKontrol }}</div>
                        <div style="font-size:11px;color:#6d28d9;font-weight:600">Kontrol</div>
                        <div style="font-size:10px;color:#64748b;margin-top:2px">{{ $pctKontrol }}% dari total</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- DETAIL TABLE -->
<div class="card">
    <div class="card-header">
        <div class="card-title">
            📋 Detail Kunjungan — {{ $namabulan }}
            <span style="font-size:12px;font-weight:400;color:#94a3b8;margin-left:8px">{{ $data->count() }} data</span>
        </div>
        <div style="display:flex;gap:8px">
            <a href="{{ route($r . 'laporan.excel', ['bulan'=>$bulan,'tahun'=>$tahun]) }}"
                class="btn btn-sm btn-outline" style="color:#059669;border-color:#059669;font-size:11px">
                <i class="fas fa-file-excel"></i> Excel
            </a>
            <a href="{{ route($r . 'laporan.pdf', ['bulan'=>$bulan,'tahun'=>$tahun]) }}"
                target="_blank" class="btn btn-sm btn-outline" style="color:#dc2626;border-color:#dc2626;font-size:11px">
                <i class="fas fa-file-pdf"></i> PDF
            </a>
        </div>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tanggal</th>
                    <th>Kode Booking</th>
                    <th>Pasien</th>
                    <th>L/P</th>
                    <th>Wilayah</th>
                    <th>Cara Bayar</th>
                    <th>Poli</th>
                    <th>Dokter</th>
                    <th>Jam</th>
                    <th>Jenis</th>
                    <th>Status</th>
                    <th>SatuSehat</th>
                    <th>Biaya (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $i => $p)
                @php
                    $isDalam = ($p->status_wilayah === 'dalam_kota' || strtolower(trim($p->pasien->kabupaten ?? '')) === 'bondowoso');
                    $wilayahLabel = $isDalam ? 'Dalam Kota' : 'Luar Kota';
                @endphp
                <tr>
                    <td style="color:#94a3b8;font-size:12px">{{ $i+1 }}</td>
                    <td style="font-size:12px;font-weight:600">{{ $p->tanggal_kunjungan->format('d/m/Y') }}</td>
                    <td><code style="font-size:10px;color:#0891b2">{{ $p->kode_booking }}</code></td>
                    <td>
                        <div style="font-weight:600;font-size:13px">{{ $p->pasien->nama_lengkap ?? '-' }}</div>
                        <div style="font-size:10px;color:#94a3b8">{{ $p->pasien->no_rm ?? '' }}</div>
                    </td>
                    <td>
                        <span class="badge" style="background:#f1f5f9;color:#334155;font-size:11px;font-weight:700">{{ $p->pasien->jenis_kelamin ?? '-' }}</span>
                    </td>
                    <td>
                        <span class="badge" style="background:{{ $isDalam ? '#e0f2fe;color:#0369a1' : '#ede9fe;color:#6d28d9' }};font-size:10px;font-weight:700">
                            {{ $wilayahLabel }}
                        </span>
                    </td>
                    <td>
                        <span class="badge" style="background:#ecfdf5;color:#065f46;font-size:10px;font-weight:700">
                            {{ $p->cara_bayar ?? 'Umum' }}
                        </span>
                    </td>
                    <td style="font-size:12px">{{ $p->poli->nama ?? '-' }}</td>
                    <td style="font-size:12px">{{ $p->dokter->nama_lengkap ?? '-' }}</td>
                    <td style="font-size:12px">{{ $p->jam_kunjungan }}</td>
                    <td>
                        <span class="badge badge-{{ $p->jenis_kunjungan === 'baru' ? 'info' : 'secondary' }}" style="font-size:10px">
                            {{ $p->jenis_kunjungan === 'baru' ? 'Baru' : 'Kontrol' }}
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-{{ $p->status_color }}" style="font-size:10px">{{ $p->status_label }}</span>
                    </td>
                    <td>
                        <span class="ss-badge {{ $p->satusehat_status }}" style="font-size:10px">
                            {{ ucfirst($p->satusehat_status) }}
                        </span>
                    </td>
                    <td style="font-size:12px;text-align:right">
                        {{ $p->biaya_konsultasi > 0 ? number_format($p->biaya_konsultasi, 0, ',', '.') : '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="14" style="text-align:center;padding:48px;color:#94a3b8">
                        Tidak ada data untuk periode ini
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($data->count() > 0)
            @php
                $pctSelesaiGlobal = $data->count() > 0
                    ? min(100, round($jmlSelesai / $data->count() * 100))
                    : 0;
            @endphp
            <tfoot>
                <tr style="background:#f0f9ff;font-weight:700">
                    <td colspan="11" style="padding:10px 16px;font-size:12px;text-align:right;color:#0c4a6e">
                        Tingkat Penyelesaian:
                    </td>
                    <td colspan="2" style="padding:10px 16px;font-size:13px;color:#059669">
                        {{ $jmlSelesai }} / {{ $data->count() }} ({{ $pctSelesaiGlobal }}%)
                    </td>
                    <td style="padding:10px 16px;font-size:12px;text-align:right;color:#64748b">Pendapatan:</td>
                </tr>
                <tr style="background:#f8fafc;font-weight:700">
                    <td colspan="13" style="padding:10px 16px;font-size:12px;text-align:right">Total Pendapatan:</td>
                    <td style="padding:10px 16px;font-size:13px;color:#059669;text-align:right">
                        Rp {{ number_format($data->sum('biaya_konsultasi'), 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const hariCtx = document.getElementById('hariChart').getContext('2d');
new Chart(hariCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($hariList) !!},
        datasets: [{
            label: 'Kunjungan',
            data: {!! json_encode($hariData) !!},
            backgroundColor: 'rgba(8,145,178,0.65)',
            borderColor: '#0891b2',
            borderWidth: 1,
            borderRadius: 4,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    title: (items) => 'Tanggal ' + items[0].label,
                    label: (item) => item.raw + ' kunjungan'
                }
            }
        },
        scales: {
            x: { ticks: { font: { size: 10 } }, grid: { display: false } },
            y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 10 } }, grid: { color: 'rgba(0,0,0,0.04)' } }
        }
    }
});
</script>
@endpush
