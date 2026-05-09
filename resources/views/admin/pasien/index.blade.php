@extends('layouts.app')
@section('title', 'Data Pasien - RS Cahya Medika')
@section('page-title', 'Data Pasien')

@section('content')
<!-- FILTER -->
<div class="card" style="margin-bottom:20px">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.pasien.index') }}">
            <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end">

                <!-- Filter Jenis Kelamin -->
                <div class="form-group" style="margin:0;min-width:140px">
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-select">
                        <option value="">Semua</option>
                        <option value="L" {{ request('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ request('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <!-- Filter Bulan Kunjungan -->
                <div class="form-group" style="margin:0;min-width:120px">
                    <label class="form-label">Bulan Kunjungan</label>
                    <select name="bulan_kunjungan" class="form-select">
                        <option value="">Semua Bulan</option>
                        @foreach(range(1,12) as $b)
                            <option value="{{ $b }}" {{ request('bulan_kunjungan') == $b ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($b)->locale('id')->monthName }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Tahun Kunjungan -->
                <div class="form-group" style="margin:0;min-width:100px">
                    <label class="form-label">Tahun</label>
                    <select name="tahun_kunjungan" class="form-select">
                        <option value="">Semua Tahun</option>
                        @foreach(range(date('Y'), date('Y')-3) as $t)
                            <option value="{{ $t }}" {{ request('tahun_kunjungan') == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Search by Nama/RM/NIK -->
                <div class="form-group" style="margin:0;flex:1;min-width:220px">
                    <label class="form-label">Cari Nama / No. RM / NIK</label>
                    <div style="position:relative">
                        <i class="fas fa-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:13px"></i>
                        <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                            placeholder="Cari nama, No. RM, atau NIK..." style="padding-left:36px">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="flex-shrink:0">
                    <i class="fas fa-search"></i> Cari
                </button>
                @if(request()->hasAny(['search','jenis_kelamin','bulan_kunjungan','tahun_kunjungan']))
                    <a href="{{ route('admin.pasien.index') }}" class="btn btn-outline" style="flex-shrink:0">Reset</a>
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
        @if(request('jenis_kelamin') || request('bulan_kunjungan'))
        <div style="display:flex;gap:6px;flex-wrap:wrap">
            @if(request('jenis_kelamin'))
                <span class="badge badge-info">{{ request('jenis_kelamin') === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
            @endif
            @if(request('bulan_kunjungan'))
                <span class="badge badge-secondary">
                    {{ \Carbon\Carbon::create()->month(request('bulan_kunjungan'))->locale('id')->monthName }}
                    {{ request('tahun_kunjungan') }}
                </span>
            @endif
        </div>
        @endif
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
                            {{ $p->jenis_kelamin === 'L' ? '♂' : '♀' }} {{ $p->jenis_kelamin }}
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
