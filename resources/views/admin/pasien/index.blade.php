@extends('layouts.app')
@section('title', 'Data Pasien - RS Cahya Medika')
@section('page-title', 'Data Pasien')

@section('content')
<!-- SEARCH -->
<div class="card" style="margin-bottom:20px">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.pasien.index') }}">
            <div style="display:flex;gap:12px">
                <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                    placeholder="Cari nama, No. RM, atau NIK..." style="flex:1">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Cari</button>
                @if(request('search'))
                    <a href="{{ route('admin.pasien.index') }}" class="btn btn-outline">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- TABLE -->
<div class="card">
    <div class="card-header">
        <div class="card-title">
            👥 Daftar Pasien
            <span style="font-size:12px;font-weight:400;color:#94a3b8;margin-left:8px">{{ $pasien->total() }} total</span>
        </div>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>No. RM</th>
                    <th>Nama Pasien</th>
                    <th>NIK</th>
                    <th>L/P</th>
                    <th>Tgl Lahir</th>
                    <th>No. HP</th>
                    <th>Total Kunjungan</th>
                    <th>SatuSehat</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pasien as $p)
                <tr>
                    <td>
                        <span style="font-family:monospace;font-weight:700;color:#0c4a6e;font-size:13px">{{ $p->no_rm }}</span>
                    </td>
                    <td>
                        <div style="font-weight:700;font-size:13px">{{ $p->nama_lengkap }}</div>
                        <div style="font-size:11px;color:#94a3b8">{{ $p->email ?? $p->no_hp }}</div>
                    </td>
                    <td><span style="font-size:12px;font-family:monospace">{{ $p->nik }}</span></td>
                    <td>
                        <span style="font-weight:700;color:{{ $p->jenis_kelamin === 'L' ? '#0891b2' : '#ec4899' }}">
                            {{ $p->jenis_kelamin }}
                        </span>
                    </td>
                    <td style="font-size:12px">
                        {{ optional($p->tanggal_lahir)->format('d/m/Y') ?? '-' }}<br>
                        <span style="color:#94a3b8">{{ $p->umur ?? '-' }} th</span>
                    </td>
                    <td style="font-size:12px">{{ $p->no_hp ?? '-' }}</td>
                    <td>
                        <span style="font-weight:700;color:#0c4a6e">{{ $p->pendaftaran()->count() }}</span>
                        <span style="color:#94a3b8;font-size:11px"> kunjungan</span>
                    </td>
                    <td>
                        @if($p->satusehat_id)
                            <span class="ss-badge success" style="font-size:10px"><i class="fas fa-check" style="font-size:8px"></i> Sync</span>
                        @else
                            <span class="ss-badge pending" style="font-size:10px">Belum</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-{{ $p->status === 'aktif' ? 'success' : 'danger' }}">
                            {{ ucfirst($p->status) }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="{{ route('admin.pasien.show', $p->id) }}" class="btn btn-sm btn-outline" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.pasien.edit', $p->id) }}" class="btn btn-sm btn-outline" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" style="text-align:center;padding:48px;color:#94a3b8">
                        <div style="font-size:36px;margin-bottom:10px">👥</div>
                        Tidak ada data pasien ditemukan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($pasien->hasPages())
    <div style="padding:16px 20px;border-top:1px solid #f1f5f9">
        {{ $pasien->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
