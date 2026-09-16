@extends('layouts.app')
@section('title', 'Profil Saya - RS Cahya Medika')
@section('page-title', 'Profil & Pengaturan Akun')

@push('styles')
<style>
.profile-card {
    background: #ffffff;
    border-radius: 20px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.05);
    overflow: hidden;
    margin-bottom: 28px;
}

.profile-card-header {
    padding: 20px 24px;
    background: linear-gradient(to right, #f8fafc, #f1f5f9);
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.profile-card-title {
    font-size: 16px;
    font-weight: 800;
    color: #0c4a6e;
    display: flex;
    align-items: center;
    gap: 10px;
}

.profile-card-body {
    padding: 24px;
}

.section-divider {
    font-size: 13px;
    font-weight: 700;
    color: #0891b2;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin: 28px 0 16px 0;
    padding-bottom: 8px;
    border-bottom: 2px dashed #e2e8f0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.section-divider:first-of-type {
    margin-top: 0;
}

/* Elevated Input Styles */
.form-label {
    font-size: 12px;
    font-weight: 700;
    color: #334155;
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.form-control, .form-select {
    border: 1.5px solid #cbd5e1 !important;
    box-shadow: 0 2px 5px rgba(15, 23, 42, 0.04) !important;
    font-size: 13px !important;
    font-weight: 500 !important;
    border-radius: 10px !important;
    background-color: #ffffff !important;
    color: #0f172a !important;
    padding: 10px 14px !important;
    transition: all 0.2s ease !important;
}

.form-control:focus, .form-select:focus {
    border-color: #0891b2 !important;
    box-shadow: 0 0 0 3px rgba(8, 145, 178, 0.15), 0 2px 5px rgba(15, 23, 42, 0.05) !important;
    background-color: #ffffff !important;
}

.form-control[readonly], .form-control:disabled {
    background-color: #f1f5f9 !important;
    border-color: #e2e8f0 !important;
    color: #64748b !important;
}

.pwd-toggle {
    position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
    color: #94a3b8; cursor: pointer; font-size: 14px; padding: 4px;
    border: none; background: none; line-height: 1;
}
.pwd-toggle:hover { color: #0891b2; }
.input-pw { padding-right: 40px !important; }
</style>
@endpush

@section('content')
<div style="max-width:960px;margin:0 auto">

    <!-- Unified Profile Card -->
    <div class="profile-card">
        <div class="profile-card-header">
            <div class="profile-card-title">
                <div style="width:36px;height:36px;background:#e0f2fe;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#0891b2">
                    <i class="fas fa-user-gear"></i>
                </div>
                <div>
                    <div>Profil & Pengaturan Akun Pasien</div>
                    <div style="font-size:12px;font-weight:500;color:#64748b">Kelola data identitas, domisili, dan keamanan akun Anda</div>
                </div>
            </div>
            @if($pasien && $pasien->satusehat_id)
                <span class="badge badge-success"><i class="fas fa-check-circle"></i> Terhubung SatuSehat</span>
            @else
                <span class="badge badge-warning"><i class="fas fa-sync fa-spin" style="font-size:10px"></i> Sinkronisasi Otomatis</span>
            @endif
        </div>

        <div class="profile-card-body">
            
            <!-- FORM DATA DIRI PASIEN -->
            <form id="formUpdateProfil" action="{{ route('pasien.profil.update') }}" method="POST">
                @csrf @method('PUT')

                <!-- 1. DATA DIRI -->
                <div class="section-divider">
                    <i class="fas fa-address-card"></i> 1. Data Diri & Identitas
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px;margin-bottom:20px">
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap Pasien *</label>
                        <input type="text" name="nama_lengkap" class="form-control {{ $errors->has('nama_lengkap') ? 'is-invalid' : '' }}"
                            value="{{ old('nama_lengkap', $pasien->nama_lengkap ?? '') }}" required placeholder="Nama sesuai KTP">
                        @error('nama_lengkap')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">NIK (16 Digit KTP) *</label>
                        <input type="text" name="nik" maxlength="16" class="form-control {{ $errors->has('nik') ? 'is-invalid' : '' }}"
                            value="{{ old('nik', $pasien->nik ?? '') }}" required placeholder="3511xxxxxxxxxxxx">
                        @error('nik')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tempat Lahir *</label>
                        <input type="text" name="tempat_lahir" class="form-control"
                            value="{{ old('tempat_lahir', $pasien->tempat_lahir ?? '') }}" required placeholder="Kota kelahiran">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tanggal Lahir *</label>
                        <input type="date" name="tanggal_lahir" class="form-control"
                            value="{{ old('tanggal_lahir', optional($pasien->tanggal_lahir)->format('Y-m-d') ?? '') }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Jenis Kelamin *</label>
                        <select name="jenis_kelamin" class="form-select" required>
                            <option value="L" {{ old('jenis_kelamin', $pasien->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin', $pasien->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Golongan Darah</label>
                        <select name="golongan_darah" class="form-select">
                            <option value="">-- Pilih Golongan Darah --</option>
                            @foreach(['A','B','AB','O'] as $gd)
                                <option value="{{ $gd }}" {{ old('golongan_darah', $pasien->golongan_darah ?? '') == $gd ? 'selected' : '' }}>Golongan Darah {{ $gd }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Agama</label>
                        <select name="agama" class="form-select">
                            <option value="">-- Pilih Agama --</option>
                            @foreach(['Islam','Kristen Protestan','Kristen Katolik','Hindu','Buddha','Konghucu'] as $ag)
                                <option value="{{ $ag }}" {{ old('agama', $pasien->agama ?? '') == $ag ? 'selected' : '' }}>{{ $ag }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status Pernikahan</label>
                        <select name="status_pernikahan" class="form-select">
                            <option value="">-- Pilih Status --</option>
                            @foreach(['Belum Menikah','Menikah','Cerai Hidup','Cerai Mati'] as $sp)
                                <option value="{{ $sp }}" {{ old('status_pernikahan', $pasien->status_pernikahan ?? '') == $sp ? 'selected' : '' }}>{{ $sp }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Pekerjaan</label>
                        <input type="text" name="pekerjaan" class="form-control"
                            value="{{ old('pekerjaan', $pasien->pekerjaan ?? '') }}" placeholder="Contoh: Wiraswasta, Karyawan Swasta, PNS">
                    </div>

                    <div class="form-group">
                        <label class="form-label">No. Telepon / WhatsApp *</label>
                        <input type="text" name="no_hp" class="form-control {{ $errors->has('no_hp') ? 'is-invalid' : '' }}"
                            value="{{ old('no_hp', $pasien->no_hp ?? '') }}" required placeholder="08xxxxxxxxxx">
                        @error('no_hp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group" style="grid-column:1/-1">
                        <label class="form-label">Email Pasien</label>
                        <input type="email" name="email" class="form-control"
                            value="{{ old('email', $pasien->email ?? '') }}" placeholder="nama@email.com">
                    </div>
                </div>

                <!-- 2. ALAMAT DOMISILI (DROPDOWN WILAYAH INDONESIA) -->
                <div class="section-divider">
                    <i class="fas fa-map-location-dot"></i> 2. Alamat Domisili Seluruh Indonesia
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:20px">
                    <!-- PROVINSI DULUAN -->
                    <div class="form-group">
                        <label class="form-label">Provinsi *</label>
                        <select name="provinsi" id="selectProvinsi" class="form-select" required onchange="onProvinsiChange()">
                            <option value="">-- Pilih Provinsi --</option>
                        </select>
                    </div>

                    <!-- KABUPATEN/KOTA -->
                    <div class="form-group">
                        <label class="form-label">Kabupaten / Kota *</label>
                        <select name="kabupaten" id="selectKabupaten" class="form-select" required onchange="onKabupatenChange()">
                            <option value="">-- Pilih Kabupaten/Kota --</option>
                        </select>
                    </div>

                    <!-- KECAMATAN -->
                    <div class="form-group">
                        <label class="form-label">Kecamatan *</label>
                        <select name="kecamatan" id="selectKecamatan" class="form-select" required>
                            <option value="">-- Pilih Kecamatan --</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Kode Pos</label>
                        <input type="text" name="kode_pos" class="form-control" value="{{ old('kode_pos', $pasien->kode_pos ?? '') }}" placeholder="Contoh: 68219">
                    </div>

                    <div class="form-group" style="grid-column:2/-1">
                        <label class="form-label">Alamat Lengkap (Jalan, RT/RW, No. Rumah) *</label>
                        <textarea name="alamat" class="form-control" rows="2" required placeholder="Jl. Mastrip No. 25, RT 01 RW 02">{{ old('alamat', $pasien->alamat ?? '') }}</textarea>
                    </div>
                </div>

                <!-- 3. PENANGGUNG JAWAB -->
                <div class="section-divider">
                    <i class="fas fa-user-shield"></i> 3. Penanggung Jawab / Kontak Darurat
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px">
                    <div class="form-group">
                        <label class="form-label">Nama Penanggung Jawab *</label>
                        <input type="text" name="nama_pj" class="form-control {{ $errors->has('nama_pj') ? 'is-invalid' : '' }}"
                            value="{{ old('nama_pj', $pasien->nama_pj ?? '') }}" required placeholder="Nama anggota keluarga / penanggung jawab">
                        @error('nama_pj')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Hubungan Keluarga *</label>
                        <select name="hubungan_pj" class="form-select" required>
                            <option value="">-- Pilih Hubungan --</option>
                            @foreach(['Suami','Istri','Orang Tua','Anak','Saudara','Kerabat','Lainnya'] as $hub)
                                <option value="{{ $hub }}" {{ old('hubungan_pj', $pasien->hubungan_pj ?? '') == $hub ? 'selected' : '' }}>{{ $hub }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">No. Telepon PJ *</label>
                        <input type="text" name="no_hp_pj" class="form-control {{ $errors->has('no_hp_pj') ? 'is-invalid' : '' }}"
                            value="{{ old('no_hp_pj', $pasien->no_hp_pj ?? '') }}" required placeholder="08xxxxxxxxxx">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Alamat Penanggung Jawab</label>
                        <input type="text" name="alamat_pj" class="form-control" value="{{ old('alamat_pj', $pasien->alamat_pj ?? '') }}" placeholder="Alamat penanggung jawab">
                    </div>
                </div>

                <div style="display:flex;gap:12px;align-items:center;margin-bottom:32px">
                    <button type="button" onclick="confirmSaveProfil()" class="btn btn-primary" style="padding:12px 24px">
                        <i class="fas fa-save" style="margin-right:6px"></i> Simpan Perubahan Profil
                    </button>
                </div>
            </form>

            <!-- 4. GANTI PASSWORD (DISATUKAN DI BAGIAN BAWAH SAMBUNGAN CARD PROFIL) -->
            <div class="section-divider" style="margin-top:36px;color:#0284c7">
                <i class="fas fa-key"></i> 4. Keamanan & Ganti Password Akun
            </div>

            @if($errors->has('password_lama') || $errors->has('password'))
            <div style="background:#fee2e2;border:1px solid #fecaca;border-radius:12px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#991b1b;display:flex;align-items:center;gap:10px">
                <i class="fas fa-exclamation-circle" style="font-size:16px"></i>
                <div>
                    @error('password_lama')<div>{{ $message }}</div>@enderror
                    @error('password')<div>{{ $message }}</div>@enderror
                </div>
            </div>
            @endif

            <form id="formUpdatePassword" action="{{ route('pasien.password.update') }}" method="POST">
                @csrf
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:18px;margin-bottom:24px">

                    <!-- Password Lama -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-lock" style="color:#94a3b8;font-size:11px"></i>
                            Password Saat Ini *
                        </label>
                        <div style="position:relative">
                            <input type="password" name="password_lama" id="pwdLama"
                                class="form-control input-pw {{ $errors->has('password_lama') ? 'is-invalid' : '' }}"
                                placeholder="Masukkan password lama" required>
                            <button type="button" class="pwd-toggle" onclick="togglePwd('pwdLama', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Password Baru -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-key" style="color:#94a3b8;font-size:11px"></i>
                            Password Baru *
                        </label>
                        <div style="position:relative">
                            <input type="password" name="password" id="pwdBaru"
                                class="form-control input-pw {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                placeholder="Min. 8 karakter" required oninput="checkStr(this.value)">
                            <button type="button" class="pwd-toggle" onclick="togglePwd('pwdBaru', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <!-- Strength bar -->
                        <div style="display:flex;gap:3px;margin-top:8px">
                            <div id="sb1" style="flex:1;height:4px;border-radius:4px;background:#e2e8f0;transition:background 0.3s"></div>
                            <div id="sb2" style="flex:1;height:4px;border-radius:4px;background:#e2e8f0;transition:background 0.3s"></div>
                            <div id="sb3" style="flex:1;height:4px;border-radius:4px;background:#e2e8f0;transition:background 0.3s"></div>
                        </div>
                        <div id="strLabel" style="font-size:11px;color:#94a3b8;margin-top:4px"></div>
                    </div>

                    <!-- Konfirmasi Password -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-check-circle" style="color:#94a3b8;font-size:11px"></i>
                            Konfirmasi Password Baru *
                        </label>
                        <div style="position:relative">
                            <input type="password" name="password_confirmation" id="pwdKonfirm"
                                class="form-control input-pw"
                                placeholder="Ulangi password baru" required oninput="checkMatch()">
                            <button type="button" class="pwd-toggle" onclick="togglePwd('pwdKonfirm', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div id="matchMsg" style="font-size:11px;margin-top:4px;font-weight:600"></div>
                    </div>
                </div>

                <div style="display:flex;align-items:center;gap:12px">
                    <button type="button" onclick="confirmSavePassword()" class="btn btn-primary" style="padding:12px 24px;background:#0284c7;border-color:#0284c7">
                        <i class="fas fa-shield-halved" style="margin-right:6px"></i> Perbarui Password Akun
                    </button>
                    <div style="font-size:12px;color:#64748b">
                        <i class="fas fa-info-circle" style="margin-right:4px"></i>
                        Gunakan kombinasi huruf, angka, dan karakter untuk keamanan optimal.
                    </div>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
// DATA WILAYAH SELURUH INDONESIA
const WILAYAH_INDONESIA = {
    "Jawa Timur": {
        "Bondowoso": ["Bondowoso", "Curahdami", "Tamanan", "Maesan", "Wringin", "Tanggul", "Tlogosari", "Prajekan", "Pujer", "Grujugan", "Klabang", "Cermee", "Wonosari", "Tepus", "Sukosari", "Jambesari Darus Sholah", "Sumber Wringin", "Botolinggo"],
        "Surabaya": ["Tegalsari", "Simokerto", "Genteng", "Bubutan", "Gubeng", "Gunung Anyar", "Sukololo", "Tambaksari", "Wonokromo", "Rungkut", "Wonocolo", "Wiyung", "Karangpilang", "Jambangan", "Gayungan", "Sawahan", "Driyorejo", "Benowo", "Pakal", "Asemrowo", "Sukomanunggal", "Tandes", "Krembangan", "Semampir", "Pabean Cantian", "Bulak", "Kenjeran", "Lakarsantri", "Sambikerep"],
        "Jember": ["Patrang", "Sumbersari", "Kaliwates", "Arjasa", "Jenggawah", "Ajung", "Rambipuji", "Bangsalsari", "Tanggul", "Semboro", "Puger", "Wuluhan", "Ambulu", "Balung"],
        "Banyuwangi": ["Banyuwangi", "Giri", "Glagah", "Kalipuro", "Licin", "Rogojampi", "Kabat", "Singojuruh", "Genteng", "Srono", "Cluring", "Gambiran"],
        "Malang": ["Klojen", "Blimbing", "Lowokwaru", "Sukun", "Kedungkandang", "Singosari", "Lawang", "Kepanjen", "Dau", "Batu"],
        "Sidoarjo": ["Sidoarjo", "Candi", "Tanggulangin", "Porong", "Krembung", "Tulangan", "Prambon", "Wonoayu", "Krian", "Balongbendo", "Taman", "Sukodono", "Waru", "Gedangan", "Sedati"]
    },
    "Jawa Barat": {
        "Bandung": ["Coblong", "Sukajadi", "Cicendo", "Andir", "Sumur Bandung", "Lengkong", "Regol", "Astanaanyar", "Bojongloa Kaler", "Cibeunying Kaler"],
        "Bogor": ["Bogor Tengah", "Bogor Utara", "Bogor Timur", "Bogor Selatan", "Bogor Barat", "Tanah Sareal", "Cibinong"],
        "Bekasi": ["Bekasi Barat", "Bekasi Timur", "Bekasi Utara", "Bekasi Selatan", "Rawalumbu", "Pondok Gede", "Tambun Selatan"],
        "Depok": ["Pancasoran Mas", "Cimanggis", "Sawangan", "Limo", "Sukmajaya", "Beji", "Cinere", "Tapos"]
    },
    "DKI Jakarta": {
        "Jakarta Selatan": ["Kebayoran Baru", "Kebayoran Lama", "Cilandak", "Pesanggrahan", "Pasar Minggu", "Jagakarsa", "Mampang Prapatan", "Pancoran", "Tebet", "Setiabudi"],
        "Jakarta Pusat": ["Gambir", "Tanah Abang", "Menteng", "Senen", "Cempaka Putih", "Johar Baru", "Kemayoran", "Sawah Besar"],
        "Jakarta Barat": ["Cengkareng", "Grogol Petamburan", "Kalideres", "Kebon Jeruk", "Kembangan", "Palmerah", "Taman Sari", "Tambora"],
        "Jakarta Timur": ["Matraman", "Pulo Gadung", "Jatinegara", "Duren Sawit", "Kramat Jati", "Makasar", "Ciracas", "Cipayung", "Pasar Rebo", "Cakung"],
        "Jakarta Utara": ["Penjaringan", "Pademangan", "Tanjung Priok", "Koja", "Cilincing", "Kelapa Gading"]
    },
    "Jawa Tengah": {
        "Semarang": ["Semarang Tengah", "Semarang Utara", "Semarang Timur", "Semarang Selatan", "Semarang Barat", "Candisari", "Gajahmungkur", "Pedurungan", "Banyumanik", "Gunungpati"],
        "Surakarta (Solo)": ["Banjarsari", "Jebres", "Laweyan", "Pasar Kliwon", "Serengan"],
        "Magelang": ["Magelang Utara", "Magelang Tengah", "Magelang Selatan", "Muntilan", "Mertoyudan"]
    },
    "DI Yogyakarta": {
        "Yogyakarta": ["Gondomanan", "Danurejan", "Gedongtengen", "Ngampil", "Wirobrajan", "Mantrijeron", "Kraton", "Mergangsan", "Umbulharjo", "Kotagede", "Gondokusuman", "Jetis", "Tegalrejo"],
        "Sleman": ["Depok", "Mlati", "Gamping", "Kalasan", "Ngaglik", "Sleman"],
        "Bantul": ["Bantul", "Banguntapan", "Sewon", "Kasihan", "Piyungan"]
    },
    "Bali": {
        "Denpasar": ["Denpasar Selatan", "Denpasar Timur", "Denpasar Barat", "Denpasar Utara"],
        "Badung": ["Kuta", "Kuta Utara", "Kuta Selatan", "Mengwi", "Abiansemal"],
        "Gianyar": ["Ubud", "Gianyar", "Sukawati", "Blahbatuh"]
    },
    "Sumatera Utara": {
        "Medan": ["Medan Kota", "Medan Baru", "Medan Helvetia", "Medan Petisah", "Medan Sunggal", "Medan Johor", "Medan Tembung"],
        "Deli Serdang": ["Lubuk Pakam", "Tanjung Morawa", "Percut Sei Tuan", "Sunggal"]
    },
    "Sumatera Barat": {
        "Padang": ["Padang Barat", "Padang Timur", "Padang Selatan", "Padang Utara", "Koto Tangah", "Kuranji"],
        "Bukittinggi": ["Guguk Panjang", "Mandiangin Koto Selayan", "Aur Birugo Tigo Baleh"]
    },
    "Riau": {
        "Pekanbaru": ["Marpoyan Damai", "Tampan", "Payung Sekaki", "Bukit Raya", "Tenayan Raya", "Rumbai"]
    },
    "Kepulauan Riau": {
        "Batam": ["Batam Kota", "Lubuk Baja", "Batu Ampar", "Nongsa", "Sekupang", "Bengkong"]
    },
    "Lampung": {
        "Bandar Lampung": ["Tanjung Karang Pusat", "Tanjung Karang Barat", "Kedaton", "Rajabasa", "Sukarame", "Teluk Betung Utara"]
    },
    "South Sulawesi / Sulawesi Selatan": {
        "Makassar": ["Ujung Pandang", "Panakkukang", "Rappocini", "Tamalanrea", "Biringkanaya", "Mariso", "Mamajang"]
    }
};

const PROVINSI_ALL = [
    "Aceh", "Sumatera Utara", "Sumatera Barat", "Riau", "Kepulauan Riau", "Jambi", "Sumatera Selatan", "Bangka Belitung", "Bengkulu", "Lampung",
    "DKI Jakarta", "Jawa Barat", "Banten", "Jawa Tengah", "DI Yogyakarta", "Jawa Timur", "Bali", "Nusa Tenggara Barat", "Nusa Tenggara Timur",
    "Kalimantan Barat", "Kalimantan Tengah", "Kalimantan Selatan", "Kalimantan Timur", "Kalimantan Utara",
    "Sulawesi Utara", "Gorontalo", "Sulawesi Tengah", "Sulawesi Barat", "Sulawesi Selatan", "Sulawesi Tenggara",
    "Maluku", "Maluku Utara", "Papua", "Papua Barat", "Papua Selatan", "Papua Tengah", "Papua Pegunungan", "Papua Barat Daya"
];

// Current values from database
const currentProv = "{{ old('provinsi', $pasien->provinsi ?? 'Jawa Timur') }}";
const currentKab  = "{{ old('kabupaten', $pasien->kabupaten ?? 'Bondowoso') }}";
const currentKec  = "{{ old('kecamatan', $pasien->kecamatan ?? 'Bondowoso') }}";

document.addEventListener('DOMContentLoaded', function() {
    initWilayahDropdowns();
});

function initWilayahDropdowns() {
    const provSelect = document.getElementById('selectProvinsi');
    provSelect.innerHTML = '<option value="">-- Pilih Provinsi --</option>';

    PROVINSI_ALL.forEach(prov => {
        const opt = document.createElement('option');
        opt.value = prov;
        opt.textContent = prov;
        if (prov === currentProv) opt.selected = true;
        provSelect.appendChild(opt);
    });

    onProvinsiChange(currentKab, currentKec);
}

function onProvinsiChange(setKab = null, setKec = null) {
    const provVal = document.getElementById('selectProvinsi').value;
    const kabSelect = document.getElementById('selectKabupaten');
    const kecSelect = document.getElementById('selectKecamatan');

    kabSelect.innerHTML = '<option value="">-- Pilih Kabupaten/Kota --</option>';
    kecSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';

    if (!provVal) return;

    const kabsData = WILAYAH_INDONESIA[provVal];

    if (kabsData) {
        Object.keys(kabsData).forEach(kab => {
            const opt = document.createElement('option');
            opt.value = kab;
            opt.textContent = kab;
            if (setKab && kab === setKab) opt.selected = true;
            else if (!setKab && kab === currentKab) opt.selected = true;
            kabSelect.appendChild(opt);
        });
    } else {
        // Fallback option
        const defaultKabs = ["Kota / Kabupaten Utama", "Lainnya"];
        defaultKabs.forEach(kab => {
            const opt = document.createElement('option');
            opt.value = kab;
            opt.textContent = kab;
            if (setKab && kab === setKab) opt.selected = true;
            kabSelect.appendChild(opt);
        });
    }

    if (setKab) {
        kabSelect.value = setKab;
    } else if (currentKab && kabSelect.querySelector(`option[value="${currentKab}"]`)) {
        kabSelect.value = currentKab;
    }

    onKabupatenChange(setKec);
}

function onKabupatenChange(setKec = null) {
    const provVal = document.getElementById('selectProvinsi').value;
    const kabVal  = document.getElementById('selectKabupaten').value;
    const kecSelect = document.getElementById('selectKecamatan');

    kecSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';

    if (!provVal || !kabVal) return;

    let kecsList = [];
    if (WILAYAH_INDONESIA[provVal] && WILAYAH_INDONESIA[provVal][kabVal]) {
        kecsList = WILAYAH_INDONESIA[provVal][kabVal];
    }

    if (kecsList.length > 0) {
        kecsList.forEach(kec => {
            const opt = document.createElement('option');
            opt.value = kec;
            opt.textContent = kec;
            if (setKec && kec === setKec) opt.selected = true;
            else if (!setKec && kec === currentKec) opt.selected = true;
            kecSelect.appendChild(opt);
        });
    } else {
        // Fallback option if custom city selected
        const defaultKecs = [currentKec || "Kecamatan Utama", "Kecamatan Lainnya"];
        defaultKecs.forEach(kec => {
            const opt = document.createElement('option');
            opt.value = kec;
            opt.textContent = kec;
            if (setKec && kec === setKec) opt.selected = true;
            kecSelect.appendChild(opt);
        });
    }

    if (setKec) {
        kecSelect.value = setKec;
    } else if (currentKec && kecSelect.querySelector(`option[value="${currentKec}"]`)) {
        kecSelect.value = currentKec;
    }
}

// SWEETALERT CONFIRMATION FOR PROFIL UPDATE
function confirmSaveProfil() {
    const form = document.getElementById('formUpdateProfil');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    Swal.fire({
        title: 'Simpan Perubahan Profil?',
        text: 'Apakah Anda yakin data diri dan alamat yang Anda masukkan sudah benar?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#0891b2',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: '<i class="fas fa-check-circle"></i> Ya, Simpan',
        cancelButtonText: 'Batal',
        customClass: {
            popup: 'rounded-20'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Menyimpan Data...',
                text: 'Harap tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });
            form.submit();
        }
    });
}

// SWEETALERT CONFIRMATION FOR PASSWORD UPDATE
function confirmSavePassword() {
    const form = document.getElementById('formUpdatePassword');
    const pwdBaru = document.getElementById('pwdBaru').value;
    const pwdKonfirm = document.getElementById('pwdKonfirm').value;

    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    if (pwdBaru !== pwdKonfirm) {
        Swal.fire({
            title: 'Password Tidak Cocok',
            text: 'Konfirmasi password baru belum sesuai dengan password baru.',
            icon: 'error',
            confirmButtonColor: '#0891b2'
        });
        return;
    }

    Swal.fire({
        title: 'Perbarui Password Akun?',
        text: 'Password akun Anda akan diubah. Anda harus menggunakan password baru saat login berikutnya.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#0284c7',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: '<i class="fas fa-key"></i> Ya, Perbarui Password',
        cancelButtonText: 'Batal',
        customClass: {
            popup: 'rounded-20'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Memperbarui Password...',
                text: 'Harap tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });
            form.submit();
        }
    });
}

// TOGGLE PASSWORD VISIBILITY
function togglePwd(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon  = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fas fa-eye-slash';
        btn.style.color = '#0891b2';
    } else {
        input.type = 'password';
        icon.className = 'fas fa-eye';
        btn.style.color = '#94a3b8';
    }
}

// PASSWORD STRENGTH CHECKER
function checkStr(val) {
    const bars   = ['sb1','sb2','sb3'];
    const colors = { weak:'#dc2626', medium:'#f59e0b', strong:'#059669' };
    const label  = document.getElementById('strLabel');
    bars.forEach(b => { document.getElementById(b).style.background = '#e2e8f0'; });

    if (val.length === 0) { label.textContent = ''; return; }

    let strength = 0;
    if (val.length >= 8) strength++;
    if (/[A-Z]/.test(val) && /[a-z]/.test(val)) strength++;
    if (/[0-9]/.test(val) && /[^A-Za-z0-9]/.test(val)) strength++;

    const map = [
        { bars:1, color: colors.weak,   text:'Kekuatan Password: Lemah',  textColor:'#dc2626' },
        { bars:2, color: colors.medium, text:'Kekuatan Password: Cukup',  textColor:'#d97706' },
        { bars:3, color: colors.strong, text:'Kekuatan Password: Sangat Kuat', textColor:'#059669' },
    ];
    const s = map[Math.min(strength, 2)];
    for (let i = 0; i < s.bars; i++) {
        document.getElementById(bars[i]).style.background = s.color;
    }
    label.textContent  = s.text;
    label.style.color  = s.textColor;
    label.style.fontWeight = '700';
}

function checkMatch() {
    const baru    = document.getElementById('pwdBaru').value;
    const konfirm = document.getElementById('pwdKonfirm').value;
    const msg     = document.getElementById('matchMsg');
    if (!konfirm) { msg.textContent = ''; return; }
    if (baru === konfirm) {
        msg.textContent = '✓ Password cocok';
        msg.style.color = '#059669';
    } else {
        msg.textContent = '✗ Password belum cocok';
        msg.style.color = '#dc2626';
    }
}
</script>
@endpush
