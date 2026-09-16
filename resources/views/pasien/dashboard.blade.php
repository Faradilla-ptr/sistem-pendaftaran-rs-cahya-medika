@extends('layouts.app')
@section('title', 'Dashboard - Portal Pasien RS Cahya Medika')
@section('page-title', 'Beranda Pasien')

@section('content')
@php $pasienData = auth()->user()->pasien; @endphp

{{-- CLEAN WELCOME BANNER --}}
<div style="background:white;border:1px solid #e2e8f0;border-radius:14px;padding:20px 24px;margin-bottom:22px;display:flex;align-items:center;justify-content:space-between;gap:20px;box-shadow:0 1px 3px rgba(0,0,0,0.03)">
    <div style="display:flex;align-items:center;gap:16px">
        <div style="width:48px;height:48px;background:#f0fdf4;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;color:#16a34a;flex-shrink:0;border:1px solid #dcfce7">
            <i class="fas fa-user-circle"></i>
        </div>
        <div>
            <div style="font-size:18px;font-weight:700;color:#0f172a;margin-bottom:2px">Selamat Datang, {{ auth()->user()->name }}</div>
            <div style="font-size:12px;color:#64748b">Portal Pelayanan Pasien RS Cahya Medika Bondowoso</div>
            @if($pasienData)
            <div style="display:flex;gap:8px;margin-top:8px">
                <span style="background:#f8fafc;color:#334155;padding:3px 10px;border-radius:6px;font-size:11px;font-weight:600;border:1px solid #e2e8f0"><i class="fas fa-id-card" style="margin-right:5px;color:#2563eb"></i>No. RM: {{ $pasienData->no_rm ?? '-' }}</span>
                <span style="background:#f8fafc;color:#334155;padding:3px 10px;border-radius:6px;font-size:11px;font-weight:600;border:1px solid #e2e8f0"><i class="fas fa-{{ $pasienData->satusehat_id ? 'circle-check' : 'clock' }}" style="margin-right:5px;color:{{ $pasienData->satusehat_id ? '#16a34a' : '#d97706' }}"></i>{{ $pasienData->satusehat_id ? 'SatuSehat Terhubung' : 'SatuSehat Pending' }}</span>
            </div>
            @endif
        </div>
    </div>
    <a href="{{ route('pasien.pendaftaran.create') }}" class="btn btn-success" style="padding:10px 18px;border-radius:9px;font-size:13px;font-weight:600;flex-shrink:0;box-shadow:0 1px 2px rgba(22,163,74,0.2)">
        <i class="fas fa-plus" style="margin-right:4px"></i> Daftar Berobat
    </a>
</div>

{{-- STAT CARDS --}}
<div class="grid grid-3" style="margin-bottom:22px">
    <div class="stat-card" style="border:1px solid #e2e8f0;box-shadow:0 1px 3px rgba(0,0,0,0.03);border-radius:12px">
        <div class="stat-icon" style="background:#f0fdf4;color:#16a34a;border:1px solid #dcfce7"><i class="fas fa-clipboard-list"></i></div>
        <div>
            <div class="stat-value" style="color:#0f172a;font-size:22px">{{ $stats['total_pendaftaran'] ?? 0 }}</div>
            <div class="stat-label">Total Pendaftaran</div>
        </div>
    </div>
    <div class="stat-card" style="border:1px solid #e2e8f0;box-shadow:0 1px 3px rgba(0,0,0,0.03);border-radius:12px">
        <div class="stat-icon" style="background:#eff6ff;color:#2563eb;border:1px solid #dbeafe"><i class="fas fa-clock"></i></div>
        <div>
            <div class="stat-value" style="color:#0f172a;font-size:22px">{{ $stats['menunggu'] ?? 0 }}</div>
            <div class="stat-label">Menunggu Dilayani</div>
        </div>
    </div>
    <div class="stat-card" style="border:1px solid #e2e8f0;box-shadow:0 1px 3px rgba(0,0,0,0.03);border-radius:12px">
        <div class="stat-icon" style="background:#fffbeb;color:#d97706;border:1px solid #fef3c7"><i class="fas fa-circle-check"></i></div>
        <div>
            <div class="stat-value" style="color:#0f172a;font-size:22px">{{ $stats['selesai'] ?? 0 }}</div>
            <div class="stat-label">Kunjungan Selesai</div>
        </div>
    </div>
</div>

<div class="grid grid-2" style="gap:16px">
    {{-- PENDAFTARAN AKTIF --}}
    <div class="card" style="border:1px solid #e2e8f0;box-shadow:0 1px 3px rgba(0,0,0,0.03);border-radius:12px">
        <div class="card-header" style="border-bottom:1px solid #f1f5f9;background:#ffffff">
            <div class="card-title" style="font-size:13.5px;color:#0f172a"><i class="fas fa-ticket" style="color:#2563eb;margin-right:6px"></i> Tiket Antrean Aktif</div>
            <a href="{{ route('pasien.pendaftaran.index') }}" class="btn btn-outline btn-sm" style="border-color:#cbd5e1;color:#475569">Lihat Semua</a>
        </div>
        @if(isset($pendaftaran_aktif) && $pendaftaran_aktif->count() > 0)
            @foreach($pendaftaran_aktif as $p)
            <div style="padding:14px 18px;border-bottom:1px solid #f1f5f9">
                <div style="display:flex;align-items:center;justify-content:space-between;gap:10px">
                    <div style="display:flex;align-items:center;gap:12px">
                        <div style="width:40px;height:40px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:9px;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:14px;color:#0f172a;flex-shrink:0">{{ $p->no_antrian }}</div>
                        <div>
                            <div style="font-weight:600;font-size:13px;color:#0f172a">{{ $p->poli->nama ?? '-' }}</div>
                            <div style="font-size:11px;color:#64748b">{{ optional($p->tanggal_kunjungan)->format('d/m/Y') }} &middot; {{ $p->jam_kunjungan }} WIB</div>
                        </div>
                    </div>
                    <span class="badge badge-{{ $p->status==='menunggu'?'warning':($p->status==='dipanggil'?'info':'success') }}">{{ ucfirst($p->status) }}</span>
                </div>
            </div>
            @endforeach
        @else
        <div style="padding:36px;text-align:center;color:#94a3b8">
            <i class="fas fa-ticket" style="font-size:28px;display:block;margin-bottom:8px;color:#cbd5e1"></i>
            <div style="font-size:13px;margin-bottom:12px">Belum ada pendaftaran aktif</div>
            <a href="{{ route('pasien.pendaftaran.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Daftar Sekarang</a>
        </div>
        @endif
    </div>

    {{-- PROFIL & RIWAYAT --}}
    <div style="display:flex;flex-direction:column;gap:14px">
        {{-- INFO PASIEN (READ-ONLY) --}}
        <div class="card" style="border:1px solid #e2e8f0;box-shadow:0 1px 3px rgba(0,0,0,0.03);border-radius:12px">
            <div class="card-header" style="border-bottom:1px solid #f1f5f9;background:#ffffff">
                <div class="card-title" style="font-size:13.5px;color:#0f172a"><i class="fas fa-id-card" style="color:#16a34a;margin-right:6px"></i> Identitas Pasien</div>
            </div>
            @if($pasienData)
            <div style="padding:16px 18px">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                    <div style="padding:10px 12px;background:#f8fafc;border-radius:8px;border:1px solid #e2e8f0">
                        <div style="font-size:10px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:0.5px">Nama Lengkap</div>
                        <div style="font-size:13px;font-weight:600;color:#0f172a;margin-top:3px">{{ $pasienData->nama_lengkap }}</div>
                    </div>
                    <div style="padding:10px 12px;background:#f8fafc;border-radius:8px;border:1px solid #e2e8f0">
                        <div style="font-size:10px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:0.5px">No. RM</div>
                        <div style="font-size:13px;font-weight:700;color:#2563eb;margin-top:3px">{{ $pasienData->no_rm ?? '-' }}</div>
                    </div>
                    <div style="padding:10px 12px;background:#f8fafc;border-radius:8px;border:1px solid #e2e8f0">
                        <div style="font-size:10px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:0.5px">NIK</div>
                        <div style="font-size:12px;font-weight:500;color:#334155;margin-top:3px;font-family:monospace">{{ $pasienData->nik }}</div>
                    </div>
                    <div style="padding:10px 12px;background:#f8fafc;border-radius:8px;border:1px solid #e2e8f0">
                        <div style="font-size:10px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:0.5px">SatuSehat</div>
                        <div style="margin-top:4px"><span class="badge badge-{{ $pasienData->satusehat_id ? 'success' : 'warning' }}"><i class="fas fa-{{ $pasienData->satusehat_id ? 'circle-check' : 'clock' }}" style="margin-right:3px"></i>{{ $pasienData->satusehat_id ? 'Terdaftar' : 'Pending' }}</span></div>
                    </div>
                </div>
            </div>
            @else
            <div style="padding:20px 18px">
                <div class="alert alert-warning" style="margin:0"><i class="fas fa-triangle-exclamation"></i> Profil pasien belum lengkap. <a href="{{ route('pasien.profil') }}" style="color:#92400e;font-weight:700">Lengkapi sekarang &rarr;</a></div>
            </div>
            @endif
        </div>

        {{-- RIWAYAT TERAKHIR --}}
        <div class="card" style="flex:1;border:1px solid #e2e8f0;box-shadow:0 1px 3px rgba(0,0,0,0.03);border-radius:12px">
            <div class="card-header" style="border-bottom:1px solid #f1f5f9;background:#ffffff">
                <div class="card-title" style="font-size:13.5px;color:#0f172a"><i class="fas fa-clock-rotate-left" style="color:#64748b;margin-right:6px"></i> Riwayat Terakhir</div>
                <a href="{{ route('pasien.riwayat') }}" class="btn btn-outline btn-sm" style="border-color:#cbd5e1;color:#475569">Semua</a>
            </div>
            @if(isset($riwayat_terakhir) && $riwayat_terakhir->count() > 0)
                @foreach($riwayat_terakhir as $r)
                <div style="padding:11px 18px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;gap:10px">
                    <div>
                        <div style="font-size:13px;font-weight:600;color:#0f172a">{{ $r->poli->nama ?? '-' }}</div>
                        <div style="font-size:11px;color:#64748b">{{ optional($r->tanggal_kunjungan)->format('d/m/Y') }}</div>
                    </div>
                    <span class="badge badge-{{ $r->status==='selesai'?'success':($r->status==='batal'?'danger':'warning') }}">{{ ucfirst($r->status) }}</span>
                </div>
                @endforeach
            @else
            <div style="padding:24px;text-align:center;color:#94a3b8;font-size:13px">
                <i class="fas fa-inbox" style="display:block;font-size:24px;margin-bottom:6px;color:#cbd5e1"></i>
                Belum ada riwayat kunjungan
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
