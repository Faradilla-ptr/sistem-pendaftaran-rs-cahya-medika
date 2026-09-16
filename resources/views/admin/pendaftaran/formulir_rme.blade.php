@extends('layouts.app')

@php
    $userRole = 'pasien';
    if (request()->is('superadmin*') || request()->is('pendaftaran-admin*') || request()->is('admin*')) {
        $userRole = 'superadmin';
    } elseif (request()->is('rekam-medis*')) {
        $userRole = 'rekam_medis';
    }

    $pasien = $pendaftaran->pasien;
    $rme = $pendaftaran->rme_data ?? [];
    $admisi = $rme['admisi'] ?? [];
    $medis  = $rme['medis'] ?? [];
@endphp

@section('page-title', 'Formulir Rekam Medis Umum (Lembar Masuk & Keluar)')

@section('content')
<style>
    .rme-grid-2 {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px 24px;
    }
    @media (max-width: 768px) {
        .rme-grid-2 {
            grid-template-columns: 1fr;
        }
    }
    .rme-grid-full {
        grid-column: 1 / -1;
    }
    .rme-card {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.04);
        margin-bottom: 24px;
        padding: 24px;
    }
    .rme-section-header {
        font-size: 14px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding-bottom: 10px;
        margin-bottom: 20px;
        border-bottom: 2px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .rme-label {
        font-size: 11px;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        margin-bottom: 5px;
        display: block;
    }
    .rme-input {
        width: 100%;
        padding: 9px 12px;
        font-size: 13px;
        color: #0f172a;
        background-color: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        transition: all 0.2s ease;
    }
    .rme-input:focus {
        outline: none;
        border-color: #0284c7;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
    }
    .rme-input-readonly {
        background-color: #f1f5f9 !important;
        color: #334155 !important;
        font-weight: 600 !important;
    }
    .icd-engine-box {
        background: #f0f9ff;
        border: 2px dashed #0284c7;
        border-radius: 12px;
        padding: 18px;
        margin-bottom: 16px;
    }
</style>

<div class="container-fluid py-3">
    <!-- BREADCRUMB & HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <span class="badge bg-primary px-3 py-2 rounded-pill text-uppercase font-weight-bold" style="letter-spacing:1px;">
                <i class="fas fa-file-medical me-1"></i> RME Lembar Masuk & Keluar
            </span>
            <h2 class="h3 font-weight-bold mt-2 text-dark">FORMULIR REKAM MEDIS UMUM</h2>
            <p class="text-muted small mb-0">Kode Booking: <strong>{{ $pendaftaran->kode_booking }}</strong> | Status: <span class="badge bg-info text-white">{{ strtoupper($pendaftaran->status) }}</span></p>
        </div>
        <div class="d-flex gap-2">
            @if($userRole === 'rekam_medis')
                <a href="{{ route('rekam-medis.pendaftaran.formulir-rme.pdf', $pendaftaran->id) }}" target="_blank" class="btn btn-danger btn-sm rounded-pill px-3 shadow-sm">
                    <i class="fas fa-file-pdf me-1"></i> Cetak Hardfile PDF (F4)
                </a>
                <a href="{{ route('rekam-medis.pendaftaran.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            @else
                <a href="{{ route('superadmin.pendaftaran.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            @endif
        </div>
    </div>

    <!-- WORKFLOW STATUS BANNER -->
    <div class="card border-0 shadow-sm rounded-4 mb-4" style="background: linear-gradient(135deg, #1e293b, #0f172a); color: #fff;">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-primary bg-opacity-25 p-3 text-primary d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                            <i class="fas fa-sync-alt fa-lg text-info"></i>
                        </div>
                        <div>
                            <h5 class="mb-1 text-white font-weight-bold">Status Alur RME: 
                                @if($pendaftaran->rme_status === 'draft_pendaftaran')
                                    <span class="badge bg-warning text-dark"><i class="fas fa-edit"></i> 1. Pengisian Admisi (Pendaftaran)</span>
                                @elseif($pendaftaran->rme_status === 'dikirim_ke_rm')
                                    <span class="badge bg-info text-white"><i class="fas fa-paper-plane"></i> 2. Dikirim ke Rekam Medis</span>
                                @elseif($pendaftaran->rme_status === 'konfirmasi_pendaftaran')
                                    <span class="badge bg-primary text-white"><i class="fas fa-clock"></i> 3. Menunggu Konfirmasi Selesai (Pendaftaran)</span>
                                @elseif($pendaftaran->rme_status === 'selesai_pendaftaran')
                                    <span class="badge bg-success text-white"><i class="fas fa-check-circle"></i> 4. Konfirmasi Selesai (Siap Verifikasi RM)</span>
                                @elseif($pendaftaran->rme_status === 'verified_rm' || $pendaftaran->satusehat_status === 'success')
                                    <span class="badge bg-success text-white"><i class="fas fa-cloud-upload-alt"></i> 5. Terverifikasi & Terintegrasi SATUSEHAT</span>
                                @else
                                    <span class="badge bg-secondary">{{ $pendaftaran->rme_status ?? 'Draft' }}</span>
                                @endif
                            </h5>
                            <p class="text-light small mb-0">Pasien: <strong>{{ $pasien->nama_lengkap ?? '-' }}</strong> (No. RM: {{ $pasien->no_rm ?? '-' }}) | NIK: {{ $pasien->nik ?? '-' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0 d-flex gap-2 justify-md-content-end">
                    @if($userRole === 'superadmin' && $pendaftaran->rme_status === 'konfirmasi_pendaftaran')
                        <form action="{{ route('superadmin.pendaftaran.formulir-rme.konfirmasi', $pendaftaran->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success btn-md rounded-pill px-4 font-weight-bold shadow-sm">
                                <i class="fas fa-check-double me-1"></i> Confirm (Pasien Selesai)
                            </button>
                        </form>
                    @elseif($userRole === 'rekam_medis' && ($pendaftaran->rme_status === 'selesai_pendaftaran' || $pendaftaran->status === 'selesai'))
                        <form action="{{ route('rekam-medis.pendaftaran.formulir-rme.verify-sync', $pendaftaran->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-warning text-dark btn-md rounded-pill px-4 font-weight-bold shadow-sm">
                                <i class="fas fa-cloud-upload-alt me-1"></i> Verifikasi & Sync Ke SATUSEHAT
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN FORM CONTAINER -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
        <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-0 font-weight-bold text-primary"><i class="fas fa-clipboard-list me-2"></i>FORMULIR REKAM MEDIS UMUM</h4>
                <small class="text-muted">(LEMBAR MASUK & KELUAR) - PERATURAN KEMENKES RI</small>
            </div>
            <div class="d-flex align-items-center gap-2">
                @if($userRole === 'rekam_medis')
                    <a href="{{ route('rekam-medis.pendaftaran.formulir-rme.pdf', $pendaftaran->id) }}" target="_blank" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                        <i class="fas fa-print me-1"></i> Cetak Hardfile PDF (F4)
                    </a>
                @endif
                <span class="badge bg-danger px-3 py-2 text-uppercase">RAHASIA</span>
            </div>
        </div>

        <div class="card-body p-4">

            <!-- ========================================================= -->
            <!-- BAGIAN 1: DIISI OLEH PENDAFTARAN (ADMISI PASIEN) -->
            <!-- ========================================================= -->
            <form action="{{ route('superadmin.pendaftaran.formulir-rme.pendaftaran', $pendaftaran->id) }}" method="POST" id="formPendaftaran">
                @csrf
                <div class="rme-card border-start border-4 border-primary">
                    <div class="rme-section-header text-primary">
                        <span><i class="fas fa-id-card me-2"></i>BAGIAN 1: IDENTITAS & ADMISI PASIEN (PENDAFTARAN)</span>
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1">Hak Akses: Pendaftaran & SuperAdmin</span>
                    </div>
                    
                    <div class="rme-grid-2">
                        <!-- ROW 1 -->
                        <div>
                            <label class="rme-label">Kamar / Kelas / No. TT</label>
                            <input type="text" name="kamar_kelas_tt" class="rme-input" value="{{ $admisi['kamar_kelas_tt'] ?? '' }}" placeholder="Contoh: Poliklinik Rawat Jalan">
                        </div>
                        <div>
                            <label class="rme-label">Dirawat Ke (Kunjungan Ke-)</label>
                            <input type="text" name="dirawat_ke" class="rme-input" value="{{ $admisi['dirawat_ke'] ?? '1' }}" placeholder="Dirawat Ke-">
                        </div>

                        <!-- ROW 2 -->
                        <div>
                            <label class="rme-label">No. Rekam Medik (RM)</label>
                            <input type="text" class="rme-input rme-input-readonly text-primary" value="{{ $pasien->no_rm ?? '-' }}" readonly>
                        </div>
                        <div>
                            <label class="rme-label">Nama Lengkap Pasien</label>
                            <input type="text" class="rme-input rme-input-readonly" value="{{ $pasien->nama_lengkap ?? '-' }}" readonly>
                        </div>

                        <!-- ROW 3 -->
                        <div>
                            <label class="rme-label">Jenis Kelamin (Sex)</label>
                            <input type="text" class="rme-input rme-input-readonly" value="{{ ($pasien->jenis_kelamin ?? 'L') == 'L' ? 'Laki-Laki' : 'Perempuan' }}" readonly>
                        </div>
                        <div>
                            <label class="rme-label">Tanggal Lahir / Umur</label>
                            <input type="text" class="rme-input rme-input-readonly" value="{{ optional($pasien->tanggal_lahir)->format('d M Y') }} ({{ $pasien->umur ?? '-' }} th)" readonly>
                        </div>

                        <!-- ROW 4 -->
                        <div>
                            <label class="rme-label">Agama</label>
                            <input type="text" name="agama" class="rme-input" value="{{ $pasien->agama ?? '' }}" placeholder="Agama Pasien">
                        </div>
                        <div>
                            <label class="rme-label">Status Perkawinan</label>
                            <select name="status_perkawinan" class="rme-input">
                                <option value="">-- Pilih Status Perkawinan --</option>
                                <option value="Kawin" {{ ($pasien->status_pernikahan ?? '') == 'Kawin' ? 'selected' : '' }}>Kawin</option>
                                <option value="Belum kawin" {{ ($pasien->status_pernikahan ?? '') == 'Belum kawin' ? 'selected' : '' }}>Belum kawin</option>
                                <option value="Janda" {{ ($pasien->status_pernikahan ?? '') == 'Janda' ? 'selected' : '' }}>Janda</option>
                                <option value="Duda" {{ ($pasien->status_pernikahan ?? '') == 'Duda' ? 'selected' : '' }}>Duda</option>
                                <option value="Dibawah umur" {{ ($pasien->status_pernikahan ?? '') == 'Dibawah umur' ? 'selected' : '' }}>Dibawah umur</option>
                            </select>
                        </div>

                        <!-- ROW 5 -->
                        <div>
                            <label class="rme-label">Pekerjaan</label>
                            <input type="text" name="pekerjaan" class="rme-input" value="{{ $pasien->pekerjaan ?? '' }}" placeholder="Pekerjaan Pasien">
                        </div>
                        <div>
                            <label class="rme-label">Pendidikan Terakhir</label>
                            <input type="text" name="pendidikan_terakhir" class="rme-input" value="{{ $pasien->pendidikan ?? '' }}" placeholder="Pendidikan Terakhir Pasien">
                        </div>

                        <!-- ROW 6 -->
                        <div>
                            <label class="rme-label">Cara KB</label>
                            <select name="cara_kb" class="rme-input">
                                <option value="Tidak" {{ ($admisi['cara_kb'] ?? '') == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                                <option value="IUD" {{ ($admisi['cara_kb'] ?? '') == 'IUD' ? 'selected' : '' }}>IUD</option>
                                <option value="Pil" {{ ($admisi['cara_kb'] ?? '') == 'Pil' ? 'selected' : '' }}>Pil</option>
                                <option value="Kondom" {{ ($admisi['cara_kb'] ?? '') == 'Kondom' ? 'selected' : '' }}>Kondom</option>
                                <option value="MOW" {{ ($admisi['cara_kb'] ?? '') == 'MOW' ? 'selected' : '' }}>MOW</option>
                                <option value="MOP" {{ ($admisi['cara_kb'] ?? '') == 'MOP' ? 'selected' : '' }}>MOP</option>
                                <option value="Lain" {{ ($admisi['cara_kb'] ?? '') == 'Lain' ? 'selected' : '' }}>Lain</option>
                            </select>
                        </div>
                        <div>
                            <label class="rme-label">Alamat Lengkap Pasien (Jalan/RT/RW)</label>
                            <input type="text" name="alamat" class="rme-input" value="{{ $pasien->alamat ?? '' }}" placeholder="Alamat Jalan, RT/RW">
                        </div>

                        <!-- ROW 7 -->
                        <div>
                            <label class="rme-label">Provinsi & Kabupaten</label>
                            <div style="display: flex; gap: 8px;">
                                <input type="text" name="provinsi" class="rme-input" value="{{ $pasien->provinsi ?? '' }}" placeholder="Provinsi">
                                <input type="text" name="kabupaten" class="rme-input" value="{{ $pasien->kabupaten ?? '' }}" placeholder="Kabupaten">
                            </div>
                        </div>
                        <div>
                            <label class="rme-label">Kecamatan & Kelurahan</label>
                            <div style="display: flex; gap: 8px;">
                                <input type="text" name="kecamatan" class="rme-input" value="{{ $pasien->kecamatan ?? '' }}" placeholder="Kecamatan">
                                <input type="text" name="kelurahan" class="rme-input" value="{{ $pasien->kelurahan ?? '' }}" placeholder="Kelurahan">
                            </div>
                        </div>

                        <!-- ROW 8 -->
                        <div>
                            <label class="rme-label">No. Telepon / HP Pasien</label>
                            <input type="text" class="rme-input rme-input-readonly" value="{{ $pasien->no_hp ?? '-' }}" readonly>
                        </div>
                        <div>
                            <label class="rme-label">Nama Ayah / Ibu / Suami / Istri</label>
                            <input type="text" name="nama_keluarga_orangtua" class="rme-input" value="{{ $admisi['nama_keluarga_orangtua'] ?? ($pasien->nama_pj ?? '') }}" placeholder="Nama Suami/Istri/Orang Tua">
                        </div>

                        <!-- ROW 9 -->
                        <div>
                            <label class="rme-label">Penanggung Biaya & No. HP</label>
                            <input type="text" name="pj_nama_hp" class="rme-input" value="{{ $admisi['pj_nama_hp'] ?? ($pasien->nama_pj ? ($pasien->nama_pj . ' - ' . ($pasien->no_hp_pj ?? '-')) : '') }}" placeholder="Penanggung Biaya & Kontak">
                        </div>
                        <div>
                            <label class="rme-label">Nama Keluarga Terdekat & Alamat</label>
                            <input type="text" name="keluarga_terdekat_detail" class="rme-input" value="{{ $admisi['keluarga_terdekat_detail'] ?? ($pasien->nama_pj ?? '') }}" placeholder="Keluarga Terdekat & Alamat">
                        </div>

                        <!-- ROW 10 -->
                        <div>
                            <label class="rme-label">Tanggal & Jam MRS (Masuk)</label>
                            <input type="text" name="tanggal_jam_mrs" class="rme-input" value="{{ $admisi['tanggal_jam_mrs'] ?? now()->format('Y-m-d H:i') }}">
                        </div>
                        <div>
                            <label class="rme-label">Cara Masuk (MRS)</label>
                            <select name="cara_mrs" class="rme-input">
                                <option value="Admission" {{ ($admisi['cara_mrs'] ?? '') == 'Admission' ? 'selected' : '' }}>Admission</option>
                                <option value="UGD" {{ ($admisi['cara_mrs'] ?? '') == 'UGD' ? 'selected' : '' }}>UGD</option>
                                <option value="Klinik Spesialis" {{ ($admisi['cara_mrs'] ?? '') == 'Klinik Spesialis' ? 'selected' : '' }}>Klinik Spesialis</option>
                                <option value="RS Lain" {{ ($admisi['cara_mrs'] ?? '') == 'RS Lain' ? 'selected' : '' }}>RS Lain</option>
                                <option value="Lain-lain" {{ ($admisi['cara_mrs'] ?? '') == 'Lain-lain' ? 'selected' : '' }}>Lain-lain</option>
                            </select>
                        </div>
                    </div>

                    @if($userRole === 'superadmin' || $userRole === 'admin')
                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-primary rounded-pill px-4 font-weight-bold shadow-sm">
                                <i class="fas fa-paper-plane me-1"></i> Simpan Admisi & Kirim ke Rekam Medis
                            </button>
                        </div>
                    @endif
                </div>
            </form>

            <!-- ========================================================= -->
            <!-- BAGIAN 2: DATA MEDIS & KODE DIAGNOSA ICD-10 -->
            <!-- KHUSUS UNTUK ROLE REKAM MEDIS (PERSYARATAN PRIVASI PASIEN) -->
            <!-- ========================================================= -->
            @if($userRole === 'rekam_medis')
                <form action="{{ route('rekam-medis.pendaftaran.formulir-rme.rekam-medis', $pendaftaran->id) }}" method="POST" id="formRekamMedis">
                    @csrf
                    <div class="rme-card border-start border-4 border-success">
                        <div class="rme-section-header text-success">
                            <span><i class="fas fa-user-md me-2"></i>BAGIAN 2: DATA MEDIS & KODE DIAGNOSA ICD-10 (REKAM MEDIS)</span>
                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-1">Khusus Rekam Medis & Dokter</span>
                        </div>
                        
                        <div class="rme-grid-2">
                            <!-- ROW 1 -->
                            <div>
                                <label class="rme-label">Dokter yang Merawat</label>
                                <input type="text" name="dokter_yang_merawat" class="rme-input" value="{{ $medis['dokter_yang_merawat'] ?? ($pendaftaran->dokter->nama ?? '') }}" placeholder="Dokter Penanggung Jawab">
                            </div>
                            <div>
                                <label class="rme-label">Diagnosa Masuk / Keluhan Utama</label>
                                <input type="text" name="diagnosa_masuk" class="rme-input" value="{{ $medis['diagnosa_masuk'] ?? ($pendaftaran->keluhan ?? '') }}" placeholder="Diagnosa Awal / Keluhan Utama Pasien">
                            </div>

                            <!-- ROW 2 -->
                            <div>
                                <label class="rme-label text-danger">Riwayat Alergi Pasien</label>
                                <select name="riwayat_alergi" class="rme-input">
                                    <option value="Tidak" {{ ($medis['riwayat_alergi'] ?? '') == 'Tidak' ? 'selected' : '' }}>Tidak Ada Alergi</option>
                                    <option value="Ya: Obat" {{ ($medis['riwayat_alergi'] ?? '') == 'Ya: Obat' ? 'selected' : '' }}>Ya: Alergi Obat</option>
                                    <option value="Ya: Makanan" {{ ($medis['riwayat_alergi'] ?? '') == 'Ya: Makanan' ? 'selected' : '' }}>Ya: Alergi Makanan</option>
                                </select>
                            </div>
                            <div>
                                <label class="rme-label">Gejala & Ringkasan Hasil Pemeriksaan</label>
                                <input type="text" name="gejala_pemeriksaan" class="rme-input" value="{{ $medis['gejala_pemeriksaan'] ?? '' }}" placeholder="Deskripsi gejala & hasil fisik">
                            </div>

                            <!-- ADVANCED 22-CHAPTER ICD-10 SEARCH & FILTER ENGINE -->
                            <div class="rme-grid-full">
                                <div class="icd-engine-box">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <label class="rme-label text-primary mb-0" style="font-size: 13px;">
                                            <i class="fas fa-search-plus me-1"></i> MESIN PENCARIAN & FILTER KODE ICD-10 (22 BAB UTAMA KEMENKES RI)
                                        </label>
                                        <span class="badge bg-primary px-3 py-1">11.400+ Kode Diagnosis</span>
                                    </div>
                                    
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                                        <!-- STEP 1: FILTER BY BAB UTAMA ICD-10 -->
                                        <div>
                                            <label class="small text-muted font-weight-bold mb-1"><i class="fas fa-layer-group me-1"></i> Filter Bab Utama ICD-10 (22 Bab):</label>
                                            <select id="icd_chapter_select" class="rme-input border-primary" onchange="searchIcd10()">
                                                <option value="">-- Semua 22 Bab Utama ICD-10 --</option>
                                                <option value="Bab I">Bab I: Penyakit Infeksi dan Parasit (A00–B99)</option>
                                                <option value="Bab II">Bab II: Neoplasma / Tumor & Kanker (C00–D48)</option>
                                                <option value="Bab III">Bab III: Penyakit Darah & Kekebalan (D50–D89)</option>
                                                <option value="Bab IV">Bab IV: Penyakit Endokrin, Nutrisi & Metabolik (E00–E90)</option>
                                                <option value="Bab V">Bab V: Gangguan Mental dan Perilaku (F00–F99)</option>
                                                <option value="Bab VI">Bab VI: Penyakit Sistem Saraf (G00–G99)</option>
                                                <option value="Bab VII">Bab VII: Penyakit Mata dan Adnexa (H00–H59)</option>
                                                <option value="Bab VIII">Bab VIII: Penyakit Telinga dan Mastoid (H60–H95)</option>
                                                <option value="Bab IX">Bab IX: Penyakit Sistem Sirkulasi / Jantung (I00–I99)</option>
                                                <option value="Bab X">Bab X: Penyakit Sistem Pernapasan (J00–J99)</option>
                                                <option value="Bab XI">Bab XI: Penyakit Sistem Pencernaan (K00–K95)</option>
                                                <option value="Bab XII">Bab XII: Penyakit Kulit dan Subkutan (L00–L99)</option>
                                                <option value="Bab XIII">Bab XIII: Penyakit Muskuloskeletal & Otot (M00–M99)</option>
                                                <option value="Bab XIV">Bab XIV: Penyakit Sistem Kemih & Genital (N00–N99)</option>
                                                <option value="Bab XV">Bab XV: Kehamilan, Persalinan & Nifas (O00–O99)</option>
                                                <option value="Bab XVI">Bab XVI: Kondisi Perinatal / Bayi Baru Lahir (P00–P96)</option>
                                                <option value="Bab XVII">Bab XVII: Kelainan Kongenital & Kromosom (Q00–Q99)</option>
                                                <option value="Bab XVIII">Bab XVIII: Gejala, Tanda & Temuan Klinis (R00–R99)</option>
                                                <option value="Bab XIX">Bab XIX: Cedera, Keracunan & Akibat Luar (S00–T98)</option>
                                                <option value="Bab XX">Bab XX: Penyebab Luar Morbiditas (V01–Y98)</option>
                                                <option value="Bab XXI">Bab XXI: Faktor Mempengaruhi Status Kesehatan (Z00–Z99)</option>
                                                <option value="Bab XXII">Bab XXII: Kode Tujuan Khusus / COVID-19 (U00–U85)</option>
                                            </select>
                                        </div>

                                        <!-- STEP 2: SEARCH KEYWORD INPUT -->
                                        <div>
                                            <label class="small text-muted font-weight-bold mb-1"><i class="fas fa-search me-1"></i> Cari Nama Penyakit / Kode (Live Search):</label>
                                            <input type="text" id="icd_search_query" class="rme-input border-primary" placeholder="Ketik kata kunci (contoh: Gastritis, TBC, Demam, I10)..." onkeyup="searchIcd10()">
                                        </div>
                                    </div>

                                    <!-- DYNAMIC SELECT RESULT DROPDOWN -->
                                    <div class="mb-3">
                                        <label class="small text-muted font-weight-bold mb-1"><i class="fas fa-list-check me-1"></i> Pilih Kode ICD-10 Hasil Pencarian:</label>
                                        <select id="icd_result_select" class="rme-input font-weight-bold border-primary" onchange="selectIcd10Item(this)">
                                            <option value="">-- Pilih Kode & Nama Penyakit ICD-10 --</option>
                                        </select>
                                    </div>

                                    <!-- RESULT AUTOFILL INPUTS -->
                                    <div style="display: grid; grid-template-columns: 1fr 3fr; gap: 12px;">
                                        <div>
                                            <label class="small text-muted font-weight-bold mb-1">Kode ICD-10 Terpilih:</label>
                                            <input type="text" name="icd_code" id="icd_code_input" class="rme-input font-weight-bold text-primary border-primary" value="{{ $medis['icd_code'] ?? '' }}" placeholder="Kode ICD-10">
                                        </div>
                                        <div>
                                            <label class="small text-muted font-weight-bold mb-1">Deskripsi Diagnosa Utama (ICD-10):</label>
                                            <input type="text" name="diagnosa_utama" id="diagnosa_utama_input" class="rme-input font-weight-bold border-primary" value="{{ $medis['diagnosa_utama'] ?? '' }}" placeholder="Nama Penyakit Diagnosa Utama">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ROW 3 -->
                            <div>
                                <label class="rme-label">Komplikasi</label>
                                <input type="text" name="komplikasi" class="rme-input" value="{{ $medis['komplikasi'] ?? '' }}" placeholder="Komplikasi medis jika ada">
                            </div>
                            <div>
                                <label class="rme-label">Diagnosa Sekunder / Penyerta</label>
                                <input type="text" name="diagnosa_sekunder" class="rme-input" value="{{ $medis['diagnosa_sekunder'] ?? '' }}" placeholder="Diagnosa sekunder jika ada">
                            </div>

                            <!-- ROW 4 -->
                            <div>
                                <label class="rme-label">Penyebab Cedera / Morfologi Neoplasma</label>
                                <input type="text" name="penyebab_cedera" class="rme-input" value="{{ $medis['penyebab_cedera'] ?? '' }}" placeholder="Detail penyebab luar / cedera">
                            </div>
                            <div>
                                <label class="rme-label">Operasi & Tindakan Medis (Kode ICD-9-CM)</label>
                                <div style="display: flex; gap: 8px;">
                                    <input type="text" name="tindakan_nama" class="rme-input" value="{{ $medis['tindakan_nama'] ?? '' }}" placeholder="Nama Tindakan Medical">
                                    <input type="text" name="tindakan_kode" class="rme-input" style="max-width:110px;" value="{{ $medis['tindakan_kode'] ?? '' }}" placeholder="ICD-9-CM">
                                </div>
                            </div>

                            <!-- ROW 5: VITAL SIGNS -->
                            <div>
                                <label class="rme-label">Tanda-Tanda Vital (Suhu & Tekanan Darah)</label>
                                <div style="display: flex; gap: 8px; align-items: center;">
                                    <span class="small font-weight-bold">Suhu:</span>
                                    <input type="text" name="suhu" class="rme-input" value="{{ $pendaftaran->suhu ?? '' }}" placeholder="36.5">
                                    <span class="small">°C</span>
                                    <span class="small font-weight-bold ms-2">TD:</span>
                                    <input type="text" name="tekanan_darah" class="rme-input" value="{{ $pendaftaran->tekanan_darah ?? '' }}" placeholder="120/80">
                                    <span class="small">mmHg</span>
                                </div>
                            </div>
                            <div>
                                <label class="rme-label">Antropometri (Berat & Tinggi Badan)</label>
                                <div style="display: flex; gap: 8px; align-items: center;">
                                    <span class="small font-weight-bold">BB:</span>
                                    <input type="text" name="berat_badan" class="rme-input" value="{{ $pendaftaran->berat_badan ?? '' }}" placeholder="65">
                                    <span class="small">kg</span>
                                    <span class="small font-weight-bold ms-2">TB:</span>
                                    <input type="text" name="tinggi_badan" class="rme-input" value="{{ $pendaftaran->tinggi_badan ?? '' }}" placeholder="168">
                                    <span class="small">cm</span>
                                </div>
                            </div>

                            <!-- ROW 6 -->
                            <div>
                                <label class="rme-label">Tanggal & Jam Keluar (KRS)</label>
                                <input type="text" name="tanggal_jam_krs" class="rme-input" value="{{ $medis['tanggal_jam_krs'] ?? '' }}" placeholder="Tgl & Jam Keluar">
                            </div>
                            <div>
                                <label class="rme-label">Lama Dirawat (Hari)</label>
                                <input type="text" name="lama_dirawat" class="rme-input" value="{{ $medis['lama_dirawat'] ?? '' }}" placeholder="Lama Dirawat (Hari)">
                            </div>

                            <!-- ROW 7 -->
                            <div>
                                <label class="rme-label">Keadaan Keluar (KRS)</label>
                                <select name="keadaan_krs" class="rme-input">
                                    <option value="">-- Pilih Keadaan KRS --</option>
                                    <option value="Sembuh" {{ ($medis['keadaan_krs'] ?? '') == 'Sembuh' ? 'selected' : '' }}>Sembuh</option>
                                    <option value="Membaik" {{ ($medis['keadaan_krs'] ?? '') == 'Membaik' ? 'selected' : '' }}>Membaik</option>
                                    <option value="Belum sembuh" {{ ($medis['keadaan_krs'] ?? '') == 'Belum sembuh' ? 'selected' : '' }}>Belum sembuh</option>
                                    <option value="Meninggal < 48 jam" {{ ($medis['keadaan_krs'] ?? '') == 'Meninggal < 48 jam' ? 'selected' : '' }}>Meninggal < 48 jam</option>
                                    <option value="Meninggal > 48 jam" {{ ($medis['keadaan_krs'] ?? '') == 'Meninggal > 48 jam' ? 'selected' : '' }}>Meninggal > 48 jam</option>
                                </select>
                            </div>
                            <div>
                                <label class="rme-label">Cara Keluar (KRS)</label>
                                <select name="cara_krs" class="rme-input">
                                    <option value="">-- Pilih Cara KRS --</option>
                                    <option value="Dipulangkan" {{ ($medis['cara_krs'] ?? '') == 'Dipulangkan' ? 'selected' : '' }}>Dipulangkan</option>
                                    <option value="Pulang paksa" {{ ($medis['cara_krs'] ?? '') == 'Pulang paksa' ? 'selected' : '' }}>Pulang paksa</option>
                                    <option value="Pindah rumah sakit lain" {{ ($medis['cara_krs'] ?? '') == 'Pindah rumah sakit lain' ? 'selected' : '' }}>Pindah rumah sakit lain</option>
                                    <option value="Lari" {{ ($medis['cara_krs'] ?? '') == 'Lari' ? 'selected' : '' }}>Lari</option>
                                    <option value="Lain-lain" {{ ($medis['cara_krs'] ?? '') == 'Lain-lain' ? 'selected' : '' }}>Lain-lain</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-success rounded-pill px-4 font-weight-bold shadow-sm">
                                <i class="fas fa-save me-1"></i> Simpan Data Medis & Kirim Konfirmasi ke Pendaftaran
                            </button>
                        </div>
                    </div>
                </form>
            @else
                <!-- PESAN KERAHASIAAN UNTUK PENDAFTARAN / SUPERADMIN -->
                <div class="card border-danger border-opacity-25 bg-danger bg-opacity-10 p-4 text-center rounded-4 my-4">
                    <div class="text-danger mb-2">
                        <i class="fas fa-user-shield fa-3x"></i>
                    </div>
                    <h5 class="font-weight-bold text-danger">BAGIAN 2: REKAM MEDIS & KODE DIAGNOSA ICD-10 (RAHASIA)</h5>
                    <p class="text-muted small mb-0">
                        Sesuai Peraturan Kemenkes RI mengenai Kerahasiaan Rekam Medis Pasien, Bagian 2 (Hasil Pemeriksaan Medis, Diagnosa ICD-10, & Tindakan Dokter) <strong>hanya dapat diakses dan diisi oleh Petugas Rekam Medis & Dokter</strong>.
                    </p>
                </div>
            @endif

        </div>
    </div>
</div>

<script>
let searchTimeout = null;

function searchIcd10() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        const chapter = document.getElementById('icd_chapter_select').value;
        const q = document.getElementById('icd_search_query').value;
        const select = document.getElementById('icd_result_select');

        select.innerHTML = '<option value="">-- Memuat hasil pencarian... --</option>';

        fetch(`/api/icd10-search?chapter=${encodeURIComponent(chapter)}&q=${encodeURIComponent(q)}`)
            .then(res => res.json())
            .then(res => {
                select.innerHTML = '<option value="">-- Pilih Kode & Nama Penyakit ICD-10 --</option>';
                if (res.data && res.data.length > 0) {
                    res.data.forEach(item => {
                        const opt = document.createElement('option');
                        opt.value = `${item.code} - ${item.name_id}`;
                        opt.textContent = `[${item.code}] ${item.name_id} (${item.chapter_number})`;
                        select.appendChild(opt);
                    });
                } else {
                    const opt = document.createElement('option');
                    opt.value = "";
                    opt.textContent = "Tidak ditemukan kode ICD-10 yang sesuai.";
                    select.appendChild(opt);
                }
            })
            .catch(err => {
                select.innerHTML = '<option value="">-- Gagal memuat data ICD-10 --</option>';
            });
    }, 250);
}

function selectIcd10Item(selectEl) {
    const val = selectEl.value;
    if (!val) return;
    const parts = val.split(' - ');
    if (parts.length >= 2) {
        document.getElementById('icd_code_input').value = parts[0].trim();
        document.getElementById('diagnosa_utama_input').value = parts.slice(1).join(' - ').trim();
    }
}

// Initial load
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('icd_result_select')) {
        searchIcd10();
    }
});
</script>
@endsection
