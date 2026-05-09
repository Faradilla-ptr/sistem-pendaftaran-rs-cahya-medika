@extends('layouts.app')
@section('title', 'Dashboard Pasien - RS Cahya Medika')
@section('page-title', 'Beranda')

@section('content')
<div style="margin-bottom:24px">
    <h2 style="font-size:22px;font-weight:800;color:#0c4a6e">
        Selamat Datang, {{ $pasien->nama_lengkap ?? auth()->user()->name }} 👋
    </h2>
    <p style="color:#64748b;font-size:14px;margin-top:4px">
        No. Rekam Medis: <strong>{{ $pasien->no_rm ?? '-' }}</strong> &nbsp;|&nbsp;
        NIK: <strong>{{ $pasien->nik ?? '-' }}</strong>
    </p>
</div>

<!-- QUICK STATS -->
<div class="grid grid-4" style="margin-bottom:24px">
    <div class="stat-card">
        <div class="stat-icon" style="background:#e0f2fe">📋</div>
        <div>
            <div class="stat-value">{{ $pendaftaran_aktif->count() }}</div>
            <div class="stat-label">Pendaftaran Aktif</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#d1fae5">✅</div>
        <div>
            <div class="stat-value">{{ $riwayat->count() }}</div>
            <div class="stat-label">Kunjungan Selesai</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fef3c7">📅</div>
        <div>
            <div class="stat-value">{{ $pasien ? $pasien->pendaftaran()->count() : 0 }}</div>
            <div class="stat-label">Total Kunjungan</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fee2e2">❤️</div>
        <div>
            <div class="stat-value">{{ $pasien->golongan_darah ?? '-' }}</div>
            <div class="stat-label">Gol. Darah</div>
        </div>
    </div>
</div>

<div class="grid grid-2" style="gap:24px">
    <!-- KIRI: Pendaftaran Aktif -->
    <div style="display:flex;flex-direction:column;gap:20px">

        <!-- PENDAFTARAN AKTIF -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">🗓️ Pendaftaran Aktif & Mendatang</div>
                <a href="{{ route('pasien.pendaftaran.create') }}" class="btn btn-accent btn-sm">
                    <i class="fas fa-plus"></i> Daftar Baru
                </a>
            </div>
            <div class="card-body" style="padding:0">
                @forelse($pendaftaran_aktif as $p)
                <div style="padding:16px 20px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:14px">
                    <div style="width:48px;height:48px;background:#e0f2fe;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0">🏥</div>
                    <div style="flex:1">
                        <div style="font-weight:700;font-size:14px;color:#0c4a6e">{{ $p->poli->nama ?? '-' }}</div>
                        <div style="font-size:12px;color:#64748b;margin-top:2px">{{ $p->dokter->nama_lengkap ?? '-' }}</div>
                        <div style="font-size:12px;color:#94a3b8;margin-top:2px">
                            📅 {{ $p->tanggal_kunjungan->format('d M Y') }} · ⏰ {{ $p->jam_kunjungan }}
                        </div>
                    </div>
                    <div style="text-align:right">
                        <span class="badge badge-{{ $p->status_color }}">{{ $p->status_label }}</span>
                        <div style="font-size:11px;color:#94a3b8;margin-top:4px">#{{ $p->no_antrian }}</div>
                        <a href="{{ route('pasien.pendaftaran.show', $p->id) }}" style="font-size:11px;color:#0891b2;text-decoration:none;margin-top:4px;display:block">Detail →</a>
                    </div>
                </div>
                @empty
                <div style="padding:40px;text-align:center;color:#94a3b8">
                    <div style="font-size:40px;margin-bottom:12px">📭</div>
                    <div style="font-weight:600;color:#64748b">Tidak ada pendaftaran aktif</div>
                    <div style="font-size:12px;margin-top:4px">Klik "Daftar Baru" untuk membuat pendaftaran</div>
                </div>
                @endforelse
            </div>
        </div>

        <!-- INFO PASIEN -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">👤 Informasi Pasien</div>
                <a href="{{ route('pasien.profil') }}" class="btn btn-outline btn-sm">
                    <i class="fas fa-edit"></i> Edit
                </a>
            </div>
            <div class="card-body" style="padding:16px 20px">
                @if($pasien)
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                    <div>
                        <div style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.5px">No. RM</div>
                        <div style="font-size:13px;font-weight:700;color:#0c4a6e;margin-top:2px">{{ $pasien->no_rm }}</div>
                    </div>
                    <div>
                        <div style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.5px">Umur</div>
                        <div style="font-size:13px;font-weight:600;margin-top:2px">{{ $pasien->umur }} tahun</div>
                    </div>
                    <div>
                        <div style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.5px">Jenis Kelamin</div>
                        <div style="font-size:13px;font-weight:600;margin-top:2px">{{ $pasien->jenis_kelamin_label }}</div>
                    </div>
                    <div>
                        <div style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.5px">No. HP</div>
                        <div style="font-size:13px;font-weight:600;margin-top:2px">{{ $pasien->no_hp ?? '-' }}</div>
                    </div>
                    <div style="grid-column:1/-1">
                        <div style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.5px">Alamat</div>
                        <div style="font-size:13px;font-weight:600;margin-top:2px">{{ Str::limit($pasien->alamat ?? '-', 60) }}</div>
                    </div>
                </div>
                @if($pasien->satusehat_id)
                <div style="margin-top:14px;padding:10px 12px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;display:flex;align-items:center;gap:8px">
                    <span>✅</span>
                    <div style="font-size:11px;color:#166534;font-weight:600">Terdaftar di SatuSehat</div>
                    <code style="font-size:10px;color:#14532d;margin-left:auto">{{ $pasien->satusehat_id }}</code>
                </div>
                @else
                <div style="margin-top:14px;padding:10px 12px;background:#fffbeb;border:1px solid #fde68a;border-radius:10px;display:flex;align-items:center;gap:8px">
                    <span>⚠️</span>
                    <div style="font-size:11px;color:#92400e;font-weight:600">Belum sinkronisasi ke SatuSehat. Lengkapi profil Anda.</div>
                </div>
                @endif
                @else
                <div style="text-align:center;padding:20px;color:#94a3b8">
                    <div style="margin-bottom:8px;font-size:28px">📝</div>
                    <div style="font-weight:600;color:#64748b">Profil belum lengkap</div>
                    <a href="{{ route('pasien.profil') }}" style="font-size:12px;color:#0891b2">Lengkapi sekarang →</a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- KANAN: Riwayat Kunjungan (dengan detail) -->
    <div>
        <div class="card">
            <div class="card-header">
                <div class="card-title">🕒 Riwayat Kunjungan</div>
                <a href="{{ route('pasien.riwayat') }}" class="btn btn-outline btn-sm">Lihat Semua</a>
            </div>
            <div class="card-body" style="padding:0">
                @forelse($riwayat as $r)
                <div style="padding:16px 20px;border-bottom:1px solid #f1f5f9">
                    <div style="display:flex;align-items:flex-start;gap:12px">
                        <!-- Icon -->
                        <div style="width:40px;height:40px;background:#d1fae5;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0;margin-top:2px">✅</div>

                        <!-- Detail -->
                        <div style="flex:1;min-width:0">
                            <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;margin-bottom:4px">
                                <div style="font-weight:700;font-size:13px;color:#0c4a6e">{{ $r->poli->nama ?? '-' }}</div>
                                <span class="badge badge-success" style="font-size:10px;flex-shrink:0">Selesai</span>
                            </div>

                            <div style="font-size:12px;color:#64748b;margin-bottom:4px">
                                <i class="fas fa-user-md" style="width:14px;color:#94a3b8"></i>
                                {{ $r->dokter->nama_lengkap ?? '-' }}
                            </div>

                            <div style="display:flex;gap:16px;font-size:11px;color:#94a3b8">
                                <span><i class="fas fa-calendar" style="margin-right:4px"></i>{{ $r->tanggal_kunjungan->format('d M Y') }}</span>
                                <span><i class="fas fa-clock" style="margin-right:4px"></i>{{ $r->jam_kunjungan }} WIB</span>
                                <span class="badge badge-{{ $r->jenis_kunjungan === 'baru' ? 'info' : 'secondary' }}" style="font-size:9px">
                                    {{ $r->jenis_kunjungan === 'baru' ? 'Baru' : 'Kontrol' }}
                                </span>
                            </div>

                            @if($r->keluhan)
                            <div style="margin-top:6px;padding:6px 10px;background:#f8fafc;border-radius:6px;font-size:11px;color:#64748b;border-left:3px solid #e2e8f0">
                                <strong style="color:#475569">Keluhan:</strong> {{ Str::limit($r->keluhan, 70) }}
                            </div>
                            @endif

                            @if($r->tekanan_darah || $r->suhu || $r->berat_badan)
                            <div style="margin-top:6px;display:flex;gap:10px;flex-wrap:wrap">
                                @if($r->tekanan_darah)
                                <span style="font-size:10px;background:#e0f2fe;color:#0891b2;padding:2px 8px;border-radius:5px">
                                    💓 {{ $r->tekanan_darah }} mmHg
                                </span>
                                @endif
                                @if($r->suhu)
                                <span style="font-size:10px;background:#fef3c7;color:#92400e;padding:2px 8px;border-radius:5px">
                                    🌡️ {{ $r->suhu }}°C
                                </span>
                                @endif
                                @if($r->berat_badan)
                                <span style="font-size:10px;background:#f1f5f9;color:#475569;padding:2px 8px;border-radius:5px">
                                    ⚖️ {{ $r->berat_badan }} kg
                                </span>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div style="padding:40px;text-align:center;color:#94a3b8">
                    <div style="font-size:36px;margin-bottom:10px">📋</div>
                    <div style="font-weight:600;color:#64748b">Belum ada riwayat kunjungan</div>
                </div>
                @endforelse
            </div>
            @if($riwayat->count() >= 5)
            <div style="padding:12px 20px;border-top:1px solid #f1f5f9;text-align:center">
                <a href="{{ route('pasien.riwayat') }}" style="font-size:12px;color:#0891b2;text-decoration:none;font-weight:600">
                    Lihat semua riwayat kunjungan →
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- INFO NON BPJS -->
<div style="margin-top:24px;background:linear-gradient(135deg,#0c4a6e,#0891b2);border-radius:16px;padding:24px 28px;display:flex;align-items:center;gap:20px;color:white">
    <div style="font-size:40px;flex-shrink:0">ℹ️</div>
    <div>
        <div style="font-size:16px;font-weight:800;margin-bottom:4px">Informasi Penting - Layanan Non-BPJS</div>
        <div style="font-size:13px;opacity:0.8;line-height:1.6">
            RS Cahya Medika Bondowoso adalah rumah sakit swasta yang baru berdiri dan <strong>belum bekerjasama dengan BPJS Kesehatan</strong>. Biaya konsultasi dokter umum mulai <strong>Rp 150.000</strong> dan spesialis mulai <strong>Rp 200.000</strong>. Informasi: <strong>0332-123456</strong>.
        </div>
    </div>
</div>
@endsection
