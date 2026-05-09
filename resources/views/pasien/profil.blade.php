@extends('layouts.app')
@section('title', 'Profil Saya - RS Cahya Medika')
@section('page-title', 'Profil Saya')

@section('content')
<div style="max-width:860px;margin:0 auto">

<div class="grid grid-2" style="gap:24px;align-items:start">

<!-- FORM DATA DIRI -->
<div style="grid-column:1/-1">
<div class="card">
<div class="card-header">
    <div class="card-title">👤 Data Diri Pasien</div>
    @if($pasien && $pasien->satusehat_id)
        <span class="badge badge-success"><i class="fas fa-check"></i> Sinkron SatuSehat</span>
    @else
        <span class="badge badge-warning">⚠️ Belum Sinkron</span>
    @endif
</div>
<div class="card-body">
<form action="{{ route('pasien.profil.update') }}" method="POST">
@csrf @method('PUT')

<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
    <div class="form-group">
        <label class="form-label">Nama Lengkap *</label>
        <input type="text" name="nama_lengkap" class="form-control {{ $errors->has('nama_lengkap') ? 'is-invalid' : '' }}"
            value="{{ old('nama_lengkap', $pasien->nama_lengkap ?? '') }}" required>
        @error('nama_lengkap')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="form-group">
        <label class="form-label">NIK (16 digit) *</label>
        <input type="text" name="nik" maxlength="16" class="form-control {{ $errors->has('nik') ? 'is-invalid' : '' }}"
            value="{{ old('nik', $pasien->nik ?? '') }}" required>
        @error('nik')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="form-group">
        <label class="form-label">Tempat Lahir *</label>
        <input type="text" name="tempat_lahir" class="form-control"
            value="{{ old('tempat_lahir', $pasien->tempat_lahir ?? '') }}" required>
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
            <option value="">-- Pilih --</option>
            @foreach(['A','B','AB','O'] as $gd)
                <option value="{{ $gd }}" {{ old('golongan_darah', $pasien->golongan_darah ?? '') == $gd ? 'selected' : '' }}>{{ $gd }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Agama</label>
        <select name="agama" class="form-select">
            <option value="">-- Pilih --</option>
            @foreach(['Islam','Kristen Protestan','Kristen Katolik','Hindu','Buddha','Konghucu'] as $ag)
                <option value="{{ $ag }}" {{ old('agama', $pasien->agama ?? '') == $ag ? 'selected' : '' }}>{{ $ag }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Status Pernikahan</label>
        <select name="status_pernikahan" class="form-select">
            <option value="">-- Pilih --</option>
            @foreach(['Belum Menikah','Menikah','Cerai Hidup','Cerai Mati'] as $sp)
                <option value="{{ $sp }}" {{ old('status_pernikahan', $pasien->status_pernikahan ?? '') == $sp ? 'selected' : '' }}>{{ $sp }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Pekerjaan</label>
        <input type="text" name="pekerjaan" class="form-control"
            value="{{ old('pekerjaan', $pasien->pekerjaan ?? '') }}" placeholder="Wiraswasta, PNS, dll">
    </div>
    <div class="form-group">
        <label class="form-label">No. HP *</label>
        <input type="text" name="no_hp" class="form-control {{ $errors->has('no_hp') ? 'is-invalid' : '' }}"
            value="{{ old('no_hp', $pasien->no_hp ?? '') }}" required>
        @error('no_hp')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="form-group">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control"
            value="{{ old('email', $pasien->email ?? '') }}">
    </div>
</div>

<!-- ALAMAT -->
<div style="padding:16px;background:#f8fafc;border-radius:12px;margin-bottom:16px">
    <div style="font-size:12px;font-weight:700;color:#0c4a6e;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:12px">📍 Alamat Domisili</div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
        <div class="form-group" style="grid-column:1/-1">
            <label class="form-label">Alamat Lengkap *</label>
            <textarea name="alamat" class="form-control" rows="2" required>{{ old('alamat', $pasien->alamat ?? '') }}</textarea>
        </div>
        <div class="form-group">
            <label class="form-label">Kecamatan *</label>
            <input type="text" name="kecamatan" class="form-control" value="{{ old('kecamatan', $pasien->kecamatan ?? '') }}" required>
        </div>
        <div class="form-group">
            <label class="form-label">Kabupaten/Kota *</label>
            <input type="text" name="kabupaten" class="form-control" value="{{ old('kabupaten', $pasien->kabupaten ?? 'Bondowoso') }}" required>
        </div>
        <div class="form-group">
            <label class="form-label">Provinsi *</label>
            <input type="text" name="provinsi" class="form-control" value="{{ old('provinsi', $pasien->provinsi ?? 'Jawa Timur') }}" required>
        </div>
        <div class="form-group">
            <label class="form-label">Kode Pos</label>
            <input type="text" name="kode_pos" class="form-control" value="{{ old('kode_pos', $pasien->kode_pos ?? '') }}">
        </div>
        {{-- Kode Wilayah untuk integrasi SatuSehat --}}
        <div style="grid-column:1/-1;background:#fffbeb;border:1px solid #fde68a;border-radius:10px;padding:12px 14px;margin-top:4px">
            <div style="font-size:11px;font-weight:700;color:#92400e;margin-bottom:6px">
                🔗 Kode Wilayah BPS — untuk Integrasi SatuSehat
                <a href="https://sig.bps.go.id/basisdata/index" target="_blank" style="font-weight:400;font-size:10px;margin-left:8px;color:#0891b2">Cari kode BPS →</a>
            </div>
            <div style="font-size:10px;color:#b45309;margin-bottom:10px">
                ⚠️ Staging SatuSehat hanya mendukung kode kota besar (Surabaya, Jakarta, dll). Default diisi Surabaya untuk testing. Ganti ke kode asli saat production.
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                <div>
                    <label class="form-label" style="font-size:10px">Kode Provinsi (2 digit)</label>
                    <input type="text" name="kode_provinsi" maxlength="2" class="form-control" style="font-family:monospace"
                        value="{{ old('kode_provinsi', $pasien->kode_provinsi ?? '35') }}" placeholder="35 = Jawa Timur">
                </div>
                <div>
                    <label class="form-label" style="font-size:10px">Kode Kabupaten/Kota (4 digit)</label>
                    <input type="text" name="kode_kabupaten" maxlength="4" class="form-control" style="font-family:monospace"
                        value="{{ old('kode_kabupaten', $pasien->kode_kabupaten ?? '3578') }}" placeholder="3578 = Kota Surabaya">
                </div>
                <div>
                    <label class="form-label" style="font-size:10px">Kode Kecamatan (6 digit)</label>
                    <input type="text" name="kode_kecamatan" maxlength="6" class="form-control" style="font-family:monospace"
                        value="{{ old('kode_kecamatan', $pasien->kode_kecamatan ?? '357801') }}" placeholder="357801">
                </div>
                <div>
                    <label class="form-label" style="font-size:10px">Kode Kelurahan/Desa (10 digit)</label>
                    <input type="text" name="kode_kelurahan" maxlength="10" class="form-control" style="font-family:monospace"
                        value="{{ old('kode_kelurahan', $pasien->kode_kelurahan ?? '3578011001') }}" placeholder="3578011001">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- PENANGGUNG JAWAB -->
<div style="padding:16px;background:#f8fafc;border-radius:12px;margin-bottom:20px">
    <div style="font-size:12px;font-weight:700;color:#0c4a6e;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:12px">🆘 Penanggung Jawab / Kontak Darurat</div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
        <div class="form-group">
            <label class="form-label">Nama PJ *</label>
            <input type="text" name="nama_pj" class="form-control {{ $errors->has('nama_pj') ? 'is-invalid' : '' }}"
                value="{{ old('nama_pj', $pasien->nama_pj ?? '') }}" required>
            @error('nama_pj')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label class="form-label">Hubungan *</label>
            <select name="hubungan_pj" class="form-select" required>
                <option value="">-- Pilih --</option>
                @foreach(['Suami','Istri','Orang Tua','Anak','Saudara','Kerabat','Lainnya'] as $hub)
                    <option value="{{ $hub }}" {{ old('hubungan_pj', $pasien->hubungan_pj ?? '') == $hub ? 'selected' : '' }}>{{ $hub }}</option>
                @endforeach
            </select>
            @error('hubungan_pj')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label class="form-label">No. HP PJ *</label>
            <input type="text" name="no_hp_pj" class="form-control {{ $errors->has('no_hp_pj') ? 'is-invalid' : '' }}"
                value="{{ old('no_hp_pj', $pasien->no_hp_pj ?? '') }}" required>
            @error('no_hp_pj')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label class="form-label">Alamat PJ</label>
            <input type="text" name="alamat_pj" class="form-control"
                value="{{ old('alamat_pj', $pasien->alamat_pj ?? '') }}">
        </div>
    </div>
</div>

<div style="display:flex;gap:12px">
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-save"></i> Simpan Perubahan
    </button>
    @if($pasien && !$pasien->satusehat_id)
    <div style="padding:10px 16px;background:#fffbeb;border:1px solid #fde68a;border-radius:10px;font-size:12px;color:#92400e;display:flex;align-items:center;gap:6px">
        <i class="fas fa-info-circle"></i> Data akan otomatis dikirim ke SatuSehat saat disimpan
    </div>
    @endif
</div>
</form>
</div>
</div>
</div>

<!-- GANTI PASSWORD -->
<div style="margin-top:24px">
<div class="card">
<div class="card-header">
    <div class="card-title">🔐 Ganti Password</div>
</div>
<div class="card-body">
<form action="{{ route('pasien.password.update') }}" method="POST">
@csrf
<div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px">
    <div class="form-group">
        <label class="form-label">Password Lama *</label>
        <input type="password" name="password_lama" class="form-control {{ $errors->has('password_lama') ? 'is-invalid' : '' }}" required>
        @error('password_lama')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="form-group">
        <label class="form-label">Password Baru *</label>
        <input type="password" name="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" required>
        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="form-group">
        <label class="form-label">Konfirmasi Password *</label>
        <input type="password" name="password_confirmation" class="form-control" required>
    </div>
</div>
<button type="submit" class="btn btn-outline">
    <i class="fas fa-key"></i> Update Password
</button>
</form>
</div>
</div>
</div>

</div>
@endsection
