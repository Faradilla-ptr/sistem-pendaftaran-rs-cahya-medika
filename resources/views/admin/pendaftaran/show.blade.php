@extends('layouts.app')
@section('title', 'Detail Pendaftaran - RS Cahya Medika')
@section('page-title', 'Detail Pendaftaran')

@section('content')
@php 
    $r = auth()->user()->role === 'rekam_medis' ? 'rekam_medis.' : (auth()->user()->role === 'pendaftaran' ? 'pendaftaran.' : 'admin.'); 
@endphp

<div style="max-width:900px;margin:0 auto">

<!-- BREADCRUMB -->
<div style="display:flex;align-items:center;gap:8px;margin-bottom:20px;font-size:13px;color:#64748b">
    <a href="{{ route($r . 'dashboard') }}" style="color:#0891b2;text-decoration:none">Dashboard</a>
    <i class="fas fa-chevron-right" style="font-size:10px"></i>
    <a href="{{ route($r . 'pendaftaran.index') }}" style="color:#0891b2;text-decoration:none">Pendaftaran</a>
    <i class="fas fa-chevron-right" style="font-size:10px"></i>
    <span>{{ $pendaftaran->kode_booking }}</span>
</div>

<div class="grid grid-2" style="gap:20px;align-items:start">

    <!-- LEFT -->
    <div style="display:flex;flex-direction:column;gap:20px">

        <!-- BOOKING INFO -->
        <div style="background:linear-gradient(135deg,#0c4a6e,#0891b2);border-radius:18px;padding:24px;color:white">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:20px">
                <div>
                    <div style="font-size:11px;opacity:0.65;text-transform:uppercase;letter-spacing:1px">Kode Booking</div>
                    <div style="font-size:22px;font-weight:900;letter-spacing:2px;margin-top:2px">{{ $pendaftaran->kode_booking }}</div>
                </div>
                <div style="text-align:center">
                    <div style="font-size:10px;opacity:0.65;margin-bottom:4px">Antrian</div>
                    <div style="width:52px;height:52px;background:rgba(255,255,255,0.15);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:22px;font-weight:900">
                        {{ $pendaftaran->no_antrian }}
                    </div>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
                <div><div style="font-size:10px;opacity:0.6;margin-bottom:2px">Poli</div><div style="font-size:13px;font-weight:700">{{ $pendaftaran->poli->nama ?? '-' }}</div></div>
                <div><div style="font-size:10px;opacity:0.6;margin-bottom:2px">Dokter</div><div style="font-size:13px;font-weight:700">{{ $pendaftaran->dokter->nama_lengkap ?? '-' }}</div></div>
                <div><div style="font-size:10px;opacity:0.6;margin-bottom:2px">Tanggal</div><div style="font-size:13px;font-weight:700">{{ $pendaftaran->tanggal_kunjungan->format('d M Y') }}</div></div>
                <div><div style="font-size:10px;opacity:0.6;margin-bottom:2px">Jam</div><div style="font-size:13px;font-weight:700">{{ $pendaftaran->jam_kunjungan }} WIB</div></div>
            </div>
        </div>

        <!-- DATA PASIEN -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">👤 Data Pasien</div>
                <div style="display:flex;gap:6px">
                    <a href="{{ route($r . 'pendaftaran.pdf', $pendaftaran->id) }}" target="_blank" class="btn btn-outline btn-sm" style="color:#dc2626;border-color:#fca5a5;background:#fef2f2">
                        <i class="fas fa-file-pdf"></i> Cetak Form PDF
                    </a>
                    @if(in_array(auth()->user()->role, ['admin', 'pendaftaran']))
                    <a href="{{ route($r . 'pasien.edit', $pendaftaran->pasien->id) }}" class="btn btn-outline btn-sm">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div style="display:grid;gap:10px">
                    @foreach([
                        ['No. RM', $pendaftaran->pasien->no_rm ?? '-'],
                        ['Nama Lengkap', $pendaftaran->pasien->nama_lengkap ?? '-'],
                        ['NIK / KTP', $pendaftaran->pasien->nik ?? '-'],
                        ['Tanggal Lahir', optional($pendaftaran->pasien->tanggal_lahir)->format('d M Y') . ' (' . ($pendaftaran->pasien->umur ?? '?') . ' tahun)'],
                        ['Jenis Kelamin', $pendaftaran->pasien->jenis_kelamin_label ?? '-'],
                        ['No. HP', $pendaftaran->pasien->no_hp ?? '-'],
                        ['Alamat', Str::limit($pendaftaran->pasien->alamat ?? '-', 60)],
                        ['Penanggung Jawab', $pendaftaran->pasien->nama_pj ?? '-'],
                    ] as [$label, $val])
                    <div style="display:flex;gap:10px">
                        <div style="font-size:11px;font-weight:700;color:#94a3b8;width:120px;flex-shrink:0;padding-top:1px">{{ $label }}</div>
                        <div style="font-size:13px;color:#1e293b;font-weight:500">{{ $val }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- KELUHAN -->
        <div class="card">
            <div class="card-header"><div class="card-title">🩺 Keluhan Pasien</div></div>
            <div class="card-body">
                <p style="font-size:14px;line-height:1.7">{{ $pendaftaran->keluhan }}</p>
                @if($pendaftaran->catatan_admin)
                <div style="margin-top:14px;padding:12px;background:#f8fafc;border-left:3px solid #0891b2;border-radius:0 8px 8px 0">
                    <div style="font-size:11px;font-weight:700;color:#0891b2;margin-bottom:4px">CATATAN ADMIN</div>
                    <div style="font-size:13px">{{ $pendaftaran->catatan_admin }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- RIGHT -->
    <div style="display:flex;flex-direction:column;gap:20px">

        <!-- UPDATE STATUS -->
        @if(in_array(auth()->user()->role, ['admin', 'pendaftaran']))
        <div class="card">
            <div class="card-header"><div class="card-title">🔄 Update Status</div></div>
            <div class="card-body">
                <form action="{{ route($r . 'pendaftaran.status', $pendaftaran->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <div class="form-group">
                        <label class="form-label">Status Kunjungan</label>
                        <select name="status" class="form-select">
                            <option value="menunggu" {{ $pendaftaran->status === 'menunggu' ? 'selected' : '' }}>⏳ Menunggu</option>
                            <option value="dipanggil" {{ $pendaftaran->status === 'dipanggil' ? 'selected' : '' }}>📢 Dipanggil</option>
                            <option value="selesai" {{ $pendaftaran->status === 'selesai' ? 'selected' : '' }}>✅ Selesai</option>
                            <option value="batal" {{ $pendaftaran->status === 'batal' ? 'selected' : '' }}>❌ Batal</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Catatan Admin</label>
                        <textarea name="catatan_admin" class="form-control" rows="2" placeholder="Catatan opsional...">{{ $pendaftaran->catatan_admin }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">
                        <i class="fas fa-save"></i> Update Status
                    </button>
                </form>
            </div>
        </div>
        @endif

        <!-- TANDA VITAL -->
        <div class="card">
            <div class="card-header"><div class="card-title">💉 Tanda Vital {{ in_array(auth()->user()->role, ['admin', 'pendaftaran']) ? '(Edit)' : '(Read-Only)' }}</div></div>
            <div class="card-body">
                @if(in_array(auth()->user()->role, ['admin', 'pendaftaran']))
                <form action="{{ route($r . 'pendaftaran.vital', $pendaftaran->id) }}" method="POST">
                    @csrf
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                        <div class="form-group">
                            <label class="form-label">Tekanan Darah</label>
                            <input type="text" name="tekanan_darah" class="form-control" value="{{ $pendaftaran->tekanan_darah }}" placeholder="120/80">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Suhu (°C)</label>
                            <input type="text" name="suhu" class="form-control" value="{{ $pendaftaran->suhu }}" placeholder="36.5">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nadi (bpm)</label>
                            <input type="text" name="nadi" class="form-control" value="{{ $pendaftaran->nadi }}" placeholder="80">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Respirasi</label>
                            <input type="text" name="respirasi" class="form-control" value="{{ $pendaftaran->respirasi }}" placeholder="18">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Berat Badan (kg)</label>
                            <input type="text" name="berat_badan" class="form-control" value="{{ $pendaftaran->berat_badan }}" placeholder="60">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tinggi Badan (cm)</label>
                            <input type="text" name="tinggi_badan" class="form-control" value="{{ $pendaftaran->tinggi_badan }}" placeholder="165">
                        </div>
                        <div class="form-group" style="grid-column:1/-1">
                            <label class="form-label">SpO2 (%)</label>
                            <input type="text" name="spo2" class="form-control" value="{{ $pendaftaran->spo2 }}" placeholder="98">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success" style="width:100%;justify-content:center">
                        <i class="fas fa-heartbeat"></i> Simpan Tanda Vital
                    </button>
                </form>
                @else
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                    <div><div style="font-size:11px;color:#94a3b8;font-weight:700">Tekanan Darah</div><div style="font-size:13px;font-weight:600">{{ $pendaftaran->tekanan_darah ?? '-' }}</div></div>
                    <div><div style="font-size:11px;color:#94a3b8;font-weight:700">Suhu</div><div style="font-size:13px;font-weight:600">{{ $pendaftaran->suhu ? $pendaftaran->suhu . ' °C' : '-' }}</div></div>
                    <div><div style="font-size:11px;color:#94a3b8;font-weight:700">Nadi</div><div style="font-size:13px;font-weight:600">{{ $pendaftaran->nadi ? $pendaftaran->nadi . ' bpm' : '-' }}</div></div>
                    <div><div style="font-size:11px;color:#94a3b8;font-weight:700">Respirasi</div><div style="font-size:13px;font-weight:600">{{ $pendaftaran->respirasi ?? '-' }}</div></div>
                    <div><div style="font-size:11px;color:#94a3b8;font-weight:700">Berat Badan</div><div style="font-size:13px;font-weight:600">{{ $pendaftaran->berat_badan ? $pendaftaran->berat_badan . ' kg' : '-' }}</div></div>
                    <div><div style="font-size:11px;color:#94a3b8;font-weight:700">Tinggi Badan</div><div style="font-size:13px;font-weight:600">{{ $pendaftaran->tinggi_badan ? $pendaftaran->tinggi_badan . ' cm' : '-' }}</div></div>
                    <div style="grid-column:1/-1"><div style="font-size:11px;color:#94a3b8;font-weight:700">SpO2</div><div style="font-size:13px;font-weight:600">{{ $pendaftaran->spo2 ? $pendaftaran->spo2 . ' %' : '-' }}</div></div>
                </div>
                @endif
            </div>
        </div>

        <!-- SATUSEHAT -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">🔗 SatuSehat API</div>
                <span class="ss-badge {{ $pendaftaran->satusehat_status }}">
                    {{ ucfirst($pendaftaran->satusehat_status) }}
                </span>
            </div>
            <div class="card-body">
                @if($pendaftaran->satusehat_encounter_id)
                <div style="margin-bottom:12px">
                    <div style="font-size:11px;font-weight:700;color:#64748b;margin-bottom:4px">ENCOUNTER ID</div>
                    <code style="font-size:11px;color:#0891b2;word-break:break-all">{{ $pendaftaran->satusehat_encounter_id }}</code>
                </div>
                @endif

                @if(in_array(auth()->user()->role, ['admin', 'rekam_medis']) && $pendaftaran->satusehat_status !== 'success')
                <form action="{{ route($r . 'satusehat.sync', $pendaftaran->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline" style="width:100%;justify-content:center">
                        <i class="fas fa-sync"></i> Sinkronisasi Ulang
                    </button>
                </form>
                @endif

                @if($pendaftaran->satusehat_response)
                <details style="margin-top:12px">
                    <summary style="font-size:12px;color:#64748b;cursor:pointer">Lihat Response API</summary>
                    <pre style="margin-top:8px;background:#f8fafc;padding:12px;border-radius:8px;font-size:10px;overflow:auto;max-height:200px">{{ json_encode($pendaftaran->satusehat_response, JSON_PRETTY_PRINT) }}</pre>
                </details>
                @endif
            </div>
        </div>
    </div>
</div>

<div style="margin-top:20px">
    <a href="{{ route($r . 'pendaftaran.index') }}" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar
    </a>
</div>
</div>
@endsection
