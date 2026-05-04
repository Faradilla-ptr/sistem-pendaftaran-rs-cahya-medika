@extends('layouts.app')
@section('title', 'SatuSehat API - RS Cahya Medika')
@section('page-title', 'Integrasi SatuSehat API')

@section('content')

<!-- HEADER -->
<div style="background:linear-gradient(135deg,#0c4a6e,#0891b2);border-radius:18px;padding:24px 28px;color:white;margin-bottom:24px;display:flex;align-items:center;gap:20px">
    <div style="font-size:48px;flex-shrink:0">🔗</div>
    <div>
        <div style="font-size:18px;font-weight:800;margin-bottom:4px">SatuSehat Platform Kemenkes RI</div>
        <div style="font-size:13px;opacity:0.75">Integrasi FHIR R4 API untuk pertukaran data kesehatan nasional</div>
        <div style="display:flex;gap:10px;margin-top:10px">
            <span style="background:rgba(255,255,255,0.15);padding:4px 12px;border-radius:8px;font-size:11px;font-weight:600">
                🌐 Staging / Sandbox Mode
            </span>
            <span style="background:rgba(6,182,212,0.3);border:1px solid rgba(6,182,212,0.5);padding:4px 12px;border-radius:8px;font-size:11px;font-weight:600">
                FHIR R4 Compliant
            </span>
        </div>
    </div>
</div>

<!-- STATS -->
<div class="grid grid-4" style="margin-bottom:24px">
    <div class="stat-card">
        <div class="stat-icon" style="background:#e0f2fe">📊</div>
        <div>
            <div class="stat-value">{{ $stats['total'] }}</div>
            <div class="stat-label">Total Encounter</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#d1fae5">✅</div>
        <div>
            <div class="stat-value">{{ $stats['success'] }}</div>
            <div class="stat-label">Berhasil Sync</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fef3c7">⏳</div>
        <div>
            <div class="stat-value">{{ $stats['pending'] }}</div>
            <div class="stat-label">Pending</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fee2e2">❌</div>
        <div>
            <div class="stat-value">{{ $stats['failed'] }}</div>
            <div class="stat-label">Gagal</div>
        </div>
    </div>
</div>

<div class="grid grid-2" style="gap:24px;align-items:start">

    <!-- KONFIGURASI API -->
    <div class="card">
        <div class="card-header"><div class="card-title">⚙️ Konfigurasi API</div></div>
        <div class="card-body">
            <div style="display:grid;gap:14px">
                @foreach([
                    ['Base URL', config('satusehat.base_url'), 'fas fa-link'],
                    ['Auth URL', config('satusehat.auth_url'), 'fas fa-key'],
                    ['Client ID', config('satusehat.client_id') ? substr(config('satusehat.client_id'),0,10).'...' : '(tidak dikonfigurasi)', 'fas fa-id-card'],
                    ['Organization ID', config('satusehat.organization_id') ?: '(tidak dikonfigurasi)', 'fas fa-building'],
                ] as [$label, $val, $icon])
                <div style="padding:12px 14px;background:#f8fafc;border-radius:10px">
                    <div style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px">
                        <i class="{{ $icon }}" style="margin-right:4px"></i>{{ $label }}
                    </div>
                    <code style="font-size:12px;color:#0891b2;word-break:break-all">{{ $val }}</code>
                </div>
                @endforeach
            </div>

            <div style="margin-top:16px;padding:12px 14px;background:#fffbeb;border:1px solid #fde68a;border-radius:10px;font-size:12px;color:#92400e">
                <i class="fas fa-info-circle" style="margin-right:4px"></i>
                <strong>Mode Sandbox:</strong> Menggunakan environment staging SatuSehat. Data tidak masuk ke sistem produksi. Ubah di file <code>.env</code> untuk konfigurasi.
            </div>
        </div>
    </div>

    <!-- RESOURCE INFO -->
    <div class="card">
        <div class="card-header"><div class="card-title">📦 FHIR Resources</div></div>
        <div class="card-body">
            <div style="display:grid;gap:8px">
                @foreach([
                    ['Patient', 'Data pasien / demografi', '👤', 'success'],
                    ['Practitioner', 'Data dokter / tenaga medis', '👨‍⚕️', 'success'],
                    ['Organization', 'Data RS Cahya Medika', '🏥', 'success'],
                    ['Encounter', 'Data kunjungan / pendaftaran', '📋', 'success'],
                    ['Condition', 'Diagnosa penyakit', '🩺', 'warning'],
                    ['Observation', 'Tanda vital & hasil lab', '💉', 'warning'],
                ] as [$resource, $desc, $icon, $status])
                <div style="display:flex;align-items:center;gap:12px;padding:10px 14px;background:#f8fafc;border-radius:10px">
                    <span style="font-size:18px">{{ $icon }}</span>
                    <div style="flex:1">
                        <div style="font-weight:700;font-size:13px">{{ $resource }}</div>
                        <div style="font-size:11px;color:#64748b">{{ $desc }}</div>
                    </div>
                    <span class="badge badge-{{ $status }}" style="font-size:10px">
                        {{ $status === 'success' ? '✅ Aktif' : '🔜 Segera' }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- PENDAFTARAN GAGAL -->
@if($pendaftaran_failed->count() > 0)
<div class="card" style="margin-top:24px">
    <div class="card-header">
        <div class="card-title">❌ Pendaftaran Gagal Sinkronisasi</div>
        <span class="badge badge-danger">{{ $pendaftaran_failed->count() }} data</span>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Kode Booking</th>
                    <th>Pasien</th>
                    <th>Poli</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendaftaran_failed as $p)
                <tr>
                    <td><code style="font-size:12px">{{ $p->kode_booking }}</code></td>
                    <td>{{ $p->pasien->nama_lengkap ?? '-' }}</td>
                    <td>{{ $p->poli->nama ?? '-' }}</td>
                    <td>{{ $p->tanggal_kunjungan->format('d/m/Y') }}</td>
                    <td>
                        <form action="{{ route('admin.satusehat.sync', $p->id) }}" method="POST" style="display:inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline">
                                <i class="fas fa-redo"></i> Sync Ulang
                            </button>
                        </form>
                        <a href="{{ route('admin.pendaftaran.show', $p->id) }}" class="btn btn-sm btn-outline">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<!-- DOKUMENTASI API -->
<div class="card" style="margin-top:24px">
    <div class="card-header"><div class="card-title">📚 Dokumentasi & Referensi API</div></div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px">
            @foreach([
                ['Portal SatuSehat', 'https://satusehat.kemkes.go.id', 'Portal resmi platform SatuSehat Kemenkes RI', '🌐'],
                ['Dokumentasi FHIR', 'https://satusehat.kemkes.go.id/platform/docs/id', 'Panduan implementasi FHIR R4 API SatuSehat', '📖'],
                ['Postman Collection', 'https://satusehat.kemkes.go.id/platform/docs/id', 'Download Postman collection untuk testing API', '📮'],
            ] as [$title, $url, $desc, $icon])
            <a href="{{ $url }}" target="_blank" style="text-decoration:none;padding:16px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;display:block;transition:all 0.2s" onmouseover="this.style.background='#e0f2fe'" onmouseout="this.style.background='#f8fafc'">
                <div style="font-size:24px;margin-bottom:8px">{{ $icon }}</div>
                <div style="font-weight:700;font-size:13px;color:#0c4a6e;margin-bottom:4px">{{ $title }}</div>
                <div style="font-size:11px;color:#64748b">{{ $desc }}</div>
                <div style="font-size:11px;color:#0891b2;margin-top:8px">Buka <i class="fas fa-external-link-alt" style="font-size:9px"></i></div>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endsection
