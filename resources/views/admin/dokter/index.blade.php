@extends('layouts.app')
@section('title', 'Data Dokter - RS Cahya Medika')
@section('page-title', 'Data Dokter')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
    <div>
        <h2 style="font-size:18px;font-weight:800;color:#0c4a6e">Daftar Dokter</h2>
        <p style="font-size:13px;color:#64748b">Manajemen dokter dan jadwal praktik</p>
    </div>
    <a href="{{ route('admin.dokter.create') }}" class="btn btn-accent">
        <i class="fas fa-plus"></i> Tambah Dokter
    </a>
</div>

<!-- FILTER -->
<div class="card" style="margin-bottom:20px">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.dokter.index') }}">
            <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end">

                <div class="form-group" style="margin:0;min-width:160px">
                    <label class="form-label">Filter Poli</label>
                    <select name="poli_id" class="form-select">
                        <option value="">Semua Poli</option>
                        @foreach($poli as $p)
                            <option value="{{ $p->id }}" {{ request('poli_id') == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" style="margin:0;min-width:180px">
                    <label class="form-label">Spesialisasi</label>
                    <input type="text" name="spesialisasi" class="form-control"
                        value="{{ request('spesialisasi') }}" placeholder="Cari spesialisasi...">
                </div>

                <div class="form-group" style="margin:0;min-width:140px">
                    <label class="form-label">Hari Praktik</label>
                    <select name="hari" class="form-select">
                        <option value="">Semua Hari</option>
                        @foreach(['senin','selasa','rabu','kamis','jumat','sabtu','minggu'] as $h)
                            <option value="{{ $h }}" {{ request('hari') === $h ? 'selected' : '' }}>
                                {{ ucfirst($h) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary" style="flex-shrink:0">
                    <i class="fas fa-search"></i> Filter
                </button>
                @if(request()->hasAny(['poli_id','spesialisasi','hari']))
                    <a href="{{ route('admin.dokter.index') }}" class="btn btn-outline" style="flex-shrink:0">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Dokter</th>
                    <th>Spesialisasi</th>
                    <th>Poli</th>
                    <th>Jadwal</th>
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
                        <div style="display:flex;align-items:center;gap:12px">
                            <div style="width:42px;height:42px;background:linear-gradient(135deg,#e0f2fe,#bae6fd);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0">
                                👨‍⚕️
                            </div>
                            <div>
                                <div style="font-weight:800;font-size:13px;color:#0c4a6e">{{ $d->nama_lengkap }}</div>
                                @if($d->nik)
                                <div style="font-size:11px;color:#94a3b8">NIK: {{ $d->nik }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td style="font-size:13px">{{ $d->spesialisasi }}</td>
                    <td>
                        <span class="badge badge-info">{{ $d->poli->nama ?? '-' }}</span>
                    </td>
                    <td>
                        <div style="display:flex;flex-wrap:wrap;gap:3px;max-width:160px">
                            @if($d->jadwal)
                                @foreach($d->jadwal as $hari => $info)
                                    @if($info['aktif'] ?? false)
                                    <span style="background:#e0f2fe;color:#0891b2;padding:2px 7px;border-radius:5px;font-size:10px;font-weight:600">
                                        {{ strtoupper(substr($hari,0,3)) }}
                                    </span>
                                    @endif
                                @endforeach
                            @else
                                <span style="color:#94a3b8;font-size:12px">-</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        @if($d->str_number)
                            <span style="font-size:11px;font-family:monospace;background:#f1f5f9;padding:3px 8px;border-radius:6px;color:#334155">{{ $d->str_number }}</span>
                        @else
                            <span style="color:#dc2626;font-size:11px;font-weight:600">⚠ Belum diisi</span>
                        @endif
                    </td>
                    <td>
                        @if($d->satusehat_id)
                            <code style="font-size:10px;color:#0891b2">{{ $d->satusehat_id }}</code>
                        @else
                            <span style="color:#94a3b8;font-size:12px">-</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-{{ $d->is_active ? 'success' : 'danger' }}">
                            {{ $d->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.dokter.edit', $d->id) }}" class="btn btn-sm btn-outline" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:48px;color:#94a3b8">
                        <div style="font-size:36px;margin-bottom:10px">👨‍⚕️</div>
                        Belum ada data dokter. <a href="{{ route('admin.dokter.create') }}" style="color:#0891b2">Tambah sekarang</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($dokter->hasPages())
    <div style="padding:16px 20px;border-top:1px solid #f1f5f9">
        {{ $dokter->links() }}
    </div>
    @endif
</div>
@endsection
