@extends('layouts.app')
@section('title', 'Master Data Pasien - RS Cahya Medika')
@section('page-title', 'Master Data Pasien')

@section('content')
<div class="page-header">
    <div>
        <div class="page-header-title">Master Data Pasien</div>
        <div class="page-header-sub">Daftar seluruh pasien yang terdaftar di RS Cahya Medika</div>
    </div>
</div>

<div class="card" style="margin-bottom:18px">
    <div class="card-body" style="padding:14px 18px">
        <form method="GET" action="{{ route('admin.pasien.index') }}">
            <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end">
                <div style="display:flex;flex-direction:column;gap:4px;min-width:140px">
                    <label style="font-size:11px;font-weight:600;color:#6b7280">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-select" style="padding:6px 10px;font-size:12px">
                        <option value="">Semua</option>
                        <option value="L" {{ request('jenis_kelamin')==='L'?'selected':'' }}>Laki-laki</option>
                        <option value="P" {{ request('jenis_kelamin')==='P'?'selected':'' }}>Perempuan</option>
                    </select>
                </div>
                <div style="display:flex;flex-direction:column;gap:4px;min-width:130px">
                    <label style="font-size:11px;font-weight:600;color:#6b7280">Bulan Kunjungan</label>
                    <select name="bulan_kunjungan" class="form-select" style="padding:6px 10px;font-size:12px">
                        <option value="">Semua Bulan</option>
                        @foreach(range(1,12) as $b)
                            <option value="{{ $b }}" {{ request('bulan_kunjungan')==$b?'selected':'' }}>{{ \Carbon\Carbon::create()->month($b)->locale('id')->monthName }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display:flex;flex-direction:column;gap:4px;min-width:90px">
                    <label style="font-size:11px;font-weight:600;color:#6b7280">Tahun</label>
                    <select name="tahun_kunjungan" class="form-select" style="padding:6px 10px;font-size:12px">
                        <option value="">Semua</option>
                        @foreach(range(date('Y'), date('Y')-3) as $t)
                            <option value="{{ $t }}" {{ request('tahun_kunjungan')==$t?'selected':'' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display:flex;flex-direction:column;gap:4px;flex:1;min-width:220px">
                    <label style="font-size:11px;font-weight:600;color:#6b7280">Cari Nama / No. RM / NIK</label>
                    <div style="position:relative">
                        <i class="fas fa-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:12px"></i>
                        <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Cari nama, No. RM, atau NIK..." style="padding:6px 10px 6px 32px;font-size:12px">
                    </div>
                </div>
                <div style="display:flex;gap:6px;align-self:flex-end">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Cari</button>
                    @if(request()->hasAny(['search','jenis_kelamin','bulan_kunjungan','tahun_kunjungan']))
                        <a href="{{ route('admin.pasien.index') }}" class="btn btn-outline btn-sm">Reset</a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="fas fa-users" style="color:#1d4ed8"></i> Daftar Pasien <span style="background:#dbeafe;color:#1e40af;padding:2px 9px;border-radius:6px;font-size:11px;font-weight:700;margin-left:6px">{{ $pasien->total() }}</span></div>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>No. RM</th><th>Nama Pasien</th><th>NIK</th><th>L/P</th><th>Tgl Lahir</th><th>No. HP</th><th>Total Kunjungan</th><th>SatuSehat</th><th>Status</th><th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pasien as $p)
                <tr>
                    <td><span style="font-family:monospace;font-weight:700;color:#1d4ed8;font-size:13px">{{ $p->no_rm }}</span></td>
                    <td>
                        <div style="font-weight:600;font-size:13px">{{ $p->nama_lengkap }}</div>
                        <div style="font-size:11px;color:#9ca3af">{{ $p->no_hp }}</div>
                    </td>
                    <td><span style="font-size:12px;font-family:monospace">{{ $p->nik }}</span></td>
                    <td><span style="font-weight:700;color:{{ $p->jenis_kelamin==='L'?'#0891b2':'#ec4899' }}">{{ $p->jenis_kelamin==='L'?'♂':'♀' }} {{ $p->jenis_kelamin }}</span></td>
                    <td style="font-size:12px">{{ optional($p->tanggal_lahir)->format('d/m/Y')??'-' }}</td>
                    <td style="font-size:12px">{{ $p->no_hp??'-' }}</td>
                    <td><span style="font-weight:700;color:#1d4ed8">{{ $p->pendaftaran()->count() }}</span> <span style="color:#9ca3af;font-size:11px">kunjungan</span></td>
                    <td>
                        @if($p->satusehat_id)
                            <span class="ss-badge success"><i class="fas fa-check" style="font-size:8px"></i> Sync</span>
                        @else
                            <span class="ss-badge pending">Belum</span>
                        @endif
                    </td>
                    <td><span class="badge badge-{{ $p->status==='aktif'?'success':'danger' }}">{{ ucfirst($p->status) }}</span></td>
                    <td>
                        <div style="display:flex;gap:5px">
                            <a href="{{ route('admin.pasien.show', $p->id) }}" class="btn btn-outline btn-sm" title="Detail"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('admin.pasien.edit', $p->id) }}" class="btn btn-outline btn-sm" title="Edit"><i class="fas fa-pen-to-square"></i></a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="10" style="text-align:center;padding:40px;color:#9ca3af">
                    <i class="fas fa-users" style="font-size:28px;display:block;margin-bottom:8px;color:#d1d5db"></i>Tidak ada data pasien ditemukan
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($pasien->hasPages())
    <div style="padding:14px 18px;border-top:1px solid #f3f4f6">{{ $pasien->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
