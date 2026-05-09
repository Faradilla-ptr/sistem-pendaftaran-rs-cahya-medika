@extends('layouts.app')
@section('title', 'Daftar Berobat - RS Cahya Medika')
@section('page-title', 'Daftar Berobat Baru')

@push('styles')
<style>
.step-bar { display:flex; align-items:center; margin-bottom:32px; }
.step { display:flex; align-items:center; gap:10px; }
.step-num {
    width:34px; height:34px; border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    font-size:13px; font-weight:700; flex-shrink:0;
    transition: all 0.3s;
}
.step-num.active { background:var(--accent); color:white; box-shadow:0 4px 12px rgba(6,182,212,0.4); }
.step-num.done { background:var(--success); color:white; }
.step-num.idle { background:var(--gray-100); color:var(--gray-400); }
.step-label { font-size:12px; font-weight:600; }
.step-label.active { color:var(--accent); }
.step-label.done { color:var(--success); }
.step-label.idle { color:var(--gray-400); }
.step-line { flex:1; height:2px; background:var(--gray-200); margin:0 10px; }
.step-line.done { background:var(--success); }

.poli-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:10px; }
.poli-card {
    border:2px solid var(--gray-200); border-radius:12px; padding:14px 10px;
    text-align:center; cursor:pointer; transition:all 0.2s;
    background:white;
}
.poli-card:hover { border-color:var(--accent); background:#f0f9ff; }
.poli-card.selected { border-color:var(--accent); background:#e0f2fe; }
.poli-icon { font-size:24px; margin-bottom:6px; }
.poli-name { font-size:11px; font-weight:700; color:#0c4a6e; line-height:1.3; }
.poli-info { font-size:10px; color:#94a3b8; margin-top:2px; }

.dokter-card {
    border:2px solid var(--gray-200); border-radius:14px; padding:16px;
    cursor:pointer; transition:all 0.2s; display:flex; align-items:center; gap:14px;
    background:white; margin-bottom:10px;
}
.dokter-card:hover { border-color:var(--accent); }
.dokter-card.selected { border-color:var(--accent); background:#e0f2fe; }
.dokter-avatar {
    width:44px; height:44px; background:#e0f2fe; border-radius:12px;
    display:flex; align-items:center; justify-content:center; font-size:20px; flex-shrink:0;
}
.jadwal-tag {
    display:inline-block; padding:2px 8px; border-radius:6px;
    font-size:10px; font-weight:600; background:#f1f5f9; color:#475569; margin:2px;
}

.booking-summary {
    background:linear-gradient(135deg,#0c4a6e,#0891b2);
    border-radius:16px; padding:22px; color:white; margin-bottom:24px;
}
.booking-summary .row {
    display:flex; justify-content:space-between; align-items:center;
    padding:8px 0; border-bottom:1px solid rgba(255,255,255,0.1); font-size:13px;
}
.booking-summary .row:last-child { border:none; }
.booking-summary .row .label { opacity:0.7; }
.booking-summary .row .val { font-weight:700; }

.jam-grid { display:grid; grid-template-columns:repeat(5,1fr); gap:8px; }
.jam-btn {
    padding:8px; border:2px solid var(--gray-200); border-radius:10px;
    text-align:center; font-size:12px; font-weight:600; cursor:pointer;
    transition:all 0.2s; background:white; color:#475569;
}
.jam-btn:hover { border-color:var(--accent); color:var(--accent); }
.jam-btn.selected { border-color:var(--accent); background:var(--accent); color:white; }
.jam-btn.full { background:#fee2e2; border-color:#fecaca; color:#dc2626; cursor:not-allowed; opacity:0.6; }

@media(max-width:768px){
    .poli-grid { grid-template-columns:repeat(2,1fr); }
    .jam-grid { grid-template-columns:repeat(3,1fr); }
}
</style>
@endpush

@section('content')
<div style="max-width:860px;margin:0 auto">

<!-- BREADCRUMB -->
<div style="display:flex;align-items:center;gap:8px;margin-bottom:20px;font-size:13px;color:#64748b">
    <a href="{{ route('pasien.dashboard') }}" style="color:#0891b2;text-decoration:none">Beranda</a>
    <i class="fas fa-chevron-right" style="font-size:10px"></i>
    <span>Daftar Berobat</span>
</div>

<!-- STEP BAR -->
<div class="step-bar">
    <div class="step">
        <div class="step-num active" id="sn1">1</div>
        <div class="step-label active" id="sl1">Pilih Poli</div>
    </div>
    <div class="step-line" id="line1"></div>
    <div class="step">
        <div class="step-num idle" id="sn2">2</div>
        <div class="step-label idle" id="sl2">Pilih Dokter</div>
    </div>
    <div class="step-line" id="line2"></div>
    <div class="step">
        <div class="step-num idle" id="sn3">3</div>
        <div class="step-label idle" id="sl3">Jadwal & Keluhan</div>
    </div>
    <div class="step-line" id="line3"></div>
    <div class="step">
        <div class="step-num idle" id="sn4">4</div>
        <div class="step-label idle" id="sl4">Konfirmasi</div>
    </div>
</div>

<form action="{{ route('pasien.pendaftaran.store') }}" method="POST" id="formDaftar">
@csrf

<!-- ===== STEP 1: POLI ===== -->
<div id="step1">
    <div class="card">
        <div class="card-header">
            <div class="card-title">🏥 Pilih Poli / Klinik</div>
            <span style="font-size:12px;color:#94a3b8">Wajib dipilih</span>
        </div>
        <div class="card-body">
            <div class="poli-grid" id="poliGrid">
                @foreach($poli as $p)
                <div class="poli-card" data-id="{{ $p->id }}" data-nama="{{ $p->nama }}" onclick="pilihPoli({{ $p->id }}, '{{ $p->nama }}')">
                    <div class="poli-icon">{{ $p->icon ?? '🏥' }}</div>
                    <div class="poli-name">{{ $p->nama }}</div>
                    <div class="poli-info">Lantai {{ $p->lantai ?? '1' }}</div>
                    <div class="poli-info">{{ $p->jam_buka }} - {{ $p->jam_tutup }}</div>
                </div>
                @endforeach
            </div>
            <input type="hidden" name="poli_id" id="poliId">
            <div id="poliError" style="color:#dc2626;font-size:12px;margin-top:10px;display:none">
                ⚠️ Silakan pilih poli terlebih dahulu
            </div>
        </div>
    </div>
    <div style="display:flex;justify-content:flex-end;margin-top:16px">
        <button type="button" onclick="nextStep(1)" class="btn btn-accent">
            Lanjut: Pilih Dokter <i class="fas fa-arrow-right"></i>
        </button>
    </div>
</div>

<!-- ===== STEP 2: DOKTER ===== -->
<div id="step2" style="display:none">
    <div class="card">
        <div class="card-header">
            <div class="card-title">👨‍⚕️ Pilih Dokter</div>
            <div id="poliSelected" style="font-size:12px;color:#0891b2;font-weight:600"></div>
        </div>
        <div class="card-body">
            <div id="dokterList">
                <div style="text-align:center;padding:30px;color:#94a3b8">
                    <i class="fas fa-spinner fa-spin" style="font-size:24px;margin-bottom:8px"></i>
                    <div>Memuat daftar dokter...</div>
                </div>
            </div>
            <input type="hidden" name="dokter_id" id="dokterId">
            <div id="dokterError" style="color:#dc2626;font-size:12px;margin-top:10px;display:none">
                ⚠️ Silakan pilih dokter terlebih dahulu
            </div>
        </div>
    </div>
    <div style="display:flex;justify-content:space-between;margin-top:16px">
        <button type="button" onclick="prevStep(2)" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Kembali
        </button>
        <button type="button" onclick="nextStep(2)" class="btn btn-accent">
            Lanjut: Jadwal <i class="fas fa-arrow-right"></i>
        </button>
    </div>
</div>

<!-- ===== STEP 3: JADWAL & KELUHAN ===== -->
<div id="step3" style="display:none">
    <div class="card" style="margin-bottom:16px">
        <div class="card-header">
            <div class="card-title">📅 Pilih Jadwal Kunjungan</div>
        </div>
        <div class="card-body">
            <div class="grid grid-2" style="gap:16px;margin-bottom:20px">
                <div class="form-group">
                    <label class="form-label">Tanggal Kunjungan *</label>
                    <input type="date" name="tanggal_kunjungan" id="tanggalInput" class="form-control"
                        min="{{ today()->format('Y-m-d') }}" required onchange="loadJadwal()">
                    @error('tanggal_kunjungan')
                        <div style="color:#dc2626;font-size:11px;margin-top:4px">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Jenis Kunjungan *</label>
                    <select name="jenis_kunjungan" class="form-select" required>
                        <option value="baru">Pasien Baru</option>
                        <option value="kontrol">Kontrol / Lanjutan</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Pilih Jam Kunjungan *</label>
                <div class="jam-grid" id="jamGrid">
                    <div style="grid-column:1/-1;color:#94a3b8;font-size:13px">Pilih tanggal terlebih dahulu</div>
                </div>
                <input type="hidden" name="jam_kunjungan" id="jamInput">
                <div id="jamError" style="color:#dc2626;font-size:12px;margin-top:8px;display:none">⚠️ Pilih jam kunjungan</div>
            </div>

            <div id="antrianInfo" style="display:none;margin-top:12px;padding:12px 14px;background:#f0f9ff;border:1px solid #bae6fd;border-radius:10px;font-size:12px;color:#0369a1"></div>
        </div>
    </div>

    <div class="card" style="margin-bottom:16px">
        <div class="card-header">
            <div class="card-title">🩺 Keluhan Utama</div>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label class="form-label">Deskripsikan keluhan Anda *</label>
                <textarea name="keluhan" id="keluhan" class="form-control" rows="4"
                    placeholder="Contoh: Sakit kepala sejak 3 hari yang lalu, disertai demam dan mual..." required
                    oninput="updateKeluhan(this.value)" maxlength="500"></textarea>
                <div style="font-size:11px;color:#94a3b8;margin-top:4px">
                    <span id="keluhanCount">0</span>/500 karakter
                </div>
                @error('keluhan')
                    <div style="color:#dc2626;font-size:11px;margin-top:4px">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div style="display:flex;justify-content:space-between;margin-top:16px">
        <button type="button" onclick="prevStep(3)" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Kembali
        </button>
        <button type="button" onclick="nextStep(3)" class="btn btn-accent">
            Lanjut: Konfirmasi <i class="fas fa-arrow-right"></i>
        </button>
    </div>
</div>

<!-- ===== STEP 4: KONFIRMASI ===== -->
<div id="step4" style="display:none">
    <div class="card" style="margin-bottom:16px">
        <div class="card-header">
            <div class="card-title">✅ Konfirmasi Pendaftaran</div>
        </div>
        <div class="card-body">
            <div class="booking-summary">
                <div style="font-size:14px;font-weight:800;margin-bottom:16px;opacity:0.8;text-transform:uppercase;letter-spacing:1px">Detail Pendaftaran</div>
                <div class="row"><span class="label">Nama Pasien</span><span class="val">{{ $pasien->nama_lengkap }}</span></div>
                <div class="row"><span class="label">No. Rekam Medis</span><span class="val">{{ $pasien->no_rm }}</span></div>
                <div class="row"><span class="label">Poli</span><span class="val" id="konfPoliNama">-</span></div>
                <div class="row"><span class="label">Dokter</span><span class="val" id="konfDokterNama">-</span></div>
                <div class="row"><span class="label">Tanggal</span><span class="val" id="konfTanggal">-</span></div>
                <div class="row"><span class="label">Jam</span><span class="val" id="konfJam">-</span></div>
                <div class="row"><span class="label">Jenis Kunjungan</span><span class="val" id="konfJenis">-</span></div>
                <div class="row"><span class="label">Biaya Estimasi</span><span class="val">Rp 150.000 - 200.000</span></div>
            </div>

            <div id="konfKeluhan" style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:16px;margin-bottom:16px">
                <div style="font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;margin-bottom:6px">Keluhan Utama</div>
                <div style="font-size:13px;color:#1e293b;line-height:1.6" id="konfKeluhanText">-</div>
            </div>

            <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:12px;padding:14px 16px;font-size:12px;color:#92400e">
                <i class="fas fa-info-circle" style="margin-right:6px"></i>
                <strong>Catatan:</strong> RS Cahya Medika saat ini melayani pasien umum (non-BPJS). Harap datang 15 menit sebelum jadwal yang dipilih. Bawa KTP dan dokumen medis terkait.
            </div>
        </div>
    </div>

    <div style="display:flex;justify-content:space-between;align-items:center">
        <button type="button" onclick="prevStep(4)" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Kembali
        </button>
        <button type="submit" class="btn btn-primary" id="btnSubmit">
            <i class="fas fa-check-circle"></i> Konfirmasi & Daftar Sekarang
        </button>
    </div>
</div>

</form>
</div>

@endsection

@push('scripts')
<script>
let selectedPoli = { id: null, nama: '' };
let selectedDokter = { id: null, nama: '', spesialisasi: '' };
let selectedJam = null;

function pilihPoli(id, nama) {
    document.querySelectorAll('.poli-card').forEach(c => c.classList.remove('selected'));
    document.querySelector(`.poli-card[data-id="${id}"]`).classList.add('selected');
    document.getElementById('poliId').value = id;
    selectedPoli = { id, nama };
    document.getElementById('poliError').style.display = 'none';
}

function pilihDokter(id, nama, spesialisasi) {
    document.querySelectorAll('.dokter-card').forEach(c => c.classList.remove('selected'));
    document.getElementById('doc-' + id).classList.add('selected');
    document.getElementById('dokterId').value = id;
    selectedDokter = { id, nama, spesialisasi };
    document.getElementById('dokterError').style.display = 'none';

    // Update konfirmasi
    document.getElementById('konfDokterNama').textContent = nama;
}

function loadDokter(poliId) {
    const list = document.getElementById('dokterList');
    list.innerHTML = '<div style="text-align:center;padding:30px;color:#94a3b8"><i class="fas fa-spinner fa-spin" style="font-size:24px"></i><div>Memuat dokter...</div></div>';

    fetch(`{{ route('pasien.api.dokter') }}?poli_id=${poliId}`)
        .then(r => r.json())
        .then(data => {
            if (!data.length) {
                list.innerHTML = '<div style="text-align:center;padding:30px;color:#94a3b8">Tidak ada dokter tersedia untuk poli ini</div>';
                return;
            }
            list.innerHTML = data.map(d => `
                <div class="dokter-card" id="doc-${d.id}" onclick="pilihDokter(${d.id}, '${d.nama.replace(/'/g,"\\'")}', '${d.spesialisasi}')">
                    <div class="dokter-avatar">👨‍⚕️</div>
                    <div style="flex:1">
                        <div style="font-weight:700;font-size:14px;color:#0c4a6e">${d.nama}</div>
                        <div style="font-size:12px;color:#64748b;margin-top:2px">${d.spesialisasi}</div>
                        <div style="margin-top:6px">
                            ${d.jadwal ? Object.entries(d.jadwal).filter(([k,v])=>v.aktif).map(([k,v])=>`<span class="jadwal-tag">${k.charAt(0).toUpperCase()+k.slice(1)}</span>`).join('') : ''}
                        </div>
                    </div>
                    <div style="text-align:right">
                        <i class="fas fa-chevron-right" style="color:#94a3b8"></i>
                    </div>
                </div>
            `).join('');
        })
        .catch(() => {
            list.innerHTML = '<div style="text-align:center;padding:30px;color:#dc2626">Gagal memuat data dokter</div>';
        });
}

function loadJadwal() {
    const tanggal = document.getElementById('tanggalInput').value;
    const dokterId = document.getElementById('dokterId').value;
    if (!tanggal || !dokterId) return;

    document.getElementById('jamGrid').innerHTML = '<div style="grid-column:1/-1;color:#94a3b8;font-size:13px"><i class="fas fa-spinner fa-spin"></i> Memuat jadwal...</div>';

    fetch(`{{ route('pasien.api.jadwal') }}?dokter_id=${dokterId}&tanggal=${tanggal}`)
        .then(r => r.json())
        .then(data => {
            // Generate jam slots
            const slots = [];
            for (let h = 8; h <= 13; h++) {
                slots.push(`${String(h).padStart(2,'0')}:00`);
                if (h < 13) slots.push(`${String(h).padStart(2,'0')}:30`);
            }

            document.getElementById('jamGrid').innerHTML = slots.map(jam => `
                <div class="jam-btn" onclick="pilihJam('${jam}', this)">${jam}</div>
            `).join('');

            const info = document.getElementById('antrianInfo');
            info.style.display = 'block';
            info.innerHTML = `📊 Antrian hari ini: <strong>${data.antrian_hari_ini}</strong> pasien &nbsp;|&nbsp; Sisa kuota: <strong>${data.sisa_kuota}</strong> dari ${data.kuota}`;

            // Update tanggal konfirmasi
            const tgl = new Date(tanggal);
            document.getElementById('konfTanggal').textContent = tgl.toLocaleDateString('id-ID', {weekday:'long', day:'numeric', month:'long', year:'numeric'});
        });
}

function pilihJam(jam, el) {
    document.querySelectorAll('.jam-btn').forEach(b => b.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('jamInput').value = jam;
    selectedJam = jam;
    document.getElementById('konfJam').textContent = jam + ' WIB';
    document.getElementById('jamError').style.display = 'none';
}

function updateKeluhan(val) {
    document.getElementById('keluhanCount').textContent = val.length;
    document.getElementById('konfKeluhanText').textContent = val || '-';
}

function nextStep(from) {
    if (from === 1) {
        if (!selectedPoli.id) { document.getElementById('poliError').style.display = 'block'; return; }
        document.getElementById('poliSelected').textContent = '📍 ' + selectedPoli.nama;
        document.getElementById('konfPoliNama').textContent = selectedPoli.nama;
        loadDokter(selectedPoli.id);
    }
    if (from === 2) {
        if (!selectedDokter.id) { document.getElementById('dokterError').style.display = 'block'; return; }
    }
    if (from === 3) {
        if (!document.getElementById('tanggalInput').value) { alert('Pilih tanggal kunjungan'); return; }
        if (!selectedJam) { document.getElementById('jamError').style.display = 'block'; return; }
        if (!document.getElementById('keluhan').value.trim()) { alert('Keluhan wajib diisi'); return; }
        const jenis = document.querySelector('[name="jenis_kunjungan"]').value;
        document.getElementById('konfJenis').textContent = jenis === 'baru' ? 'Pasien Baru' : 'Kontrol / Lanjutan';
    }

    document.getElementById(`step${from}`).style.display = 'none';
    document.getElementById(`step${from+1}`).style.display = 'block';

    // Update step indicators
    updateSteps(from + 1);
}

function prevStep(from) {
    document.getElementById(`step${from}`).style.display = 'none';
    document.getElementById(`step${from-1}`).style.display = 'block';
    updateSteps(from - 1);
}

function updateSteps(current) {
    for (let i = 1; i <= 4; i++) {
        const num = document.getElementById(`sn${i}`);
        const label = document.getElementById(`sl${i}`);
        if (i < current) {
            num.className = 'step-num done'; num.innerHTML = '<i class="fas fa-check" style="font-size:12px"></i>';
            label.className = 'step-label done';
        } else if (i === current) {
            num.className = 'step-num active'; num.textContent = i;
            label.className = 'step-label active';
        } else {
            num.className = 'step-num idle'; num.textContent = i;
            label.className = 'step-label idle';
        }
        if (i < 4) {
            document.getElementById(`line${i}`).className = i < current ? 'step-line done' : 'step-line';
        }
    }
}

document.getElementById('formDaftar').addEventListener('submit', function() {
    const btn = document.getElementById('btnSubmit');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
});
</script>
@endpush
