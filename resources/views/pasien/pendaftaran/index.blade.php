@extends('layouts.app')
@section('title', 'Pendaftaran Saya - RS Cahya Medika')
@section('page-title', 'Pendaftaran Saya')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px">
    <div>
        <h2 style="font-size:18px;font-weight:800;color:#0c4a6e">Daftar Pendaftaran</h2>
        <p style="font-size:13px;color:#64748b">Semua riwayat pendaftaran berobat Anda</p>
    </div>
    <a href="{{ route('pasien.pendaftaran.create') }}" class="btn btn-accent">
        <i class="fas fa-plus"></i> Daftar Berobat Baru
    </a>
</div>

<div class="card">
    <div class="card-body" style="padding:0">
        @forelse($pendaftaran as $p)
        <div style="padding:18px 22px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:16px">
            <!-- Date Box -->
            <div style="width:54px;text-align:center;flex-shrink:0">
                <div style="background:#e0f2fe;border-radius:12px;padding:8px">
                    <div style="font-size:18px;font-weight:900;color:#0c4a6e;line-height:1">{{ $p->tanggal_kunjungan->format('d') }}</div>
                    <div style="font-size:10px;color:#0891b2;font-weight:700;text-transform:uppercase">{{ $p->tanggal_kunjungan->format('M') }}</div>
                </div>
            </div>

            <!-- Info -->
            <div style="flex:1">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px">
                    <span style="font-weight:800;font-size:15px;color:#0c4a6e">{{ $p->poli->nama ?? '-' }}</span>
                    <span class="badge badge-{{ $p->status_color }}">{{ $p->status_label }}</span>
                </div>
                <div style="font-size:13px;color:#475569">
                    👨‍⚕️ {{ $p->dokter->nama_lengkap ?? '-' }}
                </div>
                <div style="font-size:12px;color:#94a3b8;margin-top:2px">
                    ⏰ {{ $p->jam_kunjungan }} WIB &nbsp;·&nbsp;
                    🎟️ Antrian {{ $p->no_antrian }} &nbsp;·&nbsp;
                    {{ $p->jenis_kunjungan === 'baru' ? '🆕 Pasien Baru' : '🔄 Kontrol' }}
                </div>
            </div>

            <!-- Booking Code & Action -->
            <div style="text-align:right;flex-shrink:0">
                <div style="font-size:11px;color:#94a3b8;margin-bottom:4px">Kode Booking</div>
                <div style="font-size:13px;font-weight:800;color:#0c4a6e;font-family:monospace">{{ $p->kode_booking }}</div>

                <!-- SatuSehat Status -->
                <div style="margin-top:6px">
                    @if($p->satusehat_status === 'success')
                        <span class="ss-badge success"><i class="fas fa-check-circle" style="font-size:9px"></i> SatuSehat</span>
                    @elseif($p->satusehat_status === 'pending')
                        <span class="ss-badge pending"><i class="fas fa-clock" style="font-size:9px"></i> Pending</span>
                    @else
                        <span class="ss-badge failed"><i class="fas fa-exclamation-circle" style="font-size:9px"></i> Gagal</span>
                    @endif
                </div>

                <div style="margin-top:8px">
                    <a href="{{ route('pasien.pendaftaran.show', $p->id) }}" class="btn btn-outline btn-sm">
                        <i class="fas fa-eye"></i> Detail
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div style="padding:60px;text-align:center;color:#94a3b8">
            <div style="font-size:52px;margin-bottom:16px">📋</div>
            <div style="font-size:16px;font-weight:700;color:#64748b;margin-bottom:8px">Belum ada pendaftaran</div>
            <div style="font-size:13px;margin-bottom:20px">Klik tombol di bawah untuk membuat pendaftaran berobat pertama Anda</div>
            <a href="{{ route('pasien.pendaftaran.create') }}" class="btn btn-accent">
                <i class="fas fa-plus"></i> Daftar Berobat Sekarang
            </a>
        </div>
        @endforelse
    </div>
</div>

@if($pendaftaran->hasPages())
<div style="display:flex;justify-content:center;margin-top:20px">
    {{ $pendaftaran->links() }}
</div>
@endif
@endsection
