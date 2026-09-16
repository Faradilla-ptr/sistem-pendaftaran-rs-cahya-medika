@extends('layouts.app')
@section('title', 'Manajemen Pendaftaran - RS Cahya Medika')
@section('page-title', 'Manajemen Antrean & Pendaftaran')

@section('content')

{{-- PAGE HEADER --}}
<div class="page-header">
    <div>
        <div class="page-header-title">Manajemen Antrean &amp; Pendaftaran</div>
        <div class="page-header-sub">Kelola semua data registrasi dan antrian pasien</div>
    </div>
</div>

{{-- FILTER CARD --}}
<div class="card" style="margin-bottom:18px">
    <div class="card-body" style="padding:14px 18px">
        <form method="GET" action="{{ route('admin.pendaftaran.index') }}" id="filterForm">
            <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end">
                <div style="display:flex;flex-direction:column;gap:4px;min-width:140px">
                    <label style="font-size:11px;font-weight:600;color:#6b7280">Tanggal</label>
                    <input type="date" name="tanggal" id="inputTanggal" class="form-control"
                        value="{{ request('tanggal', (!request('bulan') && !request('tahun')) ? today()->format('Y-m-d') : '') }}"
                        onchange="clearBulanTahun()" style="padding:6px 10px;font-size:12px">
                </div>
                <div style="display:flex;flex-direction:column;gap:4px;min-width:115px">
                    <label style="font-size:11px;font-weight:600;color:#6b7280">Bulan</label>
                    <select name="bulan" id="inputBulan" class="form-select" onchange="clearTanggal()" style="padding:6px 10px;font-size:12px">
                        <option value="">-- Bulan --</option>
                        @foreach(range(1,12) as $b)
                            <option value="{{ $b }}" {{ request('bulan')==$b?'selected':'' }}>{{ \Carbon\Carbon::create()->month($b)->locale('id')->monthName }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display:flex;flex-direction:column;gap:4px;min-width:90px">
                    <label style="font-size:11px;font-weight:600;color:#6b7280">Tahun</label>
                    <select name="tahun" id="inputTahun" class="form-select" onchange="clearTanggal()" style="padding:6px 10px;font-size:12px">
                        @foreach(range(date('Y'), date('Y')-3) as $t)
                            <option value="{{ $t }}" {{ request('tahun', date('Y'))==$t?'selected':'' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display:flex;flex-direction:column;gap:4px;min-width:150px">
                    <label style="font-size:11px;font-weight:600;color:#6b7280">Poliklinik</label>
                    <select name="poli_id" class="form-select" style="padding:6px 10px;font-size:12px">
                        <option value="">Semua Poli</option>
                        @foreach($poli as $p)
                            <option value="{{ $p->id }}" {{ request('poli_id')==$p->id?'selected':'' }}>{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display:flex;flex-direction:column;gap:4px;min-width:130px">
                    <label style="font-size:11px;font-weight:600;color:#6b7280">Status Antrean</label>
                    <select name="status" class="form-select" style="padding:6px 10px;font-size:12px">
                        <option value="">Semua Status</option>
                        <option value="menunggu" {{ request('status')=='menunggu'?'selected':'' }}>Menunggu</option>
                        <option value="dipanggil" {{ request('status')=='dipanggil'?'selected':'' }}>Dipanggil</option>
                        <option value="selesai" {{ request('status')=='selesai'?'selected':'' }}>Selesai</option>
                        <option value="batal" {{ request('status')=='batal'?'selected':'' }}>Batal</option>
                    </select>
                </div>
                <div style="display:flex;flex-direction:column;gap:4px;flex:1;min-width:180px">
                    <label style="font-size:11px;font-weight:600;color:#6b7280">Cari Pasien / Kode</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Nama atau kode booking" style="padding:6px 10px;font-size:12px">
                </div>
                <div style="display:flex;gap:6px;align-self:flex-end">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-filter"></i> Filter</button>
                    <a href="{{ route('admin.pendaftaran.index') }}" class="btn btn-outline btn-sm">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- DATA TABLE --}}
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-list-check" style="color:#1d4ed8"></i>
            Daftar Antrean &amp; Pendaftaran
            <span style="background:#dbeafe;color:#1e40af;padding:2px 9px;border-radius:6px;font-size:11px;font-weight:700;margin-left:6px">{{ $pendaftaran->total() }} data</span>
        </div>
        <div style="font-size:12px;color:#6b7280;font-weight:500">
            @if(request('bulan') && request('tahun'))
                {{ \Carbon\Carbon::create()->month(request('bulan'))->locale('id')->monthName }} {{ request('tahun') }}
            @elseif(request('tanggal'))
                {{ \Carbon\Carbon::parse(request('tanggal'))->locale('id')->isoFormat('D MMMM Y') }}
            @else
                Hari ini
            @endif
        </div>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th style="width:60px">Antrean</th>
                    <th>Kode Booking</th>
                    <th>Pasien</th>
                    <th>Poli / Dokter</th>
                    <th>Tanggal &amp; Jam</th>
                    <th>Jenis</th>
                    <th>Status</th>
                    <th style="width:80px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendaftaran as $p)
                <tr>
                    <td>
                        <span style="display:inline-flex;width:30px;height:30px;background:#1e293b;color:white;border-radius:8px;align-items:center;justify-content:center;font-weight:700;font-size:12px">#{{ $p->no_antrian }}</span>
                    </td>
                    <td>
                        <code style="background:#f3f4f6;padding:2px 7px;border-radius:5px;font-size:11px;color:#374151">{{ $p->kode_booking }}</code>
                    </td>
                    <td>
                        <div style="font-weight:600;font-size:13px">{{ $p->pasien->nama_lengkap ?? '-' }}</div>
                        <div style="font-size:11px;color:#9ca3af">No. RM {{ $p->pasien->no_rm ?? '-' }}</div>
                    </td>
                    <td>
                        <div style="font-weight:500;font-size:13px">{{ $p->poli->nama ?? '-' }}</div>
                        <div style="font-size:11px;color:#9ca3af">{{ $p->dokter->nama ?? '-' }}</div>
                    </td>
                    <td>
                        <div style="font-size:13px">{{ \Carbon\Carbon::parse($p->tanggal_kunjungan)->format('d/m/Y') }}</div>
                        <div style="font-size:11px;color:#9ca3af">{{ $p->jam_kunjungan }} WIB</div>
                    </td>
                    <td>
                        <span class="badge badge-{{ $p->jenis_kunjungan === 'baru' ? 'primary' : 'secondary' }}">
                            {{ $p->jenis_kunjungan === 'baru' ? 'Baru' : 'Kontrol' }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;align-items:center;gap:6px">
                            <select class="form-select" style="padding:4px 8px;font-size:11px;width:110px"
                                onchange="updateStatus({{ $p->id }}, this.value)">
                                <option value="menunggu" {{ $p->status==='menunggu'?'selected':'' }}>Menunggu</option>
                                <option value="dipanggil" {{ $p->status==='dipanggil'?'selected':'' }}>Dipanggil</option>
                                <option value="selesai" {{ $p->status==='selesai'?'selected':'' }}>Selesai</option>
                                <option value="batal" {{ $p->status==='batal'?'selected':'' }}>Batal</option>
                            </select>
                        </div>
                    </td>
                    <td>
                        <a href="{{ route('admin.pendaftaran.show', $p->id) }}" class="btn btn-outline btn-sm">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:40px;color:#9ca3af">
                        <i class="fas fa-inbox" style="font-size:28px;display:block;margin-bottom:8px;color:#d1d5db"></i>
                        Tidak ada data pendaftaran
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($pendaftaran->hasPages())
    <div style="padding:14px 18px;border-top:1px solid #f3f4f6">
        {{ $pendaftaran->withQueryString()->links() }}
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
function clearBulanTahun(){ document.getElementById('inputBulan').value=''; document.getElementById('inputTahun').value=''; }
function clearTanggal(){ document.getElementById('inputTanggal').value=''; }
function updateStatus(id, status){
    if(!confirm('Ubah status menjadi: '+status+'?')) return;
    fetch(`/admin/pendaftaran/${id}/status`, {
        method:'PATCH',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content,'Accept':'application/json'},
        body:JSON.stringify({status})
    }).then(r=>r.json()).then(d=>{ if(d.success) location.reload(); else alert('Gagal mengubah status'); });
}
</script>
@endpush
