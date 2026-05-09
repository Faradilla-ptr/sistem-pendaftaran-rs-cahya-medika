<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Kunjungan {{ $namabulan }} - RS Cahya Medika</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family: Arial, sans-serif; font-size: 11px; color: #1e293b; background: white; }

/* Print trigger saat halaman dibuka */
@media screen {
  .print-btn {
    position: fixed; top: 16px; right: 16px; z-index: 999;
    background: #0c4a6e; color: white; border: none; border-radius: 8px;
    padding: 10px 20px; font-size: 13px; font-weight: 700; cursor: pointer;
    display: flex; align-items: center; gap: 8px;
  }
  .no-print-notice {
    background: #e0f2fe; border: 1px solid #bae6fd; border-radius: 8px;
    padding: 10px 16px; margin-bottom: 16px; font-size: 12px; color: #0369a1;
    display: flex; align-items: center; gap: 8px;
  }
  .page { max-width: 960px; margin: 20px auto; padding: 20px; }
}

@media print {
  .print-btn, .no-print-notice { display: none !important; }
  .page { margin: 0; padding: 0; }
  body { font-size: 10px; }
  table { page-break-inside: auto; }
  tr { page-break-inside: avoid; page-break-after: auto; }
  thead { display: table-header-group; }
  tfoot { display: table-footer-group; }
}

/* HEADER */
.header { border-bottom: 3px solid #0891b2; padding-bottom: 16px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: flex-start; }
.rs-name { font-size: 18px; font-weight: 900; color: #0c4a6e; }
.rs-sub { font-size: 11px; color: #64748b; margin-top: 2px; }
.report-title { text-align: right; }
.report-title h2 { font-size: 14px; font-weight: 700; color: #0c4a6e; }
.report-title p { font-size: 11px; color: #64748b; }

/* SUMMARY BOXES */
.summary { display: flex; gap: 12px; margin-bottom: 20px; }
.sum-box { flex: 1; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px 14px; text-align: center; }
.sum-val { font-size: 22px; font-weight: 900; color: #0c4a6e; }
.sum-label { font-size: 10px; color: #64748b; margin-top: 2px; }

/* REKAP SECTION */
.section-title { font-size: 12px; font-weight: 700; color: #0c4a6e; text-transform: uppercase; letter-spacing: 0.5px; margin: 16px 0 8px; border-bottom: 1px solid #e2e8f0; padding-bottom: 4px; }

/* TABLE */
table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
thead { background: #0c4a6e; }
thead th { color: white; padding: 7px 8px; text-align: left; font-size: 10px; font-weight: 700; white-space: nowrap; }
tbody tr:nth-child(even) { background: #f8fafc; }
tbody td { padding: 6px 8px; border-bottom: 1px solid #f1f5f9; vertical-align: top; }
tfoot td { padding: 8px; font-weight: 700; background: #e0f2fe; color: #0c4a6e; }

/* BADGE */
.badge { display: inline-block; padding: 2px 7px; border-radius: 4px; font-size: 9px; font-weight: 700; }
.badge-success { background: #d1fae5; color: #065f46; }
.badge-warning { background: #fef3c7; color: #92400e; }
.badge-danger  { background: #fee2e2; color: #991b1b; }
.badge-info    { background: #e0f2fe; color: #0c4a6e; }
.badge-secondary { background: #f1f5f9; color: #475569; }

/* FOOTER */
.footer { margin-top: 24px; border-top: 1px solid #e2e8f0; padding-top: 12px; display: flex; justify-content: space-between; font-size: 10px; color: #94a3b8; }
.ttd { margin-top: 40px; display: flex; justify-content: flex-end; }
.ttd-box { text-align: center; }
.ttd-line { margin-top: 60px; border-top: 1px solid #334155; padding-top: 4px; font-size: 10px; min-width: 160px; }
</style>
</head>
<body>
<div class="page">

<button class="print-btn" onclick="window.print()">🖨️ Cetak / Simpan PDF</button>

<div class="no-print-notice">
    ℹ️ Klik tombol <strong>Cetak / Simpan PDF</strong> di pojok kanan atas, lalu pilih <strong>"Save as PDF"</strong> di dialog printer untuk menyimpan sebagai PDF.
</div>

<!-- KOP SURAT -->
<div class="header">
    <div>
        <div class="rs-name">🏥 RS CAHYA MEDIKA BONDOWOSO</div>
        <div class="rs-sub">Jl. Mastrip, Bondowoso, Jawa Timur · Telp. 0332-123456</div>
        <div class="rs-sub">Rumah Sakit Umum Swasta (Non-BPJS) · Terintegrasi SatuSehat Kemenkes RI</div>
    </div>
    <div class="report-title">
        <h2>LAPORAN KUNJUNGAN PASIEN</h2>
        <p>Periode: {{ $namabulan }}</p>
        <p>Dicetak: {{ now()->locale('id')->isoFormat('dddd, D MMMM Y · HH:mm') }} WIB</p>
    </div>
</div>

<!-- RINGKASAN -->
<div class="summary">
    <div class="sum-box" style="border-top: 3px solid #0891b2">
        <div class="sum-val">{{ $data->count() }}</div>
        <div class="sum-label">Total Kunjungan</div>
    </div>
    <div class="sum-box" style="border-top: 3px solid #059669">
        <div class="sum-val">{{ $byStatus['selesai'] ?? 0 }}</div>
        <div class="sum-label">Selesai</div>
    </div>
    <div class="sum-box" style="border-top: 3px solid #d97706">
        <div class="sum-val">{{ ($byStatus['menunggu'] ?? 0) + ($byStatus['dipanggil'] ?? 0) }}</div>
        <div class="sum-label">Menunggu / Dipanggil</div>
    </div>
    <div class="sum-box" style="border-top: 3px solid #dc2626">
        <div class="sum-val">{{ $byStatus['batal'] ?? 0 }}</div>
        <div class="sum-label">Dibatalkan</div>
    </div>
    <div class="sum-box" style="border-top: 3px solid #7c3aed">
        <div class="sum-val">Rp {{ number_format($data->sum('biaya_konsultasi'), 0, ',', '.') }}</div>
        <div class="sum-label">Total Pendapatan</div>
    </div>
</div>

<!-- REKAP PER POLI -->
<div class="section-title">Rekap Per Poli</div>
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Poli</th>
            <th>Total</th>
            <th>Pasien Baru</th>
            <th>Kontrol</th>
            <th>Selesai</th>
            <th>% Selesai</th>
        </tr>
    </thead>
    <tbody>
        @foreach($byPoli as $info)
        @php $pct = $info['total'] > 0 ? round($info['selesai']/$info['total']*100) : 0; @endphp
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td><strong>{{ $info['nama'] }}</strong></td>
            <td style="font-weight:700;color:#0c4a6e">{{ $info['total'] }}</td>
            <td>{{ $info['baru'] ?? '-' }}</td>
            <td>{{ $info['kontrol'] ?? '-' }}</td>
            <td>{{ $info['selesai'] }}</td>
            <td>{{ $pct }}%</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2">TOTAL</td>
            <td>{{ $data->count() }}</td>
            <td>{{ $data->where('jenis_kunjungan','baru')->count() }}</td>
            <td>{{ $data->where('jenis_kunjungan','kontrol')->count() }}</td>
            <td>{{ $byStatus['selesai'] ?? 0 }}</td>
            <td>{{ $data->count() > 0 ? round(($byStatus['selesai'] ?? 0)/$data->count()*100) : 0 }}%</td>
        </tr>
    </tfoot>
</table>

<!-- DETAIL KUNJUNGAN -->
<div class="section-title">Detail Kunjungan Pasien</div>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Tanggal</th>
            <th>Kode Booking</th>
            <th>No. RM</th>
            <th>Nama Pasien</th>
            <th>NIK</th>
            <th>Poli</th>
            <th>Dokter</th>
            <th>Jam</th>
            <th>Jenis</th>
            <th>Status</th>
            <th>Biaya (Rp)</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data as $i => $p)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $p->tanggal_kunjungan->format('d/m/Y') }}</td>
            <td style="font-size:9px">{{ $p->kode_booking }}</td>
            <td style="font-size:9px">{{ $p->pasien->no_rm ?? '-' }}</td>
            <td><strong>{{ $p->pasien->nama_lengkap ?? '-' }}</strong></td>
            <td style="font-size:9px">{{ $p->pasien->nik ?? '-' }}</td>
            <td>{{ $p->poli->nama ?? '-' }}</td>
            <td>{{ $p->dokter->nama_lengkap ?? '-' }}</td>
            <td>{{ $p->jam_kunjungan }}</td>
            <td>
                @if($p->jenis_kunjungan === 'baru')
                    <span class="badge badge-info">Baru</span>
                @else
                    <span class="badge badge-secondary">Kontrol</span>
                @endif
            </td>
            <td>
                @if($p->status === 'selesai')
                    <span class="badge badge-success">Selesai</span>
                @elseif($p->status === 'menunggu')
                    <span class="badge badge-warning">Menunggu</span>
                @elseif($p->status === 'batal')
                    <span class="badge badge-danger">Batal</span>
                @else
                    <span class="badge badge-info">{{ ucfirst($p->status) }}</span>
                @endif
            </td>
            <td style="text-align:right">{{ $p->biaya_konsultasi > 0 ? number_format($p->biaya_konsultasi, 0, ',', '.') : '-' }}</td>
        </tr>
        @empty
        <tr><td colspan="12" style="text-align:center;padding:20px;color:#94a3b8">Tidak ada data</td></tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <td colspan="11" style="text-align:right">Total Pendapatan:</td>
            <td style="text-align:right">{{ number_format($data->sum('biaya_konsultasi'), 0, ',', '.') }}</td>
        </tr>
    </tfoot>
</table>

<!-- TANDA TANGAN -->
<div class="ttd">
    <div class="ttd-box">
        <div>Bondowoso, {{ now()->locale('id')->isoFormat('D MMMM Y') }}</div>
        <div style="margin-top:4px;font-size:10px;color:#64748b">Mengetahui,</div>
        <div class="ttd-line">Direktur RS Cahya Medika</div>
    </div>
</div>

<!-- FOOTER -->
<div class="footer">
    <span>RS Cahya Medika Bondowoso · Sistem Informasi Rumah Sakit</span>
    <span>Dicetak otomatis oleh sistem · {{ now()->format('d/m/Y H:i') }}</span>
</div>

</div>
</body>
</html>
