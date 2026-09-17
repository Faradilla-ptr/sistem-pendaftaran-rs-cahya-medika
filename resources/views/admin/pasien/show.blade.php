@extends('layouts.app')
@section('title', 'Detail Pasien - RS Cahya Medika')
@section('page-title', 'Detail Pasien')

@section('content')
@php 
    $r = auth()->user()->role === 'rekam_medis' ? 'rekam_medis.' : (auth()->user()->role === 'pendaftaran' ? 'pendaftaran.' : 'admin.'); 
@endphp

<div style="max-width:960px;margin:0 auto">

<!-- BREADCRUMB -->
<div style="display:flex;align-items:center;gap:8px;margin-bottom:20px;font-size:13px;color:#64748b">
    <a href="{{ route($r . 'dashboard') }}" style="color:#0891b2;text-decoration:none">Dashboard</a>
    <i class="fas fa-chevron-right" style="font-size:10px"></i>
    <a href="{{ route($r . 'pasien.index') }}" style="color:#0891b2;text-decoration:none">Data Pasien</a>
    <i class="fas fa-chevron-right" style="font-size:10px"></i>
    <span>{{ $pasien->nama_lengkap }}</span>
</div>

<!-- HEADER CARD -->
<div style="background:linear-gradient(135deg,#0c4a6e,#0891b2);border-radius:18px;padding:24px 28px;color:white;margin-bottom:24px;display:flex;align-items:center;gap:20px">
    <div style="width:72px;height:72px;background:rgba(255,255,255,0.15);border-radius:20px;display:flex;align-items:center;justify-content:center;font-size:32px;flex-shrink:0">
        {{ $pasien->jenis_kelamin === 'L' ? '👨' : '👩' }}
    </div>
    <div style="flex:1">
        <div style="font-size:22px;font-weight:900;margin-bottom:4px">{{ $pasien->nama_lengkap }}</div>
        <div style="font-size:13px;opacity:0.75">
            No. RM: <strong>{{ $pasien->no_rm }}</strong> &nbsp;·&nbsp;
            NIK: <strong>{{ $pasien->nik }}</strong> &nbsp;·&nbsp;
            {{ $pasien->umur }} tahun
        </div>
        <div style="display:flex;gap:8px;margin-top:10px;flex-wrap:wrap">
            <span style="background:rgba(255,255,255,0.15);padding:4px 12px;border-radius:8px;font-size:11px;font-weight:600">
                {{ $pasien->jenis_kelamin_label }}
            </span>
            @if($pasien->golongan_darah)
            <span style="background:rgba(220,38,38,0.3);border:1px solid rgba(255,100,100,0.4);padding:4px 12px;border-radius:8px;font-size:11px;font-weight:600">
                Gol. Darah {{ $pasien->golongan_darah }}
            </span>
            @endif
            <span style="background:{{ $pasien->status === 'aktif' ? 'rgba(5,150,105,0.3)' : 'rgba(220,38,38,0.3)' }};padding:4px 12px;border-radius:8px;font-size:11px;font-weight:600">
                {{ ucfirst($pasien->status) }}
            </span>
            @if($pasien->satusehat_id)
            <span style="background:rgba(6,182,212,0.3);border:1px solid rgba(6,182,212,0.5);padding:4px 12px;border-radius:8px;font-size:11px;font-weight:600">
                ✅ SatuSehat ID: {{ $pasien->satusehat_id }}
            </span>
            @endif
        </div>
    </div>
    <div style="display:flex;gap:10px;flex-shrink:0">
        @if(in_array(auth()->user()->role, ['admin', 'pendaftaran']))
        <a href="{{ route($r . 'pasien.edit', $pasien->id) }}" class="btn btn-outline" style="background:rgba(255,255,255,0.1);color:white;border-color:rgba(255,255,255,0.2)">
            <i class="fas fa-edit"></i> Edit
        </a>
        @endif
    </div>
</div>

<div class="grid grid-2" style="gap:20px;align-items:start">

    <!-- LEFT: DATA DIRI -->
    <div style="display:flex;flex-direction:column;gap:20px">

        <div class="card">
            <div class="card-header"><div class="card-title">📋 Data Diri</div></div>
            <div class="card-body">
                @foreach([
                    ['NIK', $pasien->nik],
                    ['Nama Lengkap', $pasien->nama_lengkap],
                    ['Tempat Lahir', $pasien->tempat_lahir ?? '-'],
                    ['Tanggal Lahir', optional($pasien->tanggal_lahir)->format('d M Y') . ' (' . $pasien->umur . ' tahun)'],
                    ['Jenis Kelamin', $pasien->jenis_kelamin_label],
                    ['Golongan Darah', $pasien->golongan_darah ?? '-'],
                    ['Agama', $pasien->agama ?? '-'],
                    ['Status Pernikahan', $pasien->status_pernikahan ?? '-'],
                    ['Pekerjaan', $pasien->pekerjaan ?? '-'],
                    ['Pendidikan', $pasien->pendidikan ?? '-'],
                ] as [$label, $val])
                <div style="display:flex;gap:10px;padding:8px 0;border-bottom:1px solid #f1f5f9">
                    <div style="font-size:11px;font-weight:700;color:#94a3b8;width:130px;flex-shrink:0;padding-top:1px">{{ $label }}</div>
                    <div style="font-size:13px;color:#1e293b;font-weight:500">{{ $val }}</div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="card">
            <div class="card-header"><div class="card-title">📞 Kontak & Alamat</div></div>
            <div class="card-body">
                @foreach([
                    ['No. HP', $pasien->no_hp ?? '-'],
                    ['Email', $pasien->email ?? '-'],
                    ['Alamat', $pasien->alamat ?? '-'],
                    ['Kecamatan', $pasien->kecamatan ?? '-'],
                    ['Kabupaten', $pasien->kabupaten ?? '-'],
                    ['Provinsi', $pasien->provinsi ?? '-'],
                    ['Kode Pos', $pasien->kode_pos ?? '-'],
                ] as [$label, $val])
                <div style="display:flex;gap:10px;padding:8px 0;border-bottom:1px solid #f1f5f9">
                    <div style="font-size:11px;font-weight:700;color:#94a3b8;width:130px;flex-shrink:0;padding-top:1px">{{ $label }}</div>
                    <div style="font-size:13px;color:#1e293b;font-weight:500">{{ $val }}</div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="card">
            <div class="card-header"><div class="card-title">🆘 Penanggung Jawab</div></div>
            <div class="card-body">
                @foreach([
                    ['Nama PJ', $pasien->nama_pj ?? '-'],
                    ['Hubungan', $pasien->hubungan_pj ?? '-'],
                    ['No. HP PJ', $pasien->no_hp_pj ?? '-'],
                    ['Alamat PJ', $pasien->alamat_pj ?? '-'],
                ] as [$label, $val])
                <div style="display:flex;gap:10px;padding:8px 0;border-bottom:1px solid #f1f5f9">
                    <div style="font-size:11px;font-weight:700;color:#94a3b8;width:130px;flex-shrink:0;padding-top:1px">{{ $label }}</div>
                    <div style="font-size:13px;color:#1e293b;font-weight:500">{{ $val }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- RIGHT: RIWAYAT -->
    <div style="display:flex;flex-direction:column;gap:20px">

        <!-- SatuSehat Data -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">🔗 Data SatuSehat</div>
                @if($pasien->satusehat_id)
                    <span class="badge badge-success">Terdaftar</span>
                @else
                    <span class="badge badge-warning">Belum Sync</span>
                @endif
            </div>
            <div class="card-body">
                @if($pasien->satusehat_id)
                <div style="margin-bottom:12px">
                    <div style="font-size:11px;font-weight:700;color:#64748b;margin-bottom:4px">PATIENT ID (FHIR)</div>
                    <code style="font-size:12px;color:#0891b2">{{ $pasien->satusehat_id }}</code>
                </div>
                @endif
                @if($satusehatData && isset($satusehatData['total']))
                <div style="font-size:12px;color:#64748b">
                    Data ditemukan: <strong>{{ $satusehatData['total'] }}</strong> record
                </div>
                @endif
                @if(!$pasien->satusehat_id)
                <div style="padding:12px;background:#fffbeb;border:1px solid #fde68a;border-radius:10px;font-size:12px;color:#92400e">
                    <i class="fas fa-exclamation-triangle" style="margin-right:4px"></i>
                    Pasien belum terdaftar di SatuSehat. Minta pasien untuk melengkapi profil agar data tersinkronisasi.
                </div>
                @endif
            </div>
        </div>

        <!-- Riwayat Kunjungan -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">📅 Riwayat Kunjungan</div>
                <span style="font-size:12px;color:#94a3b8">{{ $pasien->pendaftaran->count() }} total</span>
            </div>
            <div class="card-body" style="padding:0">
                @forelse($pasien->pendaftaran->sortByDesc('tanggal_kunjungan')->take(10) as $kunjungan)
                <div style="padding:14px 18px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:12px">
                    <div style="width:42px;text-align:center;flex-shrink:0">
                        <div style="background:{{ $kunjungan->status === 'selesai' ? '#d1fae5' : ($kunjungan->status === 'menunggu' ? '#fef3c7' : '#f1f5f9') }};border-radius:10px;padding:6px">
                            <div style="font-size:14px;font-weight:900;color:{{ $kunjungan->status === 'selesai' ? '#065f46' : ($kunjungan->status === 'menunggu' ? '#92400e' : '#475569') }}">
                                {{ $kunjungan->tanggal_kunjungan->format('d') }}
                            </div>
                            <div style="font-size:9px;font-weight:700;text-transform:uppercase;color:#94a3b8">
                                {{ $kunjungan->tanggal_kunjungan->format('M') }}
                            </div>
                        </div>
                    </div>
                    <div style="flex:1">
                        <div style="font-weight:700;font-size:13px">{{ $kunjungan->poli->nama ?? '-' }}</div>
                        <div style="font-size:11px;color:#64748b">{{ $kunjungan->dokter->nama_lengkap ?? '-' }}</div>
                        <div style="font-size:11px;color:#94a3b8">{{ $kunjungan->kode_booking }}</div>
                    </div>
                    <div style="text-align:right">
                        <span class="badge badge-{{ $kunjungan->status_color }}" style="font-size:10px">{{ $kunjungan->status_label }}</span>
                        <div style="margin-top:4px">
                            <a href="{{ route($r . 'pendaftaran.show', $kunjungan->id) }}" style="font-size:11px;color:#0891b2;text-decoration:none">Detail</a>
                        </div>
                    </div>
                </div>
                @empty
                <div style="padding:30px;text-align:center;color:#94a3b8;font-size:13px">
                    Belum ada riwayat kunjungan
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div style="margin-top:20px">
    <a href="{{ route($r . 'pasien.index') }}" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar
    </a>
</div>
</div>
@endsection
