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
                    <td style="font-size:12px;font-family:monospace">{{ $d->str_number ?? '-' }}</td>
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
                        <div style="display:flex;gap:6px">
                            <a href="{{ route('admin.dokter.edit', $d->id) }}" class="btn btn-sm btn-outline" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
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
