@extends('layouts.app')
@section('title', 'Manajemen Pendaftaran - RS Cahya Medika')
@section('page-title', 'Manajemen Pendaftaran')

@section('content')
<!-- FILTER -->
<div class="card" style="margin-bottom:20px">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.pendaftaran.index') }}" id="filterForm">
            <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end">

                <!-- Tanggal Spesifik -->
                <div class="form-group" style="margin:0;min-width:150px">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" id="inputTanggal" class="form-control"
                        value="{{ request('tanggal', (!request('bulan') && !request('tahun')) ? today()->format('Y-m-d') : '') }}"
                        onchange="clearBulanTahun()">
                </div>

                <!-- Bulan -->
                <div class="form-group" style="margin:0;min-width:120px">
                    <label class="form-label">Bulan</label>
                    <select name="bulan" id="inputBulan" class="form-select" onchange="clearTanggal()">
                        <option value="">-- Bulan --</option>
                        @foreach(range(1,12) as $b)
                            <option value="{{ $b }}" {{ request('bulan') == $b ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($b)->locale('id')->monthName }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tahun -->
                <div class="form-group" style="margin:0;min-width:100px">
                    <label class="form-label">Tahun</label>
                    <select name="tahun" id="inputTahun" class="form-select" onchange="clearTanggal()">
                        @foreach(range(date('Y'), date('Y')-3) as $t)
                            <option value="{{ $t }}" {{ request('tahun', date('Y')) == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Poli -->
                <div class="form-group" style="margin:0;min-width:150px">
                    <label class="form-label">Poli</label>
                    <select name="poli_id" class="form-select">
                        <option value="">Semua Poli</option>
                        @foreach($poli as $p)
                            <option value="{{ $p->id }}" {{ request('poli_id') == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Status -->
                <div class="form-group" style="margin:0;min-width:130px">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="menunggu"  {{ request('status') === 'menunggu'  ? 'selected' : '' }}>Menunggu</option>
                        <option value="dipanggil" {{ request('status') === 'dipanggil' ? 'selected' : '' }}>Dipanggil</option>
                        <option value="selesai"   {{ request('status') === 'selesai'   ? 'selected' : '' }}>Selesai</option>
                        <option value="batal"     {{ request('status') === 'batal'     ? 'selected' : '' }}>Batal</option>
                    </select>
                </div>

                <!-- Search -->
                <div class="form-group" style="margin:0;flex:2;min-width:180px">
                    <label class="form-label">Cari Pasien / Kode</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Nama atau kode booking...">
                </div>

                <button type="submit" class="btn btn-primary" style="flex-shrink:0">
                    <i class="fas fa-search"></i> Filter
                </button>
                <a href="{{ route('admin.pendaftaran.index') }}" class="btn btn-outline" style="flex-shrink:0">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- TABLE -->
<div class="card">
    <div class="card-header">
        <div class="card-title">
            📋 Daftar Pendaftaran
            <span style="font-size:12px;font-weight:400;color:#94a3b8;margin-left:8px">{{ $pendaftaran->total() }} data</span>
        </div>
        <div style="font-size:13px;color:#64748b">
            @if(request('bulan') && request('tahun'))
                <strong>{{ \Carbon\Carbon::create()->month(request('bulan'))->locale('id')->monthName }} {{ request('tahun') }}</strong>
            @elseif(request('tanggal'))
                <strong>{{ \Carbon\Carbon::parse(request('tanggal'))->locale('id')->isoFormat('dddd, D MMMM Y') }}</strong>
            @else
                <strong>Hari Ini</strong>
            @endif
        </div>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Antrian</th>
                    <th>Pasien</th>
                    <th>Poli / Dokter</th>
                    <th>Tanggal & Jam</th>
                    <th>Jenis</th>
                    <th>Status</th>
                    <th>SatuSehat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendaftaran as $p)
                <tr>
                    <td style="color:#94a3b8;font-size:12px">{{ $p->id }}</td>
                    <td>
                        <div style="width:36px;height:36px;background:#e0f2fe;border-radius:10px;display:flex;align-items:center;justify-content:center;font-weight:900;font-size:15px;color:#0c4a6e">
                            {{ $p->no_antrian }}
                        </div>
                    </td>
                    <td>
                        <div style="font-weight:700;font-size:13px">{{ $p->pasien->nama_lengkap ?? '-' }}</div>
                        <div style="font-size:11px;color:#94a3b8">No. RM: {{ $p->pasien->no_rm ?? '-' }}</div>
                        <div style="font-size:11px;color:#94a3b8">{{ $p->kode_booking }}</div>
                    </td>
                    <td>
                        <div style="font-weight:600;font-size:13px">{{ $p->poli->nama ?? '-' }}</div>
                        <div style="font-size:11px;color:#64748b">{{ $p->dokter->nama_lengkap ?? '-' }}</div>
                    </td>
                    <td>
                        <div style="font-size:13px;font-weight:600">{{ $p->tanggal_kunjungan->format('d/m/Y') }}</div>
                        <div style="font-size:12px;color:#64748b">⏰ {{ $p->jam_kunjungan }} WIB</div>
                    </td>
                    <td>
                        <span class="badge badge-{{ $p->jenis_kunjungan === 'baru' ? 'info' : 'secondary' }}">
                            {{ $p->jenis_kunjungan === 'baru' ? '🆕 Baru' : '🔄 Kontrol' }}
                        </span>
                    </td>
                    <td>
                        <form action="{{ route('admin.pendaftaran.status', $p->id) }}" method="POST" style="display:inline">
                            @csrf @method('PATCH')
                            <select name="status" class="form-select" style="padding:5px 8px;font-size:12px;width:120px" onchange="this.form.submit()">
                                <option value="menunggu"  {{ $p->status === 'menunggu'  ? 'selected' : '' }}>⏳ Menunggu</option>
                                <option value="dipanggil" {{ $p->status === 'dipanggil' ? 'selected' : '' }}>📢 Dipanggil</option>
                                <option value="selesai"   {{ $p->status === 'selesai'   ? 'selected' : '' }}>✅ Selesai</option>
                                <option value="batal"     {{ $p->status === 'batal'     ? 'selected' : '' }}>❌ Batal</option>
                            </select>
                        </form>
                    </td>
                    <td>
                        @if($p->satusehat_status === 'success')
                            <span class="ss-badge success"><i class="fas fa-check-circle" style="font-size:9px"></i> Sync</span>
                        @elseif($p->satusehat_status === 'pending')
                            <span class="ss-badge pending"><i class="fas fa-clock" style="font-size:9px"></i> Pending</span>
                        @else
                            <form action="{{ route('admin.satusehat.sync', $p->id) }}" method="POST" style="display:inline">
                                @csrf
                                <button type="submit" class="ss-badge failed" style="border:none;cursor:pointer">
                                    <i class="fas fa-redo" style="font-size:9px"></i> Retry
                                </button>
                            </form>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.pendaftaran.show', $p->id) }}" class="btn btn-sm btn-outline">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align:center;padding:48px;color:#94a3b8">
                        <div style="font-size:36px;margin-bottom:10px">📭</div>
                        Tidak ada data pendaftaran untuk periode ini
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($pendaftaran->hasPages())
    <div style="padding:16px 20px;border-top:1px solid #f1f5f9">
        {{ $pendaftaran->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function clearBulanTahun() {
    document.getElementById('inputBulan').value = '';
}
function clearTanggal() {
    document.getElementById('inputTanggal').value = '';
}
</script>
@endpush
