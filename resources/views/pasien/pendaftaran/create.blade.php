@extends('layouts.app')
@section('title', 'Daftar Berobat - RS Cahya Medika')
@section('page-title', 'Daftar Berobat Baru')

@push('styles')
<style>
.step-bar { display:flex; align-items:center; margin-bottom:24px; background:#ffffff; padding:16px 20px; border-radius:16px; border:1px solid #e2e8f0; box-shadow:0 1px 3px rgba(15,23,42,0.03); }
.step { display:flex; align-items:center; gap:10px; }
.step-num {
    width:36px; height:36px; border-radius:10px;
    display:flex; align-items:center; justify-content:center;
    font-size:14px; font-weight:800; flex-shrink:0;
    transition: all 0.3s ease;
}
.step-num.active { background: linear-gradient(135deg, #0c4a6e, #0891b2); color:white; box-shadow:0 4px 12px rgba(8,145,178,0.3); }
.step-num.done { background:#10b981; color:white; }
.step-num.idle { background:#f1f5f9; color:#94a3b8; }
.step-label { font-size:13px; font-weight:700; }
.step-label.active { color:#0891b2; }
.step-label.done { color:#10b981; }
.step-label.idle { color:#94a3b8; }
.step-line { flex:1; height:3px; background:#e2e8f0; margin:0 12px; border-radius:3px; transition: background 0.3s; }
.step-line.done { background:#10b981; }

.section-head {
    background: #f1f5f9; border-left: 4px solid #0891b2;
    padding: 10px 14px; font-weight: 800; font-size: 13px; color: #0c4a6e;
    text-transform: uppercase; margin: 18px 0 14px; border-radius: 0 8px 8px 0;
}

/* HIGH CONTRAST VIBRANT BUTTONS */
.btn-accent {
    background: linear-gradient(135deg, #0284c7, #0891b2) !important;
    color: #ffffff !important;
    border: none !important;
    font-weight: 700 !important;
    padding: 12px 26px !important;
    border-radius: 12px !important;
    box-shadow: 0 4px 14px rgba(8, 145, 178, 0.35) !important;
    font-size: 13.5px !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 8px !important;
    transition: all 0.25s ease !important;
    cursor: pointer !important;
}
.btn-accent:hover {
    background: linear-gradient(135deg, #0369a1, #0c4a6e) !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 18px rgba(8, 145, 178, 0.45) !important;
}

.poli-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-top:8px; }
.poli-card {
    border: 1.5px solid #e2e8f0; border-radius: 16px; padding: 22px 14px;
    text-align: center; cursor: pointer; transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    background: #ffffff; position: relative; box-shadow: 0 2px 6px rgba(15,23,42,0.03);
}
.poli-card:hover { border-color: #0891b2; transform: translateY(-3px); box-shadow: 0 10px 20px -5px rgba(8,145,178,0.15); }
.poli-card.selected { border-color: #0891b2; background: linear-gradient(135deg, #f0f9ff, #e0f2fe); box-shadow: 0 0 0 3px rgba(8,145,178,0.25); }
.poli-icon {
    width: 48px; height: 48px; background: #e0f2fe; color: #0891b2;
    border-radius: 12px; display: flex; align-items: center; justify-content: center;
    font-size: 22px; margin: 0 auto 12px; transition: all 0.2s;
}
.poli-card.selected .poli-icon { background: #0891b2; color: #ffffff; }
.poli-name { font-size: 13.5px; font-weight: 800; color: #0f172a; line-height: 1.3; }
.poli-info { font-size: 11px; color: #64748b; margin-top: 4px; font-weight: 500; }

.dokter-card {
    border: 1.5px solid #e2e8f0; border-radius: 16px; padding: 18px 20px;
    cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 16px;
    background: white; margin-bottom: 12px; box-shadow: 0 2px 6px rgba(15,23,42,0.03);
}
.dokter-card:hover { border-color: #0891b2; transform: translateY(-2px); box-shadow: 0 8px 16px -4px rgba(8,145,178,0.12); }
.dokter-card.selected { border-color: #0891b2; background: #f0f9ff; box-shadow: 0 0 0 3px rgba(8,145,178,0.2); }
.dokter-avatar {
    width: 48px; height: 48px; background: linear-gradient(135deg, #e0f2fe, #bae6fd); border-radius: 12px;
    display: flex; align-items: center; justify-content: center; font-size: 22px; color: #0284c7; flex-shrink: 0;
}
.jadwal-tag {
    display: inline-block; padding: 3px 9px; border-radius: 6px;
    font-size: 11px; font-weight: 600; background: #f1f5f9; color: #475569; margin: 2px;
}

.booking-summary {
    background: linear-gradient(135deg,#0c4a6e,#0891b2);
    border-radius: 18px; padding: 24px; color: white; margin-bottom: 24px; box-shadow: 0 10px 25px -5px rgba(8,145,178,0.3);
}
.booking-summary .row {
    display: flex; justify-content: space-between; align-items: center;
    padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.15); font-size: 13.5px;
}
.booking-summary .row:last-child { border: none; }
.booking-summary .row .label { opacity: 0.8; font-weight: 500; }
.booking-summary .row .val { font-weight: 800; }

.jam-grid { display: grid; grid-template-columns: repeat(6, 1fr); gap: 10px; margin-top: 8px; }
.jam-btn {
    padding: 10px; border: 1.5px solid #cbd5e1; border-radius: 10px;
    text-align: center; font-size: 12.5px; font-weight: 700; cursor: pointer;
    transition: all 0.2s; background: white; color: #334155; box-shadow: 0 1px 3px rgba(15,23,42,0.03);
}
.jam-btn:hover { border-color: #0891b2; color: #0891b2; background: #f0f9ff; }
.jam-btn.selected { border-color: #0891b2; background: #0891b2; color: white; box-shadow: 0 4px 10px rgba(8,145,178,0.3); }

@media(max-width:768px){
    .poli-grid { grid-template-columns: repeat(2, 1fr); }
    .jam-grid { grid-template-columns: repeat(3, 1fr); }
}
</style>
@endpush

@section('content')
<div style="max-width:1040px;margin:0 auto">

<!-- BREADCRUMB -->
<div style="display:flex;align-items:center;gap:8px;margin-bottom:16px;font-size:13px;color:#64748b">
    <a href="{{ route('pasien.dashboard') }}" style="color:#0891b2;text-decoration:none">Beranda</a>
    <i class="fas fa-chevron-right" style="font-size:10px"></i>
    <span>Daftar Berobat</span>
</div>

<!-- STEP BAR -->
<div class="step-bar">
    <div class="step">
        <div class="step-num active" id="sn1">1</div>
        <div class="step-label active" id="sl1">Formulir Identitas Pasien</div>
    </div>
    <div class="step-line" id="line1"></div>
    <div class="step">
        <div class="step-num idle" id="sn2">2</div>
        <div class="step-label idle" id="sl2">Pilih Poli & Dokter</div>
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

<!-- BANNER ESTIMASI JARAK LOKASI PASIEN -->
<div style="background:linear-gradient(135deg,#f0f9ff,#e0f2fe);border:1px solid #bae6fd;border-radius:14px;padding:14px 18px;margin-bottom:20px;box-shadow:0 4px 14px rgba(2,132,199,0.06)">
    <div style="display:flex;align-items:center;gap:14px">
        <div style="width:38px;height:38px;background:#0284c7;color:white;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0">
            <i class="fas fa-location-dot"></i>
        </div>
        <div style="flex:1">
            <div style="font-size:13.5px;font-weight:800;color:#0f172a">Deteksi Jarak Tempuh Ke RS (Otomatis Presisi)</div>
            <div style="font-size:12px;color:#0369a1;margin-top:2px" id="locationStatusText">
                <i class="fas fa-route"></i> Mengukur lokasi & jarak tempuh ke RSUD Bondowoso...
            </div>
        </div>
        <span class="badge" style="background:#0284c7;color:white;font-weight:700;padding:5px 10px;border-radius:6px;font-size:11px">Pendaftaran Dari Rumah</span>
    </div>
</div>

<form action="{{ route('pasien.pendaftaran.store') }}" method="POST" id="formDaftar">
@csrf
<input type="hidden" name="jarak_km" id="inputJarakKm" value="3.5 km">
<input type="hidden" name="estimasi_menit" id="inputEstimasiMenit" value="12">

<!-- ===== STEP 1: FORMULIR IDENTITAS PASIEN LENGKAP ===== -->
<div id="step1">
    <div class="card">
        <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
            <div>
                <div class="card-title">📝 Step 1: Formulir Identitas Pasien (Wajib Lengkap)</div>
                <div style="font-size:12px;color:#64748b;margin-top:2px">Seluruh data wajib diisi dengan benar untuk registrasi & rekam medis rumah sakit.</div>
            </div>
            <div>
                <label class="btn" style="background:linear-gradient(135deg,#059669,#10b981);color:white;font-weight:700;font-size:12.5px;cursor:pointer;margin:0;display:inline-flex;align-items:center;gap:8px;padding:8px 16px;border-radius:10px;box-shadow:0 4px 10px rgba(16,185,129,0.25)">
                    <i class="fas fa-camera"></i> Scan KTP
                    <input type="file" id="ktpFileInput" accept="image/*" style="display:none" onchange="uploadOcrKtp(this)">
                </label>
            </div>
        </div>
        <div class="card-body">
            
            <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:12px 16px;margin-bottom:20px;font-size:12px;color:#166534">
                <i class="fas fa-magic" style="color:#059669;margin-right:6px"></i>
                <strong>Scan KTP / Isi Manual:</strong> Anda dapat mengunggah foto KTP melalui tombol <strong>"Scan KTP"</strong> untuk pengisian otomatis, atau memilih dan mengisi langsung setiap bidang pada form di bawah.
            </div>

            <!-- BAGIAN 1: DATA UMUM PASIEN -->
            <div class="section-head"><i class="fas fa-user-circle"></i> Data Umum Pasien</div>
            
            <div class="grid grid-2" style="gap:16px;margin-bottom:16px">
                <div class="form-group">
                    <label class="form-label">No. KTP / SIM / NIK</label>
                    <input type="text" name="nik" id="fieldNik" class="form-control" value="{{ old('nik', $pasien->nik ?? '') }}" placeholder="16 Digit NIK KTP" required maxlength="16">
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Lengkap Pasien</label>
                    <input type="text" name="nama_lengkap" id="fieldNama" class="form-control" value="{{ old('nama_lengkap', $pasien->nama_lengkap ?? '') }}" placeholder="Sesuai KTP / Identitas" required>
                </div>
            </div>

            <div class="grid grid-3" style="gap:16px;margin-bottom:16px">
                <div class="form-group">
                    <label class="form-label">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" id="fieldTempatLahir" class="form-control" value="{{ old('tempat_lahir', $pasien->tempat_lahir ?? '') }}" placeholder="Kota Tempat Lahir" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" id="fieldTanggalLahir" class="form-control" value="{{ old('tanggal_lahir', optional($pasien->tanggal_lahir ?? null)->format('Y-m-d') ?? '') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" id="fieldJenisKelamin" class="form-select" required>
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="L" {{ old('jenis_kelamin', $pasien->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin', $pasien->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-3" style="gap:16px;margin-bottom:16px">
                <div class="form-group">
                    <label class="form-label">Pekerjaan Pasien</label>
                    <select name="pekerjaan" id="fieldPekerjaan" class="form-select" required>
                        <option value="">-- Pilih Pekerjaan --</option>
                        @foreach(['Karyawan Swasta', 'PNS / TNI / Polri', 'Wiraswasta', 'Petani / Peternak', 'Nelayan', 'Buruh Harian Lepas', 'Ibu Rumah Tangga', 'Pelajar / Mahasiswa', 'Belum / Tidak Bekerja', 'Lainnya'] as $pk)
                            <option value="{{ $pk }}" {{ old('pekerjaan', $pasien->pekerjaan ?? '') == $pk ? 'selected' : '' }}>{{ $pk }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Agama</label>
                    <select name="agama" id="fieldAgama" class="form-select" required>
                        <option value="">-- Pilih Agama --</option>
                        @foreach(['Islam', 'Kristen', 'Katolik', 'Budha', 'Hindu', 'Lain-lain'] as $ag)
                            <option value="{{ $ag }}" {{ old('agama', $pasien->agama ?? '') == $ag ? 'selected' : '' }}>{{ $ag }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Pendidikan Terakhir</label>
                    <select name="pendidikan" id="fieldPendidikan" class="form-select" required>
                        <option value="">-- Pilih Pendidikan --</option>
                        @foreach(['SD', 'SMP', 'SMA', 'Diploma', 'Sarjana', 'Lain-lain'] as $pd)
                            <option value="{{ $pd }}" {{ old('pendidikan', $pasien->pendidikan ?? '') == $pd ? 'selected' : '' }}>{{ $pd }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-3" style="gap:16px;margin-bottom:16px">
                <div class="form-group">
                    <label class="form-label">Status Perkawinan</label>
                    <select name="status_pernikahan" id="fieldStatusPernikahan" class="form-select" required>
                        <option value="">-- Pilih Status Perkawinan --</option>
                        @foreach(['Belum Kawin', 'Kawin', 'Duda', 'Janda'] as $st)
                            <option value="{{ $st }}" {{ old('status_pernikahan', $pasien->status_pernikahan ?? '') == $st ? 'selected' : '' }}>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Warga Negara</label>
                    <select name="warga_negara" id="fieldWargaNegara" class="form-select" required>
                        <option value="">-- Pilih Warga Negara --</option>
                        <option value="WNI" {{ old('warga_negara', $pasien->warga_negara ?? '') == 'WNI' ? 'selected' : '' }}>WNI (Warga Negara Indonesia)</option>
                        <option value="WNA" {{ old('warga_negara', $pasien->warga_negara ?? '') == 'WNA' ? 'selected' : '' }}>WNA (Warga Negara Asing)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Golongan Darah</label>
                    <select name="golongan_darah" id="fieldGolonganDarah" class="form-select" required>
                        <option value="">-- Pilih Golongan Darah --</option>
                        @foreach(['A', 'B', 'AB', 'O', 'Tidak Tahu'] as $gd)
                            <option value="{{ $gd }}" {{ old('golongan_darah', $pasien->golongan_darah ?? '') == $gd ? 'selected' : '' }}>{{ $gd }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-2" style="gap:16px;margin-bottom:16px">
                <div class="form-group">
                    <label class="form-label">Nama Ibu Kandung</label>
                    <input type="text" name="nama_ibu" id="fieldNamaIbu" class="form-control" value="{{ old('nama_ibu', $pasien->nama_ibu ?? '') }}" placeholder="Nama Ibu Kandung">
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Ayah Kandung</label>
                    <input type="text" name="nama_ayah" id="fieldNamaAyah" class="form-control" value="{{ old('nama_ayah', $pasien->nama_ayah ?? '') }}" placeholder="Nama Ayah Kandung">
                </div>
            </div>

            <div class="form-group" style="margin-bottom:16px">
                <label class="form-label">Alamat Lengkap KTP (Jalan / Dusun / RT / RW)</label>
                <textarea name="alamat" id="fieldAlamat" class="form-control" rows="2" placeholder="Jalan, Dusun, RT/RW" required>{{ old('alamat', $pasien->alamat ?? '') }}</textarea>
            </div>

            <div class="grid grid-4" style="gap:16px;margin-bottom:16px">
                <div class="form-group">
                    <label class="form-label">Provinsi</label>
                    <select name="provinsi" id="fieldProvinsi" class="form-select" required>
                        <option value="">-- Pilih Provinsi --</option>
                        @foreach(['Jawa Timur', 'Jawa Tengah', 'Jawa Barat', 'DKI Jakarta', 'DI Yogyakarta', 'Bali', 'Lainnya'] as $prov)
                            <option value="{{ $prov }}" {{ old('provinsi', $pasien->provinsi ?? '') == $prov ? 'selected' : '' }}>{{ $prov }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Kabupaten / Kota</label>
                    <select name="kabupaten" id="fieldKabupaten" class="form-select" required>
                        <option value="">-- Pilih Kabupaten --</option>
                        @foreach(['Bondowoso', 'Situbondo', 'Jember', 'Banyuwangi', 'Probolinggo', 'Malang', 'Surabaya', 'Lainnya'] as $kab)
                            <option value="{{ $kab }}" {{ old('kabupaten', $pasien->kabupaten ?? '') == $kab ? 'selected' : '' }}>{{ $kab }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Kecamatan</label>
                    <select name="kecamatan" id="fieldKecamatan" class="form-select" required>
                        <option value="">-- Pilih Kecamatan --</option>
                        @foreach(['Bondowoso', 'Cermee', 'Tenggarang', 'Tamanan', 'Maesan', 'Wringin', 'Tapen', 'Pujer', 'Grujugan', 'Sukosari', 'Tlogosari', 'Curahdami', 'Jambesari', 'Klabang', 'Prajekan', 'Sempol', 'Pakem', 'Sumberwringin', 'Binakal', 'Botolinggo', 'Taman Krocok', 'Lainnya'] as $kec)
                            <option value="{{ $kec }}" {{ old('kecamatan', $pasien->kecamatan ?? '') == $kec ? 'selected' : '' }}>{{ $kec }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Desa / Kelurahan</label>
                    <select name="kelurahan" id="fieldKelurahan" class="form-select" required>
                        <option value="">-- Pilih Desa/Kelurahan --</option>
                        @foreach(['Kademangan', 'Badean', 'Dabasah', 'Kotakulon', 'Nangkaan', 'Tamansari', 'Blindungan', 'Dadapkuning', 'Sekarputih', 'Mandiro', 'Lainnya'] as $kel)
                            <option value="{{ $kel }}" {{ old('kelurahan', $pasien->kelurahan ?? '') == $kel ? 'selected' : '' }}>{{ $kel }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-2" style="gap:16px;margin-bottom:16px">
                <div class="form-group">
                    <label class="form-label">No. Telepon / WhatsApp Pasien</label>
                    <input type="text" name="no_hp" id="fieldNoHp" class="form-control" value="{{ old('no_hp', $pasien->no_hp ?? '') }}" placeholder="Contoh: 081234567890" maxlength="13" inputmode="numeric" oninput="formatAndValidatePhone(this)" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Riwayat Alergi Obat / Makanan</label>
                    <input type="text" name="jenis_alergi" id="fieldJenisAlergi" class="form-control" value="{{ old('jenis_alergi', $pasien->jenis_alergi ?? '') }}" placeholder="Sebutkan jenis alergi jika ada / Tidak Ada">
                </div>
            </div>

            <!-- BAGIAN 2: PENANGGUNG JAWAB / KELUARGA TERDEKAT -->
            <div class="section-head"><i class="fas fa-users"></i> Penanggung Jawab / Keluarga Terdekat <span style="font-size:10.5px;font-weight:normal">(Opsional jika berusia &gt; 17 tahun)</span></div>

            <div class="grid grid-3" style="gap:16px;margin-bottom:16px">
                <div class="form-group">
                    <label class="form-label">Nama Penanggung Jawab</label>
                    <input type="text" name="nama_pj" id="fieldNamaPj" class="form-control" value="{{ old('nama_pj', $pasien->nama_pj ?? '') }}" placeholder="Nama Penanggung Jawab / Keluarga">
                </div>
                <div class="form-group">
                    <label class="form-label">Jenis Kelamin Penanggung Jawab</label>
                    <select name="jenis_kelamin_pj" id="fieldJenisKelaminPj" class="form-select">
                        <option value="">-- Pilih Jenis Kelamin PJ --</option>
                        <option value="L" {{ old('jenis_kelamin_pj', $pasien->jenis_kelamin_pj ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin_pj', $pasien->jenis_kelamin_pj ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Hubungan Dengan Pasien</label>
                    <select name="hubungan_pj" id="fieldHubunganPj" class="form-select">
                        <option value="">-- Pilih Hubungan --</option>
                        @foreach(['Orang Tua', 'Anak', 'Suami', 'Istri', 'Lainnya'] as $hb)
                            <option value="{{ $hb }}" {{ old('hubungan_pj', $pasien->hubungan_pj ?? '') == $hb ? 'selected' : '' }}>{{ $hb }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-2" style="gap:16px;margin-bottom:16px">
                <div class="form-group">
                    <label class="form-label">Pekerjaan Penanggung Jawab</label>
                    <input type="text" name="pekerjaan_pj" id="fieldPekerjaanPj" class="form-control" value="{{ old('pekerjaan_pj', $pasien->pekerjaan_pj ?? '') }}" placeholder="Pekerjaan PJ">
                </div>
                <div class="form-group">
                    <label class="form-label">No. Telepon / HP Penanggung Jawab</label>
                    <input type="text" name="no_hp_pj" id="fieldNoHpPj" class="form-control" value="{{ old('no_hp_pj', $pasien->no_hp_pj ?? '') }}" placeholder="Contoh: 081234567890" maxlength="13" inputmode="numeric" oninput="formatAndValidatePhone(this)">
                </div>
            </div>

            <div class="form-group" style="margin-bottom:16px">
                <label class="form-label">Alamat Penanggung Jawab</label>
                <textarea name="alamat_pj" id="fieldAlamatPj" class="form-control" rows="2" placeholder="Alamat lengkap keluarga / penanggung jawab">{{ old('alamat_pj', $pasien->alamat_pj ?? '') }}</textarea>
            </div>

        </div>
    </div>
    <div style="display:flex;justify-content:flex-end;margin-top:16px">
        <button type="button" onclick="nextStep(1)" class="btn-accent">
            Lanjut: Pilih Poli & Dokter <i class="fas fa-arrow-right"></i>
        </button>
    </div>
</div>

<!-- ===== STEP 2: POLI & DOKTER ===== -->
<div id="step2" style="display:none">
    <div class="card" style="margin-bottom:16px">
        <div class="card-header">
            <div class="card-title">🏥 Step 2: Pilih Poliklinik / Spesialis</div>
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

    <div class="card" id="cardDokter" style="display:none">
        <div class="card-header">
            <div class="card-title">👨‍⚕️ Pilih Dokter Spesialis</div>
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
            <i class="fas fa-arrow-left"></i> Kembali ke Formulir Pasien
        </button>
        <button type="button" onclick="nextStep(2)" class="btn-accent">
            Lanjut: Jadwal & Keluhan <i class="fas fa-arrow-right"></i>
        </button>
    </div>
</div>

<!-- ===== STEP 3: JADWAL & KELUHAN ===== -->
<div id="step3" style="display:none">
    <div class="card" style="margin-bottom:16px">
        <div class="card-header">
            <div class="card-title">📅 Step 3: Pilih Tanggal & Jam Kunjungan</div>
        </div>
        <div class="card-body">
            <div class="grid grid-2" style="gap:16px;margin-bottom:20px">
                <div class="form-group">
                    <label class="form-label">Tanggal Kunjungan Berobat</label>
                    <input type="date" name="tanggal_kunjungan" id="tanggalInput" class="form-control"
                        min="{{ today()->format('Y-m-d') }}" value="{{ today()->format('Y-m-d') }}" required onchange="onTanggalChange()">
                </div>
                <div class="form-group">
                    <label class="form-label">Jenis Kunjungan</label>
                    <select name="jenis_kunjungan" class="form-select" required>
                        <option value="baru">Pasien Baru</option>
                        <option value="kontrol">Kontrol / Lanjutan</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Pilih Jam Kunjungan</label>
                <div class="jam-grid" id="jamGrid">
                    <div style="grid-column:1/-1;color:#94a3b8;font-size:13px">Pilih tanggal & dokter terlebih dahulu</div>
                </div>
                <input type="hidden" name="jam_kunjungan" id="jamInput">
                <div id="jamError" style="color:#dc2626;font-size:12px;margin-top:8px;display:none">⚠️ Pilih jam kunjungan</div>
            </div>

            <div id="antrianInfo" style="display:none;margin-top:12px;padding:12px 14px;background:#f0f9ff;border:1px solid #bae6fd;border-radius:10px;font-size:12px;color:#0369a1"></div>
        </div>
    </div>

    <div class="card" style="margin-bottom:16px">
        <div class="card-header">
            <div class="card-title">🩺 Keluhan Utama Pasien</div>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label class="form-label">Deskripsikan keluhan kesehatan Anda</label>
                <textarea name="keluhan" id="keluhan" class="form-control" rows="4"
                    placeholder="Contoh: Sakit kepala sejak 3 hari yang lalu, disertai demam dan mual..." required
                    oninput="updateKeluhan(this.value)" maxlength="500"></textarea>
                <div style="font-size:11px;color:#94a3b8;margin-top:4px">
                    <span id="keluhanCount">0</span>/500 karakter
                </div>
            </div>
        </div>
    </div>

    <div style="display:flex;justify-content:space-between;margin-top:16px">
        <button type="button" onclick="prevStep(3)" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Kembali
        </button>
        <button type="button" onclick="nextStep(3)" class="btn-accent">
            Lanjut: Konfirmasi <i class="fas fa-arrow-right"></i>
        </button>
    </div>
</div>

<!-- ===== STEP 4: KONFIRMASI ===== -->
<div id="step4" style="display:none">
    <div class="card" style="margin-bottom:16px">
        <div class="card-header">
            <div class="card-title">✅ Step 4: Konfirmasi Pendaftaran Berobat</div>
        </div>
        <div class="card-body">
            <div class="booking-summary">
                <div style="font-size:14px;font-weight:800;margin-bottom:16px;opacity:0.8;text-transform:uppercase;letter-spacing:1px">Detail Pendaftaran Pasien</div>
                <div class="row"><span class="label">Nama Pasien</span><span class="val" id="konfNama">-</span></div>
                <div class="row"><span class="label">NIK KTP</span><span class="val" id="konfNik">-</span></div>
                <div class="row"><span class="label">Poli Tujuan</span><span class="val" id="konfPoliNama">-</span></div>
                <div class="row"><span class="label">Dokter Spesialis</span><span class="val" id="konfDokterNama">-</span></div>
                <div class="row"><span class="label">Tanggal Kunjungan</span><span class="val" id="konfTanggal">-</span></div>
                <div class="row"><span class="label">Jam Kunjungan</span><span class="val" id="konfJam">-</span></div>
                <div class="row"><span class="label">Estimasi Jarak Tempuh</span><span class="val" id="konfJarak">-</span></div>
                <div class="row"><span class="label">Deposit Awal Berobat</span><span class="val">Rp 200.000 (Dibayar di Loket RS)</span></div>
            </div>

            <div id="konfKeluhan" style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:16px;margin-bottom:16px">
                <div style="font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;margin-bottom:6px">Keluhan Utama</div>
                <div style="font-size:13px;color:#1e293b;line-height:1.6" id="konfKeluhanText">-</div>
            </div>

            <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:12px;padding:14px 16px;font-size:12px;color:#92400e">
                <i class="fas fa-info-circle" style="margin-right:6px"></i>
                <strong>Petunjuk Kedatangan:</strong> Setelah mendaftar dari rumah, Anda akan mendapatkan <strong>Kode Booking & Barcode Tiket</strong>. Tunjukkan barcode tersebut ke Loket Pendaftaran RS Cahya Medika untuk pengambilan nomor antrean fisik & deposit.
            </div>
        </div>
    </div>

    <div style="display:flex;justify-content:space-between;align-items:center">
        <button type="button" onclick="prevStep(4)" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Kembali
        </button>
        <button type="button" onclick="confirmSubmitBooking()" class="btn-accent" id="btnSubmit" style="background:linear-gradient(135deg,#059669,#10b981)!important">
            <i class="fas fa-check-circle"></i> Konfirmasi & Kirim Pendaftaran
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

// Browser location access permission prompt on page load
document.addEventListener('DOMContentLoaded', function() {
    deteksiLokasiGeolocAuto();
});

function deteksiLokasiGeolocAuto() {
    const statusText = document.getElementById('locationStatusText');
    if (!navigator.geolocation) {
        if (statusText) statusText.innerHTML = 'Browser tidak mendukung Geolocation. Jarak RS: 3.5 km (&plusmn; 12 Menit).';
        return;
    }

    if (statusText) {
        statusText.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Meminta akses lokasi browser & mengukur rute jalan presisi ke RS...';
    }

    const geoOptions = { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 };

    navigator.geolocation.getCurrentPosition(function(pos) {
        const pLat = pos.coords.latitude;
        const pLng = pos.coords.longitude;
        // RS Cahya Medika Bondowoso: -7.9135, 113.8214
        const rsLat = -7.9135;
        const rsLng = 113.8214;

        // Query real driving road distance via OSRM routing API (matches Google Maps road navigation)
        const osrmUrl = `https://router.project-osrm.org/route/v1/driving/${pLng},${pLat};${rsLng},${rsLat}?overview=false`;
        
        fetch(osrmUrl)
            .then(r => r.json())
            .then(routeData => {
                if (routeData && routeData.routes && routeData.routes.length > 0) {
                    const roadMeters = routeData.routes[0].distance;
                    const roadSeconds = routeData.routes[0].duration;
                    
                    const distKm = (roadMeters / 1000).toFixed(1);
                    const estMin = Math.max(5, Math.round(roadSeconds / 60));

                    document.getElementById('inputJarakKm').value = distKm + ' km';
                    document.getElementById('inputEstimasiMenit').value = estMin;
                    if (statusText) {
                        statusText.innerHTML = `<i class="fas fa-route" style="color:#059669"></i> Rute Jalan Presisi (Lat ${pLat.toFixed(4)}, Lng ${pLng.toFixed(4)}) &middot; Jarak Tempuh Rute Darat: <strong>${distKm} km (&plusmn; ${estMin} Menit)</strong>`;
                    }
                    return;
                }
                fallbackHaversine(pLat, pLng, rsLat, rsLng, statusText);
            })
            .catch(() => {
                fallbackHaversine(pLat, pLng, rsLat, rsLng, statusText);
            });
    }, function(err) {
        if (statusText) {
            statusText.innerHTML = '<i class="fas fa-info-circle"></i> Estimasi Jarak Tempuh ke RS: <strong>3.5 km (&plusmn; 12 Menit)</strong> (Izin lokasi belum diberikan).';
        }
    }, geoOptions);
}

function fallbackHaversine(pLat, pLng, rsLat, rsLng, statusText) {
    const R = 6371; // km
    const dLat = (rsLat - pLat) * Math.PI / 180;
    const dLng = (rsLng - pLng) * Math.PI / 180;
    const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
              Math.cos(pLat * Math.PI / 180) * Math.cos(rsLat * Math.PI / 180) *
              Math.sin(dLng/2) * Math.sin(dLng/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    const airKm = R * c;
    // Road travel distance factor for Indonesian roads (~1.25x air distance)
    const distKm = (airKm * 1.25).toFixed(1);
    const estMin = Math.max(5, Math.round(distKm * 2.5));

    document.getElementById('inputJarakKm').value = distKm + ' km';
    document.getElementById('inputEstimasiMenit').value = estMin;
    if (statusText) {
        statusText.innerHTML = `<i class="fas fa-check-circle" style="color:#059669"></i> Akses Lokasi Disetujui (Lat ${pLat.toFixed(4)}, Lng ${pLng.toFixed(4)}) &middot; Jarak Tempuh Rute Darat: <strong>${distKm} km (&plusmn; ${estMin} Menit)</strong>`;
    }
}

function pilihPoli(id, nama) {
    document.querySelectorAll('.poli-card').forEach(c => c.classList.remove('selected'));
    document.querySelector(`.poli-card[data-id="${id}"]`).classList.add('selected');
    document.getElementById('poliId').value = id;
    selectedPoli = { id, nama };
    document.getElementById('poliError').style.display = 'none';
    
    document.getElementById('cardDokter').style.display = 'block';
    document.getElementById('poliSelected').textContent = '📍 Poli Dipilih: ' + nama;
    loadDokter(id);
}

function pilihDokter(id, nama, spesialisasi) {
    document.querySelectorAll('.dokter-card').forEach(c => c.classList.remove('selected'));
    const docEl = document.getElementById('doc-' + id);
    if (docEl) docEl.classList.add('selected');
    document.getElementById('dokterId').value = id;
    selectedDokter = { id, nama, spesialisasi };
    document.getElementById('dokterError').style.display = 'none';

    document.getElementById('konfDokterNama').textContent = nama;
    loadJadwal();
}

function loadDokter(poliId) {
    const list = document.getElementById('dokterList');
    list.innerHTML = '<div style="text-align:center;padding:30px;color:#94a3b8"><i class="fas fa-spinner fa-spin" style="font-size:24px"></i><div>Memuat dokter bertugas...</div></div>';

    const tanggal = document.getElementById('tanggalInput')?.value || '';
    let url = `{{ route('pasien.api.dokter') }}?poli_id=${poliId}`;
    if (tanggal) {
        url += `&tanggal=${tanggal}`;
    }

    fetch(url)
        .then(r => r.json())
        .then(data => {
            if (!data || !data.length) {
                list.innerHTML = '<div style="text-align:center;padding:24px;color:#dc2626;background:#fef2f2;border:1px dashed #fecaca;border-radius:12px;font-weight:600"><i class="fas fa-calendar-times" style="font-size:20px;margin-bottom:6px;display:block"></i>Tidak ada dokter bertugas di poli ini pada tanggal yang dipilih (Dokter Libur). Silakan ganti tanggal kunjungan di Step 3.</div>';
                document.getElementById('dokterId').value = '';
                selectedDokter = { id: null, nama: '', spesialisasi: '' };
                return;
            }
            list.innerHTML = data.map(d => `
                <div class="dokter-card ${selectedDokter.id == d.id ? 'selected' : ''}" id="doc-${d.id}" onclick="pilihDokter(${d.id}, '${d.nama.replace(/'/g,"\\'")}', '${d.spesialisasi}')">
                    <div class="dokter-avatar">👨‍⚕️</div>
                    <div style="flex:1">
                        <div style="font-weight:700;font-size:14px;color:#0c4a6e">${d.nama}</div>
                        <div style="font-size:12px;color:#64748b;margin-top:2px">${d.spesialisasi}</div>
                        <div style="margin-top:6px;display:flex;gap:6px;align-items:center;flex-wrap:wrap">
                            <span class="jadwal-tag" style="background:#e0f2fe;color:#0369a1;font-weight:700">🕒 Jam Praktik: ${d.jam_praktik}</span>
                            ${d.jadwal ? Object.entries(d.jadwal).filter(([k,v])=>v.aktif).map(([k,v])=>`<span class="jadwal-tag">${k.charAt(0).toUpperCase()+k.slice(1)}</span>`).join('') : ''}
                        </div>
                    </div>
                    <div style="text-align:right">
                        <i class="fas fa-chevron-right" style="color:#94a3b8"></i>
                    </div>
                </div>
            `).join('');

            if (selectedDokter.id && !data.some(d => d.id == selectedDokter.id)) {
                selectedDokter = { id: null, nama: '', spesialisasi: '' };
                document.getElementById('dokterId').value = '';
            }
        })
        .catch(() => {
            list.innerHTML = '<div style="text-align:center;padding:30px;color:#dc2626">Gagal memuat data dokter</div>';
        });
}

function onTanggalChange() {
    if (selectedPoli.id) {
        loadDokter(selectedPoli.id);
    }
    loadJadwal();
}

function loadJadwal() {
    const tanggal = document.getElementById('tanggalInput').value;
    const dokterId = document.getElementById('dokterId').value;
    if (!tanggal || !dokterId) return;

    document.getElementById('jamGrid').innerHTML = '<div style="grid-column:1/-1;color:#94a3b8;font-size:13px"><i class="fas fa-spinner fa-spin"></i> Memuat jadwal...</div>';

    fetch(`{{ route('pasien.api.jadwal') }}?dokter_id=${dokterId}&tanggal=${tanggal}`)
        .then(r => r.json())
        .then(data => {
            if (data.is_libur) {
                document.getElementById('jamGrid').innerHTML = `<div style="grid-column:1/-1;color:#dc2626;font-weight:700;padding:12px;background:#fef2f2;border:1px solid #fecaca;border-radius:10px">⚠️ Dokter ini libur pada hari yang dipilih (${data.hari}). Silakan ganti tanggal atau pilih dokter lain.</div>`;
                document.getElementById('jamInput').value = '';
                const info = document.getElementById('antrianInfo');
                info.style.display = 'none';
                return;
            }

            const slots = [];
            const startH = parseInt(data.jam_mulai.split(':')[0]) || 8;
            const endH = parseInt(data.jam_selesai.split(':')[0]) || 12;

            for (let h = startH; h <= endH; h++) {
                slots.push(`${String(h).padStart(2,'0')}:00`);
                if (h < endH) slots.push(`${String(h).padStart(2,'0')}:30`);
            }

            document.getElementById('jamGrid').innerHTML = slots.map(jam => `
                <div class="jam-btn ${selectedJam === jam ? 'selected' : ''}" onclick="pilihJam('${jam}', this)">${jam}</div>
            `).join('');

            const info = document.getElementById('antrianInfo');
            info.style.display = 'block';
            info.innerHTML = `📊 Antrian hari ini (${data.hari}): <strong>${data.antrian_hari_ini}</strong> pasien &nbsp;|&nbsp; Sisa kuota: <strong>${data.sisa_kuota}</strong> dari ${data.kuota}`;

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

// Precise Select Value Matching with Two-Pass Exact Matching
function setSelectValue(selectId, val) {
    if (!val) return;
    const sel = document.getElementById(selectId);
    if (!sel) return;

    let rawStr = val.toString().trim();
    let target = rawStr.toLowerCase();
    let cleanVal = rawStr.replace(/^(kabupaten|kab\.|kota|kecamatan|kec\.|desa|kelurahan|kel\.|provinsi|prov\.)\s+/i, '').trim().toLowerCase();
    let foundIndex = -1;

    // Pass 1: Exact matches
    for (let i = 0; i < sel.options.length; i++) {
        let optVal = sel.options[i].value.toString().trim().toLowerCase();
        let optText = sel.options[i].text.toString().trim().toLowerCase();
        if (!optVal) continue;

        if (optVal === target || optText === target || optVal === cleanVal || optText === cleanVal) {
            foundIndex = i;
            break;
        }
    }

    // Pass 2: Substring matches for longer region/place names (ONLY if optVal and cleanVal are at least 4 characters long)
    if (foundIndex === -1 && cleanVal.length >= 4) {
        for (let i = 0; i < sel.options.length; i++) {
            let optVal = sel.options[i].value.toString().trim().toLowerCase();
            let optText = sel.options[i].text.toString().trim().toLowerCase();
            if (!optVal || optVal.length < 4) continue; // Skip short code options like A, B, O, L, P, WNI

            if (optVal.includes(cleanVal) || cleanVal.includes(optVal) || optText.includes(cleanVal) || cleanVal.includes(optText)) {
                foundIndex = i;
                break;
            }
        }
    }

    if (foundIndex !== -1) {
        sel.selectedIndex = foundIndex;
    } else {
        const opt = document.createElement('option');
        opt.value = rawStr;
        opt.textContent = rawStr;
        opt.selected = true;
        sel.appendChild(opt);
    }
}

function formatAndValidatePhone(el) {
    let val = el.value.replace(/\D/g, ''); // Disallow any letters / non-digits
    if (val.startsWith('628')) {
        val = '08' + val.slice(3);
    } else if (val.startsWith('8')) {
        val = '08' + val.slice(1);
    }
    if (val.length > 13) {
        val = val.slice(0, 13);
    }
    el.value = val;

    let errEl = document.getElementById(el.id + 'Error');
    if (!errEl) {
        errEl = document.createElement('div');
        errEl.id = el.id + 'Error';
        errEl.style.color = '#dc2626';
        errEl.style.fontSize = '12px';
        errEl.style.marginTop = '4px';
        el.parentNode.appendChild(errEl);
    }

    if (val.length > 0) {
        if (!val.startsWith('08')) {
            errEl.textContent = '⚠️ Nomor HP wajib diawali dengan 08';
            errEl.style.display = 'block';
        } else if (val.length < 10) {
            errEl.textContent = '⚠️ Nomor HP minimal 10 digit (08xxxxxxxx)';
            errEl.style.display = 'block';
        } else {
            errEl.style.display = 'none';
        }
    } else {
        errEl.style.display = 'none';
    }
}

function nextStep(from) {
    if (from === 1) {
        const requiredFields = [
            { id: 'fieldNik', label: 'No. KTP / NIK' },
            { id: 'fieldNama', label: 'Nama Lengkap Pasien' },
            { id: 'fieldTempatLahir', label: 'Tempat Lahir' },
            { id: 'fieldTanggalLahir', label: 'Tanggal Lahir' },
            { id: 'fieldJenisKelamin', label: 'Jenis Kelamin' },
            { id: 'fieldPekerjaan', label: 'Pekerjaan Pasien' },
            { id: 'fieldAgama', label: 'Agama' },
            { id: 'fieldPendidikan', label: 'Pendidikan Terakhir' },
            { id: 'fieldStatusPernikahan', label: 'Status Perkawinan' },
            { id: 'fieldWargaNegara', label: 'Warga Negara' },
            { id: 'fieldGolonganDarah', label: 'Golongan Darah' },
            { id: 'fieldAlamat', label: 'Alamat Lengkap' },
            { id: 'fieldProvinsi', label: 'Provinsi' },
            { id: 'fieldKabupaten', label: 'Kabupaten' },
            { id: 'fieldKecamatan', label: 'Kecamatan' },
            { id: 'fieldKelurahan', label: 'Desa / Kelurahan' },
            { id: 'fieldNoHp', label: 'No. Telepon / WhatsApp Pasien' }
        ];

        let missing = [];
        requiredFields.forEach(f => {
            const el = document.getElementById(f.id);
            if (!el || !el.value || !el.value.trim()) {
                missing.push(f.label);
            }
        });

        if (missing.length > 0) {
            Swal.fire({
                title: 'Formulir Belum Lengkap',
                html: `<div style="text-align:left;font-size:13px;padding:10px;background:#fef2f2;border:1px solid #fca5a5;border-radius:10px;color:#991b1b">
                        <strong>Harap lengkapi seluruh kolom Formulir Pasien di bawah ini terlebih dahulu:</strong>
                        <ul style="margin-top:6px;margin-left:18px">
                            ${missing.map(m => `<li>${m}</li>`).join('')}
                        </ul>
                       </div>`,
                icon: 'warning',
                confirmButtonColor: '#0284c7'
            });
            return;
        }

        const phoneVal = document.getElementById('fieldNoHp').value.trim();
        if (!phoneVal.startsWith('08') || phoneVal.length < 10 || phoneVal.length > 13 || /\D/.test(phoneVal)) {
            Swal.fire({
                title: 'Nomor HP Tidak Valid',
                text: 'Nomor HP Pasien wajib diawali dengan 08, hanya berupa angka, dan berjumlah 10 hingga 13 digit.',
                icon: 'warning',
                confirmButtonColor: '#0284c7'
            });
            document.getElementById('fieldNoHp').focus();
            return;
        }

        const phonePjEl = document.getElementById('fieldNoHpPj');
        if (phonePjEl && phonePjEl.value.trim().length > 0) {
            const phonePjVal = phonePjEl.value.trim();
            if (!phonePjVal.startsWith('08') || phonePjVal.length < 10 || phonePjVal.length > 13 || /\D/.test(phonePjVal)) {
                Swal.fire({
                    title: 'Nomor HP Penanggung Jawab Tidak Valid',
                    text: 'Nomor HP Penanggung Jawab wajib diawali dengan 08, hanya berupa angka, dan berjumlah 10 hingga 13 digit.',
                    icon: 'warning',
                    confirmButtonColor: '#0284c7'
                });
                phonePjEl.focus();
                return;
            }
        }

        document.getElementById('konfNama').textContent = document.getElementById('fieldNama').value;
        document.getElementById('konfNik').textContent = document.getElementById('fieldNik').value;
    }
    if (from === 2) {
        if (!selectedPoli.id) { document.getElementById('poliError').style.display = 'block'; return; }
        if (!selectedDokter.id) { document.getElementById('dokterError').style.display = 'block'; return; }
        document.getElementById('konfPoliNama').textContent = selectedPoli.nama;
    }
    if (from === 3) {
        if (!document.getElementById('tanggalInput').value) { alert('Pilih tanggal kunjungan'); return; }
        if (!selectedJam) { document.getElementById('jamError').style.display = 'block'; return; }
        if (!document.getElementById('keluhan').value.trim()) { alert('Keluhan wajib diisi'); return; }
        document.getElementById('konfJarak').textContent = document.getElementById('inputJarakKm').value + ' (± ' + document.getElementById('inputEstimasiMenit').value + ' Menit)';
    }

    document.getElementById(`step${from}`).style.display = 'none';
    document.getElementById(`step${from+1}`).style.display = 'block';
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

function confirmSubmitBooking() {
    const form = document.getElementById('formDaftar');
    Swal.fire({
        title: 'Konfirmasi Pendaftaran',
        text: 'Apakah data Formulir Identitas Pasien yang Anda isi sudah benar?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#059669',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: '<i class="fas fa-check-circle"></i> Ya, Daftar Sekarang',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            const btn = document.getElementById('btnSubmit');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses Pendaftaran...';
            Swal.fire({
                title: 'Memproses Pendaftaran...',
                text: 'Menerbitkan kode booking pendaftaran Anda',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });
            form.submit();
        }
    });
}

// Fast Canvas Compression before uploading to Gemini OCR
function compressImage(file, maxDimension, quality, callback) {
    const reader = new FileReader();
    reader.onload = function(e) {
        const img = new Image();
        img.onload = function() {
            let width = img.width;
            let height = img.height;
            if (width > maxDimension || height > maxDimension) {
                if (width > height) {
                    height = Math.round((height * maxDimension) / width);
                    width = maxDimension;
                } else {
                    width = Math.round((width * maxDimension) / height);
                    height = maxDimension;
                }
            }
            const canvas = document.createElement('canvas');
            canvas.width = width;
            canvas.height = height;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0, width, height);
            canvas.toBlob(function(blob) {
                callback(blob || file);
            }, 'image/jpeg', quality);
        };
        img.src = e.target.result;
    };
    reader.readAsDataURL(file);
}

function uploadOcrKtp(input) {
    if (!input.files || !input.files[0]) return;
    const file = input.files[0];

    Swal.fire({
        title: 'Membaca Foto KTP...',
        text: 'Memverifikasi keaslian KTP & mengunduh data NIK, Nama, Tanggal Lahir, Alamat, dll...',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });

    compressImage(file, 640, 0.65, function(compressedBlob) {
        const formData = new FormData();
        formData.append('ktp_image', compressedBlob, 'ktp.jpg');
        formData.append('_token', '{{ csrf_token() }}');

        fetch('{{ route("pasien.ocr-ktp") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(r => r.json())
        .then(res => {
            if (res.success && res.data) {
                const d = res.data;
                if (d.nik) document.getElementById('fieldNik').value = d.nik;
                if (d.nama_lengkap) document.getElementById('fieldNama').value = d.nama_lengkap;
                if (d.tempat_lahir) document.getElementById('fieldTempatLahir').value = d.tempat_lahir;
                if (d.tanggal_lahir) document.getElementById('fieldTanggalLahir').value = d.tanggal_lahir;
                if (d.jenis_kelamin) setSelectValue('fieldJenisKelamin', d.jenis_kelamin);
                if (d.golongan_darah) setSelectValue('fieldGolonganDarah', d.golongan_darah);
                if (d.pekerjaan) setSelectValue('fieldPekerjaan', d.pekerjaan);
                if (d.agama) setSelectValue('fieldAgama', d.agama);
                if (d.status_perkawinan) setSelectValue('fieldStatusPernikahan', d.status_perkawinan);
                if (d.warga_negara) setSelectValue('fieldWargaNegara', d.warga_negara);
                if (d.alamat) document.getElementById('fieldAlamat').value = d.alamat;
                if (d.kelurahan) setSelectValue('fieldKelurahan', d.kelurahan);
                if (d.kecamatan) setSelectValue('fieldKecamatan', d.kecamatan);
                if (d.kabupaten) setSelectValue('fieldKabupaten', d.kabupaten);
                if (d.provinsi) setSelectValue('fieldProvinsi', d.provinsi);

                let infoText = `<strong>NIK:</strong> ${d.nik || '-'}<br><strong>Nama:</strong> ${d.nama_lengkap || '-'}<br><strong>Tgl Lahir:</strong> ${d.tanggal_lahir || '-'}<br><strong>Gol. Darah:</strong> ${d.golongan_darah || '-'}<br><strong>Warga Negara:</strong> ${d.warga_negara || '-'}<br><strong>Alamat:</strong> ${d.alamat || '-'}`;
                Swal.fire({
                    title: 'Ekstraksi Foto KTP Berhasil!',
                    html: `<div style="text-align:left;font-size:13px;padding:10px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;color:#166534">${infoText}</div><div style="font-size:12px;color:#64748b;margin-top:10px">Semua kolom Formulir Identitas Pasien telah otomatis terisi! Silakan periksa kembali dan lanjut.</div>`,
                    icon: 'success',
                    confirmButtonColor: '#059669'
                });
            } else {
                Swal.fire('Gambar Bukan KTP Valid', res.message || 'Harap unggah foto KTP Indonesia yang jelas.', 'error');
            }
        })
        .catch(e => {
            Swal.fire('Error', 'Gagal memproses KTP: ' + e.message, 'error');
        });
    });
}
</script>
@endpush
