@extends('layouts.app')
@section('title', 'Laporan - RS Cahya Medika')
@section('page-title', 'Laporan Kunjungan')

@section('content')

<!-- FILTER -->
<div class="card" style="margin-bottom:20px">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.laporan') }}">
            <div style="display:flex;gap:14px;align-items:flex-end;flex-wrap:wrap">
                <div class="form-group" style="margin:0">
                    <label class="form-label">Bulan</label>
                    <select name="bulan" class="form-select">
                        @foreach(range(1,12) as $b)
                            <option value="{{ $b }}" {{ $bulan == $b ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($b)->locale('id')->monthName }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="margin:0">
                    <label class="form-label">Tahun</label>
                    <select name="tahun" class="form-select">
                        @foreach(range(date('Y'), date('Y')-3) as $t)
                            <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Tampilkan</button>
                <button type="button" onclick="window.print()" class="btn btn-outline" style="margin-left:auto">
                    <i class="fas fa-print"></i> Cetak Laporan
                </button>
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
            <div class="stat-value">{{ $byStatus['menunggu'] ?? 0 }}</div>
            <div class="stat-label">Menunggu</div>
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

<div class="grid grid-2" style="gap:24px;margin-bottom:24px">

    <!-- REKAP PER POLI -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">📊 Rekap Per Poli</div>
            <span style="font-size:12px;color:#94a3b8">
                {{ \Carbon\Carbon::create()->month($bulan)->locale('id')->monthName }} {{ $tahun }}
            </span>
        </div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Poli</th>
                        <th>Total</th>
                        <th>Selesai</th>
                        <th>%</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($byPoli as $poliId => $info)
                    <tr>
                        <td style="font-weight:600">{{ $info['nama'] }}</td>
                        <td>
                            <span style="font-weight:800;color:#0c4a6e">{{ $info['total'] }}</span>
                        </td>
                        <td>{{ $info['selesai'] }}</td>
                        <td>
                            @php $pct = $info['total'] > 0 ? round($info['selesai']/$info['total']*100) : 0; @endphp
                            <div style="display:flex;align-items:center;gap:8px">
                                <div style="flex:1;background:#e2e8f0;border-radius:4px;height:6px">
                                    <div style="width:{{ $pct }}%;background:#059669;height:6px;border-radius:4px"></div>
                                </div>
                                <span style="font-size:11px;color:#64748b;width:30px">{{ $pct }}%</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align:center;padding:24px;color:#94a3b8">Tidak ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- STATUS BREAKDOWN -->
    <div class="card">
        <div class="card-header"><div class="card-title">📈 Status Kunjungan</div></div>
        <div class="card-body">
            @php $total = $data->count() ?: 1; @endphp
            @foreach(['selesai'=>['label'=>'Selesai','color'=>'#059669','bg'=>'#d1fae5'],'menunggu'=>['label'=>'Menunggu','color'=>'#d97706','bg'=>'#fef3c7'],'dipanggil'=>['label'=>'Dipanggil','color'=>'#0891b2','bg'=>'#e0f2fe'],'batal'=>['label'=>'Dibatalkan','color'=>'#dc2626','bg'=>'#fee2e2']] as $key => $info)
            @php $count = $byStatus[$key] ?? 0; $pct = round($count/$total*100); @endphp
            <div style="margin-bottom:16px">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px">
                    <span style="font-size:13px;font-weight:600">{{ $info['label'] }}</span>
                    <span style="font-size:13px;font-weight:800;color:{{ $info['color'] }}">{{ $count }} ({{ $pct }}%)</span>
                </div>
                <div style="background:#f1f5f9;border-radius:8px;height:10px;overflow:hidden">
                    <div style="width:{{ $pct }}%;background:{{ $info['color'] }};height:10px;border-radius:8px;transition:width 0.8s"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- DETAIL TABLE -->
<div class="card">
    <div class="card-header">
        <div class="card-title">
            📋 Detail Kunjungan
            <span style="font-size:12px;font-weight:400;color:#94a3b8;margin-left:8px">{{ $data->count() }} data</span>
        </div>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kode Booking</th>
                    <th>Pasien</th>
                    <th>Poli</th>
                    <th>Dokter</th>
                    <th>Tanggal</th>
                    <th>Jam</th>
                    <th>Jenis</th>
                    <th>Status</th>
                    <th>SatuSehat</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $i => $p)
                <tr>
                    <td style="color:#94a3b8;font-size:12px">{{ $i+1 }}</td>
                    <td><code style="font-size:11px;color:#0891b2">{{ $p->kode_booking }}</code></td>
                    <td>
                        <div style="font-weight:600;font-size:13px">{{ $p->pasien->nama_lengkap ?? '-' }}</div>
                        <div style="font-size:10px;color:#94a3b8">{{ $p->pasien->no_rm ?? '' }}</div>
                    </td>
                    <td style="font-size:12px">{{ $p->poli->nama ?? '-' }}</td>
                    <td style="font-size:12px">{{ $p->dokter->nama_lengkap ?? '-' }}</td>
                    <td style="font-size:12px">{{ $p->tanggal_kunjungan->format('d/m/Y') }}</td>
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
                </tr>
                @empty
                <tr>
                    <td colspan="10" style="text-align:center;padding:48px;color:#94a3b8">
                        Tidak ada data untuk periode ini
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
