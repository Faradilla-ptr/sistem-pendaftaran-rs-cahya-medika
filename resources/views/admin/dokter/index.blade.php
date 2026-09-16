@extends('layouts.app')
@section('title', 'Data Dokter Spesialis - RS Cahya Medika')
@section('page-title', 'Data Dokter & Jadwal Praktik')

@section('content')
<div class="page-header">
    <div>
        <div class="page-header-title">Data Dokter &amp; Jadwal Praktik</div>
        <div class="page-header-sub">Manajemen dokter, STR, NIK dan jadwal pelayanan poliklinik</div>
    </div>
    <a href="{{ route('admin.dokter.create') }}" class="btn btn-primary btn-lg">
        <i class="fas fa-plus"></i> Tambah Dokter
    </a>
</div>

<div class="card" style="margin-bottom:18px">
    <div class="card-body" style="padding:14px 18px">
        <form method="GET" action="{{ route('admin.dokter.index') }}">
            <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end">
                <div style="display:flex;flex-direction:column;gap:4px;min-width:180px">
                    <label style="font-size:11px;font-weight:600;color:#6b7280">Filter Poli</label>
                    <select name="poli_id" class="form-select" style="padding:6px 10px;font-size:12px">
                        <option value="">Semua Poli</option>
                        @foreach($poli as $p)
                            <option value="{{ $p->id }}" {{ request('poli_id')==$p->id?'selected':'' }}>{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display:flex;flex-direction:column;gap:4px;min-width:190px">
                    <label style="font-size:11px;font-weight:600;color:#6b7280">Spesialisasi</label>
                    <input type="text" name="spesialisasi" class="form-control" value="{{ request('spesialisasi') }}" placeholder="Cari spesialisasi..." style="padding:6px 10px;font-size:12px">
                </div>
                <div style="display:flex;flex-direction:column;gap:4px;min-width:150px">
                    <label style="font-size:11px;font-weight:600;color:#6b7280">Hari Praktik</label>
                    <select name="hari" class="form-select" style="padding:6px 10px;font-size:12px">
                        <option value="">Semua Hari</option>
                        @foreach(['senin','selasa','rabu','kamis','jumat','sabtu','minggu'] as $h)
                            <option value="{{ $h }}" {{ request('hari')===$h?'selected':'' }}>{{ ucfirst($h) }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display:flex;gap:6px;align-self:flex-end">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-filter"></i> Filter</button>
                    @if(request()->hasAny(['poli_id','spesialisasi','hari']))
                        <a href="{{ route('admin.dokter.index') }}" class="btn btn-outline btn-sm">Reset</a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="fas fa-user-doctor" style="color:#1d4ed8"></i> Daftar Dokter Spesialis &amp; Umum</div>
        <span style="font-size:12px;color:#6b7280">{{ $dokter->total() }} dokter</span>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Dokter</th>
                    <th>Spesialisasi</th>
                    <th>Poliklinik</th>
                    <th>Jadwal Praktik</th>
                    <th>No. STR</th>
                    <th>SatuSehat ID</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dokter as $d)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            <div style="width:36px;height:36px;background:linear-gradient(135deg,#dbeafe,#bfdbfe);border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0"><i class="fas fa-user-doctor" style="color:#1d4ed8"></i></div>
                            <div>
                                <div style="font-weight:700;font-size:13px;color:#111827">{{ $d->nama_lengkap }}</div>
                                @if($d->nik)<div style="font-size:11px;color:#9ca3af">NIK: {{ $d->nik }}</div>@endif
                            </div>
                        </div>
                    </td>
                    <td style="font-size:13px;color:#374151">{{ $d->spesialisasi }}</td>
                    <td>
                        <span class="badge badge-info">{{ $d->poli->nama ?? '-' }}</span>
                    </td>
                    <td>
                        <div style="display:flex;flex-wrap:wrap;gap:3px;max-width:160px">
                            @if($d->jadwal)
                                @foreach($d->jadwal as $hari => $info)
                                    @if($info['aktif'] ?? false)
                                    <span style="background:#dbeafe;color:#1e40af;padding:2px 7px;border-radius:5px;font-size:10px;font-weight:700">{{ strtoupper(substr($hari,0,3)) }}</span>
                                    @endif
                                @endforeach
                            @else
                                <span style="color:#9ca3af;font-size:12px">-</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        @if($d->str_number)
                            <span style="font-size:11px;font-family:monospace;background:#f3f4f6;padding:3px 8px;border-radius:5px;color:#374151">{{ $d->str_number }}</span>
                        @else
                            <span style="color:#dc2626;font-size:11px;font-weight:600;background:#fef2f2;padding:3px 8px;border-radius:5px">Belum STR</span>
                        @endif
                    </td>
                    <td>
                        @if($d->satusehat_id)
                            <code style="font-size:10px;color:#0891b2;background:#e0f2fe;padding:2px 7px;border-radius:5px">{{ Str::limit($d->satusehat_id,12) }}</code>
                        @else
                            <span style="color:#9ca3af;font-size:12px">-</span>
                        @endif
                    </td>
                    <td><span class="badge badge-{{ $d->is_active ? 'success' : 'danger' }}">{{ $d->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                    <td>
                        <a href="{{ route('admin.dokter.edit', $d->id) }}" class="btn btn-outline btn-sm" title="Edit Data Dokter">
                            <i class="fas fa-pen-to-square"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center;padding:40px;color:#9ca3af">
                    <i class="fas fa-user-doctor" style="font-size:28px;display:block;margin-bottom:8px;color:#d1d5db"></i>
                    Belum ada data dokter. <a href="{{ route('admin.dokter.create') }}" style="color:#1d4ed8">Tambah sekarang</a>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($dokter->hasPages())
    <div style="padding:14px 18px;border-top:1px solid #f3f4f6">{{ $dokter->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
