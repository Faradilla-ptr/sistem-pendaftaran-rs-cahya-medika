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

<!-- STATS ROW 1 -->
<div class="grid grid-4" style="margin-bottom:20px">
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

<div class="grid grid-2" style="gap:24px;margin-bottom:20px">
    <!-- PENDAFTARAN HARI INI -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">📋 Antrian Hari Ini</div>
            <a href="{{ route('admin.pendaftaran.index') }}" class="btn btn-outline btn-sm">Lihat Semua</a>
        </div>
        <div class="card-body" style="padding:0">
            @forelse($pendaftaran_hari_ini as $p)
            <div style="padding:14px 20px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:12px">
                <!-- Nomor Antrian -->
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
                            <button type="submit" class="btn btn-sm" style="padding:4px 10px;background:#e0f2fe;color:#0c4a6e;border:none;border-radius:6px;font-size:11px;font-weight:600;cursor:pointer">
                                Panggil
                            </button>
                        @elseif($p->status === 'dipanggil')
                            <input type="hidden" name="status" value="selesai">
                            <button type="submit" class="btn btn-sm" style="padding:4px 10px;background:#d1fae5;color:#065f46;border:none;border-radius:6px;font-size:11px;font-weight:600;cursor:pointer">
                                Selesai
                            </button>
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
                <div style="margin-top:14px;padding:10px 14px;background:#f0f9ff;border:1px solid #bae6fd;border-radius:10px;font-size:12px;color:#0369a1;display:flex;align-items:center;gap:8px">
                    <i class="fas fa-info-circle"></i>
                    Data kunjungan dikirim otomatis ke platform SatuSehat Kemenkes RI via FHIR API
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
