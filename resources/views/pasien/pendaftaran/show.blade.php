@extends('layouts.app')
@section('title', 'Detail Pendaftaran - RS Cahya Medika')
@section('page-title', 'Detail Pendaftaran')

@section('content')
<div style="max-width:760px;margin:0 auto">

<!-- BREADCRUMB -->
<div style="display:flex;align-items:center;gap:8px;margin-bottom:20px;font-size:13px;color:#64748b">
    <a href="{{ route('pasien.dashboard') }}" style="color:#0891b2;text-decoration:none">Beranda</a>
    <i class="fas fa-chevron-right" style="font-size:10px"></i>
    <a href="{{ route('pasien.pendaftaran.index') }}" style="color:#0891b2;text-decoration:none">Pendaftaran</a>
    <i class="fas fa-chevron-right" style="font-size:10px"></i>
    <span>{{ $pendaftaran->kode_booking }}</span>
</div>

<!-- BOOKING CARD -->
<div style="background:linear-gradient(135deg,#0c4a6e,#0891b2);border-radius:20px;padding:28px;color:white;margin-bottom:20px;position:relative;overflow:hidden">
    <div style="position:absolute;top:-40px;right:-40px;width:200px;height:200px;background:rgba(255,255,255,0.06);border-radius:50%"></div>
    <div style="position:absolute;bottom:-60px;left:-20px;width:180px;height:180px;background:rgba(6,182,212,0.15);border-radius:50%"></div>

    <div style="position:relative;z-index:1">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:24px">
            <div>
                <div style="font-size:12px;opacity:0.7;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px">Kode Booking</div>
                <div style="font-size:28px;font-weight:900;letter-spacing:2px">{{ $pendaftaran->kode_booking }}</div>
            </div>
            <div style="text-align:right">
                <div style="font-size:12px;opacity:0.7;margin-bottom:4px">No. Antrian</div>
                <div style="width:60px;height:60px;background:rgba(255,255,255,0.15);border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:24px;font-weight:900">
                    {{ $pendaftaran->no_antrian }}
                </div>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:20px">
            <div>
                <div style="font-size:10px;opacity:0.6;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px">Poli</div>
                <div style="font-size:14px;font-weight:700">{{ $pendaftaran->poli->nama ?? '-' }}</div>
            </div>
            <div>
                <div style="font-size:10px;opacity:0.6;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px">Dokter</div>
                <div style="font-size:14px;font-weight:700">{{ $pendaftaran->dokter->nama_lengkap ?? '-' }}</div>
            </div>
            <div>
                <div style="font-size:10px;opacity:0.6;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px">Status</div>
                <div>
                    <span style="padding:4px 12px;border-radius:99px;font-size:12px;font-weight:700;
                        background:{{ $pendaftaran->status === 'menunggu' ? 'rgba(251,191,36,0.3)' : ($pendaftaran->status === 'selesai' ? 'rgba(5,150,105,0.3)' : 'rgba(220,38,38,0.3)') }}">
                        {{ $pendaftaran->status_label }}
                    </span>
                </div>
            </div>
            <div>
                <div style="font-size:10px;opacity:0.6;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px">Tanggal</div>
                <div style="font-size:13px;font-weight:600">{{ $pendaftaran->tanggal_kunjungan->format('d M Y') }}</div>
            </div>
            <div>
                <div style="font-size:10px;opacity:0.6;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px">Jam</div>
                <div style="font-size:13px;font-weight:600">{{ $pendaftaran->jam_kunjungan }} WIB</div>
            </div>
            <div>
                <div style="font-size:10px;opacity:0.6;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px">Jenis</div>
                <div style="font-size:13px;font-weight:600">{{ $pendaftaran->jenis_kunjungan === 'baru' ? 'Pasien Baru' : 'Kontrol' }}</div>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-2" style="gap:20px;margin-bottom:20px">
    <!-- KELUHAN -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">🩺 Keluhan Utama</div>
        </div>
        <div class="card-body">
            <p style="font-size:14px;line-height:1.7;color:#1e293b">{{ $pendaftaran->keluhan }}</p>
        </div>
    </div>

    <!-- SATUSEHAT STATUS -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">🔗 Status SatuSehat</div>
        </div>
        <div class="card-body">
            @if($pendaftaran->satusehat_status === 'success')
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px">
                    <div style="width:40px;height:40px;background:#d1fae5;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:18px">✅</div>
                    <div>
                        <div style="font-weight:700;color:#065f46;font-size:14px">Terkirim ke SatuSehat</div>
                        <div style="font-size:12px;color:#64748b">Data encounter berhasil dibuat</div>
                    </div>
                </div>
                @if($pendaftaran->satusehat_encounter_id)
                <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:10px 14px">
                    <div style="font-size:10px;font-weight:700;color:#065f46;text-transform:uppercase;margin-bottom:4px">Encounter ID</div>
                    <code style="font-size:12px;color:#166534;word-break:break-all">{{ $pendaftaran->satusehat_encounter_id }}</code>
                </div>
                @endif
            @elseif($pendaftaran->satusehat_status === 'pending')
                <div style="display:flex;align-items:center;gap:10px">
                    <div style="width:40px;height:40px;background:#fef3c7;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:18px">⏳</div>
                    <div>
                        <div style="font-weight:700;color:#92400e;font-size:14px">Menunggu Sinkronisasi</div>
                        <div style="font-size:12px;color:#64748b">Data akan segera dikirim ke SatuSehat</div>
                    </div>
                </div>
            @else
                <div style="display:flex;align-items:center;gap:10px">
                    <div style="width:40px;height:40px;background:#fee2e2;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:18px">❌</div>
                    <div>
                        <div style="font-weight:700;color:#991b1b;font-size:14px">Gagal Sinkronisasi</div>
                        <div style="font-size:12px;color:#64748b">Hubungi admin RS untuk bantuan</div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- TANDA VITAL (jika sudah diisi) -->
@if($pendaftaran->tekanan_darah || $pendaftaran->suhu)
<div class="card" style="margin-bottom:20px">
    <div class="card-header">
        <div class="card-title">💉 Tanda Vital</div>
        <span class="badge badge-success">Sudah Diukur</span>
    </div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px">
            @if($pendaftaran->tekanan_darah)
            <div style="text-align:center;padding:14px;background:#f8fafc;border-radius:12px">
                <div style="font-size:20px;margin-bottom:4px">🩺</div>
                <div style="font-size:18px;font-weight:800;color:#0c4a6e">{{ $pendaftaran->tekanan_darah }}</div>
                <div style="font-size:10px;color:#94a3b8;margin-top:2px">Tekanan Darah</div>
            </div>
            @endif
            @if($pendaftaran->suhu)
            <div style="text-align:center;padding:14px;background:#f8fafc;border-radius:12px">
                <div style="font-size:20px;margin-bottom:4px">🌡️</div>
                <div style="font-size:18px;font-weight:800;color:#0c4a6e">{{ $pendaftaran->suhu }}°C</div>
                <div style="font-size:10px;color:#94a3b8;margin-top:2px">Suhu</div>
            </div>
            @endif
            @if($pendaftaran->nadi)
            <div style="text-align:center;padding:14px;background:#f8fafc;border-radius:12px">
                <div style="font-size:20px;margin-bottom:4px">❤️</div>
                <div style="font-size:18px;font-weight:800;color:#0c4a6e">{{ $pendaftaran->nadi }}</div>
                <div style="font-size:10px;color:#94a3b8;margin-top:2px">Nadi (bpm)</div>
            </div>
            @endif
            @if($pendaftaran->berat_badan)
            <div style="text-align:center;padding:14px;background:#f8fafc;border-radius:12px">
                <div style="font-size:20px;margin-bottom:4px">⚖️</div>
                <div style="font-size:18px;font-weight:800;color:#0c4a6e">{{ $pendaftaran->berat_badan }} kg</div>
                <div style="font-size:10px;color:#94a3b8;margin-top:2px">Berat Badan</div>
            </div>
            @endif
        </div>
    </div>
</div>
@endif

<!-- ACTIONS -->
<div style="display:flex;gap:12px;flex-wrap:wrap">
    <a href="{{ route('pasien.pendaftaran.index') }}" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar
    </a>

    @if($pendaftaran->status === 'menunggu')
    <button onclick="confirmCancelQueue()" class="btn btn-danger">
        <i class="fas fa-times-circle"></i> Batalkan Pendaftaran
    </button>
    <form id="formCancelQueue" action="{{ route('pasien.pendaftaran.cancel', $pendaftaran->id) }}" method="POST" style="display:none">
        @csrf
        <input type="hidden" name="alasan" id="alasanCancelInput">
    </form>
    @endif

    <button onclick="window.print()" class="btn btn-outline" style="margin-left:auto">
        <i class="fas fa-print"></i> Cetak Bukti
    </button>
</div>

</div>
@endsection

@push('scripts')
<script>
function confirmCancelQueue() {
    Swal.fire({
        title: 'Batalkan Pendaftaran?',
        text: 'Tindakan ini tidak dapat dibatalkan. Masukkan alasan pembatalan (opsional):',
        icon: 'warning',
        input: 'textarea',
        inputPlaceholder: 'Contoh: Ada keperluan mendadak...',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: '<i class="fas fa-trash"></i> Ya, Batalkan',
        cancelButtonText: 'Batal Kembali'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('alasanCancelInput').value = result.value || '';
            Swal.fire({
                title: 'Membatalkan Pendaftaran...',
                text: 'Mohon tunggu...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });
            document.getElementById('formCancelQueue').submit();
        }
    });
}
</script>
@endpush
