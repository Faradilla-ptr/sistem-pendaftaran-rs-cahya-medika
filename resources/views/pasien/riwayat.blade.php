@extends('layouts.app')
@section('title', 'Riwayat Kunjungan - RS Cahya Medika')
@section('page-title', 'Riwayat Kunjungan')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
    <div>
        <h2 style="font-size:18px;font-weight:800;color:#0c4a6e">Riwayat Kunjungan</h2>
        <p style="font-size:13px;color:#64748b">Semua riwayat kunjungan berobat Anda</p>
    </div>
    <a href="{{ route('pasien.pendaftaran.create') }}" class="btn btn-accent">
        <i class="fas fa-plus"></i> Daftar Berobat Baru
    </a>
</div>

<div class="card">
    <div class="card-body" style="padding:0">
        @forelse($riwayat as $r)
        <div style="padding:18px 22px;border-bottom:1px solid #f1f5f9;display:flex;align-items:flex-start;gap:16px">
            <!-- Date -->
            <div style="width:54px;text-align:center;flex-shrink:0">
                <div style="background:{{ $r->status === 'selesai' ? '#d1fae5' : ($r->status === 'batal' ? '#fee2e2' : '#fef3c7') }};border-radius:12px;padding:8px">
                    <div style="font-size:18px;font-weight:900;color:{{ $r->status === 'selesai' ? '#065f46' : ($r->status === 'batal' ? '#991b1b' : '#92400e') }};line-height:1">
                        {{ $r->tanggal_kunjungan->format('d') }}
                    </div>
                    <div style="font-size:10px;color:#94a3b8;font-weight:700;text-transform:uppercase">
                        {{ $r->tanggal_kunjungan->format('M Y') }}
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div style="flex:1">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;flex-wrap:wrap">
                    <span style="font-weight:800;font-size:15px;color:#0c4a6e">{{ $r->poli->nama ?? '-' }}</span>
                    <span class="badge badge-{{ $r->status_color }}">{{ $r->status_label }}</span>
                    <span class="badge badge-{{ $r->jenis_kunjungan === 'baru' ? 'info' : 'secondary' }}" style="font-size:10px">
                        {{ $r->jenis_kunjungan === 'baru' ? '🆕 Baru' : '🔄 Kontrol' }}
                    </span>
                </div>
                <div style="font-size:13px;color:#475569;margin-bottom:4px">
                    👨‍⚕️ {{ $r->dokter->nama_lengkap ?? '-' }}
                </div>
                <div style="font-size:12px;color:#94a3b8">
                    ⏰ {{ $r->jam_kunjungan }} WIB &nbsp;·&nbsp;
                    🎟️ Antrian #{{ $r->no_antrian }} &nbsp;·&nbsp;
                    📄 {{ $r->kode_booking }}
                </div>
                @if($r->keluhan)
                <div style="margin-top:8px;padding:8px 12px;background:#f8fafc;border-radius:8px;font-size:12px;color:#475569;line-height:1.5">
                    <span style="font-weight:700;color:#94a3b8">Keluhan:</span> {{ Str::limit($r->keluhan, 100) }}
                </div>
                @endif

                @if($r->tekanan_darah || $r->suhu)
                <div style="margin-top:8px;display:flex;gap:10px;flex-wrap:wrap">
                    @if($r->tekanan_darah)
                    <span style="background:#e0f2fe;padding:4px 10px;border-radius:8px;font-size:11px;font-weight:600;color:#0c4a6e">
                        🩺 {{ $r->tekanan_darah }} mmHg
                    </span>
                    @endif
                    @if($r->suhu)
                    <span style="background:#fef3c7;padding:4px 10px;border-radius:8px;font-size:11px;font-weight:600;color:#92400e">
                        🌡️ {{ $r->suhu }}°C
                    </span>
                    @endif
                    @if($r->berat_badan)
                    <span style="background:#f0fdf4;padding:4px 10px;border-radius:8px;font-size:11px;font-weight:600;color:#065f46">
                        ⚖️ {{ $r->berat_badan }} kg
                    </span>
                    @endif
                </div>
                @endif
            </div>

            <!-- SatuSehat + Action -->
            <div style="text-align:right;flex-shrink:0">
                @if($r->satusehat_status === 'success')
                    <span class="ss-badge success" style="font-size:10px;display:block;margin-bottom:6px">
                        <i class="fas fa-check" style="font-size:8px"></i> SatuSehat
                    </span>
                @endif
                <a href="{{ route('pasien.pendaftaran.show', $r->id) }}" class="btn btn-outline btn-sm">
                    <i class="fas fa-eye"></i> Detail
                </a>
            </div>
        </div>
        @empty
        <div style="padding:60px;text-align:center;color:#94a3b8">
            <div style="font-size:52px;margin-bottom:16px">📭</div>
            <div style="font-size:16px;font-weight:700;color:#64748b;margin-bottom:8px">Belum ada riwayat kunjungan</div>
            <div style="font-size:13px;margin-bottom:20px">Mulai daftar berobat untuk membuat riwayat kunjungan</div>
            <a href="{{ route('pasien.pendaftaran.create') }}" class="btn btn-accent">
                <i class="fas fa-plus"></i> Daftar Berobat Sekarang
            </a>
        </div>
        @endforelse
    </div>
</div>

@if($riwayat->hasPages())
<div style="display:flex;justify-content:center;margin-top:20px">
    {{ $riwayat->links() }}
</div>
@endif
@endsection
