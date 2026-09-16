@extends('layouts.app')
@section('title', 'Dashboard - Portal Pasien RS Cahya Medika')
@section('page-title', 'Beranda Pasien')

@section('content')
@php $pasienData = auth()->user()->pasien; @endphp

{{-- WELCOME BANNER --}}
<div style="background:linear-gradient(135deg,#15803d 0%,#16a34a 50%,#059669 100%);border-radius:14px;padding:22px 26px;color:white;margin-bottom:22px;display:flex;align-items:center;justify-content:space-between;gap:20px">
    <div style="display:flex;align-items:center;gap:16px">
        <div style="width:52px;height:52px;background:rgba(255,255,255,0.15);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:26px;flex-shrink:0">👋</div>
        <div>
            <div style="font-size:17px;font-weight:800;margin-bottom:3px">Selamat Datang, {{ auth()->user()->name }}!</div>
            <div style="font-size:12px;opacity:0.75">Portal Pasien RS Cahya Medika Bondowoso</div>
            @if($pasienData)
            <div style="display:flex;gap:8px;margin-top:10px">
                <span style="background:rgba(255,255,255,0.15);padding:3px 11px;border-radius:6px;font-size:11px;font-weight:600"><i class="fas fa-id-card" style="margin-right:5px"></i>No. RM: {{ $pasienData->no_rm ?? '-' }}</span>
                <span style="background:rgba(255,255,255,0.15);padding:3px 11px;border-radius:6px;font-size:11px;font-weight:600"><i class="fas fa-{{ $pasienData->satusehat_id ? 'circle-check' : 'clock' }}" style="margin-right:5px"></i>{{ $pasienData->satusehat_id ? 'SatuSehat Sync' : 'SatuSehat Pending' }}</span>
            </div>
            @endif
        </div>
    </div>
    <a href="{{ route('pasien.pendaftaran.create') }}" style="background:white;color:#15803d;padding:10px 20px;border-radius:10px;font-size:13px;font-weight:700;text-decoration:none;display:flex;align-items:center;gap:7px;flex-shrink:0;white-space:nowrap">
        <i class="fas fa-plus"></i> Daftar Berobat
    </a>
</div>

{{-- STAT CARDS --}}
<div class="grid grid-3" style="margin-bottom:22px">
    <div class="stat-card">
        <div class="stat-icon" style="background:#dcfce7"><i class="fas fa-clipboard-list" style="color:#16a34a"></i></div>
        <div>
            <div class="stat-value">{{ $stats['total_pendaftaran'] ?? 0 }}</div>
            <div class="stat-label">Total Pendaftaran</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#dbeafe"><i class="fas fa-clock" style="color:#1d4ed8"></i></div>
        <div>
            <div class="stat-value">{{ $stats['menunggu'] ?? 0 }}</div>
            <div class="stat-label">Menunggu Dilayani</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fef3c7"><i class="fas fa-circle-check" style="color:#d97706"></i></div>
        <div>
            <div class="stat-value">{{ $stats['selesai'] ?? 0 }}</div>
            <div class="stat-label">Kunjungan Selesai</div>
        </div>
    </div>
</div>

<div class="grid grid-2" style="gap:16px">
    {{-- PENDAFTARAN AKTIF --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-ticket" style="color:#1d4ed8"></i> Tiket Antrean Aktif</div>
            <a href="{{ route('pasien.pendaftaran.index') }}" class="btn btn-outline btn-sm">Lihat Semua</a>
        </div>
        @if(isset($pendaftaran_aktif) && $pendaftaran_aktif->count() > 0)
            @foreach($pendaftaran_aktif as $p)
            <div style="padding:14px 18px;border-bottom:1px solid #f9fafb">
                <div style="display:flex;align-items:center;justify-content:space-between;gap:10px">
                    <div style="display:flex;align-items:center;gap:10px">
                        <div style="width:38px;height:38px;background:linear-gradient(135deg,#dbeafe,#bfdbfe);border-radius:9px;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:14px;color:#1d4ed8;flex-shrink:0">#{{ $p->no_antrian }}</div>
                        <div>
                            <div style="font-weight:700;font-size:13px">{{ $p->poli->nama ?? '-' }}</div>
                            <div style="font-size:11px;color:#9ca3af">{{ optional($p->tanggal_kunjungan)->format('d/m/Y') }} · {{ $p->jam_kunjungan }} WIB</div>
                        </div>
                    </div>
                    <span class="badge badge-{{ $p->status==='menunggu'?'warning':($p->status==='dipanggil'?'info':'success') }}">{{ ucfirst($p->status) }}</span>
                </div>
            </div>
            @endforeach
        @else
        <div style="padding:36px;text-align:center;color:#9ca3af">
            <i class="fas fa-ticket" style="font-size:28px;display:block;margin-bottom:8px;color:#d1d5db"></i>
            <div style="font-size:13px;margin-bottom:12px">Belum ada pendaftaran aktif</div>
            <a href="{{ route('pasien.pendaftaran.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Daftar Sekarang</a>
        </div>
        @endif
    </div>

    {{-- PROFIL & RIWAYAT --}}
    <div style="display:flex;flex-direction:column;gap:14px">
        {{-- INFO PASIEN --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fas fa-id-card" style="color:#16a34a"></i> Data Pasien Anda</div>
                <a href="{{ route('pasien.profil') }}" class="btn btn-outline btn-sm"><i class="fas fa-pen-to-square"></i> Edit</a>
            </div>
            @if($pasienData)
            <div style="padding:16px 18px">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                    <div style="padding:10px 12px;background:#f9fafb;border-radius:9px">
                        <div style="font-size:10px;color:#9ca3af;font-weight:600;text-transform:uppercase;letter-spacing:0.5px">Nama Lengkap</div>
                        <div style="font-size:13px;font-weight:600;color:#111827;margin-top:3px">{{ $pasienData->nama_lengkap }}</div>
                    </div>
                    <div style="padding:10px 12px;background:#f9fafb;border-radius:9px">
                        <div style="font-size:10px;color:#9ca3af;font-weight:600;text-transform:uppercase;letter-spacing:0.5px">No. RM</div>
                        <div style="font-size:13px;font-weight:700;color:#1d4ed8;margin-top:3px">{{ $pasienData->no_rm ?? '-' }}</div>
                    </div>
                    <div style="padding:10px 12px;background:#f9fafb;border-radius:9px">
                        <div style="font-size:10px;color:#9ca3af;font-weight:600;text-transform:uppercase;letter-spacing:0.5px">NIK</div>
                        <div style="font-size:12px;font-weight:500;color:#374151;margin-top:3px;font-family:monospace">{{ $pasienData->nik }}</div>
                    </div>
                    <div style="padding:10px 12px;background:#f9fafb;border-radius:9px">
                        <div style="font-size:10px;color:#9ca3af;font-weight:600;text-transform:uppercase;letter-spacing:0.5px">Status</div>
                        <div style="margin-top:4px"><span class="badge badge-{{ $pasienData->satusehat_id ? 'success' : 'warning' }}">{{ $pasienData->satusehat_id ? '✅ Terdaftar' : '⏳ Pending' }}</span></div>
                    </div>
                </div>
            </div>
            @else
            <div style="padding:20px 18px">
                <div class="alert alert-warning" style="margin:0"><i class="fas fa-triangle-exclamation"></i> Profil pasien belum lengkap. <a href="{{ route('pasien.profil') }}" style="color:#92400e;font-weight:700">Lengkapi sekarang →</a></div>
            </div>
            @endif
        </div>

        {{-- RIWAYAT TERAKHIR --}}
        <div class="card" style="flex:1">
            <div class="card-header">
                <div class="card-title"><i class="fas fa-clock-rotate-left" style="color:#6b7280"></i> Riwayat Terakhir</div>
                <a href="{{ route('pasien.riwayat') }}" class="btn btn-outline btn-sm">Semua</a>
            </div>
            @if(isset($riwayat_terakhir) && $riwayat_terakhir->count() > 0)
                @foreach($riwayat_terakhir as $r)
                <div style="padding:11px 18px;border-bottom:1px solid #f9fafb;display:flex;align-items:center;justify-content:space-between;gap:10px">
                    <div>
                        <div style="font-size:13px;font-weight:600">{{ $r->poli->nama ?? '-' }}</div>
                        <div style="font-size:11px;color:#9ca3af">{{ optional($r->tanggal_kunjungan)->format('d/m/Y') }}</div>
                    </div>
                    <span class="badge badge-{{ $r->status==='selesai'?'success':($r->status==='batal'?'danger':'warning') }}">{{ ucfirst($r->status) }}</span>
                </div>
                @endforeach
            @else
            <div style="padding:24px;text-align:center;color:#9ca3af;font-size:13px">
                <i class="fas fa-inbox" style="display:block;font-size:24px;margin-bottom:6px;color:#d1d5db"></i>
                Belum ada riwayat kunjungan
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
