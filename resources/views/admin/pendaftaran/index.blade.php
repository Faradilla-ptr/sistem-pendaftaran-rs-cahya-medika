@extends('layouts.app')
@section('title', 'Manajemen Pendaftaran - RS Cahya Medika')
@section('page-title', 'Manajemen Antrean & Pendaftaran')

@section('content')
@php 
    $r = auth()->user()->role === 'rekam_medis' ? 'rekam_medis.' : (auth()->user()->role === 'pendaftaran' ? 'pendaftaran.' : 'admin.'); 
@endphp



{{-- FILTER CARD --}}
<div class="card" style="margin-bottom:18px">
    <div class="card-body" style="padding:14px 18px">
        <form method="GET" action="{{ route($r . 'pendaftaran.index') }}" id="filterForm">
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
                    <a href="{{ route($r . 'pendaftaran.index') }}" class="btn btn-outline btn-sm">Reset</a>
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
                    <th>Status Antrean</th>
                    @if(auth()->user()->role !== 'pendaftaran')
                    <th>SatuSehat</th>
                    @endif
                    <th style="width:160px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendaftaran as $p)
                <tr>
                    <td>
                        @if($p->no_antrian > 0)
                            <span style="display:inline-flex;align-items:center;gap:4px;padding:5px 10px;background:#0f172a;color:white;border-radius:8px;font-weight:800;font-size:12px;box-shadow:0 1px 3px rgba(0,0,0,0.08)">
                                <i class="fas fa-ticket" style="font-size:10px;color:#38bdf8"></i>#{{ $p->no_antrian }}
                            </span>
                        @else
                            <span style="display:inline-flex;align-items:center;gap:4px;padding:4px 9px;background:#f1f5f9;color:#475569;border:1px solid #cbd5e1;border-radius:8px;font-weight:600;font-size:11px" title="Nomor antrean diterbitkan saat check-in loket">
                                <i class="fas fa-clock" style="font-size:10px;color:#0284c7"></i> Belum Check-in
                            </span>
                        @endif
                    </td>
                    <td>
                        <code style="background:#f3f4f6;padding:2px 7px;border-radius:5px;font-size:11px;color:#374151">{{ $p->kode_booking }}</code>
                        @if($p->jarak_km)
                            <div style="font-size:10px;color:#6b7280;margin-top:2px"><i class="fas fa-map-marker-alt" style="color:#ef4444"></i> {{ is_numeric($p->jarak_km) ? number_format((float)$p->jarak_km, 1) : $p->jarak_km }} km (~{{ $p->estimasi_menit }} mnt)</div>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight:600;font-size:13px">{{ $p->pasien->nama_lengkap ?? '-' }}</div>
                        <div style="font-size:11px;color:#0891b2;font-weight:600">No. RM: {{ $p->pasien->no_rm ?? '-' }}</div>
                    </td>
                    <td>
                        <div style="font-weight:500;font-size:13px">{{ $p->poli->nama ?? '-' }}</div>
                        <div style="font-size:11px;color:#9ca3af">{{ $p->dokter->nama_lengkap ?? '-' }}</div>
                    </td>
                    <td>
                        <div style="font-size:13px">{{ \Carbon\Carbon::parse($p->tanggal_kunjungan)->format('d/m/Y') }}</div>
                        <div style="font-size:11px;color:#9ca3af">{{ $p->jam_kunjungan }} WIB</div>
                    </td>
                    <td>
                        @if($p->status === 'terdaftar_online')
                            <span class="badge" style="background:#fff7ed;color:#c2410c;border:1px solid #ffedd5;font-size:11px;padding:4px 8px"><i class="fas fa-laptop-house"></i> Online (Belum Check-in)</span>
                        @else
                            @if(in_array(auth()->user()->role, ['admin', 'pendaftaran']))
                            <div style="display:flex;align-items:center;gap:6px">
                                <select class="form-select" style="padding:4px 8px;font-size:11px;width:115px"
                                    onchange="updateStatus({{ $p->id }}, this.value)">
                                    <option value="menunggu" {{ $p->status==='menunggu'?'selected':'' }}>Menunggu</option>
                                    <option value="dipanggil" {{ $p->status==='dipanggil'?'selected':'' }}>Dipanggil</option>
                                    <option value="pemeriksaan_selesai" {{ $p->status==='pemeriksaan_selesai'?'selected':'' }}>Pemeriksaan Selesai</option>
                                    <option value="proses_rekam_medis" {{ $p->status==='proses_rekam_medis'?'selected':'' }}>Proses Rekam Medis</option>
                                    <option value="selesai" {{ $p->status==='selesai'?'selected':'' }}>Selesai</option>
                                    <option value="batal" {{ $p->status==='batal'?'selected':'' }}>Batal</option>
                                </select>
                            </div>
                            @else
                            <span class="badge badge-{{ $p->status === 'selesai' ? 'success' : ($p->status === 'batal' ? 'danger' : 'info') }}" style="font-size:11px;padding:4px 8px">
                                {{ ucfirst(str_replace('_', ' ', $p->status)) }}
                            </span>
                            @endif
                        @endif
                        @if($p->deposit_awal > 0)
                            <div style="font-size:10px;color:#059669;margin-top:2px;font-weight:600"><i class="fas fa-wallet"></i> Depo: Rp {{ number_format((float)$p->deposit_awal, 0, ',', '.') }}</div>
                        @endif
                    </td>
                    @if(auth()->user()->role !== 'pendaftaran')
                    <td>
                        @if($p->satusehat_status === 'success')
                            <span class="badge badge-success" style="font-size:10px;padding:3px 8px"><i class="fas fa-check-circle"></i> Terkirim 100%</span>
                        @elseif($p->satusehat_status === 'failed')
                            <span class="badge badge-danger" style="font-size:10px;padding:3px 8px"><i class="fas fa-exclamation-triangle"></i> Gagal</span>
                        @else
                            <span class="badge badge-warning" style="font-size:10px;padding:3px 8px"><i class="fas fa-clock"></i> In-Progress</span>
                        @endif
                    </td>
                    @endif
                    <td>
                        <div style="display:flex;gap:4px;flex-wrap:wrap">
                            @if(in_array(auth()->user()->role, ['admin', 'pendaftaran']) && $p->status === 'terdaftar_online')
                                <form action="{{ route($r . 'pendaftaran.checkin', $p->id) }}" method="POST" style="display:inline">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm" style="padding:3px 7px;font-size:11px" onclick="return confirm('Proses Check-in & Ambil No. Antrean untuk {{ $p->pasien->nama_lengkap ?? '' }}?')">
                                        <i class="fas fa-ticket-alt"></i> Check-in
                                    </button>
                                </form>
                            @endif

                            <a href="{{ route($r . 'pendaftaran.cetak-formulir', $p->id) }}" target="_blank" class="btn btn-outline btn-sm" title="Cetak Formulir Pasien" style="color:#0284c7;border-color:#bae6fd;background:#f0f9ff;padding:3px 7px;font-size:11px">
                                <i class="fas fa-print"></i> Form
                            </a>

                            @if(in_array(auth()->user()->role, ['admin', 'pendaftaran']) && $p->status === 'pemeriksaan_selesai')
                                <button type="button" class="btn btn-warning btn-sm" style="padding:3px 7px;font-size:11px" onclick="openDepositModal({{ $p->id }}, {{ $p->deposit_awal }}, {{ $p->biaya_total }})">
                                    <i class="fas fa-hand-holding-usd"></i> Sisa Depo
                                </button>
                            @endif

                            @if(in_array(auth()->user()->role, ['admin', 'rekam_medis']) && $p->status === 'proses_rekam_medis')
                                <form action="{{ route($r . 'pendaftaran.finalize-rekam-medis', $p->id) }}" method="POST" style="display:inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm" style="padding:3px 7px;font-size:11px" onclick="return confirm('Kirim Finished status ke SatuSehat?')">
                                        <i class="fas fa-check-double"></i> Finalisasi
                                    </button>
                                </form>
                            @endif

                            <a href="{{ route($r . 'pendaftaran.show', $p->id) }}" class="btn btn-outline btn-sm" style="padding:3px 7px;font-size:11px" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
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
        {{ $pendaftaran->withQueryString()->links('vendor.pagination.custom') }}
    </div>
    @endif
</div>

{{-- MODAL SETTLE DEPOSIT --}}
<div class="modal fade" id="depositModal" tabindex="-1" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center">
    <div style="background:white;border-radius:12px;width:100%;max-width:420px;padding:20px;box-shadow:0 10px 25px rgba(0,0,0,0.2)">
        <h3 style="margin-top:0;font-size:16px;font-weight:700;color:#1e293b"><i class="fas fa-calculator" style="color:#d97706"></i> Hitung Sisa Deposit Pasien</h3>
        <form id="depositForm" method="POST">
            @csrf
            <div style="margin-bottom:12px">
                <label style="font-size:12px;font-weight:600">Deposit Awal (Rp)</label>
                <input type="number" id="modalDepositAwal" class="form-control" readonly style="background:#f8fafc">
            </div>
            <div style="margin-bottom:12px">
                <label style="font-size:12px;font-weight:600">Total Biaya Berobat / Dokter (Rp)</label>
                <input type="number" name="biaya_total" id="modalBiayaTotal" class="form-control" required min="0" oninput="calcSisa()">
            </div>
            <div style="margin-bottom:16px;padding:12px;background:#f0fdf4;border-radius:8px;border:1px solid #bbf7d0">
                <div style="font-size:11px;color:#166534">Sisa Uang Deposit Dikembalikan ke Pasien:</div>
                <div id="textSisaDeposit" style="font-size:18px;font-weight:800;color:#15803d">Rp 0</div>
            </div>
            <div style="display:flex;justify-content:flex-end;gap:8px">
                <button type="button" class="btn btn-outline" onclick="closeDepositModal()">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> Simpan & Kembalikan Deposit</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
const routePrefix = '{{ auth()->user()->role === 'rekam_medis' ? '/rekam-medis' : (auth()->user()->role === 'pendaftaran' ? '/pendaftaran' : '/admin') }}';

function clearBulanTahun(){ document.getElementById('inputBulan').value=''; document.getElementById('inputTahun').value=''; }
function clearTanggal(){ document.getElementById('inputTanggal').value=''; }
function updateStatus(id, status){
    if(!confirm('Ubah status menjadi: '+status+'?')) return;
    fetch(`${routePrefix}/pendaftaran/${id}/status`, {
        method:'PATCH',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content,'Accept':'application/json'},
        body:JSON.stringify({status})
    }).then(r=>r.json()).then(d=>{ if(d.success) location.reload(); else alert('Gagal mengubah status'); });
}

function openDepositModal(id, depositAwal, biayaTotal){
    document.getElementById('depositForm').action = `${routePrefix}/pendaftaran/${id}/settle-deposit`;
    document.getElementById('modalDepositAwal').value = depositAwal;
    document.getElementById('modalBiayaTotal').value = biayaTotal || 0;
    calcSisa();
    document.getElementById('depositModal').style.display = 'flex';
}

function closeDepositModal(){
    document.getElementById('depositModal').style.display = 'none';
}

function calcSisa(){
    let awal = parseFloat(document.getElementById('modalDepositAwal').value) || 0;
    let biaya = parseFloat(document.getElementById('modalBiayaTotal').value) || 0;
    let sisa = awal - biaya;
    document.getElementById('textSisaDeposit').innerText = 'Rp ' + sisa.toLocaleString('id-ID');
}
</script>
@endpush
