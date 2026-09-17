@extends('layouts.app')
@section('title', 'Detail Pendaftaran - RS Cahya Medika')
@section('page-title', 'Detail Pendaftaran')

@section('content')
<div style="max-width:780px;margin:0 auto">

<!-- BREADCRUMB -->
<div style="display:flex;align-items:center;gap:8px;margin-bottom:20px;font-size:13px;color:#64748b">
    <a href="{{ route('pasien.dashboard') }}" style="color:#0891b2;text-decoration:none">Beranda</a>
    <i class="fas fa-chevron-right" style="font-size:10px"></i>
    <a href="{{ route('pasien.pendaftaran.index') }}" style="color:#0891b2;text-decoration:none">Pendaftaran</a>
    <i class="fas fa-chevron-right" style="font-size:10px"></i>
    <span>{{ $pendaftaran->kode_booking }}</span>
</div>

<!-- BOOKING CARD (BLUE TICKET CARD) -->
<div id="blueBookingCard" style="background:linear-gradient(135deg,#0c4a6e,#0891b2);border-radius:24px;padding:30px;color:white;margin-bottom:24px;position:relative;overflow:hidden;box-shadow:0 10px 30px rgba(8,145,178,0.25)">
    <!-- Decorative background elements -->
    <div style="position:absolute;top:-50px;right:-50px;width:240px;height:240px;background:rgba(255,255,255,0.06);border-radius:50%;pointer-events:none"></div>
    <div style="position:absolute;bottom:-70px;left:-30px;width:220px;height:220px;background:rgba(6,182,212,0.15);border-radius:50%;pointer-events:none"></div>

    <div style="position:relative;z-index:1;display:flex;gap:24px;align-items:stretch;justify-content:space-between;flex-wrap:wrap">
        
        <!-- LEFT COLUMN: KODE BOOKING + 3 ROWS INFO GRID -->
        <div style="flex:1;min-width:300px;display:flex;flex-direction:column;justify-content:space-between">
            
            <!-- KODE BOOKING -->
            <div style="margin-bottom:16px">
                <div style="font-size:11px;opacity:0.75;text-transform:uppercase;letter-spacing:1.2px;font-weight:700">Kode Booking Pendaftaran</div>
                <div style="font-size:32px;font-weight:900;letter-spacing:2px;margin:2px 0 4px">{{ $pendaftaran->kode_booking }}</div>
                <div style="font-size:12.5px;opacity:0.9">
                    <i class="fas fa-route"></i> Jarak Tempuh: <strong>{{ $pendaftaran->jarak_km ?? '3.5 km' }}</strong> (&plusmn; {{ $pendaftaran->estimasi_menit ?? 12 }} Menit)
                </div>
            </div>

            <!-- INFO GRID: 2 COLUMNS (KE SAMPING), 3 ROWS (KE BAWAH) -->
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px 20px;margin-top:8px">
                
                <!-- ROW 1: NO ANTREAN LOKET - POLI & DOKTER -->
                <div>
                    <div style="font-size:10px;opacity:0.75;text-transform:uppercase;letter-spacing:0.8px;font-weight:700;margin-bottom:3px">No. Antrean Loket</div>
                    <div style="font-size:16px;font-weight:900;color:#38bdf8">
                        @if($pendaftaran->no_antrian > 0)
                            {{ $pendaftaran->poli->kode ?? 'A' }}-{{ sprintf('%03d', $pendaftaran->no_antrian) }}
                        @else
                            <span style="font-size:11px;color:#ffffff !important;background:rgba(255,255,255,0.22);padding:4px 9px;border-radius:6px;font-weight:700;display:inline-block">Terbit Saat Check-in Loket</span>
                        @endif
                    </div>
                </div>
                <div>
                    <div style="font-size:10px;opacity:0.75;text-transform:uppercase;letter-spacing:0.8px;font-weight:700;margin-bottom:3px">Poli & Dokter</div>
                    <div style="font-size:13.5px;font-weight:800">{{ $pendaftaran->poli->nama ?? '-' }}</div>
                    <div style="font-size:12px;opacity:0.85">{{ $pendaftaran->dokter->nama_lengkap ?? '-' }}</div>
                </div>

                <!-- ROW 2: STATUS PENDAFTARAN - TANGGAL & JAM -->
                <div>
                    <div style="font-size:10px;opacity:0.75;text-transform:uppercase;letter-spacing:0.8px;font-weight:700;margin-bottom:3px">Status Pendaftaran</div>
                    <div>
                        <span style="padding:4px 12px;border-radius:99px;font-size:11.5px;font-weight:700;background:rgba(255,255,255,0.22);color:#ffffff;display:inline-block">
                            {{ $pendaftaran->status_label }}
                        </span>
                    </div>
                </div>
                <div>
                    <div style="font-size:10px;opacity:0.75;text-transform:uppercase;letter-spacing:0.8px;font-weight:700;margin-bottom:3px">Tanggal & Jam</div>
                    <div style="font-size:13px;font-weight:700">{{ $pendaftaran->tanggal_kunjungan->format('d M Y') }} &middot; {{ $pendaftaran->jam_kunjungan }} WIB</div>
                </div>

                <!-- ROW 3: DEPOSIT AWAL - PETUNJUK KEDATANGAN -->
                <div>
                    <div style="font-size:10px;opacity:0.75;text-transform:uppercase;letter-spacing:0.8px;font-weight:700;margin-bottom:3px">Deposit Awal</div>
                    <div style="font-size:14px;font-weight:800;color:#4ade80">Rp {{ number_format((float)($pendaftaran->deposit_awal ?? 200000), 0, ',', '.') }}</div>
                </div>
                <div>
                    <div style="font-size:10px;opacity:0.75;text-transform:uppercase;letter-spacing:0.8px;font-weight:700;margin-bottom:3px">Petunjuk Kedatangan</div>
                    <div style="font-size:11.5px;opacity:0.9">Tunjukkan QR ini di Loket RS</div>
                </div>

            </div>

        </div>

        <!-- RIGHT COLUMN: ENLARGED QR CODE BOX -->
        <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;background:#ffffff;padding:20px 24px;border-radius:20px;color:#0f172a;box-shadow:0 8px 25px rgba(0,0,0,0.2);min-width:250px;align-self:center;flex-shrink:0">
            <div id="qrCodeWebContainer" style="display:flex;align-items:center;justify-content:center;width:220px;height:220px;overflow:hidden"></div>
            <div style="font-family:monospace;font-weight:800;font-size:13px;margin-top:10px;color:#0369a1;letter-spacing:1px;text-align:center">SCAN DI LOKET</div>
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
<div style="display:flex;gap:12px;flex-wrap:wrap;align-items:center">
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

    <button onclick="downloadTicketAsImage()" class="btn" style="margin-left:auto;background:linear-gradient(135deg,#0284c7,#0891b2);color:#ffffff !important;padding:12px 24px;border-radius:12px;font-weight:700;font-size:14px;border:none;box-shadow:0 4px 14px rgba(8,145,178,0.35);display:inline-flex;align-items:center;gap:8px;cursor:pointer;transition:all 0.2s ease;">
        <i class="fas fa-file-image" style="font-size:16px;color:#ffffff"></i> Simpan Tiket QR (Gambar)
    </button>
</div>



</div>
@endsection

@push('scripts')
<script src="{{ asset('js/qrcode-gen.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcode-generator/1.4.4/qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    generateAllQrCodes();
});

function generateQrDivGridInDoc(doc, containerId, text, displaySize) {
    const targetDoc = doc || document;
    const container = targetDoc.getElementById(containerId);
    if (!container) return;

    try {
        const qrGen = (typeof qrcode !== 'undefined') ? qrcode : (window.qrcode ? window.qrcode : null);
        if (!qrGen) return;

        const qr = qrGen(0, 'H');
        qr.addData(text);
        qr.make();

        const moduleCount = qr.getModuleCount();
        const cellSize = Math.floor(displaySize / moduleCount);
        const actualQrSize = cellSize * moduleCount;
        const margin = Math.floor((displaySize - actualQrSize) / 2);

        let html = `<div style="width:${actualQrSize}px;height:${actualQrSize}px;margin:${margin}px auto;background:#ffffff;line-height:0;font-size:0;">`;
        for (let r = 0; r < moduleCount; r++) {
            html += `<div style="height:${cellSize}px;width:${actualQrSize}px;clear:both;display:block;">`;
            for (let c = 0; c < moduleCount; c++) {
                const bg = qr.isDark(r, c) ? '#000000' : '#ffffff';
                html += `<div style="display:block;float:left;width:${cellSize}px;height:${cellSize}px;background-color:${bg};box-sizing:border-box;"></div>`;
            }
            html += `</div>`;
        }
        html += `</div>`;

        container.innerHTML = html;
    } catch(e) {
        console.error('QR Div Grid Error:', e);
    }
}

function generateAllQrCodes() {
    const bookingCode = "{{ $pendaftaran->kode_booking }}";
    generateQrDivGridInDoc(document, "qrCodeWebContainer", bookingCode, 220);
}

function generateTicketCanvasPNG(data) {
    const scale = 3; // 3x High-DPI scale for Retina crispness
    const width = 600 * scale;
    const height = 870 * scale;

    const canvas = document.createElement('canvas');
    canvas.width = width;
    canvas.height = height;
    const ctx = canvas.getContext('2d');

    ctx.imageSmoothingEnabled = true;
    ctx.imageSmoothingQuality = 'high';

    // 1. FILL WHITE CARD BACKGROUND
    ctx.fillStyle = '#ffffff';
    ctx.fillRect(0, 0, width, height);

    // 2. OUTER CARD BORDER (ROUNDED RECTANGLE)
    ctx.strokeStyle = '#0f172a';
    ctx.lineWidth = 3 * scale;
    ctx.beginPath();
    if (ctx.roundRect) {
        ctx.roundRect(4 * scale, 4 * scale, width - 8 * scale, height - 8 * scale, 24 * scale);
    } else {
        ctx.rect(4 * scale, 4 * scale, width - 8 * scale, height - 8 * scale);
    }
    ctx.stroke();

    let y = 48 * scale;

    // 3. HEADER
    ctx.textAlign = 'center';
    ctx.fillStyle = '#000000';
    ctx.font = `900 ${26 * scale}px 'Inter', system-ui, sans-serif`;
    ctx.fillText('RS CAHYA MEDIKA', width / 2, y);

    y += 22 * scale;
    ctx.fillStyle = '#475569';
    ctx.font = `500 ${13 * scale}px 'Inter', system-ui, sans-serif`;
    ctx.fillText('Jl. Mastrip No. 25, Bondowoso · Telp: (0332) 421123', width / 2, y);

    y += 18 * scale;
    // BADGE PILL
    const badgeText = 'TIKET ANTREAN & BUKTI PENDAFTARAN';
    ctx.font = `800 ${11.5 * scale}px 'Inter', system-ui, sans-serif`;
    const badgeWidth = ctx.measureText(badgeText).width + 36 * scale;
    const badgeHeight = 28 * scale;

    ctx.fillStyle = '#0f172a';
    ctx.beginPath();
    if (ctx.roundRect) {
        ctx.roundRect((width - badgeWidth) / 2, y, badgeWidth, badgeHeight, 8 * scale);
    } else {
        ctx.rect((width - badgeWidth) / 2, y, badgeWidth, badgeHeight);
    }
    ctx.fill();

    ctx.fillStyle = '#ffffff';
    ctx.fillText(badgeText, width / 2, y + 18.5 * scale);

    y += 44 * scale;

    // 4. DASHED SEPARATOR
    ctx.strokeStyle = '#94a3b8';
    ctx.lineWidth = 1.5 * scale;
    ctx.setLineDash([5 * scale, 5 * scale]);
    ctx.beginPath();
    ctx.moveTo(36 * scale, y);
    ctx.lineTo(width - 36 * scale, y);
    ctx.stroke();
    ctx.setLineDash([]); // reset

    y += 28 * scale;

    // 5. BOOKING CODE
    ctx.fillStyle = '#64748b';
    ctx.font = `700 ${11 * scale}px 'Inter', system-ui, sans-serif`;
    ctx.fillText('KODE BOOKING PENDAFTARAN', width / 2, y);

    y += 36 * scale;
    ctx.fillStyle = '#000000';
    ctx.font = `900 ${36 * scale}px monospace`;
    ctx.fillText(data.kodeBooking, width / 2, y);

    y += 20 * scale;

    // 6. QR CODE BOX & QR MODULES
    const qrBoxSize = 250 * scale;
    const qrBoxX = (width - qrBoxSize) / 2;
    const qrBoxY = y;

    // Outer QR Box with rounded border
    ctx.strokeStyle = '#0f172a';
    ctx.lineWidth = 2 * scale;
    ctx.fillStyle = '#ffffff';
    ctx.beginPath();
    if (ctx.roundRect) {
        ctx.roundRect(qrBoxX, qrBoxY, qrBoxSize, qrBoxSize, 24 * scale);
    } else {
        ctx.rect(qrBoxX, qrBoxY, qrBoxSize, qrBoxSize);
    }
    ctx.fill();
    ctx.stroke();

    // DRAW QR CODE MODULES DIRECTLY
    const qrGen = (typeof qrcode !== 'undefined') ? qrcode : (window.qrcode ? window.qrcode : null);
    if (qrGen) {
        const qr = qrGen(0, 'H');
        qr.addData(data.kodeBooking);
        qr.make();

        const moduleCount = qr.getModuleCount();
        const innerPadding = 20 * scale;
        const availSize = qrBoxSize - (innerPadding * 2);
        const cellSize = Math.floor(availSize / moduleCount);
        const actualQrSize = cellSize * moduleCount;
        const qrMarginX = qrBoxX + innerPadding + Math.floor((availSize - actualQrSize) / 2);
        const qrMarginY = qrBoxY + innerPadding + Math.floor((availSize - actualQrSize) / 2);

        ctx.fillStyle = '#000000';
        for (let r = 0; r < moduleCount; r++) {
            for (let c = 0; c < moduleCount; c++) {
                if (qr.isDark(r, c)) {
                    ctx.fillRect(
                        qrMarginX + c * cellSize,
                        qrMarginY + r * cellSize,
                        cellSize,
                        cellSize
                    );
                }
            }
        }
    }

    y += qrBoxSize + 20 * scale;

    // SUBTITLE UNDER QR
    ctx.fillStyle = '#0f172a';
    ctx.font = `800 ${13 * scale}px monospace`;
    ctx.fillText('SCAN DI LOKET PENDAFTARAN RS', width / 2, y);

    y += 28 * scale;

    // 7. DETAILS GRID (#f8fafc box)
    const gridX = 36 * scale;
    const gridW = width - (72 * scale);
    const gridH = 194 * scale;
    const gridY = y;

    ctx.fillStyle = '#f8fafc';
    ctx.strokeStyle = '#e2e8f0';
    ctx.lineWidth = 1 * scale;
    ctx.beginPath();
    if (ctx.roundRect) {
        ctx.roundRect(gridX, gridY, gridW, gridH, 20 * scale);
    } else {
        ctx.rect(gridX, gridY, gridW, gridH);
    }
    ctx.fill();
    ctx.stroke();

    // 2 COLUMNS OF DETAILS
    const col1X = gridX + 24 * scale;
    const col2X = gridX + (gridW / 2) + 12 * scale;
    let fieldY = gridY + 28 * scale;

    const drawField = (x, label, value, valFont, valColor) => {
        ctx.textAlign = 'left';
        ctx.fillStyle = '#64748b';
        ctx.font = `700 ${10.5 * scale}px 'Inter', system-ui, sans-serif`;
        ctx.fillText(label, x, fieldY);

        ctx.fillStyle = valColor || '#000000';
        ctx.font = valFont || `900 ${14 * scale}px 'Inter', system-ui, sans-serif`;
        ctx.fillText(value, x, fieldY + 18 * scale);
    };

    // Row 1
    drawField(col1X, 'NAMA PASIEN', data.namaPasien.toUpperCase());
    drawField(col2X, 'NIK PASIEN', data.nikPasien, `800 ${14 * scale}px monospace`);

    fieldY += 46 * scale;
    // Row 2
    drawField(col1X, 'POLIKLINIK TUJUAN', data.poli);
    drawField(col2X, 'DOKTER SPESIALIS', data.dokter, `800 ${14 * scale}px 'Inter', system-ui, sans-serif`);

    fieldY += 46 * scale;
    // Row 3
    drawField(col1X, 'TANGGAL & JAM', data.tanggalJam, `800 ${13.5 * scale}px 'Inter', system-ui, sans-serif`);
    drawField(col2X, 'NO. ANTREAN LOKET', data.noAntrean);

    fieldY += 46 * scale;
    // Row 4
    drawField(col1X, 'ESTIMASI JARAK RS', data.estimasiJarak, `700 ${13 * scale}px 'Inter', system-ui, sans-serif`);
    drawField(col2X, 'DEPOSIT AWAL', data.depositAwal);

    y = gridY + gridH + 24 * scale;

    // 8. DASHED SEPARATOR
    ctx.strokeStyle = '#94a3b8';
    ctx.lineWidth = 1.5 * scale;
    ctx.setLineDash([5 * scale, 5 * scale]);
    ctx.beginPath();
    ctx.moveTo(36 * scale, y);
    ctx.lineTo(width - 36 * scale, y);
    ctx.stroke();
    ctx.setLineDash([]);

    y += 28 * scale;

    // 9. FOOTER
    ctx.textAlign = 'center';
    ctx.fillStyle = '#0f172a';
    ctx.font = `800 ${12 * scale}px 'Inter', system-ui, sans-serif`;
    ctx.fillText('PETUNJUK KEDATANGAN:', width / 2, y);

    y += 18 * scale;
    ctx.fillStyle = '#334155';
    ctx.font = `500 ${12 * scale}px 'Inter', system-ui, sans-serif`;
    ctx.fillText('Simpan Tiket QR ini. Tunjukkan ke petugas Loket Pendaftaran RS Cahya Medika untuk scan', width / 2, y);

    y += 16 * scale;
    ctx.fillText('barcode & konfirmasi antrean.', width / 2, y);

    return canvas.toDataURL('image/png');
}

function downloadTicketAsImage() {
    Swal.fire({
        title: 'Memproses Gambar Tiket...',
        text: 'Sedang menyiapkan gambar tiket antrean...',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });

    setTimeout(() => {
        try {
            const ticketData = {
                kodeBooking: "{{ $pendaftaran->kode_booking }}",
                namaPasien: "{{ $pendaftaran->pasien->nama_lengkap ?? '-' }}",
                nikPasien: "{{ $pendaftaran->pasien->nik ?? '-' }}",
                poli: "{{ $pendaftaran->poli->nama ?? '-' }}",
                dokter: "{{ $pendaftaran->dokter->nama_lengkap ?? '-' }}",
                tanggalJam: "{{ $pendaftaran->tanggal_kunjungan->format('d M Y') }} · {{ $pendaftaran->jam_kunjungan }} WIB",
                noAntrean: "{{ $pendaftaran->no_antrian > 0 ? (($pendaftaran->poli->kode ?? 'A') . '-' . sprintf('%03d', $pendaftaran->no_antrian)) : 'Terbit Saat Check-in Loket' }}",
                estimasiJarak: "{{ $pendaftaran->jarak_km ?? '3.5 km' }} (± {{ $pendaftaran->estimasi_menit ?? 12 }} Menit)",
                depositAwal: "Rp {{ number_format((float)($pendaftaran->deposit_awal ?? 200000), 0, ',', '.') }} (Dibayar di RS)"
            };

            const imageURI = generateTicketCanvasPNG(ticketData);

            const link = document.createElement('a');
            link.download = 'Tiket-Antrean-{{ $pendaftaran->kode_booking }}.png';
            link.href = imageURI;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            Swal.fire({
                icon: 'success',
                title: 'Gambar Tiket Berhasil Diunduh!',
                text: 'File gambar (PNG) telah tersimpan di perangkat Anda.',
                confirmButtonColor: '#0891b2'
            });
        } catch(err) {
            console.error('Error generating ticket image:', err);
            Swal.fire({
                icon: 'error',
                title: 'Gagal Membuat Gambar',
                text: 'Terjadi kesalahan saat memproses tiket.'
            });
        }
    }, 100);
}

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
