@extends('layouts.app')
@section('title', 'Riwayat Kunjungan - Portal Pasien RS Cahya Medika')
@section('page-title', 'Riwayat Kunjungan')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="fas fa-clock-rotate-left" style="color:var(--palette-medium);margin-right:6px"></i> Riwayat Kunjungan Berobat</div>
        <span class="badge badge-primary" style="font-size:12px">{{ isset($riwayat) ? $riwayat->count() : 0 }} Kunjungan</span>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Kode Booking</th>
                    <th>Poliklinik</th>
                    <th>Dokter</th>
                    <th>Tanggal Kunjungan</th>
                    <th>Jam</th>
                    <th>Jenis</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayat ?? [] as $r)
                <tr>
                    <td><code style="background:#f3f4f6;padding:3px 8px;border-radius:5px;font-size:11px">{{ $r->kode_booking }}</code></td>
                    <td style="font-weight:600;font-size:13px">{{ $r->poli->nama ?? '-' }}</td>
                    <td style="font-size:13px;color:#374151">{{ $r->dokter->nama_lengkap ?? '-' }}</td>
                    <td style="font-size:13px">{{ optional($r->tanggal_kunjungan)->format('d/m/Y') }}</td>
                    <td style="font-size:13px;color:#6b7280">{{ $r->jam_kunjungan }} WIB</td>
                    <td><span class="badge badge-{{ $r->jenis_kunjungan==='baru'?'primary':'secondary' }}">{{ $r->jenis_kunjungan==='baru'?'Baru':'Kontrol' }}</span></td>
                    <td>
                        <span class="badge badge-{{ $r->status==='selesai'?'success':($r->status==='batal'?'danger':($r->status==='dipanggil'?'info':'warning')) }}">
                            {{ ucfirst($r->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('pasien.pendaftaran.show', $r->id) }}" class="btn btn-outline btn-sm">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center;padding:40px;color:#9ca3af">
                    <i class="fas fa-clock-rotate-left" style="font-size:28px;display:block;margin-bottom:8px;color:#d1d5db"></i>
                    Belum ada riwayat kunjungan
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
